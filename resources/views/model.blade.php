@php echo "<?php"
@endphp namespace {{ $modelNameSpace }};

use Illuminate\Database\Eloquent\Model;
@if($hasSoftDelete)use Illuminate\Database\Eloquent\SoftDeletes;
@endif

class {{ $modelBaseName }} extends Model
{
    @if($hasSoftDelete)use SoftDeletes;
    @endif

    @if (!is_null($tableName))protected $table = '{{ $tableName }}';
    @endif

    @if ($fillable)protected $fillable = [
    @foreach($fillable as $f)
    "{{ $f }}",
    @endforeach

    ];
    @endif

    @if ($hidden)protected $hidden = [
    @foreach($hidden as $h)
    "{{ $h }}",
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
