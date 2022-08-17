<?php

namespace AlwaysOpen\Sidekick\Tests\Helpers;

use AlwaysOpen\Sidekick\Helpers\S3;
use AlwaysOpen\Sidekick\Tests\TestCase;

class S3Test extends TestCase
{
    /** @test */
    public function parsesFilePathCorrectly()
    {
        $this->assertEquals('path/to/file.csv', S3::getS3FilePath('s3.com/path/to/file.csv'));
        $this->assertEquals('path/to/file.csv', S3::getS3FilePath('path/to/file.csv'));
        $this->assertEquals('/path/to/file.csv', S3::getS3FilePath('/path/to/file.csv'));
        $this->assertEquals('test_import.xlsx', S3::getS3FilePath('https://test-us-east-staging.s3.us-east-2.amazonaws.com/test_import.xlsx'));
    }

    /** @test */
    public function parsesBucketCorrectly()
    {
        $this->assertNull(S3::getBucketFromPath('s3.com/path/to/file.csv'));
        $this->assertNull(S3::getBucketFromPath('path/to/file.csv'));
        $this->assertNull(S3::getBucketFromPath('/path/to/file.csv'));
        $this->assertEquals('test-us-east-staging', S3::getBucketFromPath('https://test-us-east-staging.s3.us-east-2.amazonaws.com/test_import.xlsx'));
    }
}
