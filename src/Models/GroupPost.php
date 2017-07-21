<?php

namespace Zhiyi\Component\ZhiyiPlus\PlusComponentGroup\Models;

use Zhiyi\Plus\Models\User;
use Zhiyi\Plus\Models\Comment;
use Zhiyi\Plus\Models\FileWith;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class GroupPost extends Model
{
	protected $table = 'group_posts';

	protected $hidden = [
		'deleted_at'	
	];

	protected $with = [
		'images'
	];
	
	/**
	 * 属于哪个圈子
	 * @return [type] [description]
	 */
	public function group() {
		return $this->belongsTo(Group::class, 'group_id');
	}

	/**
	 * 属于哪个用户
	 * @return [type] [description]
	 */
	public function user() {
		return $this->belongsTo(User::class, 'user_id', 'id');
	}

	/**
     * Has comments.
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphMany
     * @author Seven Du <shiweidu@outlook.com>
     */
	public function comments()
	{
		return $this->morphMany(Comment::class, 'commentable');
	}

	public function collections() {
		return $this->hasMany(GroupPostCollection::class, 'post_id');
	}

	/**
	 * 动态的点赞
	 * @return [type] [description]
	 */
	public function diggs() {
		return $this->hasMany(GroupPostDigg::class, 'post_id')
			->select('id', 'post_id', 'user_id');
	}

	/**
	 * 动态的图片
	 * @return [type] [description]
	 */
	public function images() {
		return $this->hasMany(FileWith::class, 'raw', 'id')
			->where('channel', 'post:image')
			->select('raw', 'size', 'id');
	}

	/**
	 * 查询未通过审核的动态
	 * @param  Builder $query [description]
	 * @return [type]         [description]
	 */
	public function scopeByNotAudit(Builder $query, int $user): Builder
	{
		return $query->where('is_audit', 0)
			->where('user_id', $user);
	}

	/**
	 * 通过关键字查询
	 * @param  Builder $query   
	 * @param  string  $keyword [关键字]
	 * @return [type]           [description]
	 */
	public function scopeByKeyword(Builder $query, string $keyword): Builder
	{
		return $query->where('title', 'like', "%{$keyword}%")
			->orWhere('content', 'like', "%{$keyword}%");
	}
}