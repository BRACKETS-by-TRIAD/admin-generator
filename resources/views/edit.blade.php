{{'@'}}extends('brackets/admin::admin.layout.form')

{{'@'}}section('body')

    <div class="container-xl">

        <div class="card">

            <{{ $modelRouteAndViewName }}-form
                :action="'{{'{{'}} route('admin/{{ $modelRouteAndViewName }}/update', ['{{ $modelRouteAndViewName }}' => ${{ $modelRouteAndViewName }}]) }}'"
                :default="{{'{{'}} ${{ $modelRouteAndViewName }}->toJson() }}"
                inline-template>

                <form class="form-horizontal" method="post" {{'@'}}submit.prevent="onSubmit" :action="this.action">

                    <div class="card-header">
                        <i class="fa fa-plus"></i> Edit the {{ $modelBaseName }}
                    </div>

                    <div class="card-block">

                        {{'@'}}include('admin.{{ $modelRouteAndViewName }}.components.form-elements')

                        {{'{{'}} csrf_field() }}

                    </div>

                    <div class="card-footer">
                        <button type="submit" class="btn btn-primary">Save</button>
                    </div>

                </form>

        </{{ $modelRouteAndViewName }}-form>

    </div>

</div>

{{'@'}}endsection