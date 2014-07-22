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
      "path": "/.json/s/{section_title}",
      "operations": [
        {
          "method": "GET",
          "summary": "Get listing of posts from spreadit",
          "notes": "",
          "type": "",
          "nickname": "getById",
          "authorizations": {},
          "parameters": [
            {
              "name": "section_title",
              "description": "title of spreadit to be retrieved.",
              "required": true,
              "type": "string",
              "paramType": "path"
            },
            {
              "name": "page",
              "description": "page of results",
              "required": false,
              "type": "integer",
              "paramType": "query"
            }
          ],
          "responseMessages": [
            {
              "code": 200,
              "message": "Successfully retrieved spreadits"
            }
          ]
        }
      ]
    },
    {
      "path": "/.json/s/{section_title}/posts/{post_id}",
      "operations": [
        {
          "method": "GET",
          "summary": "get data on a post including comments",
          "notes": "",
          "type": "",
          "nickname": "getById",
          "authorizations": {},
          "parameters": [
            {
              "name": "section_title",
              "description": "title of spreadit post resides in",
              "required": true,
              "type": "string",
              "paramType": "path"
            },
            {
              "name": "post_id",
              "description": "numeric id of post.",
              "required": true,
              "type": "integer",
              "paramType": "path"
            }
          ],
          "responseMessages": [
            {
              "code": 200,
              "message": "Successfully retrieved spreadits"
            }
          ]
        }
      ]
    },
    {
      "path": "/.json/vote/post/{post_id}",
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
              "name": "post_id",
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
      "path": "/vote/post/{post_id}/up",
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
              "name": "post_id",
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
      "path": "/vote/post/{post_id}/down",
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
              "name": "post_id",
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
