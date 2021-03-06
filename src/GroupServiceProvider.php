<?php

namespace Zhiyi\Component\ZhiyiPlus\PlusComponentGroup;

use Zhiyi\Plus\Support\PackageHandler;
use Illuminate\Support\ServiceProvider;
use Zhiyi\Plus\Support\ManageRepository;
use Zhiyi\Plus\Models\Comment;
use Illuminate\Database\Eloquent\Relations\Relation;
use Zhiyi\Component\ZhiyiPlus\PlusComponentGroup\GroupPackageHandler;
use Zhiyi\Component\ZhiyiPlus\PlusComponentGroup\Models\Group;

class GroupServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the package.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerRouter();
        // 注入处理器
        PackageHandler::loadHandleFrom('group', GroupPackageHandler::class);
        // Register migration files.
        $this->loadMigrationsFrom([
            dirname(__DIR__).'/databases/migrations'
        ]);
        // Register views files.
        $this->loadViewsFrom(dirname(__DIR__).'/views', 'group');
    }

    /**
     * Register the package service provider.
     *
     * @return void
     */
    public function register()
    {
        //注入后台导航
         $this->app->make(ManageRepository::class)
             ->loadManageFrom('圈子管理', 'group:admin', [
             'icon' => 'G',
             'route' => true
         ]);

        Relation::morphMap([
            'group-posts' => Models\GroupPost::class,
        ]);
    }

    /**
     * register the routers for new channel
     * @return void
     */
    public function registerRouter() {
        $this->app->make(RouterRegister::class)->all();
    }
}
