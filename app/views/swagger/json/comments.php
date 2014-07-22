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
      "path": "/.json/vote/comment/{comment_id}",
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
      "path": "/vote/comment/{comment_id}/up",
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
            }
          ]
        }
      ]
    },
    {
      "path": "/vote/comment/{comment_id}/down",
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
            }
          ]
        }
      ]
    }
  ]
}
