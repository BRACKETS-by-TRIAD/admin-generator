{{'@'}}extends('brackets/admin::admin.layout.default')

{{'@'}}section('body')

    <div class="container-xl">

        <div class="card">

            @if($hasTranslatable)<{{ $modelJSName }}-form
            :action="'{{'{{'}} route('{{ $route }}', ['{{ $modelVariableName }}' => ${{ $modelVariableName }}]) }}'"
            :data="{{'{{'}} ${{ $modelVariableName }}->toJsonAllLocales() }}"
            :locales="@{{ json_encode($locales) }}"
            :send-empty-locales="false"
            inline-template>
            @else<{{ $modelJSName }}-form
            :action="'{{'{{'}} route('{{ $route }}', ['{{ $modelVariableName }}' => ${{ $modelVariableName }}]) }}'"
            :data="{{'{{'}} ${{ $modelVariableName }}->toJson() }}"
            inline-template>
            @endif

                <form class="form-horizontal" method="post" {{'@'}}submit.prevent="onSubmit" :action="this.action">

                    <div class="card-header">
                        <i class="fa fa-plus"></i> {{ $modelBaseName }}
                    </div>

                    <div class="card-block">

                        @include('brackets/admin-generator::form')

                    </div>

                    <div class="card-footer">
                        <button type="submit" class="btn btn-primary">Save</button>
                    </div>

                </form>

            </{{ $modelJSName }}-form>

        </div>

    </div>

{{'@'}}endsection