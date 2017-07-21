<?php

namespace Zhiyi\Component\ZhiyiPlus\PlusComponentGroup\Models;

use Zhiyi\Plus\Models\User as Model;

class User extends Model
{
	public function groups()
	{
		return $this->belongsToMany(Group::class, 'group_members', 'user_id', 'group_id', 'id');
	}
}