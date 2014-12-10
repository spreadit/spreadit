<?php

class SectionTableSeeder extends Seeder {

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Section::create(['title' => 'all', 'data'=>'', 'markdown' => '']);
        $statement = "UPDATE  `sections` SET  `id` =  '0' WHERE  `sections`.`id` =1;";
        DB::unprepared($statement);
        Section::create(['title' => 'news', 'data'=>'', 'markdown' => '']);
        Section::create(['title' => 'science', 'data'=>'', 'markdown' => '']);
        Section::create(['title' => 'tech', 'data'=>'', 'markdown' => '']);
        Section::create(['title' => 'politics', 'data'=>'', 'markdown' => '']);
        Section::create(['title' => 'frogs', 'data'=>'', 'markdown' => '']);
        Section::create(['title' => 'econ', 'data'=>'', 'markdown' => '']);
        Section::create(['title' => 'math', 'data'=>'', 'markdown' => '']);
        Section::create(['title' => 'prog', 'data'=>'', 'markdown' => '']);
        Section::create(['title' => 'worms', 'data'=>'', 'markdown' => '']);
    }
}
