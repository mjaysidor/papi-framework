<?php
declare(strict_types=1);

namespace App\Controller;

use papi\Controller\Controller;
use papi\Response\JsonResponse;
use Workerman\Protocols\Http\Request;

class MainController extends Controller
{
    public function init(): void
    {
        $this->get(
            "/",
            function (Request $request) {
                return new JsonResponse(201, ['Welcome to papi!']);
            }
        );

        $this->get(
            "/asd",
            function (Request $request) {
                return new JsonResponse(201, ['Welcome to papi!']);
            }
        );
    }
}