<?php

namespace Huangdijia\Youdu\Console;

use Huangdijia\Youdu\Facades\Youdu;
use Illuminate\Console\Command;

class SendCommand extends Command
{
    protected $signature   = 'youdu:send {message} {--to= : Users, implode by \'|\'} {--dept= : Depts, implode by \'|\'}';
    protected $description = 'Send a youdu message';

    public function handle()
    {
        $toUser  = (string) $this->option('to');
        $toDept  = (string) $this->option('dept');
        $message = (string) $this->argument('message');

        try {
            Youdu::send($toUser, $toDept, $message);
        } catch (\Exception $e) {
            $this->warn($e->getMessage());
            return;
        }

        $this->info('Send success!');
    }
}
