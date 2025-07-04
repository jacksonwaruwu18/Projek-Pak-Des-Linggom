<?php

namespace App\Filament\Resources;

use App\Filament\Resources\BarangResource\Pages;
use App\Filament\Resources\BarangResource\RelationManagers;
use App\Models\Barang;
use Filament\Forms;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Infolist;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Illuminate\Database\Eloquent\Model;

class BarangResource extends Resource
{
    protected static ?string $model = Barang::class;

    protected static ?string $navigationIcon = 'heroicon-o-academic-cap';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('nama')
                    ->required(),
                TextInput::make('barcode')
                    ->required(),
                TextInput::make('satuan')
                    ->required(),
            ]);
    }

    public static function infolist(Infolist $infolist): Infolist
    {
        return $infolist->schema([
        TextEntry::make('nama'),
        TextEntry::make('barcode'),
        TextEntry::make('totalStock')
            ->numeric(locale: 'id')
            ->suffix(fn ($record) => " {$record->satuan}"),
    ]);
    }
    

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('nama')
                    ->label('Nama Barang')
                    ->sortable()
                    ->searchable(),
                TextColumn::make('barcode')
                    ->sortable()
                    ->searchable(),
                TextColumn::make('satuan'),
                TextColumn::make('totalstock')
                    ->numeric(locale: 'id')
                    ->suffix(fn (Model $record)=>"{$record -> satuan}"),
            ])
            ->filters([
                SelectFilter::make('satuan')
                    ->options(
                        function () {
                            return Barang::select('satuan')
                                ->pluck('satuan', 'satuan');
                        }
                    ),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])
            ->modifyQueryUsing(
                fn (Builder $query) => $query->with(['stocks'])
            );

    }

    public static function getRelations(): array
    {
        return [
                RelationManagers\StocksRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListBarangs::route('/'),
            'create' => Pages\CreateBarang::route('/create'),
            'view' => Pages\ViewBarang::route('/{record}'),
            'edit' => Pages\EditBarang::route('/{record}/edit'),
        ];
    }
}