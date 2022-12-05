<?php

namespace Tests\Unit;

use App\Services\NYTService;
use Mockery\MockInterface;
use ReflectionClass;
use ReflectionException;
use ReflectionMethod;
use Tests\TestCase;

class NYTServiceTest extends TestCase
{
    protected MockInterface $nyt;
    protected string $fakeApiKey;

    /**
     * Basic setup for the tests. Partial mock for everything that connects
     * to the NYT, as we only want to test the query string generation functionality.
     */
    protected function setUp(): void
    {
        parent::setUp();
        $this->fakeApiKey = '12345';

        // mock fetchBestSellers and API key, we don't want or need to actually hit the NYT API
        $this->nyt = $this->partialMock(NYTService::class, function (MockInterface $mock) {
            $mock->shouldReceive('fetchBestSellers');

            $mock->shouldAllowMockingProtectedMethods();
            $mock->shouldReceive('getApiKey')->andReturn($this->fakeApiKey);
        });
    }

    /**
     * Helper function using reflection to get the protected method we want to test.
     *
     * @param string $name name of the method to be tested
     * @return ReflectionMethod
     * @throws ReflectionException
     */
    protected function getMethod(string $name): ReflectionMethod
    {
        $class = new ReflectionClass($this->nyt);
        $method = $class->getMethod($name);
        $method->setAccessible(true);
        return $method;
    }

    /**
     * Test that the given params and the API key are combined into a valid query string.
     *
     * @throws ReflectionException
     */
    public function testGetQueryString()
    {
        $queryStringMethod = $this->getMethod('getQueryString');

        $author = 'Jim Smith';
        $title = 'Test Book Title';
        $offset = 20;

        $data = [
            'author' => $author,
            'title' => $title,
            'offset' => $offset,
        ];

        $queryString = $queryStringMethod->invokeArgs($this->nyt, [$data]);

        $this->assertStringContainsString(sprintf('api-key=%s', $this->fakeApiKey), $queryString);

        $this->assertStringContainsString(sprintf('title=%s', rawurlencode($title)), $queryString);
        $this->assertStringContainsString(sprintf('offset=%s', rawurlencode($offset)), $queryString);
        $this->assertStringContainsString(sprintf('offset=%s', rawurlencode($offset)), $queryString);
    }

    /**
     * Test that the query string contains the API key only if no parameters are provided.
     *
     * @throws ReflectionException
     */
    public function testGetQueryStringAPIOnly()
    {
        $queryStringMethod = $this->getMethod('getQueryString');
        $queryString = $queryStringMethod->invokeArgs($this->nyt, [[]]);

        $this->assertEquals(sprintf('api-key=%s', $this->fakeApiKey), $queryString);
    }

}
