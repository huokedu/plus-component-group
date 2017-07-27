<?php 

namespace Zhiyi\Component\ZhiyiPlus\PlusComponentGroup\API2;

use Illuminate\Http\Request;
use Zhiyi\Plus\Http\Controllers\Controller;
use Zhiyi\Plus\Models\WalletCharge as WalletChargeModel;
use Zhiyi\Plus\Models\Comment as CommentModel;
use Zhiyi\Plus\Component\ZhiyiPlus\PlusComponentGroup\Models\Group as GroupModel;
use Zhiyi\Plus\Component\ZhiyiPlus\PlusComponentGroup\Models\GroupPost as GroupPostModel;

class PinnedController extends Controller
{	
	/**
	 *  do top comment from user
	 *  @author Wayne < qiaobinloverabbi@gmail.com >
	 *  @param  Request        $request [description]
	 *  @param  GroupModel     $group   [description]
	 *  @param  GroupPostModel $post    [description]
	 *  @param  CommentModel   $comment [description]
	 *  @return [type]
	 */
	public function commentPinned(Request $request, GroupModel $group, GroupPostModel $post, CommentModel $comment)
	{
		$user = $request->user();

		// 判断是否当前用户对自己的评论发起的置顶申请
		if($user->id !== $comment->user_id) {
			abort(403, '你没有申请权限');
		}
	}
}