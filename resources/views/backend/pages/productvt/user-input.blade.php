@extends('backend.layouts.master')
@section('title')
    @include('backend.pages.admins.partials.title')
@endsection

@section('admin-content')
    @include('backend.pages.admins.partials.header-breadcrumbs')
    <main class="main">
        {{-- user input --}}
<div class="user-input"></div>
        <div class="cards">
            <div class="cards_item">
                <div class="card">
                    <div class="card_content">
                        <h2 class="card_title">Nhập sản lượng cuối ca làm việc</h2>
                        <p class="card_text">Họ tên: Nguyễn Văn A - Mã COS: 1234456</p>
                        <p class="card_text">Ngày làm việc: 14/04/2024</p>
                        <p class="card_text">Ca làm việc: Ca 1</p>
                        <p class="card_text">Sản lượng đạt được: </p>
                        <a href="#" class="btn card_btn">Lưu thông tin</a>
                    </div>
                </div>
            </div>
        </div>
    </main>
@endsection
@section('scripts')
    <script></script>
@endsection
