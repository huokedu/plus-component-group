<?php

namespace Zhiyi\Component\ZhiyiPlus\PlusComponentGroup\AdminControllers;

use Illuminate\Http\Request;
use Zhiyi\Plus\Http\Controllers\Controller;
use Zhiyi\Plus\Models\FileWith as FileWithModel;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Zhiyi\Component\ZhiyiPlus\PlusComponentGroup\Models\Group;
use Zhiyi\Component\ZhiyiPlus\PlusComponentGroup\Models\GroupPost;
use Zhiyi\Component\ZhiyiPlus\PlusComponentGroup\Models\GroupManager;
use Zhiyi\Component\ZhiyiPlus\PlusComponentGroup\Models\GroupMember;

class GroupController extends Controller 
{

    /**
     * 圈子列表
     * @param Request $request
     * @return mixed
     * @author: huhao <915664508@qq.com>
     */
    public function index(Request $request)
    {
        $limit = (int) $request->get('limit', 20);
        $keyword = $request->get('keyword');
        $audit = $request->get('audit');

        $items = Group::when($keyword, function ($query) use ($keyword) {
            $query->byKeyword($keyword);
        })
        ->when(!is_null($audit), function ($query) use ($audit) {
            $query->byAudit($audit);
        })
        ->with('founder', 'founder.user')
        ->orderBy('id', 'desc')
        ->paginate($limit)->appends($request->all());

        $data = [];
        $data['query'] = $request->all();
        $data['items'] = $items;

        return view('group::groups.index', $data);
    }

    /**
     * 更新圈子状态
     * @param int $gid
     * @return mixed
     * @author: huhao <915664508@qq.com>
     */
    public function audit(int $gid)
    {
        try {
            $group = Group::findOrFail($gid);
            $group->is_audit = $group->is_audit ? 0 : 1;
            $group->save();
        } catch (ModelNotFoundException $modelNotFoundException) {
            return back()->with('error', '圈子不存在或已被删除');
        }
        if ($group->is_audit == 1 && $group->founder->user) { // 审核通过给创始人发送通知
            $group->founder->user->sendNotifyMessage('group:audit', sprintf('你创建的圈子%s通过了审核', $group->title), [
                'group' => $group,
            ]);
        }

        return back()->with('success', sprintf('"%s"圈子状态更新成功', $group->title));
    }

    /**
     * 获取某圈子动态
     * @param Request $request
     * @param int $gid
     * @return mixed
     * @author: huhao <915664508@qq.com>
     */
    public function posts(Request $request, int $groupId)
    {
        $audit = $request->get('audit');
        $keyword = $request->get('keyword');
        $limit = (int) $request->get('limit', 20);

        $posts = GroupPost::where('group_id', $groupId)
            ->when(!is_null($audit), function ($query) use ($audit) {
                $query->byAudit($audit);
            })
            ->when($keyword, function ($query) use ($keyword) {
                $query->byKeyword($keyword);
            })
            ->orderBy('id', 'desc')
            ->paginate($limit)->appends($request->all());

        $data = [];
        $data['query'] = $request->all();
        $data['posts'] = $posts;

        return view('group::groups.posts', $data);
    }

    /**
     * 圈子成员
     * @param int $groupId
     * @return mixed
     * @author: huhao <915664508@qq.com>
     */
    public function members(int $groupId)
    {
        $members = GroupMember::with('user')->where('group_id', $groupId)->get();
        $data = [];
        $data['members'] = $members;

        return view('group::groups.members', $data);
    }

    /**
     * 圈子管理员
     * @param  Int    $groupId [圈子ID]
     */
    public function managers(Int $groupId)
    {
        $managers = GroupManager::with('user')->where('group_id', $groupId)->get();
        $data = [];
        $data['managers'] = $managers;

        return view('group::groups.managers', $data);
    }

    /**
     * 删除圈子
     * @param int $groupId
     * @return mixed
     * @author: huhao <915664508@qq.com>
     */
    public function delete(int $groupId)
    {
        try {
            Group::findOrFail($groupId)->delete();
        } catch (ModelNotFoundException $modelNotFoundException) {
            return back()->with('error', '圈子不存在或已被删除');
        }
        return back()->with('success', '删除圈子成功');
    }

