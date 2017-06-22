{{'@'}}extends('brackets/admin::admin.layout.index')

{{'{{-- TODO remove @'}}section('title')
    <h1>{{ ucfirst($objectNamePlural) }} listing</h1>

    <a class="btn btn-primary" href="{{'{{'}} url('admin/{{ $objectName }}/create') }}" role="button">Add new {{ $objectName }}</a>
{{'@'}}endsection{{'--'}}}}

{{'@'}}section('body')

    <{{ $objectName }}-listing
        :data="{{'{{'}} $data->toJson() }}"
        :url="'{{'{{'}} url('admin/{{ $objectName }}') }}'"
        inline-template>

        <div class="row">
            <div class="col">
                <div class="card">
                    <div class="card-header">
                        <i class="fa fa-align-justify"></i> {{ ucfirst($objectNamePlural) }} listing
                    </div>
                    <div class="card-block">
                        <form @submit.prevent="">
                            <div class="row">
                                <div class="col-sm-12 col-md-7 col-xl-5 form-group small-right-gutter-md">
                                    <div class="input-group">
                                        <input class="form-control" placeholder="Search" @keyup.enter="filter('search', $event.target.value)" />
                                        <span class="btn-group input-group-btn">
                                            <button type="button" class="btn btn-primary" @click="filter('search', $event.target.value)"><i class="fa fa-search"></i></button>
                                        </span>
                                    </div>
                                </div>

                                <div class="col"></div> <!-- dynamic space between -->

                                <div class="col-sm-auto form-group ">
                                    <select class="form-control" v-model="pagination.state.per_page">
                                        <option value="10">10</option>
                                        <option value="25">25</option>
                                        <option value="100">100</option>
                                    </select>
                                </div>

                            </div>
                        </form>

                        <table class="table table-striped table-hover">
                            <thead>
                                <tr>
                                    @foreach($columns as $col)<th is='sortable' :column="'{{ $col }}'">{{ ucfirst($col) }}</th>
                                    @endforeach

                                    <th></th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="item in collection">
                                    @foreach($columns as $col)<td>{{'@{{'}} item.{{ $col }} }}</td>
                                    @endforeach

                                    <td>
                                        <div class="row no-gutters">
                                            <div class="col-auto">
                                                <a class="btn btn-sm btn-info" :href="'{{'{{'}} url('admin/{{ $objectName }}/edit') }}/' + item.id" title="Edit" role="button"><i class="fa fa-edit"></i></a>
                                            </div>
                                            <form class="col" :action="'{{'{{'}} url('admin/{{ $objectName }}/destroy') }}/' + item.id" method="post">
                                                <input name="_method" type="hidden" value="DELETE">
                                                {{'{'}}!! csrf_field() !!{{'}'}}
                                                <button type="submit" class="btn btn-sm btn-danger" title="Delete"><i class="fa fa-trash-o"></i></button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            </tbody>
                        </table>

                        <div class="row">
                            <div class="col">
                                <span v-if="collection">Displaying from @{{ pagination.state.from }} to @{{ pagination.state.to }} of total @{{ pagination.state.total }} items.</span>
                            </div>
                            <div class="col-auto">
                                <!-- TODO how to add push state to this pagination so the URL will actually change? we need JS router - do we want it? -->
                                <pagination></pagination>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </{{ $objectName }}-listing>

{{'@'}}endsection