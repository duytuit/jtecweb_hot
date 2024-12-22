@extends('backend.layouts.master')

@section('title')
    @include('backend.pages.uploadData.partials.title')
@endsection

@section('admin-content')
    @include('backend.pages.uploadData.partials.header-breadcrumbs')
    <div class="container-fluid">
        @include('backend.layouts.partials.messages')
        <div class="create-page">
            <form id="postForm" action="{{ route('admin.uploadDatas.store') }}" method="POST" enctype="multipart/form-data"
                data-parsley-validate data-parsley-focus="first">
                @csrf
                <div class="form-body">
                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <ul>
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
                    <div class="card-body">
                        <div class="col-12 align-items-center justify-content-center mx-auto">
                            {{-- <a href="{{ route('admin.uploadDatas.restartWebPdf') }}" class="btn btn-secondary"><i class="fa fa-refresh"></i> Reset Web</a> --}}
                                <div class="w-100">
                                    <div class="form-group">
                                        <label class="control-label" for="filepdf1"><strong style="font-size: 20px">Dữ liệu 1 màn hình</strong></label>
                                        <input type="file" class="form-control dropify" data-height="100"
                                            data-allowed-file-extensions="pdf" id="filepdf1" name="filepdf1" />
                                    </div>
                                </div>
                                <div class="w-100">
                                    <div class="form-group">
                                        <label class="control-label" for="filepdf2"><strong  style="font-size: 20px">Dữ liệu 2 màn hình</strong></label>
                                        <input type="file" class="form-control dropify" data-height="100"
                                            data-allowed-file-extensions="pdf" id="filepdf2" name="filepdf2" />
                                    </div>
                                </div>
                                <div class="w-100">
                                    <div class="form-group">
                                        <label class="control-label" for="filepdf3"><strong  style="font-size: 20px">Dữ liệu 3 màn hình</strong></label>
                                        <input type="file" class="form-control dropify" data-height="100"
                                            data-allowed-file-extensions="pdf" id="filepdf3" name="filepdf3" />
                                    </div>
                                </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        $('.dropify').change(function(e){
            e.preventDefault();
            let formData = new FormData();
            formData.append("_token", "{{ csrf_token() }}");
            formData.append($(this).attr("name"),this.files[0]);
            console.log(Object.fromEntries(formData));
            $.ajax({
                url: "{{ route('admin.uploadDatas.store') }}",
                method: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                success: function(data) {
                    $(".dropify-clear").trigger("click");
                    if(data.status == true){
                        toastr.success(data.message, 'Thông báo');
                    }
                    if(data.status == false){
                        toastr.error(data.message, 'Thông báo');
                    }
                }
            });
        })
    </script>
@endsection
