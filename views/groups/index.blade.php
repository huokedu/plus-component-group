@extends('group::layouts.app')
@section('content')
<!-- 列表数据 -->
<table class="table table-responsive">
    <caption>
        <!-- 提示组件 -->
        @component('group::component.alert')@endcomponent
        <div class="panel-title">圈子列表</div>
        <div>
            <div class="form-inline">
                <div class="form-group">
                    <label for="">搜索：</label>
                    <input type="text"
                           class="form-control"
                           placeholder="关键词搜索"
                           name="keyword"
                           id="keyword">
                </div>
                <div class="form-group">
                    <label for="">状态</label>
                    <select id="audit" class="form-control">
                        <option value="">全部</option>
                        <option value="1">已审核</option>
                        <option value="0">未审核</option>
                    </select>
                </div>
                <div class="form-group">
                    <button class="btn btn-default" id="search">搜索</button>
                </div>
            </div>
        </div>
    </caption>
    <thead>
    <tr>
        <th>圈子名</th>
        <th>简介</th>
        <th>动态数</th>
        <th>成员数</th>
        <th>状态</th>
        <th>创建者</th>
        <th>时间</th>
        <th>操作</th>
    </tr>
    </thead>
    <tbody>
    @if ($items->count())
        @foreach($items as $item)
            <tr>
                <td>{{ $item->title }}</td>
                <td>{{ $item->intro }}</td>
                <td>
                    <a href="{{ route('group:posts', $item->id) }}">{{ $item->posts_count }}</a>
                </td>
                <td>
                    <a href="{{ route('group:managers', $item->id) }}">{{ $item->members_count }}</a>
                </td>
                <td>
                    <a class="label {{ $item->is_audit ? 'label-success':'label-danger' }}">{{ $item->is_audit ? '已审核' : '未审核' }}</a>
                    <form action="{{ route('group:audit', $item->id) }}" method="post">
                        {{ method_field('PATCH') }}
                        {{ csrf_field() }}
                    </form>
                </td>
                <td>{{ $item->managers[0]->user->name  }}</td>
                <td>{{ $item->created_at }}</td>
                <td>
                    <form action="{{ route('group:delete', $item->id) }}" method="post">
                        {{ method_field('DELETE') }}
                        {{ csrf_field() }}
                        <button class="btn btn-danger btn-sm">删除</button>
                    </form>
                </td>
            </tr>
        @endforeach
    @else
        <tr><td colspan="9" style="text-align: center;">无相关记录</td></tr>
    @endif
    </tbody>
</table>
<div class="text-right">
    {{ $items->links() }}
</div>
@endsection
@section('javascript')
    <script>
        $(function(){
            @if (isset($query['keyword']))
                $('#keyword').val("{{ $query['keyword'] }}");
            @endif

            @if (isset($query['audit']))
                $('#audit').val("{{ $query['audit'] }}");
            @endif
            /**
             * 获取筛选参数
             * @returns {string}
             */
            function getQueryParams()
            {
                var keyword = $('#keyword').val();
                var audit   = $('#audit').val();
                var query = '';

                if ( keyword ) {
                    query += '?keyword=' + keyword;
                }
                if ( audit ) {
                    if ( query.indexOf('?') !== -1 ) {
                        query += '&audit=' + audit;
                    } else {
                        query += '?audit=' + audit;
                    }
                }

                return query;
            }

            /**
             * 数据筛选
             */
            $('#search').click(function () {
               var url = "{{ route('group:admin') }}";
               window.location = url + getQueryParams();
            });
            /**
             * 状态更新
             */
            $('.label').click(function () {
                $(this).parent().find('form').submit();
            });
        })
    </script>
@endsection