{{'@'}}extends('brackets/admin::admin.layout.form')

{{'@'}}section('body')

    <div class="container-xl">

        <div class="card">

            <{{ $viewName }}-form
                :action="'{{'{{'}} route('{{ $route }}') }}'"
                :data="{{'{{'}} ${{ $modelRouteAndViewName }}->toJson() }}"
                inline-template>

                <form class="form-horizontal" method="post" {{'@'}}submit.prevent="onSubmit" :action="this.action">

                    <div class="card-header">
                        <i class="fa fa-plus"></i> Edit Profile
                    </div>

                    <div class="card-block">

@php
    $columns = $columns->reject(function($column) {
        return in_array($column['name'], ['password', 'activated', 'forbidden']);
    });
@endphp
                        @include('brackets/admin-generator::templates.profile.form')

                    </div>

                    <div class="card-footer">
                        <button type="submit" class="btn btn-primary">Save</button>
                    </div>

                </form>

            </{{ $viewName }}-form>

        </div>

    </div>

{{'@'}}endsection