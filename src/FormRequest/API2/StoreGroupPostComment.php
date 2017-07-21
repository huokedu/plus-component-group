<?php

namespace Zhiyi\Component\ZhiyiPlus\PlusComponentGroup\FormRequest\API2;

use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Auth\Access\AuthorizationException;

class StoreGroupPostComment extends FormRequest
{
	/**
	 * 创建权限判断
	 * @return [type] [description]
	 */
	public function authorize(): bool
	{
		return $this->user() ? true : false;
	}

	public function rules(): array
	{
		return [
			'body' => 'bail|required|display_length:255',
			'reply_user' => 'nullable|integer|exists:users,id',
		];
	}

	public function messages(): array
	{
		return [
			'body.required' => '没有发送任何内容',
			'body.display_length' => '评论超出最大限制',
			'reply_user.integer' => '回复用户不合法',
			'reply_user.exists' => '回复用户不存在',
		];
	}
}