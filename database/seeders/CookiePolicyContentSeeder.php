<?php

namespace Database\Seeders;

use App\Models\CookiePolicyContent;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CookiePolicyContentSeeder extends Seeder
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
                'value' => 'Cookie Policy',
                'type' => 'text',
                'label' => 'Hero Title',
                'section' => 'hero',
                'order' => 1,
            ],
            [
                'key' => 'hero_description',
                'value' => 'Learn how we use cookies and similar technologies on our website to enhance your browsing experience.',
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
                'value' => 'This Cookie Policy explains how Cari Loker ("we", "us", or "our") uses cookies and similar tracking technologies when you visit our website. By using our website, you consent to the use of cookies in accordance with this policy.',
                'type' => 'textarea',
                'label' => 'Introduction',
                'section' => 'content',
                'order' => 1,
            ],
            
            // What Are Cookies
            [
                'key' => 'what_are_cookies_title',
                'value' => 'What Are Cookies?',
                'type' => 'text',
                'label' => 'What Are Cookies Title',
                'section' => 'content',
                'order' => 2,
            ],
            [
                'key' => 'what_are_cookies',
                'value' => 'Cookies are small text files that are placed on your device when you visit a website. They are widely used to make websites work more efficiently and provide information to website owners. Cookies allow a website to recognize your device and store some information about your preferences or past actions.',
                'type' => 'textarea',
                'label' => 'What Are Cookies Content',
                'section' => 'content',
                'order' => 3,
            ],
            
            // How We Use Cookies
            [
                'key' => 'how_we_use_cookies_title',
                'value' => 'How We Use Cookies',
                'type' => 'text',
                'label' => 'How We Use Cookies Title',
                'section' => 'content',
                'order' => 4,
            ],
            [
                'key' => 'how_we_use_cookies',
                'value' => 'We use cookies to:\n\n• Remember your preferences and settings\n• Analyze how you use our website to improve performance\n• Provide personalized content and job recommendations\n• Track website traffic and user behavior\n• Ensure website security and prevent fraud\n• Enable certain features and functionality',
                'type' => 'textarea',
                'label' => 'How We Use Cookies Content',
                'section' => 'content',
                'order' => 5,
            ],
            
            // Types of Cookies
            [
                'key' => 'types_of_cookies_title',
                'value' => 'Types of Cookies We Use',
                'type' => 'text',
                'label' => 'Types of Cookies Title',
                'section' => 'content',
                'order' => 6,
            ],
            [
                'key' => 'types_of_cookies',
                'value' => 'We use the following types of cookies:\n\n1. Essential Cookies: These are necessary for the website to function properly and cannot be switched off.\n\n2. Performance Cookies: These help us understand how visitors interact with our website by collecting and reporting information anonymously.\n\n3. Functionality Cookies: These allow the website to remember choices you make and provide enhanced features.\n\n4. Targeting Cookies: These are used to deliver relevant advertisements and track campaign effectiveness.',
                'type' => 'textarea',
                'label' => 'Types of Cookies Content',
                'section' => 'content',
                'order' => 7,
            ],
            
            // Managing Cookies
            [
                'key' => 'manage_cookies_title',
                'value' => 'Managing Cookies',
                'type' => 'text',
                'label' => 'Managing Cookies Title',
                'section' => 'content',
                'order' => 8,
            ],
            [
                'key' => 'manage_cookies',
                'value' => 'You can control and manage cookies in various ways. Most web browsers allow you to control cookies through their settings preferences. However, limiting cookies may impact your ability to use certain features of our website.\n\nYou can delete cookies that are already on your device and set your browser to prevent new cookies from being placed. Please note that disabling cookies may affect the functionality of our website.',
                'type' => 'textarea',
                'label' => 'Managing Cookies Content',
                'section' => 'content',
                'order' => 9,
            ],
            
            // Third-Party Cookies
            [
                'key' => 'third_party_cookies_title',
                'value' => 'Third-Party Cookies',
                'type' => 'text',
                'label' => 'Third-Party Cookies Title',
                'section' => 'content',
                'order' => 10,
            ],
            [
                'key' => 'third_party_cookies',
                'value' => 'Some cookies on our website are set by third-party services such as Google Analytics, which help us analyze website traffic and user behavior. These third parties may use cookies to collect information about your online activities across different websites.',
                'type' => 'textarea',
                'label' => 'Third-Party Cookies Content',
                'section' => 'content',
                'order' => 11,
            ],
            
            // Contact Information
            [
                'key' => 'contact_info_title',
                'value' => 'Contact Us',
                'type' => 'text',
                'label' => 'Contact Info Title',
                'section' => 'content',
                'order' => 12,
            ],
            [
                'key' => 'contact_info',
                'value' => 'If you have any questions about our use of cookies, please contact us at info@cariloker.com.',
                'type' => 'textarea',
                'label' => 'Contact Info Content',
                'section' => 'content',
                'order' => 13,
            ],
            
            // Conclusion
            [
                'key' => 'conclusion',
                'value' => 'We may update this Cookie Policy from time to time. Any changes will be posted on this page with an updated revision date. We encourage you to review this policy periodically to stay informed about how we use cookies.',
                'type' => 'textarea',
                'label' => 'Conclusion',
                'section' => 'content',
                'order' => 14,
            ],
        ];

        foreach ($contents as $content) {
            CookiePolicyContent::updateOrCreate(
                ['key' => $content['key']],
                $content
            );
        }
    }
}
