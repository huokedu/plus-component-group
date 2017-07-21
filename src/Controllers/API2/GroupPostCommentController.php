<?php

namespace Zhiyi\Component\ZhiyiPlus\PlusComponentGroup\API2;

use Carbon\Carbon;
use Illuminate\Http\Request;
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
        });

		if ($post->user_id !== $user->id) {
			$message = sprintf('%s 评论了你的帖子', $user->name);
	        $post->user->sendNotifyMessage('group:comment', $message, [
	            'post' => $post,
	            'user' => $user,
	        ]);
		}

        if ($replyUser && $replyUser !== $user->id) {
            $replyUser = $user->newQuery()->where('id', $replyUser)->first();
            $message = sprintf('%s 回复了您的帖子评论', $user->name);
            $replyUser->sendNotifyMessage('group:comment-reply', $message, [
                'post' => $post,
                'user' => $user,
            ]);
        }

        return $response->json([
            'message' => ['操作成功'],
            'comment' => $comment,
        ])->setStatusCode(201);
	}

	public function comments(Request $request, GroupModel $group, GroupPostModel $post)
	{
		if(!$group->is_audit) {
			abort(404, '圈子不存在或未通过审核');
		}

		if(!$post->is_audit) {
			abort(404, '动态不存在或未通过审核');
		}

		$limit = $request->query('limit', 15);
		$after = $request->query('after');

		$comments = $post->hascomments()->where(function ($query) use ($after) {
			if($after) {
				$query->where('id', '<', $after);
			}

		})
			->limit($limit)
			->orderBy('id', 'desc')
			->get();
			

		return response()->json($comments)->setStatusCode(200);
	}

	/**
	 * 评论帖子
	 * @param  Request
	 * @param  GroupModel     $group   
	 * @param  GroupPostModel $post    
	 * @return response
	 */
	// public function store(StorePostCommentRequest $request, GroupModel $group, GroupPostModel $post)
	// {
	// 	if(!$group->is_audit) {
	// 		abort(404, '圈子不存在或未通过审核');
	// 	}

	// 	if(!$post->is_audit) {
	// 		abort(404, '动态不存在或未通过审核');
	// 	}

	// 	$user = $request->user('api')->id;

	// 	$comment = new GroupPostCommentModel();
	// 	$comment->post_id = $post->id;
	// 	$comment->user_id = $user;
	// 	$comment->content = $request->input('content');
	// 	$comment->group_post_comment_mark = $request->input('group_post_comment_mark');
	// 	$comment->reply_to_user_id = $request->input('reply_to_user_id') ?? 0;
	// 	$comment->to_user_id = $post->user_id;
		
	// 	try {
	// 		$comment->getConnection()->transaction( function () use ($comment, $post) {
	// 			$comment->save();
	// 			$post->increment('comments');
	// 		});	
	// 	} catch (\Exception $e) {
	// 		throw $e;
	// 	}

	// 	return response()->json([
	// 			'id' => $comment->id,
	// 			'created_at' => $comment->created_at->timestamp,
	// 			'user_id' => $comment->user_id,
	// 			'reply_to_user_id' => $comment->reply_to_user_id,
	// 			'group_post_comment_mark' => $comment->group_post_comment_mark
	// 		])->setStatusCode(201);
	// }

	/**
	 * delete comment of post
	 * @param  Request               $request [description]
	 * @param  GroupModel            $group   [description]
	 * @param  GroupPostModel        $post    [description]
	 * @param  GroupPostCommentModel $comment [description]
	 * @return [type]                         [description]
	 */
	public function destory(Request $request, GroupModel $group, GroupPostModel $post, GroupPostCommentModel $comment) {
		if(!$group->is_audit) {
			abort(404, '圈子不存在或未通过审核');
		}

		if(!$post->is_audit) {
			abort(404, '动态不存在或未通过审核');
		}


		$user = $request->user('api')->id;

		// 非当前用户的评论
		if($comment->user_id !== $user) {
			abort(401, '没有权限删除');
		}

		try {
			$comment->delete();
			$post->decrement('comments');
		} catch (\Exception $e) {
			throw $e;
		}

		abort(204);
	}
}