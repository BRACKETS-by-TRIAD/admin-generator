@php echo "<?php"
@endphp namespace {{ $modelNameSpace }};

use Illuminate\Database\Eloquent\Model;

class {{ $modelBaseName }} extends Model
{
    @if (!is_null($tableName))protected $table = '{{ $tableName }}';
    @endif

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

    @if (!$timestamps)public $timestamps = false;
    @endif

}
