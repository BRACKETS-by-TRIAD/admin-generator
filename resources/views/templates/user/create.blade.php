{{'@'}}extends('brackets/admin::admin.layout.default')

{{'@'}}section('title', 'New {{ $modelBaseName }}')

{{'@'}}section('body')

    <div class="container-xl">

        <div class="card">

            <{{ $modelJSName }}-form
                :action="'{{'{{'}} url('admin/{{ $modelViewsDirectory }}/store') }}'"
                :activation="!!'@{{ $activation }}'"
                @if($hasTranslatable):locales="@{{ json_encode($locales) }}"
                :send-empty-locales="false"@endif

                inline-template>

                <form class="form-horizontal form-create" method="post" {{'@'}}submit.prevent="onSubmit" :action="this.action">

                    <div class="card-header">
                        <i class="fa fa-plus"></i> New {{ $modelBaseName }}
                    </div>

                    <div class="card-block">

                        {{'@'}}include('admin.{{ $modelDotNotation }}.components.form-elements')

                    </div>

                    <div class="card-footer">
	                    <button type="submit" class="btn btn-primary">
		                    <i class="fa" :class="submiting ? 'fa-spinner' : 'fa-download'"></i>
		                    Save
	                    </button>
                    </div>

                </form>

            </{{ $modelJSName }}-form>

        </div>

    </div>

{{'@'}}endsection