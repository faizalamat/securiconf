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
        Schema::table('attendees', function (Blueprint $table) {
            $table->string('passport_photo')->nullable()->after('photo');
            $table->string('passport_number')->nullable()->after('passport_photo');
            $table->string('passport_expiry_date')->nullable()->after('passport_number');
            $table->string('passport_country')->nullable()->after('passport_expiry_date');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('attendees', function (Blueprint $table) {
            $table->dropColumn('passport_photo');
            $table->dropColumn('passport_number');
            $table->dropColumn('passport_expiry_date');
            $table->dropColumn('passport_country');
        });
    }
};
