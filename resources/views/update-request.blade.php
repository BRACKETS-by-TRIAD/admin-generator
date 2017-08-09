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

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        ${{ $modelVariableName }} = $this->{{ $modelVariableName }};

@if($translatable)
        $standardRules = collect([
            @foreach($standardColumn as $column)'{{ $column['name'] }}' => '{!! implode('|', (array) $column['serverUpdateRules']) !!}',
            @endforeach

        ]);

        $rules = $this->getRequiredLocales()->flatMap(function($locale) use (${{ $modelVariableName }}){
            return collect([
                @foreach($translatableColumns as $column)'{{ $column['name'] }}' => ['{!! implode('\', \'', (array) $column['serverUpdateRules']) !!}'],
                @endforeach

            ])->mapWithKeys(function($rule, $ruleKey) use ($locale, ${{ $modelVariableName }}) {
                //TODO refactor
                if(!$locale['required']) {
                    if(($key = array_search('required', $rule)) !== false) {
                        unset($rule[$key]);
                        array_push($rule, 'nullable');
                    }
                }
                if(($key = array_search('uniqueTranslatable', $rule)) !== false) {
                    @if($hasSoftDelete)$rule[$key] = Rule::unique('{{$tableName}}', $ruleKey.'->'.$locale['locale'])->ignore(${{ $modelVariableName }}->id, 'id')->whereNull('deleted_at');
                    @else$rule[$key] = Rule::unique('{{$tableName}}', $ruleKey.'->'.$locale['locale'])->ignore(${{ $modelVariableName }}->id, 'id');
                    @endif

                }
                return [$ruleKey.'.'.$locale['locale'] => $rule];
            });
        })->merge($standardRules);

        return $rules->toArray();
@else
        return [
            @foreach($columns as $column)'{{ $column['name'] }}' => '{!! implode('|', (array) $column['serverUpdateRules']) !!}',
            @endforeach

        ];
@endif
    }
}
