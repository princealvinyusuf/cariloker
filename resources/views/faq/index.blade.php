<x-app-layout>
    <!-- Hero Section -->
    <div class="bg-gradient-to-br from-violet-50 via-fuchsia-50 to-white py-16 border-b border-gray-200">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center">
                <h1 class="text-4xl md:text-5xl font-bold text-gray-900 mb-4">
                    {{ __('Frequently Asked Questions') }}
                </h1>
                <p class="text-lg md:text-xl text-gray-600 max-w-3xl mx-auto">
                    {{ __('Find answers to common questions about Cari Loker and how to use our platform.') }}
                </p>
            </div>
        </div>
    </div>

    <!-- FAQ Section -->
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-16">
        @if($faqs->count() > 0)
            <div class="space-y-4">
                @foreach($faqs as $faq)
                    <div class="bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden">
                        <button 
                            class="faq-item w-full px-6 py-5 text-left flex items-center justify-between hover:bg-gray-50 transition-colors group"
                            onclick="toggleFaq({{ $faq->id }})"
                            aria-expanded="false"
                        >
                            <h3 class="text-lg font-bold text-gray-900 pr-4 group-hover:text-violet-600 transition-colors">
                                {{ $faq->question }}
                            </h3>
                            <i class="fa-solid fa-chevron-down text-violet-600 transition-transform faq-icon" id="icon-{{ $faq->id }}"></i>
                        </button>
                        <div class="faq-answer hidden px-6 pb-6" id="answer-{{ $faq->id }}">
                            <div class="text-gray-600 leading-relaxed">
                                {!! nl2br(e($faq->answer)) !!}
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-12 text-center">
                <i class="fa-solid fa-question-circle text-4xl text-gray-300 mb-4"></i>
                <h3 class="text-xl font-semibold text-gray-900 mb-2">{{ __('No FAQs Available') }}</h3>
                <p class="text-gray-600">{{ __('Check back later for frequently asked questions.') }}</p>
            </div>
        @endif

        <!-- Contact Section -->
        <div class="mt-12 bg-gradient-to-br from-violet-50 to-fuchsia-50 rounded-2xl p-8 border border-violet-100">
            <div class="text-center">
                <h3 class="text-2xl font-bold text-gray-900 mb-3">{{ __('Still have questions?') }}</h3>
                <p class="text-gray-600 mb-6">{{ __('Can\'t find the answer you\'re looking for? Please get in touch with our friendly team.') }}</p>
                <a href="{{ route('about') }}" class="inline-block bg-violet-600 hover:bg-violet-700 text-white font-semibold rounded-lg px-6 py-3 transition-colors">
                    {{ __('Contact Us') }}
                </a>
            </div>
        </div>
    </div>

    <style>
        .faq-icon {
            transition: transform 0.3s ease;
        }
        .faq-icon.rotate-180 {
            transform: rotate(180deg);
        }
    </style>
    <script>
        function toggleFaq(id) {
            const answer = document.getElementById('answer-' + id);
            const icon = document.getElementById('icon-' + id);
            const button = answer.previousElementSibling;
            const isExpanded = !answer.classList.contains('hidden');

            if (isExpanded) {
                answer.classList.add('hidden');
                icon.classList.remove('rotate-180');
                button.setAttribute('aria-expanded', 'false');
            } else {
                answer.classList.remove('hidden');
                icon.classList.add('rotate-180');
                button.setAttribute('aria-expanded', 'true');
            }
        }
    </script>
</x-app-layout>

