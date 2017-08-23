
/* Auto-generated profile routes */
Route::middleware(['admin'])->group(function () {
    {!! str_pad("Route::get('/admin/profile',", 60) !!}'{{ $controllerPartiallyFullName }}@editProfile')->name('admin/profile/edit');
    {!! str_pad("Route::post('/admin/profile',", 60) !!}'{{ $controllerPartiallyFullName }}@updateProfile')->name('admin/profile/update');
    {!! str_pad("Route::get('/admin/password',", 60) !!}'{{ $controllerPartiallyFullName }}@editPassword')->name('admin/password/edit');
    {!! str_pad("Route::post('/admin/password',", 60) !!}'{{ $controllerPartiallyFullName }}@updatePassword')->name('admin/password/update');
});