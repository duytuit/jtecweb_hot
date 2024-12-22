@extends('backend.layouts.master')
@php
    use App\Helpers\ArrayHelper;
@endphp
@section('title')
    @include('backend.pages.exams.partials.title')
@endsection

@section('admin-content')
    @include('backend.pages.exams.partials.header-breadcrumbs')
    <div class="container-fluid">
        @include('backend.layouts.partials.messages')
        <div class="row form-group">
            <div class="col-sm-12">
                <a href="{{ route('admin.exams.exportExcel',Request::all()) }}" class="btn btn-success"><i class="fa fa-edit"></i> Xuất Excel</a>
            </div>
        </div>
        <form id="form-search-advance" action="{{ route('admin.exams.reportFailAnswer') }}" method="get" class="hidden">
            <div id="search-advance" class="search-advance">
                <div class="row form-group space-5">
                    <div class="col-sm-2">
                        <select name="question" class="form-control" style="width: 100%;">
                            <option value="">Câu hỏi</option>
                             @foreach ($arrayExamPd[@$filter['exam_type']]['data'] as $key => $item)
                                @foreach ($item['questions'] as $item1)
                                    <option value="{{$item1['id']}}" {{ @$filter['question'] == $item1['id'] ? 'selected' : '' }}>{{'Câu '.$item1['id']}}</option>
                                @endforeach
                             @endforeach
                        </select>
                    </div>
                    <div class="col-sm-1">
                        <select name="cycle_name" class="form-control" style="width: 100%;">
                            <option value="">Kỳ thi</option>
                             @foreach ($cycleNames as $item)
                                <option value="{{$item}}" {{ @$filter['cycle_name'] == $item ? 'selected' : '' }}>{{$item}}</option>
                             @endforeach
                        </select>
                    </div>
                    <div class="col-sm-2">
                        <select name="exam_type" class="form-control" style="width: 100%;">
                            <option value="">Bài thi</option>
                             @foreach ($arrayExamPd as $key => $item)
                                <option value="{{$key}}" {{ @$filter['exam_type'] == $key ? 'selected' : '' }}>{{$item['title']}}</option>
                             @endforeach
                        </select>
                    </div>
                    <div class="col-sm-2">
                        <select name="dept" class="form-control" style="width: 100%;">
                            <option value="">Bộ phận</option>
                             @foreach ($depts as $item)
                                <option value="{{$item->id}}" {{ @$filter['dept'] == $item->id ? 'selected' : '' }}>{{$item->name}}</option>
                             @endforeach
                        </select>
                    </div>
                    <div class="col-sm-1">
                        <select name="status" class="form-control" style="width: 100%;">
                            <option value="">Trạng thái</option>
                            <option value="1" {{ @$filter['status'] == '1' ? 'selected' : '' }}>Đạt</option>
                            <option value="0" {{ @$filter['status'] == '0' ? 'selected' : '' }}>Chưa đạt</option>
                        </select>
                    </div>
                    <div class="col-sm-2">
                        <button class="btn btn-warning btn-block">Tìm kiếm</button>
                    </div>
                </div>
            </div>
        </form><!-- END #form-search-advance -->
        <div class="row form-group">
            <div class="_chart" style="overflow-x: auto">
                <div id="chart"></div>
            </div>
        </div>
        <div class="row form-group">
            <div class="col-sm-6">
                <form id="form_lists" action="{{ route('admin.exams.action') }}" method="post">
                    @csrf
                    <input type="hidden" name="method" value="" />
                    <input type="hidden" name="status" value="" />
                    <div class="table-responsive product-table">
                        <h3>Danh sách nhân viên trả lời sai</h3>
                        <table class="table table-bordered" id="exams_table">
                            <thead>
                                <tr>
                                    <th>Câu hỏi</th>
                                    <th>kết quả</th>
                                    <th>Mã NV</th>
                                    <th>Nhân viên</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($lists->where('result',0)->all() as $index=> $item)
                                <tr>
                                    <td>
                                        @php
                                             $groupExamPd = ArrayHelper::arrayExamPd()[@$filter['exam_type']]['data'];
                                             $array_answer = [];
                                             foreach ($groupExamPd as $key => $value) {
                                                $array_answer = array_filter($value['questions'], fn ($element) => $element['id'] == $item->id);
                                                if( count($array_answer) > 0){
                                                    break;
                                                }
                                             }
                                        @endphp
                                        {{'Câu: ' .$item->id }}
                                         <div>
                                            {{ @$array_answer ? current($array_answer)['name'] :''}}
                                         </div>
                                         <div>
                                            <img src="{{  @$array_answer ? asset(current($array_answer)['path_image']) :'' }}" alt="" width="200" />
                                         </div>
                                    </td>
                                    <td>{{ $item->result == 0?'sai':'đúng' }}</td>
                                    <td>{{ $item->code }}</td>
                                    <td>{{ $item->name }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </form>
            </div>
            <div class="col-sm-6">
                @php
                    $report_false = $report_lists[2];
                    arsort($report_false);
                    $report_false = array_slice($report_false, 0, 11, true);
                @endphp
                <div class="table-responsive product-table">
                    <h3>Top 10 câu trả lời sai nhiều nhất</h3>
                    <table class="table table-bordered" id="exams_table">
                        <thead>
                            <tr>
                                <th>Câu hỏi</th>
                                <th>Số người trả lời sai</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($report_false as $index=> $item)
                                @if ($index > 0)
                                    <tr>
                                        <td>
                                            @php
                                                $groupExamPd = ArrayHelper::arrayExamPd()[@$filter['exam_type']]['data'];
                                                $array_answer = [];
                                                foreach ($groupExamPd as $key => $value) {
                                                    $array_answer = array_filter($value['questions'], fn ($element) => $element['id'] == $index);
                                                    if( count($array_answer) > 0){
                                                        break;
                                                    }
                                                }
                                            @endphp
                                            {{'Câu: ' .$index }}
                                            <div>
                                                {{ @$array_answer ? current($array_answer)['name'] :''}}
                                            </div>
                                            <div>
                                                <img src="{{  @$array_answer ? asset(current($array_answer)['path_image']) :'' }}" alt="" width="200" />
                                            </div>
                                        </td>
                                        <td>{{ $item }}</td>
                                    </tr>
                                @endif
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <input type="hidden" id="report_lists" value="{{json_encode($report_lists)}}">
    </div>
@endsection

@section('scripts')
    <script>
        $('input.date_picker').datepicker({
        autoclose: true,
        dateFormat: "dd-mm-yy"
        }).val();
        var chart = c3.generate({
            data: {
                    x: 'x',
                    columns: [],
                    type: 'bar'
                    // groups: [
                    //    ['data1', 'data2'], ['data3']
                    // ]
                },
            axis: {
                x: {
                    type: 'category',
                    tick:{
                        retate:90,
                        multiline:true
                    },
                    height:50
                }
            },
            legend: {
                position: 'inset',
                inset: {
                    anchor: 'top-left',
                    x: 20,
                    y: -40,
                    step: 1
                }
            },
            padding: {
                top: 40
            }
        });
    setTimeout(function () {
        chart.load({
            columns: JSON.parse($('#report_lists').val())
        });
    }, 1000);
    setTimeout(function () {
        chart.groups([['x','Đúng', 'Sai']])
    }, 1500);
      function getWidth() {
        return Math.max(
            document.body.scrollWidth,
            document.documentElement.scrollWidth,
            document.body.offsetWidth,
            document.documentElement.offsetWidth,
            document.documentElement.clientWidth
        );
        }
      window.addEventListener('resize', function(event){
        var pagewidth = getWidth() - 50;
        chart.resize({width:pagewidth});
      });
    </script>
@endsection
