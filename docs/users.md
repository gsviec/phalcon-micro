###

### Create users

Method: POST
Content-Type: application/json
Endpoint: export HOST=https://lackky.com/v1/users

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
\-H "Content-Type: application/json" -X POST ${HOST}
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

``` 
curl -d '{""password": "lackkylove"}' 
\-H "Content-Type: application/json" -X PUT ${HOST}/password

``'

### Update 

