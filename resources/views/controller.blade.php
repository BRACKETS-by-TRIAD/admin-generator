@php echo "<?php"
@endphp namespace {{ $namespace }};

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Brackets\Admin\AdminListing;
use App\Models\{{ $modelFullName }};

class {{ $className }} extends Controller
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
        $data = AdminListing::instance({{ $modelName }}::class)->processRequestAndGet(
            // pass the request with params
            $request,

            // set columns to query
            ['{!! implode('\', \'', $columnsToQuery) !!}'],

            // set columns to searchIn
            ['{!! implode('\', \'', $columnsToSearchIn) !!}']@if(count($filters) > 0),

            // optionally attach custom filters
            function($query) use ($request) {

                @foreach($filters as $filter)@if($filter['type'] == 'boolean')if ($request->has('{{ $filter['name'] }}')) {
                    $query->where('{{ $filter['name'] }}', $request->input('{{ $filter['name'] }}'));
                }
                @elseif($filter['type'] == 'date')if ($request->has('{{ $filter['name'] }}_from')) {
                    $query->where('{{ $filter['name'] }}', '>=', $request->input('{{ $filter['name'] }}_from'));
                }
                if ($request->has('{{ $filter['name'] }}_to')) {
                    $query->where('{{ $filter['name'] }}', '<=', $request->input('{{ $filter['name'] }}_to'));
                }
                @endif
@endforeach

                // TODO customize these filters to your needs

            }
            @endif

        );

        if ($request->ajax()) {
            return ['data' => $data];
        }

        return view('admin.{{ $objectName }}.index', ['data' => $data]);

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

        return redirect('admin/{{ $objectName }}')
            ->withSuccess("Created");
    }

    /**
     * Display the specified resource.
     * @param  {{ $modelName }} ${{ $objectName }}
     * @return \Illuminate\Http\Response
     */
    public function show({{ $modelName }} ${{ $objectName }})
    {
        // TODO add authorization
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  {{ $modelName }} ${{ $objectName }}
     * @return \Illuminate\Http\Response
     */
    public function edit({{ $modelName }} ${{ $objectName }})
    {
        // TODO add authorization

        return view('admin.post.edit', [
            '{{ $objectName }}' => ${{ $objectName }},
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  {{ $modelName }} ${{ $objectName }}
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, {{ $modelName }} ${{ $objectName }})
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

        // Update changed values {{ $objectName }}
        ${{ $objectName }}->update($sanitized);

        return redirect('admin/{{ $objectName }}')
            ->withSuccess("Updated");
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  {{ $modelName }} ${{ $objectName }}
     * @return \Illuminate\Http\Response
     */
    public function destroy({{ $modelName }} ${{ $objectName }})
    {
        // TODO add authorization

        ${{ $objectName }}->delete();

        return redirect()->back()
            ->withSuccess("Deleted");
    }

}
