# Welcome to Papi framework!

### WARNING: PAPI IS AN EXPERIMENTAL PROJECT CURRENTLY IN THE ALPHA DEVELOPMENT STAGE.
**Papi is still in early stages of
development and has not been battle-tested in a production environment. At this stage nothing is guaranteed to work 100% properly. In the near
future there will probably be some fixes, tweaks and changes to be made.  
At this stage Papi will not be exported to a separate composer package to allow full customization, which might be
needed, given the early stage of the development process.**

### Papi is a framework designed to help developers implement performant PHP APIs both easily and quickly. It is a minimalistic framework focused on performance and ease of use.

### What it provides:
* **[A built-in server based on Workerman][app]**

* **[Resource Class System][resources]** - no need to manage database by SQL statements - database creation, drops, schema updates, endpoint handling, validation, etc. are all managed by the framework. All you have to do is to generate resource class via CLI command & fill out a few arrays!

* **[Migration System][migrations]** - you can generate and execute migrations (based on changes in resource classes) to update db schema via CLI.

* **[Controller System][controllers]** - fully customizable controllers with GET/POST/PUT/DELETE endpoints are automatically generated for each created resource. You can also easily create custom controllers unrelated to resources.

* **[Endpoint Validation System][validators]** - you can set up different validation rules for PUT/POST requests in resource classes by choosing from available validators (ex. MinLength, NotBlank, Email, Url, Regex, etc.). Need custom validation? Easy - just create a class extending AbstractValidator & fill out 2 methods - isValid($data):bool and getErrorMessage():string. Now you can use your validators the same way you would use the built-in ones.

* **[Postgres database wrapper][db]** - no need to write SQLs - Papi provides a module covering all the basic db operations (including query escaping to prevent SQL injections)

* **[Custom database query result cache][cache]** - no more complicated Redis implementations - you can choose to store
  results for SELECT queries in our custom app cache mechanism. After testing the framework with our cache mechanism - at least for small result sets - it was much faster (about 5 times!).  
  **WE ARE NOT CLAIMING THAT OUR CACHE IS UNIVERSALLY FASTER THAN REDIS, AS WE HAVE NOT CARRIED OUT ANY EXTENSIVE RESEARCH**. With that said - **it is damn fast**.

* **Cursor & Offset Pagination System** - Papi automatically paginates resource-GET-collection endpoints. If possible - cursor pagination is used for better performance, otherwise offset pagination is applied. Of course, you can easily disable pagination on any given endpoint.

* **[JWT Authentication System][jwt]** - provides JWT generation, user verification, validating JWTs, etc.. Payload validation & user verification are fully customizable, with built in database-user based authentication.

* **[Automatic OpenAPI documentation generation][doc]** - on each app boot OpenAPI documentation is generated for all resource endpoints.

* **Automatic error logging** - all the errors are logged in /var/log/error.log log file

### To create a project use:
```
composer create-project mjaysidor/papi-framework your_project_name
```

[resources]: Resources.md
[migrations]: Migrations.md
[validators]: Validators.md
[controllers]: Controllers.md
[app]: App&#32;instance.md
[doc]: Documentation.md
[jwt]: JWT&#32;Auth.md
[cache]: Caching&#32;query&#32;results.md
[db]: Custom&#32;endpoints,&#32;db&#32;handling&#32;&&#32;transactions.md