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
      "path": "/s/{section_title}/.json",
      "operations": [
        {
          "method": "GET",
          "summary": "Get listing of posts from spreadit",
          "notes": "",
          "type": "",
          "nickname": "getPosts",
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
      "path": "/s/{section_title}/{sort_mode}/.json",
      "operations": [
        {
          "method": "GET",
          "summary": "Get sorted listing of posts from spreadit",
          "notes": "",
          "type": "",
          "nickname": "getPostsSorted",
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
              "name": "sort_mode",
              "description": "method of sorting.",
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
      "path": "/s/{section_title}/{sort_mode}/{timeframe}/.json",
      "operations": [
        {
          "method": "GET",
          "summary": "Get sorted listing limited by timeframe of posts from spreadit",
          "notes": "",
          "type": "",
          "nickname": "getPostsSortedTimeframe",
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
              "name": "sort_mode",
              "description": "method of sorting.",
              "required": true,
              "type": "string",
              "paramType": "path"
            },
            {
              "name": "timeframe",
              "description": "time method to apply.",
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
      "path": "/s/{section_title}/posts/{post_id}/.json",
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
      "path": "/s/{section_title}/add/.json",
      "operations": [
        {
          "method": "POST",
          "summary": "create a post",
          "notes": "",
          "type": "",
          "nickname": "createPost",
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
              "name": "title",
              "description": "title of post",
              "required": true,
              "type": "string",
              "paramType": "form"
            },
            {
              "name": "section",
              "description": "spreadit to post into",
              "required": true,
              "type": "string",
              "paramType": "form"
            },
            {
              "name": "url",
              "description": "url/link for post",
              "required": false,
              "type": "string",
              "paramType": "form"
            },
            {
              "name": "data",
              "description": "content of post",
              "required": false,
              "type": "string",
              "paramType": "form"
            }
          ],
          "responseMessages": [
            {
              "code": 200,
              "message": "Successfully retrieved spreadits"
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
      "path": "/vote/post/{post_id}/.json",
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
      "path": "/vote/post/{post_id}/up/.json",
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
              "name": "X-Auth-Token",
              "description": "public authentication token",
              "required": true,
              "type": "string",
              "paramType": "header"
            },
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
      "path": "/vote/post/{post_id}/down/.json",
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
              "name": "X-Auth-Token",
              "description": "public authentication token",
              "required": true,
              "type": "string",
              "paramType": "header"
            },
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
