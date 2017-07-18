<?php

namespace Zhiyi\Component\ZhiyiPlus\PlusComponentGroup\API2;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Zhiyi\Plus\Http\Controllers\Controller;
use Zhiyi\Plus\Models\FileWith as FileWithModel;
use Illuminate\Contracts\Routing\ResponseFactory as ResponseContract;
use Zhiyi\Component\ZhiyiPlus\PlusComponentGroup\Models\User as UserModel;
use Zhiyi\Component\ZhiyiPlus\PlusComponentGroup\Models\Group as GroupModel;
use Zhiyi\Component\ZhiyiPlus\PlusComponentGroup\Models\GroupManager as GroupManagerModel;
use Zhiyi\Component\ZhiyiPlus\PlusComponentGroup\Models\GroupMember as GroupMemberModel;
use Zhiyi\Component\ZhiyiPlus\PlusComponentGroup\Models\GroupCount as GroupCountModel;
use Zhiyi\Component\ZhiyiPlus\PlusComponentGroup\FormRequest\API2\StoreGroup as StoreGroupRequest;

class GroupController extends Controller
{	

	/**
	 * 获取圈子列表
	 * @param  Request $request [description]
	 * @return [type]           [description]
	 */
	public function index(Request $request)
	{
		$limit = $request->query('limit', 15);
		$after = $request->query('after');

		$groups = GroupModel::where('is_audit', 1)
		->where(function ($query) use ($after) {
			if(!$after) {
				return;
			}
			$query->where('id', '<', $after);
		})
		->with(['avatar', 'cover', 'managers'])
		->limit($limit)
		->orderBy('id', 'desc')
		->get();

		$user = $request->user('api')->id ?? 0;
		$groups->map( function($group) use ($user) {
			$group->is_member = GroupMemberModel::where('user_id', $user)
			->where('group_id', $group->id)
			->count();
		})
		;

		if($groups->isEmpty()) abort(404, '没有圈子');
		return response()->json($groups)->setStatusCode(200);
	}

	/**
	 * 获取我加入的圈子
	 * @param  Request $request [description]
	 * @return [type]           [description]
	 */
	public function joined(Request $request)
	{
		$limit = $request->query('limit', 15);
        $after = $request->query('after');
		$user = $request->user('api')->id ?: 0;
		
		$userModel = UserModel::findOrFail($user);
		$groups = $userModel->groups()
			->with([
				'avatar',
				'cover',
				'members'
			])
			->where(function ($query) use ($after) {
				if(!$after) {
					return;
				}
				$query->where('id', '<', $after);
			})
			->limit($limit)
			->orderBy('id', 'desc')
			->get();
		return response()->json($groups)->setStatusCode(200);
		
		
	}

	/**
	 * 创建圈子
	 * @param  Request $request [description]
	 * @return [type]           [description]
	 */
	public function store(StoreGroupRequest $request)
	{
		$user = $request->user();
		$fileWithAvatar = $this->makeFileWithAvatar($request);
		$fileWithCover = $this->makeFileWithCover($request);
		$group = $this->fillGroupBaseData($request, new GroupModel());
		try {
			$group->saveOrFail();
			$group->getConnection()->transaction(function () use ($request, $group, $fileWithAvatar, $fileWithCover) {
				$this->saveGroupFounder($request, $group);
				$this->saveGroupMember($request, $group);
                $this->saveGroupFileWithCover($fileWithCover, $group);
                $this->saveGroupFileWithAvatar($fileWithAvatar, $group);
            });

		} catch (\Exception $e) {
			$group->delete();
            throw $e;
		}
		// 正在等待审核
		return response()->json(['message' => ['提交成功,请等待管理员审核'], 'id' => $group->id])->setStatusCode(202);
	}

	/**
	 * 获取单个圈子信息
	 * @param  int              $group    圈子id
	 * @param  ResponseContract $response 
	 * @return [type] 
	 */
	public function show(GroupModel $group, ResponseContract $response)
	{
		if(!$group->is_audit) {
			abort(404, '圈子不存在或未通过审核');
		}

		$group->load([
			'avatar',
			'cover',
			'managers'
		]);

		$group->managers->map(function($manager) {
			return [
				'id' => $manager->id,
				'founder' => $manager->founder
			];
		});
		return $response->json($group)
		->setStatusCode(200);
	}

	/**
	 * 保存创始人
	 * @param  StoreGroupRequest $request [description]
	 * @param  GroupModel        $group   [description]
	 * @return [type]                     [description]
	 */
	protected function saveGroupFounder(StoreGroupRequest $request, GroupModel $group)
	{
		$founder = new GroupManagerModel();
		$founder->user_id = $request->user('api')->id;
		$founder->group_id = $group->id;
		$founder->founder = 1;
		$founder->save();
	}

	/**
	 * save the founder as member of group
	 * @param  StoreGroupRequest $request [description]
	 * @param  GroupModel        $group   [description]
	 * @return [type]                     [description]
	 */
	protected function saveGroupMember(StoreGroupRequest $request, GroupModel $group)
	{
		$member = new GroupMemberModel();
		$member->user_id = $request->user('api')->id;
		$member->group_id = $group->id;
		$member->save();
	}

	/**
	 * save the cover of group
	 * @param  [type]     $fileWithCover [description]
	 * @param  GroupModel $group         [description]
	 * @return [type]                    [description]
	 */
	protected function saveGroupFileWithCover(FileWithModel $fileWithCover, GroupModel $group)
	{
		$fileWithCover->channel = 'group:cover';
		$fileWithCover->raw = $group->id;
		$fileWithCover->save();
	}

	/**
	 * save the avatar of group
	 * @param  [type]     $fileWithAvatar [description]
	 * @param  GroupModel $group          [description]
	 * @return [type]                     [description]
	 */
	protected function saveGroupFileWithAvatar(FileWithModel $fileWithAvatar, GroupModel $group)
	{
		$fileWithAvatar->channel = 'group:avatar';
		$fileWithAvatar->raw = $group->id;
		$fileWithAvatar->save();
	}

	/**
	 * get file-with of group avatar
	 * @param  StoreGroupRequest $request [description]
	 * @return [type]                     [description]
	 */
	protected function makeFileWithAvatar(StoreGroupRequest $request)
	{
		return FileWithModel::where('id', $request->input('avatar'))
		->where('channel', null)
        ->where('raw', null)
        ->where('user_id', $request->user('api')->id)
        ->first();
	}

	/**
	 * get file-with of group cover
	 * @param  StoreGroupRequest $request [description]
	 * @return [type]                     [description]
	 */
	protected function makeFileWithCover(StoreGroupRequest $request)
	{
		return FileWithModel::where('id', $request->input('cover'))
		->where('channel', null)
		->where('raw', null)
		->where('user_id', $request->user('api')->id)
		->first();
	}

	/**
	 * 完善圈子基本信息
	 * @param  Request    $request [description]
	 * @param  GroupModel $group   [description]
	 * @return [type]              [description]
	 */
	protected function fillGroupBaseData(Request $request, GroupModel $group): GroupModel
	{
		foreach ($request->only(['title', 'intro']) as $key => $value) {
			$group[$key] = $value;
		}
		$group->is_audit = 0; // 前台申请的默认为未审核
		$group->group_client_ip = $request->getClientIp();

		return $group;
	}
}