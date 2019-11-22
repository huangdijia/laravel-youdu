<?php

namespace Huangdijia\Youdu\Console;

use Huangdijia\Youdu\Facades\Youdu;
use Huangdijia\Youdu\Messages\App\Text;
use Illuminate\Console\Command;

class SendToDeptCommand extends Command
{
    protected $signature   = 'youdu:sendToDept {dept} {message} {--app=default}';
    protected $description = 'Send a youdu message';

    public function handle()
    {
        $toDept  = (string) $this->argument('dept');
        $message = (string) $this->argument('message');
        $app     = (string) $this->option('app');

        try {
            $message = new Text($message);

            Youdu::app($app)->sendToDept($message);
        } catch (\Exception $e) {
            $this->warn($e->getMessage());
            return;
        }

        $this->info('Send success!');
    }
}
