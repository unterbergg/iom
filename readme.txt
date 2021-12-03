## Endpoints

The plugin adds two new endpoints to the REST API:

 - */wp-json/healthos/v1/set-password*
 -- HTTP Verb: POST
 -- Parameters (**all required**):
 --- email
 --- password
 --- new_password

## Example Requests (jQuery)

### Set New Password

`
$.ajax({
  url: '/wp-json/healthos/v1/set-password',
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


### Credits

 == Changelog ==
 = 0.0.1 =
 * added endpoint for set new password
