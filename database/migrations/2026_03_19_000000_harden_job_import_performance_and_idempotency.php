<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('job_listings', function (Blueprint $table) {
            $table->string('source_hash', 64)->nullable()->after('slug');
            $table->unique('source_hash', 'job_listings_source_hash_unique');
        });

        Schema::table('locations', function (Blueprint $table) {
            $table->index(['city', 'state', 'country'], 'locations_city_state_country_index');
        });
    }

    public function down(): void
    {
        Schema::table('locations', function (Blueprint $table) {
            $table->dropIndex('locations_city_state_country_index');
        });

        Schema::table('job_listings', function (Blueprint $table) {
            $table->dropUnique('job_listings_source_hash_unique');
            $table->dropColumn('source_hash');
        });
    }
};
