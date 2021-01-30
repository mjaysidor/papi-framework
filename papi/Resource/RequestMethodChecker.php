<?php
declare(strict_types=1);

namespace papi\Resource;

use Workerman\Protocols\Http\Request;

class RequestMethodChecker
{
    public static function isPost(Request $request): bool
    {
        return $request->method() === 'POST';
    }

    public static function isPut(Request $request): bool
    {
        return $request->method() === 'PUT';
    }

    public static function isDelete(Request $request): bool
    {
        return $request->method() === 'DELETE';
    }

    public static function isGet(Request $request): bool
    {
        return $request->method() === 'GET';
    }
}