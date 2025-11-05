<?php

namespace Database\Seeders;

use App\Models\Faq;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class FaqSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faqs = [
            [
                'question' => 'How do I search for jobs on Cari Loker?',
                'answer' => 'You can search for jobs by using the search bar on our homepage. Enter keywords related to the job title, company name, or skills you\'re looking for. You can also filter results by location, job type, salary range, and experience level.',
                'order' => 1,
                'is_active' => true,
            ],
            [
                'question' => 'Do I need to create an account to apply for jobs?',
                'answer' => 'While you can browse jobs without an account, creating a free account allows you to save job listings, apply for positions, upload your resume, and receive job recommendations based on your profile.',
                'order' => 2,
                'is_active' => true,
            ],
            [
                'question' => 'How do I apply for a job?',
                'answer' => 'Once you find a job you\'re interested in, click on the "Apply Now" button. If you have an account, you can apply directly through our platform. Some jobs may redirect you to the employer\'s website for application.',
                'order' => 3,
                'is_active' => true,
            ],
            [
                'question' => 'Is it free to use Cari Loker?',
                'answer' => 'Yes, Cari Loker is completely free for job seekers. There are no hidden fees or charges for browsing jobs, creating an account, or applying for positions.',
                'order' => 4,
                'is_active' => true,
            ],
            [
                'question' => 'How often are new jobs posted?',
                'answer' => 'New jobs are posted daily by employers. We recommend checking back regularly or creating an account to receive notifications about new opportunities that match your profile.',
                'order' => 5,
                'is_active' => true,
            ],
            [
                'question' => 'Can I save jobs for later?',
                'answer' => 'Yes! If you have an account, you can save jobs by clicking the heart icon on any job listing. You can view all your saved jobs in your profile dashboard.',
                'order' => 6,
                'is_active' => true,
            ],
            [
                'question' => 'How do I update my profile?',
                'answer' => 'After logging in, go to your profile page. You can update your personal information, upload or update your resume, and modify your job preferences at any time.',
                'order' => 7,
                'is_active' => true,
            ],
            [
                'question' => 'What types of jobs are available?',
                'answer' => 'Cari Loker features jobs across various industries including technology, finance, healthcare, education, marketing, and more. We list full-time, part-time, contract, and remote positions.',
                'order' => 8,
                'is_active' => true,
            ],
        ];

        foreach ($faqs as $faq) {
            Faq::updateOrCreate(
                ['question' => $faq['question']],
                $faq
            );
        }
    }
}
