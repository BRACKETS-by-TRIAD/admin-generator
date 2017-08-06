@php echo "<?php"
@endphp namespace App\Http\Requests\Admin\{{ $modelBaseName }};

use Illuminate\Foundation\Http\FormRequest;
use Gate;

class Update{{ $modelBaseName }} extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return Gate::allows('update-{{ $modelRouteAndViewName }}', $this->{{ $modelRouteAndViewName }});
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            @foreach($columns as $column)'{{ $column['name'] }}' => '{{ implode('|', (array) $column['serverUpdateRules']) }}',
            @endforeach

        ];
    }
}
