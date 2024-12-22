@extends('backend.layouts.master')
@section('title')
    @include('backend.pages.admins.partials.title')
@endsection

@section('admin-content')
    @include('backend.pages.admins.partials.header-breadcrumbs')
    <main class="main">
        {{-- @foreach ($productvt as $item) --}}
        <div class="custom-container d-flex">
            <div class="col-md-3 border border-dark d-flex align-items-center">
                <span class="d-inline-block mx-1">MỤC TIÊU :</span>
                <input value="123" id="slTarget" name="slTarget" type="text" class="w-50 h-100 border border-0">
            </div>
            <div class="col-md-9 border border-dark text-center">
                <p class="fs-5">BẢNG QUẢN LÝ SỐ LƯỢNG DÂY CẮT HẰNG NGÀY</p>
                <div>
                    <span>THÁNG</span>
                    <select id="selectMonth" name="selectMonth" class="month-select" aria-label="Default select example">
                        <option selected>Tháng hiện tại</option>
                        @for ($i = 1; $i <= 12; $i++)
                            <option value="{{ $i }}">{{ $i }}</option>
                        @endfor
                    </select>
                    <select id="selectMachine" name="selectMachine" class="machine-select"
                        aria-label="Default select example">
                        <option selected>Máy</option>
                        @for ($i = 1; $i <= 16; $i++)
                            <option value="{{ $i }}">Máy - {{ $i }}</option>
                        @endfor
                    </select>
                </div>
                @if (session('status'))
                    <h6 class="alert alert-success">{{ session('status') }}</h6>
                @endif
            </div>

        </div>
        <div class="custom-container">
            <table class="sanluong-table">
                <thead>
                    <tr>
                        <td class="table-title" rowspan="2">Ngày</td>
                        <td class="table-title" rowspan="2">MỤC TIÊU <br>DÂY CẮT<br>(<span class="muctieuValue"></span>/1ca)
                        </td>
                        <td class="table-title" colspan="3">
                            <select id="selectMonth" name="selectMonth" class="month-select"
                                aria-label="Default select example">
                                <option selected>Ca làm việc</option>
                                <option value="1">1</option>
                                <option value="2">2</option>
                                <option value="hanhchinh">Hành chính</option>
                            </select>
                        </td>
                        <td class="table-title" rowspan="2">% ĐẠT ĐƯỢC</td>
                        <td class="table-title col-md-1" rowspan="2">Ghi chú</td>
                        <td class="table-title col-md-1" rowspan="2"></td>
                    </tr>
                    <tr>
                        <td class="table-title">SLDC<br>TRÊN MÁY</td>
                        <td class="table-title">SỐ LƯỢNG<br> ĐẠT ĐƯỢC</td>
                        <td class="table-title">CODE</td>
                    </tr>
                </thead>
                {{-- @foreach ($days as $day) --}}
                <tbody>
                    <tr>
                        <td class="table-data">1</td>
                        <td class="table-data"><span class="muctieuValue"></span></td>
                        <td class="table-data"></td>
                        <td class="table-data"></td>
                        <td class="table-data"></td>
                        <td class="table-data"></td>
                        <td class="table-data"></td>
                        <td class="table-data">
                            <div
                            class="text-center d-flex justify-content-center align-items-center">
                            <a href="{{ url('admin/productvt/edit') }}" class="btn btn-primary">Sửa</a>
                        </div>
                        </td>
                    </tr>
                </tbody>
                {{-- @endforeach --}}
            </table>
        </div>
        {{-- @endforeach --}}
    </main>
@endsection
@section('scripts')
<script>
    function updateValue() {
        var newValue = document.getElementById('slTarget').value;
        document.querySelectorAll('.muctieuValue').innerText = newValue / 2;
    }
    document.getElementById('slTarget').addEventListener('input', updateValue);
</script>
@endsection
