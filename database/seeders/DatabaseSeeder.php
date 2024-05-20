<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Hash;
use Database\Seeders\DraftbillSeeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {

        $this->call(AccrualSeeder::class);
        $this->call(DraftbillSeeder::class);
        $this->call(DraftbilldetailSeeder::class);
        $this->call(DraftbillrelationSeeder::class);
        $this->call(InvoiceSeeder::class);
        $this->call(InvoicedetailSeeder::class);
        $this->call(InvoicerelationSeeder::class);
        $this->call(PermissionSeeder::class);
    }
}
