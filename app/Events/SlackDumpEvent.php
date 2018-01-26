<?php

namespace App\Events;

use ServiceMonitor\Slack\SlackEvent;

class SlackDumpEvent extends SlackEvent
{
    public function isExecutable(array $value): bool
    {
        return true;
    }

    public function execute(array $value): void
    {
        dump($value);
    }
}
