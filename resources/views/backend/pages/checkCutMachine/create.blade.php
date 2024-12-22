@extends('backend.layouts.master')

@section('admin-content')
    <div class="container-fluid ">
        <h1 class="title text-center ">BẢNG KIỂM TRA HÀNG NGÀY MÁY CẮT</h1>
        <div class="text-center font-20">
            <strong>Bộ phận: {{ $employee_department->department->name }}</strong>
            <form role="form" action="{{ route('admin.checkCutMachine.create') }}" method="GET">
                <!-- select -->
                <span>Máy: </span>
                <select name="selecMachine" id="selecMachine" data-live-search="true"
                    onchange='this.form.submit()'>
                    <option value="">Chọn máy kiểm tra</option>
                    @foreach ($machineLists as $machineName)
                        <option value="{{ $machineName['name'] }}" @if (@$filter['selecMachine'] == $machineName['name']) selected @endif>
                            {{ $machineName['name'] }}</option>
                    @endforeach
                </select>
                <noscript><input type="submit" value="Submit"></noscript>
            </form>
            <br>
            <span>Ngày làm việc: </span><strong>{{ date('d/m/Y') }}</strong><br>
            @php
                $auth = Auth::user();
            @endphp
            <span>Người thực hiện: </span><strong>{{ $auth->first_name . ' ' . $auth->last_name }}</strong>
        </div>
        {{-- <input type="hidden" name="_selecMachine" id="_selecMachine" value="{{@$filter['selecMachine']}}"> --}}
        <form action="{{ route('admin.requireds.requireCheckListMachineCut') }}" method="POST" data-parsley-validate
            data-parsley-focus="first">
            @csrf
            <input type="hidden" name="departmentId" value="{{ $employee_department->department_id }}">
            <input type="hidden" name="selecMachine" value="{{ @$filter['selecMachine'] }}">
            <div class="">
                <div class="row mb-2 ">
                    <div class="col-11 w-75 mx-auto p-md-2 fs-3">
                        <div class="row">
                            @foreach ($formTypeJobs as $index => $formTypeJob)
                                <div class="col-md-4 p-2 ">
                                    <div class="h-100 p-2 shadow-lg check-cut-machine">
                                        <span class="p-md-2 d-block bg-danger text-light">Vị trí
                                            {{ $formTypeJob['id'] + 1 }}</span>
                                        <div>
                                            <img style="height: 220px; object-fit: cover;object-position: top left;"
                                                class="w-100 mb-auto" src="{{ '../../' . $formTypeJob['image'] }}"
                                                alt="">
                                        </div>
                                        <div class="pt-2 ">
                                            <strong class="text-uppercase ">Vị trí kiểm tra:</strong>
                                            <p>{{ $formTypeJob['position'] }}</p>
                                            <strong class="text-uppercase ">Nội dung kiểm tra:</strong>
                                            <p>{{ $formTypeJob['method'] }}</p>
                                            <strong class="text-uppercase ">Xử lý:</strong>
                                            <p>{{ $formTypeJob['handle'] }}</p>
                                            <strong class="text-uppercase ">Kết quả kiểm tra</strong><br>
                                            @foreach ($formTypeJob['answer_list'] as $index1 => $item)
                                                <input name="answer[{{ $index }}]"
                                                    id="answerId{{ $index . $index1 }}"
                                                    style="width:20px;height:20px; vertical-align: middle;" type="radio"
                                                    value="{{ $item }}"
                                                    data-parsley-required-message="Bạn chưa check list" required {{ $item == 1 ? 'checked' : '' }}>
                                                <label
                                                    for="answerId{{ $index . $index1 }}">{{ $item == 0 ? 'Bất bình thường' : 'Bình thường' }}</label>
                                            @endforeach
                                        </div>
                                    </div>
                                </div>
                            @endforeach

                            <div class="col-md-4 p-2 ">
                                <div class="h-100 p-2 shadow-lg">
                                    <span class="p-md-2 d-block bg-primary text-light">Ghi chú</span>
                                    <div class="pt-2 ">
                                        <textarea name="repair_history" id="" class="w-100"></textarea>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row justify-content-center ">
                    <button type="submit" class="btn btn-success">
                        <i class="fa fa-check"></i> Lưu thông tin
                    </button>
                </div>

            </div>
        </form>
    </div>
@endsection

@section('scripts')
    <script>
        //    $(document).ready( function () {
        //     if($('#_selecMachine').val()){
        //         $('#selecMachine').val($('#_selecMachine').val()).change();
        //     }
        //   })
    </script>
@endsection
