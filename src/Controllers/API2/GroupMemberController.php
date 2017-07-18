<?php

namespace Zhiyi\Component\ZhiyiPlus\PlusComponentGroup\API2;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Zhiyi\Plus\Http\Controllers\Controller;
use Zhiyi\Plus\Models\FileWith as FileWithModel;
use Illuminate\Contracts\Routing\ResponseFactory as ResponseContract;
use Zhiyi\Component\ZhiyiPlus\PlusComponentGroup\Models\Group as GroupModel;
use Zhiyi\Component\ZhiyiPlus\PlusComponentGroup\Models\GroupManager as GroupManagerModel;
use Zhiyi\Component\ZhiyiPlus\PlusComponentGroup\Models\GroupMember as GroupMemberModel;
use Zhiyi\Component\ZhiyiPlus\PlusComponentGroup\Models\GroupCount as GroupCountModel;
use Zhiyi\Component\ZhiyiPlus\PlusComponentGroup\FormRequest\API2\StoreGroup as StoreGroupRequest;

class GroupMemberController extends Controller
{	
	/**
	 * 圈子成员列表
	 * @param  Request $request [description]
	 * @param  int     $group   [description]
	 * @return [type]           [description]
	 */
	public function members(Request $request, GroupModel $group)
	{
		if(!$group->is_audit) {
			abort(404);
		}

		$limit = $request->query('limit', 15);
		$before = $request->query('before');

		$members = GroupMemberModel::where('group_id', '=', $group->id)
			->where( function ($query) use ($before) {
				if(! $before) {
					return;
				}
				$query->where('id', '>', $before);
			})
			->orderBy('id', 'ASC')
			->limit($limit)
			->get();
		return response()->json($members)->setStatusCode(200);
	}

	/**
	 * 加入圈子操作
	 * @param  Request $request 
	 * @param  int     $group   圈子id
	 * @return json  结果
	 */
	public function join(Request $request, GroupModel $group)
	{
		if(!$group->is_audit) {
			abort(404, '圈子不存在或未通过审核');
		}

		$user = $request->user('api')->id;

		$newMember = new GroupMemberModel();
		$newMember->group_id = $group->id;
		$newMember->user_id = $user;

		try {
			$group->getConnection()->transaction( function () use ($group, $newMember) {
				$group->members()->save($newMember);
				$group->members_count += 1;
				$group->save();
			});
		} catch (\Exception $e) {
			abort(400);
		}
		return response()->json([
                'message' => '加入成功',
            ])->setStatusCode(201);
	}

	/**
	 * 退出圈子
	 * @param  Request $request 
	 * @param  int     $group   圈子id
	 * @return none         
	 */
	public function quit(Request $request, GroupModel $group)
	{
		if(!$group->is_audit) {
			abort(404, '圈子不存在或未通过审核');
		}

		$user = $request->user('api')->id;
		$joined = GroupMemberModel::where('user_id', $user)
				 	->where('group_id', $group->id)
				 	->first();
		if(!$joined) {
			abort(404, '非圈子成员');
		}
		
		try {
			$group->getConnection()->transaction( function () use ($group, $joined) {
				$joined->delete();
			 	$group->members_count -= 1;
			 	$group->save();
			});
		} catch (\Exception $e) {
			abort(400);
			throw $e;
		}

		abort(204);
	}
}