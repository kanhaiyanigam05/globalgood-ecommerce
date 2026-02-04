<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Country;
use App\Models\TaxSetting;
use App\Models\TaxOverride;

class TaxSettingsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $india = Country::where('code', 'in')->first();
        if ($india) {
            // Base Tax (GST 9%?)
            TaxSetting::updateOrCreate(
                ['country_id' => $india->id],
                ['tax_rate' => 9.00, 'tax_name' => 'GST', 'is_active' => true]
            );

            // Some Regional Overrides
            $overrides = [
                'Bihar' => ['rate' => 18.00, 'name' => 'IGST', 'type' => 'instead'],
                'Maharashtra' => ['rate' => 18.00, 'name' => 'IGST', 'type' => 'instead'],
                'Delhi' => ['rate' => 18.00, 'name' => 'IGST', 'type' => 'instead'],
            ];

            foreach ($overrides as $zoneName => $data) {
                $zone = $india->zones()->where('name', $zoneName)->first();
                if ($zone) {
                    TaxOverride::updateOrCreate(
                        ['country_id' => $india->id, 'country_zone_id' => $zone->id],
                        ['tax_rate' => $data['rate'], 'tax_name' => $data['name'], 'tax_type' => $data['type']]
                    );
                }
            }
        }
    }
}
