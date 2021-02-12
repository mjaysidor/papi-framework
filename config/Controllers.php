<?php
declare(strict_types=1);

namespace config;

use App\Controller\AuthController;
use App\Controller\CommentController;
use App\Controller\CommentPostController;
use App\Controller\PostController;
use papi\Config\BootstrapConfig;
use JetBrains\PhpStorm\Pure;

class Controllers implements BootstrapConfig
{
    #[Pure] public static function getItems(): array
    {
        return [
            CommentController::class,
            PostController::class,
            CommentPostController::class,
            AuthController::class,
        ];
    }
}