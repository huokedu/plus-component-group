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
use Zhiyi\Component\ZhiyiPlus\PlusComponentGroup\Models\GroupPostCollection as GroupPostCollectionModel;


class GroupPostCollectionController extends Controller
{	

	public function myCollections(Request $request)
	{
		
		$user = $request->user('api')->id;
		$limit = $request->query('limit', 15);
		$after = $request->query('after');

		$collections = GroupPostCollectionModel::where(function ($query) use ($after) {
				if(! $after) {
					return;
				}
				$query->where('id', '<', $after);
			})
			->where('user_id', $user)
			->limit($limit)
			->orderBy('id', 'desc')
			->get();

		return response()->json($collections)->setStatusCode(200);
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

        if ($post->collections()->where('user_id', $user)->first()) {
            abort(422, '已收藏过该动态');
        }

       	$post->collections()->create(['user_id' => $user]);
       	$post->increment('collections', 1);

        return response()->json(['message' => '收藏成功'])->setStatusCode(201);
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
        $digg = $post->collections()->where('user_id', $user)->first();
        if (! $digg) {
            abort(422, '未收藏该动态');
        }

        $post->collections()->where('user_id', $user)->delete();
		$post->increment('collections', 1);

        abort(204);
	}
}
