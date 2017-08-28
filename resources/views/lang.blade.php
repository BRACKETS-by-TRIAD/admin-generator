    '{{ $modelLangFormat }}' => [
        'actions' => [
            'index' => '{{ $modelPlural }}',
            'create' => 'New {{ studly_case($modelBaseName) }}',
            'edit' => 'Edit :name',
        ],

        'columns' => [
            @foreach($columns as $col)'{{ $col['name'] }}' => "{{ ucfirst(str_replace('_', ' ', $col['name'])) }}",
            @endforeach

        ],
    ],

    // Do not delete me :) I'm used for auto-generation