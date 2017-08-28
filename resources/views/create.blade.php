{{'@'}}extends('brackets/admin::admin.layout.default')

{{'@'}}section('title', trans('admin.{{ $modelLangFormat }}.actions.create'))

{{'@'}}section('body')

    <div class="container-xl">

        <div class="card">

            <{{ $modelJSName }}-form
                :action="'{{'{{'}} url('admin/{{ $modelViewsDirectory }}/store') }}'"
                @if($hasTranslatable):locales="@{{ json_encode($locales) }}"
                :send-empty-locales="false"@endif

                inline-template>

                <form class="form-horizontal" method="post" {{'@'}}submit.prevent="onSubmit" :action="this.action">

                    <div class="card-header">
                        <i class="fa fa-plus"></i> {{'{{'}} trans('admin.{{ $modelLangFormat }}.actions.create') }}
                    </div>

                    <div class="card-block">

                        {{'@'}}include('admin.{{ $modelDotNotation }}.components.form-elements')

                    </div>

                    <div class="card-footer">
                        <button type="submit" class="btn btn-primary">@{{ trans('brackets/admin::admin.btn.save') }}</button>
                    </div>

                </form>

            </{{ $modelJSName }}-form>

        </div>

    </div>

{{'@'}}endsection