<?php

declare(strict_types=1);

namespace App\Controller;

use papi\Controller\Controller;
use papi\Response\JsonResponse;
use papi\Response\OKResponse;
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

        $this->post(
            "/",
            function (Request $request) {
                return new OKResponse();
            }
        );
    }
}
