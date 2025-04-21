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
        if (Schema::hasTable('jobs') && !Schema::hasColumn('jobs', 'terms_and_conditions')) {
            Schema::table('jobs', function (Blueprint $table) {
                $table->text('terms_and_conditions')->nullable()->after('requirement');
            });
        }

        if (Schema::hasTable('job_applications') && !Schema::hasColumn('job_applications', 'terms_condition_check')) {
            Schema::table('job_applications', function (Blueprint $table) {
                $table->string('terms_condition_check')->nullable()->after('custom_question');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('jobs', function (Blueprint $table) {
            //
        });

        Schema::table('job_applications', function (Blueprint $table) {
            //
        });
    }
};
