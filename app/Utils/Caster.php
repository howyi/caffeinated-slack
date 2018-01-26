<?php

namespace App\Utils;

use App\Utils\Slack;
use ServiceMonitor\Twitter\TwitterEvent;

class Caster
{
    public static function log(string $text, array $context = null): void
    {
        self::post($text, '', 'Log', 'neutral_face', '#439FE0', $context);
    }

    public static function warning(\Throwable $e, array $context = null): void
    {
        $title = $e->getMessage();
        $text = <<<EOT
code: {$e->getCode()}
file: {$e->getFile()}: L{$e->getLine()}
EOT;

        self::post($title, $text, 'Exception', 'dizzy_face', 'warning', $context);
    }

    public static function exception(\Throwable $e, array $context = null): void
    {
        $title = $e->getMessage();
        $text = <<<EOT
code: {$e->getCode()}
file: {$e->getFile()}: L{$e->getLine()}
EOT;

        self::post($title, $text, 'Exception', 'dizzy_face', 'danger', $context);
    }

    private static function post(
        string $title,
        string $text,
        string $username,
        string $iconEmoji,
        string $color,
        ?array $context
    ): void {
        $slackToken = env('SLACK_TOKEN');

        $slack = new Slack($slackToken);

        $request = [
            'channel'    => '#test',
            'username'   => $username,
            'icon_emoji' => ":$iconEmoji:",
            'attachments' => [
                [
                    'title' => $title,
                    'text'  => $text,
                    'color' => $color,
                ]
            ],
        ];

        if (false === is_null($context)) {
            $encodedContext = json_encode($context, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
            $request['attachments'][0]['text'] .= PHP_EOL . PHP_EOL . PHP_EOL . 'context:' . PHP_EOL;
            $request['attachments'][0]['text'] .= $encodedContext;
        }

        $response = $slack->post('chat.postMessage', $request);
    }
}
