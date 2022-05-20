### Overview:
Resource classes are used to represent an API resource. They are stored in Postgresql database as tables. Creating resource classes allows you to create controllers with automated CRUD endpoints and migrations which bring database schema up to date, no more custom SQL statements!
There are two ways to create a resource - recommended way is to use CLI module (**"php cli make:resource"**), but you can also create a class extending \papi\Resource\Resource. **The CLI way saves you some time by creating corresponding CRUD controller on the spot.**

### Elements of resource class:
* **getTableName()** - sets the name of the corresponding Postgresql database table
```
    public function getTableName(): string
    {
        return 'comment';
    }
```
* **getFields()** - manages the fields of the resource. Fields are stored in format 'field_name' => new FieldType(params), ex. 'content' => new Varchar(100). Changing the content of this method will result in being able to generate a migration to bring db schema up to date. Apart from plain fields - relations are also stored in this method.
```
    public function getFields(): array
    {
        return [
            'id'      => new Id(),
            'content' => new Text(),
            'comment_id' => new ManyToOne(__CLASS__, Comment::class),
        ];
    }
```
* **getDefaultSELECTFields()** - specifies which fields are being returned by default on GET requests to resource endpoints. Simply store field names as strings, no array keys needed.
```
    public function getDefaultSELECTFields(): array
    {
        return [
            'id',
            'comment_id',
        ];
    }
```
* **getEditableFields()** - specifies which of the fields can be edited via user PUT requests after the creation of the resource object.
```
    public function getEditableFields(): array
    {
        return [
            'content',
            'comment_id',
        ];
    }
```
* **getPUTValidators()** - stores validators for PUT (edit resource) requests. Multiple validators may be specified for a field. Validators are stored in following format: 'field_name' => [new SomeValidator(options), new SomeOtherValidator(options)].
```
    public function getPUTValidators(): array
    {
        return [
            [
                'content' => [
                    new MinLength(10),
                ],
            ],
        ];
    }
```
* **getPOSTValidators()** - same as above, but for POST (create resource) requests.
```
    return [
            [
                'content' => [
                    new MinLength(10),
                    new NotBlank(),
                ],
                'email' => [
                    new Email()
                ]
            ],
        ];
```