<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        if (!Schema::hasColumn('users', 'trial_expire_date')) {
            Schema::table('users', function (Blueprint $table) {
                $table->date('trial_expire_date')->nullable()->after('requested_plan');
                $table->integer('trial_plan')->default(0)->after('trial_expire_date');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            //
        });
    }
};
