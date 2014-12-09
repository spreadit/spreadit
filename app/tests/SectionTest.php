<?php

class SectionTest extends TestCase {

	public function testHomepage()
	{
		$crawler = $this->client->request('GET', '/');
        $this->assertTrue($this->client->getResponse()->isOk());
	}

    public function testSectionAll()
    {
        $crawler = $this->client->request('GET', '/s/all');
        $this->assertTrue($this->client->getResponse()->isOk());
    }

    public function testSectionNews()
    {
        $crawler = $this->client->request('GET', '/s/news');
        $this->assertTrue($this->client->getResponse()->isOk());
    }

    public function testSectionNewsNew()
    {
        $crawler = $this->client->request('GET', '/s/news/new');
        $this->assertTrue($this->client->getResponse()->isOk());
    }

    public function testSectionNewsHot()
    {
        $crawler = $this->client->request('GET', '/s/news/hot');
        $this->assertTrue($this->client->getResponse()->isOk());
    }

    public function testSectionNewsTopDay()
    {
        $crawler = $this->client->request('GET', '/s/news/top/day');
        $this->assertTrue($this->client->getResponse()->isOk());
    }

    public function testSectionNewsTopWeek()
    {
        $crawler = $this->client->request('GET', '/s/news/top/week');
        $this->assertTrue($this->client->getResponse()->isOk());
    }

    public function testSectionNewsTopMonth()
    {
        $crawler = $this->client->request('GET', '/s/news/top/month');
        $this->assertTrue($this->client->getResponse()->isOk());
    }

    public function testSectionNewsTopYear()
    {
        $crawler = $this->client->request('GET', '/s/news/top/year');
        $this->assertTrue($this->client->getResponse()->isOk());
    }

    public function testSectionNewsTopForever()
    {
        $crawler = $this->client->request('GET', '/s/news/top/forever');
        $this->assertTrue($this->client->getResponse()->isOk());
    }

    public function testSectionNewsControversialDay()
    {
        $crawler = $this->client->request('GET', '/s/news/controversial/day');
        $this->assertTrue($this->client->getResponse()->isOk());
    }

    public function testSectionNewsControversialWeek()
    {
        $crawler = $this->client->request('GET', '/s/news/controversial/week');
        $this->assertTrue($this->client->getResponse()->isOk());
    }

    public function testSectionNewsControversialMonth()
    {
        $crawler = $this->client->request('GET', '/s/news/controversial/month');
        $this->assertTrue($this->client->getResponse()->isOk());
    }

    public function testSectionNewsControversialYear()
    {
        $crawler = $this->client->request('GET', '/s/news/controversial/year');
        $this->assertTrue($this->client->getResponse()->isOk());
    }

    public function testSectionNewsControversialForever()
    {
        $crawler = $this->client->request('GET', '/s/news/controversial/forever');
        $this->assertTrue($this->client->getResponse()->isOk());
    }

}
