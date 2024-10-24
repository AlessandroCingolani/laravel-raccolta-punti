<?php

namespace App\Console\Commands;

use App\Models\GiftVoucher;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class ExpireGiftVouchers extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'gift_vouchers:expired';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Set vouchers as expired when the valid_until date is past the current date.';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $currentDate = Carbon::now();

        // Update vouchers"expired"
        GiftVoucher::query()
            ->where('expiration_date', '<', $currentDate)
            ->where('status', '=', 'valid')
            ->update(['status' => 'expired']);

        $this->info('Buoni regalo controllati con successo!');
    }
}