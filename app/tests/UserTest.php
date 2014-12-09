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
        $response = $this->client->getResponse();
        $this->assertSame(200, $response->getStatusCode());
        $this->assertSame('text/html; charset=UTF-8', $response->headers->get('Content-Type'));
        $this->assertNotEmpty($response->getContent());
    }

    public function testCommentsHtml()
    {
        $this->client->request('GET', '/u/user/comments');
        $response = $this->client->getResponse();
        $this->assertSame(200, $response->getStatusCode());
        $this->assertSame('text/html; charset=UTF-8', $response->headers->get('Content-Type'));
        $this->assertNotEmpty($response->getContent());
    }

    public function testVoteCommentsHtml()
    {
        $this->client->request('GET', '/u/user/votes/comments');
        $response = $this->client->getResponse();
        $this->assertSame(200, $response->getStatusCode());
        $this->assertSame('text/html; charset=UTF-8', $response->headers->get('Content-Type'));
        $this->assertNotEmpty($response->getContent());
    }

    public function testVoteCommentsJson()
    {
        $this->client->request('GET', '/u/user/votes/comments/.json');
        $response = $this->client->getResponse();
        $this->assertSame(200, $response->getStatusCode());
        $this->assertSame('application/json', $response->headers->get('Content-Type'));
        $this->assertNotEmpty($response->getContent());
    }

    public function testPostsHtml()
    {
        $this->client->request('GET', '/u/user/posts');
        $response = $this->client->getResponse();
        $this->assertSame(200, $response->getStatusCode());
        $this->assertSame('text/html; charset=UTF-8', $response->headers->get('Content-Type'));
        $this->assertNotEmpty($response->getContent());
    }

    public function testVotePostsHtml()
    {
        $this->client->request('GET', '/u/user/votes/posts');
        $response = $this->client->getResponse();
        $this->assertSame(200, $response->getStatusCode());
        $this->assertSame('text/html; charset=UTF-8', $response->headers->get('Content-Type'));
        $this->assertNotEmpty($response->getContent());
    }

    public function testVotePostsJson()
    {
        $this->client->request('GET', '/u/user/votes/posts/.json');
        $response = $this->client->getResponse();
        $this->assertSame(200, $response->getStatusCode());
        $this->assertSame('application/json', $response->headers->get('Content-Type'));
        $this->assertNotEmpty($response->getContent());
    }
}