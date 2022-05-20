## Overview:
If you need to create endpoint which does more than just a standard REST handling you need to know a few things:

### Responses
Papi controllers should return a Response object. Following are currently available:
* AccessDeniedResponse
* ErrorResponse
* JsonResponse
* MethodNotAllowedResponse
* NotFoundResponse
* OKResponse
* ValidationErrorResponse

**Keep in mind that all of the above are json encoded responses.**

This is how you return a sample response:
```
return new OKResponse(
    [
        'some_key'    => 'some_value',
        'another_key' => 'another_value',
    ]
);
```
### Database handling
Papi comes with a PostgresDb database handler. To manage resources in a custom way, instead of using ResourceCRUDHandler, you can use resource objects directly. This is how you handle CRUD operations on resources:
```
// create database connection
$dbConnection = new PostgresDb();

// create resource handler object
$resource = new Comment();

// get resource collection from database
$resource->get($dbConnection);

// get resource collection with custom options:
// SELECT content 
// WHERE id > 99 AND content = sample_content
// ORDER BY id DESC

$resource->get(
    $dbConnection,
    ['id>' => 99, 'content=' => 'sample_content'],
    ['content'],
    'id',
    'desc'
);

// get resource by id
$resource->getById($dbConnection, '117');

```
```
// create resource
$resource->create(
    $dbConnection,
    ['content' => 'the content of the comment']
);
                
// update resource
$resource->update(
    $dbConnection,
    '117',              
    ['content' => 'changed content of the comment']
);
                
// delete resource by id 
$resource->delete(
    $dbConnection,
    '117'
);
```
Alternatively, if you need something even more custom, you can use PostgresDb handler directly:
```
// get resource             
$dbConnection->select('comment');
                  
// create new resource   
$dbConnection->insert(
    'comment',
    ['content' => 'new_content'],
);

// update resource      
$dbConnection->update(
    'comment',
    ['content' => 'new_content'],
    ['id=' => '117']
);

// delete resource by id
$dbConnection->delete(
    'comment',
    ['id=' => '117']
);

// execute a custom query
$dbConnection->query('select * from comment');
```
### Transactions:
If you need to perform more than 1 INSERT/UPDATE/DELETE operations in one endpoint - it is recommended to use transactions to ensure optimal performance & stability, by avoiding creating unnecessary multiple connections & queries. To perform a transaction - use beginTransaction() and executeTransaction() methods of PostgresDb class. Everything between these two calls will be included in the transaction.

Let's say you want to have an endpoint, which performs 5 insert queries to the database. If you use standard handlers - there will be 5 connections, with 5 queries executed, which, more or less, means 5 times the execution & 5 times less possible requests per second for the endpoint. If you use a transaction - all 5 queries will be executed at once, using one connection, which will probably affect the performance by a few percent at the most.

**This is how to do it:**
```
// begin transaction
$dbConnection->beginTransaction();

// now, add queries to transaction
// you can use any of the methods mentioned above, they all work together, as long as the same connection object is being used
// for example:

$dbConnection->query('insert into comment (content) values(some_value)');
$dbConnection->insert('comment', ['content' => 'some_value']);

$resource = new Comment();
$resource->create($dbConnection, ['content' => 'some_value']);
$resource->update($dbConnection, '117', ['content' => 'new value']);

(new OtherResource())->create($dbConnection, $dataArray);

// execute all the above queries
$dbConnection->executeTransaction();

// return response with HTTP status 201 (created)
return new JsonResponse(201);
```  
**NOTE: transactions are not really meant for SELECT queries, as there is probably no sense in executing multiple SELECT queries at once, especially in REST APIs.**
 
 