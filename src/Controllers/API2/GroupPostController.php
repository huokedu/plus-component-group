<?php

namespace Zhiyi\Component\ZhiyiPlus\PlusComponentGroup\API2;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Zhiyi\Plus\Http\Controllers\Controller;
use Zhiyi\Plus\Models\FileWith as FileWithModel;
use Illuminate\Contracts\Routing\ResponseFactory as ResponseContract;
use Zhiyi\Component\ZhiyiPlus\PlusComponentGroup\Models\Group as GroupModel;
use Zhiyi\Component\ZhiyiPlus\PlusComponentGroup\Models\GroupPostDigg as GroupPostDiggModel;
use Zhiyi\Component\ZhiyiPlus\PlusComponentGroup\Models\GroupPostCollection as GroupPostCollectionModel;
use Zhiyi\Component\ZhiyiPlus\PlusComponentGroup\Models\GroupPost as GroupPostModel;
use Zhiyi\Component\ZhiyiPlus\PlusComponentGroup\FormRequest\API2\StorePost as StorePostRequest;

class GroupPostController extends Controller
{
	/**
	 *  posts of a group
	 *  @author Wayne < qiaobinloverabbi@gmail.com >
	 *  @param  Request    $request [description]
	 *  @param  GroupModel $group   [description]
	 *  @return [type]
	 */
	public function posts(Request $request, GroupModel $group)
	{
		$limit = $request->query('limit', 15);
		$after = $request->query('after');
		$user = $request->user('api');

		$posts = $group->posts()
			->when($after, function ($query) use ($after) {
				$query->where('id', '<', $after);
			})
			->limit($limit)
			->orderBy('id', 'desc')
			->get();

		$posts->map( function ($post) use ($user) {
			$post->load(['comments' => function ($query) {
				$query->orderBy('id', 'desc')
					->limit(5);
			}]);
			
			$post->has_collection = $user ? GroupPostCollectionModel::where(['post_id' => $post->id, 'user_id' => $user->id])->first() !== null : false;
			$post->has_like = $post->liked($user);
			return $post;
		});

		return response()->json($posts)->setStatusCode(200);
	}

	/**
	 * 获取圈子动态详情
	 * @param  Request $request 
	 * @param  int     $group   圈子id
	 * @param  int     $post    动态id
	 * @return response           
	 */
	public function show(Request $request, GroupModel $group, GroupPostModel $post)
	{
		if(!$group->is_audit) {
			abort(404, '圈子不存在或未通过审核');
		}

		if(!$post->is_audit) {
			abort(404, '动态不存在或未通过审核');
		}
		$user = $request->user('api');
		$user_id = $user->id ?? 0;
		$post->increment('views');

		$post->has_collection = $user_id ? GroupPostCollectionModel::where(['post_id' => $post->id, 'user_id' => $user_id])->count() !== 0 : false;
		$post->has_like = $post->liked($user);

		return response()->json($post)->setStatusCode(200);
	}

	/**
	 * 创建圈子动态
	 * @param  Request $request 
	 * @param  int     $group   圈子id
	 * @return response 
	 */
	public function store(StorePostRequest $request, GroupModel $group)
	{

		$fileWiths = $this->makeFileWith($request);

		$data = $request->only(['title', 'content', 'group_post_mark']);
		$user = $request->user('api')->id;

		$post = new GroupPostModel();
		$post->title = $data['title'];
		$post->content = $data['content'];
		$post->group_id = $group->id;
		$post->user_id = $user;
		$post->group_post_mark = $data['group_post_mark'];
		$post->is_audit = 1;

		try {
			$group->getConnection()->transaction(function() use ($group, $fileWiths, $post) {
				$post->save();
				$fileWiths && $this->saveGroupFileWith($fileWiths, $post);
				$group->increment('posts_count');
			});
		} catch (\Exception $e) {
			throw $e;	
		}
		
		return response()->json(['message' => '创建成功', 'id' => $post->id, 'group_post_mark' => $post->group_post_mark ])->setStatusCode(201);
	}

	public function destroy(Request $request, GroupModel $group, GroupPostModel $post)
	{
		if(!$group->is_audit) {
			abort(404, '圈子不存在或未通过审核');
		}

		$user = $request->user('api')->id;

		if($post->user_id !== $user) {
			abort(422, '没有删除权限');
		}
		// 假删除
		try {
			$post->getConnection()->transaction(function() use ($post, $group) {
				$post->delete();
				$group->decrement('posts_count');
			});
		} catch (\Exception $e) {
			throw $e;
		}

		abort(204);
	}

	/**
	 * save the cover of group
	 * @param  [type]     $fileWithCover [description]
	 * @param  GroupModel $group         [description]
	 * @return [type]                    [description]
	 */
	protected function saveGroupFileWith($fileWiths, GroupPostModel $post)
	{
		foreach ($fileWiths as $fileWith) {
			$fileWith->channel = 'post:image';
			$fileWith->raw = $post->id;
			$fileWith->save();
		}
	}

	/**
     * 创建文件使用模型.
     *
     * @param StoreFeedPostRequest $request
     * @return mixed
     * @author Seven Du <shiweidu@outlook.com>
     */
    protected function makeFileWith(StorePostRequest $request)
    {
        return FileWithModel::whereIn(
            'id',
            collect($request->input('images'))->filter(function (array $item) {
                return isset($item['id']);
            })->map(function (array $item) {
                return $item['id'];
            })->values()
        )->where('channel', null)
        ->where('raw', null)
        ->where('user_id', $request->user('api')->id)
        ->get();
    }
}