
/* Auto-generated profile routes */
{!! str_pad("Route::get('/admin/profile',", 60) !!}'{{ $controllerPartiallyFullName }}@editProfile')->name('admin/profile/edit');
{!! str_pad("Route::post('/admin/profile/update',", 60) !!}'{{ $controllerPartiallyFullName }}@updateProfile')->name('admin/profile/update');
{!! str_pad("Route::get('/admin/profile/password',", 60) !!}'{{ $controllerPartiallyFullName }}@editPassword')->name('admin/password/edit');
{!! str_pad("Route::post('/admin/profile/password/update',", 60) !!}'{{ $controllerPartiallyFullName }}@updatePassword')->name('admin/password/update');