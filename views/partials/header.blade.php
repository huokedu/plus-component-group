<div>
    <ul class="nav nav-tabs nav-component" style="margin-top: 4px; ">
        <?php $segment = request()->segments()[2]; ?>
        <li role="presentation" class="{{ $segment === 'groups' ? 'active' : '' }}">
            <a href="{{ route('group:admin') }}">圈子管理</a>
        </li>
        <li role="presentation" class="{{ $segment === 'posts' ? 'active' : '' }}">
            <a href="{{ route('groups:posts') }}">帖子管理</a>
        </li>
    </ul>
</div>