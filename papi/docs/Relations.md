## What, how & why:
In Papi - relations are managed inside resource classes. To add a relation you can either call **"make:relation"** command or add a new relation object (OneToOne, ManyToOne or ManyToMany) to the resource class. The preferred way is using the CLI command, as it handles not only adding all the necessary code in the resource class, but also creating controllers for new endpoints, if necessary (many to many relations).
### There are 3 types of relations in Papi:
* OneToOne
* ManyToOne
* ManyToMany  
  OneToMany is currently not supported in order to avoid creating bidirectional relations, which can both unnecessarily complicate db schema and also impair performance, if used incorrectly.
### Examples:
* ManyToOne
```
public function getFields(): array
    {
        return [
            'id'      => new Id(),
            'content' => new Text(),
            'comment_id' => new ManyToOne(__CLASS__, Comment::class),
        ];
    }

    public function getDefaultSELECTFields(): array
    {
        return [
            'id',
            'comment_id',
        ];
    }

    public function getEditableFields(): array
    {
        return [
            'content',
            'comment_id',
        ];
    }
```
* ManyToMany
```
    public function getFields(): array
    {
        return [
            'id'      => new Id(),
            'content' => new Varchar(100),
            new ManyToMany(__CLASS__, Post::class),
        ];
    }
```
Note that ManyToMany relations do not require a defined associative key in getFields() array, as many to many relation does not involve a field inside resource table (it creates another table). Therefore, no extra entries in getDefaultSELECTFields and getEditableFields are needed.