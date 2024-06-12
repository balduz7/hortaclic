<?php

namespace App\Filament\Resources;

use App\Filament\Resources\FileResource\Pages;
use App\Filament\Resources\FileResource\RelationManagers;
use App\Models\File;
use Filament\Forms;
use Filament\Resources\Form;
use Filament\Forms\Components\FileUpload;
use Filament\Resources\Resource;
use Filament\Resources\Table;
use Filament\Tables;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Livewire\TemporaryUploadedFile;
use Illuminate\Support\Facades\Storage;

class FileResource extends Resource
{
    protected static ?string $model = File::class;

    protected static ?string $navigationIcon = 'heroicon-o-document';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                // Declara un camp de pujada de fitxer al formulari
                FileUpload::make('filepath')
                    ->label(__('Arxiu'))
                    // Fa que el valor del camp sigui requerit
                    ->required()
                    // Especifica que s'ha d'emplenar amb una imatge
                    ->image()
                    // Limita el tamany de pujada a 2MB
                    ->maxSize(2048)
                    // Indica a on es pujarà l'arxiu
                    ->directory('uploads')
                    // Modifica el nom de l'arxiu per incloure el temps de la pujada
                    ->getUploadedFileNameForStorageUsing(function (TemporaryUploadedFile $file): string {
                        return time() . '_' . $file->getClientOriginalName();
                    }),

                // Declara un camp de text al formulari
                //Forms\Components\TextInput::make('filesize')
                    // Fa que el valor del camp sigui requerit
                    //->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('filepath')
                    ->label(__('File path')),
                Tables\Columns\TextColumn::make('filesize')
                    ->label(__('File size')),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->translateLabel(),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->translateLabel(),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make()
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
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
            'index' => Pages\ListFiles::route('/'),
            'create' => Pages\CreateFile::route('/create'),
            'view' => Pages\ViewFile::route('/{record}'),
            'edit' => Pages\EditFile::route('/{record}/edit'),
        ];
    }    
}
