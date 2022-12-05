<?php

namespace Tests\Feature;

use App\Services\NYTService;
use Illuminate\Http\JsonResponse;
use Tests\TestCase;

class BestSellersTest extends TestCase
{
    protected string $uri;

    /**
     * Basic setup for all tests.
     */
    protected function setUp(): void
    {
        parent::setUp();
        $this->uri = '/api/1/nyt/best-sellers';

        // mock the NYTService, we don't need to hit the NYT API
        $this->mock(NYTService::class)
            ->shouldReceive('fetchBestSellers');
    }

    /**
     * Test that the endpoint returns success when given no parameters (since all params are optional).
     */
    public function testSuccesWithNoParameters()
    {
        $response = $this->get($this->uri);
        $response->assertStatus(JsonResponse::HTTP_OK);
    }

    /**
     * Test that the endpoint returns success when a valid author is provided.
     */
    public function testSuccessWithValidAuthor()
    {
        $response = $this->call('GET', $this->uri, ['author' => 'Test Author']);
        $response->assertStatus(JsonResponse::HTTP_OK);
    }

    /**
     * Test that the endpoint returns failure when an invalid author is provided.
     */
    public function testFailureWithInvalidAuthor()
    {
        $response = $this->call('GET', $this->uri, ['author' => '']);
        $response->assertStatus(JsonResponse::HTTP_UNPROCESSABLE_ENTITY);
    }

    /**
     * Test that the endpoint returns success when a valid 10 digit ISBN is provided.
     */
    public function testSuccessWithValid10DigitISBN()
    {
        $response = $this->call('GET', $this->uri, ['isbn' => ['1111111111']]);
        $response->assertStatus(JsonResponse::HTTP_OK);
    }

    /**
     * Test that the endpoint returns success when a valid 13 digit ISBN is provided.
     */
    public function testSuccessWithValid13DigitISBN()
    {
        $response = $this->call('GET', $this->uri, ['isbn' => ['1111111111111']]);
        $response->assertStatus(JsonResponse::HTTP_OK);
    }

    /**
     * Test that the endpoint returns success when multiple valid ISBNs are provided.
     */
    public function testSuccessWithMultipleValidISBNs()
    {
        $response = $this->call('GET', $this->uri, ['isbn' => ['1111111111111', '1111111111111']]);
        $response->assertStatus(JsonResponse::HTTP_OK);
    }

    /**
     * Test that the endpoint returns failure when ISBN is provided in non-list format.
     */
    public function testFailureWithInvalidISBNNonList()
    {
        $response = $this->call('GET', $this->uri, ['isbn' => '']);
        $response->assertStatus(JsonResponse::HTTP_UNPROCESSABLE_ENTITY);
    }

    /**
     * Test that the endpoint returns failure when an empty ISBN list is provided.
     */
    public function testFailureWithEmptyISBNList()
    {
        $response = $this->call('GET', $this->uri, ['isbn' => []]);
        $response->assertStatus(JsonResponse::HTTP_UNPROCESSABLE_ENTITY);
    }

    /**
     * Test that the endpoint returns failure when an ISBN is provided that is not 10 or 13 digits.
     */
    public function testFailureWithInvalidISBNLength()
    {
        $response = $this->call('GET', $this->uri, ['isbn' => ['101010101010']]);
        $response->assertStatus(JsonResponse::HTTP_UNPROCESSABLE_ENTITY);
    }

    /**
     * Test that the endpoint returns success when a valid title is provided.
     */
    public function testSuccessWithValidTitle()
    {
        $response = $this->call('GET', $this->uri, ['title' => 'Test Book Title']);
        $response->assertStatus(JsonResponse::HTTP_OK);
    }

    /**
     * Test that the endpoint returns failure when an invalid title is provided.
     */
    public function testFailureWithInvalidTitle()
    {
        $response = $this->call('GET', $this->uri, ['title' => '']);
        $response->assertStatus(JsonResponse::HTTP_UNPROCESSABLE_ENTITY);
    }

    /**
     * Test that the endpoint returns success when a valid offset of zero is provided.
     */
    public function testSuccessWithValidOffsetZero()
    {
        $response = $this->call('GET', $this->uri, ['offset' => 0]);
        $response->assertStatus(JsonResponse::HTTP_OK);
    }

    /**
     * Test that the endpoint returns success when a valid offset (multiple of 20) is provided.
     */
    public function testSuccessWithValidOffsetNonZero()
    {
        $response = $this->call('GET', $this->uri, ['offset' => 20]);
        $response->assertStatus(JsonResponse::HTTP_OK);
    }

    /**
     * Test that the endpoint returns failure when a non-numeric offset is provided.
     */
    public function testFailureWithInvalidOffsetNonNumeric()
    {
        $response = $this->call('GET', $this->uri, ['offset' => 'test']);
        $response->assertStatus(JsonResponse::HTTP_UNPROCESSABLE_ENTITY);
    }

    /**
     * Test that the endpoint returns failure when an offset is provided that is not a multiple of 20.
     */
    public function testFailureWithInvalidOffsetNonMultipleOf20()
    {
        $response = $this->call('GET', $this->uri, ['offset' => 10]);
        $response->assertStatus(JsonResponse::HTTP_UNPROCESSABLE_ENTITY);
    }

    /**
     * Test that the endpoint returns success when multiple valid parameters are provided.
     */
    public function testSuccessWithMultipleParameters()
    {
        $response = $this->call(
            'GET',
            $this->uri,
            [
                'offset' => 20,
                'author' => 'Test Author',
                'isbn' => ['1111111111111'],
                'title' => 'Test Book Title',
            ]
        );

        $response->assertStatus(JsonResponse::HTTP_OK);
    }

    /**
     * Test that the endpoint returns failure when multiple valid parameters are provided,
     * but a single invalid parameter is provided.
     */
    public function testFailureWithMultipleValidParametersAndOneInvalidParameter()
    {
        $response = $this->call(
            'GET',
            $this->uri,
            [
                'offset' => 20,                 // valid offset
                'author' => 'Test Author',      // valid author
                'isbn' => [],                   // *INVALID* ISBN, cannot be empty list
                'title' => 'Test Book Title',   // valid title
            ]
        );

        $response->assertStatus(JsonResponse::HTTP_UNPROCESSABLE_ENTITY);
    }
}
