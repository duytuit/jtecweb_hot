@if (Route::is('admin.campaigns.index'))
Campaigns
@elseif(Route::is('admin.campaigns.create'))
Create New Campaign
@elseif(Route::is('admin.campaigns.edit'))
Edit Campaign {{ $campaign->title }}
@elseif(Route::is('admin.campaigns.show'))
View Campaign {{ $campaign->title }}
@endif
| Admin Panel -
{{ config('app.name') }}
