{{'@'}}extends('brackets/admin::admin.layout.form')

{{'@'}}section('body')

    <div class="container-xl">

        <div class="card">

            <{{ $viewName }}-form
                :action="'{{'{{'}} route('{{ $route }}', ['{{ $modelRouteAndViewName }}' => ${{ $modelRouteAndViewName }}]) }}'"
                :data="{{'{{'}} ${{ $modelRouteAndViewName }}->toJson() }}"
                inline-template>

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

            </{{ $viewName }}-form>

        </div>

    </div>

{{'@'}}endsection