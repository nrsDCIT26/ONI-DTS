<li class="{{ Request::is('admin/home*') ? 'active' : '' }}">
    <a href="{!! route('admin.dashboard') !!}"><i class="fa fa-home"></i><span> HOME</span></a>
</li>
@can('read users')
    <li class="{{ Request::is('admin/users*') ? 'active' : '' }}">
        <a href="{!! route('users.index') !!}"><i class="fa fa-users"></i><span> USERS</span></a>
    </li>
@endcan
@can('read tags')
    <li class="{{ Request::is('admin/tags*') ? 'active' : '' }}">
        <a href="{!! route('tags.index') !!}"><i
                class="fa fa-tags"></i><span style="text-transform: uppercase;"> {{ucfirst(config('settings.tags_label_plural'))}}</span></a>
    </li>
@endcan
@can('viewAny',\App\Document::class)
    <li class="treeview {{ Request::is('admin/documents*') ? 'active' : '' }}">
        <a href="{!! route('documents.index') !!}">
            <i class="fa fa-file-text-o"></i>
            <span>DOCUMENTS</span>
            <span class="pull-right-container">
              <i class="fa fa-angle-left pull-right"></i>
            </span>
        </a>
        <ul class="treeview-menu">
        <li class="{{ Request::is('admin/documents/create*') ? 'active' : '' }}">
            <a href="{{ route('documents.create') }}"><i class="fa fa-solid fa-file-arrow-up"></i><span>Upload</span></a>
        </li>
        </li>
        <li class="{{ Request::query('status') == 'PENDING' ? 'active' : '' }}">
        <a href="{{ route('documents.index', ['status' => 'PENDING']) }}"><i class="fa fa-bell"></i><span>New</span></a>
        </li>
        <li class="{{ Request::query('status') == 'FORWARDED' ? 'active' : '' }}">
        <a href="{{ route('documents.index', ['status' => 'FORWARDED']) }}"><i class="fa fa-forward"></i><span>Forwarded</span></a>
        </li>
        <li class="{{ Request::query('status') == 'RETURNED' ? 'active' : '' }}">
        <a href="{{ route('documents.index', ['status' => 'RETURNED']) }}"><i class="fa fa-forward"></i><span>Returned</span></a>
        </li>
        <li class="{{ Request::query('status') == 'APPROVED' ? 'active' : '' }}">
            <a href="{{ route('documents.index', ['status' => 'APPROVED']) }}"><i class="fa fa-solid fa-circle-check"></i><span>Approved</span></a>
        </li>
        <li class="{{ Request::query('status') == 'DECLINED' ? 'active' : '' }}">
            <a href="{{ route('documents.index', ['status' => 'DECLINED']) }}"><i class="fa fa-solid fa-file-excel"></i><span>Declined</span></a>
        </li>
        </ul>
    </li>
@can('user manage permission')
@if(!auth()->user()->is_super_admin)
    <li class="treeview">
        <a href="{!! route('documents.index') !!}">
            <i class="fa fa-file-text-o"></i>
            <span>RECEIVED FILES</span>
            <span class="pull-right-container">
              <i class="fa fa-angle-left pull-right"></i>
            </span>
        </a>
        <ul class="treeview-menu">
            <li class="">
                <a href=""><i class="fa fa-solid fa-file-arrow-down"></i></i><span>New </span></a>
            </li>
            <li class="">
                <a href="{!! route('documents.index', ['status' => 'APPROVED']) !!}"><i class="fa fa-solid fa-circle-check"></i><span>Approved</span></a>
            </li>
            <li class="">
                <a href="{!! route('documents.index', ['status' => 'DECLINED']) !!}"><i class="fa fa-solid fa-file-excel"></i><span>Declined</span></a>
            </li>
        </ul>
    </li>
    @endif
@endcan
@endcan
@if(auth()->user()->is_super_admin)
    <li class="treeview {{ Request::is('admin/advanced*') ? 'active' : '' }}">
        <a href="#">
            <i class="fa fa-info-circle"></i>
            <span>ADVANCE SETTINGS</span>
            <span class="pull-right-container">
              <i class="fa fa-angle-left pull-right"></i>
            </span>
        </a>
        <ul class="treeview-menu">
            <li class="{{ Request::is('admin/advanced/settings*') ? 'active' : '' }}">
                <a href="{!! route('settings.index') !!}"><i class="fa fa-gear"></i><span>Settings</span></a>
            </li>
            <li class="{{ Request::is('admin/advanced/custom-fields*') ? 'active' : '' }}">
                <a href="{!! route('customFields.index') !!}"><i
                        class="fa fa-file-text-o"></i><span>Custom Fields</span></a>
            </li>
            <li class="{{ Request::is('admin/advanced/file-types*') ? 'active' : '' }}">
                <a href="{!! route('fileTypes.index') !!}"><i class="fa fa-file-o"></i><span>{{ucfirst(config('settings.file_label_singular'))}} Types</span></a>
            </li>
        </ul>
    </li>
@endif

