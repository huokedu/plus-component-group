<?php

namespace Zhiyi\Component\ZhiyiPlus\PlusComponentGroup\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class GroupCount extends Model
{
	protected $table = 'groups_count';

	protected $hidden = [
		'created_at',
		'updated_at',
		'id',
		'group_id'
	];
}