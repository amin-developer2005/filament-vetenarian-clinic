<?php

namespace App\Filament\Owner\Pages;

use App\Enums\PanelRole;
use App\Models\Role;
use App\Models\User;
use Filament\Actions\Action;
use Filament\Facades\Filament;
use Filament\Pages\Page;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Image;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Text;
use Filament\Schemas\Schema;
use Filament\Support\Enums\Size;
use Filament\Support\Icons\Heroicon;

class Profile extends Page
{
    protected static string|null|\BackedEnum $navigationIcon = Heroicon::OutlinedUserCircle;

    protected static ?int $navigationSort = 99;

    public static function getNavigationLabel(): string
    {
        return __('owner/profile.navigation.label');
    }

    public function getTitle(): string
    {
        return __('owner/profile.title');
    }

    public function content(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make(__('owner/profile.sections.header'))
                    ->icon(Heroicon::OutlinedUserCircle)
                    ->description(__('owner/profile.sections.header_description'))
                    ->schema([
                        Grid::make()
                            ->schema([
                                        Grid::make(12)

                                            ->schema([


                                                /*
                                                |--------------------------------------------------------------------------
                                                | Avatar
                                                |--------------------------------------------------------------------------
                                                */

                                                Image::make(Filament::auth()->user()->profile?->avatarUrl, asset('images/avatars/default-avatar.png'))
                                                    ->imageHeight(110)
                                                    ->imageWidth(110)
                                                    ->columnSpan([
                                                        'default' => 12,
                                                        'lg' => 2,
                                                    ]),

                                                /*
                                                |--------------------------------------------------------------------------
                                                | Information
                                                |--------------------------------------------------------------------------
                                                */

                                                Grid::make(2)

                                                    ->columnSpan([
                                                        'default' => 12,
                                                        'lg' => 10,
                                                    ])

                                                    ->schema([

                                                        Text::make(
                                                            Filament::auth()->user()->profile?->full_name
                                                            ?? Filament::auth()->user()->name
                                                        )

                                                            ->size(Size::ExtraLarge)

                                                            ->weight('bold')

                                                            ->columnSpanFull(),

                                                        Text::make(Filament::auth()->user()->email)

                                                            ->icon(Heroicon::OutlinedEnvelope)

                                                            ->color('gray')

                                                            ->columnSpanFull(),

                                                        Text::make(
                                                            function () {
                                                                $user = Filament::auth()->user();
                                                                $userRoles = '';

                                                                foreach ($user->roles()->get()->pluck('name')->toArray() as $role) {
                                                                    if ($role === PanelRole::OWNER) {
                                                                        $userRoles .= PanelRole::Owner->getLabel();
                                                                    } else  if ($role === PanelRole::DOCTOR) {
                                                                        $userRoles .= PanelRole::Doctor->getLabel();
                                                                    } else  if ($role === PanelRole::ADMIN) {
                                                                        $userRoles .= PanelRole::Admin->getLabel();
                                                                    } else  if ($role === PanelRole::RECEPTIONIST) {
                                                                        $userRoles .= PanelRole::RECEPTIONIST->getLabel();
                                                                    }
                                                                }

                                                                return $userRoles;
                                                            }
                                                        )
                                                            ->badge()
                                                            ->size(Size::ExtraLarge)
                                                            ->formatStateUsing(
                                                                fn ($state) => dd($state)
                                                            )
                                                            ->color('success'),

                                                        Text::make(
                                                            __('owner/profile.member_since', [
                                                                'date' => Filament::auth()->user()->created_at
                                                                    ->format('Y/m/d'),
                                                            ])
                                                        )

                                                            ->icon(Heroicon::OutlinedCalendar)

                                                            ->color('gray'),

                                                        Text::make(
                                                            Filament::auth()->user()->hasVerifiedEmail()
                                                                ? __('owner/profile.email.verified')
                                                                : __('owner/profile.email.unverified')
                                                        )

                                                            ->badge()

                                                            ->color(fn () => Filament::auth()->user()->hasVerifiedEmail()
                                                                ? 'success'
                                                                : 'danger')

                                                            ->icon(fn () => Filament::auth()->user()->hasVerifiedEmail()

                                                                ? Heroicon::OutlinedCheckBadge

                                                                : Heroicon::OutlinedExclamationTriangle)

                                                            ->columnSpanFull(),

                                                    ]),
                                            ])->columnSpanFull(),
                            ]),
                    ])
                    ->afterHeader([

                        Action::make('editProfile')

                            ->label(__('owner/profile.actions.edit'))

                            ->icon(Heroicon::OutlinedPencilSquare)

                            ->color('primary')

                            ->button()

                            ->url(route('filament.owner.pages.profile')),

                    ]),

                /*
                |--------------------------------------------------------------------------
                | Statistics
                |--------------------------------------------------------------------------
                */

                Section::make(__('owner/profile.sections.statistics'))
                    ->icon(Heroicon::OutlinedChartBar)
                    ->schema([
                        Grid::make(4)
                            ->schema([

                            ]),

                    ]),
                /*
             |--------------------------------------------------------------------------
             | Personal Information
             |--------------------------------------------------------------------------
             */

                Section::make(__('owner/profile.sections.personal_information'))

                    ->icon(Heroicon::OutlinedIdentification)

                    ->schema([

                        // مرحله بعد

                    ]),

                /*
                |--------------------------------------------------------------------------
                | Contact Information
                |--------------------------------------------------------------------------
                */

                Section::make(__('owner/profile.sections.contact_information'))

                    ->icon(Heroicon::OutlinedPhone)

                    ->schema([

                        // مرحله بعد

                    ]),

                /*
                |--------------------------------------------------------------------------
                | Avatar
                |--------------------------------------------------------------------------
                */

                Section::make(__('owner/profile.sections.avatar'))

                    ->icon(Heroicon::OutlinedPhoto)

                    ->schema([

                        // مرحله بعد

                    ]),

                /*
                |--------------------------------------------------------------------------
                | Password
                |--------------------------------------------------------------------------
                */

                Section::make(__('owner/profile.sections.security'))

                    ->icon(Heroicon::OutlinedLockClosed)

                    ->schema([

                        // مرحله بعد

                    ]),
            ]);
    }
}
