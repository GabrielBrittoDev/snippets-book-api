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
        $skills = ['Java', 'PHP', 'C#',
            'C', 'C++', 'JS',
            'Elixir', 'GO', 'Perk',
            'XML', 'Erlang', 'SQL',
            'Lisp', 'Lua', 'Swift',
            'MatLab', 'Cobol', 'Pascal',
            'Dart', 'Delphi', 'Ruby',
            'Rust', 'Kotlin'];

        foreach ($skills as $skill) {
            DB::table('skills')->insert(['name' => $skill]);
        }
    }
}


