/** @var \Illuminate\Database\Eloquent\Factory $factory */
$factory->define(App\Models\{{ $modelFullName }}::class, function (Faker\Generator $faker) {
    return [
        @foreach($columns as $col)'{{ $col['name'] }}' => {!! $col['faker'] !!},
        @endforeach

    ];
});

