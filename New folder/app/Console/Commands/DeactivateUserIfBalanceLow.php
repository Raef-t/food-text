<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use Carbon\Carbon;

class DeactivateUserIfBalanceLow extends Command
{
    // Command name and description
    protected $signature = 'user:deactivate-low-balance';
    protected $description = 'Deactivate users if balance is less than the required amount on the last day of the month';

    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
        // Get the current date
        $currentDate = Carbon::now();

        // Check if today is the last day of the month
        if ($currentDate->isLastOfMonth()) {
            // Find users who meet the conditions
            $users = User::where('is_2ydha', true)
                ->where('active_2ydha', true)
                ->whereColumn('amount_2ydha', '>', 'wallet_balance')
                ->get();

            foreach ($users as $user) {
                // Set active_2ydha to false
                $user->active_2ydha = false;
                $user->save();

                // Log or output the deactivated user information
                $this->info("User {$user->id} deactivated due to insufficient balance.");
            }
        } else {
            $this->info("Today is not the last day of the month. No action taken.");
        }

        return 0;
    }
}
