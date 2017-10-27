<?php

namespace Zhiyi\Component\ZhiyiPlus\PlusComponentGroup\API2;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Zhiyi\Plus\Services\Push;
use Zhiyi\Plus\Http\Controllers\Controller;
use Zhiyi\Plus\Models\Comment as CommentModel;
use Zhiyi\Plus\Models\FileWith as FileWithModel;
use Zhiyi\Component\ZhiyiPlus\PlusComponentGroup\Models\Group as GroupModel;
use Illuminate\Contracts\Routing\ResponseFactory as ResponseFactoryContract;
use Zhiyi\Component\ZhiyiPlus\PlusComponentGroup\Models\GroupPost as GroupPostModel;
use Zhiyi\Component\ZhiyiPlus\PlusComponentGroup\FormRequest\API2\StoreGroupPostComment as StorePostCommentRequest;

class GroupPostCommentController extends Controller
{
	/**
	 * List all comments.
	 *
	 * @param \Illuminate\Http\Request $request
	 * @param \Illuminate\Contracts\Routing\ResponseFactory $response
	 * @param \Zhiyi\Component\ZhiyiPlus\PlusComponentGroup\Models\Group $group
	 * @param \Zhiyi\Component\ZhiyiPlus\PlusComponentGroup\Models\GroupPost $post
	 * @return mixed
	 * @author Seven Du <shiweidu@outlook.com>
	 */
	public function index(Request $request,
						  ResponseFactoryContract $response,
						  GroupModel $group,
						  GroupPostModel $post)
	{
		if (! $group->is_audit || ! $post->is_audit) {
			return $response->json(['message' => ['非法请求']], 403);
		}

		$limit = $request->query('limit', 20);
		$after = $request->query('after', false);

		$comments = $post->comments()
			->when($after, function ($query) use ($after) {
				return $query->where('id', '<', $after);
			})
			->orderBy('id', 'desc')
			->limit($limit)
			->get();

		return $response->json($comments, 200);
	}

	/**
	 * Send a posts comment.
	 *
	 * @param \Zhiyi\Component\ZhiyiPlus\PlusComponentGroup\FormRequest\API2\StoreGroupPostComment $request
	 * @param \Illuminate\Contracts\Routing\ResponseFactory $response
	 * @param \Zhiyi\Plus\Models\Comment $group
	 * @param \Zhiyi\Component\ZhiyiPlus\PlusComponentGroup\Models\GroupPost $post
	 * @return mixed
	 * @author Seven Du <shiweidu@outlook.com>
	 */
	public function store(StorePostCommentRequest $request,
						  ResponseFactoryContract $response,
						  CommentModel $comment,
						  GroupModel $group,
						  GroupPostModel $post)
	{
		if (! $group->is_audit || ! $post->is_audit) {
			return $response->json(['message' => ['非法请求']], 403);
		}

		$user = $request->user();
		$body = $request->input('body');
		$replyUser = intval($request->input('reply_user', 0));

		$comment->user_id = $user->id;
		$comment->target_user = $post->user_id;
		$comment->reply_user = $replyUser;
		$comment->body = $body;

		$post->getConnection()->transaction(function () use ($post, $user, $comment) {
            $post->comments()->save($comment);
            $post->increment('comments_count', 1);
            $user->extra()->firstOrCreate([])->increment('comments_count', 1);

            if ($post->user->id !== $user->id) {
            	$post->user->unreadCount()->firstOrCreate([])->increment('unread_comments_count', 1);
            	app(push::class)->push(sprintf('%s评论了你的帖子', $user->name), (string) $post->user->id, ['channel' => 'group:comment']);
            }
        });

        if ($replyUser && $replyUser !== $user->id) {
            $replyUser = $user->newQuery()->where('id', $replyUser)->first();
			$replyUser->unreadCount()->firstOrCreate([])->increment('unread_comments_count', 1);
        	app(push::class)->push(sprintf('%s 回复了您的帖子评论', $user->name), (string) $replyUser->id, ['channel' => 'group:comment-reply']);
        }

        return $response->json([
            'message' => ['操作成功'],
            'comment' => $comment,
            'group_post_comment_mark' => $request->input('group_post_comment_mark')
        ])->setStatusCode(201);
	}

	
	/**
	 * delete comment of post
	 *
	 * @param Request $request [description]
	 * @param ResponseFactoryContract $response [description]
	 * @param GroupModel $group [description]
	 * @param GroupPostModel $post [description]
	 * @param CommentModel $comment [description]
	 * @return [type] [description]
	 * @author Seven Du <shiweidu@outlook.com>
	 */
	public function destory(Request $request,
							ResponseFactoryContract $response,
							GroupModel $group,
							GroupPostModel $post,
							CommentModel $comment)
	{
		$user = $request->user();
		if (! $group->is_audit || ! $post->is_audit) {
			return $response->json(['message' => ['非法请求']], 403);
		} elseif ($comment->user_id !== $user->id) {
			return $response->json(['message' => ['没有权限删除']], 403);
		}

		$group->getConnection()->transaction(function () use ($post, $comment) {
			$comment->delete();
			$post->decrement('comments_count', 1);
		});

		return $response->make('', 204);
	}
}