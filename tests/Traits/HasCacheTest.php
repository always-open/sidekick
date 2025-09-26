<?php

namespace AlwaysOpen\Sidekick\Tests\Traits;

use AlwaysOpen\Sidekick\Helpers\Cache;
use AlwaysOpen\Sidekick\Models\Traits\HasCache;
use AlwaysOpen\Sidekick\Tests\TestCase;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;

class HasCacheTest extends TestCase
{
    #[DataProvider('dataSets')]
    #[Test]
    public function cacheKeyScenario($prefixes, $keyValues, $suffixes, $expected)
    {
        $this->assertEquals($expected, Cache::generateCacheKey($keyValues, prefixes: $prefixes, suffixes: $suffixes));
    }

    public static function dataSets(): \Generator
    {
        $data = [
            [
                'prefixes' => [],
                'expected' => '',
                'keyValues' => null,
                'suffixes' => [],
            ],
            [
                'prefixes' => [''],
                'expected' => '0f4080a2cc8baabf40131fa70b461b7a',
                'keyValues' => 'asdf',
                'suffixes' => [],
            ],
            [
                'prefixes' => ['test'],
                'expected' => 'test',
                'keyValues' => null,
                'suffixes' => [],
            ],
            [
                'prefixes' => ['test', 'places'],
                'expected' => 'test-places',
                'keyValues' => null,
                'suffixes' => [],
            ],
            [
                'prefixes' => ['test', 'places'],
                'expected' => 'test-places-0f4080a2cc8baabf40131fa70b461b7a',
                'keyValues' => 'asdf',
                'suffixes' => [],
            ],
            [
                'prefixes' => ['test', 'places'],
                'expected' => 'test-places-dcc9d9d1656afcf20f30a9d178323fef',
                'keyValues' => ['z', 'a', 'f'],
                'suffixes' => [],
            ],
            [
                'prefixes' => ['test', 'places'],
                'expected' => 'test-places-dcc9d9d1656afcf20f30a9d178323fef',
                'keyValues' => ['a', 'z', 'f'],
                'suffixes' => [],
            ],
            [
                'prefixes' => ['test', 'places'],
                'expected' => 'test-places-dcc9d9d1656afcf20f30a9d178323fef',
                'keyValues' => ['a', 'f', 'z'],
                'suffixes' => [],
            ],
            [
                'prefixes' => [],
                'expected' => 'dcc9d9d1656afcf20f30a9d178323fef-test-places',
                'keyValues' => ['z', 'f', 'a'],
                'suffixes' => ['test', 'places'],
            ],
            [
                'prefixes' => [],
                'expected' => 'dcc9d9d1656afcf20f30a9d178323fef-test-places',
                'keyValues' => ['a', 'f', 'z'],
                'suffixes' => ['test', 'places'],
            ],
            [
                'prefixes' => ['test', 'places'],
                'expected' => 'test-places-dcc9d9d1656afcf20f30a9d178323fef-test-places',
                'keyValues' => ['a', 'f', 'z'],
                'suffixes' => ['test', 'places'],
            ],

        ];

        foreach ($data as $datum) {
            yield $datum;
        }
    }

    #[Test]
    public function cacheKeyAddExistsRemove()
    {
        $trait = new class {
            use HasCache;
        };
        $key = 'test-key';

        $trait::addCacheKey($key);

        $this->assertTrue($trait::cacheKeyExists($key));

        $trait::removeCacheKey($key);

        $this->assertFalse($trait::cacheKeyExists($key));
    }
}
