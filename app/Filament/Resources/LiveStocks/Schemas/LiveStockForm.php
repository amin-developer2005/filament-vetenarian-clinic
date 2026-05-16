<?php

namespace App\Filament\Resources\LiveStocks\Schemas;

use App\Enums\LiveStockType;
use App\Enums\PanelRole;
use App\Models\Role;
use Filament\Facades\Filament;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Illuminate\Database\Eloquent\Builder;

class LiveStockForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make([
                    FileUpload::make('avatar')
                        ->label('عکس دام')
                        ->nullable()
                        ->previewable()
                        ->image()
                        ->imageEditor()
                        ->directory('avatars/liveStocks'),
                    TextInput::make('name')
                        ->label('نام دام')
                        ->required()
                        ->string(),
                    Select::make('type')
                        ->label('نوع دام')
                        ->required()
                        ->options(LiveStockType::class)
                        ->searchable()
                        ->preload()
                        ->native(false),
                    DatePicker::make('date_of_birth')
                        ->label('تاریخ تولد')
                        ->required()
                        ->date()
                        ->displayFormat('d M,Y')
                        ->native(false),
                ]),
                Section::make([
                    Select::make('owner_id')
                        ->label('مالک دام')
                        ->relationship('owner', 'name', modifyQueryUsing: function (Builder $query) {
                            $tenant = Filament::getTenant();
                            $doctorRole = Role::query()->firstWhere('name', PanelRole::Doctor);

                             return $tenant
                                ->users()
                                ->whereHas('roles', function ($query) use ($doctorRole) {
                                    return $query->where('id', $doctorRole->id);
                                });
                        })
                        ->required()
                        ->searchable()
                        ->preload()
                        ->native(false),
                ])
            ]);
    }
}
