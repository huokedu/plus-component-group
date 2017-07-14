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
	public function posts(Request $request, GroupModel $group)
	{

		$limit = $request->query('limit', 15);
		$after = $request->query('after');
		
		$posts = $group->posts()
			->where(function ($query) use ($after) {
				if(!$after) {
					return;
				}
				$query->where('id', '<', $after);
			})
			->limit($limit)
			->orderBy('id', 'desc')
			->get();

		return response()->json([
			'message' => '获取成功',
			'data' => $posts
		])->setStatusCode(200);
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

		$user = $request->user('api')->id ?? 0;
		$post->increment('views');

		// $post
		// 	->load([
		// 		'images',
		// 		'comments' => function ($query) {
		// 			$query->limit(15)
		// 				->orderBy('id', 'desc');
		// 		},
		// 		'diggs' => function ($query) {
		// 			$query->limit(8)
		// 				->orderBy('id', 'desc');
		// 		}
		// 	]);

		$post->is_collection = GroupPostCollectionModel::where(['post_id' => $post->id, 'user_id' => $user])->count();
		$post->is_digg = GroupPostDiggModel::where(['post_id' => $post, 'user_id' => $user])->count();

		return response()->json(['message' => '获取成功', 'data' => $post])->setStatusCode(200);
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

		$data = $request->only(['title', 'content']);
		$user = $request->user('api')->id;

		$post = new GroupPostModel();
		$post->title = $data['title'];
		$post->content = $data['content'];
		$post->group_id = $group->id;
		$post->user_id = $user;

		try {
			$group->getConnection()->transaction(function() use ($group, $fileWiths, $post) {
				$post->save();
				$fileWiths && $this->saveGroupFileWith($fileWiths, $post);
				$group->increment('posts_count');
			});
		} catch (\Exception $e) {
			throw $e;	
		}
		
		return response()->json(['message' => '创建成功', 'id' => $post->id])->setStatusCode(201);
	}

	public function destory(Request $request, GroupModel $group, GroupPostModel $post)
	{
		if(!$group->is_audit) {
			abort(404, '圈子不存在或未通过审核');
		}

		$user = $request->user('api')->id;

		if($post->user_id !== $user) {
			abort(401);
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