<?php

namespace Zhiyi\Component\ZhiyiPlus\PlusComponentGroup\Models;

use Zhiyi\Plus\Models\User;
use Zhiyi\Plus\Models\FileWith;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class GroupManager extends Model
{
	protected $table = 'group_managers';

	protected $hidden = [
		'created_at',
		'updated_at',
		'deleted_at',
		'id',
	];

	// public function group()
	// {
	// 	return $this->belongsTo(Group::class, 'group_id');
	// }

	 public function user()
	 {
	 	return $this->hasOne(User::class, 'id','user_id');
	 }
}