<?php

namespace Huangdijia\Youdu\Console;

use Huangdijia\Youdu\Facades\Youdu;
use Illuminate\Console\Command;

class SendCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'youdu:send {message} {--to= : } {--dept= : }';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send a youdu message';

    public function handle()
    {
        $toUser  = (string) $this->option('to');
        $toDept  = (string) $this->option('dept');
        $message = (string) $this->argument('message');

        try {
            Youdu::send($toUser, $toUser, $message);
        } catch (\Exception $e) {
            $this->warn($e->getMessage());
            return;
        }

        $this->info('Send success!');
    }
}
