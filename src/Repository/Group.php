<?php

namespace Zhiyi\Component\ZhiyiPlus\PlusComponentFeed\Repository;

use Carbon\Carbon;
use Zhiyi\Plus\Models\FileWith as FileWithModel;
use Illuminate\Contracts\Cache\Repository as CacheContract;
use Zhiyi\Component\ZhiyiPlus\PlusComponentGroup\Models\Group as GroupModel;
use Zhiyi\Component\ZhiyiPlus\PlusComponentGroup\Models\GroupMember as GroupMemberModel;

class Group
{
	protected $model;

	protected $cache;

	 protected $dateTime;

	public function __construct(GroupModel $model, CacheContract $cache, Carbon $dateTime)
	{
		$this->model = $model;
		$this->cache = $cache;
		$this->dateTime = $dateTime;
	}

	/**
	 * find Group
	 * @param  [integer] $id      [description]
	 * @param  array  $columns [description]
	 * @return [type]          [description]
	 */
	public function find($id, $columns = ['*'])
	{
		return $this->model = $this->cache->remember(sprintf('group:%s', $id), $this->dateTime->copy()->addDays(7), function () use ($id, $columns) {
            $this->model = $this->model->findOrFail($id, $columns);

            return $this->model;
        });
	}

	/**
     * Group avatar.
     *
     * @return \Illuminate\Database\Eloquent\Collection|static[]
     * @author Seven Du <shiweidu@outlook.com>
     */
    public function avatar()
    {
        $this->model->setRelation('images', $this->cache->remember(sprintf('group:%s:avatar', $this->model->id), $this->dateTime->copy()->addDays(7), function () {
            $this->model->load([
                'images'
            ]);

            return $this->model->avatar;
        }));

        return $this->model->avatar;
    }

    /**
     * Group cover.
     *
     * @return \Illuminate\Database\Eloquent\Collection|static[]
     * @author Seven Du <shiweidu@outlook.com>
     */
    public function cover()
    {
        $this->model->setRelation('images', $this->cache->remember(sprintf('group:%s:cover', $this->model->id), $this->dateTime->copy()->addDays(7), function () {
            $this->model->load([
                'images'
            ]);

            return $this->model->cover;
        }));

        return $this->model->cover;
    }

    public function hasJoined(int $user): bool
    {
    	$cacheKey = sprintf('group:%s:has-joined:%s', $this->model->id, $user);
    	if ($this->cache->has($cacheKey)) {
            return $this->model->has_joined = $this->cache->get($cacheKey);
        }

        $this->model->has_joined = $this->model->members()->where('user_id', $user)->count() >= 1;
        $this->cache->forever($cacheKey, $this->model->has_joined);

        return $this->model->has_joined;
    }

    /**
     * Set feed model.
     *
     * @param Zhiyi\Component\ZhiyiPlus\PlusComponentFeed\Models\Feed $model
     * @author Seven Du <shiweidu@outlook.com>
     */
    public function setModel(GroupModel $model)
    {
        $this->model = $model;

        return $this;
    }

    public function forget($key)
    {
        $this->cache->forget($key);
    }
}