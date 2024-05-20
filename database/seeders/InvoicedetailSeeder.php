<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\LazyCollection;

class InvoicedetailSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::disableQueryLog();
        LazyCollection::make(function () {
            $file = fopen(public_path('invoice_details.csv'), 'r');
            while (($line = fgetcsv($file, 4096)) !== false) {
                $dataString = implode(',', $line);
                $row = explode(',', $dataString);
                yield $row;
            }
            fclose($file);
        })
            ->chunk(1000)
            ->each(function ($lines) {
                DB::table('invoicedetails')->insert($lines->map(function ($line) {
                    return [
                        'id' => $line[0],
                        'reversal_doc' => $line[1],
                        'gr_amount' => $line[2],
                        'date_reversal' => $line[3],
                        'accounting_doc' => $line[4],
                        'invoice_date_received' => $line[5],
                        'pojo_no' => $line[6],
                        'gr_no_meralco' => $line[7],
                        'billing_statement' => $line[8],
                        'invoice_date_approved' => $line[9],
                        'invoice_posting_date' => $line[10],
                        'invoice_posting_amount' => $line[11],
                        'invoice_date_forwarded' => $line[12],
                        'invoice_attachment' => $line[13],
                        'created_at' => now(),
                        'updated_at' => now(),
                    ];
                })->toArray());
            });
    }
}
