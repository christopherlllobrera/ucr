<?php

namespace App\Providers;

// use Illuminate\Support\Facades\Gate;

use App\Models\Role;
use App\Models\User;
use App\Models\accrual;
use App\Models\invoice;
use App\Models\draftbill;
use App\Models\collection;
use App\Policies\RolePolicy;
use App\Policies\UserPolicy;
use App\Models\invoicedetails;
use App\Policies\AccrualPolicy;
use App\Policies\InvoicePolicy;
use App\Models\draftbilldetails;
use App\Policies\ActivityPolicy;
use App\Models\collectiondetails;
use App\Policies\DraftbillPolicy;
use App\Policies\CollectionPolicy;
use App\Policies\InvoicedetailsPolicy;
use Spatie\Activitylog\Models\Activity;
use App\Policies\DraftbilldetailsPolicy;
use App\Policies\CollectiondetailsPolicy;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The model to policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        Activity::class => ActivityPolicy::class,
        User::class => UserPolicy::class,
        Role::class => RolePolicy::class,
        accrual::class => AccrualPolicy::class,
        collection::class => CollectionPolicy::class,
        collectiondetails::class => CollectiondetailsPolicy::class,
        draftbill::class => DraftbillPolicy::class,
        draftbilldetails::class => DraftbilldetailsPolicy::class,
        invoice::class => InvoicePolicy::class,
        invoicedetails::class => InvoicedetailsPolicy::class,

    ];

    /**
     * Register any authentication / authorization services.
     */
    public function boot(): void
    {
        //
    }
}
