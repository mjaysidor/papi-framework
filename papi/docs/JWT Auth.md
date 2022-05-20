## READ: https://jwt.io/

## Overview:
JWT Authentication system allows you to restrict access to specific endpoints and verify user identity. In order to create Auth system use **"php cli auth:create".**

### Auth system elements:
* **AuthController**: handles user verification & JWT generation via /auth endpoint.
    * **credentialsValid()** - checks if data passed in request is valid (ex. checks for provided username & password against database table users)

    * **getPayload()** - if credentialsValid() returns true, it returns data to be contained in JWT. Ex. returns roles of user specified in database table users, or expiration date.
    * **getOpenApiDocRequestBody()** - provides OpenAPI request bodydocumentation array for /auth endpoint.  
      Example:
```
protected function credentialsValid(?array $requestBody): bool
    {
        // if no username and password are provided - deny access
        if (isset($requestBody['username'], $requestBody['password']) === false) {
            return false;
        }
        // get user from database
        $user = (new User())->get(
            [
                'username' => $requestBody['username'],
            ],
            ['*']
        );
        // if user does not exists - deny access
        if (isset($user[0]) !== true) {
            return false;
        }
        // store provided user data
        $this->userData = $user[0];
        // check if provided password is valid
        return password_verify($requestBody['password'], $this->userData['password']);
    }

    protected function getPayload(?array $requestBody): array
    {
        // if credentials are valid - store user roles in returned JSON Web Token payload
        return [
            'roles' => $this->userData['roles']
        ];
    }

    // get /auth required request body OpenAPI documentation
    protected function getOpenApiDocRequestBody(): array
    {
        return [
            'username' => [
                'type' => 'string',
            ],
            'password' => [
                'type' => 'string',
            ],
        ];
    }
```

* OPTIONAL **UserController** - if you decide to handle user identity based on users table with username & password - it handles CRUD operations on database users.
```
class UserController extends ResourceController
{
    public function getResource(): User
    {
        return new User();
    }

    public function init(): void
    {
        $this->post(
            function (Request $request) {
                return ResourceCRUDHandler::create($this->resource, $request, [new EncodePassword(), new AddRole()]);
            }
        );

        $this->put(
            function (Request $request, $id) {
                return ResourceCRUDHandler::update($this->resource, $id, $request, [new EncodePassword()]);
            }
        );

        $this->delete(
            function (Request $request, $id) {
                return ResourceCRUDHandler::delete($this->resource, $id);
            }
        );

        $this->getById(
            function (Request $request, $id) {
                return ResourceCRUDHandler::getById($this->resource, $id);
            }
        );

        $this->get(
            function (Request $request) {
                return ResourceCRUDHandler::getCollection($this->resource, $request);
            }
        );
    }
}
```
* OPTIONAL **User resource** - a resource containing username, roles and password.  
  Example:
```
class User extends Resource
{
    public function getTableName(): string
    {
        return 'users';
    }

    public function getFields(): array
    {
        return [
            'id'       => new Id(),
            'username' => new Varchar(30, 'unique'),
            'roles'    => new Varchar(100),
            'password' => new Varchar(110),
        ];
    }

    public function getDefaultSELECTFields(): array
    {
        return [
            'id',
            'username',
            'roles',
        ];
    }

    public function getEditableFields(): array
    {
        return [
            'username',
            'password',
            'roles',
        ];
    }

    public function getPUTValidators(): array
    {
        return [];
    }

    public function getPOSTValidators(): array
    {
        return [
            'username' => [new NotBlank()],
            'password' => [new NotBlank()],
        ];
    }

    public function getRoles(): array
    {
        return [
            'ROLE_ADMIN',
            'ROLE_USER',
        ];
    }
}
```
* **JWT class** - creates, encodes & validates JSON Web Tokens.

* **JWTVoters($request)** - extending \papi\Auth\JWTVoter. Used to grant access based on payload data provided in JWT. To create custom tokens create classes extending JwtVoter. All you have to do is to override hasValidPayload method.  
  Example:
    * Voter class
  ```
  class AsdVoter extends JwtVoter
  {
      protected function hasValidPayload(): bool
      {
          // check if JWT payload contains field "admin" and "admin" field is set to TRUE
          return (isset($this->payload['admin']) && $this->payload['admin'] === true);
      }
  }
  ```
    * Usage in controller
  ```
  $this->post(
              function (Request $request) {
                  if ((new AsdVoter($request))->hasValidTokenAndPayload() !== true) {
                      return new AccessDeniedResponse();
                  }
 
                  return ResourceCRUDHandler::create($this->resource, $request);
              }
          );
  ```  

* **AuthConfig** - sets the secret used in encoding/decoding JSON Web Tokens.  
  Example:
```
class AuthConfig implements \papi\Config\AuthConfig
{
    public static function getSecret(): string
    {
        return 'secret123';
    }
}
```