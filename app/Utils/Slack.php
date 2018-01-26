<?php

namespace App\Utils;

use App\Utils\Caster;
use Frlnc\Slack\Contracts\Http\Interactor;
use Frlnc\Slack\Contracts\Http\ResponseFactory;

class Slack
{
    protected $token;

    protected $baseUrl = 'https://slack.com/api/';

    public function __construct(string $token = null)
    {
        $this->token = $token;
    }

    public function post($method, array $parameters = [])
    {
        $url = $this->baseUrl . $method;

        if (!is_null($this->token)) {
            $parameters['token'] = $this->token;
        }

        foreach ($parameters as $key => $value) {
            if (is_array($value)) {
                $parameters[$key] = json_encode($value);
            }
        }

        if (0 !== count($parameters)) {
            $url .= '?' . http_build_query($parameters);
        }

    		$options = [
    			'ssl' => [
    				'verify_peer' => false,
    				'verify_peer_name' => false,
    			],
    		];
        try {
            $text = file_get_contents($url, false, stream_context_create($options));
            $text = mb_convert_encoding($text, 'utf8', 'auto');
            $response = json_decode($text, true);

            if ($response['ok'] === false) {
                throw new \RuntimeException('Slack API error');
            }
        } catch (\Throwable $e) {
            Caster::warning($e, $response);
        }
    }
}
