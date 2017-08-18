<?php

namespace Zhiyi\Component\ZhiyiPlus\PlusComponentGroup\AdminControllers;

use Zhiyi\Component\ZhiyiPlus\PlusComponentGroup\Models\Group;
use Zhiyi\Component\ZhiyiPlus\PlusComponentGroup\Models\GroupPost;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Zhiyi\Plus\Http\Controllers\Controller;
use Illuminate\Http\Request;

class GroupPostController extends Controller
{
    /**
     * 圈子动态
     * @param Request $request
     * @return mixed
     * @author: huhao <915664508@qq.com>
     */
    public function index(Request $request)
    {
        $audit = $request->get('audit');
        $keyword = $request->get('keyword');
        $groupId = (int) $request->get('group_id');
        $limit = (int) $request->get('limit', 20);

        $posts = GroupPost::with(['group', 'user'])
        ->when(!is_null($audit), function ($query) use ($audit) {
            $query->byAudit($audit);
        })
        ->when($groupId, function ($query) use ($groupId) {
            $query->byGroup($groupId);
        })
        ->when($keyword, function ($query) use ($keyword) {
            $query->byKeyword($keyword);
        })
        ->orderBy('id', 'desc')
        ->paginate($limit)->appends($request->all());

        $data = [];
        $data['posts'] = $posts;
        $data['groups'] = Group::select(['id','title'])->get();
        $data['query'] = $request->all();

        return view('group::posts.index', $data);
    }

    /**
     * 帖子状态更新
     * @param int $postId
     * @return mixed
     * @author: huhao <915664508@qq.com>
     */
    public function audit(int $postId)
    {
        try {
            $groupPost = GroupPost::findOrFail($postId);
        } catch (ModelNotFoundException $modelNotFoundException) {
            return back()->with('success', '帖子不存在或已被删除');
        }

        $groupPost->is_audit = $groupPost->is_audit ? 0 : 1;
        $groupPost->save();

        return back()->with('success', sprintf('更新"%s"帖子状态成功', $groupPost->title));
    }

    /**
     * 删除帖子
     * @param int $postId
     * @return mixed
     * @author: huhao <915664508@qq.com>
     */
    public function delete(int $postId)
    {
        try {
           GroupPost::findOrFail($postId)->delete();
        } catch (ModelNotFoundException $modelNotFoundException) {
            return back()->with('error', '帖子不存在或已被删除');
        }
        return back()->with('success', '删除帖子成功');
    }
}
