<!-- ============================================================== -->
<!-- Top Show Data of List Exam -->
<!-- ============================================================== -->
@php
use Carbon\Carbon;
@endphp
<div class="row mt-1 fs-16 font-times-new">
    <!-- Column -->
    <div class="col-md-6 col-lg-6 col-xlg-6">
        <div class="card card-hover">
            <div class="box bg-info text-center">
                <div class="bg-yellow">
                    @php
                        $month = substr(sprintf("%06s", $cycleName), 0, 2);
                        $year = substr(sprintf("%06s", $cycleName), 2);
                        $formattedDate = $month . '/' . $year;
                    @endphp
                    <div class="text-white">Kết quả Đợt <b>1</b> (Từ 1 đến 15) tháng <b>{{ $formattedDate }}</b></div>
                    <div class="text-white" style="display: flex;
                        justify-content: space-around;">
                        <div>
                            <div>Tổng</div>
                            <div>
                                @if (  Carbon::now()->format('mY') == $cycleName)
                                    {{ count($emp_pass_1) + count($emp_fail_1_90_95) + count($emp_fail_1_90) + count($emp_yet_1) }}
                                @else
                                    {{ count($emp_pass_1) + count($emp_fail_1_90_95) + count($emp_fail_1_90) }}
                                @endif
                            </div>
                        </div>
                        <div>
                            <div>Đạt</div>
                            <div><a href="javascript:;" class="btn-sm btn-warning detailReport"
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
                                    </tr>
                                    <tr>
                                        <td style="padding: 5px 0px">
                                            <div><a href="javascript:;" class="btn-sm btn-warning detailReport"
                                                    data-emp="{{ json_encode($emp_fail_1_90_95) }}" data-type="2"
                                                    style="color: white">{{ count($emp_fail_1_90_95) }}</a></div>
                                        </td>
                                        <td style="padding: 5px 0px">
                                            <div><a href="javascript:;" class="btn-sm btn-warning detailReport"
                                                    data-emp="{{ json_encode($emp_fail_1_90) }}" data-type="3"
                                                    style="color: white">{{ count($emp_fail_1_90) }}</a></div>
                                        </td>
                                    </tr>
                                </table>
                            </div>
                        </div>
                        @if (Carbon::now()->format('mY') == $cycleName)
                            <div>
                                <div>Chưa thi</div>
                                <div><a href="javascript:;" class="btn-sm btn-warning detailReport"
                                        data-emp="{{ json_encode($emp_yet_1) }}" data-type="4"
                                        style="color: white">{{ count($emp_yet_1) }}</a></div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Column -->
    <div class="col-md-6 col-lg-6 col-xlg-6">
        <div class="card card-hover">
            <div class="box bg-info text-center">
                <div class="bg-yellow">
                    <div class="text-white">Kết quả Đợt <b>2</b> (Từ 16 đến 31) tháng <b>{{ $formattedDate }}</b></div>
                    <div class="text-white"
                        style="display: flex;
                        justify-content: space-around;">
                        <div>
                            <div>Tổng</div>
                            <div>
                                @if (Carbon::now()->format('mY') == $cycleName)
                                   {{ count($emp_pass_2) + count($emp_fail_2_90_95) + count($emp_fail_2_90) + count($emp_yet_2) }}
                                @else
                                   {{ count($emp_pass_2) + count($emp_fail_2_90_95) + count($emp_fail_2_90) }}
                                @endif
                            </div>
                        </div>
                        <div>
                            <div>Đạt</div>
                            <div><a href="javascript:;" class="btn-sm btn-warning detailReport"
                                    data-emp="{{ json_encode($emp_pass_2) }}" data-type="5"
                                    style="color: white">{{ count($emp_pass_2) }}</a></div>
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
                                    </tr>
                                    <tr>
                                        <td style="padding: 5px 0px">
                                            <div><a href="javascript:;" class="btn-sm btn-warning detailReport"
                                                    data-emp="{{ json_encode($emp_fail_2_90_95) }}" data-type="6"
                                                    style="color: white">{{ count($emp_fail_2_90_95) }}</a></div>
                                        </td>
                                        <td style="padding: 5px 0px">
                                            <div><a href="javascript:;" class="btn-sm btn-warning detailReport"
                                                    data-emp="{{ json_encode($emp_fail_2_90) }}" data-type="7"
                                                    style="color: white">{{ count($emp_fail_2_90) }}</a></div>
                                        </td>
                                    </tr>
                                </table>
                            </div>
                        </div>
                        @if (Carbon::now()->format('mY') == $cycleName)
                            <div>
                                <div>Chưa thi</div>
                                <div><a href="javascript:;" class="btn-sm btn-warning detailReport"
                                        data-emp="{{ json_encode($emp_yet_2) }}" data-type="8"
                                        style="color: white">{{ count($emp_yet_2) }}</a></div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>
