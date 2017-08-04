@php echo "<?php"
@endphp namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class Save{{ $modelBaseName }}Request extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        // TODO
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            @foreach($columns as $column)'{{ $column['name'] }}' => '{{ implode('|', (array) $column['serverStoreRules']) }}',
            @endforeach

        ];
    }
}
