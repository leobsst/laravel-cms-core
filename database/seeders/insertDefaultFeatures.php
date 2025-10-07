<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class insertDefaultFeatures extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $features = [
            [
                'name' => 'pages',
                'value' => false,
            ],
            [
                'name' => 'slides',
                'value' => false,
            ],
            [
                'name' => 'diaporamas',
                'value' => false,
            ],
            [
                'name' => 'menus',
                'value' => false,
            ],
            [
                'name' => 'payments',
                'value' => false,
            ],
            [
                'name' => 'file_explorer',
                'value' => true,
            ],
        ];

        foreach ($features as $feature) {
            DB::table(table: 'features')->updateOrInsert(
                ['name' => $feature['name']],
                [
                    'value' => $feature['value'] ? 'true' : 'false',
                    'scope' => '__laravel_null',
                ]
            );
        }
    }
}
