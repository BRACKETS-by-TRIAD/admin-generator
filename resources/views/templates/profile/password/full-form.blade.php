{{'@'}}extends('brackets/admin::admin.layout.default')

{{'@'}}section('title', 'Edit Password')

{{'@'}}section('body')

    <div class="container-xl">

        <div class="card">

            <{{ $modelJSName }}-form
                :action="'{{'{{'}} route('{{ $route }}') }}'"
                :data="{{'{{'}} ${{ $modelVariableName }}->toJson() }}"
                inline-template>

                <form class="form-horizontal form-edit" method="post" {{'@'}}submit.prevent="onSubmit" :action="this.action">

                    <div class="card-header">
                        <i class="fa fa-pencil"></i> Edit Password
                    </div>

                    <div class="card-block">

@php
    $columns = $columns->reject(function($column) {
        return !in_array($column['name'], ['password']);
    });
@endphp
                        @include('brackets/admin-generator::templates.profile.form')

                    </div>

                    <div class="card-footer">
	                    <button type="submit" class="btn btn-primary" :disabled="submiting">
		                    <i class="fa" :class="submiting ? 'fa-spinner' : 'fa-download'"></i>
		                    Save
	                    </button>
                    </div>

                </form>

            </{{ $modelJSName }}-form>

        </div>

    </div>

{{'@'}}endsection