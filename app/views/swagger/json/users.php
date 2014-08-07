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
      "path": "/notifications/.json",
      "operations": [
        {
          "method": "GET",
          "summary": "get all notifications",
          "notes": "",
          "type": "",
          "nickname": "getUserNotifications",
          "authorizations": {},
          "parameters": [
          ],
          "responseMessages": [
            {
              "code": 200,
              "message": "Success"
            }
          ]
        }
      ]
    },
    {
      "path": "/logout",
      "operations": [
        {
          "method": "POST",
          "summary": "logout of spreadit",
          "notes": "",
          "type": "",
          "nickname": "logout",
          "authorizations": {},
          "parameters": [
          ],
          "responseMessages": [
            {
              "code": 200,
              "message": "Success"
            }
          ]
        }
      ]
    }
  ]
}
