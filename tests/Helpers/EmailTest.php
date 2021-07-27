<?php

namespace BluefynInternational\Sidekick\Tests\Helpers;

use BluefynInternational\Sidekick\Helpers\Email;
use BluefynInternational\Sidekick\Tests\TestCase;

class EmailTest extends TestCase
{
    /** @test */
    public function withDots()
    {
        $this->assertEquals('testemail@domain.com', Email::normalizeFullEmail('te.st.email@domain.com'));
    }

    /** @test */
    public function withDashes()
    {
        $this->assertEquals('testemail@domain.com', Email::normalizeFullEmail('test-email@domain.com'));
    }

    /** @test */
    public function withSubDomain()
    {
        $this->assertEquals('testemail@domain.com', Email::normalizeFullEmail('test.email@sub.domain.com'));
    }

    /** @test */
    public function withCapitals()
    {
        $this->assertEquals('testemail@domain.com', Email::normalizeFullEmail('TeStEmail@DomAin.cOm'));
    }

    /** @test */
    public function withEverything()
    {
        $this->assertEquals('testemail@domain.com', Email::normalizeFullEmail('TeS.-tE-mail@multi.sub.sub.DomAin.cOm'));
    }

    /** @test */
    public function invalidAddress()
    {
        $this->expectException(\InvalidArgumentException::class);
        Email::normalizeFullEmail('TeS.-tE-mail@not.1');
    }

    /** @test */
    public function aliasedDomain()
    {
        $normalizedEmailOne = Email::normalizeFullEmail('TeS.tEmail@me.COM');
        $normalizedEmailTwo = Email::normalizeFullEmail('Te-StEm.ail@icloud.COM');
        $normalizedEmailThree = Email::normalizeFullEmail('TeS.tEma-il@mAc.com');

        $this->assertEquals($normalizedEmailOne, $normalizedEmailTwo);
        $this->assertEquals($normalizedEmailTwo, $normalizedEmailThree);
    }
}
