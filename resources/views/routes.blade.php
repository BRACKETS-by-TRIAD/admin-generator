
/* Auto-generated admin routes */
{!! str_pad("Route::get('/admin/".$objectName."',", 60) !!}'{{ $namespace }}\{{ $className }}@index');
{!! str_pad("Route::get('/admin/".$objectName."/create',", 60) !!}'{{ $namespace }}\{{ $className }}@create');
{!! str_pad("Route::post('/admin/".$objectName."/store',", 60) !!}'{{ $namespace }}\{{ $className }}@store');
{!! str_pad("Route::get('/admin/".$objectName."/edit/{".$objectName."}',", 60) !!}'{{ $namespace }}\{{ $className }}@edit')->name('admin/{{ $objectName }}/edit');
{!! str_pad("Route::put('/admin/".$objectName."/update/{".$objectName."}',", 60) !!}'{{ $namespace }}\{{ $className }}@update')->name('admin/{{ $objectName }}/update');
{!! str_pad("Route::delete('/admin/".$objectName."/destroy/{".$objectName."}',", 60) !!}'{{ $namespace }}\{{ $className }}@destroy')->name('admin/{{ $objectName }}/destroy');