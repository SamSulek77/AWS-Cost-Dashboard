<?php

namespace App\Filament\Resources;

use App\Filament\Resources\AccountNameResource\Pages;
use App\Models\AccountName;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\DatePicker;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Actions\DeleteAction;
use Filament\Tables\Actions\DeleteBulkAction;
use Filament\Tables\Filters\Filter;
use Illuminate\Database\Eloquent\Builder;

class AccountNameResource extends Resource
{
    protected static ?string $model = AccountName::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('accName')
                    ->label('Account Name')
                    ->required()
                    ->maxLength(255),

                TextInput::make('cost')
                    ->label('Cost')
                    ->numeric()
                    ->required(),

                DatePicker::make('date')
                    ->label('Date')
                    ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('id')->sortable(),
                TextColumn::make('accName')->label('Account Name')->sortable()->searchable(),
                TextColumn::make('cost')->label('Cost')->sortable(),
                TextColumn::make('date')
                    ->label('Date')
                    ->date('F Y') // e.g. June 2025
                    ->sortable(),
            ])
            ->filters([
                Filter::make('month')
                    ->form([
                        DatePicker::make('month')
                            ->label('Filter by Month')
                            ->displayFormat('F Y'),
                    ])
                    ->query(function (Builder $query, array $data) {
                        if (!empty($data['month'])) {
                            $query->whereMonth('date', $data['month']->month)
                                  ->whereYear('date', $data['month']->year);
                        }
                    }),
            ])
            ->actions([
                EditAction::make(),
                DeleteAction::make(),
            ])
            ->bulkActions([
                DeleteBulkAction::make(),
            ]);
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListAccountNames::route('/'),
            'create' => Pages\CreateAccountName::route('/create'),
            'edit' => Pages\EditAccountName::route('/{record}/edit'),
        ];
    }
    
}
