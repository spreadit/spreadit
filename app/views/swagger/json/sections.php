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
    },
    {
      "path": "/vote/section/{section_id}/up/.json",
      "operations": [
        {
          "method": "POST",
          "summary": "upvote a section",
          "notes": "",
          "type": "",
          "nickname": "sectionUpvote",
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
              "name": "section_id",
              "description": "id of section to upvote",
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
      "path": "/vote/section/{section_id}/down/.json",
      "operations": [
        {
          "method": "POST",
          "summary": "downvote a section",
          "notes": "",
          "type": "",
          "nickname": "sectionDownvote",
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
              "name": "section_id",
              "description": "id of section to downvote",
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
    }
  ]
}
