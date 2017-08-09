@php echo "<?php"
@endphp namespace App\Http\Requests\Admin\{{ $modelWithNamespaceFromDefault }};

use Illuminate\Foundation\Http\FormRequest;
use Gate;
use {{ $modelFullName }};

class Update{{ $modelBaseName }} extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return Gate::allows('admin.{{ $modelDotNotation }}.edit', ['{{ $modelVariableName }}' => $this->{{ $modelVariableName }}]);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        ${{ $modelVariableName }} = $this->{{ $modelVariableName }};
        return [
            @foreach($columns as $column)'{{ $column['name'] }}' => '{!! implode('|', (array) $column['serverUpdateRules']) !!}',
            @endforeach

        ];
    }
}
