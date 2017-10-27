@extends('group::layouts.app')
@section('content')
    <div class="panel-heading">
        <div class="form-inline">
            <div class="form-group">
                <input type="text"
                       class="form-control"
                       placeholder="评论者ID/用户名搜索"
                       name="keyword"
                       id="keyword">
            </div>
            <div class="form-group">
                <input type="text"
                       class="form-control"
                       placeholder="内容搜索"
                       name="body"
                       id="body">
            </div>
            <div class="form-group">
                <button class="btn btn-default" id="search">搜索</button>
            </div>
        </div>
    </div>
    <div class="panel-body">
        <!-- 列表数据 -->
        <table class="table table-responsive">
            <thead>
            <tr>
                <th>#ID</th>
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
                        <td>{{ $item->id }}</td>
                        <td>{{ $item->user->name }}</td>
                        <td>{{ $item->target->name }}</td>
                        <td>{{ $item->reply ? $item->reply->name : '' }}</td>
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
    $(function() {
        @if (isset($query['keyword']))
          $('#keyword').val("{{ $query['keyword'] }}");
        @endif
        @if (isset($query['body']))
          $('#body').val("{{ $query['body'] }}");
        @endif
        deleteConfirm('确定要删除评论吗？');
        $('#search').click(function () {
           var url = "{{ route('posts:comments:index') }}";
           url += '?keyword=' + $('#keyword').val();
           url += '&body=' + $('#body').val();
           window.location = url;
        });
    })
</script>
@endsection