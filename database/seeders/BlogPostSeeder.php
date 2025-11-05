<?php

namespace Database\Seeders;

use App\Models\BlogPost;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class BlogPostSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get admin user or create one if doesn't exist
        $admin = User::where('role', 'admin')->first();
        if (!$admin) {
            $admin = User::factory()->create([
                'name' => 'Admin',
                'email' => 'admin@cariloker.test',
                'role' => 'admin',
            ]);
        }

        $posts = [
            [
                'title' => '10 Tips for Writing a Winning Resume',
                'excerpt' => 'Learn how to create a resume that stands out to employers and gets you noticed in today\'s competitive job market.',
                'content' => 'A well-crafted resume is your first impression with potential employers. Here are 10 essential tips to make your resume shine:

1. **Tailor Your Resume** - Customize your resume for each job application. Highlight relevant skills and experiences that match the job description.

2. **Use Action Verbs** - Start bullet points with strong action verbs like "achieved," "managed," "developed," or "implemented."

3. **Quantify Your Achievements** - Use numbers and metrics to show your impact. Instead of "improved sales," write "increased sales by 25% in Q3."

4. **Keep It Concise** - Aim for 1-2 pages maximum. Recruiters spend only seconds scanning resumes, so make every word count.

5. **Include Keywords** - Use relevant keywords from the job posting to pass through Applicant Tracking Systems (ATS).

6. **Professional Formatting** - Use clean, professional formatting with consistent fonts and spacing. Avoid overly creative designs unless you\'re in a creative field.

7. **Highlight Relevant Skills** - Place your most relevant skills near the top, especially if they match the job requirements.

8. **Include a Summary** - A brief professional summary (2-3 sentences) at the top can quickly communicate your value proposition.

9. **Proofread Carefully** - Errors can immediately disqualify you. Have someone else review your resume for typos and grammar mistakes.

10. **Update Regularly** - Keep your resume current with your latest experiences and skills, even when you\'re not actively job searching.',
                'status' => 'published',
                'published_at' => now()->subDays(5),
            ],
            [
                'title' => 'How to Ace Your Job Interview: A Complete Guide',
                'excerpt' => 'Master the art of interviewing with our comprehensive guide covering preparation, common questions, and follow-up strategies.',
                'content' => 'Job interviews can be nerve-wracking, but with proper preparation, you can confidently showcase your skills and land your dream job. Here\'s your complete guide:

**Before the Interview:**

- Research the company thoroughly - understand their mission, values, and recent news
- Review the job description and prepare examples of how your experience matches
- Prepare questions to ask the interviewer
- Plan your route and arrive 10-15 minutes early
- Prepare your outfit the night before

**Common Interview Questions:**

1. **"Tell me about yourself"** - Give a 2-minute summary focusing on relevant professional experience
2. **"Why do you want this job?"** - Connect your goals with the company\'s needs
3. **"What are your strengths?"** - Provide examples that relate to the job
4. **"What are your weaknesses?"** - Be honest but show how you\'re working to improve
5. **"Where do you see yourself in 5 years?"** - Show ambition aligned with company growth

**During the Interview:**

- Maintain eye contact and positive body language
- Listen carefully and ask for clarification if needed
- Use the STAR method (Situation, Task, Action, Result) for behavioral questions
- Show enthusiasm for the role and company

**After the Interview:**

- Send a thank-you email within 24 hours
- Follow up if you haven\'t heard back within the expected timeframe
- Reflect on what went well and what you could improve

Remember, an interview is a two-way conversation. Use it to determine if the company is the right fit for you too!',
                'status' => 'published',
                'published_at' => now()->subDays(3),
            ],
            [
                'title' => 'Remote Work Best Practices: Thriving in a Virtual Environment',
                'excerpt' => 'Discover strategies for maintaining productivity, work-life balance, and professional growth while working remotely.',
                'content' => 'Remote work has become the new norm for many professionals. Here are best practices to help you thrive in a virtual work environment:

**Setting Up Your Workspace:**

- Designate a dedicated workspace free from distractions
- Invest in ergonomic furniture and proper lighting
- Ensure reliable internet connection and necessary tools
- Keep your workspace organized and professional-looking

**Time Management:**

- Establish a consistent daily routine
- Use time-blocking techniques to structure your day
- Set clear boundaries between work and personal time
- Take regular breaks to avoid burnout

**Communication:**

- Over-communicate with your team to stay connected
- Use video calls for important discussions
- Update your status regularly on communication platforms
- Document important decisions and conversations

**Staying Productive:**

- Dress for work to maintain a professional mindset
- Minimize distractions by turning off notifications
- Use productivity tools and techniques like the Pomodoro method
- Set daily goals and track your progress

**Professional Development:**

- Continue learning through online courses and webinars
- Network virtually through professional platforms
- Seek feedback regularly from supervisors and peers
- Stay updated with industry trends and developments

**Work-Life Balance:**

- Set clear start and end times for your workday
- Take time off when needed to recharge
- Maintain social connections outside of work
- Practice self-care and maintain healthy habits

Remote work offers flexibility, but it requires discipline and proactive management of your time and relationships.',
                'status' => 'published',
                'published_at' => now()->subDay(),
            ],
        ];

        foreach ($posts as $postData) {
            $slug = Str::slug($postData['title']);
            $uniqueSlug = $slug;
            $counter = 1;
            while (BlogPost::where('slug', $uniqueSlug)->exists()) {
                $uniqueSlug = $slug . '-' . $counter;
                $counter++;
            }

            BlogPost::create([
                'user_id' => $admin->id,
                'title' => $postData['title'],
                'slug' => $uniqueSlug,
                'excerpt' => $postData['excerpt'],
                'content' => $postData['content'],
                'status' => $postData['status'],
                'published_at' => $postData['published_at'],
            ]);
        }
    }
}
