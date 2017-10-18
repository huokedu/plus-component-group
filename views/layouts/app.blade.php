<!-- extend bootstrap blade -->
@extends('layouts.bootstrap')

@section('title', '圈子')

@section('head')
    @parent
    @yield('style')  
@endsection
@section('body')
    <div class="context-container">
        @include('group::partials.header')
        <!-- 提示组件 -->
        @component('group::component.alert')@endcomponent
        <div class="panel panel-default" style="margin: 15px;">
            @yield('content')
        </div>
    </div>
    @parent
    <script>
        window.authToken = "{{ Tymon\JWTAuth\Facades\JWTAuth::fromUser(request()->user()) }}";
        function deleteConfirm(title) {
            $('.del-btn').on('click', function(){
                confirm(title) && $(this).parent().submit(); 
            });
        }
    </script>
    @yield('javascript')
@endsection