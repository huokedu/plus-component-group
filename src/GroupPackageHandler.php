<?php

namespace Zhiyi\Component\ZhiyiPlus\PlusComponentGroup;

use Illuminate\Console\Command;
use Zhiyi\Plus\Support\PackageHandler;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;

class GroupPackageHandler extends PackageHandler
{

    public function installHandle(Command $command)
    {
    	// if(! $command->comfirm('sure to publish')) {
    	// 	return;
    	// }

        // 群组
    }

    // 升级包 备用
    public function updateHandle(Command $command)
    {
        if( !$command->comfirm('sure to update group component')) {
            return;
        }
    }
}
