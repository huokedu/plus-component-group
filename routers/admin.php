<?php

use Illuminate\Support\Facades\Route;

Route::prefix('groups')->group(function() {
    /**
     * 圈子列表
     */
    Route::get('/', 'GroupController@index');

    /**
     * 圈子审核
     */
    Route::patch('/{gid}/audit', 'GroupController@audit')
    ->where('gid', '[0-9]+');

    /**
     * 圈子所有动态
     */
    Route::get('/posts', 'GroupController@groupPosts');

    /**
     *某个圈子的动态
     */
    Route::get('/{gid}/posts', 'GroupController@posts')
    ->where('gid', '[0-9]+');
});



