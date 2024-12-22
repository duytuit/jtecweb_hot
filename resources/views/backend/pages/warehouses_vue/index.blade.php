@extends('backend.layouts.master')
@section('admin-content')
    @include('backend.pages.warehouses.partials.header-breadcrumbs')
    <div class="container-fluid">
        <div>
            <ware-house></ware-house>
        </div>
    </div>
@endsection
<style>
</style>
@section('scripts')
    <script>
    </script>
@endsection
