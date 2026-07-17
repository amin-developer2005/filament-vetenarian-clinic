<?php

namespace App\Filament\Owner\Pages;

use App\Filament\Pages\Concerns\HasProfileRoutes;
use App\Filament\Pages\Concerns\InteractWithProfile;
use App\Models\Role;
use Filament\Actions\Action;
use Filament\Infolists\Components\ImageEntry;
use Filament\Infolists\Components\TextEntry;
use Filament\Pages\Page;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Support\Enums\IconSize;
use Filament\Support\Enums\TextSize;
use Filament\Support\Icons\Heroicon;
use UnitEnum;

class Profile extends Page
{
    use HasProfileRoutes;
    use InteractWithProfile;

    protected static string|null|\BackedEnum $navigationIcon = Heroicon::OutlinedUserCircle;

    protected static ?int $navigationSort = 99;

    protected static string $routePath = 'profile';

    protected static ?string $slug = 'profile';

    public static function getNavigationLabel(): string
    {
        return __('owner/profile.navigation.label');
    }

    public function getTitle(): string
    {
        return __('owner/profile.title');
    }

    public static function getNavigationGroup(): string|UnitEnum|null
    {
        return __('owner/profile.navigation.group');
    }

    public function mount(): void
    {
        $this->user = $this->resolveUser();
        $this->profile = $this->resolveProfile();
    }

    public function content(Schema $schema): Schema
    {
        return $schema
            ->record([
                $this->user,
                $this->profile,
            ])
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

                                        ImageEntry::make('profile.avatar')
                                            ->state(fn () => $this->profile->avatarUrl)
                                            ->label('تصویر پروفایل من')
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

                                                TextEntry::make('name')
                                                    ->label('نام کاربری')
                                                    ->state($this->profile?->fullName)
                                                    ->size(TextSize::Medium)
                                                    ->weight('bold')
                                                    ->columnSpanFull()
                                                    ->color('gray'),

                                                TextEntry::make('email')
                                                    ->label('ایمیل')
                                                    ->state(fn () => $this->user->email)
                                                    ->color('gray')
                                                    ->size(TextSize::Medium)
                                                    ->icon(Heroicon::OutlinedEnvelope)
                                                    ->copyable()
                                                    ->color('gray')
                                                    ->columnSpanFull(),

                                                TextEntry::make('roles')
                                                    ->label('نقش ')
                                                    ->size(TextSize::Medium)
                                                    ->state(function () {
                                                        return $this->user->roles
                                                            ->first()
                                                            ->name;
                                                    })
                                                    ->badge()
                                                    ->color('success'),

                                                TextEntry::make('created_at')
                                                    ->label('تاریخ عضویت')
                                                    ->state(fn () => $this->user->created_at)
                                                    ->jalaliDateTime('d, M Y H:i')
                                                    ->icon(Heroicon::OutlinedCalendar)
                                                    ->size(TextSize::Medium)
                                                    ->color('gray'),
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
             | Personal Information
             |--------------------------------------------------------------------------
             */

                Section::make(__('owner/profile.sections.personal_information'))

                    ->icon(Heroicon::OutlinedIdentification)

                    ->schema([
                        Grid::make(2)
                            ->schema([
                                TextEntry::make('profile.first_name')
                                    ->size(TextSize::Medium)
                                    ->color('gray')
                                    ->placeholder('ثبت نشده')
                                    ->state(fn () => $this->profile?->first_name)
                                    ->label('نام'),
                                TextEntry::make('profile.surname')
                                    ->size(TextSize::Medium)
                                    ->color('gray')
                                    ->placeholder('ثبت نشده')
                                    ->state(fn () => $this->profile?->surname)
                                    ->label('نام خانوادگی'),
                                TextEntry::make('profile.gender')
                                    ->size(TextSize::Medium)
                                    ->placeholder('ثبت نشده')
                                    ->color('gray')
                                    ->state(fn () => $this->profile?->gender?->getLabel() ?? '')
                                    ->label('جنسیت'),
                                TextEntry::make('profile.birth_date')
                                    ->state(fn () => $this->profile?->birth_date)
                                    ->size(TextSize::Medium)
                                    ->placeholder('ثبت نشده')
                                    ->jalaliDate('d, M Y')
                                    ->color('gray')
                                    ->label('تاریخ تولد'),
                            ]),
                    ]),

                /*
                |--------------------------------------------------------------------------
                | Contact Information
                |--------------------------------------------------------------------------
                */

                Section::make(__('owner/profile.sections.contact_information'))

                    ->icon(Heroicon::OutlinedPhone)

                    ->schema([
                        Grid::make()
                            ->schema([

                                TextEntry::make('profile.mobile')
                                    ->label('شماره موبایل')
                                    ->state(fn () => $this->profile?->mobile)
                                    ->icon(Heroicon::OutlinedDevicePhoneMobile)
                                    ->size(TextSize::Medium)
                                    ->color('gray')
                                    ->copyable()
                                    ->placeholder('ثبت نشده'),

                                TextEntry::make('profile.address')
                                    ->label('آدرس')
                                    ->state(fn () => $this->profile?->address)
                                    ->icon(Heroicon::OutlinedHomeModern)
                                    ->size(TextSize::Medium)
                                    ->color('gray')
                                    ->placeholder('ثبت نشده'),
                            ]),
                    ]),
            ]);
    }
}
