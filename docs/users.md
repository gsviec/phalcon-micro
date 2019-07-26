###

### Create users

Method: POST
Content-Type: application/json
Endpoint: https://lackky.com/v1/users

Request Body Payload

| Property Name        | Type           | Description  |
| -------------        |:---------------:| -----:|
| email                | string       | Email is required |
| password             | string       |   Password is required |
| bio                  | string      |    Can empty|

Response Payload

| Property Name        | Type           | Description  |
| -------------        |:---------------:| -----:|
| email                | string       | Email is required |
| password             | string       |   Password is required |
| bio                  | string      |    Can empty|

Sample request

``` 
curl -d '{"email" : "hello@lackky.com", "password": "lackkylove", "fullName" : "Lackky"}'
\-H "Content-Type: application/json" -X POST https://lackky.com/v1/users
```
Sample response 
```
 {
     "data": {
         "id": 8,
         "email": "hello@lackky.com",
         "phone": null,
         "fullName": "Lackky"
     }
 } 
```
### Update users

### Update avatar

### Update password

### Update 