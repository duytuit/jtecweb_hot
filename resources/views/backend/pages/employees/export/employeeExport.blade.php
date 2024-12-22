<div class="table-responsive product-table">
    <table class="table table-bordered" id="employee_table">
        <thead>
            <tr>
                <th width="3%"></th>
                <th>TT</th>
                <th>Mã nhân viên</th>
                <th>Tên nhânviên</th>
                <th>Trạng thái</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($lists as $index => $item)
                <tr>
                    <td></td>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $item->code }}</td>
                    <td>{{ $item->first_name . ' ' . $item->last_name }}</td>
                    <td>{{ $item->status == 1 ? 'Hoạt động' : 'Không hoạt động' }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
