{
  "apiVersion": "1.0.0",
  "swaggerVersion": "1.2",
  "basePath": "https://spreadit.io",
  "resourcePath": "",
  "produces": [
    "application/json"
  ],
  "apis": [
    {
      "path": "/api/auth/.json",
      "operations": [
        {
          "method": "GET",
          "summary": "Get auth details",
          "notes": "",
          "type": "",
          "nickname": "getAuth",
          "authorizations": {},
          "parameters": [
            {
              "name": "X-Auth-Token",
              "description": "public authentication token",
              "required": true,
              "type": "string",
              "paramType": "header"
            }
          ],
          "responseMessages": [
            {
              "code": 200,
              "message": "Successfully retrieved user details"
            },
            {
              "code": 401,
              "message": "Not authorized"
            }
          ]
        },
        {
          "method": "POST",
          "summary": "Generate authentication token",
          "notes": "",
          "type": "",
          "nickname": "createAuthToken",
          "authorizations": {},
          "parameters": [
            {
              "name": "username",
              "description": "your username",
              "required": true,
              "type": "string",
              "paramType": "form"
            },
            {
              "name": "password",
              "description": "your password",
              "required": true,
              "type": "string",
              "paramType": "form"
            }
          ],
          "responseMessages": [
            {
              "code": 200,
              "message": "Successfully retrieved authentication token"
            },
            {
              "code": 401,
              "message": "Not authorized"
            }
          ]
        },
        {
          "method": "DELETE",
          "summary": "delete/log out of application",
          "notes": "",
          "type": "",
          "nickname": "deleteAuth",
          "authorizations": {},
          "parameters": [
            {
              "name": "X-Auth-Token",
              "description": "public authentication token",
              "required": true,
              "type": "string",
              "paramType": "header"
            }
          ],
          "responseMessages": [
            {
              "code": 200,
              "message": "Successfully logged out"
            },
            {
              "code": 401,
              "message": "Not authorized"
            }
          ]
        }
      ]
    }
  ]
}
