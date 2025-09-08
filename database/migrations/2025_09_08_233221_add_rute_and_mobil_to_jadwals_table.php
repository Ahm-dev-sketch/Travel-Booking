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
        Schema::table('jadwals', function (Blueprint $table) {
            $table->unsignedBigInteger('rute_id')->nullable()->after('id');
            $table->unsignedBigInteger('mobil_id')->nullable()->after('rute_id');
            $table->foreign('rute_id')
                ->references('id')
                ->on('rutes')
                ->onDelete('cascade');

            $table->foreign('mobil_id')
                ->references('id')
                ->on('mobils')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('jadwals', function (Blueprint $table) {
            $table->dropForeign(['rute_id']);
            $table->dropForeign(['mobil_id']);
            $table->dropColumn(['rute_id', 'mobil_id']);
        });
    }
};
