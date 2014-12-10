<?php

class CommentsTableSeeder extends Seeder {

    protected $faker;

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->faker = Faker\Factory::create();
                
        for($i=0; $i<10; $i++) {
            $time = time() - rand(10000, 60*60*24*30);
            $this->tree(0, $i+1, 0, $time);
        }
    }

    protected function tree($depth, $post_id, $parent_id, $time)
    {
        if(rand(0, 20) < $depth + 1)  return;

        while(rand(0, 1) != 1) {
            $time = $time + rand(0, 100);

            $comment = Comment::create([
                'post_id'      => $post_id,
                'parent_id'    => $parent_id,
                'user_id'      => rand(1, 100),
                'created_at'   => $time,
                'updated_at'   => $time,
                'data'         => $this->faker->paragraph,
                'markdown'     => '',
                'upvotes'      => 0,
                'downvotes'    => 0,
            ]);

            $this->tree($depth + 1, $post_id, $comment->id, $time + rand(0, 1000));
        }
    }
}
