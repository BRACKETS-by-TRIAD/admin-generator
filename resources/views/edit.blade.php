{{'@'}}extends('brackets/admin::admin.layout.default')

{{'@'}}section('title', trans('admin.{{ $modelLangFormat }}.actions.edit', [${{ $modelVariableName }}->{{$modelTitle}}]))

{{'@'}}section('body')

    <div class="container-xl">

        <div class="card">

            @if($hasTranslatable)<{{ $modelJSName }}-form
                :action="'{{'{{'}} route('admin/{{ $modelViewsDirectory }}/update', ['{{ $modelVariableName }}' => ${{ $modelVariableName }}]) }}'"
                :data="{{'{{'}} ${{ $modelVariableName }}->toJsonAllLocales() }}"
                :locales="@{{ json_encode($locales) }}"
                :send-empty-locales="false"
                inline-template>
            @else<{{ $modelJSName }}-form
                :action="'{{'{{'}} route('admin/{{ $modelViewsDirectory }}/update', ['{{ $modelVariableName }}' => ${{ $modelVariableName }}]) }}'"
                :data="{{'{{'}} ${{ $modelVariableName }}->toJson() }}"
                inline-template>
            @endif

                <form class="form-horizontal" method="post" {{'@'}}submit.prevent="onSubmit" :action="this.action">

                    <div class="card-header">
                        <i class="fa fa-pencil"></i> {{'{{'}} trans('admin.{{ $modelLangFormat }}.actions.edit', [${{ $modelVariableName }}->{{$modelTitle}}]) }}
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