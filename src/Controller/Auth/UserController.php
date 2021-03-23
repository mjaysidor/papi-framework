<?php
declare(strict_types=1);

namespace App\Controller\Auth;

use App\Resource\Auth\User;
use papi\Callbacks\EncodePassword;
use papi\Callbacks\AddRole;
use papi\Controller\ResourceController;
use papi\Resource\ResourceCRUDHandler;
use Workerman\Protocols\Http\Request;

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
