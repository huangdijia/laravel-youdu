<?php

declare(strict_types=1);
/**
 * This file is part of laravel-youdu.
 *
 * @link     https://github.com/huangdijia/laravel-youdu
 * @document https://github.com/huangdijia/laravel-youdu/blob/3.x/README.md
 * @contact  huangdijia@gmail.com
 */
namespace Huangdijia\Youdu\Console;

use Huangdijia\Youdu\YouduServiceProvider;
use Illuminate\Console\Command;

class InstallCommand extends Command
{
    protected $signature = 'youdu:install';

    protected $description = 'Install Package';

    public function handle()
    {
        $this->info('Installing Package...');

        $this->info('Publishing configuration...');

        $this->call('vendor:publish', [
            '--provider' => YouduServiceProvider::class,
            '--tag' => 'config',
        ]);

        $this->info('Installed Package');
    }
}
