<?php

namespace App\Console\Commands\Bot;

use App\Utils\Bot;
use App\Utils\Caster;
use Illuminate\Console\Command;
use ServiceMonitor\Slack\SlackMonitor;
use App\Events\SlackDumpEvent;
use App\Events\SlackToTweetEvent;

class SlackCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'bot:slack';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Run slack bot';

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
        $monitor = new \ServiceMonitor\Slack\SlackMonitor(env('SLACK_TOKEN'));
        $monitor->setEvent(new SlackDumpEvent());

        set_error_handler(
            function ($errno, $errstr, $errfile, $errline) {
                throw new \ErrorException(
                    $errstr,
                    0,
                    $errno,
                    $errfile,
                    $errline
                );
            }
        );

        try {
            Caster::log('Start bot:slack command :rocket:', $this->arguments());
            $monitor->start();
        } catch (\Throwable $e) {
            Caster::exception($e, $this->arguments());
        }
    }
}
