@php
    $formId = $formId ?? 'filters';
    $formClass = $formClass ?? '';
    $salaryRanges = [
        '0-5000000' => 'Rp 0 - 5.000.000',
        '5000000-10000000' => 'Rp 5.000.000 - 10.000.000',
        '10000000-15000000' => 'Rp 10.000.000 - 15.000.000',
        '15000000-20000000' => 'Rp 15.000.000 - 20.000.000',
        '20000000+' => 'Rp 20.000.000+',
    ];
    $selectedSalary = request('salary_range');

    $experienceLevels = ['1' => '1 Year', '2' => '2 Years', '3' => '3 Years', '4' => '4 Years', '5' => '5 Years'];
    $selectedExp = request('experience');
    $selectedExpArray = is_array($selectedExp) ? $selectedExp : ($selectedExp ? [$selectedExp] : []);

    $dateRanges = [
        'today' => __('Today'),
        'last_7_days' => __('Last 7 Days'),
        'last_15_days' => __('Last 15 Days'),
        'last_month' => __('Last Month'),
    ];
    $selectedDate = request('date_posted');
@endphp

<form id="{{ $formId }}" method="GET" action="{{ route('jobs.index') }}" class="{{ $formClass }}">
    <input type="hidden" name="q" value="{{ request('q') }}">
    <input type="hidden" name="location" value="{{ request('location') }}">
    @if(request()->filled('education_level'))
        @php $educationParam = request()->input('education_level'); @endphp
        @if(is_array($educationParam))
            @foreach($educationParam as $item)
                <input type="hidden" name="education_level[]" value="{{ $item }}">
            @endforeach
        @else
            <input type="hidden" name="education_level" value="{{ $educationParam }}">
        @endif
    @endif

    <div class="space-y-6">
        <!-- Salary Range -->
        <div>
            <h3 class="text-sm font-semibold text-gray-900 mb-3">{{ __('Salary Range') }}</h3>
            <div class="space-y-2">
                @foreach($salaryRanges as $key => $label)
                    <label class="flex items-center gap-3 cursor-pointer group">
                        <input type="checkbox" name="salary_range[]" value="{{ $key }}" form="{{ $formId }}"
                               @checked(is_array($selectedSalary) && in_array($key, $selectedSalary) || $selectedSalary === $key)
                               onchange="document.getElementById('{{ $formId }}').submit()"
                               class="w-4 h-4 text-violet-600 border-gray-300 rounded focus:ring-violet-500 cursor-pointer">
                        <span class="text-sm text-gray-700 group-hover:text-violet-600">{{ $label }}</span>
                    </label>
                @endforeach
            </div>
        </div>

        <!-- Experience -->
        <div>
            <h3 class="text-sm font-semibold text-gray-900 mb-3">{{ __('Experience') }}</h3>
            <div class="space-y-2">
                @foreach($experienceLevels as $key => $label)
                    <label class="flex items-center gap-3 cursor-pointer group">
                        <input type="checkbox" name="experience[]" value="{{ $key }}" form="{{ $formId }}"
                               @checked(in_array($key, $selectedExpArray))
                               onchange="document.getElementById('{{ $formId }}').submit()"
                               class="w-4 h-4 text-violet-600 border-gray-300 rounded focus:ring-violet-500 cursor-pointer">
                        <span class="text-sm text-gray-700 group-hover:text-violet-600">{{ $label }}</span>
                    </label>
                @endforeach
            </div>
        </div>

        <!-- Date Posted -->
        <div>
            <h3 class="text-sm font-semibold text-gray-900 mb-3">{{ __('Date Posted') }}</h3>
            <div class="space-y-2">
                @foreach($dateRanges as $key => $label)
                    <label class="flex items-center gap-3 cursor-pointer group">
                        <input type="checkbox" name="date_posted[]" value="{{ $key }}" form="{{ $formId }}"
                               @checked(is_array($selectedDate) && in_array($key, $selectedDate) || $selectedDate === $key)
                               onchange="document.getElementById('{{ $formId }}').submit()"
                               class="w-4 h-4 text-violet-600 border-gray-300 rounded focus:ring-violet-500 cursor-pointer">
                        <span class="text-sm text-gray-700 group-hover:text-violet-600">{{ $label }}</span>
                    </label>
                @endforeach
            </div>
        </div>

        <!-- Job Type / Work Arrangement -->
        <div>
            <h3 class="text-sm font-semibold text-gray-900 mb-3">{{ __('Job Type') }}</h3>
            <div class="space-y-2">
                <label class="flex items-center gap-3 cursor-pointer group">
                    <input type="checkbox" name="work_arrangement[]" value="onsite" form="{{ $formId }}"
                           @checked(is_array(request('work_arrangement')) && in_array('onsite', request('work_arrangement')) || request('work_arrangement') === 'onsite')
                           onchange="document.getElementById('{{ $formId }}').submit()"
                           class="w-4 h-4 text-violet-600 border-gray-300 rounded focus:ring-violet-500 cursor-pointer">
                    <span class="text-sm text-gray-700 group-hover:text-violet-600">{{ __('Work From Office') }}</span>
                </label>
                <label class="flex items-center gap-3 cursor-pointer group">
                    <input type="checkbox" name="work_arrangement[]" value="remote" form="{{ $formId }}"
                           @checked(is_array(request('work_arrangement')) && in_array('remote', request('work_arrangement')) || request('work_arrangement') === 'remote' || request('remote'))
                           onchange="document.getElementById('{{ $formId }}').submit()"
                           class="w-4 h-4 text-violet-600 border-gray-300 rounded focus:ring-violet-500 cursor-pointer">
                    <span class="text-sm text-gray-700 group-hover:text-violet-600">{{ __('Work From Home') }}</span>
                </label>
                <label class="flex items-center gap-3 cursor-pointer group">
                    <input type="checkbox" name="work_arrangement[]" value="remote_check" form="{{ $formId }}"
                           @checked(request('remote'))
                           onchange="document.getElementById('{{ $formId }}').submit()"
                           class="w-4 h-4 text-violet-600 border-gray-300 rounded focus:ring-violet-500 cursor-pointer">
                    <span class="text-sm text-gray-700 group-hover:text-violet-600">{{ __('Remote') }}</span>
                </label>
            </div>
        </div>
    </div>
</form>

