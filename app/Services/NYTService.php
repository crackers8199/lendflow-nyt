<?php

namespace App\Services;

use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Http;

class NYTService
{
    protected string $apiKey;
    protected string $baseURL;

    public function __construct()
    {
        $this->apiKey = config('nyt.api_key');
        $this->baseURL = config('nyt.api_base_url');
    }

    /**
     * Returns the API key.
     *
     * @return string the API key
     */
    protected function getApiKey(): string
    {
        return $this->apiKey;
    }

    /**
     * Get the query string to be sent to the selected endpoint (merge the API key with the parameters provided).
     *
     * @param array $data filters to be included in the query string (merged with the API key)
     * @return string the text query string to be sent to the NYT API
     */
    protected function getQueryString(array $data = []): string
    {
        return Arr::query(array_merge($data, ['api-key' => $this->getApiKey()]));
    }

    /**
     * Returns a JSON list of NYT Best Sellers, filtered by the given parameters.
     *
     * @param array $data Associative array containing parameters to filter on, valid filter parameters are
     *      author, title, isbn and offset
     * @return string JSON string response from the NYT API
     *
     */
    public function fetchBestSellers(array $data): string
    {
        $url = $this->baseURL . config('nyt.best_sellers_endpoint');

        $data['isbn'] = !empty($data['isbn']) ? implode(';', $data['isbn']) : null;

        return Http::get($url, $this->getQueryString($data))->body();
    }

}
