@php echo "<?php"
@endphp namespace {{ $namespace }};

use Illuminate\Database\Eloquent\Model;

class {{ $modelName }} extends Model
{
    @if ($dates)protected $dates = [
    @foreach($dates as $date)
    "{{ $date }}",
    @endforeach

    ];
    @endif

}
