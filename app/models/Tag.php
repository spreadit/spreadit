<?php

class Tag extends BaseModel
{
    protected $table = 'post_tags';
    protected $guarded = array('id');

    const UP = 1;
    const DOWN = -1;
    const NSFW = 0;
    const NSFL = 1;

    protected static $errors = [
        'same_stored' => 'vote is same as stored value',
        'reverse' => 'vote cannot be reversed',
        'lackingpoints' => 'tag cannot be completed as you do not have enough points',
        'anonymous' => 'tags cannot come from an anon user, please register',
        'systemerror' => 'general system error occurred',
    ];

    protected function checkTag($post_id)
    {
        return DB::table('post_tags')
            ->select('updown')
            ->where('post_id', '=', $post_id)
            ->where('user_id', '=', Auth::user()->id)
            ->orderBy('id', 'asc')
            ->get();
    }

    protected function alreadyExists($check)
    {
        $check = $check[0];
        if($check->updown == self::UP) {
            return ['success'=>false, 'errors'=>array(self::$errors['same_stored'])];
        } else {
            return ['success'=>false, 'errors'=>array(self::$errors['reverse'])];
        }
    }

    private function getColumn($type) {
        switch($type) {
            case self::NSFW: return 'nsfw'; break;
            case self::NSFL: return 'nsfl'; break;
            default: App::abort(500); return;
        }
    }

    public function applyTag($post_id, $type, $updown)
    {
        $post = Post::findOrFail($post_id);

        //upvote/downvote the post itself
        if($updown == self::UP) {
            $post->increment($this->getColumn($type));
        } else if($updown == self::DOWN) {
            $post->decrement($this->getColumn($type));
        }

        //deal with votes table
        $tag = new Tag(array(
            'type'    => $type,
            'user_id' => Auth::user()->id,
            'post_id' => $post_id,
            'updown'  => $updown
        ));

        $tag->save();
    }

    protected function action($post_id, $type, $updown)
    {
        $check = $this->checkTag($post_id);

        if(count($check) > 0) {
            return self::alreadyExists($check);
        }

        $user = User::findOrFail(Auth::user()->id);
        
        if($user->points < 1) {
            return ['success' => false, 'errors' => array(self::$errors['lackingpoints'])];
        }

        if($user->anonymous == 1) {
            return ['success' => false, 'errors' => array(self::$errors['anonymous'])];
        }

        try {
            $this->applyTag($post_id, $type, $updown);
        } catch (Exception $e) {
            Log::error($e);
            return ['success' => false, 'errors' => array(self::$errors['systemerror'])];
        }

        return ['success' => true, 'errors' => []];
    }
} 

