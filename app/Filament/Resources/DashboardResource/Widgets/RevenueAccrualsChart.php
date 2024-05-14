<?php

namespace App\Filament\Resources\DashboardResource\Widgets;

use Leandrocfe\FilamentApexCharts\Widgets\ApexChartWidget;

class RevenueAccrualsChart extends ApexChartWidget
{
    /**
     * Chart Id
     *
     * @var string
     */
    protected static ?string $chartId = 'revenueAccrualsChart';

    /**
     * Widget Title
     *
     * @var string|null
     */
    protected static ?string $heading = 'RevenueAccrualsChart';

    /**
     * Chart options (series, labels, types, size, animations...)
     * https://apexcharts.com/docs/options
     *
     * @return array
     */
    protected function getOptions(): array
    {
        return [
            'chart' => [
                'type' => 'bar',
                'height' => 300,
            ],
            'series' => [
                [
                    'name' => 'Accruals Amount',
                    'data' => [ '0M', '50M', '100M'],
                ],
            ],
            'xaxis' => [
                'categories' => ['Meralco', 'MIESCOR', 'MWCI', 'Radius Telecom Inc.', 'MSPECTRUM', 'UPPC', 'NLEX Corporation',
                                'MAYNILAD', 'SM Retail Inc' , 'MSERV' , 'MBI' , 'Atimonan One Energy Inc',
                                'Blue Leaf Energy Services Philippines Inc', 'TEH HSIN Enterprise Philippines Corporation',
                                'Mr. Josep Eunice Mundo', 'Global Business Power Corporation', 'Power Source First Bulacan Solar , Inc' ,
                                'PH Renewables Inc.' , 'MOVEM' , 'Texicon Agri Ventures Corporation' , 'TAKASAGO International (Philippines) Inc.',
                                'MVP', 'Shin Clark Power Corporation' , 'Clark Electric Distribution Corporation', 'M Pioneer Insurance Inc.' ,
                                'ABOITIZ POWER', 'LG Electronics Phil' , 'CIS Bayad Center' , 'MIDC', 'PDRF', 'OCEANA', 'MSERV', 'Other Client',
                                'SLMC'],
                'labels' => [
                    'style' => [
                        'fontFamily' => 'inherit',
                    ],
                ],
            ],
            'yaxis' => [
                'labels' => [
                    'style' => [
                        'fontFamily' => 'inherit',
                    ],
                ],
            ],
            'colors' => ['#f59e0b'],
            'plotOptions' => [
                'bar' => [
                    'borderRadius' => 3,
                    'horizontal' => true,
                ],
            ],
        ];
    }
}
