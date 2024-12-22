<div class="table-responsive product-table">
    <table class="table table-bordered" id="exams_table">
        <thead>
            <tr>
                <th>Mã</th>
                <th>Qr code</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($collection[0] as $item)
                @if ($item[0])
                    <tr>
                        <td>{{ $item[0] }}</td>
                        <td style="text-align: center;">
                            <img src="data:image/png;base64, {!! base64_encode(QrCode::format('png')->size(100)->margin(1)->generate((string) $item[0])) !!} ">
                        </td>
                    </tr>
                @endif
            @endforeach
        </tbody>
    </table>
</div>