    /**
     * 创建圈子.
     * @param  Request $request
     * @return mixed
     */
    public function create(Request $request, FileWithModel $fileWithModel)
    {
        if (strtolower($request->method()) === 'post') {

            $this->validate($request, $this->groupRule(), $this->groupMsg());

            \DB::beginTransaction();
            try {
                $model = new Group();
                $model->title = $request->get('title');
                $model->intro = $request->get('intro');
                $model->group_mark = 0;
                $model->group_client_ip = $request->getClientIp();
                $model->save();

                $groupManagerModel = new GroupManager();
                $groupManagerModel->user_id = $request->get('founder');
                $groupManagerModel->founder = 1;
                $groupManagerModel->group_id = $model->id;
                $groupManagerModel->save();

                // 保存头像
                $avatarFileModel = $this->findNotWithFileModels($request->input('avatar'), $fileWithModel);
                $avatarFileModel->channel = 'group:avatar';
                $avatarFileModel->raw = $model->id;
                $avatarFileModel->save();
                // 保存封面
                $coverFileModel = $this->findNotWithFileModels($request->input('cover'), $fileWithModel);
                $coverFileModel->channel = 'group:cover';
                $coverFileModel->raw = $model->id;
                $coverFileModel->save();

                \DB::commit();
                return redirect()->route('group:admin')->with('success', '圈子创建成功');
            } catch (\Exception $e) {
                \DB::rollback(); 
                return back()->with('error', $e->getMessage());
            }
        } else {
            return view('group::groups.create');
        }
    }

    public function edit(Request $request, int $groupId, FileWithModel $fileWithModel)
    {
        try {
            $group = Group::findOrFail($groupId);
        } catch (ModelNotFoundException $modelNotFoundException) {
            return back()->with('error', '圈子不存在或已被删除');
        }

        if (strtolower($request->method()) === 'put') {

            $this->validate($request, $this->groupRule(), $this->groupMsg());

            \DB::beginTransaction();

            try {
                $group->title = $request->get('title');
                $group->intro = $request->get('intro');
                $group->group_mark = 0;
                $group->group_client_ip = $request->getClientIp();
                $group->save();

                $groupManager = GroupManager::where('group_id', $groupId)->first();
                $groupManager->user_id = $request->get('founder');
                $groupManager->founder = 1;
                $groupManager->save();

                // 保存头像
                $avatarFileModel = $this->findNotWithFileModels($request->input('avatar'), $fileWithModel);
                if (! is_null($avatarFileModel)) {
                    if (! is_null($group->avatar)) {
                        $group->avatar->delete();
                    }
                    $avatarFileModel->channel = 'group:avatar';
                    $avatarFileModel->raw = $group->id;
                    $avatarFileModel->save();
                }

                // 保存封面
                $coverFileModel = $this->findNotWithFileModels($request->input('cover'), $fileWithModel);
                if (! is_null($coverFileModel)) {
                    if (! is_null($group->cover)) {
                        $group->cover->delete();
                    }
                    $coverFileModel->channel = 'group:cover';
                    $coverFileModel->raw = $group->id;
                    $coverFileModel->save();
                }

                \DB::commit();
                return redirect()->route('group:admin')->with('success', '圈子编辑成功');
            } catch (\Exception $e) {
                \DB::rollback();
                return back()->with('error', $e->getMessage());
            }

        } else {
            return view('group::groups.edit', compact('group'));
        }
    }


    /**
     * group rule.
     *
     * @return array
     */
    protected function groupRule() {
        return  [
                'title' => 'required',
                'intro' => 'required',
                'founder' => 'required',
                'avatar' => 'bail|required|required_with:files|integer|exists:file_withs,id',
                'cover' => 'bail|required|required_with:files|integer|exists:file_withs,id',
        ];
    }

    /**
     *  group rule message.
     *
     * @return array
     */
    protected function groupMsg() {
        return [
                'title.required' => '圈子标题不能为空',
                'intro.required' => '圈子描述不能为空',
                'founder.required' => '圈子创建者不能为空',
                'avatar.required' => '圈子头像未上传',
                'avatar.exists' => '圈子头像不存在或已被使用',
                'cover.required' => '圈子封面未上传',
                'cover.exists' => '圈子封面不存在或已被使用',
        ];
    }


    /**
     * File not with file models.
     *
     * @param int $fileId
     * @param FileWithModel $fileWithModel
     * @return mixed
     */
    protected function findNotWithFileModels(int $fileId, FileWithModel $fileWithModel)
    {
        return $fileWithModel->where('channel', null)
            ->where('raw', null)
            ->where('id', $fileId)
            ->first();
    }
}

