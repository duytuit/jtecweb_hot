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
                    <a href="/check_device">
                        <div class="cards_item">
                            <div class="card">
                                <div class="card_content">
                                    <h2 class="card_title">Kiểm tra thiết bị</h2>
                                    <p class="card_text">Bộ phận Cắm</p>
                                </div>
                            </div>
                        </div>
                    </a>
                @endif
                {{-- Bộ phận Dập --}}
                @if ($employeeDepartment->department_id == 7)
                @endif
                {{-- Bộ phận Kiểm tra --}}
                @if ($employeeDepartment->department_id == 6)

                @endif
                {{-- Bộ phận cắt --}}
                @if ($employeeDepartment->department_id == 5)
                    <a href="/qrcode#cat">
                        <div class="cards_item">
                            <div class="card">
                                <div class="card_content">
                                    <h2 class="card_title">Tạo QR code</h2>
                                    <p class="card_text">Bộ phận Cắt</p>
                                </div>
                            </div>
                        </div>
                    </a>
                    <a href="{{ route('admin.checkCutMachine.create') }}">
                        <div class="cards_item">
                            <div class="card">
                                <div class="card_content">
                                    <h2 class="card_title">Thêm check list máy cắt</h2>
                                    <p class="card_text">Bộ phận Cắt</p>
                                </div>
                            </div>
                        </div>
                    </a>
                @endif
                {{-- Bộ phận Kho --}}
                @if ($employeeDepartment->department_id == 8)
                    <a href="/qrcode#kho">
                        <div class="cards_item">
                            <div class="card">
                                <div class="card_content">
                                    <h2 class="card_title">Tạo QR code</h2>
                                    <p class="card_text">Bộ phận Kho</p>
                                </div>
                            </div>
                        </div>
                    </a>
                @endif
                {{-- Bộ phận Mới --}}
                <a href="{{ route('admin.uploadDatas.create') }}">
                    <div class="cards_item">
                        <div class="card">
                            <div class="card_content">
                                <h2 class="card_title">Tải dữ liệu lên bàn gá</h2>
                                <p class="card_text">Bộ phận DB</p>
                            </div>
                        </div>
                    </div>
                </a>
            </div>
        </div>
    </div>
    <button class="add-button" style="display: none;">Add to Home screen</button>
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



