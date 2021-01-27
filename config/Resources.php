<?php
declare(strict_types=1);

namespace config;

use App\Resources\Comment;
use App\Resources\Post;
use framework\Config\BootstrapConfig;
use JetBrains\PhpStorm\Pure;

class Resources implements BootstrapConfig
{
    #[Pure] public static function getItems(): array
    {
        return [
            Post::class,
            Comment::class,
        ];
    }
}