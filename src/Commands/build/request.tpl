<?php

namespace {NAMESPACE}Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class {MODEL}Request extends FormRequest
{
    //验证规则
    public function rules()
    {
        return {REQUEST_RULE};
    }

    //错误信息处理
    public function messages()
    {
        return {REQUEST_RULE_MESSAGE};
    }

    //权限验证
    public function authorize()
    {
        return true;
    }
}
