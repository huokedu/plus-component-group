<?php 

namespace Zhiyi\Component\ZhiyiPlus\PlusComponentGroup\API2;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Zhiyi\Plus\Http\Controllers\Controller;
use Zhiyi\Plus\Models\Comment as CommentModel;
use Zhiyi\Plus\Models\WalletCharge as WalletChargeModel;
use Zhiyi\Component\ZhiyiPlus\PlusComponentGroup\Models\GroupPost;

class CommentPinanedController extends Controller
{

	public function store(Request $request,
                         Carbon $dateTime,
                         WalletChargeModel $charge,
                         FeedModel $feed,
                         CommentModel $comment,
                         PostPinnedModel $pinned)
	{

	}
}