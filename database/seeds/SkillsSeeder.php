<?php

use Illuminate\Database\Seeder;

class SkillsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $skills = [[
            'name' => 'Java'],
            ['name' => 'PHP'],
            ['name' => 'C#'],
            ['name' => 'C'],
            ['name' => 'C++'],
            ['name' => 'JS'],
            ['name' => 'Elixir'],
            ['name' => 'GO'],
        ];

        foreach ($skills as $skill) {
            DB::table('skills')->insert($skill);
        }
    }
}
