<?php

namespace Database\Seeders;

use App\Models\CompetitionOrganisation;
use Illuminate\Database\Seeder;

class CompetitionOrganisationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        CompetitionOrganisation::create(['name' => 'vttl']);
        CompetitionOrganisation::create(['name' => 'sporta']);
    }
}
