
/* Auto-generated admin routes */
{!! str_pad("Route::get('/admin/".$modelRouteAndViewName."',", 60) !!}'{{ $controllerPartiallyFullName }}@index');
{!! str_pad("Route::get('/admin/".$modelRouteAndViewName."/create',", 60) !!}'{{ $controllerPartiallyFullName }}@create');
{!! str_pad("Route::post('/admin/".$modelRouteAndViewName."/store',", 60) !!}'{{ $controllerPartiallyFullName }}@store');
{!! str_pad("Route::get('/admin/".$modelRouteAndViewName."/edit/{".$modelRouteAndViewName."}',", 60) !!}'{{ $controllerPartiallyFullName }}@edit')->name('admin/{{ $modelRouteAndViewName }}/edit');
{!! str_pad("Route::post('/admin/".$modelRouteAndViewName."/update/{".$modelRouteAndViewName."}',", 60) !!}'{{ $controllerPartiallyFullName }}@update')->name('admin/{{ $modelRouteAndViewName }}/update');
{!! str_pad("Route::delete('/admin/".$modelRouteAndViewName."/destroy/{".$modelRouteAndViewName."}',", 60) !!}'{{ $controllerPartiallyFullName }}@destroy')->name('admin/{{ $modelRouteAndViewName }}/destroy');