<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    @include('group::partials.style')
    @yield('style')
    <script>
        window.authToken = "{{ Tymon\JWTAuth\Facades\JWTAuth::fromUser(request()->user()) }}";
    </script>
</head>
<body>
    <div class="context-container" style="margin: 15px;">
        @include('group::partials.header')
        <!-- 提示组件 -->
        @component('group::component.alert')@endcomponent
        <div class="panel panel-default" style="margin-top: 15px;">
            @yield('content')
        </div>
    </div>
</body>
@include('group::partials.javascript')
<script>
    function deleteConfirm(title) {
        $('.del-btn').on('click', function(){
            var ok = confirm(title);
            ok && $(this).parent().submit(); 
        });
    }
</script>
@yield('javascript')
</html>