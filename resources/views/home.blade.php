@extends('layouts.app')
@section('title','Home')
@section('scripts')
    <script>
        function gotoUpload() {
            var docId = $("#document_id").val();
            var urlToUp = "{{route('documents.files.create', '')}}"+"/"+docId;
            console.log(urlToUp);
            window.location.href = urlToUp;
            return false;
        }
        $(function () {
            $('#activityrange').daterangepicker(
                {
                    ranges   : {
                        'Today'       : [moment(), moment()],
                        'Yesterday'   : [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
                        'Last 7 Days' : [moment().subtract(6, 'days'), moment()],
                        'Last 30 Days': [moment().subtract(29, 'days'), moment()],
                        'This Month'  : [moment().startOf('month'), moment().endOf('month')],
                        'Last Month'  : [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
                    },
                    startDate: moment().subtract(29, 'days'),
                    endDate  : moment()
                },
                function (start, end) {
                    $('#activityrange span').html(start.format('MMMM D, YYYY') + ' - ' + end.format('MMMM D, YYYY'));
                    $('#activity_range').val(start.format('YYYY-MM-DD') + 'to' + end.format('YYYY-MM-DD'));
                }
            );
            @if(request()->has('activity_range'))
                var dates = '{{request('activity_range')}}'.split('to');
                var start = moment(dates[0]);
                var end = moment(dates[1]);
                $('#activityrange span').html(start.format('MMMM D, YYYY') + ' - ' + end.format('MMMM D, YYYY'));
            @endif
        });
    </script>
@stop
@section('content')
        <section class="content-header">
            <h1 class="pull-left">Dashboard</h1>
        </section>
        <section class="content" style="margin-top: 20px;">
            <div class="clearfix"></div>
            <div class="row">
                <div class="col-md-6">
                    <div class="info-box bg-yellow">
                        <span class="info-box-icon"><i class="fa fa-tags"></i></span>
                        <div class="info-box-content">
                            <span class="info-box-text">{{ ucfirst(config('settings.tags_label_plural')) }}</span>
                            <span class="info-box-number">{{ $tagCounts }}</span>
                            <span class="progress-description">Total {{ ucfirst(config('settings.tags_label_plural')) }} in system</span>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="info-box bg-green">
                        <span class="info-box-icon"><i class="fa fa-folder"></i></span>
                        <div class="info-box-content">
                            <span class="info-box-text">{{ ucfirst(config('settings.document_label_plural')) }}</span>
                            <span class="info-box-number">{{ $documentCounts }}</span>
                            <span class="progress-description">Containing {{ $filesCounts }} {{ ucfirst(config('settings.file_label_plural')) }}</span>
                        </div>
                    </div>
                </div>
            </div>
          <!--  <div class="row">
                <div class="col-md-12">
                    <div class="box box-default">
                        <div class="box-header no-border text-center">
                            <h3 class="box-title">Quick Upload</h3>
                            <div class="box-tools pull-right">
                                <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
                            </div>
                        </div>
                        <div class="box-body">
                            <form action="#" class="text-center" style="width: 30vw; margin: 0 auto;" onsubmit="return gotoUpload()">
                                <div class="form-group">
                                    <label for="document_id">Choose {{ ucfirst(config('settings.document_label_singular')) }}</label>
                                    <select name="document_id" id="document_id" class="form-control select2" required>
                                        @foreach ($documents as $document)
                                            @can('view', $document)
                                                <option value="{{ $document->id }}">{{ $document->name }}</option>
                                            @endcan
                                        @endforeach
                                    </select>
                                </div>
                                <div class="form-group">
                                    <button class="btn btn-primary">Upload</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div> -->
            <div class="row">
            <div class="col-md-6">
                <!-- Bar Chart for Uploaded Documents -->
                <div class="box box-default">
                    <div class="box-header with-border">
                        <h3 class="box-title">Monthly Activities</h3>
                    </div>
                    <div class="box-body">
                        <canvas id="monthlyActivitiesChart"></canvas>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <!-- Line Chart for Monthly Activities -->
                <div class="box box-default">
                    <div class="box-header with-border">
                        <h3 class="box-title">Uploaded Documents</h3>
                    </div>
                    <div class="box-body">
                        <canvas id="uploadedDocumentsChart"></canvas>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <div class="box box-default">
                        <div class="box-header no-border">
                            <h3 class="box-title">Activities</h3>
                            <div class="box-tools pull-right">
                                {!! Form::open(['method' => 'get', 'style'=>'display:inline;']) !!}
                                    {!! Form::hidden('activity_range', '', ['id' => 'activity_range']) !!}
                                    <button type="button" id="activityrange" class="btn btn-default btn-sm">
                                        <i class="fa fa-calendar"></i>&nbsp;<span>Choose dates</span>&nbsp;&nbsp;&nbsp;<i class="fa fa-caret-down"></i>
                                    </button>&nbsp;&nbsp;&nbsp;&nbsp;
                                    {!! Form::button('<i class="fa fa-filter"></i>&nbsp;Filter', ['class' => 'btn btn-default btn-sm', 'type'=>'submit']) !!}
                                {!! Form::close() !!}&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
                            </div>
                        </div>
                        <div class="box-body">
                            <ul class="timeline">
                                <li class="time-label">
                                    <span style="background-color:#E34234; color:#fff">{{ formatDate(optional($activities->first())->created_at,'d M Y') }}</span>
                                </li>
                                @foreach ($activities as $activity)
                                    @if (auth()->user() && $activity->document && $activity->document->created_by === auth()->user()->id)
                                        <li>
                                            <i class="fa fa-solid" data-toggle="tooltip" title="{{ $activity->createdBy->name }}"></i>
                                            <div class="timeline-item">
                                                <span class="time" data-toggle="tooltip" title="{{ formatDateTime($activity->created_at) }}"><i class="fa fa-solid"></i> {{ \Carbon\Carbon::parse($activity->created_at)->diffForHumans() }}</span>
                                                <h4 class="timeline-header no-border">{!! $activity->activity !!}</h4>
                                            </div>
                                        </li>
                                    @endif
                                @endforeach
                            </ul>
                            <div class="text-center">
                                {!! $activities->appends(request()->all())->render() !!}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
 <!-- Script section for Chart.js -->
 <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/3.7.0/chart.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // Prepare data
            var monthlyActivitiesData = @json($monthlyActivities);
            var uploadedDocumentsData = @json($uploadedDocumentsData);

            // Line chart for monthly activities
            var monthlyActivitiesCtx = document.getElementById('monthlyActivitiesChart').getContext('2d');
            var monthlyActivitiesChart = new Chart(monthlyActivitiesCtx, {
                type: 'line',
                data: {
                    labels: monthlyActivitiesData.map(item => item.month_name),
                    datasets: [{
                        label: 'Monthly Activities',
                        data: monthlyActivitiesData.map(item => item.count),
                        backgroundColor: '#3b8e3f',
                        borderColor: '#3b8e3f',
                        borderWidth: 1
                    }]
                },
                options: {
                    scales: {
                        y: {
                            beginAtZero: true
                        }
                    }
                }
            });

           // Bar Chart for uploaded documents
            var uploadedDocumentsCtx = document.getElementById('uploadedDocumentsChart').getContext('2d');
            var uploadedDocumentsChart = new Chart(uploadedDocumentsCtx, {
                type: 'bar',
                data: {
                    labels: {!! json_encode(array_column($uploadedDocumentsData, 'month_name')) !!},
                    datasets: [{
                        label: 'Uploaded Documents',
                        data: {!! json_encode(array_column($uploadedDocumentsData, 'count')) !!},
                        fill: false,
                        backgroundColor: '#0056b3',
                        borderColor: 'rgba(54, 162, 235, 1)', 
                        tension: 0.1
                    }]
                },
                options: {
                    scales: {
                        y: {
                            beginAtZero: true
                        }
                    }
                }
            });
        });
    </script>
@endsection
