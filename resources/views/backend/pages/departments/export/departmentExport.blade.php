<div class="table-responsive product-table">
    <table class="table table-bordered" id="department_table">
        <thead>
            <tr>
                <th width="3%"></th>
                <th>TT</th>
                <th>Mã Bộ phận</th>
                <th>Tên Bộ phận</th>
                <th>Trạng thái</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($lists as $index => $item)
                <tr>
                    <td></td>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $item->code }}</td>
                    <td>{{ $item->name }}</td>
                    <td>{{ $item->status == 1 ? 'Hoạt động' : 'Không hoạt động' }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
