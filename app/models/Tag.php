<?php

class Tag extends BaseModel
{
    protected $table = 'post_tags';
    protected $guarded = array('id');


    protected $errors = [
        'same_stored'   => 'vote is same as stored value',
        'reverse'       => 'vote cannot be reversed',
        'lackingpoints' => 'tag cannot be completed as you do not have enough points',
        'anonymous'     => 'tags cannot come from an anon user, please register',
        'systemerror'   => 'general system error occurred',
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
        if($check->updown == Constant::TAG_UP) {
            return ['success'=>false, 'errors'=>array($this->$errors['same_stored'])];
        } else {
            return ['success'=>false, 'errors'=>array($this->$errors['reverse'])];
        }
    }

    private function getColumn($type) {
        switch($type) {
            case Constant::TAG_NSFW: return 'nsfw'; break;
            case Constant::TAG_NSFL: return 'nsfl'; break;
            default: App::abort(500); return;
        }
    }

    public function applyTag($post_id, $type, $updown)
    {
        $post = Post::findOrFail($post_id);

        //upvote/downvote the post itself
        if($updown == Constant::TAG_UP) {
            $post->increment($this->getColumn($type));
        } else if($updown == Constant::TAG_DOWN) {
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
            return $this->alreadyExists($check[0]);
        }

        $user = User::findOrFail(Auth::user()->id);
        
        if($user->points < 1) {
            return ['success' => false, 'errors' => array($this->$errors['lackingpoints'])];
        }

        if($user->anonymous == 1) {
            return ['success' => false, 'errors' => array($this->$errors['anonymous'])];
        }

        try {
            $this->applyTag($post_id, $type, $updown);
        } catch (Exception $e) {
            Log::error($e);
            return ['success' => false, 'errors' => array($this->$errors['systemerror'])];
        }

        return ['success' => true, 'errors' => []];
    }
} 

