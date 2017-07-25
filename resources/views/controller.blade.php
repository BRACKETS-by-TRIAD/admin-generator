@php echo "<?php";
@endphp namespace {{ $controllerNamespace }};

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Brackets\Admin\AdminListing;
use {{ $modelFullName }};
@if($userGeneration)use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Hash;
@endif
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
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response|array
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

        return view('admin.{{ $modelRouteAndViewName }}.index', ['data' => $data]);

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        // TODO add authorization

        return view('admin.{{ $modelRouteAndViewName }}.create',[
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
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response|array
     */
    public function store(Request $request)
    {
        // TODO add authorization

        // Validate the request
        $this->validate($request, [
            @foreach($columns as $column)'{{ $column['name'] }}' => '{!! implode('|', (array) $column['serverStoreRules']) !!}',
            @endforeach

        ]);

        // Sanitize input
        $sanitized = $request->only([
            @foreach($columns as $column)'{{ $column['name'] }}',
            @endforeach

        ]);

        @if($userGeneration)

        //Modify input, set activated if needed and set hashed password
        $sanitized = $this->modifyInputData($sanitized, false);
        @endif

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
            return ['redirect' => url('admin/{{ $modelRouteAndViewName }}')];
        }

        return redirect('admin/{{ $modelRouteAndViewName }}')
            ->withSuccess("Created");
    }

    /**
     * Display the specified resource.
     * @param  {{ $modelBaseName }} ${{ $modelVariableName }}
     * @return \Illuminate\Http\Response
     */
    public function show({{ $modelBaseName }} ${{ $modelVariableName }})
    {
        // TODO add authorization

        // TODO your code goes here
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  {{ $modelBaseName }} ${{ $modelVariableName }}
     * @return \Illuminate\Http\Response
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

        return view('admin.{{ $modelRouteAndViewName }}.edit', [
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
     * @param  \Illuminate\Http\Request  $request
     * @param  {{ $modelBaseName }} ${{ $modelVariableName }}
     * @return \Illuminate\Http\Response|array
     */
    public function update(Request $request, {{ $modelBaseName }} ${{ $modelVariableName }})
    {
        // TODO add authorization

        // Validate the request
        $this->validate($request, [
            @foreach($columns as $column)'{{ $column['name'] }}' => '{!! implode('|', (array) $column['serverUpdateRules']) !!}',
            @endforeach

        ]);

        // Sanitize input
        $sanitized = $request->only([
            @foreach($columns as $column)'{{ $column['name'] }}',
            @endforeach

        ]);
        @if($userGeneration)

        //Modify input, set activated if needed and set hashed password
        $sanitized = $this->modifyInputData($sanitized, true);
        @endif

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
            return ['redirect' => url('admin/{{ $modelRouteAndViewName }}')];
        }

        return redirect('admin/{{ $modelRouteAndViewName }}')
            ->withSuccess("Updated");
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  {{ $modelBaseName }} ${{ $modelVariableName }}
     * @return \Illuminate\Http\Response|bool
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

    @if($userGeneration)/**
    * Modify input data for save
    *
    * @param  array $data
    * @param  bool $edit
    * @return array
    */
    protected function modifyInputData($data, $edit = false)
    {
        if (array_key_exists('password', $data) && empty($data['password']) && $edit) {
            unset($data['password']);
        }
        if(!empty($data['password'])) {
            $data['password'] = Hash::make($data['password']);
        }
        return $data;
    }
    @endif

}
