<li class="{{ Request::is('admin/home*') ? 'active' : ''}}">
    <a href="{!! route('admin.dashboard') !!}"><i class="fa fa-home"></i><span> HOME</span></a>
</li>
@can('read users')
    <li class="{{ Request::is('admin/users*') ? 'active' : ''}}">
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
    <li class="{{ Request::is('admin/documents/create*') ? 'active' : '' }}">
            <a href="{{ route('documents.upload') }}"><i class="fa fa-solid fa-file-arrow-up"></i><span>UPLOAD</span></a>
    </li>
    <li class="treeview {{ Request::is('admin/documents*') ? 'active' : ''  }}">
        <a>
            <i class="fa fa-file-text-o"></i>
            <span>DOCUMENTS</span>
            <span class="pull-right-container">
              <i class="fa fa-angle-left pull-right"></i>
            </span>
        </a>
        <ul class="treeview-menu">
            <li class="{{ Request::query('status') == 'PENDING' ? 'active' : '' }}">
                <a href="{{ route('documents.index', ['status' => 'PENDING']) }}"><i class="fa fa-solid fa-paper-plane"></i></i><span>Sent</span></a>
            </li>
            <li class="{{ Request::query('status') == 'FORWARDED' ? 'active' : '' }}">
                <a href="{{ route('documents.index', ['status' => 'FORWARDED']) }}"><i class="fa fa-forward"></i><span>Forwarded</span></a>
            </li>
            <li class="{{ Request::query('status') == 'RETURNED' ? 'active' : '' }}">
                <a href="{{ route('documents.index', ['status' => 'RETURNED']) }}"><i class="fa fa-backward"></i><span>Returned</span></a>
            </li>
            <li class="{{ Request::query('status') == 'APPROVED' ? 'active' : '' }}">
                <a href="{{ route('documents.index', ['status' => 'APPROVED']) }}"><i class="fa fa-solid fa-circle-check"></i><span>Approved</span></a>
            </li>
            <li class="{{ Request::query('status') == 'DECLINED' ? 'active' : '' }}">
                <a href="{{ route('documents.index', ['status' => 'DECLINED']) }}"><i class="fa fa-solid fa-file-excel"></i><span>Declined</span></a>
            </li>
        </ul>
    </li>
@endcan
@can('user manage permission')
    @if(!auth()->user()->is_super_admin)
    <li class="treeview {{ Request::is('admin/document-received*') ? 'active' : '' }}">
    @php
        $oneDayAgo = now()->subDay(); // Get the current time minus 1 day
        $recentFiles = DB::table('received_documents')
                        ->where('receiver_id', auth()->id())
                        ->where('created_at', '>', $oneDayAgo)
                        ->exists();

       /* if ($recentFiles) {
            echo "There are recent files uploaded.";
        } else {
            echo "No recent files uploaded.";
        } */
    @endphp
    <a>
        <i class="fa fa-file-text-o"></i>
        <span>RECEIVED FILES</span>
        <span class="pull-right-container">
            @if ($recentFiles)
                <span class="badge badge-red"></span>
            @endif
            <i class="fa fa-angle-left pull-right"></i>
        </span>
    </a>
    <ul class="treeview-menu">
        <li class="{{ Request::query('status') == '' ? 'active' : '' }}">
            <a href="{{ route('documents.received', ['receiver_id' => auth()->id(), 'created_at' => \Carbon\Carbon::now()->format('Y-m-d')]) }}">
                <i class="fa fa-solid fa-inbox"></i><span> Inbox</span>
                @if ($recentFiles)
                    <span class="badge badge-red"></span>
                @endif
            </a>
        </li>
    </ul>
</li>


           {{--<li class="{{ Request::query('receiver_id') == auth()->id() && Request::query('status') == 'APPROVED' ? 'active' : '' }}">
                <a href="{{ route('documents.received', ['receiver_id' => auth()->id(), 'status' => 'APPROVED']) }}">
                    <i class="fa fa-solid fa-folder-tree"></i>
                    <span>??</span>
                </a>
            </li> --}}
            </ul>
        </li>
    @endif
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

