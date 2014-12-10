<?php

class FeedTest extends TestCase
{
    public function testHomepageRss()
    {
        $this->client->request('GET', '/.rss');
        $this->checkResponse($this->client->getResponse(), 200, 'application/rss+xml; charset=utf-8');
    }

    public function testSectionAllRss()
    {
        $this->client->request('GET', '/s/all/.rss');
        $this->checkResponse($this->client->getResponse(), 200, 'application/rss+xml; charset=utf-8');
    }

    public function testSectionNewsRss()
    {
        $this->client->request('GET', '/s/news/.rss');
        $this->checkResponse($this->client->getResponse(), 200, 'application/rss+xml; charset=utf-8');
    }

    public function testHomepageAtom()
    {
        $this->client->request('GET', '/.atom');
        $this->checkResponse($this->client->getResponse(), 200, 'application/atom+xml; charset=utf-8');
    }

    public function testSectionAllAtom()
    {
        $this->client->request('GET', '/s/all/.atom');
        $this->checkResponse($this->client->getResponse(), 200, 'application/atom+xml; charset=utf-8');
    }

    public function testSectionNewsAtom()
    {
        $this->client->request('GET', '/s/news/.atom');
        $this->checkResponse($this->client->getResponse(), 200, 'application/atom+xml; charset=utf-8');
    }
}