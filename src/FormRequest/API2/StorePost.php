<?php

namespace Zhiyi\Component\ZhiyiPlus\PlusComponentGroup\FormRequest\API2;

use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Auth\Access\AuthorizationException;

Class StorePost extends FormRequest
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
            'content' => 'bail|required_without:images|max:10000',
            'images' => ['required_without:content', 'array'],
            'images.*.id' => [
                'required_with:images',
                'distinct',
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
			'content.required_without' => '内容不能为空',
			'images.required_without' => '没有发生任何内容',
			'images.*.id.required_without' => '发送的文件不存在',
			'images.*.id.distinct' => '发送的文件中存在重复内容',
			'images.*.id.exists' => '文件不存在或已经被使用',
		];
	}

	/**
     * Handle a failed authorization attempt.
     *
     * @return void
     *
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    protected function failedAuthorization()
    {
        throw new AuthorizationException('你没有发布动态权限');
    }
}