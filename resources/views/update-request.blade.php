@php echo "<?php"
@endphp namespace App\Http\Requests\Admin\{{ $modelWithNamespaceFromDefault }};
@php
    if($translatable) {
        $translatableColumns = $columns->filter(function($column) use ($translatable) {
            return in_array($column['name'], $translatable->toArray());
        });
        $standardColumn = $columns->reject(function($column) use ($translatable) {
            return in_array($column['name'], $translatable->toArray());
        });
    }
@endphp

@if($translatable)use Brackets\Admin\TranslatableFormRequest;
@else use Illuminate\Foundation\Http\FormRequest;
@endif
use Gate;
use Illuminate\Validation\Rule;
use {{ $modelFullName }};

class Update{{ $modelBaseName }} extends @if($translatable)TranslatableFormRequest @else FormRequest
@endif
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return Gate::allows('admin.update.{{ $modelDotNotation }}', $this->{{ $modelVariableName }});
    }

@if($translatable)/**
     * Get the validation rules that apply to the requests untranslatable fields.
     *
     * @return  array
     */
    public function untranslatableRules() {
        return [
            @foreach($standardColumn as $column)'{{ $column['name'] }}' => [{!! implode(', ', (array) $column['serverUpdateRules']) !!}],
            @endforeach

        ];
    }

    /**
     * Get the validation rules that apply to the requests translatable fields.
     *
     * @return  array
     */
    public function translatableRules($locale) {
        return [
            @foreach($translatableColumns as $column)'{{ $column['name'] }}' => [{!! implode(', ', (array) $column['serverUpdateRules']) !!}],
            @endforeach

        ];
    }
@else/**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            @foreach($columns as $column)'{{ $column['name'] }}' => '{!! implode('|', (array) $column['serverUpdateRules']) !!}',
            @endforeach

        ];
    }
@endif
}
