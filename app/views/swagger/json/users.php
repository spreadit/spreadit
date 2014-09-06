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
      "path": "/u/{username}/comments/.json",
      "operations": [
        {
          "method": "GET",
          "summary": "Get listing of comments from user",
          "notes": "",
          "type": "",
          "nickname": "getUserComments",
          "authorizations": {},
          "parameters": [
            {
              "name": "username",
              "description": "username to grab comments from",
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
              "message": "Successfully retrieved comments"
            }
          ]
        }
      ]
    },
    {
      "path": "/u/{username}/posts/.json",
      "operations": [
        {
          "method": "GET",
          "summary": "Get listing of posts from user",
          "notes": "",
          "type": "",
          "nickname": "getUserPosts",
          "authorizations": {},
          "parameters": [
            {
              "name": "username",
              "description": "username to grab posts from",
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
              "message": "Successfully retrieved posts"
            }
          ]
        }
      ]
    },
    {
      "path": "/u/{username}/votes/comments/.json",
      "operations": [
        {
          "method": "GET",
          "summary": "Get listing of comment votes from user",
          "notes": "",
          "type": "",
          "nickname": "getUserCommentVotes",
          "authorizations": {},
          "parameters": [
            {
              "name": "username",
              "description": "username to grab post votes from",
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
              "message": "Successfully retrieved votes"
            }
          ]
        }
      ]
    },
    {
      "path": "/u/{username}/votes/posts/.json",
      "operations": [
        {
          "method": "GET",
          "summary": "Get listing of post votes from user",
          "notes": "",
          "type": "",
          "nickname": "getUserPostsVotes",
          "authorizations": {},
          "parameters": [
            {
              "name": "username",
              "description": "username to grab post votes from",
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
              "message": "Successfully retrieved votes"
            }
          ]
        }
      ]
    },
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
    },
    {
      "path": "/login/.json",
      "operations": [
        {
          "method": "POST",
          "summary": "login to spreadit",
          "notes": "",
          "type": "",
          "nickname": "login",
          "authorizations": {},
          "parameters": [
            {
              "name": "username",
              "description": "username to login with",
              "required": true,
              "type": "string",
              "paramType": "form"
            },
            {
              "name": "password",
              "description": "password to login with",
              "required": true,
              "type": "string",
              "paramType": "form"
            }
          ],
          "responseMessages": [
            {
              "code": 200,
              "message": "Successfully executed login action (not necessarily logged in)"
            }
          ]
        }
      ]
    }
  ]
}
