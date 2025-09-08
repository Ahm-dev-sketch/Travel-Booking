<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('jadwals', function (Blueprint $table) {
            if (Schema::hasColumn('jadwals', 'kota_awal')) {
                $table->dropColumn('kota_awal');
            }
            if (Schema::hasColumn('jadwals', 'kota_tujuan')) {
                $table->dropColumn('kota_tujuan');
            }
        });
    }

    public function down(): void
    {
        Schema::table('jadwals', function (Blueprint $table) {
            $table->string('kota_awal')->nullable();
            $table->string('kota_tujuan')->nullable();
        });
    }
};
