<?php
use Illuminate\Support\Facades\Route;
use Zhiyi\Plus\Http\Middleware;

Route::prefix('/groups')->group(function () {
	// 圈子首页
	Route::get('/', 'GroupController@index');

	// 圈子详情
	// 
	Route::get('/{group}', 'GroupController@show')
		->where('group', '[0-9]+');

	// 获取圈子的动态详情
	Route::get('/{group}/posts/{post}', 'GroupPostController@show')
		->where('group', '[0-9]+')
		->where('post', '[0-9]+');

    // 获取圈子动态列表
    Route::get('/{group}/posts', 'GroupPostController@posts')
        ->where('group', '[0-9]+');

	// 圈子成员
	Route::get('/{group}/members', 'GroupMemberController@members')
		->where('group', '[0-9]+');
        
    // Get all comments.
    Route::get('/{group}/posts/{post}/comments', 'GroupPostCommentController@index');

    // 动态点赞用户
    Route::get('/{group}/posts/{post}/diggs', 'GroupPostDiggController@diggs')
        ->where('group', '[0-9]+')
        ->where('post', '[0-9]+');
	/*
     * 需要授权的路由
     */
    Route::middleware('auth:api')->group(function () {
    	// 创建圈子
    	Route::post('/', 'GroupController@store');

    	// 加入的圈子
    	Route::get('/joined', 'GroupController@joined');

    	// 加入
    	Route::post('/{group}/join', 'GroupMemberController@join')
    		->where('group', '[0-9]+');

    	// 退出
    	Route::delete('/{group}/join', 'GroupMemberController@quit')
    		->where('group', '[0-9]+');

    	// 创建圈子动态
    	Route::post('/{group}/posts', 'GroupPostController@store')
    		->where('group', '[0-9]+');

        Route::delete('/{group}/posts/{post}', 'GroupPostController@destory')
            ->where('group', '[0-9]+')
            ->where('post', '[0-9]+');

        // 创建帖子评论
        Route::post('/{group}/posts/{post}/comment', 'GroupPostCommentController@store')
            ->where('group', '[0-9]+')
            ->where('post', '[0-9]+');

        // 删除评论
        Route::delete('/{group}/posts/{post}/comments/{comment}', 'GroupPostCommentController@destory');

        // 点赞动态
        Route::post('/{group}/posts/{post}/digg', 'GroupPostDiggController@store')
            ->where('group', '[0-9]+')
            ->where('post', '[0-9]+');

        // 取赞动态
        Route::delete('/{group}/posts/{post}/digg', 'GroupPostDiggController@destory')
            ->where('group', '[0-9]+')
            ->where('post', '[0-9]+');

        // 收藏动态
        Route::post('/{group}/posts/{post}/collection', 'GroupPostCollectionController@store')
            ->where('group', '[0-9]+')
            ->where('post', '[0-9]+');

        // 取消收藏动态
        Route::delete('/{group}/posts/{post}/collection', 'GroupPostCollectionController@destory')
            ->where('group', '[0-9]+')
            ->where('post', '[0-9]+');

        // 我收藏的动态
        Route::get('/posts/collections', 'GroupPostCollectionController@myCollections')
            ->where('group', '[0-9]+')
            ->where('post', '[0-9]+');

    });
});	
