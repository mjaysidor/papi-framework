## What, how & why:
Papi provides classes allowing you to automatically edit incoming request's body - ex. you can automatically add "created_at" field with current date, or encode sent plain password into argon2i-encoded password.

Papi comes with following modifier classes out of the box:
* AddCurrentDate - adds "created_at" field with current date
* AddRole - adds user role to body (default ROLE_USER)
* EncodePassword - encodes sent password into argon2i-encoded password

To use a modifier simply add an array of desired objects to ResourceCRUDHandler create/update method arguments, ex.
```
$this->post(
            function (Request $request) {
                return ResourceCRUDHandler::create($this->resource, $request, [new AddCurrentDate(), new AddRole()]);
            }
        );
```