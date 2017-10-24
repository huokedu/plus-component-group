<div>
    <nav class="navbar navbar-default">
      <div class="container-fluid">
        <div class="navbar-header">
          <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1" aria-expanded="false">
            <span class="sr-only">切换导航条</span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
          </button>
          <div class="navbar-brand">圈子</div>
        </div>
        <!-- Collect the nav links, forms, and other content for toggling -->
        <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
          <!-- The settings nav. -->
          <ul class="nav navbar-nav">
	        <?php $segment = request()->segments()[2]; ?>
	        <li role="presentation" class="{{ $segment === 'groups' ? 'active' : '' }}">
	            <a href="{{ route('group:admin') }}">圈子管理</a>
	        </li>
	        <li role="presentation" class="{{ $segment === 'posts' ? 'active' : '' }}">
	            <a href="{{ route('groups:posts') }}">帖子管理</a>
	        </li>
          <li role="presentation" class="{{ $segment === 'comments' ? 'active' : '' }}">
              <a href="{{ route('posts:comments:index') }}">帖子评论管理</a>
          </li>
          </ul>
        </div>    
      </div>
    </nav>
</div>