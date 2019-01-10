@php echo "<?php";
@endphp namespace {{ $exportNamespace }};

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithHeadings;
use {{ $modelFullName }};

class {{ $classBaseName }} implements FromCollection, WithMapping, WithHeadings
{
    /**
     * {{'@'}}return \Illuminate\Support\Collection
     */
    public function collection()
    {
        return {{$modelBaseName}}::all();
    }

    public function headings(): array
    {
        return [
@foreach($columnsToExport as $column)
            trans('admin.{{ $modelLangFormat }}.columns.{{ $column }}'),
@endforeach
        ];
    }

    /**
     * {{'@'}}param {{$modelBaseName}} ${{ $modelVariableName }}
     * {{'@'}}return array
     *
     */
    public function map(${{ $modelVariableName }}): array
    {
        return [
@foreach($columnsToExport as $column)
            ${{$modelVariableName}}->{{$column}},
@endforeach
        ];
    }
}
