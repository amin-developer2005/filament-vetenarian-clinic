<?php

namespace App\Filament\Owner\Pages;

use App\Enums\AppointmentStatus;
use App\Enums\PanelRole;
use App\Filament\Owner\Pages\Profile\Schemas\EditProfileSchema;
use App\Filament\Pages\Concerns\HasProfileRoutes;
use App\Filament\Pages\Concerns\InteractWithProfile;
use App\Models\Animal;
use App\Models\Appointment;
use Filament\Actions\Action;
use Filament\Facades\Filament;
use Filament\Pages\Page;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Image;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Text;
use Filament\Schemas\Schema;
use Filament\Support\Enums\IconSize;
use Filament\Support\Enums\Size;
use Filament\Support\Icons\Heroicon;
use Filament\Widgets\StatsOverviewWidget\Stat;

class Profile extends Page
{
    use HasProfileRoutes;
    use InteractWithProfile;

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

                                        Image::make(asset('images/avatars/default-avatar.png'), asset('images/avatars/default-avatar.png'))
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
                                                            } elseif ($role === PanelRole::DOCTOR) {
                                                                $userRoles .= PanelRole::Doctor->getLabel();
                                                            } elseif ($role === PanelRole::ADMIN) {
                                                                $userRoles .= PanelRole::Admin->getLabel();
                                                            } elseif ($role === PanelRole::RECEPTIONIST) {
                                                                $userRoles .= PanelRole::Receptionist->getLabel();
                                                            }
                                                        }

                                                        return $userRoles;
                                                    }
                                                )
                                                    ->badge()
                                                    ->size(Size::ExtraLarge)
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
                            ->iconSize(IconSize::TwoExtraLarge)
                            ->color('primary')

                            ->button()

                            ->url($this->fetchProfileEditPageUrl()),

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
                                Stat::make(
                                    'حیوانات من',
                                    Animal::query()
                                        ->where('owner_id', Filament::auth()->id())
                                        ->count()
                                ),
                                Stat::make(
                                    'نوبت های من',
                                    Appointment::query()
                                        ->where('owner_id', Filament::auth()->id())
                                        ->latest()
                                        ->count(),
                                ),
                                Stat::make(
                                    'نوبت‌های در انتظار تایید',
                                    Appointment::query()
                                        ->where('owner_id', Filament::auth()->id())
                                        ->where('status', AppointmentStatus::Pending)
                                        ->count()
                                ),
                                Stat::make(
                                    'آخرین مراجعه',
                                    function () {
                                        $lastVisit = Appointment::query()
                                            ->where('owner_id', Filament::auth()->id())
                                            ->where('status', AppointmentStatus::Completed)
                                            ->first();

                                        if (! $lastVisit) {
                                            return null;
                                        }

                                        return $lastVisit->slot->date->format('Y M-d');
                                    }
                                )->description(
                                    function ($value) {
                                        if (blank($value)) {
                                            return 'هنوز مراجعه‌ای ثبت نشده است.';
                                        }
                                    }
                                )
                                ,
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
