{{'@'}}extends('brackets/admin-ui::admin.layout.default')

{{'@'}}section('title', trans('admin.{{ $modelLangFormat }}.actions.create'))

{{'@'}}section('body')

    <div class="container-xl">

        <div class="card">

            <{{ $modelJSName }}-form
                :action="'{{'{{'}} url('admin/{{ $resource }}') }}'"
                @if($hasTranslatable):locales="@{{ json_encode($locales) }}"
                :send-empty-locales="false"@endif

                inline-template>

                <form class="form-horizontal form-create" method="post" {{'@'}}submit.prevent="onSubmit" :action="this.action" novalidate>

                    <div class="card-header">
                        <i class="fa fa-plus"></i> {{'{{'}} trans('admin.{{ $modelLangFormat }}.actions.create') }}
                    </div>

                    <div class="card-block">

                        {{'@'}}include('admin.{{ $modelDotNotation }}.components.form-elements')

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