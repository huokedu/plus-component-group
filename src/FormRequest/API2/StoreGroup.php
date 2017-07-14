<?php

namespace Zhiyi\Component\ZhiyiPlus\PlusComponentGroup\FormRequest\API2;

use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Auth\Access\AuthorizationException;

Class StoreGroup extends FormRequest
{
	/**
	 * 创建权限判断
	 * @return [type] [description]
	 */
	public function authorize(): bool
	{
		// TODO
		return true;
	}

	public function rules(): array
	{
		return [
            'title' => 'bail|required|unique:groups|max:30',
            'intro' => 'bail|required|max:100',
            'avatar' => [
            	'required',
            	Rule::exists('file_withs', 'id')->where(function ($query) {
            		$query->where('channel', null);
            		$query->where('raw', null);
            	})
            ],
            'cover' => [
            	'required',
            	Rule::exists('file_withs', 'id')->where(function ($query) {
            		$query->where('channel', null);
            		$query->where('raw', null);
            	}),
            ]
        ];
	}

	public function messages(): array
	{
		return [
			'title.required' => '圈子名不能为空',
			'title.unique' => '圈子已经存在',
			'title.max' => '名称最多30个字',
			'intro.required' => '圈子简介不能为空',
			'intro.max' => '简介最多100个字',
			'avatar.required' => '圈子图标必传',
			'avatar.integer' => '图标类型错误',
			'avatar.exists' => '文件不存在或已经被使用',
			'cover.required' => '背景图必传',
			'cover.integer' => '背景图类型错误',
			'cover.exists' => '文件不存在或已经被使用'
			// 'title' => [
			// 	'required' => '圈子名不能为空',
			// 	'unique' => '圈子已经存在',
			// 	'max' => '名称最大30个字'
			// ],
			// 'intro' => [
			// 	'required' => '圈子简介不能为空',
			// 	'max' => '简介最多100个字'
			// ],
			// 'avatar' => [
			// 	'required' => '圈子图标必传',
			// 	'integer' => '图标类型错误',
			// 	'exists' => '文件不存在或已经被使用'
			// ],
			// 'cover' => [
			// 	'required' => '圈子背景图必传',
			// 	'integer' => '背景图类型错误',
			// 	'exists' => '文件不存在或已经被使用'
			// ],
		];
	}
}