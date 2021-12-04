## Endpoints

The plugin adds three new endpoints to the REST API:

| Endpoint                              | HTTP Verb | Parameters                         |
| ------------------------------------- | --------- | ---------------------------------- |
| */wp-json/healthos/v2/set-password*   | POST      | email* <br /> password* <br /> new_password*            |
| */wp-json/healthos/v2/user/{id}*      | GET       | id*                                 |
| */wp-json/healthos/v2/user/{id}*      | POST      | id* <br /> first_name* <br /> last_name* <br /> user_email* <br /> phone_number* <br /> *other fields*                            |

## Change Log
- 0.0.2
   - added endpoints for set get/update user
 - 0.0.1
   - added endpoint for set new password
