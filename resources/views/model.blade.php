@php echo "<?php"
@endphp namespace {{ $namespace }};

use Illuminate\Database\Eloquent\Model;

class {{ $className }} extends Model
{

    @if ($fillable)protected $fillable = [
    @foreach($fillable as $f)
    "{{ $f }}",
    @endforeach

    ];
    @endif

    @if ($dates)protected $dates = [
    @foreach($dates as $date)
    "{{ $date }}",
    @endforeach

    ];
    @endif

}
