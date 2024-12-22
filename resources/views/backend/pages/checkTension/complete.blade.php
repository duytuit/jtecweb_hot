@extends('backend.layouts.master')

@section('admin-content')
<div class="view-container">
  <div class="view-content">
    <div class="head">
      <h1 class="title">TRUY XUẤT THÔNG TIN</h1>
      <img src="/public/assets/images/logo/logo.png" alt="" class="tension-logo">
    </div>
    <div class="input-group d-flex">
      <div class="menu">
        <p class="label">Menu</p>
        <select name="" id="menuSelect" class="menuSelect">
          <option value="succang" selected>Sức căng</option>
        </select>
      </div>
      <div class="search-text">
        <p class="label">Nội dung</p>
        <input type="text" id="searchinput" name="searchinput" class="search-input">
      </div>
      <div class="search-from">
        <p class="label">Từ ngày</p>
        <input type="date" class="date_picker" autocomplete="off">
      </div>
      <div class="search-to">
        <p class="label">Đến ngày</p>
        <input type="date" class="date_picker" autocomplete="off">
      </div>
    </div>
    <div class="button-group d-flex">
      <button class="btn">
        <img src="/public/assets/images/pages/tension/search.png" alt="" class="search-logo">
        <span>Tìm kiếm</span>
      </button>
      <button class="btn">
        {{-- <img src="/public/assets/images/pages/tension/search.png" alt="" class="tension-logo"> --}}
        <span>Xóa bộ nối</span>
      </button>
      <button class="btn">
        {{-- <img src="/public/assets/images/pages/tension/search.png" alt="" class="tension-logo"> --}}
        <span>Xuất file</span>
      </button>
      <button class="btn">
        {{-- <img src="/public/assets/images/pages/tension/search.png" alt="" class="tension-logo"> --}}
        <span>Thoát</span>
      </button>
    </div>
    <div class="print-group">
      <button class="btn btn-print">
        <img src="/public/assets/images/pages/tension/printer.png" alt="" class="print-logo">
        <span>In</span>
      </button>
      <select name="print" id="printoutput" class="print-output">
        <option value="print-to-pdf" selected>Microsoft Print to PDF</option>
        <option value="print-to-onenote">OneNote (Desktop)</option>
      </select>
    </div>
  </div>
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
        <tr>
          <td></td>
          <td>{{$checkTension->id}}</td>
          <td>{{$checkTension->code}}</td>
          <td>{{$checkTension->name}}</td>
          <td>{{ $checkTension->created_at}}</td>
          <td>{{ $checkTension->target125}}</td>
          <td>{{ $checkTension->weight125 }}</td>
          <td>{{ $checkTension->target2}}</td>
          <td>{{ $checkTension->weight2 }}</td>
          <td>{{ $checkTension->target55}}</td>
          <td>{{ $checkTension->weight55 }}</td>
          <td>{{ $checkTension->selectComputer}}</td>
          <td>{{ $checkTension->checkresult}}</td>
        </tr>
      </tbody>
    </table>
  </div>
</div>
@endsection

@section('scripts')
<script></script>
@endsection
