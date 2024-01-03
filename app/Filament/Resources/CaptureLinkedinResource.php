<?php

namespace App\Filament\Resources;

use App\Filament\Resources\CaptureLinkedinResource\Pages;
use App\Filament\Resources\CaptureLinkedinResource\RelationManagers;
use App\Models\CaptureLinkedin;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class CaptureLinkedinResource extends Resource
{
    protected static ?string $model = CaptureLinkedin::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->autofocus()
                    ->required()
                    ->placeholder(__('Name')),
                Forms\Components\TextInput::make('email')
                    ->required()
                    ->placeholder(__('Email')),
                Forms\Components\TextInput::make('phone')
                    ->required()
                    ->placeholder(__('Phone')),
                Forms\Components\TextInput::make('function')
                    ->required()
                    ->placeholder(__('Function')),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('email')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('phone')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('function')
                    ->searchable()
                    ->sortable(),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
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
            'index' => Pages\ListCaptureLinkedins::route('/'),
            'create' => Pages\CreateCaptureLinkedin::route('/create'),
            'edit' => Pages\EditCaptureLinkedin::route('/{record}/edit'),
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        // return just the records that belong to the authenticated user
        return static::getModel()::query()
            ->withoutGlobalScope(SoftDeletingScope::class)
            ->where('owner_id', auth()->id());
    }
}
