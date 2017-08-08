/** @var \Illuminate\Database\Eloquent\Factory $factory */
$factory->define({{ $modelFullName }}::class, function (Faker\Generator $faker) {
    return [
        @foreach($columns as $col)'{{ $col['name'] }}' => {!! $col['faker'] !!},
        @endforeach

    ];
});

