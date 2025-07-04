<?php

namespace App\Filament\Resources;

use App\Filament\Resources\TransaksiStockResource\Pages;
use App\Filament\Resources\TransaksiStockResource\RelationManagers;
use App\JenisTransaksi;
use App\Models\Barang;
use App\Models\Gudang;
use App\Models\TransaksiStock;
use Filament\Forms;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Actions\Action;
use Filament\Tables\Actions\BulkAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;

class TransaksiStockResource extends Resource
{
    protected static ?string $model = TransaksiStock::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                DatePicker::make('tanggal')
                    ->label('Tanggal')
                    ->default(Carbon::today())
                    ->format('Y-m-d')
                    ->required(),
                Select::make('barang_id')
                    ->label('Barang')
                    ->options(Barang::all()->pluck('nama', 'id'))
                    ->searchable()
                    ->required(),
                Select::make('gudang_id')
                    ->label('Gudang')
                    ->options(Gudang::all()->pluck('nama', 'id'))
                    ->searchable()
                    ->required(),
                TextInput::make('keterangan')
                    ->label('Keterangan')
                    ->required(),
                Select::make('jenis')
                    ->label('Jenis Transaksi')
                    ->options(JenisTransaksi::class)
                    ->required(),
                TextInput::make('jumlah')
                    ->label('Jumlah')
                    ->numeric()
                    ->minValue(1)
                    ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('tanggal')
                    ->label('Tanggal')
                    ->sortable(),
                TextColumn::make('stock.barang.nama')
                    ->label('Barang')
                    ->sortable()
                    ->searchable(),
                TextColumn::make('stock.gudang.nama')
                    ->label('Gudang')
                    ->sortable()
                    ->searchable(),
                TextColumn::make('keterangan')
                    ->label('Keterangan')
                    ->searchable(),
                TextColumn::make('jenis')
                    ->label('Jenis Transaksi')
                    ->sortable(),
                TextColumn::make('jumlah')
                    ->label('Jumlah'),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Action::make('row_action')
                    ->action(
                        function (Model $record) {
                            Notification::make()
                                ->title("Transaksi {$record->jenis->value}")
                                ->info()
                                ->send();
                        }
                    ),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
                BulkAction::make('select_records')
                    ->action(
                        function (Collection $records) {
                            Notification::make()
                                ->title("{$records->count()} selected")
                                ->info()
                                ->send();
                        }
                    ),
            ])
            ->headerActions([
                Action::make('hello')
                    ->form([
                        TextInput::make('nama')
                            ->required(),
                    ])
                    ->action(
                        function (array $data) {
                            Notification::make()
                                ->title("Hello {$data['nama']}")
                                ->info()
                                ->send();
                        }
                    ),
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
            'index' => Pages\ListTransaksiStocks::route('/'),
            'create' => Pages\CreateTransaksiStock::route('/create'),
            'edit' => Pages\EditTransaksiStock::route('/{record}/edit'),
        ];
    }
}