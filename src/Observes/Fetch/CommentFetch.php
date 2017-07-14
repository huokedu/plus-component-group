<?php

namespace Zhiyi\Component\ZhiyiPlus\PlusComponentGroup\Observers\Fetch;

use Zhiyi\Component\ZhiyiPlus\PlusComponentGroup\Models\GroupPost;
use Zhiyi\Component\ZhiyiPlus\PlusComponentGroup\Models\GroupPostComment;
use Zhiyi\Plus\Contracts\Model\FetchComment as CommentFetchConyract;
// use Zhiyi\Component\ZhiyiPlus\PlusComponentFeed\Repository\Feed as FeedRepository;

class CommentFetch implements CommentFetchConyract
{
    /**
     * GroupPost comment model.
     *
     * @var \Zhiyi\Component\ZhiyiPlus\PlusComponentGroup\Models\GroupPostComment
     */
    protected $comment;

    /**
     * The comment post.
     *
     * @var \Zhiyi\Component\ZhiyiPlus\PlusComponentGroup\Models\GroupPostComment
     */
    protected $post;

    /**
     * Create the comment fetch instance.
     *
     * @param \Zhiyi\Component\ZhiyiPlus\PlusComponentGroupPost\Models\GroupPostComment $comment
     * @author Seven Du <shiweidu@outlook.com>
     */
    public function __construct(GroupPostComment $comment)
    {
        $this->comment = $comment;
    }

    /**
     * Get comment centent.
     *
     * @return string
     * @author Seven Du <shiweidu@outlook.com>
     */
    public function getCommentContentAttribute(): string
    {
        return $this->comment->content;
    }

    /**
     * Get target source display title.
     *
     * @return string
     * @author Seven Du <shiweidu@outlook.com>
     */
    public function getTargetTitleAttribute(): string
    {
        return str_limit($this->post()->content ?? '', 100);
    }

    /**
     * Get target source image file with ID.
     *
     * @return int
     * @author Seven Du <shiweidu@outlook.com>
     */
    public function getTargetImageAttribute(): int
    {
        if (! isset($this->post()->images) || ! $this->post()->images) {
            return 0;
        }

        foreach ((array) $this->post()->images as $fileWith) {
            return $fileWith->id ?? 0;
        }
    }

    /**
     * Get target source id.
     *
     * @return int
     * @author Seven Du <shiweidu@outlook.com>
     */
    public function getTargetIdAttribute(): int
    {
        return $this->post()->post_id ?? 0;
    }

    /**
     * Get the comment to post.
     *
     * @return [type]
     * @author Seven Du <shiweidu@outlook.com>
     */
    protected function post()
    {
        if(! $this->post instanceof GroupPost) {
            $this->post = GroupPost::findOrFail($this->comment->post_id);
        }

        return $this->post;
    }
}
