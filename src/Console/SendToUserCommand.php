<?php

namespace Huangdijia\Youdu\Console;

use Throwable;
use Illuminate\Console\Command;
use Huangdijia\Youdu\Facades\Youdu;
use Huangdijia\Youdu\Messages\App\Text;

class SendToUserCommand extends Command
{
    protected $signature   = 'youdu:sendToUser {user} {message} {--app=default}';
    protected $description = 'Send a youdu message';

    public function handle()
    {
        $toUser  = (string) $this->argument('user');
        $message = (string) $this->argument('message');
        $app     = (string) $this->option('app');

        try {
            $message = new Text($message);

            Youdu::app($app)->sendToUser($toUser, $message);
        } catch (Throwable $e) {
            $this->warn($e->getMessage());
            return;
        }

        $this->info('Send success!');
    }
}
