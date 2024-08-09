<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Cache;

class NinjaVersion extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'ninja:version';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Get the latest version of Invoice Ninja';

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
        $version_file = trim(file_get_contents('https://raw.githubusercontent.com/invoiceninja/invoiceninja/v5-develop/VERSION.txt'));
        Cache::forever('version', $version_file);
    }

}
