{{'@'}}extends('brackets/admin::admin.layout.form', ['action' => route('admin/{{ $objectName }}/update', ['{{ $objectName }}' => ${{ $objectName }}])])

{{'@'}}section('title')
    <h1>Edit {{ $objectName }} {{'{{'}} ${{ $objectName }}->{{ $titleColumn }} }}</h1>
{{'@'}}endsection

{{'@'}}section('body')
@foreach($columns as $col)
    @if($col['type'] == 'date')

    <div class="form-group">
        <label for="{{ $col['name'] }}" class="col-sm-2 control-label">{{ ucfirst($col['name']) }}</label>
        <div class="col-sm-6">
            <input type="date" class="form-control" id="{{ $col['name'] }}" name="{{ $col['name'] }}" value="{{'{{'}} old('{{ $col['name'] }}', ${{ $objectName }}->{{ $col['name'] }}->toDateString()) }}">
        </div>
    </div>
    @elseif($col['type'] == 'time')

    <div class="form-group">
        <label for="{{ $col['name'] }}" class="col-sm-2 control-label">{{ ucfirst($col['name']) }}</label>
        <div class="col-sm-4">
            <input type="time" class="form-control" id="{{ $col['name'] }}" name="{{ $col['name'] }}" value="{{'{{'}} old('{{ $col['name'] }}', ${{ $objectName }}->{{ $col['name'] }}->toTimeString()) }}">
        </div>
    </div>
    @elseif($col['type'] == 'datetime')

    <div class="form-group">
        <label for="{{ $col['name'] }}_date" class="col-sm-2 control-label">{{ ucfirst($col['name']) }}</label>
        <div class="col-sm-6">
            <input type="date" class="form-control" id="{{ $col['name'] }}_date" name="{{ $col['name'] }}_date" value="{{'{{'}} old('{{ $col['name'] }}_date', ${{ $objectName }}->{{ $col['name'] }}->toDateString()) }}">
        </div>
        <div class="col-sm-4">
            <input type="time" class="form-control" id="{{ $col['name'] }}_time" name="{{ $col['name'] }}_time" value="{{'{{'}} old('{{ $col['name'] }}_time', ${{ $objectName }}->{{ $col['name'] }}->toTimeString()) }}">
        </div>
        <!-- TODO concat date and time together into one field -->
        <input type="hidden" name="{{ $col['name'] }}">
    </div>
    @elseif($col['type'] == 'text')

    <div class="form-group">
        <label for="{{ $col['name'] }}" class="col-sm-2 control-label">{{ ucfirst($col['name']) }}</label>
        <div class="col-sm-10">
            <textarea class="form-control" rows="2" id="{{ $col['name'] }}" name="{{ $col['name'] }}" placeholder="Lorem ipsum dolor itum..">{{'{{'}} old('{{ $col['name'] }}', ${{ $objectName }}->{{ $col['name'] }}) }}</textarea>
        </div>
    </div>
    @elseif($col['type'] == 'boolean')

    <div class="form-group">
        <div class="col-sm-offset-2 col-sm-10">
            <div class="checkbox">
                <label>
                    <input type="checkbox" name="{{ $col['name'] }}" value="1"{{'@'}}if(old('{{ $col['name'] }}', ${{ $objectName }}->{{ $col['name'] }})) checked="checked"{{'@'}}endif> {{ ucfirst($col['name']) }}
                </label>
            </div>
        </div>
    </div>
    @else

    <div class="form-group">
        <label for="{{ $col['name'] }}" class="col-sm-2 control-label">{{ ucfirst($col['name']) }}</label>
        <div class="col-sm-10">
            <input type="text" class="form-control" id="{{ $col['name'] }}" name="{{ $col['name'] }}" placeholder="{{ ucfirst($col['name']) }}" value="{{'{{'}} old('{{ $col['name'] }}', ${{ $objectName }}->{{ $col['name'] }}) }}">
        </div>
    </div>
    @endif
@endforeach

    <div class="form-group">
        <div class="col-sm-offset-2 col-sm-10">
            <button type="submit" class="btn btn-primary">Save</button>
        </div>
    </div>

    {{'{{'}} method_field('PUT') }}

{{'@'}}endsection