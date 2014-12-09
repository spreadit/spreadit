<?php

class FeedTest extends TestCase
{
    public function testHomepageRss()
    {
        $this->client->request('GET', '/.rss');
        $response = $this->client->getResponse();
        $this->assertSame(200, $response->getStatusCode());
        $this->assertSame('application/rss+xml; charset=utf-8', $response->headers->get('Content-Type'));
        $this->assertNotEmpty($response->getContent());
    }

    public function testSectionAllRss()
    {
        $this->client->request('GET', '/s/all/.rss');
        $response = $this->client->getResponse();
        $this->assertSame(200, $response->getStatusCode());
        $this->assertSame('application/rss+xml; charset=utf-8', $response->headers->get('Content-Type'));
        $this->assertNotEmpty($response->getContent());
    }

    public function testSectionNewsRss()
    {
        $this->client->request('GET', '/s/news/.rss');
        $response = $this->client->getResponse();
        $this->assertSame(200, $response->getStatusCode());
        $this->assertSame('application/rss+xml; charset=utf-8', $response->headers->get('Content-Type'));
        $this->assertNotEmpty($response->getContent());
    }

    public function testHomepageAtom()
    {
        $this->client->request('GET', '/.atom');
        $response = $this->client->getResponse();
        $this->assertSame(200, $response->getStatusCode());
        $this->assertSame('application/atom+xml; charset=utf-8', $response->headers->get('Content-Type'));
        $this->assertNotEmpty($response->getContent());
    }

    public function testSectionAllAtom()
    {
        $this->client->request('GET', '/s/all/.atom');
        $response = $this->client->getResponse();
        $this->assertSame(200, $response->getStatusCode());
        $this->assertSame('application/atom+xml; charset=utf-8', $response->headers->get('Content-Type'));
        $this->assertNotEmpty($response->getContent());
    }

    public function testSectionNewsAtom()
    {
        $this->client->request('GET', '/s/news/.atom');
        $response = $this->client->getResponse();
        $this->assertSame(200, $response->getStatusCode());
        $this->assertSame('application/atom+xml; charset=utf-8', $response->headers->get('Content-Type'));
        $this->assertNotEmpty($response->getContent());
    }
}