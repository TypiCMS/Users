<?php

namespace TypiCMS\Modules\Users\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use PhpOffice\PhpSpreadsheet\Shared\Date;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;
use TypiCMS\Modules\Core\Filters\FilterOr;
use TypiCMS\Modules\Users\Models\User;

class Export implements WithColumnFormatting, ShouldAutoSize, FromCollection, WithHeadings, WithMapping
{
    protected $collection;

    public function __construct($request)
    {
        $this->collection = QueryBuilder::for(User::class)
            ->allowedSorts(['first_name', 'last_name', 'email', 'subscription_plan', 'subscription_ends_at', 'last_payment_at', 'superuser'])
            ->allowedFilters([
                AllowedFilter::custom('first_name,last_name,email', new FilterOr()),
            ])
            ->get();
    }

    public function map($model): array
    {
        return [
            Date::dateTimeToExcel($model->created_at),
            Date::dateTimeToExcel($model->updated_at),
            $model->last_name,
            $model->first_name,
            $model->email,
            $model->phone,
            $model->street,
            $model->number,
            $model->box,
            $model->postal_code,
            $model->city,
            $model->country,
            $model->locale,
            $model->privacy_policy_accepted,
        ];
    }

    public function headings(): array
    {
        return [
            'created_at',
            'updated_at',
            'last_name',
            'first_name',
            'email',
            'phone',
            'street',
            'number',
            'box',
            'postal_code',
            'city',
            'country',
            'locale',
            'privacy_policy_accepted',
        ];
    }

    public function columnFormats(): array
    {
        return [
            'A' => NumberFormat::FORMAT_DATE_DATETIME,
            'B' => NumberFormat::FORMAT_DATE_DATETIME,
        ];
    }

    public function collection()
    {
        return $this->collection;
    }
}
