<?php

use Illuminate\Auth\UserInterface;
use Illuminate\Auth\Reminders\RemindableInterface;

class User extends BaseModel implements UserInterface, RemindableInterface {

        /**
         * The database table used by the model.
         *
         * @var string
         */
        protected $table = 'users';


        /**
         * The attributes excluded from the model's JSON form.
         *
         * @var array
         */
        protected $hidden = array('password');


        protected $attributes = array(
            'points' => '10',
            'upvotes' => '0',
            'downvotes' => '0'
        );

        /**
         * Get the unique identifier for the user.
         *
         * @return mixed
         */
        public function getAuthIdentifier()
        {
                return $this->getKey();
        }

        /**
         * Get the password for the user.
         *
         * @return string
         */
        public function getAuthPassword()
        {
                return $this->password;
        }

        public function getRememberToken()
        {
                return $this->remember_token;
        }

        public function setRememberToken($value)
        {
                $this->remember_token = $value;
        }

        public function getRememberTokenName()
        {
                return 'remember_token';
        }

        /**
         * Get the e-mail address where password reminders are sent.
         *
         * @return string
         */
        public function getReminderEmail()
        {
                return $this->email;
        }
}
