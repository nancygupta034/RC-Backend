<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TrubillingInsuranceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $insurances = [
            [
                'name' => 'KS AETNA',
                'insurance_id' => '5',
                'location' => 'KS',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'name' => 'KS AMBETTER',
                'insurance_id' => '7',
                'location' => 'KS',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'name' => 'KS Cenpatico Behavioral Health',
                'insurance_id' => '8',
                'location' => 'KS',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'name' => 'KS Medicare Part B',
                'insurance_id' => '9',
                'location' => 'KS',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'name' => 'KS United Health Care',
                'insurance_id' => '10',
                'location' => 'KS',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'name' => 'KS Golden Rule Insurance Company',
                'insurance_id' => '14',
                'location' => 'KS',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'name' => 'KS UMR - Wausau',
                'insurance_id' => '15',
                'location' => 'KS',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'name' => 'KS UnitedHealthcare Community Plan KanCare',
                'insurance_id' => '16',
                'location' => 'KS',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'name' => 'Aetna Better Health of Kansas',
                'insurance_id' => '17',
                'location' => 'KS',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'name' => 'KS CIGNA',
                'insurance_id' => '18',
                'location' => 'KS',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'name' => 'KS Medicaid',
                'insurance_id' => '19',
                'location' => 'KS',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'name' => 'KC BCBS',
                'insurance_id' => '6',
                'location' => 'KS',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'name' => 'KS Oxford Health insurances',
                'insurance_id' => '12',
                'location' => 'KS',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'name' => 'KS Medica',
                'insurance_id' => '20',
                'location' => 'KS',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'name' => 'Humana',
                'insurance_id' => '66',
                'location' => 'KS',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'name' => 'Tricare West',
                'insurance_id' => '67',
                'location' => 'KS',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'name' => 'Magellan Health Services',
                'insurance_id' => '70',
                'location' => 'KS',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'name' => 'GEHA',
                'insurance_id' => '71',
                'location' => 'KS',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'name' => 'Mutual of Omaha',
                'insurance_id' => '72',
                'location' => 'KS',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'name' => 'Oscar',
                'insurance_id' => '73',
                'location' => 'KS',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'name' => 'Tricare',
                'insurance_id' => '74',
                'location' => 'KS',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'name' => 'First Health insurances',
                'insurance_id' => '75',
                'location' => 'KS',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'name' => 'Optum VACCN',
                'insurance_id' => '76',
                'location' => 'KS',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'name' => 'MO HealthNet',
                'insurance_id' => '77',
                'location' => 'KS',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'name' => 'Cerner',
                'insurance_id' => '78',
                'location' => 'KS',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'name' => 'coventry',
                'insurance_id' => '79',
                'location' => 'KS',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'name' => 'Great West (Value Options)',
                'insurance_id' => '80',
                'location' => 'KS',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],

            [
                'name' => 'Railroad Medicare',
                'insurance_id' => '81',
                'location' => 'KS',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'name' => 'Healthy Blue Missouri',
                'insurance_id' => '82',
                'location' => 'KS',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'name' => 'KS Beacon Health Strategies',
                'insurance_id' => '83',
                'location' => 'KS',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'name' => 'Healthcare Preferred',
                'insurance_id' => '84',
                'location' => 'KS',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'name' => 'ProviDRs Care Network',
                'insurance_id' => '86',
                'location' => 'KS',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'name' => 'Wellcare',
                'insurance_id' => '87',
                'location' => 'KS',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'name' => 'PHCS',
                'insurance_id' => '94',
                'location' => 'KS',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],


        ];

        DB::table('tru_billing_insurances')->insert($insurances);
    }
}
