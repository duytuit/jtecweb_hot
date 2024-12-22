<div class="table-responsive product-table">
    <table class="table table-bordered" id="exams_table">
        <thead>
            <tr>
                <th>TT</th>
                <th>Code</th>
                <th>Bộ phận yc</th>
                <th>Số cuộn yc</th>
                <th>Số lượng yc</th>
                <th>Số lượng xuất</th>
                <th>Định mức</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($lists as $index=> $item)
            <tr>
                <td>{{ $index+1 }}</td>
                <td>{{ $item->code }}</td>
                <td>{{ $item->department->name }}</td>
                <td>{{ $item->quantity }}</td>
                <td>{{ $item->quantity_detail }}</td>
                <td>{{ $item->quantity_detail - $item->remaining }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
