<x-filament-widgets::widget class="fi-account-widget">
    <x-filament::section>
        {{-- Widget content --}}

        <x-filament-panels::avatar.user
            size="lg"
            :user="$user"
            loading="lazy" />

        <div class="fi-account-widget-main">
            <h2 class="fi-account-widget-heading">
                {{ __('filament-panels::widgets/account-widget.welcome', ['app' => config('app.name')]) }}
            </h2>

            <div class="flex">
                <p class="fi-account-widget-user-name">
                    {{ filament()->getUserName($user) }}
                </p>
                <div class="flex space-y-32">
                    <p>نقش ها:</p>

                    @foreach($roles as $role)
                        <p class="fi-account-widget-user-name">
                            {{ $role }}
                        </p>
                    @endforeach
                </div>
            </div>

        </div>

        <form
            action="{{ filament()->getLogoutUrl() }}"
            method="post"
            class="fi-account-widget-logout-form"
        >
            @csrf
            <x-filament::button color="gray"
                                :icon="\Filament\Support\Icons\Heroicon::ArrowLeftEndOnRectangle"
                                :icon-alias="\Filament\View\PanelsIconAlias::WIDGETS_ACCOUNT_LOGOUT_BUTTON"
                                labeled-from="sm"
                                tag="button"
                                type="submit"
            >
                {{ __('filament-panels::widgets/account-widget.actions.logout.label') }}

            </x-filament::button>
        </form>

    </x-filament::section>
</x-filament-widgets::widget>
