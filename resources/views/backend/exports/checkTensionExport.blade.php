<div class="view-container">
  <div class="result-content">
    <table>
      <thead>
        <tr>
          <th></th>
          <th>ID</th>
          <th>Code nhân viên</th>
          <th>Tên nhân viên</th>
          <th>Thời gian nhập</th>
          <th>Tải trọng quy định<br>B1.25Kg</th>
          <th>Tải trọng B1.25<br>Nhập (Kg)</th>
          <th>Tải trọng quy định<br>B2(Kg)</th>
          <th>Tải trọng <br>B2 Nhập (Kg)</th>
          <th>Tải trọng quy định<br>B5.5 (Kg)</th>
          <th>Tải trọng B5.5<br>Nhập (Kg)</th>
          <th>Máy nhập</th>
          <th>Kết quả</th>
        </tr>
      </thead>
      <tbody>
        @foreach ($viewdata  as $item)
        <tr>
          <td></td>
          <td>{{$item->id}}</td>
          <td>{{$item->code}}</td>
          <td>{{$item->name}}</td>
          <td>{{ $item->created_at}}</td>
          <td>{{ $item->target125}}</td>
          <td>{{ $item->weight125 }}</td>
          <td>{{ $item->target2}}</td>
          <td>{{ $item->weight2 }}</td>
          <td>{{ $item->target55}}</td>
          <td>{{ $item->weight55 }}</td>
          <td>{{ $item->selectComputer}}</td>
          <td>{{ $item->checkresult}}</td>
        </tr>
        @endforeach
      </tbody>
    </table>
  </div>
</div>

@section('scripts')
<script></script>
@endsection
