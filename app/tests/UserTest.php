<?php

class UserTest extends TestCase
{
    /* TODO
     *
     * login, logout, register, notifications, notifications.json
     *
     */

    public function testVoteIndexHtml()
    {
        $this->client->request('GET', '/u/user');
        $this->checkResponse($this->client->getResponse(), 200, 'text/html; charset=UTF-8');
    }

    public function testCommentsHtml()
    {
        $this->client->request('GET', '/u/user/comments');
        $this->checkResponse($this->client->getResponse(), 200, 'text/html; charset=UTF-8');
    }

    public function testVoteCommentsHtml()
    {
        $this->client->request('GET', '/u/user/votes/comments');
        $this->checkResponse($this->client->getResponse(), 200, 'text/html; charset=UTF-8');
    }

    public function testVoteCommentsJson()
    {
        $this->client->request('GET', '/u/user/votes/comments/.json');
        $this->checkResponse($this->client->getResponse(), 200, 'application/json');
    }

    public function testPostsHtml()
    {
        $this->client->request('GET', '/u/user/posts');
        $this->checkResponse($this->client->getResponse(), 200, 'text/html; charset=UTF-8');
    }

    public function testVotePostsHtml()
    {
        $this->client->request('GET', '/u/user/votes/posts');
        $this->checkResponse($this->client->getResponse(), 200, 'text/html; charset=UTF-8');
    }

    public function testVotePostsJson()
    {
        $this->client->request('GET', '/u/user/votes/posts/.json');
        $this->checkResponse($this->client->getResponse(), 200, 'application/json');
    }
}