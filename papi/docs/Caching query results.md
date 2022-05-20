## What, how & why:
Papi includes caching system, which can be used to store database query results. Currently, it is supported in PostgresDb class (basic database handler), resource classes & ResourceCRUDHandler for SELECT operations. On standard resource endpoints all you have to do is set "cache" parameter to true (& optionally cache TTL in seconds):
```
$this->get(
            function (Request $request) {
                return ResourceCRUDHandler::getCollection($this->resource, $request, true, cacheTtl: 600);
            }
        );
```  
You can use it alternatively in resource class:
```
$postgresConnection = new PostgresDb();
$result = (new Comment())->get($postgresConnection, cache: true, cacheTtl: 600);
```  
or even PostgresDb handler:
```
$postgresConnection = new PostgresDb();
$result = $postgresConnection->select('comment', cache: true, cacheTtl: 600);
```