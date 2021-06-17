<?php

namespace BluefynInternational\Sidekick\Tests\Helpers;

use BluefynInternational\Sidekick\Helpers\Strings;
use BluefynInternational\Sidekick\SidekickFacade;
use BluefynInternational\Sidekick\Tests\TestCase;

class StringsTest extends TestCase
{
    /** @test */
    public function properlyChangesClassToJustName()
    {
        $this->assertNotEquals('Strings', Strings::class);
        $this->assertEquals('Strings', Strings::nameFromClass(Strings::class));
    }

    /** @test */
    public function nullClassFromName()
    {
        $this->assertEquals('', Strings::modelClassFromName(null));
    }

    /** @test */
    public function invalidClassFromName()
    {
        $this->assertEquals('Not A Class', Strings::modelClassFromName('Not A Class'));
    }

    /** @test */
    public function properlyAddsSpacing()
    {
        $expected = 'My New Sentence';
        $input = 'MyNewSentence';
        $this->assertEquals($expected, Strings::pascalToSpaces($input));
    }

    /** @test */
    public function properlyPrettifiesClassName()
    {
        $expected = 'Strings';
        $this->assertNotEquals($expected, Strings::class);
        $this->assertEquals($expected, Strings::nameFromClassPretty(Strings::class));

        $expected = 'Sidekick Facade';
        $this->assertNotEquals($expected, SidekickFacade::class);
        $this->assertEquals($expected, Strings::nameFromClassPretty(SidekickFacade::class));
    }

    /** @test */
    public function properlyCreatesPascal()
    {
        $input = 'My New Sentence';
        $expected = 'MyNewSentence';
        $this->assertEquals($expected, Strings::spacesToPascal($input));
    }

    /** @test */
    public function properlyPrettifiesName()
    {
        $input = 'Strings';
        $expected = 'Strings';
        $this->assertEquals($expected, Strings::modelClassFromNamePretty($input));
    }
}
