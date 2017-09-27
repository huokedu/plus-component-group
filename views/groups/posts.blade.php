@extends('group::layouts.app')
@section('content')
    <div class="panel-heading">
        <div class="form-inline">
            <div class="form-group">
                <form action="">
                    <input type="text" class="form-control" id="keyword" placeholder="关键词搜索" name="keyword">
                    <button class="btn btn-default" id="search">搜索</button>
                </form>
            </div>
            <div class="form-group pull-right">
                <button class="btn btn-primary" onclick="window.history.go(-1)">返回</button>
            </div>
        </div>
    </div>
    <div class="panel-body">
        <!-- 列表数据 -->
        <table class="table table-responsive">
            <thead>
            <tr>
                <th>标题</th>
                <th>圈子</th>
                <th>阅读数</th>
                <th>点赞数</th>
                <th>收藏数</th>
                <th>评论数</th>
                <th>用户</th>
                <th>状态</th>
                <th>创建日期</th>
                <th>操作</th>
            </tr>
            </thead>
            <tbody>
            @if($posts->count())
                @foreach($posts as $post)
                    <tr>
                        <td>{{ $post->title }}</td>
                        <td>{{ $post->content }}</td>
                        <td>{{ $post->views }}</td>
                        <td><a href="">{{ $post->diggs }}</a></td>
                        <td><a href="">{{ $post->collections }}</a></td>
                        <td><a href="">{{ $post->comments_count }}</a></td>
                        <td>{{ $post->user->name }}</td>
                        <td>
                            <a class="label {{ $post->is_audit ? 'label-success':'label-danger' }}" href="javascript:;">
                                {{ $post->is_audit ? '已审核' : '未审核' }}</a>
                            <form action="{{ route('post:audit', $post->id) }}" method="post">
                                {{ method_field('PATCH') }}
                                {{ csrf_field() }}
                            </form>
                        </td>
                        <td>{{ $post->created_at }}</td>
                        <td>
                            <form action="{{ route('post:delete', $post->id) }}" method="post">
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
            {{ $posts->links() }}
        </div>
    </div>
@endsection
@section('javascript')
    <script>
        $(function(){
            @if( isset($query['keyword']) )
                $('#keyword').val("{{ $query['keyword'] }}");
            @endif

            $('.label').click(function () {
                $(this).parent().find('form').submit();
            });

            function getQueryParams()
            {
                var keyword = $('#keyword').val();

                var query = '';
                if ( keyword ) {
                    query += '?keyword=' + keyword;
                }

                return query;
            }

            $('#search').click(function(){
                var query = getQueryParams();
                var url = window.location.origin+window.location.pathname;
                window.location = url+query;
            });

            deleteConfirm('确定要删除帖子吗？');

        })
    </script>
@endsection