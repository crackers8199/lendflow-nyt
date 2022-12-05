## New York Times Best Sellers API
Wrapper for the NYT Best Sellers endpoint.

One endpoint is available: `GET /api/1/nyt/best-sellers`

### Available Filters
This endpoint supports the following subset of the NYT API's Query Parameters:

`author`: string <br />
`isbn[]`: string<br />
* ISBN is 10 or 13 digits
* Multiple ISBN can be searched at once
  * when searching multiple ISBNs, provide them as an array 
    * example: `isbn[]=1234567890&isbn[]=0987654321`

`title`: string<br />
`offset`: integer
* Offset must be a multiple of 20
* Zero is a valid offset

All filters above are optional. 




