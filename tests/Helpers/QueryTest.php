<?php

namespace BluefynInternational\Sidekick\Tests\Helpers;

use BluefynInternational\Sidekick\Helpers\Query;
use BluefynInternational\Sidekick\Tests\TestCase;
use Illuminate\Support\Facades\DB;

class QueryTest extends TestCase
{
    /** @test */
    public function simpleQuery()
    {
        $query = DB::table('fake');

        $sqlString = Query::toString($query);
        $this->assertIsString($sqlString);
        $this->assertNotEmpty($sqlString);
        $this->assertStringContainsString('fake', $sqlString);
    }

    /** @test */
    public function complexQuery()
    {
        $query = DB::table('fake')
            ->join('another', 'another.key', '=', 'fake.key')
            ->join('sub', function ($builder) {
                $builder->where('sub.id', '>', 9)
                    ->on('sub.key', '=', 'fake.key');
            });

        $sqlString = Query::toString($query);
        $this->assertIsString($sqlString);
        $this->assertNotEmpty($sqlString);
        $this->assertStringContainsString('fake', $sqlString);
        $this->assertStringContainsString('`sub`.`id` > 9', $sqlString);
        $this->assertStringContainsString('`sub`.`key` = `fake`.`key`', $sqlString);
        $this->assertStringContainsString('`another`.`key`', $sqlString);
    }
}
