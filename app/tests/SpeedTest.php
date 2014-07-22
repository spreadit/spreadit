<?php

class SpeedTest extends TestCase
{
	public function testHome()
	{
		$logger = new Profiler\Logger\Logger;
		$profiler = new Profiler\Profiler($logger);
		$profiler->startTimer('testLogging');

		$crawler = $this->client->request('GET', '/');
		$this->assertTrue($this->client->getResponse()->isOk());
		$logger->warning('lalal');

		$profiler->endTimer('testLogging');
	}

	//todo : test all other page times.. ensure they meet acceptable rendering times
}
