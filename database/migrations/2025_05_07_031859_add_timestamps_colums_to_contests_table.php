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
        Schema::table('contests', function (Blueprint $table): void {
            $table->dateTime('started_at');
            $table->dateTime('ended_at');
            $table->boolean('is_ended')->default(false);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('contests', function (Blueprint $table) {
            $table->dropColumn('started_at');
            $table->dropColumn('ended_at');
            $table->dropColumn('is_ended');
        });
    }
};
