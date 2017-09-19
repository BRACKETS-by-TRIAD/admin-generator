
/* Auto-generated admin routes */
Route::middleware(['admin'])->group(function () {
    {!! str_pad("Route::get('/admin/".$resource."',", 60) !!}'{{ $controllerPartiallyFullName }}@index');
    {!! str_pad("Route::get('/admin/".$resource."/create',", 60) !!}'{{ $controllerPartiallyFullName }}@create');
    {!! str_pad("Route::post('/admin/".$resource."/store',", 60) !!}'{{ $controllerPartiallyFullName }}@store');
    {!! str_pad("Route::get('/admin/".$resource."/edit/{".$modelVariableName."}',", 60) !!}'{{ $controllerPartiallyFullName }}@edit')->name('admin/{{ $resource }}/edit');
    {!! str_pad("Route::post('/admin/".$resource."/update/{".$modelVariableName."}',", 60) !!}'{{ $controllerPartiallyFullName }}@update')->name('admin/{{ $resource }}/update');
    {!! str_pad("Route::delete('/admin/".$resource."/destroy/{".$modelVariableName."}',", 60) !!}'{{ $controllerPartiallyFullName }}@destroy')->name('admin/{{ $resource }}/destroy');
    {!! str_pad("Route::get('/admin/".$resource."/resend-activation/{".$modelVariableName."}',", 60) !!}'{{ $controllerPartiallyFullName }}@resendActivationEmail')->name('admin/{{ $resource }}/resendActivationEmail');
});