<?php

namespace App\Filament\Resources;

use App\Filament\Resources\CaptureLinkedinResource\Pages;
use App\Models\CaptureLinkedin;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Mohamedsabil83\FilamentFormsTinyeditor\Components\TinyEditor;
use pxlrbt\FilamentExcel\Actions\Tables\ExportAction;
use pxlrbt\FilamentExcel\Actions\Tables\ExportBulkAction;
use pxlrbt\FilamentExcel\Columns\Column;
use pxlrbt\FilamentExcel\Exports\ExcelExport;

class CaptureLinkedinResource extends Resource
{
    protected static ?string $model = CaptureLinkedin::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('name')
                    ->autofocus()
                    ->required()
                    ->placeholder(__('Name')),
                TextInput::make('email')
                    ->placeholder(__('Email')),
                TextInput::make('phone')
                    ->placeholder(__('Phone')),
                TextInput::make('function')
                    ->placeholder(__('Function')),
                TextInput::make('entreprise')
                    ->placeholder(__('Entreprise')),
                TextInput::make('lieu')
                    ->placeholder(__('Lieu')),
                TinyEditor::make('comment')
                    ->columnSpanFull()->language('fr_FR')
                    ->placeholder(__('Comment')),
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
            ])->headerActions([
                ExportAction::make()->exports([
                    ExcelExport::make()->withColumns([
                        Column::make('name'),
                        Column::make('email'),
                        Column::make('phone'),
                        Column::make('function'),
                        Column::make('entreprise'),
                        Column::make('lieu'),
                        Column::make('created_at'),
                    ]),
                ]),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
//                    ExportBulkAction::make()->exports([
//                        ExcelExport::make()->withColumns([
//                            Column::make('name'),
//                            Column::make('email'),
//                            Column::make('phone'),
//                            Column::make('function'),
//                            Column::make('entreprise'),
//                            Column::make('lieu'),
//                            Column::make('created_at'),
//                        ]),
//                    ])
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
