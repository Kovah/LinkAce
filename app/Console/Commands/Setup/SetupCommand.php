<?php

namespace App\Console\Commands\Setup;

use Illuminate\Console\Command;

class SetupCommand extends Command
{
    use SetupDatabaseViaCLI;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'linkace:setup';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'With the help of this command you can take care of your LinkAce installation easily.';

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
        $this->line('========================');
        $this->line('LinkAce Setup ' . config('linkace.version'));
        $this->line('========================');
        $this->line("\nWelcome to the LinkAce Setup.\nIn the following steps we will configure your database and prepare the application to be ready to use.");

        if (!$this->confirm('Do you cant to continue?')) {
            $this->line('If you need help or if you are stuck, please consult the LinkAce wiki or file an issue on Github.');
        }

        $this->runStepDatabase();
    }


}
