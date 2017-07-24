<?php

namespace Zhiyi\Component\ZhiyiPlus\PlusComponentGroup\Models;

use Zhiyi\Plus\Models\User;
// use Zhiyi\Plus\Models\FileWith;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class GroupPostCollection extends Model
{
	protected $table = 'group_post_collections';

	protected $fillable = [
		'user_id'
	];

	protected $hidden = [
		'created_at',
		'deleted_at',
		'user_id'
	];

	protected $with = [
		'post'
	];

	/**
	 * 属于动态
	 * @return [type] [description]
	 */
	public function post()
	{
		return $this->hasOne(GroupPost::class, 'id', 'post_id');
	}

	/**
	 * 根据动态查找
	 * @param  Builder $query   [description]
	 * @param  integer $post_id [description]
	 * @return [type]           [description]
	 */
	public function scopeByPost(Builder $query, integer $post_id): Builder
	{
		return $query->where('post_id', $post_id);
	}

	/**
	 * 根据用户查找
	 * @param  Builder $query   [description]
	 * @param  integer $user_id [description]
	 * @return [type]           [description]
	 */
	public function scopeByUser(Builder $query, integer $user_id): Builder
	{
		return $query->where('user_id', $user_id);
	}
}