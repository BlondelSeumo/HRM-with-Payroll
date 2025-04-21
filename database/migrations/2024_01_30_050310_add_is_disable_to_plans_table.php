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
        if (!Schema::hasColumn('plans', 'is_disable')) {

            Schema::table('plans', function (Blueprint $table) {
                $table->integer('is_disable')->default(1)->after('trial_days');     
                $table->decimal('price', 30, 2)->nullable()->default(0.0)->change();                       
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('plans', function (Blueprint $table) {
            //
        });
    }
};
