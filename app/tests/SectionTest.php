<?php

class SectionTest extends TestCase
{

    public function testHomepageHtml()
    {
        $this->client->request('GET', '/');
        $this->checkResponse($this->client->getResponse(), 200, 'text/html; charset=UTF-8');
    }

    public function testSectionAllHtml()
    {
        $this->client->request('GET', '/s/all');
        $this->checkResponse($this->client->getResponse(), 200, 'text/html; charset=UTF-8');
    }

    public function testSectionNewsHtml()
    {
        $this->client->request('GET', '/s/news');
        $this->checkResponse($this->client->getResponse(), 200, 'text/html; charset=UTF-8');
    }

    public function testSectionNewsNewHtml()
    {
        $this->client->request('GET', '/s/news/new');
        $this->checkResponse($this->client->getResponse(), 200, 'text/html; charset=UTF-8');
    }

    public function testSectionNewsHotHtml()
    {
        $this->client->request('GET', '/s/news/hot');
        $this->checkResponse($this->client->getResponse(), 200, 'text/html; charset=UTF-8');
    }

    public function testSectionNewsTopDayHtml()
    {
        $this->client->request('GET', '/s/news/top/day');
        $this->checkResponse($this->client->getResponse(), 200, 'text/html; charset=UTF-8');
    }

    public function testSectionNewsTopWeekHtml()
    {
        $this->client->request('GET', '/s/news/top/week');
        $this->checkResponse($this->client->getResponse(), 200, 'text/html; charset=UTF-8');
    }

    public function testSectionNewsTopMonthHtml()
    {
        $this->client->request('GET', '/s/news/top/month');
        $this->checkResponse($this->client->getResponse(), 200, 'text/html; charset=UTF-8');
    }

    public function testSectionNewsTopYearHtml()
    {
        $this->client->request('GET', '/s/news/top/year');
        $this->checkResponse($this->client->getResponse(), 200, 'text/html; charset=UTF-8');
    }

    public function testSectionNewsTopForeverHtml()
    {
        $this->client->request('GET', '/s/news/top/forever');
        $this->checkResponse($this->client->getResponse(), 200, 'text/html; charset=UTF-8');
    }

    public function testSectionNewsControversialDayHtml()
    {
        $this->client->request('GET', '/s/news/controversial/day');
        $this->checkResponse($this->client->getResponse(), 200, 'text/html; charset=UTF-8');
    }

    public function testSectionNewsControversialWeekHtml()
    {
        $this->client->request('GET', '/s/news/controversial/week');
        $this->checkResponse($this->client->getResponse(), 200, 'text/html; charset=UTF-8');
    }

    public function testSectionNewsControversialMonthHtml()
    {
        $this->client->request('GET', '/s/news/controversial/month');
        $this->checkResponse($this->client->getResponse(), 200, 'text/html; charset=UTF-8');
    }

    public function testSectionNewsControversialYearHtml()
    {
        $this->client->request('GET', '/s/news/controversial/year');
        $this->checkResponse($this->client->getResponse(), 200, 'text/html; charset=UTF-8');
    }

    public function testSectionNewsControversialForeverHtml()
    {
        $this->client->request('GET', '/s/news/controversial/forever');
        $this->checkResponse($this->client->getResponse(), 200, 'text/html; charset=UTF-8');
    }

    public function testHomepageJson()
    {
        $this->client->request('GET', '/.json');
        $response = $this->client->getResponse();
        $this->assertSame(200, $response->getStatusCode());
        $this->assertSame('application/json', $response->headers->get('Content-Type'));
        $this->assertNotEmpty($response->getContent());
    }

    public function testSectionAllJson()
    {
        $this->client->request('GET', '/s/all/.json');
        $response = $this->client->getResponse();
        $this->assertSame(200, $response->getStatusCode());
        $this->assertSame('application/json', $response->headers->get('Content-Type'));
        $this->assertNotEmpty($response->getContent());
    }

    public function testSectionNewsJson()
    {
        $this->client->request('GET', '/s/news/.json');
        $response = $this->client->getResponse();
        $this->assertSame(200, $response->getStatusCode());
        $this->assertSame('application/json', $response->headers->get('Content-Type'));
        $this->assertNotEmpty($response->getContent());
    }

