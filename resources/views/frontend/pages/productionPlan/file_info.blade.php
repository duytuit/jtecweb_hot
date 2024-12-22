@php
   use App\Helpers\ReturnPathHelper;
@endphp
<div class="form-group">
    @extends('frontend.layouts.master_no_container_header')
    @if (@$files)
    <div class="container-fluid">

            <form id="form_lists" action="{{ route('frontend.productionPlans.action') }}" method="post">
                @csrf
                <input type="hidden" name="method" value="" />
                <input type="hidden" name="status" value="" />
                <div class="table-responsive product-table">
                    <table class="table table-bordered" id="exams_table">
                        <thead>
                            <tr>
                                <th >TT</th>
                                <th >Tên</th>
                                <th >Kích thước</th>
                                <th >Ngày chỉnh sửa</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($files as $index=> $item)
                                @php
                                    $filePath = $dirPath . '/' . $item;
                                    $size = File::size($filePath);
                                    $filesize = ReturnPathHelper::fm_get_filesize($size);
                                    $modif_raw = filemtime($filePath);
                                    $modif = date('m/d/Y g:i A', $modif_raw);
                                @endphp
                                @if (is_file($filePath))
                                    <tr>
                                        <td>
                                            {{($index+1)}}
                                        </td>
                                        <td>
                                            <a target="_blank" href="{{ route('frontend.productionPlans.file_info',['file_path'=>$filePath])}}">{{$item}}</a>
                                        </td>
                                        <td>{{ $filesize }}</td>
                                        <td>{{ $modif }}</td>
                                    </tr>
                                @else
                                    <tr>
                                        <td>
                                            {{($index+1)}}
                                        </td>
                                        <td>
                                            <a href="{{ route('frontend.productionPlans.file_info',['folder_path'=>$filePath])}}">{{$item}}</a>
                                        </td>
                                        <td>{{ $filesize }}</td>
                                        <td>{{ $modif }}</td>
                                    </tr>
                                @endif
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </form>
    </div>
    @endif
    @if (@$file_detail)
        <div>
            <object id="fileBase64" data="{{@$file_detail}}" width="100%" style="height:98vh" type="application/pdf"></object>
        </div>
    @endif
</div>
<style>
    body {
        padding:0 !important;
    }
    .come_back_link{
        position: absolute !important;
        padding: 5px!important;
        background-color: white!important;
        left: 55px;
        border-radius: 5px;
        top: 10px;
    }
</style>
