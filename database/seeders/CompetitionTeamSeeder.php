<?php

namespace Database\Seeders;

use App\Models\CompetitionTeam;
use Illuminate\Database\Seeder;

class CompetitionTeamSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        CompetitionTeam::create(['name' => 'Nijlen A']);
        CompetitionTeam::create(['name' => 'Nijlen B']);
        CompetitionTeam::create(['name' => 'Nijlen C']);
        CompetitionTeam::create(['name' => 'Nijlen D']);
        CompetitionTeam::create(['name' => 'Nijlen E']);
        CompetitionTeam::create(['name' => 'Bevel A']);
        CompetitionTeam::create(['name' => 'Bevel B']);
        CompetitionTeam::create(['name' => 'Bevel C']);
        CompetitionTeam::create(['name' => 'Bevel D']);
        CompetitionTeam::create(['name' => 'Bevel E']);
        CompetitionTeam::create(['name' => 'Bevel F']);
    }
}
