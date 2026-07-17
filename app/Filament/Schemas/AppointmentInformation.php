<?php

namespace App\Filament\Schemas;

use App\Models\Appointment;
use Filament\Infolists\Components\ImageEntry;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class AppointmentInformation
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([

            ]);
    }

    public static function make(): array
    {
        return [
            Section::make('اطلاعات مالک')
                ->columns(3)
                ->schema([
                    TextEntry::make('owner.name')
                        ->label('نام'),
                    TextEntry::make('owner.profile.mobile')
                        ->label('شماره موبایل'),
                    TextEntry::make('owner.email')
                        ->label('ایمیل '),
                    TextEntry::make('owner.created_at')
                        ->jalaliDateTime('d M ,Y H:i A')
                        ->label('تاریخ عضویت'),
                ]),
            Section::make('اطلاعات حیوان')
                ->columns(3)
                ->schema([

                    ImageEntry::make('animal.avatar')
                        ->label('تصویر'),

                    TextEntry::make('animal.name')
                        ->label('نام'),

                    TextEntry::make('animal.species')
                        ->label('گونه'),

                    TextEntry::make('animal.gender')
                        ->label('جنسیت'),

                    TextEntry::make('animal.date_of_birth')
                        ->jalaliDate('d,M Y')
                        ->label('تاریخ تولد'),
                ]),

            Section::make('اطلاعات نوبت')
                ->columns(3)
                ->schema([

                    TextEntry::make('slot.date')
                        ->jalaliDate('d M ,Y')
                        ->label('تاریخ نوبت'),

                    TextEntry::make('slot.start_time')
                        ->jalaliDateTime('H:i A')
                        ->label('شروع'),

                    TextEntry::make('slot.end_time')
                        ->jalaliDateTime('H:i A')
                        ->label('پایان'),

                    TextEntry::make('status')
                        ->label('وضعیت نوبت')
                        ->badge(),

                    TextEntry::make('created_at')
                        ->label('تاریخ رزرو نوبت')
                        ->jalaliDateTime('d M , Y H:i A'),
                ]),
            Section::make('شرح مشکل')
                ->schema([
                    TextEntry::make('description')
                        ->label('توضیحات مالک')
                        ->markdown()
                        ->columnSpanFull(),
                ]),
            Section::make('اطلاعات پزشک')
                ->columns(3)
                ->schema([

                    TextEntry::make('slot.schedule.doctor.name')
                        ->label('نام'),

                    TextEntry::make('slot.schedule.doctor.email')
                        ->label('ایمیل '),

                    TextEntry::make('slot.schedule.doctor.profile.mobile')
                        ->label('شماره موبایل'),
                ]),
        ];
    }
}
