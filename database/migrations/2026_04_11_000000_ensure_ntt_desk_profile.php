<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Models\User;
use App\Models\UserDetail;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Find NTT Desk user
        $user = User::where('firstname', 'NTT')
            ->where('lastname', 'Desk')
            ->first();

        // If not found by name, try finding by specific ID from yesterday
        if (!$user) {
            $user = User::find(397);
        }

        if ($user) {
            // Assign Reporter role if Spatie is available
            if (method_exists($user, 'assignRole')) {
                $user->assignRole('Reporter');
            }

            // Ensure profile details exist
            $details = UserDetail::where('user_id', $user->id)->first();
            if (!$details) {
                $details = new UserDetail();
                $details->user_id = $user->id;
            }

            $details->designation = 'Official News Desk';
            $details->bio = 'The official News The Truth editorial desk. Covering breaking news, fact-checks, and in-depth analysis from our global team.';
            $details->save();
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
