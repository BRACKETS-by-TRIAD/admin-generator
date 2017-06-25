@foreach($columns as $col)@if($col['type'] == 'date')<div class="form-group row" :class="{'has-danger': errors.has('{{ $col['name'] }}'), 'has-success': this.fields.{{ $col['name'] }} && this.fields.{{ $col['name'] }}.valid }">
    <label for="{{ $col['name'] }}" class="col-md-3 col-form-label text-md-right">{{ ucfirst($col['name']) }}</label>
    <div class="col-sm-6">
        <input type="text" v-model="form.{{ $col['name'] }}" v-validate="'{{ implode('|', $col['frontendRules']) }}'" class="form-control" :class="{'form-control-danger': errors.has('{{ $col['name'] }}'), 'form-control-success': this.fields.{{ $col['name'] }} && this.fields.{{ $col['name'] }}.valid}" id="{{ $col['name'] }}" name="{{ $col['name'] }}" placeholder="YYYY-MM-DD">
        <div v-if="errors.has('{{ $col['name'] }}')" class="form-control-feedback" v-cloak>{{'@{{'}} errors.first('{{ $col['name'] }}') }}</div>
    </div>
</div>
@elseif($col['type'] == 'time')<div class="form-group row" :class="{'has-danger': errors.has('{{ $col['name'] }}'), 'has-success': this.fields.{{ $col['name'] }} && this.fields.{{ $col['name'] }}.valid }">
    <label for="{{ $col['name'] }}" class="col-md-3 col-form-label text-md-right">{{ ucfirst($col['name']) }}</label>
    <div class="col-sm-6">
        <input type="text" v-model="form.{{ $col['name'] }}" v-validate="'{{ implode('|', $col['frontendRules']) }}'" class="form-control" :class="{'form-control-danger': errors.has('{{ $col['name'] }}'), 'form-control-success': this.fields.{{ $col['name'] }} && this.fields.{{ $col['name'] }}.valid}" id="{{ $col['name'] }}" name="{{ $col['name'] }}" placeholder="hh:mm:ss">
        <div v-if="errors.has('{{ $col['name'] }}')" class="form-control-feedback" v-cloak>{{'@{{'}} errors.first('{{ $col['name'] }}') }}</div>
    </div>
</div>
@elseif($col['type'] == 'datetime')<div class="form-group row" :class="{'has-danger': errors.has('{{ $col['name'] }}'), 'has-success': this.fields.{{ $col['name'] }} && this.fields.{{ $col['name'] }}.valid }">
    <label for="{{ $col['name'] }}" class="col-md-3 col-form-label text-md-right">{{ ucfirst($col['name']) }}</label>
    <div class="col-sm-6">
        <input type="text" v-model="form.{{ $col['name'] }}" v-validate="'{{ implode('|', $col['frontendRules']) }}'" class="form-control" :class="{'form-control-danger': errors.has('{{ $col['name'] }}'), 'form-control-success': this.fields.{{ $col['name'] }} && this.fields.{{ $col['name'] }}.valid}" id="{{ $col['name'] }}" name="{{ $col['name'] }}" placeholder="YYYY-MM-DD hh:mm:ss">
        <div v-if="errors.has('{{ $col['name'] }}')" class="form-control-feedback" v-cloak>{{'@{{'}} errors.first('{{ $col['name'] }}') }}</div>
    </div>
</div>
@elseif($col['type'] == 'text')<div class="form-group row" :class="{'has-danger': errors.has('{{ $col['name'] }}'), 'has-success': this.fields.{{ $col['name'] }} && this.fields.{{ $col['name'] }}.valid }">
    <label for="{{ $col['name'] }}" class="col-md-3 col-form-label text-md-right">{{ ucfirst($col['name']) }}</label>
    <div class="col-md-9 col-xl-8">
        <textarea v-model="form.{{ $col['name'] }}" v-validate="'{{ implode('|', $col['frontendRules']) }}'" class="form-control" :class="{'form-control-danger': errors.has('{{ $col['name'] }}'), 'form-control-success': this.fields.{{ $col['name'] }} && this.fields.{{ $col['name'] }}.valid }" rows="3" id="{{ $col['name'] }}" name="{{ $col['name'] }}" placeholder="Lorem ipsum dolor itum.."></textarea>
        <div v-if="errors.has('{{ $col['name'] }}')" class="form-control-feedback" v-cloak>{{'@{{'}} errors.first('{{ $col['name'] }}') }}</div>
    </div>
</div>
@elseif($col['type'] == 'boolean')<div class="form-check row" :class="{'has-danger': errors.has('{{ $col['name'] }}'), 'has-success': this.fields.{{ $col['name'] }} && this.fields.{{ $col['name'] }}.valid }">
    <div class="col-md-9 col-xl-8 offset-md-3">
        <label class="form-check-label">
            <input class="form-check-input" type="checkbox" v-model="form.{{ $col['name'] }}" v-validate="'{{ implode('|', $col['frontendRules']) }}'" data-vv-name="{{ $col['name'] }}"  name="{{ $col['name'] }}_fake_element">
            <input type="hidden" name="{{ $col['name'] }}" :value="form.{{ $col['name'] }}">
            {{ ucfirst($col['name']) }}?
            <div v-if="errors.has('{{ $col['name'] }}')" class="form-control-feedback" v-cloak>{{'@{{'}} errors.first('{{ $col['name'] }}') }}</div>
        </label>
    </div>
</div>
@else<div class="form-group row" :class="{'has-danger': errors.has('{{ $col['name'] }}'), 'has-success': this.fields.{{ $col['name'] }} && this.fields.{{ $col['name'] }}.valid }">
    <label for="{{ $col['name'] }}" class="col-md-3 col-form-label text-md-right">{{ ucfirst($col['name']) }}</label>
    <div class="col-md-9 col-xl-8">
        <input type="text" v-model="form.{{ $col['name'] }}" v-validate="'{{ implode('|', $col['frontendRules']) }}'" class="form-control" :class="{'form-control-danger': errors.has('{{ $col['name'] }}'), 'form-control-success': this.fields.{{ $col['name'] }} && this.fields.{{ $col['name'] }}.valid}" id="{{ $col['name'] }}" name="{{ $col['name'] }}" placeholder="{{ ucfirst($col['name']) }}">
        <div v-if="errors.has('{{ $col['name'] }}')" class="form-control-feedback" v-cloak>{{'@{{'}} errors.first('{{ $col['name'] }}') }}</div>
    </div>
</div>
@endif

@endforeach
