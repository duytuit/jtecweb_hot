@extends('backend.layouts.master')

@section('title')
@include('backend.pages.dashboard.partials.title')
@endsection

@section('admin-content')
@include('backend.pages.dashboard.partials.header-breadcrumbs')

<!-- ============================================================== -->
<!-- Container fluid  -->
<!-- ============================================================== -->
<div class="container-fluid">
    <h4>
        THÔNG TIN NỘI BỘ JTEC HÀ NỘI
    </h4>
    <p>
        Chúc cán bộ công nhân viên có 1 ngày làm việc hiệu quả!!!
    </p>

    {{-- @include('backend.pages.dashboard.partials.top-show') --}}
    {{-- @include('backend.pages.dashboard.partials.email-campaign') --}}
    {{-- @include('backend.pages.dashboard.partials.revinue-page-bounce') --}}
    {{-- @include('backend.pages.dashboard.partials.recent-data') --}}
</div>
<!-- ============================================================== -->
<!-- End Container fluid  -->
<!-- ============================================================== -->
@endsection
