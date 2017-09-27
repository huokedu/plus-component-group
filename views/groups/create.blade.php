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
            <form action="{{ route('post:group') }}" method="POST">
                <div class="col-md-5 col-md-offset-2">
                    <div class="form-group">
                        <label class="control-label">圈子名称：</label>
                        <input type="text" class="form-control" placeholder="圈子名称" name="title">
                    </div>  
                    <div class="form-group">
                        <label class="control-label">圈子描述：</label>
                        <textarea class="form-control" placeholder="圈子描述" class="intro" name="intro"></textarea>
                    </div>
                    <div class="form-group">
                        <label class="control-label">圈子创建者：</label>
                        <div class="input-group-btn">
                            <div class="row">
                                <div class="col-md-6">
                                    <input type="text" class="form-control" id="founder" name="founder" placeholder="圈子创建者ID">
                                </div>
                                <div class="col-md-6">
                                    <input type="text" class="form-control" id="serach">
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
            </form>
        </div>
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
})
</script>
@endsection