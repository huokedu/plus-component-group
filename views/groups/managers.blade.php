@extends('group::layouts.app')
@section('content')
    <!-- 列表数据 -->
    <table class="table table-responsive">
        <caption>
            <div class="panel-title">圈子成员</div>
        </caption>
        <thead>
        <tr>
            <th>名字</th>
            <th>操作</th>
        </tr>
        </thead>
        <tbody>
        @if ($managers->count())
            @foreach($managers as $manager)
                <tr>
                    <td>
                        {{ $manager->user->name }}
                        @if($manager->founder===1)
                            <label  class="label label-success">管理员</label>
                        @endif
                    </td>
                    <td><button class="btn btn-danger btn-sm">删除</button></td>
                </tr>
            @endforeach
        @else
            <tr><td colspan="2" style="text-align: center;">无相关记录</td></tr>
        @endif
        </tbody>
    </table>
@endsection
@section('javascript')

@endsection