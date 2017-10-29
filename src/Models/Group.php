<?php

namespace Zhiyi\Component\ZhiyiPlus\PlusComponentGroup\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Zhiyi\Plus\Models\FileWith;
use Illuminate\Database\Eloquent\SoftDeletes;

class Group extends Model
{
	use SoftDeletes;
        // Concerns\HasFeedCollect;

	protected $table = 'groups';

	protected $fillable = [
		'title',
		'intro'
	];

	protected $hidden = [
		'group_client_ip',
		'updated_at',
		'deleted_at'
	];

	protected $with = [
		'avatar',
		'cover',
		'members',
		'managers.user'
	];

	/**
	 * 创始人
	 * @return [type] [description]
	 */
	public function founder() {
		return $this->hasOne(GroupManager::class, 'group_id')
			->where('founder', 1);
	}

	/**
	 * 管理者
	 * @return [type] [description]
	 */
	public function managers() {
		return $this->hasMany(GroupManager::class, 'group_id');
	}

	/**
	 * 圈子统计数据
	 * @return [type] [description]
	 */
	public function statistics()
	{
		return $this->hasOne(GroupTotalCount::class, 'group_id');
	}

	/**
	 * 圈子动态
	 * @return [type] [description]
	 */
	public function posts() {
		return $this->hasMany(GroupPost::class, 'group_id')
			->where('is_audit', 1);
	}

	/**
	 * 圈子头像
	 * @return [type] [description]
	 */
	public function avatar() {
		return $this->hasOne(FileWith::class, 'raw', 'id')
            ->where('channel', 'group:avatar')
            ->select(['raw', 'size', 'id']);
	}

	public function cover()
	{
		return $this->hasOne(FileWith::class, 'raw', 'id')
			->where('channel', 'group:cover')
			->select(['raw', 'size', 'id']);
	}

	// public function datas() {
	// 	return $this->hasOne(GroupCount::class, 'group_id');
	// }

	/**
	 * 圈子成员
	 * @return [type] [description]
	 */
	public function members() {
		return $this->hasMany(GroupMember::class, 'group_id');
	}

	// 通过关键字查找
	public function scopeByKeyword(Builder $query, string $keyword): Builder
	{
		return $query->where('title', 'like', "%{$keyword}%")
			->orWhere('intro', 'like', "%{$keyword}%");
	}

    /**
     * 根据审核状态查询
     * @param Builder $builder
     * @param int $audit
     * @return mixed
     */
    public function scopeByAudit(Builder $builder, int $audit): Builder
    {
        return $builder->where('is_audit', $audit);
    }
}
