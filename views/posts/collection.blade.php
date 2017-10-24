@extends('group::layouts.app')
@section('content')
    <div class="panel-heading">
        <ol class="breadcrumb">
          <li><a href="{{ route('groups:posts') }}">帖子管理</a></li>
          <li class="active">帖子收藏管理</li>
        </ol>
    </div>
    <div class="panel-body">
        <!-- 列表数据 -->
        <table class="table table-responsive">
            <thead>
            <tr>
                <th>收藏者</th>
                <th>被收藏动态</th>
                <th>收藏时间</th>
            </tr>
            </thead>
            <tbody>
            @if($items->count())
                @foreach($items as $item)
                    <tr>
                        <td>{{ $item->user->name }}</td>
                        <td>{{ $item->post->title }}</td>
                        <td>{{ $item->created_at }}</td>
                    </tr>
                @endforeach
            @else
                <tr><td colspan="10" style="text-align: center;">无相关记录</td></tr>
            @endif
            </tbody>
        </table>
        <!-- 分页 -->
        <div class="text-center">
            {{ $items->links() }}
        </div>
    </div>
@endsection
@section('javascript')
@endsection