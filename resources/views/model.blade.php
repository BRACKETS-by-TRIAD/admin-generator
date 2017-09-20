@php echo "<?php"
@endphp namespace {{ $modelNameSpace }};
@php
    $hasRoles = false;
    if(count($relations) && count($relations['belongsToMany'])) {
        $hasRoles = $relations['belongsToMany']->filter(function($belongsToMany) {
            return $belongsToMany['related_table'] == 'roles';
        })->count() > 0;
        $relations['belongsToMany'] = $relations['belongsToMany']->reject(function($belongsToMany) {
            return $belongsToMany['related_table'] == 'roles';
        });
    }
@endphp

use Illuminate\Database\Eloquent\Model;
@if($hasSoftDelete)use Illuminate\Database\Eloquent\SoftDeletes;
@endif
@if($hasRoles)use Spatie\Permission\Traits\HasRoles;
@endif
@if($translatable->count() > 0)use Brackets\Translatable\Traits\HasTranslations;
@endif

class {{ $modelBaseName }} extends Model
{
    @if($hasSoftDelete)use SoftDeletes;
    @endif
@if($hasRoles)use HasRoles;
@endif
@if($translatable->count() > 0)use HasTranslations;
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

    @if ($translatable->count() > 0)// these attributes are translatable
    public $translatable = [
    @foreach($translatable as $translatableField)
    "{{ $translatableField }}",
    @endforeach

    ];
    @endif

    @if (!$timestamps)public $timestamps = false;
    @endif

    protected $appends = ['resource_url'];

    /* ************************ ACCESSOR ************************* */

    public function getResourceUrlAttribute() {
        return url('/admin/{{$resource}}/'.$this->getKey());
    }

    @if (count($relations))/* ************************ RELATIONS ************************ */

    @if (count($relations['belongsToMany']))
@foreach($relations['belongsToMany'] as $belongsToMany)/**
    * Relation to {{ $belongsToMany['related_model_name_plural'] }}
    *
    * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
    */
    public function {{ $belongsToMany['related_table'] }}() {
        return $this->belongsToMany({{ $belongsToMany['related_model_class'] }}, '{{ $belongsToMany['relation_table'] }}', '{{ $belongsToMany['foreign_key'] }}', '{{ $belongsToMany['related_key'] }}');
    }

@endforeach
    @endif
    @endif

}
