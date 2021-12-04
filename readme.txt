## Endpoints

The plugin adds new endpoints to the REST API:

 - */wp-json/healthos/v2/set-password*
 -- HTTP Verb: POST
 -- Parameters (**all required**):
 --- email
 --- password
 --- new_password

  - */wp-json/healthos/v2/user/{id}*
  -- HTTP Verb: GET
  -- Parameters (**all required**):
  --- id

   - */wp-json/healthos/v2/user/{id}*
   -- HTTP Verb: POST
   -- Parameters:
   --- id*
   --- first_name*
   --- last_name*
   --- user_email*
   --- phone_number*
   --- fields for update

## Example Requests (jQuery)

### Set New Password

`
$.ajax({
  url: '/wp-json/healthos/v2/set-password',
  method: 'POST',
  data: {
    email: 'example@example.com',
    password: 'Pa$$word1',
    new_password: 'NewPa$$word12'
  },
  success: function( response ) {
    console.log( response );
  },
  error: function( response ) {
    console.log( response );
  },
});
`

### Get User

`
$.ajax({
  url: '/wp-json/healthos/v2/user/614',
  method: 'GET',
  success: function( response ) {
    console.log( response );
  },
  error: function( response ) {
    console.log( response );
  },
});
`

## Example Success Responses (JSON)

### Set New Password

`
{
    "data": {
        "status": 200
    },
    "message": "Password Reset Successfully."
}
`

### Get User / Update User

`
{
    "ID": "614",
    "user_login": "veronica.gordon",
    "user_nicename": "veronica-gordon-2-2-2-2-2",
    "user_email": "vladunter404@gmail.com",
    "user_url": "",
    "user_registered": "2021-07-08 15:50:25",
    "user_status": "0",
    "display_name": "vladunter404@gmail.com",
    "nickname": [
        "veronica.gordon"
    ],
    "first_name": [
        ""
    ],
    "last_name": [
        ""
    ],
    "description": [
        ""
    ],
    ...
    "phone_number": [
        "375445732415"
    ],
    "user_group": [
        "3830"
    ],
    "user_parent": [
        "1"
    ]
}
`

## Example Error Responses (JSON)

### Set New Password

`
{
    "code": "bad_email",
    "message": "No User Found.",
    "data": {
        "status": 404
    }
}
`

### Get User

`
{
    "code": "bad_id",
    "message": "No User Found.",
    "data": {
        "status": 404
    }
}
`

### Get User

`
{
    "code": "rest_missing_callback_param",
    "message": "Missing parameter(s): first_name",
    "data": {
        "status": 400,
        "params": [
            "first_name"
        ]
    }
}
`

### Credits

 == Changelog ==
 = 0.0.2 =
  * added endpoints for set get/update user
 = 0.0.1 =
 * added endpoint for set new password
