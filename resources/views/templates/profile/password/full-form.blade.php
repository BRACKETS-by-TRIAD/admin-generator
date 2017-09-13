{{'@'}}extends('brackets/admin-ui::admin.layout.default')

{{'@'}}section('title', trans('admin.{{ $modelLangFormat }}.actions.edit_password'))

{{'@'}}section('body')

    <div class="container-xl">

        <div class="card">

            <{{ $modelJSName }}-form
                :action="'{{'{{'}} url('{{ $route }}') }}'"
                :data="{{'{{'}} ${{ $modelVariableName }}->toJson() }}"
                inline-template>

                <form class="form-horizontal form-edit" method="post" {{'@'}}submit.prevent="onSubmit" :action="this.action">

                    <div class="card-header">
                        <i class="fa fa-pencil"></i> {{'{{'}} trans('admin.{{ $modelLangFormat }}.actions.edit_password') }}
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
                            @{{ trans('brackets/admin-ui::admin.btn.save') }}
                        </button>
                    </div>

                </form>

            </{{ $modelJSName }}-form>

        </div>

    </div>

{{'@'}}endsection