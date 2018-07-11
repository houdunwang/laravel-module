@extends('admin::layouts.master')
@section('content')
    <div class="card">
        <div class="card-header">{MODEL_TITLE}管理</div>
        <div class="tab-container">
            <ul role="tablist" class="nav nav-tabs">
                <li class="nav-item"><a href="/{SMODULE}/{SMODEL}" class="nav-link active">{MODEL_TITLE}列表</a></li>
                <li class="nav-item"><a href="/{SMODULE}/{SMODEL}/create" class="nav-link">添加{MODEL_TITLE}</a></li>
            </ul>
            <div class="card card-contrast card-border-color-success">
                <div class="card-body">
                    <table class="table">
                        <thead>
                        <tr>
                            <th style="width: 10%;">编号</th>
                            @foreach($columns as $column)
                                @isset($column['title'])
                                    <th>{{$column['title']}}</th>
                                @endisset
                            @endforeach
                            <th>创建时间</th>
                            <th>修改时间</th>
                            <th>&nbsp;</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($data as $d)
                            <tr>
                                <td>{{$d['id']}}</td>
                                @foreach($columns as $column)
                                    @isset($column['title'])
                                        <td>
                                            {{$handle->value($column,$d)}}
                                        </td>
                                    @endisset
                                @endforeach
                                <td>{{$d['created_at']}}</td>
                                <td>{{$d['updated_at']}}</td>
                                <td class="text-right">
                                    <a href="/{SMODULE}/{SMODEL}/{{$d['id']}}/edit" class="btn btn-secondary">编辑</a>
                                    <button type="button" class="btn btn-secondary btn-danger" onclick="del({{$d['id']}},this)">删除</button>
                                    <form action="/{SMODULE}/{SMODEL}/{{$d['id']}}" hidden method="post">
                                        @csrf @method('DELETE')
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <div class="float-right">
        {{$data->links()}}
    </div>
@endsection
@section('scripts')
    <script>
        function del(id, el) {
            if (confirm('确定删除吗？')) {
                $(el).next('form').trigger('submit')
            }
        }
    </script>
@endsection
