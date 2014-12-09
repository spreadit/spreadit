<?php

class PostsTableSeeder extends Seeder {

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = Faker\Factory::create();
                
        for($i=0; $i<100; $i++) {
            $time = time() - rand(0, 60*60*24*30);
            Post::create([
                'section_id' => rand(2, 10),
                'user_id' => rand(1, 100),
                'created_at' => $time,
                'updated_at' => $time,
                'url' => rand(0, 1) == 0 ? $faker->url : '',
                'title' => $faker->sentence,
                'data' => rand(0, 1) == 0 ? $faker->paragraph : '',
            ]);
        }
    }
}
