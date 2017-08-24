{{'@'}}extends('brackets/admin::admin.layout.default')

{{'@'}}section('title', '{{ $modelPlural }}')

{{'@'}}section('body')

    <{{ $modelJSName }}-listing
        :data="{{'{{'}} $data->toJson() }}"
        :url="'{{'{{'}} url('admin/{{ $modelViewsDirectory }}') }}'"
        inline-template>

        <div class="row">
            <div class="col">
                <div class="card">
                    <div class="card-header">
                        <i class="fa fa-align-justify"></i> {{ $modelPlural }} listing
                        <a class="btn btn-primary btn-sm pull-right m-b-0" href="{{'{{'}} url('admin/{{ $modelViewsDirectory }}/create') }}" role="button"><i class="fa fa-plus"></i>&nbsp; New {{ $modelBaseName }}</a>
                    </div>
                    <div class="card-block" v-cloak>
                        <form @submit.prevent="">
                            <div class="row justify-content-md-between">
                                <div class="col col-lg-7 col-xl-5 form-group">
                                    <div class="input-group">
                                        <input class="form-control" placeholder="Search" v-model="search" @keyup.enter="filter('search', $event.target.value)" />
                                        <span class="btn-group input-group-btn">
                                            <button type="button" class="btn btn-primary" @click="filter('search', search)"><i class="fa fa-search"></i>&nbsp; Search</button>
                                        </span>
                                    </div>
                                </div>

                                <div class="col-sm-auto form-group ">
                                    <select class="form-control" v-model="pagination.state.per_page">
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
                                    @foreach($columns as $col)<th is='sortable' :column="'{{ $col['name'] }}'">{{ ucfirst($col['name']) }}</th>
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
                                                <a class="btn btn-sm btn-info" :href="'{{'{{'}} url('admin/{{ $modelViewsDirectory }}/edit') }}/' + item.id" title="Edit" role="button"><i class="fa fa-edit"></i></a>
                                            </div>
                                            <form class="col" @submit.prevent="deleteItem('{{'{{'}} url('admin/{{ $modelViewsDirectory }}/destroy') }}/' + item.id)">
                                                <button type="submit" class="btn btn-sm btn-danger" title="Delete"><i class="fa fa-trash-o"></i></button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            </tbody>
                        </table>

                        <div class="row" v-if="pagination.state.total > 0">
                            <div class="col-sm">
                                <span class="pagination-caption">Displaying from {{'@{{'}} pagination.state.from }} to {{'@{{'}} pagination.state.to }} of total {{'@{{'}} pagination.state.total }} items.</span>
                            </div>
                            <div class="col-sm-auto">
                                <!-- TODO how to add push state to this pagination so the URL will actually change? we need JS router - do we want it? -->
                                <pagination></pagination>
                            </div>
                        </div>

	                    <div class="no-items-found" v-if="!collection.length > 0">
		                    <i class="fa fa-search"></i>
		                    <h3>Could not find any {{ $modelPlural }}</h3>
		                    <p>Try changing the filters or add a new one</p>
	                    </div>
                    </div>
                </div>
            </div>
        </div>
    </{{ $modelJSName }}-listing>

{{'@'}}endsection