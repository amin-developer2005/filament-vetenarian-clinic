<?php

namespace App\Filament\Owner\Pages\Profile\Schemas;

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

class ProfileHeaderSchema extends Page
{
    protected static bool $isDiscovered = false;

    public function content(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make(__('owner/profile.sections.account'))

                    ->description(
                        __('owner/profile.sections.account_description')
                    )

                    ->icon(Heroicon::OutlinedUserCircle)

                    ->secondary()

                    ->compact()

                    ->afterHeader([

                        Action::make('editProfile')

                            ->label(__('owner/profile.actions.edit'))

                            ->icon(Heroicon::OutlinedPencilSquare)

                            ->color('primary')

                            ->button()

                            ->url(route('filament.owner.pages.profile')),

                    ])

                    ->schema([

                        Grid::make([
                            'default' => 1,
                            'lg' => 12,
                        ])

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

                                        Text::make(__('roles.owner'))
                                            ->badge()
                                            ->color('success')
                                            ->icon(Heroicon::OutlinedShieldCheck),

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

                                                ? __('owner/profile.email_verified')

                                                : __('owner/profile.email_not_verified')
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

                            ]),

                    ])
            ]);
    }
}
