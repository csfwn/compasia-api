<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Str;
use RaditzFarhan\ApiResponse\ApiResponse;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    public $apiResponse;

    public function __construct()
    {
        $this->apiResponse = new ApiResponse;
    }

    protected function getCacheKeyPrefix(string $classname): string
    {
        $resource_name = str($classname)->basename('Controller');

        $collection = str($classname)->explode('\\');
        $collection->pop();
        $prefix = $collection->last();

        if ($prefix) {
            $resource_name = $prefix . '-' . $resource_name;
        }

        return strtolower($resource_name);
    }

    protected function getListCacheKey(Request $request = null, ?string $classname, ?string $action = null): string
    {
        $key = Str::of($classname)->kebab()->lower();

        if ($action) {
            $key .= '-' . Str::of($action)->kebab()->lower();
        }

        if ($request && count($request->query()) > 0) {
            $key = $key . '-' . $request->collect()
                ->sortKeys()
                ->map(function ($item, $key) {
                    return $key . ':' . $item;
                })
                ->implode('-');
        }

        return $key;
    }

    protected function getOneCacheKey($resource, ?string $classname, ?string $action = null): string
    {
        $key = Str::of($classname)->kebab()->lower();

        if ($action) {
            $key .= '-' . Str::of($action)->kebab()->lower();
        }

        if ($resource instanceof \Illuminate\Database\Eloquent\Model) {
            $key .= ':' . $resource->{$resource->getKeyName()};
        } elseif (is_string($resource) || is_numeric($resource)) {
            $key .= ':' . $resource;
        }

        return $key;
    }
}
