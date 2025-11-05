<?php

namespace Database\Seeders;

use App\Models\TermsOfServiceContent;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class TermsOfServiceContentSeeder extends Seeder
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
                'value' => 'Terms of Service',
                'type' => 'text',
                'label' => 'Hero Title',
                'section' => 'hero',
                'order' => 1,
            ],
            [
                'key' => 'hero_description',
                'value' => 'Please read these terms carefully before using our platform. By accessing or using Cari Loker, you agree to be bound by these Terms of Service.',
                'type' => 'textarea',
                'label' => 'Hero Description',
                'section' => 'hero',
                'order' => 2,
            ],
            [
                'key' => 'last_updated',
                'value' => date('F j, Y'),
                'type' => 'text',
                'label' => 'Last Updated Date',
                'section' => 'hero',
                'order' => 3,
            ],
            
            // Introduction
            [
                'key' => 'introduction',
                'value' => 'Welcome to Cari Loker ("we", "us", or "our"). These Terms of Service ("Terms") govern your access to and use of our website and services. By using our platform, you agree to comply with and be bound by these Terms.',
                'type' => 'textarea',
                'label' => 'Introduction',
                'section' => 'content',
                'order' => 1,
            ],
            
            // Acceptance of Terms
            [
                'key' => 'acceptance_of_terms_title',
                'value' => 'Acceptance of Terms',
                'type' => 'text',
                'label' => 'Acceptance of Terms Title',
                'section' => 'content',
                'order' => 2,
            ],
            [
                'key' => 'acceptance_of_terms',
                'value' => 'By accessing or using Cari Loker, you agree to be bound by these Terms of Service and all applicable laws and regulations. If you do not agree with any of these terms, you are prohibited from using or accessing this platform.',
                'type' => 'textarea',
                'label' => 'Acceptance of Terms Content',
                'section' => 'content',
                'order' => 3,
            ],
            
            // Use of Service
            [
                'key' => 'use_of_service_title',
                'value' => 'Use of Service',
                'type' => 'text',
                'label' => 'Use of Service Title',
                'section' => 'content',
                'order' => 4,
            ],
            [
                'key' => 'use_of_service',
                'value' => 'Cari Loker provides an online platform for job seekers and employers to connect. You may use our service to:\n\n• Browse and search for job opportunities\n• Create a profile and apply for jobs\n• Post job listings (for employers)\n• Manage job applications\n\nYou must use our service in compliance with all applicable laws and regulations.',
                'type' => 'textarea',
                'label' => 'Use of Service Content',
                'section' => 'content',
                'order' => 5,
            ],
            
            // User Accounts
            [
                'key' => 'user_accounts_title',
                'value' => 'User Accounts',
                'type' => 'text',
                'label' => 'User Accounts Title',
                'section' => 'content',
                'order' => 6,
            ],
            [
                'key' => 'user_accounts',
                'value' => 'To access certain features of our platform, you may need to create an account. You are responsible for:\n\n• Maintaining the confidentiality of your account credentials\n• All activities that occur under your account\n• Providing accurate and up-to-date information\n• Notifying us immediately of any unauthorized use\n\nWe reserve the right to suspend or terminate accounts that violate these Terms.',
                'type' => 'textarea',
                'label' => 'User Accounts Content',
                'section' => 'content',
                'order' => 7,
            ],
            
            // Job Postings
            [
                'key' => 'job_postings_title',
                'value' => 'Job Postings and Applications',
                'type' => 'text',
                'label' => 'Job Postings Title',
                'section' => 'content',
                'order' => 8,
            ],
            [
                'key' => 'job_postings',
                'value' => 'Employers are responsible for the accuracy of job postings and compliance with all applicable employment laws. Job seekers are responsible for the accuracy of their applications and resumes. We do not guarantee job placements or employment outcomes.',
                'type' => 'textarea',
                'label' => 'Job Postings Content',
                'section' => 'content',
                'order' => 9,
            ],
            
            // Intellectual Property
            [
                'key' => 'intellectual_property_title',
                'value' => 'Intellectual Property',
                'type' => 'text',
                'label' => 'Intellectual Property Title',
                'section' => 'content',
                'order' => 10,
            ],
            [
                'key' => 'intellectual_property',
                'value' => 'All content on Cari Loker, including text, graphics, logos, and software, is the property of Cari Loker or its content suppliers and is protected by copyright and other intellectual property laws. You may not reproduce, distribute, or create derivative works without our written permission.',
                'type' => 'textarea',
                'label' => 'Intellectual Property Content',
                'section' => 'content',
                'order' => 11,
            ],
            
            // User Conduct
            [
                'key' => 'user_conduct_title',
                'value' => 'User Conduct',
                'type' => 'text',
                'label' => 'User Conduct Title',
                'section' => 'content',
                'order' => 12,
            ],
            [
                'key' => 'user_conduct',
                'value' => 'You agree not to:\n\n• Post false, misleading, or fraudulent information\n• Harass, abuse, or harm other users\n• Violate any applicable laws or regulations\n• Use automated systems to access the platform\n• Interfere with the platform\'s security or functionality\n• Transmit viruses or malicious code',
                'type' => 'textarea',
                'label' => 'User Conduct Content',
                'section' => 'content',
                'order' => 13,
            ],
            
            // Privacy
            [
                'key' => 'privacy_title',
                'value' => 'Privacy and Data Protection',
                'type' => 'text',
                'label' => 'Privacy Title',
                'section' => 'content',
                'order' => 14,
            ],
            [
                'key' => 'privacy',
                'value' => 'Your use of our platform is also governed by our Privacy Policy. By using Cari Loker, you consent to the collection and use of your information as described in our Privacy Policy.',
                'type' => 'textarea',
                'label' => 'Privacy Content',
                'section' => 'content',
                'order' => 15,
            ],
            
            // Limitation of Liability
            [
                'key' => 'limitation_of_liability_title',
                'value' => 'Limitation of Liability',
                'type' => 'text',
                'label' => 'Limitation of Liability Title',
                'section' => 'content',
                'order' => 16,
            ],
            [
                'key' => 'limitation_of_liability',
                'value' => 'Cari Loker is provided "as is" without warranties of any kind. We are not responsible for:\n\n• The accuracy of job postings or user profiles\n• Employment decisions or outcomes\n• Communications between users\n• Technical issues or service interruptions\n\nTo the maximum extent permitted by law, we disclaim all warranties and limit our liability.',
                'type' => 'textarea',
                'label' => 'Limitation of Liability Content',
                'section' => 'content',
                'order' => 17,
            ],
            
            // Termination
            [
                'key' => 'termination_title',
                'value' => 'Termination',
                'type' => 'text',
                'label' => 'Termination Title',
                'section' => 'content',
                'order' => 18,
            ],
            [
                'key' => 'termination',
                'value' => 'We reserve the right to suspend or terminate your access to Cari Loker at any time, with or without cause or notice, for any reason including violation of these Terms.',
                'type' => 'textarea',
                'label' => 'Termination Content',
                'section' => 'content',
                'order' => 19,
            ],
            
            // Changes to Terms
            [
                'key' => 'changes_to_terms_title',
                'value' => 'Changes to Terms',
                'type' => 'text',
                'label' => 'Changes to Terms Title',
                'section' => 'content',
                'order' => 20,
            ],
            [
                'key' => 'changes_to_terms',
                'value' => 'We may update these Terms of Service from time to time. We will notify you of any material changes by posting the new Terms on this page and updating the "Last Updated" date. Your continued use of the platform after such changes constitutes your acceptance of the new Terms.',
                'type' => 'textarea',
                'label' => 'Changes to Terms Content',
                'section' => 'content',
                'order' => 21,
            ],
            
            // Contact Information
            [
                'key' => 'contact_info_title',
                'value' => 'Contact Us',
                'type' => 'text',
                'label' => 'Contact Info Title',
                'section' => 'content',
                'order' => 22,
            ],
            [
                'key' => 'contact_info',
                'value' => 'If you have any questions about these Terms of Service, please contact us at info@cariloker.com.',
                'type' => 'textarea',
                'label' => 'Contact Info Content',
                'section' => 'content',
                'order' => 23,
            ],
            
            // Conclusion
            [
                'key' => 'conclusion',
                'value' => 'These Terms of Service constitute the entire agreement between you and Cari Loker regarding the use of our platform. If any provision of these Terms is found to be unenforceable, the remaining provisions will remain in full effect.',
                'type' => 'textarea',
                'label' => 'Conclusion',
                'section' => 'content',
                'order' => 24,
            ],
        ];

        foreach ($contents as $content) {
            TermsOfServiceContent::updateOrCreate(
                ['key' => $content['key']],
                $content
            );
        }
    }
}
