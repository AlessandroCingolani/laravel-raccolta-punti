<?php

namespace App\Console\Commands;

use App\Models\Customer;
use Illuminate\Console\Command;

class ResetCustomerPoints extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'customers:reset-points';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Reset customers points';

    /**
     * Execute the console command.
     */
    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
        // Azzerare i punti dei clienti
        Customer::query()->update(['customer_points' => 0]);

        $this->info('Punti dei clienti azzerati con successo.');
    }
}