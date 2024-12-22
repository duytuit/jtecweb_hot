@if (Route::is('admin.productionPlans.index'))
Kế hoạch sản xuất
@elseif(Route::is('admin.productionPlans.create'))
Thêm mới
@elseif(Route::is('admin.productionPlans.edit'))
Sửa cột {{ $productionPlan->title }}
@elseif(Route::is('admin.productionPlans.show'))
Chi tiết{{ $productionPlan->title }}
@endif
| Admin Panel -
{{ config('app.name') }}
