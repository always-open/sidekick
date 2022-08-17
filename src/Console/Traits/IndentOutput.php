<?php

namespace AlwaysOpen\Sidekick\Console\Traits;

trait IndentOutput
{
    private static string $OUTPUT_INDENT = '    ';

    protected function indent(int $level = 1) : string
    {
        return str_repeat(self::$OUTPUT_INDENT, $level);
    }

    protected function indentedInfo(string $message, int $indentation = 1, string|int|null $verbosity = null) : void
    {
        $this->info($this->indent($indentation) . $message, $verbosity);
    }
}
