@php echo "<?php"
@endphp namespace {{ $namespace }};

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
@if($modelFullName)use App\Models\{{ $modelFullName }};
@endif

class {{ $className }} extends Controller
{

    /**
    * Display a listing of the resource.
    *
    * @return \Illuminate\Http\Response
    */
    public function index()
    {
        //
    }

    /**
    * Show the form for creating a new resource.
    *
    * @return \Illuminate\Http\Response
    */
    public function create()
    {
        //
    }

    /**
    * Store a newly created resource in storage.
    *
    * @param  \Illuminate\Http\Request  $request
    * @return \Illuminate\Http\Response
    */
    public function store(Request $request)
    {
        //
    }

@if($modelName)
    /**
    * Display the specified resource.
    * @param  {{ $modelName }} $object
    * @return \Illuminate\Http\Response
    */
    public function show({{ $modelName }} $object)
@else
    /**
    * Display the specified resource.
    * @param  int  $id
    * @return \Illuminate\Http\Response
    */
    public function show($id)
@endif
    {
        //
    }

@if($modelName)
    /**
    * Show the form for editing the specified resource.
    *
    * @param  {{ $modelName }} $object
    * @return \Illuminate\Http\Response
    */
    public function edit({{ $modelName }} $object)
@else
    /**
    * Display the specified resource.
    * @param  int  $id
    * @return \Illuminate\Http\Response
    */
    public function edit($id)
@endif
    {
        //
    }

@if($modelName)
    /**
    * Update the specified resource in storage.
    *
    * @param  \Illuminate\Http\Request  $request
    * @param  {{ $modelName }} $object
    * @return \Illuminate\Http\Response
    */
    public function update(Request $request, {{ $modelName }} $object)
@else
    /**
    * Update the specified resource in storage.
    *
    * @param  \Illuminate\Http\Request  $request
    * @param  int  $id
    * @return \Illuminate\Http\Response
    */
    public function update(Request $request, $id)
@endif
    {
        //
    }

@if($modelName)
    /**
    * Remove the specified resource from storage.
    *
    * @param  {{ $modelName }} $object
    * @return \Illuminate\Http\Response
    */
    public function destroy({{ $modelName }} $object)
@else
    /**
    * Remove the specified resource from storage.
    *
    * @param  int  $id
    * @return \Illuminate\Http\Response
    */
    public function destroy($id)
@endif
    {
        //
    }

}
