<?php

namespace BluefynInternational\Sidekick\Tests\Helpers;

use BluefynInternational\Sidekick\Helpers\Arrays;
use BluefynInternational\Sidekick\Tests\TestCase;

class ArraysTest extends TestCase
{
    /** @test */
    public function mergeEmptyArrayIntoExisting()
    {
        $expected = [123];
        $this->assertEquals(
            $expected,
            Arrays::uniqueMergeColumn(['found' => [123]], [], 'found'),
        );
    }

    /** @test */
    public function mergeExistingArrayIntoEmpty()
    {
        $expected = [123];
        $this->assertEquals(
            $expected,
            Arrays::uniqueMergeColumn([], ['found' => [123]], 'found'),
        );
    }

    /** @test */
    public function mergeTwoNestedArrays()
    {
        $array1 = ['found' => [
            '0',
            '1',
        ]];

        $array2 = ['found' => [
            '1',
            '2',
        ]];

        $merged = Arrays::uniqueMergeColumn($array1, $array2, 'found');
        $this->assertTrue(in_array('0', $merged));
        $this->assertTrue(in_array('1', $merged));
        $this->assertTrue(in_array('2', $merged));
    }
}
