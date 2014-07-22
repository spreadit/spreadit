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
      "path": "/vote/post/{item_id}",
      "operations": [
        {
          "method": "GET",
          "summary": "get all user votes on a post",
          "notes": "",
          "type": "",
          "nickname": "getById",
          "authorizations": {},
          "parameters": [
            {
              "name": "item_id",
              "description": "id of item to grab votes from",
              "required": true,
              "type": "integer",
              "paramType": "path"
            }
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
      "path": "/vote/post/{item_id}/up",
      "operations": [
        {
          "method": "POST",
          "summary": "upvote a single post",
          "notes": "",
          "type": "",
          "nickname": "getById",
          "authorizations": {},
          "parameters": [
            {
              "name": "item_id",
              "description": "id of item to upvote",
              "required": true,
              "type": "integer",
              "paramType": "path"
            }
          ],
          "responseMessages": [
            {
              "code": 200,
              "message": "Successfully upvoted"
            }
          ]
        }
      ]
    },
    {
      "path": "/vote/post/{item_id}/down",
      "operations": [
        {
          "method": "POST",
          "summary": "downvote a single post",
          "notes": "",
          "type": "",
          "nickname": "getById",
          "authorizations": {},
          "parameters": [
            {
              "name": "item_id",
              "description": "id of item to downvote",
              "required": true,
              "type": "integer",
              "paramType": "path"
            }
          ],
          "responseMessages": [
            {
              "code": 200,
              "message": "Successfully downvoted"
            }
          ]
        }
      ]
    }
  ]
}
