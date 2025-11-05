<?php

namespace Database\Seeders;

use App\Models\AboutPageContent;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class AboutPageContentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $contents = [
            // Hero Section
            [
                'key' => 'hero_title',
                'value' => 'About Cari Loker',
                'type' => 'text',
                'label' => 'Hero Title',
                'section' => 'hero',
                'order' => 1,
            ],
            [
                'key' => 'hero_description',
                'value' => 'Your trusted platform for finding the perfect job opportunity. We connect talented job seekers with top employers.',
                'type' => 'textarea',
                'label' => 'Hero Description',
                'section' => 'hero',
                'order' => 2,
            ],
            
            // About Section
            [
                'key' => 'about_title',
                'value' => 'About Us',
                'type' => 'text',
                'label' => 'About Title',
                'section' => 'about',
                'order' => 1,
            ],
            [
                'key' => 'about_content',
                'value' => 'Cari Loker is a leading job portal in Indonesia, dedicated to connecting job seekers with their dream careers. Our platform provides a seamless experience for both job seekers and employers, making the job search process efficient and effective.

We understand that finding the right job can be challenging, which is why we have built a comprehensive platform that offers:
- Thousands of job opportunities from top companies
- Advanced search and filtering options
- Easy application process
- Career resources and tips
- User-friendly interface

Our team is committed to helping you achieve your career goals.',
                'type' => 'textarea',
                'label' => 'About Content',
                'section' => 'about',
                'order' => 2,
            ],
            [
                'key' => 'mission_title',
                'value' => 'Our Mission',
                'type' => 'text',
                'label' => 'Mission Title',
                'section' => 'about',
                'order' => 3,
            ],
            [
                'key' => 'mission_content',
                'value' => 'To empower individuals in their career journey by providing the best job opportunities and resources, while helping employers find the perfect talent for their organizations.',
                'type' => 'textarea',
                'label' => 'Mission Content',
                'section' => 'about',
                'order' => 4,
            ],
            [
                'key' => 'vision_title',
                'value' => 'Our Vision',
                'type' => 'text',
                'label' => 'Vision Title',
                'section' => 'about',
                'order' => 5,
            ],
            [
                'key' => 'vision_content',
                'value' => 'To become the leading job portal in Indonesia, connecting millions of job seekers with opportunities that match their skills and aspirations.',
                'type' => 'textarea',
                'label' => 'Vision Content',
                'section' => 'about',
                'order' => 6,
            ],
            
            // Contact Section
            [
                'key' => 'contact_title',
                'value' => 'Contact Us',
                'type' => 'text',
                'label' => 'Contact Title',
                'section' => 'contact',
                'order' => 1,
            ],
            [
                'key' => 'contact_description',
                'value' => 'Get in touch with us. We are here to help you with any questions or concerns about our platform.',
                'type' => 'textarea',
                'label' => 'Contact Description',
                'section' => 'contact',
                'order' => 2,
            ],
            [
                'key' => 'contact_email',
                'value' => 'info@cariloker.com',
                'type' => 'text',
                'label' => 'Contact Email',
                'section' => 'contact',
                'order' => 3,
            ],
            [
                'key' => 'contact_phone',
                'value' => '+62 123 456 7890',
                'type' => 'text',
                'label' => 'Contact Phone',
                'section' => 'contact',
                'order' => 4,
            ],
            [
                'key' => 'contact_address',
                'value' => 'Jakarta, Indonesia',
                'type' => 'text',
                'label' => 'Contact Address',
                'section' => 'contact',
                'order' => 5,
            ],
            [
                'key' => 'contact_hours',
                'value' => 'Monday - Friday: 9:00 AM - 6:00 PM',
                'type' => 'text',
                'label' => 'Business Hours',
                'section' => 'contact',
                'order' => 6,
            ],
        ];

        foreach ($contents as $content) {
            AboutPageContent::updateOrCreate(
                ['key' => $content['key']],
                $content
            );
        }
    }
}
