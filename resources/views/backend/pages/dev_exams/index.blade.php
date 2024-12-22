@extends('backend.layouts.master')

@section('title')
    @include('backend.pages.exams.partials.title')
@endsection

@section('admin-content')
    @include('backend.pages.exams.partials.header-breadcrumbs')
    <div class="container-fluid">
        {{-- @include('backend.pages.exams.partials.top-show') --}}
        @include('backend.layouts.partials.messages')
        <div class="table-responsive product-table">
            <table class="table table-striped table-bordered display ajax_view" id="exams_table">
                <thead>
                    <tr>
                        <th>Thứ tự</th>
                        <th>Mã NV</th>
                        <th>Tên NV</th>
                        <th>Công đoạn</th>
                        <th>Kỳ thi</th>
                        <th>Ngày thi</th>
                        <th>Tổng câu</th>
                        <th>Trả lời đúng</th>
                        <th>Điểm</th>
                        <th>Thời gian nộp</th>
                        <th>Thời gian thi</th>
                        <th>Trạng thái</th>
                        <th>Người duyệt</th>
                        <th width="100">Action</th>
                    </tr>
                </thead>
            </table>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
    const ajaxURL = "<?php echo Route::is('admin.exams.trashed' ? 'exams/trashed/view' : 'exams') ?>";
    $('table#exams_table').DataTable({
        dom: 'Blfrtip',
        language: {processing: "<span class='spinner-border spinner-border-sm' role='status' aria-hidden='true'></span> Loading Data..."},
        processing: true,
        serverSide: true,
        ajax: {url: ajaxURL},
        aLengthMenu: [[25, 50, 100, 1000, -1], [25, 50, 100, 1000, "All"]],
        buttons: ['excel', 'pdf', 'print'],
        columns: [
            {data: 'DT_RowIndex', name: 'DT_RowIndex'},
            {data: 'name', name: 'name'},
            {data: 'code', name: 'code'},
            {data: 'sub_dept', name: 'sub_dept'},
            {data: 'cycle_name', name: 'cycle_name'},
            {data: 'create_date', name: 'create_date'},
            {data: 'total_questions', name: 'total_questions'},
            {data: 'results', name: 'results'},
            {data: 'marks', name: 'marks'},
            {data: 'counting_time', name: 'counting_time'},
            {data: 'limit_time', name: 'limit_time'},
            {data: 'status', name: 'status'},
            {data: 'confirm', name: 'confirm'},
            {data: 'action', name: 'action'}
        ]
    });
    </script>
@endsection
