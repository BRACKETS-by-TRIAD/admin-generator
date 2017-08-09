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

class Store{{ $modelBaseName }} extends @if($translatable)TranslatableFormRequest @else FormRequest
@endif
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return Gate::allows('admin.store.{{ $modelDotNotation }}');
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
@if($translatable)
        $standardRules = collect([
            @foreach($standardColumn as $column)'{{ $column['name'] }}' => '{!! implode('|', (array) $column['serverStoreRules']) !!}',
            @endforeach

        ]);

        $rules = $this->getRequiredLocales()->flatMap(function($locale){
            return collect([
                @foreach($translatableColumns as $column)'{{ $column['name'] }}' => ['{!! implode('\', \'', (array) $column['serverStoreRules']) !!}'],
                @endforeach

            ])->mapWithKeys(function($rule, $ruleKey) use ($locale) {
                //TODO refactor
                if(!$locale['required']) {
                    if(($key = array_search('required', $rule)) !== false) {
                        unset($rule[$key]);
                        array_push($rule, 'nullable');
                    }
                }
                if(($key = array_search('uniqueTranslatable', $rule)) !== false) {
                    $rule[$key] = Rule::unique('{{$tableName}}', $ruleKey.'->'.$locale['locale']);
                }
                return [$ruleKey.'.'.$locale['locale'] => $rule];
            });
        })->merge($standardRules);

        return $rules->toArray();
@else
        return [
            @foreach($columns as $column)'{{ $column['name'] }}' => '{!! implode('|', (array) $column['serverStoreRules']) !!}',
            @endforeach

        ];
@endif
    }
}