    public function testSectionNewsNewJson()
    {
        $this->client->request('GET', '/s/news/new/.json');
        $response = $this->client->getResponse();
        $this->assertSame(200, $response->getStatusCode());
        $this->assertSame('application/json', $response->headers->get('Content-Type'));
        $this->assertNotEmpty($response->getContent());
    }

    public function testSectionNewsHotJson()
    {
        $this->client->request('GET', '/s/news/hot/.json');
        $response = $this->client->getResponse();
        $this->assertSame(200, $response->getStatusCode());
        $this->assertSame('application/json', $response->headers->get('Content-Type'));
        $this->assertNotEmpty($response->getContent());
    }

    public function testSectionNewsTopDayJson()
    {
        $this->client->request('GET', '/s/news/top/day/.json');
        $response = $this->client->getResponse();
        $this->assertSame(200, $response->getStatusCode());
        $this->assertSame('application/json', $response->headers->get('Content-Type'));
        $this->assertNotEmpty($response->getContent());
    }

    public function testSectionNewsTopWeekJson()
    {
        $this->client->request('GET', '/s/news/top/week/.json');
        $response = $this->client->getResponse();
        $this->assertSame(200, $response->getStatusCode());
        $this->assertSame('application/json', $response->headers->get('Content-Type'));
        $this->assertNotEmpty($response->getContent());
    }

    public function testSectionNewsTopMonthJson()
    {
        $this->client->request('GET', '/s/news/top/month/.json');
        $response = $this->client->getResponse();
        $this->assertSame(200, $response->getStatusCode());
        $this->assertSame('application/json', $response->headers->get('Content-Type'));
        $this->assertNotEmpty($response->getContent());
    }

    public function testSectionNewsTopYearJson()
    {
        $this->client->request('GET', '/s/news/top/year/.json');
        $response = $this->client->getResponse();
        $this->assertSame(200, $response->getStatusCode());
        $this->assertSame('application/json', $response->headers->get('Content-Type'));
        $this->assertNotEmpty($response->getContent());
    }

    public function testSectionNewsTopForeverJson()
    {
        $this->client->request('GET', '/s/news/top/forever/.json');
        $response = $this->client->getResponse();
        $this->assertSame(200, $response->getStatusCode());
        $this->assertSame('application/json', $response->headers->get('Content-Type'));
        $this->assertNotEmpty($response->getContent());
    }

    public function testSectionNewsControversialDayJson()
    {
        $this->client->request('GET', '/s/news/controversial/day/.json');
        $response = $this->client->getResponse();
        $this->assertSame(200, $response->getStatusCode());
        $this->assertSame('application/json', $response->headers->get('Content-Type'));
        $this->assertNotEmpty($response->getContent());
    }

    public function testSectionNewsControversialWeekJson()
    {
        $this->client->request('GET', '/s/news/controversial/week/.json');
        $response = $this->client->getResponse();
        $this->assertSame(200, $response->getStatusCode());
        $this->assertSame('application/json', $response->headers->get('Content-Type'));
        $this->assertNotEmpty($response->getContent());
    }

    public function testSectionNewsControversialMonthJson()
    {
        $this->client->request('GET', '/s/news/controversial/month/.json');
        $response = $this->client->getResponse();
        $this->assertSame(200, $response->getStatusCode());
        $this->assertSame('application/json', $response->headers->get('Content-Type'));
        $this->assertNotEmpty($response->getContent());
    }

    public function testSectionNewsControversialYearJson()
    {
        $this->client->request('GET', '/s/news/controversial/year/.json');
        $response = $this->client->getResponse();
        $this->assertSame(200, $response->getStatusCode());
        $this->assertSame('application/json', $response->headers->get('Content-Type'));
        $this->assertNotEmpty($response->getContent());
    }

    public function testSectionNewsControversialForeverJson()
    {
        $this->client->request('GET', '/s/news/controversial/forever/.json');
        $response = $this->client->getResponse();
        $this->assertSame(200, $response->getStatusCode());
        $this->assertSame('application/json', $response->headers->get('Content-Type'));
        $this->assertNotEmpty($response->getContent());
    }
}
