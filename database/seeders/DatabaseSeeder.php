<?php

namespace Database\Seeders;

use App\Models\Application;
use App\Models\Company;
use App\Models\Job;
use App\Models\JobCategory;
use App\Models\Location;
use App\Models\Skill;
use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Users
        $admin = User::factory()->create([
            'name' => 'Admin',
            'email' => 'admin@cariloker.test',
            'role' => 'admin',
            'password' => 'password',
        ]);

        $employer = User::factory()->create([
            'name' => 'Employer',
            'email' => 'employer@cariloker.test',
            'role' => 'employer',
            'password' => 'password',
        ]);

        $candidate = User::factory()->create([
            'name' => 'Candidate',
            'email' => 'candidate@cariloker.test',
            'role' => 'candidate',
            'password' => 'password',
        ]);

        // Taxonomies
        $locations = Location::factory()->count(8)->create();
        $categories = JobCategory::factory()->count(8)->create();
        $skills = Skill::factory()->count(15)->create();

        // Companies
        $companies = Company::factory()->count(12)->create([
            'user_id' => $employer->id,
            'location_id' => $locations->random()->id,
        ]);

        // Jobs
        $companies->each(function (Company $company) use ($categories, $skills, $locations) {
            Job::factory()->count(rand(3, 8))->create([
                'company_id' => $company->id,
                'category_id' => $categories->random()->id,
                'location_id' => $locations->random()->id,
            ])->each(function (Job $job) use ($skills) {
                $job->skills()->sync($skills->random(rand(3, 6))->pluck('id'));
            });
        });

        // About Page Content
        $this->call(AboutPageContentSeeder::class);
        
        // FAQ Content
        $this->call(FaqSeeder::class);
        
        // Cookie Policy Content
        $this->call(CookiePolicyContentSeeder::class);
        
        // Terms of Service Content
        $this->call(TermsOfServiceContentSeeder::class);
        
        // Privacy Policy Content
        $this->call(PrivacyPolicyContentSeeder::class);
        
        // Blog Posts
        $this->call(BlogPostSeeder::class);
    }
}
