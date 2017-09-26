<?php

namespace Zhiyi\Component\ZhiyiPlus\PlusComponentGroup;

use Illuminate\Console\Command;
use Zhiyi\Plus\Support\PackageHandler;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;

class GroupPackageHandler extends PackageHandler
{
    /**
     * 运行圈子数据表迁移.
     *
     * @author bs<414606094@qq.com>
     * @param  \Illuminate\Console\Command $command
     * @return mixed
     */
    public function migrateHandle(Command $command)
    {
        return $command->call('migrate');
    }
}
