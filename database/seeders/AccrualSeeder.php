<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\LazyCollection;
use Illuminate\Database\Seeder;

class AccrualSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::disableQueryLog();
        LazyCollection::make(function () {
            $file = fopen(public_path('CE_UCR_1_1_1.csv'), 'r');
            while (($line = fgetcsv($file, 4096)) !== false) {
                $dataString = implode(',', $line);
                $row = explode(',', $dataString);
                yield $row;
            }
            fclose($file);
        })
            ->chunk(1000)
            ->each(function ($lines) {
                DB::table('accruals')->insert($lines->map(function ($line) {
                    return [
                        'id' => $line[0],
                        'ucr_ref_id' => $line[1],
                        'client_name' => $line[2],
                        'date_accrued' => $line[3],
                        'UCR_Park_Doc' => $line[4],
                        'contract_type' => $line[5],
                        'person_in_charge' => $line[6],
                        'business_unit' => $line[7],
                        'particulars' => $line[8],
                        'period_started' => $line[9],
                        'period_ended' => $line[10],
                        'wbs_no' => $line[11],
                        'month' => $line[12],
                        'accrual_amount' => $line[13],
                        'accruals_attachment' => $line[14],
                        'created_at' => now(),
                        'updated_at' => now(),
                    ];
                })->toArray());
            });
    }
}
