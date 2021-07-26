<?php

namespace BluefynInternational\Sidekick\Tests\Helpers;

use BluefynInternational\Sidekick\Helpers\Email;
use BluefynInternational\Sidekick\Tests\TestCase;

class EmailTest extends TestCase
{
    /** @test */
    public function withDots()
    {
        $this->assertEquals('testemail@domain.com', Email::normalize('te.st.email@domain.com'));
    }

    /** @test */
    public function withDashes()
    {
        $this->assertEquals('testemail@domain.com', Email::normalize('test-email@domain.com'));
    }

    /** @test */
    public function withSubDomain()
    {
        $this->assertEquals('testemail@domain.com', Email::normalize('test.email@sub.domain.com'));
    }

    /** @test */
    public function withCapitals()
    {
        $this->assertEquals('testemail@domain.com', Email::normalize('TeStEmail@DomAin.cOm'));
    }

    /** @test */
    public function withEverything()
    {
        $this->assertEquals('testemail@domain.com', Email::normalize('TeS.-tE-mail@multi.sub.sub.DomAin.cOm'));
    }

    /** @test */
    public function invalidAddress()
    {
        $this->expectException(\InvalidArgumentException::class);
        Email::normalize('TeS.-tE-mail@not.1');
    }
}
