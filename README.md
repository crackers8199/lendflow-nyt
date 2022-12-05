## New York Times Best Sellers API
Use Laravel to create a JSON API around the NYT Best Sellers endpoint. Your app should expose one (and only one) endpoint:

### Available Filters
This endpoint should support the following subset of the NYT API's Query Parameters:
author: string
isbn[]: string
title: string
offset: integer

All filters above are optional. ISBN is 10 or 13 digits. Multiple ISBN can be searched at once. Do take note of the
format the NYT API expects multiple ISBN's. Offset must be a multiple of 20. Zero is a valid offset.
