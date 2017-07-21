<?php

namespace Zhiyi\Component\ZhiyiPlus\PlusComponentGroup\Observers;

use Zhiyi\Plus\Models\Comment;
use Zhiyi\Component\ZhiyiPlus\PlusComponentGroup\Models\GroupPostComment;
use Zhiyi\Component\ZhiyiPlus\PlusComponentGroup\Models\GroupPost;

class PlusCommentObserver
{
    /**
     * Global Comment deleted.
     *
     * @param \Zhiyi\Plus\Models\Comment $comment
     * @return mixed
     * @author Seven Du <shiweidu@outlook.com>
     */
    public function deleted(Comment $comment)
    {
        return $this->validateOr($comment, function (GroupPostComment $groupPostComment) {
            $groupPost = GroupPost::find($groupPostComment->group_id);
            $groupPostComment->getConnection()->transaction( function () use ($groupPostComment, $groupPost) {
                $groupPostComment->delete();
                $groupPost->decrement('comments')->save();
            });
        });
    }

    /**
     * Fetch event.
     *
     * @param \Zhiyi\Plus\Models\Comment $comment
     * @return mixed
     * @author Seven Du <shiweidu@outlook.com>
     */
    public function fetch(Comment $comment)
    {
        return $this->validateOr($comment, function (GroupPostComment $groupPostComment) {
            return new Fetch\CommentFetch($groupPostComment);
        });
    }

    /**
     * Validate or run call.
     *
     * @param \Zhiyi\Plus\Models\Comment $comment
     * @param callable $call
     * @return mixed
     * @author Seven Du <shiweidu@outlook.com>
     */
    protected function validateOr(Comment $comment, callable $call)
    {
        if ($comment->channel !== 'group:post' || ! ($comment = GroupPostComment::find($comment->target))) {
            return null;
        }

        return call_user_func_array($call, [$comment]);
    }
}
