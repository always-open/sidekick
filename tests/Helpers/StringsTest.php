<?php

namespace AlwaysOpen\Sidekick\Tests\Helpers;

use AlwaysOpen\Sidekick\Helpers\Strings;
use AlwaysOpen\Sidekick\SidekickFacade;
use AlwaysOpen\Sidekick\Tests\TestCase;
use PHPUnit\Framework\Attributes\Test;

class StringsTest extends TestCase
{
    #[Test]
    public function properlyChangesClassToJustName()
    {
        $this->assertNotEquals('Strings', Strings::class);
        $this->assertEquals('Strings', Strings::nameFromClass(Strings::class));
    }

    #[Test]
    public function nullClassFromName()
    {
        $this->assertEquals('', Strings::modelClassFromName(null));
    }

    #[Test]
    public function invalidClassFromName()
    {
        $this->assertEquals('Not A Class', Strings::modelClassFromName('Not A Class'));
    }

    #[Test]
    public function properlyAddsSpacing()
    {
        $expected = 'My New Sentence';
        $input = 'MyNewSentence';
        $this->assertEquals($expected, Strings::pascalToSpaces($input));
    }

    #[Test]
    public function properlyPrettifiesClassName()
    {
        $expected = 'Strings';
        $this->assertNotEquals($expected, Strings::class);
        $this->assertEquals($expected, Strings::nameFromClassPretty(Strings::class));

        $expected = 'Sidekick Facade';
        $this->assertNotEquals($expected, SidekickFacade::class);
        $this->assertEquals($expected, Strings::nameFromClassPretty(SidekickFacade::class));
    }

    #[Test]
    public function properlyCreatesPascal()
    {
        $input = 'My New Sentence';
        $expected = 'MyNewSentence';
        $this->assertEquals($expected, Strings::spacesToPascal($input));
    }

    #[Test]
    public function properlyPrettifiesName()
    {
        $input = 'Strings';
        $expected = 'Strings';
        $this->assertEquals($expected, Strings::modelClassFromNamePretty($input));
    }
}
