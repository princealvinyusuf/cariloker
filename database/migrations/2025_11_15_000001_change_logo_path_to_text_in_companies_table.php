<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        $driver = DB::getDriverName();
        
        if ($driver === 'mysql') {
            // MySQL: Direct ALTER TABLE
            DB::statement('ALTER TABLE companies MODIFY COLUMN logo_path TEXT NULL');
        } elseif ($driver === 'sqlite') {
            // SQLite: Requires table recreation
            DB::statement('PRAGMA foreign_keys=OFF');
            DB::statement('ALTER TABLE companies RENAME TO companies_old');
            
            Schema::create('companies', function (Blueprint $table) {
                $table->id();
                $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
                $table->foreignId('location_id')->nullable()->constrained()->nullOnDelete();
                $table->string('name');
                $table->string('slug')->unique();
                $table->text('logo_path')->nullable();
                $table->string('website_url')->nullable();
                $table->string('industry')->nullable();
                $table->string('size')->nullable();
                $table->unsignedSmallInteger('founded_year')->nullable();
                $table->text('description')->nullable();
                $table->timestamps();
            });
            
            DB::statement('INSERT INTO companies SELECT * FROM companies_old');
            DB::statement('DROP TABLE companies_old');
            DB::statement('PRAGMA foreign_keys=ON');
        } else {
            // PostgreSQL and others
            Schema::table('companies', function (Blueprint $table) {
                $table->text('logo_path')->nullable()->change();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Revert to VARCHAR
        $driver = DB::getDriverName();
        
        if ($driver === 'mysql') {
            DB::statement('ALTER TABLE companies MODIFY COLUMN logo_path VARCHAR(255) NULL');
        } elseif ($driver === 'sqlite') {
            // SQLite: Requires table recreation
            DB::statement('PRAGMA foreign_keys=OFF');
            DB::statement('ALTER TABLE companies RENAME TO companies_old');
            
            Schema::create('companies', function (Blueprint $table) {
                $table->id();
                $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
                $table->foreignId('location_id')->nullable()->constrained()->nullOnDelete();
                $table->string('name');
                $table->string('slug')->unique();
                $table->string('logo_path')->nullable();
                $table->string('website_url')->nullable();
                $table->string('industry')->nullable();
                $table->string('size')->nullable();
                $table->unsignedSmallInteger('founded_year')->nullable();
                $table->text('description')->nullable();
                $table->timestamps();
            });
            
            DB::statement('INSERT INTO companies SELECT * FROM companies_old');
            DB::statement('DROP TABLE companies_old');
            DB::statement('PRAGMA foreign_keys=ON');
        } else {
            Schema::table('companies', function (Blueprint $table) {
                $table->string('logo_path')->nullable()->change();
            });
        }
    }
};

