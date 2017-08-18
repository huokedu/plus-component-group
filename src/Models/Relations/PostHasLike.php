<?php

namespace Zhiyi\Component\ZhiyiPlus\PlusComponentGroup\Models\Relations;

use Zhiyi\Plus\Models\Like;
use Zhiyi\Plus\Models\User;

trait PostHasLike 
{	
	/**
	 *  @author Wayne < qiaobinloverabbi@gmail.com >
	 *  @return [type]
	 */
	public function likes()
	{
		return $this->morphMany(Like::class, 'likeable');
	}

	/**
	 *  [do like post]
	 *  @author Wayne < qiaobinloverabbi@gmail.com >
	 *  @param  Object $user
	 *  @return Boolean
	 */
	public function like($user)
	{
		// TODO
	}

	/**
	 *  Whether user liked the post
	 *  @author Wayne < qiaobinloverabbi@gmail.com >
	 *  @param  Object $user
	 *  @return bool
	 */
	public function liked($user): bool
	{	
		if (!$user instanceOf User) {
			return false;
		}
		
		return $this->likes()->where('user_id', $user->id)->first() !== null;
	}
}