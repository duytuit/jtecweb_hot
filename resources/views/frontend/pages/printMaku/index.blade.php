@extends('frontend.layouts.master')

@section('title')
    {{ config('app.name') }} | {{ config('app.description') }}
@endsection

@section('main-content')
    <main class="main">
        <div class="container text-center">
            <div class="mt-5 text-center">
                <p>Trỏ chuột vào ô dưới và quét hoặc nhập mã</p>
                <input class="d-inline-block" type="text" value="" placeholder="">
            </div>
            <div class="row">
                <div class="card-body">
                    <select name="makuLevel1" class="form-control w-100" style="display: inline;width: auto;"
                        data-target="#form_lists">
                        @php $list = ['0','1','2','3','4','5','6','7','8',]; @endphp
                        @foreach ($list as $num)
                            <option value="{{ $num }}" {{ $num == 5 ? 'selected' : '' }}>
                                MAKU đầu {{ $num }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="card-body">
                    <select name="makuLevel2" class="form-control w-100" style="display: inline;width: auto;"
                        data-target="#form_lists">
                        @php $list = [5, 10, 20, 50, 100, 200]; @endphp
                        @foreach ($list as $num)
                            <option value="{{ $num }}" {{ $num == 5 ? 'selected' : '' }}>
                                MAKU đầu {{ $num }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="card-body">
                    <select name="makuLevel3" class="form-control w-100" style="display: inline;width: auto;"
                        data-target="#form_lists">
                        @php $list = [5, 10, 20, 50, 100, 200]; @endphp
                        @foreach ($list as $num)
                            <option value="{{ $num }}" {{ $num == 5 ? 'selected' : '' }}>
                                MAKU đầu {{ $num }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="card-body w-25 text-left">
                    <p>FileName: <span>{{ '' }}</span></p>
                </div>
            </div>
            <button class="btn btn-success">Mở file</button>
        </div>
    </main>
@endsection

@section('scripts')
    <script></script>
@endsection
