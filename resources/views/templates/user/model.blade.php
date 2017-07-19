@php echo "<?php"
@endphp namespace {{ $modelNameSpace }};

use Brackets\AdminAuth\Auth\Activations\CanActivate;
use Brackets\AdminAuth\Contracts\Auth\CanActivate as CanActivateContract;
use Brackets\AdminAuth\Notifications\ResetPassword;
@if($hasSoftDelete)use Illuminate\Database\Eloquent\SoftDeletes;
@endif
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class {{ $modelBaseName }} extends Authenticatable implements CanActivateContract
{
    use Notifiable;
    use CanActivate;
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


    /**
     * Send the password reset notification.
     *
     * @param  string  $token
     * @return void
     */
    public function sendPasswordResetNotification($token)
    {
        $this->notify(app( ResetPassword::class, ['token' => $token]));
    }
}
