{{'@'}}extends('brackets/admin::admin.layout.default')

{{'@'}}section('title', trans('admin.{{ $modelDotNotation }}.actions.index'))

{{'@'}}section('body')

    <{{ $modelJSName }}-listing
        :data="{{'{{'}} $data->toJson() }}"
        :url="'{{'{{'}} url('admin/{{ $modelViewsDirectory }}') }}'"
        inline-template>

        <div class="row">
            <div class="col">
                <div class="card">
                    <div class="card-header">
                        <i class="fa fa-align-justify"></i> {{'{{'}} trans('admin.{{ $modelDotNotation }}.actions.index') }}
                        <a class="btn btn-primary btn-sm pull-right m-b-0" href="{{'{{'}} url('admin/{{ $modelViewsDirectory }}/create') }}" role="button"><i class="fa fa-plus"></i>&nbsp; {{'{{'}} trans('admin.{{ $modelDotNotation }}.actions.create') }}</a>
                    </div>
                    <div class="card-block" v-cloak>
                        <form @submit.prevent="">
                            <div class="row justify-content-md-between">
                                <div class="col col-lg-7 col-xl-5 form-group">
                                    <div class="input-group">
                                        <input class="form-control" placeholder="@{{ trans('brackets/admin::admin.placeholder.search') }}" v-model="search" @keyup.enter="filter('search', $event.target.value)" />
                                        <span class="btn-group input-group-btn">
                                            <button type="button" class="btn btn-primary" @click="filter('search', search)"><i class="fa fa-search"></i>&nbsp; @{{ trans('brackets/admin::admin.btn.search') }}</button>
                                        </span>
                                    </div>
                                </div>

                                <div class="col-sm-auto form-group ">
                                    <select class="form-control" v-model="pagination.state.per_page">
                                        {{-- TODO extract these options into a config or smtn --}}
                                        <option value="10">10</option>
                                        <option value="25">25</option>
                                        <option value="100">100</option>
                                    </select>
                                </div>

                            </div>
                        </form>

                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    @foreach($columns as $col)<th is='sortable' :column="'{{ $col['name'] }}'">{{'{{'}} trans('admin.{{ $modelDotNotation }}.columns.{{ $col['name'] }}') }}</th>
                                    @endforeach

                                    <th></th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="(item, index) in collection">
                                    @foreach($columns as $col)@if($col['switch'])<td>
                                        <label class="switch switch-3d switch-success">
                                            <input type="checkbox" class="switch-input" v-model="collection[index].{{ $col['name'] }}" @change="toggleSwitch('{{'{{'}} url('admin/{{ $modelViewsDirectory }}/update') }}/' + item.id, '{{ $col['name'] }}', collection[index])">
                                            <span class="switch-label"></span>
                                            <span class="switch-handle"></span>
                                        </label>
                                    </td>
                                    @else<td>{{'@{{'}} item.{{ $col['name'] }}{{ $col['filters'] }} }}</td>@endif

                                    @endforeach

                                    <td>
                                        <div class="row no-gutters">
                                            <div class="col-auto">
                                                <a class="btn btn-sm btn-info" :href="'{{'{{'}} url('admin/{{ $modelViewsDirectory }}/edit') }}/' + item.id" title="@{{ trans('brackets/admin::admin.btn.edit') }}" role="button"><i class="fa fa-edit"></i></a>
                                            </div>
                                            <form class="col" @submit.prevent="deleteItem('{{'{{'}} url('admin/{{ $modelViewsDirectory }}/destroy') }}/' + item.id)">
                                                <button type="submit" class="btn btn-sm btn-danger" title="@{{ trans('brackets/admin::admin.btn.delete') }}"><i class="fa fa-trash-o"></i></button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            </tbody>
                        </table>

                        <div class="row" v-if="pagination.state.total > 0">
                            <div class="col-sm">
                                <span class="pagination-caption">@{{ trans('brackets/admin::admin.pagination.overview') }}</span>
                            </div>
                            <div class="col-sm-auto">
                                <pagination></pagination>
                            </div>
                        </div>

	                    <div class="no-items-found" v-if="!collection.length > 0">
		                    <i class="fa fa-search"></i>
		                    <h3>@{{ trans('brackets/admin::admin.index.no_items') }}</h3>
		                    <p>@{{ trans('brackets/admin::admin.index.try_changing_items') }}</p>
	                    </div>
                    </div>
                </div>
            </div>
        </div>
    </{{ $modelJSName }}-listing>

{{'@'}}endsection