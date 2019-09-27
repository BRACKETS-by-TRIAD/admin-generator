@php echo "<?php";
@endphp


namespace {{ $controllerNamespace }};

@if($export)
use App\Exports\{{$exportBaseName}};
@endif
use App\Http\Controllers\Controller;
@if(!$withoutBulk)
use App\Http\Requests\Admin\{{ $modelWithNamespaceFromDefault }}\BulkDestroy{{ $modelBaseName }};
@endif
use App\Http\Requests\Admin\{{ $modelWithNamespaceFromDefault }}\Destroy{{ $modelBaseName }};
use App\Http\Requests\Admin\{{ $modelWithNamespaceFromDefault }}\Index{{ $modelBaseName }};
use App\Http\Requests\Admin\{{ $modelWithNamespaceFromDefault }}\Store{{ $modelBaseName }};
use App\Http\Requests\Admin\{{ $modelWithNamespaceFromDefault }}\Update{{ $modelBaseName }};
use {{ $modelFullName }};
use Brackets\AdminListing\Facades\AdminListing;
@if(!$withoutBulk && $hasSoftDelete)
use Carbon\Carbon;
@endif
use Exception;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Contracts\Routing\ResponseFactory;
use Illuminate\Contracts\View\Factory;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Response;
use Illuminate\Routing\Redirector;
@if (count($relations))
@if (count($relations['belongsToMany']))
@foreach($relations['belongsToMany'] as $belongsToMany)
use {{ $belongsToMany['related_model'] }};
@endforeach
@endif
@endif
@if(!$withoutBulk)
use Illuminate\Support\Facades\DB;
@endif
@if(in_array('created_by_admin_user_id', $columnsToQuery) || in_array('updated_by_admin_user_id', $columnsToQuery))
use Illuminate\Support\Facades\Auth;
@endif
@if($export)use Maatwebsite\Excel\Facades\Excel;
@endif
@if($export)use Symfony\Component\HttpFoundation\BinaryFileResponse;
@endif
use Illuminate\View\View;

class {{ $controllerBaseName }} extends Controller
{

