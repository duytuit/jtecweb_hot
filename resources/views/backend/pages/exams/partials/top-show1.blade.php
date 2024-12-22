<!-- ============================================================== -->
<!-- Top Show Data of List Exam -->
<!-- ============================================================== -->
@php
use Carbon\Carbon;
@endphp
<div class="row mt-1 mb-2 p-1 fs-16 font-times-new bg-info  container-fluid mx-auto rounded">
    <!-- Column -->
    <div class="w-100">
        <div class="card col-md-6 bg-info col-lg-6 col-xlg-6 m-auto border border-light rounded">
            <div class="text-center">
                <div class="bg-yellow">
                    <div class="text-white text-uppercase">KẾT QUẢ BÀI KIỂM TRA KIẾN THỨC CÔNG NHÂN MỚI</div>
                    <div class="text-white" style="display: flex;
                        justify-content: space-around;">
                        <div>
                            <div>Tổng</div>
                            <div>
                                @if (Carbon::now()->format('mY') == $cycleName)
                                   {{ count($emp_pass_1) + count($emp_fail_1_60_79) + count($emp_fail_1_50_59) + count($emp_fail_1_49) + count($emp_yet_1) }}
                                @else
                                   {{ count($emp_pass_1) + count($emp_fail_1_60_79) + count($emp_fail_1_50_59) + count($emp_fail_1_49) }}
                                @endif

                            </div>
                        </div>
                        <div>
                            <div>Đạt</div>
                            <div><a href="javascript:;" class="btn-sm btn-warning detailReport1"
                                    data-emp="{{ json_encode($emp_pass_1) }}" data-type="1"
                                    style="color: white">{{ count($emp_pass_1) }}</a></div>
                        </div>
                        <div>
                            <div>Chưa đạt</div>
                            <div>
                                <table class="table-bordered">
                                    <tr>
                                        <td style="padding: 0 10px">
                                            Thi lại
                                        </td>
                                        <td style="padding: 0 10px">
                                            Đào tạo lại
                                        </td>
                                        <td style="padding: 0 10px">
                                            Không đạt
                                        </td>
                                    </tr>
                                    <tr>
                                        <td style="padding: 5px 0px">
                                            <div><a href="javascript:;" class="btn-sm btn-warning detailReport1"
                                                    data-emp="{{ json_encode($emp_fail_1_60_79) }}" data-type="3"
                                                    style="color: white">{{ count($emp_fail_1_60_79) }}</a></div>
                                        </td>
                                        <td style="padding: 5px 0px">
                                            <div><a href="javascript:;" class="btn-sm btn-warning detailReport1"
                                                    data-emp="{{ json_encode($emp_fail_1_50_59) }}" data-type="2"
                                                    style="color: white">{{ count($emp_fail_1_50_59) }}</a></div>
                                        </td>
                                        <td style="padding: 5px 0px">
                                            <div><a href="javascript:;" class="btn-sm btn-warning detailReport1"
                                                    data-emp="{{ json_encode($emp_fail_1_49) }}" data-type="3"
                                                    style="color: white">{{ count($emp_fail_1_49) }}</a></div>
                                        </td>
                                    </tr>
                                </table>
                            </div>
                        </div>
                        @if (Carbon::now()->format('mY') == $cycleName)
                            <div>
                                <div>Chưa thi</div>
                                <div><a href="javascript:;" class="btn-sm btn-warning detailReport1"
                                        data-emp="{{ json_encode($emp_yet_1) }}" data-type="4"
                                        style="color: white">{{ count($emp_yet_1) }}</a></div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
