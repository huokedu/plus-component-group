<?php

namespace Zhiyi\Component\ZhiyiPlus\PlusComponentGroup\Models;

use Zhiyi\Plus\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class GroupPostDigg extends Model
{
	protected $table = 'group_post_diggs';

	protected $fillable = [
		'user_id',
		'post_id'
	];

	protected $hidden = [
		'created_at',
		'updated_at',
		'post_id',
		'to_user_id'
	];

	/**
	 * 属于哪条动态
	 * @return [type] [description]
	 */
	public function post()
	{
		return $this->belongsTo(GroupPost::class, 'post_id');
	}

	/**
	 * 根据用户进行查找
	 * @param  Builder $query   [description]
	 * @param  integer $user_id [description]
	 * @return [type]           [description]
	 */
	public function scopeByUser(Builder $query, integer $user_id): Builder
	{
		return $query->where('user_id', '=', $user_id);
	}

	/**
	 * 根据post_id查找点赞
	 * @param  Builder $query   [description]
	 * @param  integer $post_id [description]
	 * @return [type]           [description]
	 */
	public function scopeByPost(Builder $query, integer $post_id): Builder
	{
		return $query->where('post_id', $post_id);
	}
}