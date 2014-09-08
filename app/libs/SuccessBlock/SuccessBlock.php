<?php

class SuccessBlock
{
    public $success;
    public $errors;
    public $data;

    public function __construct()
    {
        $this->success = true;
        $this->errors = array();
        $this->data = new stdClass;
    }
}
