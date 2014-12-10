<?php

class TestCase extends Illuminate\Foundation\Testing\TestCase
{
    public function setUp()
    {
        parent::setUp();
        $this->prepareDatabase();
    }

	/**
	 * Creates the application.
	 *
	 * @return \Symfony\Component\HttpKernel\HttpKernelInterface
	 */
	public function createApplication()
	{
		$unitTesting = true;
		$testEnvironment = 'testing';

		return require __DIR__.'/../../bootstrap/start.php';
    }

    private function prepareDatabase()
    {
        Artisan::call('migrate');
        Artisan::call('db:seed');
        Mail::pretend(true);
    }

    protected function checkResponse($response, $code, $contentType)
    {
        $this->assertSame($code, $response->getStatusCode());
        $this->assertSame($contentType, $response->headers->get('Content-Type'));
        $this->assertNotEmpty($response->getContent());
    }
}
