<?php

namespace Zhiyi\Component\ZhiyiPlus\PlusComponentGroup\AdminControllers;

use Zhiyi\Component\ZhiyiPlus\PlusComponentGroup\Models\Group;
use Zhiyi\Component\ZhiyiPlus\PlusComponentGroup\Models\GroupManager;
use Zhiyi\Component\ZhiyiPlus\PlusComponentGroup\Models\GroupPost;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Pagination\LengthAwarePaginator;
use Zhiyi\Plus\Http\Controllers\Controller;
use Illuminate\Http\Request;

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
    public function managers(int $groupId)
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
}

