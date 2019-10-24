<?php

use Laravel\Lumen\Testing\DatabaseMigrations;
use Laravel\Lumen\Testing\DatabaseTransactions;

class ApiBasicTest extends TestCase
{
    /**
     * An API basic test.
     *
     * @return void
     */
    public function testApiState()
    {
        $this->get('/');

        $response = json_decode($this->response->getContent(), false);

        $this->assertEquals(200, $response->status);
    }

    /**
     * An API factories list test.
     *
     * @return void
     */
    public function testApiFactoriesListNoAuth()
    {
        $this->get('/api/factories');

        $response = json_decode($this->response->getContent(), false);

        $this->assertEquals('Authorization header not found', $response->message);
    }

    /**
     * An API factories list test.
     *
     * @return void
     */
    public function testApiFactoriesListWithAuthBad()
    {
        $this->get('/api/factories', ['Authorization' => 'Test-value']);

        $response = json_decode($this->response->getContent(), false);

        $this->assertEquals('Wrong authorization', $response->message);
    }

    /**
     * An API factories list test.
     *
     * @return void
     */
    public function testApiFactoriesListWithAuthGood()
    {
        $this->get('/api/factories', ['Authorization' => 'cf23df2207d99a74fbe169e3eba035e633b65d94']);

        $response = json_decode($this->response->getContent(), false);

        $this->assertEquals('Factories tree was retrieved successfuly', $response->message);
    }
}
