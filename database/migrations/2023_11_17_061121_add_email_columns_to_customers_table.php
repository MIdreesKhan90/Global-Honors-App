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
        Schema::table('customers', function (Blueprint $table) {
            $table->boolean('offers_notification')->default(0)->after('policy_agreement');
            $table->boolean('deadlines_notification')->default(0)->after('offers_notification');
            $table->boolean('awards_notification')->default(0)->after('deadlines_notification');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('customers', function (Blueprint $table) {
            $table->dropColumn('offers_notification');
            $table->dropColumn('deadlines_notification');
            $table->dropColumn('awards_notification');
        });
    }
};
