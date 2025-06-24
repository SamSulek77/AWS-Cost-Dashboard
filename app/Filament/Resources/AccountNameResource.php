<?php

namespace App\Filament\Resources;

use App\Filament\Resources\AccountNameResource\Pages;
use App\Filament\Resources\AccountNameResource\RelationManagers;
use App\Models\AccountName;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Actions\DeleteAction;
use Filament\Tables\Actions\DeleteBulkAction;
use Filament\Forms\Components\TextInput;


use Filament\Tables\Table;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class AccountNameResource extends Resource
{
    protected static ?string $model = AccountName::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                //
                TextInput::make('accName')->required()->maxLength(255),
            TextInput::make('cost')->numeric()->required(),
            TextInput::make('date')->required()->maxLength(255),

            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                //
                TextColumn::make('id')->sortable(),
                TextColumn::make('accName')->sortable(),
                TextColumn::make('cost')->sortable(),
                TextColumn::make('date')->sortable(),
                




            ])
            ->filters([
                //
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
        return [
            //
        ];
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
