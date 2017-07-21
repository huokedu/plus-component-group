<?php

namespace Zhiyi\Component\ZhiyiPlus\PlusComponentGroup\API2;

use DB;
use Zhiyi\Plus\Models\Digg;
use Illuminate\Http\Request;
use Zhiyi\Plus\Jobs\PushMessage;
use Illuminate\Database\QueryException;
use Zhiyi\Plus\Http\Controllers\Controller;
use Zhiyi\Component\ZhiyiPlus\PlusComponentGroup\Models\Group as GroupModel;
use Zhiyi\Component\ZhiyiPlus\PlusComponentGroup\Models\GroupPost as GroupPostModel;
use Zhiyi\Component\ZhiyiPlus\PlusComponentGroup\Models\GroupPostDigg as GroupPostDiggModel;


class GroupPostDiggController extends Controller
{	

	public function diggs(Request $request, GroupModel $group, GroupPostModel $post)
	{
		if(! $group->is_audit) {
			abort(404, '圈子不存在或未通过审核');
		}

		if(! $post->is_audit) {
			abort(404, '动态不存在或未通过审核');
		}

		$limit = $request->query('limit', 15);
		$after = $request->query('after');

		$diggs = $post->diggs()
			->where(function ($query) use ($after) {
				if(! $after) {
					return;
				}
				$query->where('id', '<', $after);
			})
			->limit($limit)
			->orderBy('id', 'desc')
			->get();

		return response()->json($diggs)->setStatusCode(200);
	}

	/**
	 * digg post
	 * @param  Request
	 * @param  GroupModel     $group   
	 * @param  GroupPostModel $post    
	 * @return response                  
	 */
	public function store(Request $request, GroupModel $group, GroupPostModel $post)
	{
		if(! $group->is_audit) {
			abort(404, '圈子不存在或未通过审核');
		}

		if(! $post->is_audit) {
			abort(404, '动态不存在或未通过审核');
		}

		$user = $request->user('api')->id;

        if ($post->diggs()->where('user_id', $user)->first()) {
           abort(422, '已赞过该动态');
        }

        DB::beginTransaction();

        try {
            $digg = $post->diggs()->create(['user_id' => $user]);

            $post->increment('diggs'); //增加点赞数量

            Digg::create([
            	'component' => 'group',
                'digg_table' => 'group_post_diggs',
                'digg_id' => $digg->id,
                'source_table' => 'group_posts',
                'source_id' => $post->id,
                'source_content' => $post->title,
                'source_cover' => 0,
                'user_id' => $user,
                'to_user_id' => $post->user_id,
            ]); // 统计到点赞总表

            DB::commit();
        } catch (QueryException $e) {
            DB::rollBack();

            return response()->json([
                'message' => [$e->errorInfo],
            ])->setStatusCode(500);
        }

        $extras = ['action' => 'digg'];
        $alert = '有人赞了你的动态，去看看吧';
        $alias = $post->user_id;

        dispatch(new PushMessage($alert, (string) $alias, $extras));

        abort(201, '点赞成功');
	}

	public function destory(Request $request, GroupModel $group, GroupPostModel $post)
	{	
		if(! $group->is_audit) {
			abort(404, '圈子不存在或未通过审核');
		}

		if(! $post->is_audit) {
			abort(404, '动态不存在或未通过审核');
		}

		$user = $request->user('api')->id;
        $digg = $post->diggs()->where('user_id', $user)->first();
        if (! $digg) {
            return response()->json([
                'message' => ['未对该动态点赞'],
            ])->setStatusCode(422);
        }

        DB::transaction(function () use ($digg, $post, $user) {
            $digg->delete();
            $post->decrement('diggs'); //减少点赞数量

            Digg::where(['component' => 'group', 'digg_id' => $digg->id])->delete(); // 统计到点赞总表
        });

        abort(204);
	}
}
