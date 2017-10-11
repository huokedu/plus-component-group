@extends('group::layouts.app')
@section('style')
    <style>
        img {
            height: 20px;
            width: 20px;
            display: inline-block;
            margin-right: 20px;
        }
    </style>
@endsection
@section('content')
    <div class="panel-heading">
        创建圈子
    </div>
    <div class="panel-body">
        <div class="form-horizontal">
            <form action="{{ route('put:group', $group->id) }}" method="POST">
                <div class="col-md-5 col-md-offset-2">
                    <div class="form-group">
                        <label class="control-label">圈子名称：</label>
                        <input type="text" class="form-control" placeholder="圈子名称" name="title" value="{{ $group->title }}">
                    </div>
                    <div class="form-group">
                        <label class="control-label">圈子描述：</label>
                        <textarea class="form-control" placeholder="圈子描述" class="intro" name="intro">{{ $group->intro }}</textarea>
                    </div>
                    <div class="form-group">
                        <label for="">圈子头像：</label>
                        <div class="input-group">
                            <input type="text" class="form-control preview-input" id="avatar"
                                   value="{{ empty($group->avatar) ? '' : url('api/v2/files'.'/'.$group->avatar->id) }}" disabled>
                            <input type="text" name="avatar" class="hide" id="avatar-id"
                                   value="{{ empty($group->avatar) ? '' : $group->avatar->id}}">
                            <div class="input-group-btn">
                                <button class="btn btn-primary preview" type="button">预览</button>
                                <button class="btn btn-default" id="avatar-upload-btn" data-loading-text="上传中" type="button">上传</button>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="">圈子封面：</label>
                        <div class="input-group">
                            <input type="text" class="form-control preview-input" id="cover" name="cover"
                                   value="{{ empty($group->cover) ? '' : url('api/v2/files'.sprintf('/%d', $group->cover->id)) }}" disabled>
                            <input type="text" name="cover" class="hide" id="cover-id"
                                   value="{{ empty($group->cover) ? '' : $group->cover->id}}">
                            <div class="input-group-btn">
                                <button class="btn btn-primary preview" type="button">预览</button>
                                <button class="btn btn-default" id="cover-upload-btn" data-loading-text="上传中" type="button">上传</button>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="control-label">圈子创建者：</label>
                        <div class="input-group-btn">
                            <div class="row">
                                <div class="col-md-6">
                                    <?php
                                        $managers = $group->managers;
                                        $founder = '';
                                        if ($managers->count()) {
                                            foreach($managers as $manager) {
                                                if ($manager->founder === 1) {
                                                    $founder = $manager->user_id;
                                                    break;
                                                }
                                            }
                                        }
                                    ?>
                                    <input type="text" class="form-control" id="founder" name="founder" placeholder="创建者ID" value="{{ $founder }}">
                                </div>
                                <div class="col-md-6">
                                    <input type="text" class="form-control" id="serach" placeholder="输入用户名搜索">
                                    <ul class="dropdown-menu" style="margin-left:15px;"></ul>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <button class="btn btn-primary">确认</button>
                    </div>
                </div>
                {{ csrf_field() }}
                {{ method_field('PUT') }}
            </form>
        </div>
        <input type="file" id="file-input" style="display:none;">
    </div>
@endsection
@section('javascript')
    <script>
        $(function(){
            $('#serach').on('input', function(){
                $.ajax({
                    url: '/api/v2/user/search?keyword='+$(this).val(),
                    type: 'get',
                    dataType: 'json',
                    success: function(data) {
                        addUserToMenu(data);
                        $('.dropdown-menu').show();
                    }
                })
            });

            function addUserToMenu(data) {
                var html = '';
                if (data.length) {
                    for (var i=0; i<data.length; i++) {
                        html += '<li><a href="javascript:;" data-id="'+data[i].id+'"><img src="'+data[i].avatar+'" class="img-circle"><span>'+data[i].name+'</span></a></li>';
                    }
                } else {
                    html += '<li class="text-center">无相关用户</li>';
                }
                $('.dropdown-menu').html('').html(html);
            }

            // 光标是否在menu上
            var isDropdownMenu = false;
            $('.dropdown-menu').hover(function(){
                isDropdownMenu = true;
            }, function(){
                isDropdownMenu = false;
            });

            $('.dropdown-menu').on('click', 'li a', function(){
                $('#founder').val($(this).attr('data-id'));
                $('#serach').val('');
                $('.dropdown-menu').hide();
            });

            $('#serach').on('blur', function(){
                if (!isDropdownMenu) $('.dropdown-menu').hide();
            });

            var btnId = '';
            $('#cover-upload-btn,#avatar-upload-btn').on('click', function (e) {
                btnId = $(this).attr('id');
                $('#file-input').click();
            });

            // 文件上传
            $('#file-input').on('change', function (e) {

                var file = e.target.files[0];
                var param = new FormData();

                param.append('file', file);
                param.append('_token', "{{ csrf_token() }}");

                $.ajax({
                    url: "{{ url('api/v2/files') }}",
                    type: 'post',
                    data: param,
                    cache: false,
                    headers: {'Authorization': 'Bearer ' + window.authToken},
                    contentType: false,
                    processData: false,
                    success: function (response) {
                        var url = "{{ url('api/v2/files') }}/"+response.id;
                        if (btnId == 'cover-upload-btn') {
                            $('#cover').val(url);
                            $('#cover-id').val(response.id);
                        } else {
                            $('#avatar').val(url);
                            $('#avatar-id').val(response.id);
                        }
                    },
                    error: function (response) {}
                });
            });

            $('.preview').on('click', function (e) {
                var url = $(this).parent().parent().find('.preview-input').val();
                console.log(url);
                if (url) {
                    window.open(url);
                } else {
                    alert('请上传，在预览');
                }
            })
        })
    </script>
@endsection