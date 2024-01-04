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
        Schema::table('capture_linkedins', function (Blueprint $table) {
            $table->string('entreprise')->nullable();
            $table->string('lieu')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('capture_linkedins', function (Blueprint $table) {
            $table->dropColumn('entreprise');
            $table->dropColumn('lieu');
        });
    }
};
