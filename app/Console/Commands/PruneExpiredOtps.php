<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;

class PruneExpiredOtps extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:prune-expired-otps';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $expiredUsers = User::where('otp_expiry', '<=', now())->get();

        foreach ($expiredUsers as $user) {
            $user->otp = null;
            $user->otp_expiry = null;
            $user->save();
        }
    
        $this->info('Expired OTPs pruned successfully.');
    }
}
