<?php

namespace Database\Seeders;

use App\Models\PrivacyPolicyContent;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PrivacyPolicyContentSeeder extends Seeder
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
                'value' => 'Privacy Policy',
                'type' => 'text',
                'label' => 'Hero Title',
                'section' => 'hero',
                'order' => 1,
            ],
            [
                'key' => 'hero_description',
                'value' => 'Your privacy is important to us. This Privacy Policy explains how we collect, use, disclose, and safeguard your information when you use our platform.',
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
                'value' => 'Cari Loker ("we", "us", or "our") is committed to protecting your privacy. This Privacy Policy describes how we collect, use, and share your personal information when you use our job search platform.',
                'type' => 'textarea',
                'label' => 'Introduction',
                'section' => 'content',
                'order' => 1,
            ],
            
            // Information We Collect
            [
                'key' => 'information_we_collect_title',
                'value' => 'Information We Collect',
                'type' => 'text',
                'label' => 'Information We Collect Title',
                'section' => 'content',
                'order' => 2,
            ],
            [
                'key' => 'information_we_collect',
                'value' => 'We collect information that you provide directly to us, including:\n\n• Personal information (name, email address, phone number)\n• Resume and career information\n• Job application history\n• Account credentials\n• Profile information\n\nWe also automatically collect certain information when you use our platform, such as:\n\n• IP address and device information\n• Browser type and version\n• Usage data and analytics\n• Cookies and similar tracking technologies',
                'type' => 'textarea',
                'label' => 'Information We Collect Content',
                'section' => 'content',
                'order' => 3,
            ],
            
            // How We Use
            [
                'key' => 'how_we_use_title',
                'value' => 'How We Use Your Information',
                'type' => 'text',
                'label' => 'How We Use Title',
                'section' => 'content',
                'order' => 4,
            ],
            [
                'key' => 'how_we_use',
                'value' => 'We use the information we collect to:\n\n• Provide, maintain, and improve our services\n• Match job seekers with relevant opportunities\n• Process job applications\n• Send you job alerts and notifications\n• Communicate with you about our services\n• Analyze usage patterns and improve user experience\n• Detect and prevent fraud or abuse\n• Comply with legal obligations',
                'type' => 'textarea',
                'label' => 'How We Use Content',
                'section' => 'content',
                'order' => 5,
            ],
            
            // Information Sharing
            [
                'key' => 'information_sharing_title',
                'value' => 'Information Sharing and Disclosure',
                'type' => 'text',
                'label' => 'Information Sharing Title',
                'section' => 'content',
                'order' => 6,
            ],
            [
                'key' => 'information_sharing',
                'value' => 'We may share your information with:\n\n• Employers: When you apply for a job, we share your application and resume with the employer\n• Service Providers: Third-party companies that help us operate our platform\n• Legal Requirements: When required by law or to protect our rights\n• Business Transfers: In connection with any merger, sale, or acquisition\n\nWe do not sell your personal information to third parties for their marketing purposes.',
                'type' => 'textarea',
                'label' => 'Information Sharing Content',
                'section' => 'content',
                'order' => 7,
            ],
            
            // Data Security
            [
                'key' => 'data_security_title',
                'value' => 'Data Security',
                'type' => 'text',
                'label' => 'Data Security Title',
                'section' => 'content',
                'order' => 8,
            ],
            [
                'key' => 'data_security',
                'value' => 'We implement appropriate technical and organizational measures to protect your personal information against unauthorized access, alteration, disclosure, or destruction. However, no method of transmission over the Internet is 100% secure, and we cannot guarantee absolute security.',
                'type' => 'textarea',
                'label' => 'Data Security Content',
                'section' => 'content',
                'order' => 9,
            ],
            
            // Your Rights
            [
                'key' => 'your_rights_title',
                'value' => 'Your Rights',
                'type' => 'text',
                'label' => 'Your Rights Title',
                'section' => 'content',
                'order' => 10,
            ],
            [
                'key' => 'your_rights',
                'value' => 'You have the right to:\n\n• Access your personal information\n• Correct inaccurate or incomplete information\n• Delete your personal information\n• Object to processing of your information\n• Request data portability\n• Withdraw consent at any time\n\nTo exercise these rights, please contact us using the information provided below.',
                'type' => 'textarea',
                'label' => 'Your Rights Content',
                'section' => 'content',
                'order' => 11,
            ],
            
            // Cookies
            [
                'key' => 'cookies_title',
                'value' => 'Cookies and Tracking Technologies',
                'type' => 'text',
                'label' => 'Cookies Title',
                'section' => 'content',
                'order' => 12,
            ],
            [
                'key' => 'cookies',
                'value' => 'We use cookies and similar tracking technologies to collect and use information about you. For more information about how we use cookies, please see our Cookie Policy.',
                'type' => 'textarea',
                'label' => 'Cookies Content',
                'section' => 'content',
                'order' => 13,
            ],
            
            // Third-Party Services
            [
                'key' => 'third_party_services_title',
                'value' => 'Third-Party Services',
                'type' => 'text',
                'label' => 'Third-Party Services Title',
                'section' => 'content',
                'order' => 14,
            ],
            [
                'key' => 'third_party_services',
                'value' => 'Our platform may contain links to third-party websites or services. We are not responsible for the privacy practices of these third parties. We encourage you to read their privacy policies before providing any information.',
                'type' => 'textarea',
                'label' => 'Third-Party Services Content',
                'section' => 'content',
                'order' => 15,
            ],
            
            // Children's Privacy
            [
                'key' => 'children_privacy_title',
                'value' => "Children's Privacy",
                'type' => 'text',
                'label' => "Children's Privacy Title",
                'section' => 'content',
                'order' => 16,
            ],
            [
                'key' => 'children_privacy',
                'value' => 'Our services are not intended for individuals under the age of 18. We do not knowingly collect personal information from children. If you believe we have collected information from a child, please contact us immediately.',
                'type' => 'textarea',
                'label' => "Children's Privacy Content",
                'section' => 'content',
                'order' => 17,
            ],
            
            // Policy Changes
            [
                'key' => 'policy_changes_title',
                'value' => 'Changes to This Privacy Policy',
                'type' => 'text',
                'label' => 'Policy Changes Title',
                'section' => 'content',
                'order' => 18,
            ],
            [
                'key' => 'policy_changes',
                'value' => 'We may update this Privacy Policy from time to time. We will notify you of any material changes by posting the new Privacy Policy on this page and updating the "Last Updated" date. Your continued use of our platform after such changes constitutes your acceptance of the new Privacy Policy.',
                'type' => 'textarea',
                'label' => 'Policy Changes Content',
                'section' => 'content',
                'order' => 19,
            ],
            
            // Contact Information
            [
                'key' => 'contact_info_title',
                'value' => 'Contact Us',
                'type' => 'text',
                'label' => 'Contact Info Title',
                'section' => 'content',
                'order' => 20,
            ],
            [
                'key' => 'contact_info',
                'value' => 'If you have any questions about this Privacy Policy or our privacy practices, please contact us at info@cariloker.com.',
                'type' => 'textarea',
                'label' => 'Contact Info Content',
                'section' => 'content',
                'order' => 21,
            ],
            
            // Conclusion
            [
                'key' => 'conclusion',
                'value' => 'By using Cari Loker, you acknowledge that you have read and understood this Privacy Policy. If you do not agree with this policy, please do not use our platform.',
                'type' => 'textarea',
                'label' => 'Conclusion',
                'section' => 'content',
                'order' => 22,
            ],
        ];

        foreach ($contents as $content) {
            PrivacyPolicyContent::updateOrCreate(
                ['key' => $content['key']],
                $content
            );
        }
    }
}
