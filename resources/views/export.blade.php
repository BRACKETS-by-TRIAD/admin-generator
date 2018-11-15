@php echo "<?php";
@endphp namespace {{ $exportNamespace }};

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithMapping;
use {{ $modelFullName }};

class {{ $classBaseName }} implemets FromCollection, WithMapping
{
    /**
     * {{'@'}}return \Illuminate\Support\Collection
     */
    public function collection()
    {
        return {{$modelBaseName}}::all();
    }

    /**
     * {{'@'}}param {{$modelBaseName}} {{ $modelVariableName }}
     * {{'@'}}return array
     *
     */
    public function map(${{ $modelVariableName }}): array
    {
        return [
@foreach($columnsToExport as $column)
            {{$modelVariableName}}->{{$column}}
@endforeach
        ];
    }
}