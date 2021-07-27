<?php

namespace BluefynInternational\Sidekick\Helpers;

class Email
{
    const DOMAIN_ALIASES = [
        'googlemail.com' => [
            'alias_for' => 'gmail.com',
        ],
        'me.com' => [
            'alias_for' => 'icloud.com',
        ],
        'mac.com' => [
            'alias_for' => 'icloud.com',
        ],
        'yahoo.com.cn' => [
            'alias_for' => 'yahoo.cn',
        ],
        'yahoo.co.in' => [
            'alias_for' => 'yahoo.co.in',
        ],
        'yandex.ru' => [
            'alias_for' => 'yandex.com',
        ],
        'yandex.by' => [
            'alias_for' => 'yandex.com',
        ],
        'yandex.ua' => [
            'alias_for' => 'yandex.com',
        ],
        'yandex.kz' => [
            'alias_for' => 'yandex.com',
        ],
        'ya.ru' => [
            'alias_for' => 'yandex.com',
        ],
    ];

    public static function normalizeFullEmail(String $emailAddress) : string
    {
        if (! filter_var($emailAddress, FILTER_VALIDATE_EMAIL)) {
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

        $normalizedDomain = implode('.', array_splice($domainParts, -2, 2));

        if (isset(self::DOMAIN_ALIASES[$normalizedDomain])) {
            return self::DOMAIN_ALIASES[$normalizedDomain]['alias_for'];
        }

        return $normalizedDomain;
    }
}
