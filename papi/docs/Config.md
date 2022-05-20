### Overview:
Papi comes with a few configuration files you need to be aware of - API Responses Documentation, Auth, Database and Documentation config. You'd probably want to change the default values they come with.

### Config files:
* **APIResponsesDocConfig** - it configures default OpenAPI (Swagger) documentation responses for various request methods in arrays. The default values are a good starting point. If you want to change the defaults it is highly recommended to get acquainted with Swagger Docs and use Swagger Editor to debug generated docs.
  Example:
```
    public function getPOSTResponses(): array
    {
        return [
            201 => [
                'description' => 'Resource created',
            ],
            400 => [
                'description' => 'Invalid body',
            ],
        ];
    }
```
* **AuthConfig** - only present after using auth:create command. It sets the secret used in encoding/decoding JSON Web Tokens. If you're not sure what that means please visit https://jwt.io/introduction.
  Example:
```
    public static function getSecret(): string
    {
        return 'secret123';
    }
```
* **DatabaseConfig** - it configures Postgresql database connection parameters such as name, server (host), username, password, etc.. **Caution: for best performance DO NOT USE 'localhost' server name, instead use '127.0.0.1'. Also - if database is set up on the same server as the app - return true in isLocal method - it allows faster connections by omitting the "host" parameter.** Example:
```
    public static function getName(): string
    {
        return 'ihmj';
    }
``` 
* **DocumentationConfig** - it configures OpenAPI documentation parameters: OpenAPI version and description, title and version of your application. Example:
```
    public function getOpenApiVersion(): string
    {
        return '3.0.0';
    }
```