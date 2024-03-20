<?php

namespace SqlModels;

use Psr\Log\AbstractLogger;

class Logger extends AbstractLogger
{
    /**
     * Logs with an arbitrary level.
     *
     * @param mixed   $level
     * @param string|\Stringable $message
     * @param mixed[] $context
     *
     * @return void
     *
     * @throws \Psr\Log\InvalidArgumentException
     */
    public function log($level, string|\Stringable $message, array $context = []): void
    {
        $microTime = explode(' ', microtime());
        $microTime = date('Y-m-d h:i:s.4') . $microTime[1];

        $line = "{$microTime} :: {$level} :: {$message}\n";

        fwrite(STDERR, $line);
    }

    /**
     * Interpolates context values into the message placeholders.
     */
    protected function interpolate(string|\Stringable $message, array $context = []): string
    {
        if (empty($context)) {
            return $message;
        }
        
        // build a replacement array with braces around the context keys
        $replace = [];
        foreach ($context as $key => $val) {
            // check that the value can be cast to string
            if (!is_array($val) && (!is_object($val) || method_exists($val, '__toString'))) {
                $replace['{' . $key . '}'] = $val;
            }
        }

        // interpolate replacement values into the message and return
        return strtr($message, $replace);
    }
}