<?php

namespace AlwaysOpen\Sidekick\Helpers;

use Aws\Result;
use Aws\S3\S3Client;
use League\Flysystem\AwsS3V3\AwsS3V3Adapter;
use League\Flysystem\Filesystem;

class S3
{
    /**
     * @param string $path
     *
     * @return string
     */
    public static function getS3FilePath(string $path): string
    {
        if (str_contains($path, '.com/')) {
            $path = mb_substr($path, mb_strpos($path, '.com/') + 5);
        }

        return $path;
    }

    /**
     * @param string $path
     *
     * @return string|null
     */
    public static function getBucketFromPath(string $path): ?string
    {
        if (str_contains($path, '.s3')) {
            return mb_substr($path, mb_strpos($path, '//') + 2, mb_strpos($path, '.s3') - mb_strpos($path, '//') - 2);
        }

        return null;
    }

    /**
     * @return string|null
     */
    public static function getBucket() : ?string
    {
        return env('AWS_BUCKET');
    }

    /**
     * @return S3Client
     */
    public static function getS3Client(): S3Client
    {
        $config = [
            'credentials' => [
                'key' => env('AWS_ACCESS_KEY_ID', ''),
                'secret' => env('AWS_SECRET_ACCESS_KEY', ''),
            ],
            'region' => env('AWS_REGION', 'us-east-2'),
            'version' => 'latest',
        ];

        return new S3Client($config);
    }

    /**
     * @param string $path
     *
     * @return Filesystem
     */
    public static function getS3Filesystem(string $path): Filesystem
    {
        $bucket = self::getBucketFromPath($path);

        $client = self::getS3Client();

        $adapter = new AwsS3V3Adapter($client, $bucket);

        return new Filesystem($adapter);
    }

    /**
     * @param string $path
     *
     * @return bool
     *
     * @throws \League\Flysystem\FilesystemException
     */
    public static function delete(string $path): bool
    {
        try {
            self::getS3Filesystem($path)
                ->delete(self::getS3FilePath($path));
        } catch (\Exception $ex) {
            return false;
        }

        return true;
    }

    /**
     * @param string $path
     *
     * @return string
     *
     * @throws \League\Flysystem\FilesystemException
     */
    public static function getContent(string $path): string
    {
        $filePath = self::getS3FilePath($path);

        $client = self::getS3Filesystem($path);

        return $client->read($filePath);
    }

    /**
     * @param string      $path
     * @param string      $key
     * @param string|null $destination_bucket
     * @param array       $extras
     *
     * @return string|null
     *
     * @note Allows uploading entire file to S3
     */
    public static function uploadFileToBucket(
        string $path,
        string $key,
        ?string $destination_bucket = null,
        array $extras = []
    ): ?string {
        return self::uploadToBucket($path, null, $key, $destination_bucket, $extras)->get('ObjectURL');
    }

    /**
     * @param string      $content
     * @param string      $key
     * @param string|null $destination_bucket
     * @param array       $extras
     *
     * @return string|null
     *
     * @note Allows for uploading just file content to S3
     */
    public static function uploadContentToBucket(
        string $content,
        string $key,
        ?string $destination_bucket = null,
        array $extras = []
    ): ?string {
        return self::uploadToBucket(null, $content, $key, $destination_bucket, $extras)->get('ObjectURL');
    }

    /**
     * @param string|null $path
     * @param string|null $content
     * @param string      $key
     * @param string|null $destination_bucket
     * @param array       $extras
     *
     * @return Result
     */
    private static function uploadToBucket(
        ?string $path,
        ?string $content,
        string $key,
        ?string $destination_bucket,
        array $extras = []
    ): Result {
        $s3 = self::getS3Client();

        $object = [
            'Bucket' => $destination_bucket ?? self::getBucket(),
            'Key' => $key,
        ];

        if (null !== $path) {
            $object['SourceFile'] = $path;
        }

        if (null !== $content) {
            $object['Body'] = $content;
        }

        if (! empty($extras)) {
            $object = array_merge($object, $extras);
        }

        return $s3->putObject($object);
    }
}
