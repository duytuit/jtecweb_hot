<div class="table-responsive product-table">
    <h4>{{ $title }}</h4>
    <table class="table table-bordered" id="exams_table">
        <thead>
            <tr>
                <th>Mã NV</th>
                <th>Tên NV</th>
                <th>Điểm</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($lists as $index => $item)
                <tr>
                    <td>{{ $item->code }}</td>
                    <td>{{ @$item->first_name ? @$item->first_name . ' ' . $item->last_name : @$item->employee->first_name . ' ' . $item->employee->last_name }}
                    </td>
                    <td>{{ @$item->scores }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
