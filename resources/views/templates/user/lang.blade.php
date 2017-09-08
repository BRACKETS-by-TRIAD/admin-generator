    '{{ $modelLangFormat }}' => [
        'title' => '{{ $modelPlural }}',

        'actions' => [
            'index' => '{{ $modelPlural }}',
            'create' => 'New {{ studly_case($modelBaseName) }}',
            'edit' => 'Edit :name',
            'edit_profile' => 'Edit Profile',
            'edit_password' => 'Edit Password',
        ],

        'columns' => [
            'id' => "ID",
            @foreach($columns as $col)'{{ $col['name'] }}' => "{{ ucfirst(str_replace('_', ' ', $col['name'])) }}",
@if($col['name'] == 'password')            '{{ $col['name'] }}_repeat' => "{{ ucfirst(str_replace('_', ' ', $col['name'])) }} Confirmation",
@endif
            @endforeach
@if (count($relations))
    @if (count($relations['belongsToMany']))

            //Belongs to many relations
            @foreach($relations['belongsToMany'] as $belongsToMany)'{{ lcfirst($belongsToMany['related_model_name_plural']) }}' => "{{ ucfirst(str_replace('_', ' ', $belongsToMany['related_model_name_plural'])) }}",
            @endforeach
    @endif
@endif

        ],
    ],

    // Do not delete me :) I'm used for auto-generation