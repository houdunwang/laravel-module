<?php
namespace {NAMESPACE}Http\Controllers;

use App\Http\Controllers\Controller;
use {NAMESPACE}Entities\{MODEL};
use Illuminate\Http\Request;
use {NAMESPACE}Tables\{MODEL}Handle;

class {MODEL}Controller extends Controller
{
    //显示列表
    public function index()
    {
        $data = Role::paginate(10);
        return view('{SMODULE}::{SMODEL}.index', compact('data'));
    }

    //创建视图
    public function create(RoleRequest $request)
    {
        $handle = new {MODEL}Handle(new {MODEL});
        $html   = $handle->render();
        return view('{SMODULE}::{SMODEL}.create', compact('html'));
    }

    //保存数据
    public function store({MODEL}Request $request,{MODEL} ${SMODEL})
    {
        ${SMODEL}->fill($request->all());
        ${SMODEL}->save();

        return back()->with('success', '保存成功');
    }

    //显示记录
    public function show({MODEL} ${SMODEL})
    {
        $handle = new {MODEL}Handle(${SMODEL});
        $html   = $handle->render();
        return view('{SMODULE}::{SMODEL}.show', compact('html'));
    }

    //编辑视图
    public function edit({MODEL} ${SMODEL})
    {
        $handle = new {MODEL}Handle(${SMODEL});
        $html   = $handle->render();
        return view('{SMODULE}::{SMODEL}.edit', compact('html','{SMODEL}'));
    }

    //更新数据
    public function update({MODEL}Request $request, {MODEL} ${SMODEL})
    {
        ${SMODEL}->update($request->all());
        return redirect('/{SMODULE}/{SMODEL}')->with('success','更新成功');
    }

    //删除模型
    public function destroy({MODEL} ${SMODEL})
    {
        ${SMODEL}->delete();
        return redirect('/{SMODULE}/{SMODEL}')->with('success','删除成功');
    }
}
