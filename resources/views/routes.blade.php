
/* Auto-generated admin routes */
Route::middleware(['auth:' . config('admin-auth.defaults.guard'), 'admin'])->group(static function () {
    {!! str_pad("Route::get('/admin/".$resource."',", 60) !!}'{{ $controllerPartiallyFullName }}@index');
    {!! str_pad("Route::get('/admin/".$resource."/create',", 60) !!}'{{ $controllerPartiallyFullName }}@create');
    {!! str_pad("Route::post('/admin/".$resource."',", 60) !!}'{{ $controllerPartiallyFullName }}@store');
    {!! str_pad("Route::get('/admin/".$resource."/{".$modelVariableName."}/edit',", 60) !!}'{{ $controllerPartiallyFullName }}@edit')->name('admin/{{ $resource }}/edit');
@if(!$withoutBulk)
    {!! str_pad("Route::post('/admin/".$resource."/bulk-destroy',", 60) !!}'{{ $controllerPartiallyFullName }}@bulkDestroy')->name('admin/{{ $resource }}/bulk-destroy');
@endif
    {!! str_pad("Route::post('/admin/".$resource."/{".$modelVariableName."}',", 60) !!}'{{ $controllerPartiallyFullName }}@update')->name('admin/{{ $resource }}/update');
    {!! str_pad("Route::delete('/admin/".$resource."/{".$modelVariableName."}',", 60) !!}'{{ $controllerPartiallyFullName }}@destroy')->name('admin/{{ $resource }}/destroy');
@if($export)
    {!! str_pad("Route::get('/admin/".$resource."/export',", 60) !!}'{{ $controllerPartiallyFullName }}@export')->name('admin/{{ $resource }}/export');
@endif
});