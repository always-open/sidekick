<?php

namespace AlwaysOpen\Sidekick\Tests\Helpers;

use AlwaysOpen\Sidekick\Helpers\Email;
use AlwaysOpen\Sidekick\Tests\TestCase;
use PHPUnit\Framework\Attributes\Test;

class EmailTest extends TestCase
{
    #[Test]
    public function withDots()
    {
        $this->assertEquals('testemail@domain.com', Email::normalizeFullEmail('te.st.email@domain.com'));
    }

    #[Test]
    public function withDashes()
    {
        $this->assertEquals('testemail@domain.com', Email::normalizeFullEmail('test-email@domain.com'));
    }

    #[Test]
    public function withSubDomain()
    {
        $this->assertEquals('testemail@domain.com', Email::normalizeFullEmail('test.email@sub.domain.com'));
    }

    #[Test]
    public function withCapitals()
    {
        $this->assertEquals('testemail@domain.com', Email::normalizeFullEmail('TeStEmail@DomAin.cOm'));
    }

    #[Test]
    public function withEverything()
    {
        $this->assertEquals('testemail@domain.com', Email::normalizeFullEmail('TeS.-tE-mail@multi.sub.sub.DomAin.cOm'));
    }

    #[Test]
    public function invalidAddress()
    {
        $this->expectException(\InvalidArgumentException::class);
        Email::normalizeFullEmail('TeS.-tE-mail@not.1');
    }

    #[Test]
    public function aliasedDomain()
    {
        $normalizedEmailOne = Email::normalizeFullEmail('TeS.tEmail@me.COM');
        $normalizedEmailTwo = Email::normalizeFullEmail('Te-StEm.ail@icloud.COM');
        $normalizedEmailThree = Email::normalizeFullEmail('TeS.tEma-il@mAc.com');

        $this->assertEquals($normalizedEmailOne, $normalizedEmailTwo);
        $this->assertEquals($normalizedEmailTwo, $normalizedEmailThree);
    }
}
