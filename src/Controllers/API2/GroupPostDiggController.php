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

		$diggs = $post->likes()
			->when($after, function ($query) use ($after) {
				$query->where('id', '<', $after);
			})
			->with(['user'])
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

		$user = $request->user();

        if ($post->likes()->where('user_id', $user->id)->first()) {
           abort(422, '已赞过该动态');
        }

        $post->getConnection()->transaction( function() use ($post, $user) {
        	$post->likes()->create([
        		'user_id' => $user->id,
        		'target_user' => $post->user_id
        	]);
        	$post->increment('diggs');
        	$post->user->extra()->firstOrCreate([])->increment('likes_count', 1);

            // 发送用户通知
            $user->sendNotifyMessage('group-post:digg', sprintf('%s 点赞了你的圈子动态', $user->name), [
                'user' => $user,
            ]);
        });

        return response()->json(['message' => '点赞成功'])->setStatusCode(201);
	}

	public function destory(Request $request, GroupModel $group, GroupPostModel $post)
	{	
		if(! $group->is_audit) {
			abort(404, '圈子不存在或未通过审核');
		}

		if(! $post->is_audit) {
			abort(404, '动态不存在或未通过审核');
		}

		$user = $request->user('api');

        $digg = $post->likes()->where('user_id', $user->id)->first();
        if (! $digg) {
            abort(422, '未对该动态点赞');
        }

        $post->getConnection()->transaction(function () use ($digg, $post, $user) {
            $digg->delete();
            $post->decrement('diggs'); //减少点赞数量
            $post->user->extra()->decrement('likes_count');
        });

        return response()->json()->setStatusCode(204);
	}
}
