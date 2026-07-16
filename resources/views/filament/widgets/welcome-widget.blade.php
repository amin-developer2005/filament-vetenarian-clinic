{{--
    WelcomeWidget — Premium SaaS Dashboard Widget
    Veterinary Clinic Management System
--}}
<x-filament-widgets::widget class="fi-account-widget">
    <x-filament::section>

@once
    <style>
        @keyframes fi-welcome-rise {
            from {
                opacity: 0;
                transform: translateY(24px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .fi-welcome-rise {
            opacity: 0;
            animation: fi-welcome-rise 0.65s cubic-bezier(0.16, 1, 0.3, 1) forwards;
        }
    </style>
@endonce



        <div class="space-y-6">

            {{-- ════════════════════════════════════════════════════════════
                 SECTION 1 — Hero Greeting Banner
            ════════════════════════════════════════════════════════════ --}}
            <section class="fi-welcome-rise relative overflow-hidden rounded-2xl bg-gradient-to-br from-emerald-600 via-teal-600 to-cyan-600 p-8 shadow-2xl dark:from-emerald-800 dark:via-teal-800 dark:to-cyan-800 sm:p-10">

                {{-- Decorative blurred circles --}}
                <div class="pointer-events-none absolute -right-10 -top-10 h-48 w-48 rounded-full bg-white/[0.08] blur-xl"></div>
                <div class="pointer-events-none absolute -bottom-8 -left-8 h-36 w-36 rounded-full bg-white/[0.06] blur-lg"></div>
                <div class="pointer-events-none absolute right-1/3 top-1 h-24 w-24 rounded-full bg-white/[0.05] blur-md"></div>

                {{-- Subtle dot‑grid texture --}}
                <div
                    class="pointer-events-none absolute inset-0 opacity-[0.05]"
                    style="background-image: radial-gradient(circle, #fff 1px, transparent 1px); background-size: 20px 20px;"
                ></div>

                <div class="relative z-10">
                    <p class="text-base font-medium text-white/70">
                        {{ $greetingMessage }}
                    </p>
                    <h1 class="mt-1 text-3xl font-bold tracking-tight text-white sm:text-4xl">
                        {{ $userName }}
                    </h1>

                    <div class="mt-5 flex flex-wrap items-center gap-x-6 gap-y-2 text-sm text-white/50">
                <span class="inline-flex items-center gap-2">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="h-4 w-4" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 012.25-2.25h13.5A2.25 2.25 0 0121 7.5v11.25m-18 0A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75m-18 0v-7.5A2.25 2.25 0 015.25 9h13.5A2.25 2.25 0 0121 11.25v7.5" />
                    </svg>
                    {{ $todayDate }}
                </span>
                        <span class="inline-flex items-center gap-2">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="h-4 w-4" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    {{ $currentTime }}
                </span>
                    </div>
                </div>
            </section>

            {{-- ════════════════════════════════════════════════════════════
                 SECTION 2 — Today's Appointment Statistics
            ════════════════════════════════════════════════════════════ --}}

            <section class="grid grid-cols-2 gap-4 sm:grid-cols-3 lg:grid-cols-6">
                @foreach ($this->getStatsCards() as $i => $card)
                    <div
                        @class([
                            'fi-welcome-rise group relative overflow-hidden rounded-2xl border-t-[3px] p-5',
                            'bg-white/90 backdrop-blur-sm shadow-sm',
                            'dark:bg-gray-800/90 dark:backdrop-blur-sm',
                            'transition-all duration-300 hover:-translate-y-1 hover:shadow-lg',
                            'dark:hover:shadow-xl dark:hover:shadow-black/20',
                            $card['borderColor'],
                        ])
                        style="animation-delay: {{ ($i + 1) * 0.08 }}s"
                    >
                        <div class="flex items-start justify-between gap-3">
                            <div class="min-w-0 space-y-1.5">
                                <p class="text-[11px] font-semibold uppercase tracking-wider text-gray-400 dark:text-gray-500">
                                    {{ $card['label'] }}
                                </p>
                                <p class="text-3xl font-bold tabular-nums text-gray-900 dark:text-white">
                                    {{ $card['value'] }}
                                </p>
                                <p class="text-[11px] leading-snug text-gray-400 dark:text-gray-500">
                                    {{ $card['description'] }}
                                </p>
                            </div>

                            <div @class([
                        'flex h-11 w-11 shrink-0 items-center justify-center rounded-xl',
                        'transition-transform duration-300 group-hover:scale-110',
                        $card['iconBg'],
                        $card['iconColor'],
                    ])>
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="h-6 w-6" aria-hidden="true">
                                    {!! $card['svg'] !!}
                                </svg>
                            </div>
                        </div>
                    </div>
                @endforeach
            </section>

            {{-- ════════════════════════════════════════════════════════════
                 SECTION 3 — Today's Schedule Summary
            ════════════════════════════════════════════════════════════ --}}

            <section
                class="fi-welcome-rise overflow-hidden rounded-2xl bg-white/90 backdrop-blur-sm shadow-sm dark:bg-gray-800/90 dark:backdrop-blur-sm"
                style="animation-delay: 0.65s"
            >
                {{-- Section header --}}
                <div class="flex items-center gap-2.5 border-b border-gray-100 px-6 py-4 dark:border-gray-700/50">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="h-5 w-5 text-emerald-500" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <h2 class="text-base font-semibold text-gray-800 dark:text-gray-200">
                        {{ __('widgets/dashboard.schedule.title') }}
                    </h2>
                </div>

                <div class="p-6">
                    @if ($hasSchedule)
                        {{-- ── Timeline bar ── --}}
                        <div class="hidden sm:block">
                            <div class="relative flex items-center justify-between px-1">
                                {{-- Start dot --}}
                                <div class="z-10 h-3 w-3 rounded-full bg-emerald-500 ring-4 ring-emerald-50 dark:ring-emerald-500/10"></div>

                                {{-- Track --}}
                                <div class="absolute inset-x-3 top-1/2 h-0.5 -translate-y-1/2 bg-gradient-to-r from-emerald-400 via-teal-400 to-cyan-400 opacity-30"></div>

                                {{-- Working hours pill (center) --}}
                                <div class="relative z-10 rounded-full bg-gradient-to-r from-emerald-50 to-teal-50 px-3 py-1 dark:from-emerald-500/10 dark:to-teal-500/10">
                            <span class="text-xs font-semibold text-emerald-700 dark:text-emerald-400">
                                {{ $workingHoursDisplay }}
                            </span>
                                </div>

                                {{-- End dot --}}
                                <div class="z-10 h-3 w-3 rounded-full bg-cyan-500 ring-4 ring-cyan-50 dark:ring-cyan-500/10"></div>
                            </div>
                        </div>

                        {{-- ── Schedule detail cards ── --}}
                        <div class="mt-6 grid grid-cols-1 gap-4 sm:grid-cols-3">
                            {{-- First Appointment --}}
                            <div class="flex items-center gap-4 rounded-xl bg-gray-50 p-4 dark:bg-gray-700/40">
                                <div class="flex h-12 w-12 shrink-0 items-center justify-center rounded-xl bg-emerald-50 text-emerald-500 dark:bg-emerald-500/10 dark:text-emerald-400">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="h-6 w-6" aria-hidden="true">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                </div>
                                <div>
                                    <p class="text-2xl font-bold tabular-nums text-gray-900 dark:text-white">
                                        {{ $firstAppointmentTime }}
                                    </p>
                                    <p class="text-xs text-gray-500 dark:text-gray-400">
                                        {{ __('widgets/dashboard.schedule.first_appointment') }}
                                    </p>
                                </div>
                            </div>

                            {{-- Working Hours --}}
                            <div class="flex items-center gap-4 rounded-xl bg-gray-50 p-4 dark:bg-gray-700/40">
                                <div class="flex h-12 w-12 shrink-0 items-center justify-center rounded-xl bg-amber-50 text-amber-500 dark:bg-amber-500/10 dark:text-amber-400">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="h-6 w-6" aria-hidden="true">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 3v11.25A2.25 2.25 0 006 16.5h2.25M3.75 3h-1.5m1.5 0h16.5m0 0h1.5m-1.5 0v11.25A2.25 2.25 0 0118 16.5h-2.25m-7.5 0h7.5m-7.5 0l-1 3m8.5-3l1 3m0 0l.5 1.5m-.5-1.5h-9.5m0 0l-.5 1.5" />
                                    </svg>
                                </div>
                                <div>
                                    <p class="text-2xl font-bold tabular-nums text-gray-900 dark:text-white">
                                        {{ $workingHoursDisplay }}
                                    </p>
                                    <p class="text-xs text-gray-500 dark:text-gray-400">
                                        {{ __('widgets/dashboard.schedule.working_hours') }}
                                    </p>
                                </div>
                            </div>

                            {{-- Last Appointment --}}
                            <div class="flex items-center gap-4 rounded-xl bg-gray-50 p-4 dark:bg-gray-700/40">
                                <div class="flex h-12 w-12 shrink-0 items-center justify-center rounded-xl bg-cyan-50 text-cyan-500 dark:bg-cyan-500/10 dark:text-cyan-400">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="h-6 w-6" aria-hidden="true">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                </div>
                                <div>
                                    <p class="text-2xl font-bold tabular-nums text-gray-900 dark:text-white">
                                        {{ $lastAppointmentTime }}
                                    </p>
                                    <p class="text-xs text-gray-500 dark:text-gray-400">
                                        {{ __('widgets/dashboard.schedule.last_appointment') }}
                                    </p>
                                </div>
                            </div>
                        </div>
                    @else
                        {{-- ── Empty State ── --}}
                        <div class="flex flex-col items-center justify-center py-12">
                            <div class="flex h-20 w-20 items-center justify-center rounded-full bg-gray-100 text-gray-300 dark:bg-gray-700/60 dark:text-gray-600">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1" stroke="currentColor" class="h-10 w-10" aria-hidden="true">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 012.25-2.25h13.5A2.25 2.25 0 0121 7.5v11.25m-18 0A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75m-18 0v-7.5A2.25 2.25 0 015.25 9h13.5A2.25 2.25 0 0121 11.25v7.5" />
                                </svg>
                            </div>
                            <h3 class="mt-4 text-base font-semibold text-gray-600 dark:text-gray-300">
                                {{ __('widgets/dashboard.empty_schedule.title') }}
                            </h3>
                            <p class="mt-2 max-w-sm text-center text-sm leading-relaxed text-gray-400 dark:text-gray-500">
                                {{ __('widgets/dashboard.empty_schedule.description') }}
                            </p>
                        </div>
                    @endif
                </div>
            </section>

            {{-- ════════════════════════════════════════════════════════════
                 SECTION 4 — Quick Information
            ════════════════════════════════════════════════════════════ --}}

            <section
                class="fi-welcome-rise overflow-hidden rounded-2xl bg-white/90 backdrop-blur-sm shadow-sm dark:bg-gray-800/90 dark:backdrop-blur-sm"
                style="animation-delay: 0.75s"
            >
                {{-- Section header --}}
                <div class="flex items-center gap-2.5 border-b border-gray-100 px-6 py-4 dark:border-gray-700/50">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="h-5 w-5 text-sky-500" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M11.25 11.25l.041-.02a.75.75 0 011.063.852l-.708 2.836a.75.75 0 001.063.853l.041-.021M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-9-3.75h.008v.008H12V8.25z" />
                    </svg>
                    <h2 class="text-base font-semibold text-gray-800 dark:text-gray-200">
                        {{ __('widgets/dashboard.quick_info.title') }}
                    </h2>
                </div>

                <div class="p-6">
                    <div class="grid grid-cols-1 gap-3 sm:grid-cols-3">
                        @foreach ($this->getQuickInfoItems() as $item)
                            <div class="flex items-center gap-3.5 rounded-xl border border-gray-100 bg-gray-50/50 p-4 transition-colors duration-200 hover:bg-gray-100/60 dark:border-gray-700/50 dark:bg-gray-700/20 dark:hover:bg-gray-700/30">
                                <div @class([
                            'flex h-10 w-10 shrink-0 items-center justify-center rounded-lg',
                            $item['iconBg'],
                            $item['iconColor'],
                        ])>
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="h-5 w-5" aria-hidden="true">
                                        {!! $item['svg'] !!}
                                    </svg>
                                </div>
                                <div class="min-w-0">
                                    <p class="text-[11px] font-medium uppercase tracking-wider text-gray-400 dark:text-gray-500">
                                        {{ $item['label'] }}
                                    </p>
                                    <p class="truncate text-sm font-semibold text-gray-800 dark:text-gray-200" title="{{ $item['value'] }}">
                                        {{ $item['value'] }}
                                    </p>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </section>

        </div>


    </x-filament::section>
</x-filament-widgets::widget>
