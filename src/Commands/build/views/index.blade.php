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
                            <th style="width: 10%;"></th>
                            <th></th>
                            <th></th>
                            <th></th>
                            <th></th>
                            <th></th>
                        </tr>
                        </thead>
                        <tbody>
                        <tr>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td>

                            </td>
                        </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection
