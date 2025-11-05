<x-app-layout>
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <div class="mb-8">
            <h1 class="text-3xl font-bold text-gray-900 mb-2">{{ __('Manage FAQs') }}</h1>
            <p class="text-gray-600">{{ __('Add, edit, or remove frequently asked questions.') }}</p>
        </div>

        @if(session('success'))
            <div class="bg-green-50 border border-green-200 text-green-800 px-4 py-3 rounded-lg mb-6">
                {{ session('success') }}
            </div>
        @endif

        <form method="POST" action="{{ route('faq.update') }}" id="faq-form">
            @csrf
            @method('PUT')
            
            <div id="faq-items" class="space-y-4 mb-6">
                @if($faqs->count() > 0)
                    @foreach($faqs as $index => $faq)
                        <div class="faq-item bg-white rounded-2xl shadow-sm border border-gray-200 p-6">
                            <input type="hidden" name="faqs[{{ $index }}][id]" value="{{ $faq->id }}">
                            <div class="grid grid-cols-1 md:grid-cols-12 gap-4 mb-4">
                                <div class="md:col-span-10">
                                    <label class="block text-sm font-semibold text-gray-900 mb-2">
                                        {{ __('Question') }}
                                    </label>
                                    <input 
                                        type="text" 
                                        name="faqs[{{ $index }}][question]" 
                                        value="{{ old('faqs.' . $index . '.question', $faq->question) }}" 
                                        class="w-full px-4 py-2.5 rounded-lg border border-gray-200 focus:border-violet-500 focus:ring-2 focus:ring-violet-200 text-gray-900 transition-all"
                                        required
                                    >
                                </div>
                                <div class="md:col-span-2">
                                    <label class="block text-sm font-semibold text-gray-900 mb-2">
                                        {{ __('Order') }}
                                    </label>
                                    <input 
                                        type="number" 
                                        name="faqs[{{ $index }}][order]" 
                                        value="{{ old('faqs.' . $index . '.order', $faq->order) }}" 
                                        class="w-full px-4 py-2.5 rounded-lg border border-gray-200 focus:border-violet-500 focus:ring-2 focus:ring-violet-200 text-gray-900 transition-all"
                                        min="0"
                                    >
                                </div>
                            </div>
                            <div class="mb-4">
                                <label class="block text-sm font-semibold text-gray-900 mb-2">
                                    {{ __('Answer') }}
                                </label>
                                <textarea 
                                    name="faqs[{{ $index }}][answer]" 
                                    rows="4" 
                                    class="w-full px-4 py-2.5 rounded-lg border border-gray-200 focus:border-violet-500 focus:ring-2 focus:ring-violet-200 text-gray-900 transition-all"
                                    required
                                >{{ old('faqs.' . $index . '.answer', $faq->answer) }}</textarea>
                            </div>
                            <div class="flex items-center justify-between">
                                <label class="flex items-center gap-2 cursor-pointer">
                                    <input 
                                        type="checkbox" 
                                        name="faqs[{{ $index }}][is_active]" 
                                        value="1"
                                        {{ old('faqs.' . $index . '.is_active', $faq->is_active) ? 'checked' : '' }}
                                        class="w-4 h-4 text-violet-600 border-gray-300 rounded focus:ring-violet-500"
                                    >
                                    <span class="text-sm text-gray-700">{{ __('Active') }}</span>
                                </label>
                                <button 
                                    type="button" 
                                    onclick="removeFaqItem(this)" 
                                    class="text-red-600 hover:text-red-700 font-medium text-sm"
                                >
                                    <i class="fa-solid fa-trash mr-1"></i>{{ __('Delete') }}
                                </button>
                            </div>
                        </div>
                    @endforeach
                @else
                    <div id="empty-state" class="bg-white rounded-2xl shadow-sm border border-gray-200 p-12 text-center">
                        <i class="fa-solid fa-question-circle text-4xl text-gray-300 mb-4"></i>
                        <h3 class="text-xl font-semibold text-gray-900 mb-2">{{ __('No FAQs yet') }}</h3>
                        <p class="text-gray-600 mb-4">{{ __('Click "Add New FAQ" to create your first FAQ.') }}</p>
                    </div>
                @endif
            </div>

            <div class="flex items-center justify-between mb-6">
                <button 
                    type="button" 
                    onclick="addFaqItem()" 
                    class="bg-violet-600 hover:bg-violet-700 text-white font-semibold rounded-lg px-6 py-3 transition-colors"
                >
                    <i class="fa-solid fa-plus mr-2"></i>{{ __('Add New FAQ') }}
                </button>
                <div class="flex items-center gap-4">
                    <a href="{{ route('faq') }}" class="px-6 py-2.5 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 font-semibold transition-colors">
                        {{ __('Cancel') }}
                    </a>
                    <button type="submit" class="px-6 py-2.5 bg-violet-600 hover:bg-violet-700 text-white font-semibold rounded-lg transition-colors">
                        {{ __('Save Changes') }}
                    </button>
                </div>
            </div>
        </form>
    </div>

    <script>
        let faqIndex = {{ $faqs->count() }};

        function addFaqItem() {
            const emptyState = document.getElementById('empty-state');
            if (emptyState) {
                emptyState.remove();
            }

            const faqItems = document.getElementById('faq-items');
            const newItem = document.createElement('div');
            newItem.className = 'faq-item bg-white rounded-2xl shadow-sm border border-gray-200 p-6';
            newItem.innerHTML = `
                <div class="grid grid-cols-1 md:grid-cols-12 gap-4 mb-4">
                    <div class="md:col-span-10">
                        <label class="block text-sm font-semibold text-gray-900 mb-2">
                            {{ __('Question') }}
                        </label>
                        <input 
                            type="text" 
                            name="faqs[${faqIndex}][question]" 
                            class="w-full px-4 py-2.5 rounded-lg border border-gray-200 focus:border-violet-500 focus:ring-2 focus:ring-violet-200 text-gray-900 transition-all"
                            required
                        >
                    </div>
                    <div class="md:col-span-2">
                        <label class="block text-sm font-semibold text-gray-900 mb-2">
                            {{ __('Order') }}
                        </label>
                        <input 
                            type="number" 
                            name="faqs[${faqIndex}][order]" 
                            value="${faqIndex}"
                            class="w-full px-4 py-2.5 rounded-lg border border-gray-200 focus:border-violet-500 focus:ring-2 focus:ring-violet-200 text-gray-900 transition-all"
                            min="0"
                        >
                    </div>
                </div>
                <div class="mb-4">
                    <label class="block text-sm font-semibold text-gray-900 mb-2">
                        {{ __('Answer') }}
                    </label>
                    <textarea 
                        name="faqs[${faqIndex}][answer]" 
                        rows="4" 
                        class="w-full px-4 py-2.5 rounded-lg border border-gray-200 focus:border-violet-500 focus:ring-2 focus:ring-violet-200 text-gray-900 transition-all"
                        required
                    ></textarea>
                </div>
                <div class="flex items-center justify-between">
                    <label class="flex items-center gap-2 cursor-pointer">
                        <input 
                            type="checkbox" 
                            name="faqs[${faqIndex}][is_active]" 
                            value="1"
                            checked
                            class="w-4 h-4 text-violet-600 border-gray-300 rounded focus:ring-violet-500"
                        >
                        <span class="text-sm text-gray-700">{{ __('Active') }}</span>
                    </label>
                    <button 
                        type="button" 
                        onclick="removeFaqItem(this)" 
                        class="text-red-600 hover:text-red-700 font-medium text-sm"
                    >
                        <i class="fa-solid fa-trash mr-1"></i>{{ __('Delete') }}
                    </button>
                </div>
            `;
            faqItems.appendChild(newItem);
            faqIndex++;
        }

        function removeFaqItem(button) {
            const faqItem = button.closest('.faq-item');
            const hiddenInput = faqItem.querySelector('input[type="hidden"][name*="[id]"]');
            
            if (hiddenInput && hiddenInput.value) {
                // Existing FAQ - show confirmation
                if (confirm('{{ __("Are you sure you want to delete this FAQ?") }}')) {
                    // Create a delete form
                    const form = document.createElement('form');
                    form.method = 'POST';
                    form.action = '{{ route("faq.destroy", ":id") }}'.replace(':id', hiddenInput.value);
                    form.innerHTML = `
                        @csrf
                        @method('DELETE')
                    `;
                    document.body.appendChild(form);
                    form.submit();
                }
            } else {
                // New FAQ - just remove from DOM
                faqItem.remove();
                
                // Show empty state if no items left
                const faqItems = document.getElementById('faq-items');
                if (faqItems.children.length === 0) {
                    faqItems.innerHTML = `
                        <div id="empty-state" class="bg-white rounded-2xl shadow-sm border border-gray-200 p-12 text-center">
                            <i class="fa-solid fa-question-circle text-4xl text-gray-300 mb-4"></i>
                            <h3 class="text-xl font-semibold text-gray-900 mb-2">{{ __('No FAQs yet') }}</h3>
                            <p class="text-gray-600 mb-4">{{ __('Click "Add New FAQ" to create your first FAQ.') }}</p>
                        </div>
                    `;
                }
            }
        }
    </script>
</x-app-layout>

