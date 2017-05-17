@php echo "<?php"
@endphp namespace {{ $namespace }};

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Redirect;
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
        // TODO add authorization

        return view('admin.{{ $objectName }}.index', [
            'data' => {{ $className }}::all(),
        ]);
    }

    /**
    * Show the form for creating a new resource.
    *
    * @return \Illuminate\Http\Response
    */
    public function create()
    {
        // TODO add authorization

        return view('admin.{{ $objectName }}.create');
    }

    /**
    * Store a newly created resource in storage.
    *
    * @param  \Illuminate\Http\Request  $request
    * @return \Illuminate\Http\Response
    */
    public function store(Request $request)
    {
        // TODO add authorization

        // Validate the request
        $this->validate($request, [
            @foreach($columns as $column)'{{ $column['name'] }}' => '{{ implode('|', (array) $column['rules']) }}',
            @endforeach

        ]);

        // Sanitize input
        $sanitized = $request->only([
            @foreach($columns as $column)'{{ $column['name'] }}',
            @endforeach

        ]);

        // Store the {{ $objectName }}
        {{ $modelName }}::create($sanitized);

        return redirect(('admin/{{ $objectName }}')
            ->withSuccess(trans('admin.operation.succeed'));
    }

@if($modelName)
    /**
    * Display the specified resource.
    * @param  {{ $modelName }} ${{ $objectName }}
    * @return \Illuminate\Http\Response
    */
    public function show({{ $modelName }} ${{ $objectName }})
@else
    /**
    * Display the specified resource.
    * @param  int  $id
    * @return \Illuminate\Http\Response
    */
    public function show($id)
@endif
    {
        // TODO add authorization
    }

@if($modelName)
    /**
    * Show the form for editing the specified resource.
    *
    * @param  {{ $modelName }} ${{ $objectName }}
    * @return \Illuminate\Http\Response
    */
    public function edit({{ $modelName }} ${{ $objectName }})
@else
    /**
    * Display the specified resource.
    * @param  int  $id
    * @return \Illuminate\Http\Response
    */
    public function edit($id)
@endif
    {
        // TODO add authorization

        return ${{ $objectName }};
    }

@if($modelName)
    /**
    * Update the specified resource in storage.
    *
    * @param  \Illuminate\Http\Request  $request
    * @param  {{ $modelName }} ${{ $objectName }}
    * @return \Illuminate\Http\Response
    */
    public function update(Request $request, {{ $modelName }} ${{ $objectName }})
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
        // TODO add authorization
    }

@if($modelName)
    /**
    * Remove the specified resource from storage.
    *
    * @param  {{ $modelName }} ${{ $objectName }}
    * @return \Illuminate\Http\Response
    */
    public function destroy({{ $modelName }} ${{ $objectName }})
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
        // TODO add authorization
    }

}
