<?php

namespace Zhiyi\Component\ZhiyiPlus\PlusComponentGroup\Models;

use Zhiyi\Plus\Models\User;
// use Zhiyi\Plus\Models\FileWith;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class GroupPostComment extends Model
{
	protected $table = 'group_post_comments';

	protected $hidden = [
		'updated_at',
		'deleted_at',
		'floor',
		'post_id'
	];

	/**
	 * 评论所属的动态
	 * @return [type] [description]
	 */
	public function groupPost()
	{
		return $this->belongsTo(GroupPost::class, 'post_id', 'id');
	}

	/**
	 * 评论者
	 * @return [type] [description]
	 */
	public function user()
	{
		return $this->belongsTo(User::class, 'id', 'user_id');
	}

	/**
	 * 在评论中被回复的用户
	 * @return [type] [description]
	 */
	public function replyUser()
	{
		return $this->belongsTo(User::class, 'id', 'reply_to_user_id');
	}

	/**
	 * 通过用户id查询评论
	 * @param  Builder $query   [description]
	 * @param  integer $user_id [description]
	 * @return [type]           [description]
	 */
	public function scopeByUser(Builder $query, integer $user_id): Builder
	{
		return $query->where('user_id', '=', $user_id);
	}

	/**
	 * 根据post_id查找评论
	 * @param  Builder $query   [description]
	 * @param  integer $post_id [description]
	 * @return [type]           [description]
	 */
	public function scopeByPost(Builder $query, integer $post_id): Builder
	{
		return $query->where('post_id', $post_id);
	}
}