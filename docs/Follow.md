###

### Follow or unfollow a user.

Method: POST

Content-Type: application/json

Endpoint: export HOST=https://lackky.com/v1/follow

Request Body Payload

| Property Name        | Type           | Description  |
| -------------        |:---------------:| -----:|
| userId                | string       | Field is required |

Response Payload

``` 
{
    "success": {
        "message": "follower success",
        "code": 202
    }
}
```
Sample request

``` 
curl -d '{"userId" : "1}' -H "Content-Type: application/json" -X POST ${HOST}
```
Sample response 
```
{
    "success": {
        "message": "follower success",
        "code": 202
    }
}
```
To delete follower

``` 
curl -d '{"userId" : "1}' -H "Content-Type: application/json" -X DELETE ${HOST}
```

Sample respone

``` 
{
    "success": {
        "message": "Un follower success",
        "code": 202
    }
}
```

### Get following and follower
Method: GET

Content-Type: application/json

Endpoint: export HOST=https://lackky.com/v1/follow/me

``` 
curl -H "Content-Type: application/json" -X GET ${HOST}
```

