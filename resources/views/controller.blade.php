@php echo "<?php";
@endphp namespace {{ $controllerNamespace }};

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use \Illuminate\Http\Response;
use App\Http\Requests\Admin\Store{{ $modelBaseName }};
use App\Http\Requests\Admin\Update{{ $modelBaseName }};
use Brackets\Admin\AdminListing;
use {{ $modelFullName }};
@if (count($relations))
@if (count($relations['belongsToMany']))
@foreach($relations['belongsToMany'] as $belongsToMany)
use {{ $belongsToMany['related_model'] }};
@endforeach
@endif
@endif

class {{ $controllerBaseName }} extends Controller
{

    /**
     * Display a listing of the resource.
     *
     * {{'@'}}param  Request $request
     * {{'@'}}return Response|array
     */
    public function index(Request $request)
    {
        // TODO add authorization

        // TODO params validation (filter/search/pagination/ordering) - maybe extract as a Request?

        // create and AdminListing instance for a specific model and
        $data = AdminListing::instance({{ $modelBaseName }}::class)->processRequestAndGet(
            // pass the request with params
            $request,

            // set columns to query
            ['{!! implode('\', \'', $columnsToQuery) !!}'],

            // set columns to searchIn
            ['{!! implode('\', \'', $columnsToSearchIn) !!}']
        );

        if ($request->ajax()) {
            return ['data' => $data];
        }

        return view('admin.{{ $modelDotNotation }}.index', ['data' => $data]);

    }

    /**
     * Show the form for creating a new resource.
     *
     * {{'@'}}return Response
     */
    public function create()
    {
        // TODO add authorization

        return view('admin.{{ $modelDotNotation }}.create',[
@if (count($relations))
@if (count($relations['belongsToMany']))
@foreach($relations['belongsToMany'] as $belongsToMany)
            '{{ $belongsToMany['related_table'] }}' => {{ $belongsToMany['related_model_name'] }}::all(),
@endforeach
@endif
@endif
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * {{'@'}}param  Store{{ $modelBaseName }} $request
     * {{'@'}}return Response|array
     */
    public function store(Store{{ $modelBaseName }} $request)
    {
        // Sanitize input
        $sanitized = $request->only([
            @foreach($columns as $column)'{{ $column['name'] }}',
            @endforeach

        ]);

        // Store the {{ $modelBaseName }}
        ${{ $modelVariableName }} = {{ $modelBaseName }}::create($sanitized);

@if (count($relations))
@if (count($relations['belongsToMany']))
@foreach($relations['belongsToMany'] as $belongsToMany)
        // But we do have a {{ $belongsToMany['related_table'] }}, so we need to attach the {{ $belongsToMany['related_table'] }} to the {{ $modelVariableName }}
        ${{ $modelVariableName }}->{{ $belongsToMany['related_table'] }}()->sync($request->input('{{ $belongsToMany['related_table'] }}', []));
@endforeach
@endif
@endif

        if ($request->ajax()) {
            return ['redirect' => url('admin/{{ $modelViewsDirectory }}')];
        }

        return redirect('admin/{{ $modelViewsDirectory }}')
            ->withSuccess("Created");
    }

    /**
     * Display the specified resource.
     *
     * {{'@'}}param  {{ $modelBaseName }} ${{ $modelVariableName }}
     * {{'@'}}return Response
     */
    public function show({{ $modelBaseName }} ${{ $modelVariableName }})
    {
        // TODO add authorization

        // TODO your code goes here
    }

    /**
     * Show the form for editing the specified resource.
     *
     * {{'@'}}param  {{ $modelBaseName }} ${{ $modelVariableName }}
     * {{'@'}}return Response
     */
    public function edit({{ $modelBaseName }} ${{ $modelVariableName }})
    {
        // TODO add authorization

@if (count($relations))
@if (count($relations['belongsToMany']))
@foreach($relations['belongsToMany'] as $belongsToMany)
        ${{ $modelVariableName }}->load('{{ $belongsToMany['related_table'] }}');
@endforeach
@endif
@endif

        return view('admin.{{ $modelDotNotation }}.edit', [
            '{{ $modelRouteAndViewName }}' => ${{ $modelVariableName }},
@if (count($relations))
@if (count($relations['belongsToMany']))
@foreach($relations['belongsToMany'] as $belongsToMany)
            '{{ $belongsToMany['related_table'] }}' => {{ $belongsToMany['related_model_name'] }}::all(),
@endforeach
@endif
@endif
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * {{'@'}}param  Update{{ $modelBaseName }} $request
     * {{'@'}}param  {{ $modelBaseName }} ${{ $modelVariableName }}
     * {{'@'}}return Response|array
     */
    public function update(Update{{ $modelBaseName }} $request, {{ $modelBaseName }} ${{ $modelVariableName }})
    {
        // Sanitize input
        $sanitized = $request->only([
            @foreach($columns as $column)'{{ $column['name'] }}',
            @endforeach

        ]);

        // Update changed values {{ $modelBaseName }}
        ${{ $modelVariableName }}->update($sanitized);

@if (count($relations))
@if (count($relations['belongsToMany']))
@foreach($relations['belongsToMany'] as $belongsToMany)
        // But we do have a {{ $belongsToMany['related_table'] }}, so we need to attach the {{ $belongsToMany['related_table'] }} to the {{ $modelVariableName }}
        ${{ $modelVariableName }}->{{ $belongsToMany['related_table'] }}()->sync($request->input('{{ $belongsToMany['related_table'] }}', []));
@endforeach
@endif
@endif

        if ($request->ajax()) {
            return ['redirect' => url('admin/{{ $modelViewsDirectory }}')];
        }

        return redirect('admin/{{ $modelViewsDirectory }}')
            ->withSuccess("Updated");
    }

    /**
     * Remove the specified resource from storage.
     *
     * {{'@'}}param  Request $request
     * {{'@'}}param  {{ $modelBaseName }} ${{ $modelVariableName }}
     * {{'@'}}return Response|bool
     */
    public function destroy(Request $request, {{ $modelBaseName }} ${{ $modelVariableName }})
    {
        // TODO add authorization

        ${{ $modelVariableName }}->delete();

        if ($request->ajax()) {
            return response([]);
        }

        return redirect()->back()
            ->withSuccess("Deleted");
    }
}
