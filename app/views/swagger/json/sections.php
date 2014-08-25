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
      "path": "/spreadits/.json",
      "operations": [
        {
          "method": "GET",
          "summary": "Get listing of spreadits",
          "notes": "",
          "type": "",
          "nickname": "getById",
          "authorizations": {},
          "parameters": [
          ],
          "responseMessages": [
            {
              "code": 200,
              "message": "Successfully retrieved spreadits"
            }
          ]
        }
      ]
    }
  ]
}
