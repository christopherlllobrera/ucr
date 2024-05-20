<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\LazyCollection;

class DraftbilldetailSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::disableQueryLog();
        LazyCollection::make(function () {
            $file = fopen(public_path('draftbilldetails.csv'), 'r');
            while (($line = fgetcsv($file, 4096)) !== false) {
                $dataString = implode(',', $line);
                $row = explode(',', $dataString);
                yield $row;
            }
            fclose($file);
        })
            ->chunk(1000)
            ->each(function ($lines) {
                DB::table('draftbilldetails')->insert($lines->map(function ($line) {
                    return [
                        'id' => $line[0],
                        'draftbill_no' => $line[1],
                        'draftbill_particular' => $line[2],
                        'bill_date_created' => $line[3],
                        'draftbill_amount' => $line[4],
                        'bill_date_submitted' => $line[5],
                        'bill_date_approved' => $line[6],
                        'bill_attachment' => $line[3],
                        'created_at' => now(),
                        'updated_at' => now(),
                    ];
                })->toArray());
            });
    }
}
