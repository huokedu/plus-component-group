<?php

namespace Zhiyi\Component\ZhiyiPlus\PlusComponentGroup\Observers;

use Zhiyi\Plus\Models\Comment;
use Zhiyi\Component\ZhiyiPlus\PlusComponentGroup\Models\GroupPostComment;

class CommentObserver
{
    /**
     * Feed comment created.
     *
     * @param \Zhiyi\Component\ZhiyiPlus\PlusComponentFeed\Models\FeedComment $feedComment
     * @return mixed
     * @author Seven Du <shiweidu@outlook.com>
     */
    public function created(GroupPostComment $groupPostComment)
    {
        $comment = new Comment();
        $comment->user_id = $groupPostComment->user_id;
        $comment->target_user = $groupPostComment->to_user_id;
        $comment->reply_user = $groupPostComment->reply_to_user_id ?: 0;
        $comment->target = $groupPostComment->id;
        $comment->channel = 'group:post';
        $comment->save();
    }

    /**
     * Feed comment deleted.
     *
     * @param \Zhiyi\Component\ZhiyiPlus\PlusComponentFeed\Models\FeedComment $feedComment
     * @return mixed
     * @author Seven Du <shiweidu@outlook.com>
     */
    public function deleted(GroupPostComment $groupPostComment)
    {
        Comment::where('channel', 'group:post')
            ->where('target', $groupPostComment->id)
            ->delete();
    }
}
