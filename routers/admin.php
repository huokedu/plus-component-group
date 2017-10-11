<?php

use Illuminate\Support\Facades\Route;

Route::prefix('groups')->group(function() {
    /**
     * 圈子列表
     */
    Route::get('/', 'GroupController@index')
        ->name('group:admin');
    /**
     * 圈子审核
     */
    Route::patch('/{gid}/audit', 'GroupController@audit')
        ->where('gid', '[0-9]+')
        ->name('group:audit');
    /**
     * 某个圈子的动态
     */
    Route::get('/{groupId}/posts', 'GroupController@posts')
        ->where('groupId', '[0-9]+')
        ->name('group:posts');
    /**
     * 圈子成员
     */
    Route::get('/{groupId}/managers', 'GroupController@managers')
        ->where('groupId', '[0-9]+')
        ->name('group:managers');
    /**
     * 删除圈子
     */
    Route::delete('/{groupId}', 'GroupController@delete')
        ->where('groupId', '[0-9]+')
        ->name('group:delete');

    /**
     * 创建
     */
    Route::get('/create', 'GroupController@create')
        ->name('create:group');
    Route::post('/', 'GroupController@create')
        ->name('store:group');

    /**
     * 编辑
     */
    Route::get('/{groupId}/edit', 'GroupController@edit')
        ->where('postId', '[0-9]+')
        ->name('edit:group');
    Route::put('/{groupId}/edit', 'GroupController@edit')
        ->where('postId', '[0-9]+')
        ->name('put:group');
});

Route::prefix('posts')->group(function(){
    /**
     * 帖子
     */
    Route::get('/', 'GroupPostController@index')
        ->name('groups:posts');
    /**
     * 更新帖子状态
     */
    Route::patch('/{postId}/audit', 'GroupPostController@audit')
        ->where('postId', '[0-9]+')
        ->name('post:audit');

    /**
     * 删除帖子
     */
    Route::delete('/{postId}', 'GroupPostController@delete')
        ->where('postId', '[0-9]+')
        ->name('post:delete');
});



