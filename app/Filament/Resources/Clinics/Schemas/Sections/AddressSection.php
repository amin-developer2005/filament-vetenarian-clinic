<?php

namespace App\Filament\Resources\Clinics\Schemas\Sections;

use Filament\Forms\Components\RichEditor;
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
            ->label(__('resources/clinics.form.sections.general.label'))
            ->icon('heroicon-o-building-office-2')
            ->description(__('resources/clinics.form.sections.general.description'))
            ->schema([
                Grid::make(2)
                    ->schema([
                        TextInput::make('name')
                            ->label(__('resources/schema.form.components.name.label'))
                            ->placeholder(__('resources/schema.form.components.name.placeholder'))
                            ->required()
                            ->maxLength(255)
                            ->autofocus()
                            ->columnSpan(1),

                        TextInput::make('code')
                            ->label(__('resources/schema.form.components.code.label'))
                            ->placeholder(__('resources/schema.form.components.code.placeholder'))
                            ->required()
                            ->unique(ignoreRecord: true)
                            ->maxLength(50)
                            ->validationMessages([
                                'unique' => __('resources/validation.code.unique'),
                            ])
                            ->hint(__('resources/schema.form.components.code.hint'))
                            ->hintIcon('heroicon-o-information-circle')
                            ->columnSpan(1),

                        TextInput::make('phone')
                            ->label(__('resources/schema.form.components.phone.label'))
                            ->placeholder(__('resources/schema.form.components.phone.placeholder'))
                            ->tel()
                            ->maxLength(20)
                            ->columnSpan(1),

                        TextInput::make('email')
                            ->label(__('resources/schema.form.components.email.label'))
                            ->placeholder(__('resources/schema.form.components.email.placeholder'))
                            ->email()
                            ->unique(ignoreRecord: true)
                            ->maxLength(255)
                            ->validationMessages([
                                'unique' => __('resources/validation.email.unique'),
                            ])
                            ->hint(__('resources/schema.form.components.email.hint'))
                            ->hintIcon('heroicon-o-envelope')
                            ->columnSpan(1),

                        TextInput::make('website')
                            ->label(__('resources/schema.form.components.website.label'))
                            ->placeholder(__('resources/schema.form.components.website.placeholder'))
                            ->url()
                            ->maxLength(255)
                            ->columnSpan(1),

                        Toggle::make('is_active')
                            ->label(__('resources/schema.form.components.is_active.label'))
                            ->default(true)
                            ->inline(false)
                            ->onColor('success')
                            ->offColor('danger')
                            ->columnSpan(1),
                    ]),

                RichEditor::make('description')
                    ->label(__('resources/schema.form.components.description.label'))
                    ->placeholder(__('resources/schema.form.components.description.placeholder'))
                    ->columnSpanFull()
                    ->toolbarButtons([
                        'bold',
                        'italic',
                        'underline',
                        'strike',
                        'h2',
                        'h3',
                        'bulletList',
                        'orderedList',
                        'link',
                        'blockquote',
                        'undo',
                        'redo',
                    ])
                    ->maxLength(5000),
            ]);
    }
}
