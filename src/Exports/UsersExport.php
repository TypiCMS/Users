<?php

namespace TypiCMS\Modules\Users\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;
use TypiCMS\Modules\Core\Filters\FilterOr;
use TypiCMS\Modules\Users\Models\User;

class UsersExport implements ShouldAutoSize, FromCollection, WithHeadings, WithMapping
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

    public function map($user): array
    {
        return [
            $user->created_at,
            $user->last_name,
            $user->first_name,
            $user->email,
            $user->phone,
            $user->street,
            $user->number,
            $user->box,
            $user->postal_code,
            $user->city,
            $user->country,
            $user->locale,
            $user->privacy_policy_accepted,
        ];
    }

    public function headings(): array
    {
        return [
            'created_at',
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

    public function collection()
    {
        return $this->collection;
    }
}
