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
      "path": "/vote/comment/{comment_id}/.json",
      "operations": [
        {
          "method": "GET",
          "summary": "get all user comments on a post",
          "notes": "",
          "type": "",
          "nickname": "getById",
          "authorizations": {},
          "parameters": [
            {
              "name": "comment_id",
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
      "path": "/vote/comment/{comment_id}/up/.json",
      "operations": [
        {
          "method": "POST",
          "summary": "upvote a single comment",
          "notes": "",
          "type": "",
          "nickname": "getById",
          "authorizations": {},
          "parameters": [
            {
              "name": "X-Auth-Token",
              "description": "public authentication token",
              "required": true,
              "type": "string",
              "paramType": "header"
            },
            {
              "name": "comment_id",
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
            },
            {
              "code": 401,
              "message": "Not authorized"
            }
          ]
        }
      ]
    },
    {
      "path": "/vote/comment/{comment_id}/down/.json",
      "operations": [
        {
          "method": "POST",
          "summary": "downvote a single comment",
          "notes": "",
          "type": "",
          "nickname": "getById",
          "authorizations": {},
          "parameters": [
            {
              "name": "X-Auth-Token",
              "description": "public authentication token",
              "required": true,
              "type": "string",
              "paramType": "header"
            },
            {
              "name": "comment_id",
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
