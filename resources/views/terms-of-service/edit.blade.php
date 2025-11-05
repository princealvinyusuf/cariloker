<x-app-layout>
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <div class="mb-8">
            <h1 class="text-3xl font-bold text-gray-900">{{ __('Edit Terms of Service Content') }}</h1>
            <p class="text-gray-600 mt-2">{{ __('Update the content for the Terms of Service page. All fields are editable from the database.') }}</p>
        </div>

        @if(session('success'))
            <div class="bg-green-50 border border-green-200 text-green-800 px-4 py-3 rounded-lg mb-6">
                {{ session('success') }}
            </div>
        @endif

        <form method="POST" action="{{ route('terms-of-service.update') }}" class="space-y-8">
            @csrf
            @method('PUT')

            @foreach($sections as $sectionName => $sectionContents)
                <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-6">
                    <h2 class="text-xl font-bold text-gray-900 mb-6 capitalize">{{ str_replace('_', ' ', $sectionName) }}</h2>
                    
                    <div class="space-y-6">
                        @foreach($sectionContents as $content)
                            <div>
                                <label class="block text-sm font-semibold text-gray-900 mb-2">
                                    {{ $content->label }}
                                </label>
                                
                                @if($content->type === 'textarea')
                                    <textarea 
                                        name="content[{{ $content->key }}]" 
                                        rows="6"
                                        class="w-full px-4 py-2.5 rounded-lg border border-gray-200 focus:border-violet-500 focus:ring-2 focus:ring-violet-200 text-gray-900 transition-all"
                                    >{{ old('content.' . $content->key, $content->value) }}</textarea>
                                @elseif($content->type === 'html')
                                    <textarea 
                                        name="content[{{ $content->key }}]" 
                                        rows="8"
                                        class="w-full px-4 py-2.5 rounded-lg border border-gray-200 focus:border-violet-500 focus:ring-2 focus:ring-violet-200 text-gray-900 transition-all font-mono text-sm"
                                    >{{ old('content.' . $content->key, $content->value) }}</textarea>
                                    <p class="text-xs text-gray-500 mt-1">{{ __('HTML is allowed') }}</p>
                                @else
                                    <input 
                                        type="text" 
                                        name="content[{{ $content->key }}]" 
                                        value="{{ old('content.' . $content->key, $content->value) }}"
                                        class="w-full px-4 py-2.5 rounded-lg border border-gray-200 focus:border-violet-500 focus:ring-2 focus:ring-violet-200 text-gray-900 transition-all"
                                    >
                                @endif
                                
                                @error('content.' . $content->key)
                                    <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                        @endforeach
                    </div>
                </div>
            @endforeach

            <div class="flex items-center justify-end gap-4">
                <a href="{{ route('terms-of-service') }}" class="px-6 py-2.5 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 font-semibold transition-colors">
                    {{ __('Cancel') }}
                </a>
                <button type="submit" class="px-6 py-2.5 bg-violet-600 hover:bg-violet-700 text-white font-semibold rounded-lg transition-colors">
                    {{ __('Save Changes') }}
                </button>
            </div>
        </form>
    </div>
</x-app-layout>

