@extends('layouts.app')
@section('title',ucfirst(config('settings.document_label_plural'))." List")
@section('css')
    <style type="text/css">
        .bg-folder-shaper {
            width: 100%;
            height: 115px;
            border-radius: 0px 15px 15px 15px !Important;
        }

        .folder-shape-top {
            width: 57px;
            height: 17px;
            border-radius: 20px 37px 0px 0px;
            position: absolute;
            top: -16px;
            left: 0;
            right: 0;
            bottom: 0;
        }

        .widget-user-2 .widget-user-username, .widget-user-2 .widget-user-desc {
            margin-left: 10px;
            font-weight: 400;
            font-size: 17px;
        }

        .widget-user-username {
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .m-t-20 {
            margin-top: 20px;
        }

        .dropdown-menu {
            min-width: 100%;
        }

        .doc-box.box {
            box-shadow: 0 0px 0px rgba(0, 0, 0, 0.0) !important;
        }

        .bg-folder-shaper:hover {
            background-color: yellow;
        }

        .select2-container {
            width: 100% !important;
        }

        #filterForm.in, filterForm.collapsing {
            display: block !important;
        }
    </style>
@stop
@section('scripts')
    <script>

    </script>
@stop
@section('content')
    <section class="content-header">
        <h1 class="pull-left">
            {{ucfirst(config('settings.document_label_plural'))}}
        </h1>
        <h1 class="pull-right">
            @can('create',\App\Document::class)
                <a href="{{route('documents.create')}}"
                   class="btn btn-primary">
                    <i class="fa fa-plus"></i>
                    Add New
                </a>
            @endcan
        </h1>
    </section>
    <div class="content" style="margin-top: 22px;">
        <div class="clearfix"></div>

        @include('flash::message')

        <div class="clearfix"></div>
        <div class="box box-primary">
            <div class="box-header">
                <div class="form-group hidden visible-xs">
                    <button type="button" class="btn btn-default btn-block" data-toggle="collapse"
                            data-target="#filterForm"><i class="fa fa-filter"></i> Filter
                    </button>
                </div>
                {!! Form::model(request()->all(), ['method'=>'get','class'=>'form-inline visible hidden-xs','id'=>'filterForm']) !!}
                <div class="form-group">
                    <label for="search" class="sr-only">Search</label>
                    {!! Form::text('search',null,['class'=>'form-control input-sm','placeholder'=>'Search...']) !!}
                </div>
              <!--  <div class="form-group">
                    <label for="tags" class="sr-only">{{config('settings.tags_label_singular')}}:</label>
                    <select class="form-control select2 input-sm" name="tags[]" id="tags"
                            data-placeholder="Choose {{config('settings.tags_label_singular')}}" multiple>
                        @foreach($tags as $tag)
                            @canany(['read documents','read documents in tag '.$tag->id])
                                <option
                                    value="{{$tag->id}}" {{in_array($tag->id,request('tags',[]))?'selected':''}}>{{$tag->name}}</option>
                            @endcanany
                        @endforeach
                    </select>
                </div> -->
               <!-- <div class="form-group">
                    <label for="status" class="sr-only">{{config('settings.tags_label_singular')}}:</label>
                    {!! Form::select('status',['0'=>"ALL",config('constants.STATUS.PENDING')=>config('constants.STATUS.PENDING'),config('constants.STATUS.APPROVED')=>config('constants.STATUS.APPROVED'),config('constants.STATUS.DECLINED')=>config('constants.STATUS.DECLINED')],null,['class'=>'form-control input-sm']) !!}
                </div> 
                <button type="submit" class="btn btn-default btn-sm"><i class="fa fa-filter"></i> Filter</button> -->
                {!! Form::close() !!}
            </div>
            <div class="box-body">
            <div class="table-responsive">
                <table class="table table-bordered text-center">
                    <thead>
                        <tr>
                            <th>Document ID</th>
                            <th>Document Title</th>
                            <th>Status</th>
                            <th>Last Updated</th>
                            <th>Remarks</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($documents->sortByDesc('document_id') as $document)
                            @cannot('view', $document)
                                @continue
                            @endcannot
                            <tr>
                                <td> <!-- <i class="fa fa-solid fa-file-lines"></i> -->
                                    <a href="{{ route('documents.show', $document->id) }}" style="color: #000">{{ $document->document_id }}</a>
                                </td>
                                <td style="text-align: left">
                                    <a href="{{ route('documents.show', $document->id) }}" style="font-family: Varela Round; font-weight: bold;">{{ $document->name }}</a> <br>
                                    <small class="description"><b
                                            title="{{formatDateTime($document->created_at)}}"
                                            data-toggle="tooltip">{{\Carbon\Carbon::parse($document->created_at)->diffForHumans()}}</b>
                                    by <b>{{$document->createdBy->name}}</b></small>
                                </td>
                                <td>
                                    @if ($document->activities->isNotEmpty())
                                        @php 
                                            $activities = $document->activities->reverse(); 
                                        @endphp
                                            @foreach ($activities as $activity)
                                                @if($loop->last)
                                                 <p style="font-family: Varela Round;">{!! $activity->activity !!}</p>
                                                @endif
                                            @endforeach
                                    @else
                                        <span>No activity</span>
                                    @endif
                                </td>
                                <td>{{ formatDate($document->updated_at) }}</td>
                                <td>
                                    @if ($document->isVerified)
                                        <i title="Approved" data-toggle="tooltip" class="fa fa-check-circle" style="color: #388E3C;"><p style="font-family: Varela Round;">Approved</p></i>
                                    @elseif w($document->isDeclined)
                                        <i title="Declined" data-toggle="tooltip" class="fa fa-ban" style="color: #f44336;"><p style="font-family: Varela Round;">Declined</p></i>
                                    @elseif ($document->status == config('constants.STATUS.PENDING')) 
                                        <i title="In Progress" data-toggle="tooltip" class="fa fa-clock" style="color:#E49B0F;"><p style="font-family: Varela Round;">In Progress</p></i>
                                    @elseif ($document->status == config('constants.STATUS.FORWARDED')) 
                                        <i title="Forwarded" data-toggle="tooltip" class="fa fa-forward" style="color:#388E3C;"><p style="font-family: Varela Round;">Forwarded</p></i>
                                    @elseif ($document->status == config('constants.STATUS.RETURNED')) 
                                        <i title="Returned" data-toggle="tooltip" class="fa fa-backward" style="color:#f44336;"><p style="font-family: Varela Round;">Returned</p></i>
                                    @endif
                                </td>
                                <td>
                                    <a href="{{ route('documents.show', $document->id) }}" title="Show" class="btn btn-primary btn-xs"><i class="fa fa-eye"></i></a>
                                    @can('edit', $document)
                                        <a href="{{ route('documents.edit', $document->id) }}" title="Edit" class="btn btn-warning btn-xs"><i class="fa fa-edit"></i></a>
                                    @endcan
                                    @can('delete', $document)
                                        {!! Form::open(['route' => ['documents.destroy', $document->id], 'method' => 'delete', 'style' => 'display:inline']) !!}
                                            <button type="submit" class="btn btn-danger btn-xs" title="Delete" onclick="return conformDel(this,event)"><i class="fa fa-trash"></i></button>
                                        {!! Form::close() !!}
                                    @endcan
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
            <div class="box-footer">
                {{ $documents->links() }}
            </div>
        </div>
    </div>
@endsection
