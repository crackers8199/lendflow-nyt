<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\BestSellersRequest;
use App\Services\NYTService;

class NYTController extends Controller
{
    public function bestSellers(BestSellersRequest $request, NYTService $nyt)
    {
        return response($nyt->fetchBestSellers($request->validated()));
    }
}
