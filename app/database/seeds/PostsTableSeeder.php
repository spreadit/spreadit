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
                
        for($i=0; $i<10; $i++) {
            $time = time() - rand(0, 60*60*24*30);
            $type = rand(0, 1) == 0;
            Post::create([
                'section_id' => rand(2, 10),
                'user_id'       => rand(1, 100),
                'created_at'    => $time,
                'updated_at'    => $time,
                'url'           => $type ? '' : $faker->url,
                'title'         => $faker->sentence,
                'data'          => rand(0, 1) == 0 ? $faker->paragraph : '',
                'markdown'      => '',
                'type'          => $type,
                'upvotes'       => 0,
                'downvotes'     => 0,
                'comment_count' => 0,
                'thumbnail'     => '',
                'nsfw'          => 0,
                'nsfl'          => 0,
            ]);
        }
    }
}
