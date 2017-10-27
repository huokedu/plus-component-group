@extends('group::layouts.app')
@section('content')
    <div class="panel-heading">
        圈子成员
    </div>
    <div class="panel-body">
        <!-- 列表数据 -->
        <table class="table table-responsive">
            <thead>
            <tr>
                <th>名字</th>
                <th>状态</th>
            </tr>
            </thead>
            <tbody>
                @if ($members->count())
                    @foreach($members as $member)
                        <tr>
                            <td>
                                {{ $member->user->name }}
                            </td>
                            <td>
                                {{ $member->is_audit ? '未通过' : '通过' }}
                            </td>
                        </tr>
                    @endforeach
                @else
                    <tr><td colspan="2" style="text-align: center;">无相关记录</td></tr>
                @endif
            </tbody>
        </table>
    </div>
@endsection
@section('javascript')

@endsection