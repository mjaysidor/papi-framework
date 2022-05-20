## What, how & why:
In Papi endpoints are managed and defined via use of controllers.  
There are 3 controller types:
* Controller - a plain controller, handles custom endpoints
* ResourceController - handles resource endpoints
* ManyToManyController - handles many to many relation endpoints

**Resource & ManyToMany Controllers are automatically created upon resource creation** (make:resource command).  
To create plain controller you can use **"make:controller" command**.  
Controllers can also be created by creating classes extending Controller/ResourceController/ManyToManyController.

Each controller contains init() method, which adds endpoints via $this->post/get/delete/put() methods. Additionally, Resource/ManyToMany Controllers contain getById() and also getResource() method, which returns instance of related resource.

### Handlers:
Controllers come with ResourceCRUDHandler and ManyToManyHandler classes, which handle CRUD operations on resources & many-to-many relations. They, respectively, contain following methods: create/delete/update/getById/getCollection and createRelation, deleteRelation, getRelation.


### Examples:
* ResourceController
```
class CommentController extends ResourceController
{
    public function getResource(): Comment
    {
        return new Comment();
    }

    public function init(): void
    {
        // adding endpoints......
    }
}
```
**if you do not need customizable CRUD endpoints in resource controller, you can just call $this->standardCrud(), then, instead of doing:**
```
    public function init(): void
    {
        $this->post(
            function (Request $request) {
                return ResourceCRUDHandler::create($this->resource, $request);
            }
        );

        $this->put(
            function (Request $request, $id) {
                return ResourceCRUDHandler::update($this->resource, $id, $request);
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
```  
you can just do this:
```
    public function init(): void
    {
        $this->standardCRUD();
    }
```

* ManyToManyController
```
class CommentPostController extends ManyToManyController
{
    protected function getResource(): ManyToMany
    {
        return new ManyToMany(Comment::class, Post::class);
    }

    public function init(): void
    {
        $this->standardCRUD();

//        or, if you need customizable endpoints:

//        $this->post(
//            function (Request $request) {
//                return ManyToManyHandler::createRelation($this->relation, $request);
//            }
//        );
//
//        $this->delete(
//            function (Request $request, $rootResourceId, $relatedResourceId) {
//                return ManyToManyHandler::deleteRelation($this->relation, $rootResourceId, $relatedResourceId);
//            }
//        );
//
//        $this->get(
//            function (Request $request) {
//                return ManyToManyHandler::getRelation($this->relation, $request);
//            }
//        );
    }
}
```


* Controller (plain)
```
class MainController extends Controller
{
    public function init(): void
    {
        $this->get(
            "/",
            function (Request $request) {
                // do something
                return new JsonResponse(201, ['Welcome to papi!']);
            }
        );

        $this->post(
            "/",
            function (Request $request) {
                 // do something
                 return new OKResponse();
            }
        );
    }
}
```