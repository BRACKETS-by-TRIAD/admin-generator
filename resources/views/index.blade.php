{{'@'}}extends('brackets/admin-ui::admin.layout.default')

{{'@'}}section('title', trans('admin.{{ $modelLangFormat }}.actions.index'))

{{'@'}}section('body')

    <{{ $modelJSName }}-listing
        :data="{{'{{'}} $data->toJson() }}"
        :url="'{{'{{'}} url('admin/{{ $resource }}') }}'"
        inline-template>

        <div class="row">
            <div class="col">
                <div class="card">
                    <div class="card-header">
                        <i class="fa fa-align-justify"></i> {{'{{'}} trans('admin.{{ $modelLangFormat }}.actions.index') }}
@if($export)
                        <a class="btn btn-primary btn-sm pull-right m-b-0 ml-2" href="{{'{{'}} url('admin/{{ $resource }}/export') }}" role="button"><i class="fa fa-file-excel-o"></i>&nbsp; {{'{{'}} trans('admin.{{ $modelLangFormat }}.actions.export') }}</a>
@endif
                        <a class="btn btn-primary btn-spinner btn-sm pull-right m-b-0" href="{{'{{'}} url('admin/{{ $resource }}/create') }}" role="button"><i class="fa fa-plus"></i>&nbsp; {{'{{'}} trans('admin.{{ $modelLangFormat }}.actions.create') }}</a>
                    </div>
                    <div class="card-body" v-cloak>
                        <form @submit.prevent="">
                            <div class="row justify-content-md-between">
                                <div class="col col-lg-7 col-xl-5 form-group">
                                    <div class="input-group">
                                        <input class="form-control" placeholder="@{{ trans('brackets/admin-ui::admin.placeholder.search') }}" v-model="search" @keyup.enter="filter('search', $event.target.value)" />
                                        <span class="input-group-append">
                                            <button type="button" class="btn btn-primary" @click="filter('search', search)"><i class="fa fa-search"></i>&nbsp; @{{ trans('brackets/admin-ui::admin.btn.search') }}</button>
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

                        <table class="table table-hover table-listing">
                            <thead>
                                <tr>
@if(!$withoutBulk)
                                    <th class="bulk-checkbox">
                                        <input class="form-check-input" id="enabled" type="checkbox" v-model="isClickedAll" v-validate="''" data-vv-name="enabled"  name="enabled_fake_element" @click="onBulkItemsClickedAll('/admin/{{ $resource }}')">
                                        <label class="form-check-label" for="enabled">
                                            #
                                        </label>
                                    </th>
@endif

                                    @foreach($columns as $col)<th is='sortable' :column="'{{ $col['name'] }}'">{{'{{'}} trans('admin.{{ $modelLangFormat }}.columns.{{ $col['name'] }}') }}</th>
                                    @endforeach

                                    <th></th>
                                </tr>
@if(!$withoutBulk)
                                <tr v-show="(clickedBulkItemsCount > 0) || isClickedAll">
                                    <td class="bg-primary d-table-cell text-center" colspan="{{count($columns) + 2 }}">
                                        <span class="align-middle font-weight-light text-white">@{{ trans('brackets/admin-ui::admin.listing.selected_items') }} {{'@{{'}} clickedBulkItemsCount }}.  <a href="#" class="text-secondary" @click="onBulkItemsClickedAll('/admin/{{ $resource }}')" v-if="(clickedBulkItemsCount < pagination.state.total)"> <i class="fa" :class="bulkCheckingAllLoader ? 'fa-spinner' : ''"></i> @{{ trans('brackets/admin-ui::admin.listing.check_all_items') }} {{'@{{'}} pagination.state.total }}</a> | <a
                                                    href="#" class="text-secondary" @click="onBulkItemsClickedAllUncheck()">@{{ trans('brackets/admin-ui::admin.listing.uncheck_all_items') }}</a>  </span>

                                            <span class="pull-right col-md-2 text-primary font-weight-light">
                                            <button class="btn btn-secondary btn-sm" @click="bulkDelete('/admin/{{ $resource }}/bulk-destroy')"><i class="fa fa-trash-o text-secondary"></i> @{{ trans('brackets/admin-ui::admin.btn.delete') }}</button>
                                        </span>

                                    </td>
                                </tr>
