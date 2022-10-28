<?php

namespace AlwaysOpen\Sidekick\Helpers;

use Illuminate\Contracts\Filesystem\FileNotFoundException;
use Illuminate\Contracts\Filesystem\Filesystem;
use Illuminate\Filesystem\FilesystemAdapter;
use Illuminate\Support\Facades\Storage as StorageFacade;

class Storage
{
    public static function storage(): Filesystem|FilesystemAdapter
    {
        if (app()->environment('local')) {
            $storage = StorageFacade::disk('local');
        } else {
            $storage = StorageFacade::disk('s3');
        }

        return $storage;
    }

    /**
     * @throws \League\Flysystem\FilesystemException
     * @throws FileNotFoundException
     */
    public static function get(string $path): string|null
    {
        if (app()->environment('local')) {
            return self::storage()->get($path);
        }

        return S3::getContent($path);
    }

    public static function path(string $path) : string
    {
        return self::storage()->path($path);
    }

    public static function put(string $path, $contents, mixed $options = []) : bool|string
    {
        return self::storage()->put($path, $contents, $options);
    }

    public static function putFileAs(string $path, $contents, string $name, mixed $options = []) : string
    {
        return self::storage()->putFileAs($path, $contents, $name, $options);
    }

    public static function url(string $path): string
    {
        return self::storage()->url($path);
    }
}
