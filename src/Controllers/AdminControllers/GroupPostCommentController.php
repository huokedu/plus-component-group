<?php

namespace Zhiyi\Component\ZhiyiPlus\PlusComponentGroup\AdminControllers;

use Illuminate\Http\Request;
use Zhiyi\Plus\Http\Controllers\Controller;
use Zhiyi\Plus\Models\Comment as CommentModel;
use Zhiyi\Component\ZhiyiPlus\PlusComponentGroup\Models\GroupPost as GroupPostModel;

class GroupPostCommentController extends Controller
{

    public function index(Request $request)
    {
        $limit = (int) $request->get('limit', 20);
        $keyword = $request->get('keyword');
        $body = $request->get('body');

        $data = [];

        $data['query'] = $request->all();
        $data['items'] = CommentModel::where('commentable_type', 'group-posts')
        ->when(! is_null($keyword), function ($query) use($keyword) {
            if (is_numeric($keyword)) {
                $query->where('user_id', $keyword);
            } else {
                $query->whereHas('user', function ($query) use ($keyword) {
                    $query->where('name', 'like', sprintf('%%%s%%', $keyword));
                });
            }
        })
        ->when(! is_null($body), function ($query) use ($body) {
            $query->where('body', 'like', sprintf('%%%s%%', $body));
        })
        ->orderBy('id', 'desc')
        ->paginate($limit)
        ->appends($request->all());

        return view('group::comments.index', $data);
    }

    public function delete(CommentModel $comment)
    {   
        $post = GroupPostModel::find($comment->commentable_id);

        if ($comment->delete() && $post->decrement('comments_count', 1)) {
            return back()->with('success', '删除帖子成功');
        } else {
            return back()->with('error', '删除帖子失败，请稍后再试');
        }
    }
}
