@extends('backend.layouts.master')

@section('title')
    @include('backend.pages.assets.partials.title')
@endsection

@section('admin-content')
    @include('backend.pages.assets.partials.header-breadcrumbs')
    <div class="container-fluid">
        @include('backend.pages.assets.partials.top-show')
        @include('backend.layouts.partials.messages')
        <!-- Page Content -->
        <div class="container">
            <div class="cards">
                {{-- Bộ phận Cắm --}}
                @if ($employeeDepartment->department_id == 4)
                    <a href="{{ route('exam',['code'=>@$user->username,'type'=>1]) }}">
                        <div class="cards_item">
                            <div class="card">
                                <div class="card_content">
                                    <h2 class="card_title">Đánh giá định kỳ</h2>
                                    <p class="card_text">Kiểm tra năng lực nhận biết màu dây</p>
                                    <div class="btn card_btn">Bắt đầu làm bài</div>
                                </div>
                            </div>
                        </div>
                    </a>
                    <a href="{{ route('examNew',['code'=>@$user->username,'type'=>2]) }}">
                        <div class="cards_item">
                            <div class="card">
                                <div class="card_content">
                                    <h2 class="card_title">Đánh giá công nhân mới vào</h2>
                                    <p class="card_text">Kiểm tra đánh giá công nhân</p>
                                    <div class="btn card_btn">Bắt đầu làm bài</div>
                                </div>
                            </div>
                        </div>
                    </a>
                @endif
                {{-- Bộ phận Dập --}}
                @if ($employeeDepartment->department_id == 7)
                    <a href="{{ route('exam',['code'=>@$user->username,'type'=>4]) }}">
                        <div class="cards_item">
                            <div class="card">
                                <div class="card_content">
                                    <h2 class="card_title">Bộ phận Dập</h2>
                                    <p class="card_text">Kiểm tra đánh giá công nhân nhóm Nối</p>
                                    <div class="btn card_btn">Bắt đầu làm bài</div>
                                </div>
                            </div>
                        </div>
                    </a>
                    <a href="{{ route('exam',['code'=>@$user->username,'type'=>5]) }}">
                        <div class="cards_item">
                            <div class="card">
                                <div class="card_content">
                                    <h2 class="card_title">Bộ phận Dập</h2>
                                    <p class="card_text">Kiểm tra đánh giá công nhân Xoắn</p>
                                    <div class="btn card_btn">Bắt đầu làm bài</div>
                                </div>
                            </div>
                        </div>
                    </a>
                    <a href="{{ route('exam',['code'=>@$user->username,'type'=>6]) }}">
                        <div class="cards_item">
                            <div class="card">
                                <div class="card_content">
                                    <h2 class="card_title">Bộ phận Dập</h2>
                                    <p class="card_text">Kiểm tra đánh giá công nhân nhóm Dập</p>
                                    <div class="btn card_btn">Bắt đầu làm bài</div>
                                </div>
                            </div>
                        </div>
                    </a>
                    <a href="{{ route('exam',['code'=>@$user->username,'type'=>9]) }}">
                        <div class="cards_item">
                            <div class="card">
                                <div class="card_content">
                                    <h2 class="card_title">Bộ phận Dập</h2>
                                    <p class="card_text">Kiểm tra đánh giá công nhân hàn nhúng</p>
                                    <div class="btn card_btn">Bắt đầu làm bài</div>
                                </div>
                            </div>
                        </div>
                    </a>
                @endif
                {{-- Bộ phận Kiểm tra --}}
                @if ($employeeDepartment->department_id == 6)
                    <a href="{{ route('exam',['code'=>@$user->username,'type'=>3]) }}" >
                        <div class="cards_item">
                            <div class="card">
                                <div class="card_content">
                                    <h2 class="card_title">Bộ phận kiểm tra</h2>
                                    <p class="card_text">Kiểm tra đánh giá công nhân</p>
                                    <div class="btn card_btn">Bắt đầu làm bài</div>
                                </div>
                            </div>
                        </div>
                    </a>
                    <a href="{{ route('exam',['code'=>@$user->username,'type'=>8]) }}">
                        <div class="cards_item">
                            <div class="card">
                                <div class="card_content">
                                    <h2 class="card_title">Bộ phận Kiểm Tra</h2>
                                    <p class="card_text">Kiểm tra đánh giá công nhân trên 1 năm</p>
                                    <div class="btn card_btn">Bắt đầu làm bài</div>
                                </div>
                            </div>
                        </div>
                    </a>
                    <a href="{{ route('exam',['code'=>@$user->username,'type'=>10]) }}">
                        <div class="cards_item">
                            <div class="card">
                                <div class="card_content">
                                    <h2 class="card_title">Bộ phận Kiểm Tra</h2>
                                    <p class="card_text">Test công nhân mới vào</p>
                                    <div class="btn card_btn">Bắt đầu làm bài</div>
                                </div>
                            </div>
                        </div>
                    </a>
                @endif
                {{-- Bộ phận cắt --}}
                @if ($employeeDepartment->department_id == 5)
                    <a href="{{ route('exam',['code'=>@$user->username,'type'=>7]) }}">
                        <div  class="cards_item">
                            <div class="card">
                                <div class="card_content">
                                    <h2 class="card_title">Bộ phận Cắt</h2>
                                    <p class="card_text">Kiểm tra đánh giá công nhân</p>
                                    <div class="btn card_btn">Bắt đầu làm bài</div>
                                </div>
                            </div>
                        </div>
                    </a>
                @endif
            </div>
        </div>
    </div>
@endsection
@section('scripts')
    <script>
    </script>
@endsection
@section('styles')
    <style>
        .cards {
            display: flex;
            flex-wrap: wrap;
            list-style: none;
            margin: 0;
            padding: 0;
        }
        .cards_item {
            display: flex;
            padding: 1rem;
            width: 100%;
        }
        .card {
            background-color: white;
            border-radius: 0.25rem;
            box-shadow: 0 15px 30px -14px rgba(0, 0, 0, 0.25);
            display: flex;
            flex-direction: column;
            overflow: hidden;
            cursor: pointer;
            width: 320px;
            height: 130px;
        }
        .card:hover {
            border: 2px solid blueviolet;
        }
        .card_content {
            padding: 1rem;
            background: linear-gradient(to bottom left, #EF8D9C 40%, #FFC39E 100%);
            height: 100%;
        }
        .card_title {
            color: #ffffff;
            font-size: 1.1rem;
            font-weight: 700;
            letter-spacing: 1px;
            text-transform: capitalize;
            margin: 0px;
        }
        .card_title a {
            color: inherit;
        }
        .card_text {
            color: #ffffff;
            font-size: 0.875rem;
            line-height: 1.5;
            margin-bottom: 1.25rem;
            font-weight: 400;
        }

    </style>
@endsection