@endif
                            </thead>
                            <tbody>
                                <tr v-for="(item, index) in collection" @if(!$withoutBulk):key="item.id" :class="bulkItems[item.id] ? 'bg-bulk' : ''"@endif>
@if(!$withoutBulk)
                                    <td class="bulk-checkbox">
                                        <input class="form-check-input" :id="'enabled' + item.id" type="checkbox" v-model="bulkItems[item.id]" v-validate="''" :data-vv-name="'enabled' + item.id"  :name="'enabled' + item.id + '_fake_element'" @click="onBulkItemClicked(item.id)" :disabled="bulkCheckingAllLoader">
                                        <label class="form-check-label" :for="'enabled' + item.id">
                                        </label>
                                    </td>
@endif

                                    @foreach($columns as $col)@if($col['switch'])<td>
                                        <label class="switch switch-3d switch-success">
                                            <input type="checkbox" class="switch-input" v-model="collection[index].{{ $col['name'] }}" @change="toggleSwitch(item.resource_url, '{{ $col['name'] }}', collection[index])">
                                            <span class="switch-slider"></span>
                                        </label>
                                    </td>
@elseif($col['name'] === 'created_by_admin_user_id')<div class="user-detail-tooltips-list">
                                        <td>
                                            <user-detail-tooltip :user="item.created_by_admin_user" v-if="item.created_by_admin_user">
                                                <p>Created on {{'@{{'}} item.created_at | datetime('HH:mm:ss, DD.MM.YYYY') }}</p>
                                            </user-detail-tooltip>
                                        </td>
                                    </div>
@elseif($col['name'] === 'updated_by_admin_user_id')<div class="user-detail-tooltips-list">
                                        <td>
                                            <user-detail-tooltip :user="item.updated_by_admin_user" v-if="item.updated_by_admin_user">
                                                <p>Updated on {{'@{{'}} item.updated_at | datetime('HH:mm:ss, DD.MM.YYYY') }}</p>
                                            </user-detail-tooltip>
                                        </td>
                                    </div>
                                    @else<td>{{'@{{'}} item.{{ $col['name'] }}{{ $col['filters'] }} }}</td>@endif

                                    @endforeach

                                    <td>
                                        <div class="row no-gutters">
                                            <div class="col-auto">
                                                <a class="btn btn-sm btn-spinner btn-info" :href="item.resource_url + '/edit'" title="@{{ trans('brackets/admin-ui::admin.btn.edit') }}" role="button"><i class="fa fa-edit"></i></a>
                                            </div>
                                            <form class="col" @submit.prevent="deleteItem(item.resource_url)">
                                                <button type="submit" class="btn btn-sm btn-danger" title="@{{ trans('brackets/admin-ui::admin.btn.delete') }}"><i class="fa fa-trash-o"></i></button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            </tbody>
                        </table>

                        <div class="row" v-if="pagination.state.total > 0">
                            <div class="col-sm">
                                <span class="pagination-caption">@{{ trans('brackets/admin-ui::admin.pagination.overview') }}</span>
                            </div>
                            <div class="col-sm-auto">
                                <pagination></pagination>
                            </div>
                        </div>

	                    <div class="no-items-found" v-if="!collection.length > 0">
		                    <i class="icon-magnifier"></i>
		                    <h3>@{{ trans('brackets/admin-ui::admin.index.no_items') }}</h3>
		                    <p>@{{ trans('brackets/admin-ui::admin.index.try_changing_items') }}</p>
                            <a class="btn btn-primary btn-spinner" href="{{'{{'}} url('admin/{{ $resource }}/create') }}" role="button"><i class="fa fa-plus"></i>&nbsp; {{'{{'}} trans('admin.{{ $modelLangFormat }}.actions.create') }}</a>
	                    </div>
                    </div>
                </div>
            </div>
        </div>
    </{{ $modelJSName }}-listing>

{{'@'}}endsection