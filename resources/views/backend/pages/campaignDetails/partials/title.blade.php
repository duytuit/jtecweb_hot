@if (Route::is('admin.campaignDetails.index'))
campaignDetails
@elseif(Route::is('admin.campaignDetails.create'))
Create New campaignDetail
@elseif(Route::is('admin.campaignDetails.edit'))
Edit campaignDetail {{ $campaignDetail->title }}
@elseif(Route::is('admin.campaignDetails.show'))
View campaignDetail {{ $campaignDetail->title }}
@endif
| Admin Panel -
{{ config('app.name') }}
