<?php

namespace BluefynInternational\Sidekick\Helpers;

class Email
{
    public static function normalize(String $emailAddress) : string
    {
        if (!filter_var($emailAddress, FILTER_VALIDATE_EMAIL)) {
            throw new \InvalidArgumentException('Invalid email address');
        }
        [$name, $domain] = explode('@', $emailAddress);

        return self::normalizeEmailName($name) . '@' . self::normalizeDomain($domain);
    }

    public static function normalizeEmailName(string $emailName) : string
    {
        return mb_strtolower(preg_replace('[\.|-]', '', $emailName));
    }

    public static function normalizeDomain(string $emailDomain) : string
    {
        $domainParts = explode('.', mb_strtolower($emailDomain));

        return implode('.', array_splice($domainParts, -2, 2));
    }
}
