@extends('frontend.layouts.master')

@section('title')
    {{ config('app.name') }} | {{ config('app.description') }}
@endsection

@section('main-content')
    <main class="main">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-md-8 mt-5">

                    @if (session('status'))
                        <div class="alert alert-success">{{ session('status') }}</div>
                    @endif

                    <div class="card">
                        <div class="card-header">
                            <h4>Nhập dữ liệu câu hỏi</h4>
                        </div>
                        <div class="card-body">

                            <form action="{{ url('question/import') }}" method="POST" enctype="multipart/form-data">
                                @csrf
                                <div class="input-group">
                                    <input type="file" name="import_file" class="form-control" />
                                    <button type="submit" class="btn btn-primary">Nhập câu hỏi</button>
                                </div>

                            </form>

                            <hr>

                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Name</th>
                                        <th>Ảnh</th>
                                        <th>Câu trả lời</th>
                                        <th>Danh sách câu trả lời</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($questions as $item)
                                        @if($item->name)
                                            <tr>
                                                <td>{{$item->myid}}</td>
                                                <td>{{$item->name}}</td>
                                                <td>
                                                    
                                                    <form action="{{ route('upload.handle') }}" method="POST" enctype="multipart/form-data">
                                                        @csrf
                                                        <input type="file" name="image">
                                                        <input type="submit" value="Submit">
                                                    </form>
                                                </td>
                                                <td>{{$item->answer}}</td>
                                                <td>{{$item->answer_list}}</td>
                                            </tr>
                                        @endif
                                    @endforeach
                                </tbody>
                            </table>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>
@endsection

@section('scripts')
    <script>
        
    </script>
@endsection