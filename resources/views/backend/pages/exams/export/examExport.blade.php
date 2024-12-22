<div class="table-responsive product-table">
    <table class="table table-bordered" id="exams_table">
        <thead>
            <tr>
                <th>TT</th>
                <th>Mã NV</th>
                <th>Tên NV</th>
                <th>Công đoạn</th>
                <th>Kỳ thi</th>
                <th>Ngày thi</th>
                <th>Tổng câu</th>
                <th>Trả lời đúng</th>
                <th>Điểm</th>
                <th>Thời gian làm bài</th>
                <th>Trạng thái</th>
                <th>Kết quả</th>
                <th>Người duyệt</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($lists as $index=> $item)
            @php
                $employee = $item->employee;
            @endphp
            <tr>
                <td>{{ $index+1 }}</td>
                <td>{{ $item->code }}</td>
                <td>{{@$employee->first_name.' '.@$employee->last_name}}</td>
                <td></td>
                <td>{{ $item->cycle_name }}</td>
                <td>{{ date('d-m-Y', strtotime(@$item->create_date))  }}</td>
                <td>{{ $item->total_questions }}</td>
                <td>{{ $item->results }}</td>
                <td>{{ $item->scores }}</td>
                <td>{{ $item->counting_time }}</td>
                <td>
                    @if ( $item->status)
                        <span class="badge badge-success font-weight-100">Đạt</span>
                    @else
                        <span class="badge badge-warning">Chưa Đạt</span>
                    @endif
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
