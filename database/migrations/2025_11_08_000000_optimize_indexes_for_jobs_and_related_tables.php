<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        // Jobs table
        Schema::table('jobs', function (Blueprint $table) {
            $table->index('status');
            $table->index('category_id');
            $table->index('company_id');
            $table->index('location_id');
            $table->index('employment_type');
            $table->index('is_remote');
            $table->index('salary_min');
            $table->index('salary_max');
            $table->index('work_arrangement');
            $table->index('created_at');
            $table->index('education_level');
        });
        // Job categories
        Schema::table('job_categories', function (Blueprint $table) {
            $table->index('slug');
        });
        // Companies
        Schema::table('companies', function (Blueprint $table) {
            $table->index('industry');
            $table->index('size');
        });
    }
    public function down(): void {
        // Jobs table
        Schema::table('jobs', function (Blueprint $table) {
            $table->dropIndex(['status']);
            $table->dropIndex(['category_id']);
            $table->dropIndex(['company_id']);
            $table->dropIndex(['location_id']);
            $table->dropIndex(['employment_type']);
            $table->dropIndex(['is_remote']);
            $table->dropIndex(['salary_min']);
            $table->dropIndex(['salary_max']);
            $table->dropIndex(['work_arrangement']);
            $table->dropIndex(['created_at']);
            $table->dropIndex(['education_level']);
        });
        // Job categories
        Schema::table('job_categories', function (Blueprint $table) {
            $table->dropIndex(['slug']);
        });
        // Companies
        Schema::table('companies', function (Blueprint $table) {
            $table->dropIndex(['industry']);
            $table->dropIndex(['size']);
        });
    }
};
