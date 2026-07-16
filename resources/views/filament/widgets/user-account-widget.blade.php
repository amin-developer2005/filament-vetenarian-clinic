<x-filament-widgets::widget class="fi-account-widget">
    <x-filament::section>
        <div class="grid gap-6 lg:grid-cols-3">

            {{-- Left --}}
            <div class="flex items-center gap-5">
                <x-filament-panels::avatar.user
                    size="xl"
                    :user="$user"
                    loading="lazy"
                />

                <div>
                    <h2 class="text-2xl font-bold">
                        👋 {{ __('خوش آمدید') }}
                    </h2>

                    <p class="text-lg font-semibold mt-1">
                        {{ filament()->getUserName($user) }}
                    </p>

                    <div class="mt-2 flex gap-2">

                        <x-filament::badge color="success">
                            {{ auth()->user()->roles->first()?->name }}
                        </x-filament::badge>

                        <x-filament::badge color="primary">
                            {{ filament()->getTenant()?->name }}
                        </x-filament::badge>

                    </div>

                </div>
            </div>

            {{-- Right --}}
            <div class="flex flex-col justify-between">

                <div>

                    <p class="text-gray-500">
                        {{ \Morilog\Jalali\Jalalian::now()->format('l d F Y') }}
                    </p>

                    <div class="mt-5">

                        <p>
                            🕗 First Appointment
                        </p>

                        <h3 class="font-bold">
                            {{ $firstAppointment ?? '--:--' }}
                        </h3>

                    </div>

                    <div class="mt-4">

                        <p>
                            🕔 Last Appointment
                        </p>

                        <h3 class="font-bold">
                            {{ $lastAppointment ?? '--:--' }}
                        </h3>

                    </div>

                </div>

                <form
                    action="{{ filament()->getLogoutUrl() }}"
                    method="POST"
                    class="mt-5"
                >
                    @csrf

                    <x-filament::button
                        type="submit"
                        color="danger"
                        icon="heroicon-o-arrow-left-on-rectangle"
                        class="w-full"
                    >
                        Logout
                    </x-filament::button>

                </form>

            </div>

        </div>
    </x-filament::section>
</x-filament-widgets::widget>
