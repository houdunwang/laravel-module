@extends('admin::layouts.master')
@section('content')
    <div class="card">
        <div class="card-header">{MODEL_TITLE}管理</div>
        <div class="tab-container">
            <ul role="tablist" class="nav nav-tabs">
                <li class="nav-item"><a href="/{SMODULE}/{SMODEL}" class="nav-link">{MODEL_TITLE}列表</a></li>
                <li class="nav-item"><a href="/{SMODULE}/{SMODEL}/create" class="nav-link active">添加{MODEL_TITLE}</a></li>
            </ul>
            <div class="card card-contrast card-border-color-success">
                <form action="/{SMODULE}/{SMODEL}/{{${SMODEL}['id']}}" method="post">
                    @csrf @method('PUT')
                    {!! $html !!}
                    <button class="btn btn-perimary">保存提交</button>
                </form>
            </div>
        </div>
    </div>
@endsection
