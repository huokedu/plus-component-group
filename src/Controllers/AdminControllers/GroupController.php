<?php

namespace Zhiyi\Component\ZhiyiPlus\PlusComponentGroup\AdminControllers;

use Zhiyi\Component\ZhiyiPlus\PlusComponentGroup\Models\Group;
use Zhiyi\Component\ZhiyiPlus\PlusComponentGroup\Models\GroupPost;
use Zhiyi\Plus\Http\Controllers\Controller;
use Illuminate\Http\Request;

class GroupController extends Controller 
{

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
                ->paginate($limit);

        return response()->json($items)->setStatusCode(200);
    }


    public function audit(int $gid)
    {
        $group = Group::findOrFail($gid);
        $group->is_audit = $group->is_audit ? 0 : 1;
        $group->save();
        return response()->json(['message' => '操作成功'])->setStatusCode(200);
    }

    public function groupPosts(Request $request)
    {
        $audit = $request->get('audit');
        $keyword = $request->get('keyword');
        $groupId = (int) $request->get('group_id');
        $limit = (int) $request->get('limit', 20);

        $items = GroupPost::when(!is_null($audit), function ($query) use ($audit) {
                      $query->byAudit($audit);
                  })
                  ->when($groupId, function ($query) use ($groupId) {
                      $query->byGroup($groupId);
                  })
                  ->when($keyword, function ($query) use ($keyword) {
                      $query->byKeyword($keywords);
                  })
                  ->orderBy('id', 'desc')
                  ->paginate($limit);

        return response()->json($items)->setStatusCode(200);
    }

    public function posts(Request $request, int $gid)
    {
        $audit = $request->get('audit');
        $keyword = $request->get('keyword');
        $groupId = (int) $request->get('group_id');
        $limit = (int) $request->get('limit', 20);

        $items = GroupPost::where('group_id', $gid)
            ->when(!is_null($audit), function ($query) use ($audit) {
                $query->byAudit($audit);
            })
            ->when($keyword, function ($query) use ($keyword) {
                $query->byKeyword($keyword);
            })
            ->orderBy('id', 'desc')
            ->paginate($limit);

        return response()->json($items)->setStatusCode(200);
    }

}