    /**
     * Display a listing of the resource.
     *
     * {{'@'}}param Index{{ $modelBaseName }} $request
     * {{'@'}}return array|Factory|View
     */
    public function index(Index{{ $modelBaseName }} $request)
    {
        // create and AdminListing instance for a specific model and
        $data = AdminListing::create({{ $modelBaseName }}::class)->processRequestAndGet(
            // pass the request with params
            $request,

            // set columns to query
            ['{!! implode('\', \'', $columnsToQuery) !!}'],

            // set columns to searchIn
            ['{!! implode('\', \'', $columnsToSearchIn) !!}']@if(in_array('created_by_admin_user_id', $columnsToQuery) || in_array('updated_by_admin_user_id', $columnsToQuery)),@endif

@if(in_array('created_by_admin_user_id', $columnsToQuery) || in_array('updated_by_admin_user_id', $columnsToQuery))
    @if(in_array('created_by_admin_user_id', $columnsToQuery) && in_array('updated_by_admin_user_id', $columnsToQuery))
        function ($query) use ($request) {
                $query->with(['createdByAdminUser', 'updatedByAdminUser']);
            }
    @elseif(in_array('created_by_admin_user_id', $columnsToQuery))
        function ($query) use ($request) {
                $query->with(['createdByAdminUser']);
            }
    @elseif(in_array('updated_by_admin_user_id', $columnsToQuery))
        function ($query) use ($request) {
                $query->with(['updatedByAdminUser']);
            }
    @endif
@endif()
        );

        if ($request->ajax()) {
@if(!$withoutBulk)
            if ($request->has('bulk')) {
                return [
                    'bulkItems' => $data->pluck('id')
                ];
            }
@endif
            return ['data' => $data];
        }

        return view('admin.{{ $modelDotNotation }}.index', ['data' => $data]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * {{'@'}}throws AuthorizationException
     * {{'@'}}return Factory|View
     */
    public function create()
    {
        $this->authorize('admin.{{ $modelDotNotation }}.create');

@if (count($relations) && count($relations['belongsToMany']))
        return view('admin.{{ $modelDotNotation }}.create',[
@foreach($relations['belongsToMany'] as $belongsToMany)
            '{{ $belongsToMany['related_table'] }}' => {{ $belongsToMany['related_model_name'] }}::all(),
@endforeach
        ]);
@else
        return view('admin.{{ $modelDotNotation }}.create');
@endif
    }

    /**
     * Store a newly created resource in storage.
     *
     * {{'@'}}param Store{{ $modelBaseName }} $request
     * {{'@'}}return array|RedirectResponse|Redirector
     */
    public function store(Store{{ $modelBaseName }} $request)
    {
        // Sanitize input
        $sanitized = $request->getSanitized();
@if(in_array('created_by_admin_user_id', $columnsToQuery) || in_array('updated_by_admin_user_id', $columnsToQuery))
    @if(in_array('created_by_admin_user_id', $columnsToQuery) && in_array('updated_by_admin_user_id', $columnsToQuery))
    $sanitized['created_by_admin_user_id'] = Auth::getUser()->id;
        $sanitized['updated_by_admin_user_id'] = Auth::getUser()->id;
    @elseif(in_array('created_by_admin_user_id', $columnsToQuery))
        $sanitized['created_by_admin_user_id'] = Auth::getUser()->id;
    @elseif(in_array('updated_by_admin_user_id', $columnsToQuery))
        $sanitized['updated_by_admin_user_id'] = Auth::getUser()->id;
    @endif
@endif()

        // Store the {{ $modelBaseName }}
        ${{ $modelVariableName }} = {{ $modelBaseName }}::create($sanitized);

@if (count($relations))
@if (count($relations['belongsToMany']))
@foreach($relations['belongsToMany'] as $belongsToMany)
        // But we do have a {{ $belongsToMany['related_table'] }}, so we need to attach the {{ $belongsToMany['related_table'] }} to the {{ $modelVariableName }}
        ${{ $modelVariableName }}->{{ $belongsToMany['related_table'] }}()->sync(collect($request->input('{{ $belongsToMany['related_table'] }}', []))->map->id->toArray());
@endforeach

@endif
@endif
        if ($request->ajax()) {
            return ['redirect' => url('admin/{{ $resource }}'), 'message' => trans('brackets/admin-ui::admin.operation.succeeded')];
        }

        return redirect('admin/{{ $resource }}');
    }

    /**
     * Display the specified resource.
     *
     * {{'@'}}param {{ $modelBaseName }} ${{ $modelVariableName }}
     * {{'@'}}throws AuthorizationException
     * {{'@'}}return void
     */
    public function show({{ $modelBaseName }} ${{ $modelVariableName }})
    {
        $this->authorize('admin.{{ $modelDotNotation }}.show', ${{ $modelVariableName }});

        // TODO your code goes here
    }

    /**
     * Show the form for editing the specified resource.
     *
     * {{'@'}}param {{ $modelBaseName }} ${{ $modelVariableName }}
     * {{'@'}}throws AuthorizationException
     * {{'@'}}return Factory|View
     */
    public function edit({{ $modelBaseName }} ${{ $modelVariableName }})
    {
        $this->authorize('admin.{{ $modelDotNotation }}.edit', ${{ $modelVariableName }});

@if(in_array('created_by_admin_user_id', $columnsToQuery) || in_array('updated_by_admin_user_id', $columnsToQuery))
    @if(in_array('created_by_admin_user_id', $columnsToQuery) && in_array('updated_by_admin_user_id', $columnsToQuery))
    ${{ $modelVariableName }}->load(['createdByAdminUser', 'updatedByAdminUser']);
    @elseif(in_array('created_by_admin_user_id', $columnsToQuery))
    ${{ $modelVariableName }}->load('createdByAdminUser');
    @elseif(in_array('updated_by_admin_user_id', $columnsToQuery))
    ${{ $modelVariableName }}->load('updatedByAdminUser');
    @endif
@endif()

@if (count($relations))
@if (count($relations['belongsToMany']))
@foreach($relations['belongsToMany'] as $belongsToMany)
        ${{ $modelVariableName }}->load('{{ $belongsToMany['related_table'] }}');
@endforeach

@endif
@endif
        return view('admin.{{ $modelDotNotation }}.edit', [
            '{{ $modelVariableName }}' => ${{ $modelVariableName }},
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
     * {{'@'}}param Update{{ $modelBaseName }} $request
     * {{'@'}}param {{ $modelBaseName }} ${{ $modelVariableName }}
     * {{'@'}}return array|RedirectResponse|Redirector
     */
    public function update(Update{{ $modelBaseName }} $request, {{ $modelBaseName }} ${{ $modelVariableName }})
    {
        // Sanitize input
        $sanitized = $request->getSanitized();
@if(in_array('updated_by_admin_user_id', $columnsToQuery))
        $sanitized['updated_by_admin_user_id'] = Auth::getUser()->id;
@endif

        // Update changed values {{ $modelBaseName }}
        ${{ $modelVariableName }}->update($sanitized);

@if (count($relations))
@if (count($relations['belongsToMany']))
@foreach($relations['belongsToMany'] as $belongsToMany)
        // But we do have a {{ $belongsToMany['related_table'] }}, so we need to attach the {{ $belongsToMany['related_table'] }} to the {{ $modelVariableName }}
        if ($request->has('{{ $belongsToMany['related_table'] }}')) {
            ${{ $modelVariableName }}->{{ $belongsToMany['related_table'] }}()->sync(collect($request->input('{{ $belongsToMany['related_table'] }}', []))->map->id->toArray());
        }
@endforeach

@endif
@endif
        if ($request->ajax()) {
            return [
                'redirect' => url('admin/{{ $resource }}'),
                'message' => trans('brackets/admin-ui::admin.operation.succeeded'),
@if($containsPublishedAtColumn)
                'object' => ${{ $modelVariableName }}
@endif
            ];
        }

        return redirect('admin/{{ $resource }}');
    }

    /**
     * Remove the specified resource from storage.
     *
     * {{'@'}}param Destroy{{ $modelBaseName }} $request
     * {{'@'}}param {{ $modelBaseName }} ${{ $modelVariableName }}
     * {{'@'}}throws Exception
     * {{'@'}}return ResponseFactory|RedirectResponse|Response
     */
    public function destroy(Destroy{{ $modelBaseName }} $request, {{ $modelBaseName }} ${{ $modelVariableName }})
    {
        ${{ $modelVariableName }}->delete();

        if ($request->ajax()) {
            return response(['message' => trans('brackets/admin-ui::admin.operation.succeeded')]);
        }

        return redirect()->back();
    }

    @if(!$withoutBulk)/**
     * Remove the specified resources from storage.
     *
     * {{'@'}}param BulkDestroy{{ $modelBaseName }} $request
     * {{'@'}}throws Exception
     * {{'@'}}return Response|bool
     */
    public function bulkDestroy(BulkDestroy{{ $modelBaseName }} $request) : Response
    {
@if($hasSoftDelete)
        DB::transaction(static function () use ($request) {
            collect($request->data['ids'])
                ->chunk(1000)
                ->each(static function ($bulkChunk) {
                    DB::table('{{ str_plural($modelVariableName) }}')->whereIn('id', $bulkChunk)
                        ->update([
                            'deleted_at' => Carbon::now()->format('Y-m-d H:i:s')
                    ]);

                    // TODO your code goes here
                });
        });
@else
        DB::transaction(static function () use ($request) {
            collect($request->data['ids'])
                ->chunk(1000)
                ->each(static function ($bulkChunk) {
                    {{ $modelBaseName }}::whereIn('id', $bulkChunk)->delete();

                    // TODO your code goes here
                });
        });
@endif

        return response(['message' => trans('brackets/admin-ui::admin.operation.succeeded')]);
    }
@endif
@if($export)

    /**
     * Export entities
     *
     * {{'@'}}return BinaryFileResponse|null
     */
    public function export(): ?BinaryFileResponse
    {
        return Excel::download(app({{ $exportBaseName }}::class), '{{ str_plural($modelVariableName) }}.xlsx');
    }
@endif}
