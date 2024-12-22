@php
    use App\Models\Employee;
@endphp
<div class="table-responsive product-table">
    <table class="table table-bordered" id="exams_table">
        <thead>
            <tr>
                <th>TT</th>
                <th style="background-color: yellow">Linh kiện</th>
                <th>Vị trí kho</th>
                <th>Bộ phận yc</th>
                <th>Số cuộn yc</th>
                <th>Số lượng yc</th>
                <th>Số lượng xuất</th>
                <th>Trạng thái</th>
                <th>Thời gian yc</th>
                <th>Ngày xuất</th>
                <th>Thời gian xuất</th>
                <th>Người xuất</th>
                <th>Người yêu cầu</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($lists as $index=> $item)
                @if ($item)
                    @php
                        $confirm_form = json_decode(@$item->confirm_form);
                        $employee = Employee::findEmployeeById($item->created_by);
                        $user = Employee::findEmployeeById(@$confirm_form[0]->user_id);
                    @endphp
                    <tr>
                        <td>{{ $index+1 }}</td>
                        <td>{{ $item->code}}</td>
                        <td>{{ $item->location}}</td>
                        <td>{{ $item->department->name }}</td>
                        <td>{{ $item->quantity }}</td>
                        <td>{{ $item->quantity_detail }}</td>
                        <td>{{ $item->quantity_detail - $item->remaining }}</td>
                        <td>
                            @if ($item->status == 0)
                                Chưa xuất
                            @else
                                @if (@$confirm_form[0]->quantity < $item->quantity_detail )
                                  Đã xuất hàng lẻ
                                @else
                                  Đã xuất đủ hàng
                                @endif
                            @endif
                        </td>
                        <td>{{ $item->created_at }}</td>
                        @if (@$confirm_form)
                            <td>{{@$confirm_form ? date('Y-m-d', strtotime(@$confirm_form[0]->date)):''}}</td>
                            <td>{{@$confirm_form ? date('Y-m-d H:i:s', strtotime(@$confirm_form[0]->date)):''}}</td>
                            <td>{{@$user->code.' - '.@$user->first_name.' '.@$user->last_name}}</td>
                        @endif
                        <td>   <div>{{@$employee->first_name . ' ' . @$employee->last_name }}</div></td>
                    </tr>
                @endif
            @endforeach
        </tbody>
    </table>
</div>
