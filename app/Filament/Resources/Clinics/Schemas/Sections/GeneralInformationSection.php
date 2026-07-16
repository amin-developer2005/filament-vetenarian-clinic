<?php

namespace App\Filament\Resources\Clinics\Schemas\Sections;

use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;

class GeneralInformationSection extends Section
{
    protected function setUp(): void
    {
        parent::setUp();

        $this->build();
    }

    private function build(): void
    {
        $this
            ->label(__('resources/clinics.schema.form.sections.general.label'))
            ->icon('heroicon-o-building-office-2')
            ->description(__('resources/clinics.schema.form.sections.general.description'))
            ->schema([
                Grid::make(2)
                    ->schema([
                        TextInput::make('name')
                            ->label(__('resources/clinics.schema.form.components.name.label'))
                            ->placeholder(__('resources/clinics.schema.form.components.name.placeholder'))
                            ->required()
                            ->maxLength(255)
                            ->autofocus()
                            ->columnSpan(1),

                        TextInput::make('code')
                            ->label(__('resources/clinics.schema.form.components.code.label'))
                            ->placeholder(__('resources/clinics.schema.form.components.code.placeholder'))
                            ->required()
                            ->unique(ignoreRecord: true)
                            ->maxLength(50)
                            ->validationMessages([
                                'unique' => __('resources/clinics.validation.code.unique'),
                            ])
                            ->hint(__('resources/clinics.schema.form.components.code.hint'))
                            ->columnSpan(1),

                        TextInput::make('phone')
                            ->label(__('resources/clinics.schema.form.components.phone.label'))
                            ->placeholder(__('resources/clinics.schema.form.components.phone.placeholder'))
                            ->string()
                            ->maxLength(20)
                            ->columnSpan(1),

                        TextInput::make('email')
                            ->label(__('resources/clinics.schema.form.components.email.label'))
                            ->placeholder(__('resources/clinics.schema.form.components.email.placeholder'))
                            ->email()
                            ->unique(ignoreRecord: true)
                            ->maxLength(255)
                            ->validationMessages([
                                'unique' => __('resources/clinics.validation.email.unique'),
                            ])
                            ->hint(__('resources/clinics.schema.form.components.email.hint'))
                            ->hintIcon('heroicon-o-envelope')
                            ->columnSpan(1),

                        TextInput::make('website')
                            ->label(__('resources/clinics.schema.form.components.website.label'))
                            ->placeholder(__('resources/clinics.schema.form.components.website.placeholder'))
                            ->url()
                            ->maxLength(255)
                            ->columnSpan(1),

                        Toggle::make('is_active')
                            ->label(__('resources/clinics.schema.form.components.is_active.label'))
                            ->default(true)
                            ->inline(false)
                            ->onColor('success')
                            ->offColor('danger')
                            ->columnSpan(1),
                    ]),

                Textarea::make('description')
                    ->label(__('resources/clinics.schema.form.components.description.label'))
                    ->placeholder(__('resources/clinics.schema.form.components.description.placeholder'))
                    ->columnSpanFull()
                    ->maxLength(5000),
            ]);
    }
}
