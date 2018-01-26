<?php

namespace App\Console\Commands\Bot;

use App\Utils\Slack;
use Illuminate\Console\Command;

class TestCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'test';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Test';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $slack = new Slack(env('SLACK_TOKEN'));
        $slack->post('chat.postMessage', [
            'channel'     => '#test',
            'text'        => 'Test',
            'username'    => 'Test',
            'icon_emoji'  => ":upside_down_face:",
            'attachments' => [
                [
                    'text'    => '22',
                    'actions' => [
                        [
                            'type' => 'button',
                            'text' => 'hdscsb',
                            'url'  => 'http://127.0.0.1',
                        ],
                    ],
                ],
            ],
        ]);
    }
}
