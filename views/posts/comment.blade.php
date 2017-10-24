@extends('group::layouts.app')
@section('content')
    <div class="panel-heading">
        <ol class="breadcrumb">
          <li><a href="{{ route('groups:posts') }}">帖子管理</a></li>
          <li class="active">帖子评论管理</li>
        </ol>
    </div>
    <div class="panel-body">
        <!-- 列表数据 -->
        <table class="table table-responsive">
            <thead>
            <tr>
                <th>评论者</th>
                <th>资源作者</th>
                <th>被回复者</th>
                <th>内容</th>
                <th>时间</th>
                <th>操作</th>
            </tr>
            </thead>
            <tbody>
            @if($items->count())
                @foreach($items as $item)
                    <tr>
                        <td>{{ $item->user->name }}</td>
                        <td>{{ $item->target->name }}</td>
                        <td>{{ $item->reply ? $items->reply->name : '' }}</td>
                        <td>{{ $item->body }}</td>
                        <td>{{ $item->created_at }}</td>
                        <td>
                            <form action="{{ route('posts:comments:delete', $item->id) }}" method="post">
                                {{ method_field('DELETE') }}
                                {{ csrf_field() }}
                                <button class="btn btn-danger btn-sm del-btn" type="button">删除</button>
                            </form>
                        </td>
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
<script>
    deleteConfirm('确定要删除评论吗？');
</script>
@endsection