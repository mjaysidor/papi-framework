<?php
declare(strict_types=1);

namespace config;

use App\Controller\CommentController;
use App\Controller\CommentPostController;
use App\Controller\PostController;
use framework\Config\BootstrapConfig;
use JetBrains\PhpStorm\Pure;

class Controllers implements BootstrapConfig
{
    #[Pure] public static function getItems(): array
    {
        return [
            CommentController::class,
            PostController::class,
            CommentPostController::class,
        ];
    }
}