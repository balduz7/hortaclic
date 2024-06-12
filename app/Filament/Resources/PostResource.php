<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PostResource\Pages;
use App\Filament\Resources\PostResource\RelationManagers;
use App\Models\Post;
use Filament\Forms;
use App\Models\Visibility;
use Filament\Resources\Form;
use Filament\Resources\Resource;
use Filament\Resources\Table;
use Filament\Tables;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Livewire;
use App\Filament\Resources\PostResource\Pages\Log;
use Filament\Forms\Components\RichEditor;

class PostResource extends Resource
{
    protected static ?string $model = Post::class;

    protected static ?string $navigationIcon = 'heroicon-o-book-open';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Fieldset::make('File')
                    ->relationship('file')
                    ->saveRelationshipsWhenHidden()
                    ->schema([
                        Forms\Components\FileUpload::make('filepath')
                        ->required()
                        ->image()
                        ->maxSize(2048)
                        ->directory('uploads')
                        ->getUploadedFileNameForStorageUsing(function (Livewire\TemporaryUploadedFile $file): string {
                            return time() . '_' . $file->getClientOriginalName();
                        }),
                    ]),
                Forms\Components\Fieldset::make('Post')
                    ->schema([
                        Forms\Components\Select::make('author_id')
                        ->required()
                            ->relationship('author', 'name')
                            ->default(auth()->user()->id)
                            ->disablePlaceholderSelection(),
                        Forms\Components\Hidden::make('file_id'),
                        Forms\Components\RichEditor::make('post_name')
                        ->required()
                        ->maxLength(255),
                        Forms\Components\RichEditor::make('content')
                        ->required()
                        ->maxLength(255),

                        ]),
                        
                            
                       
                ]);
         
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('file_id')->translateLabel(),
                Tables\Columns\TextColumn::make('author_id')->translateLabel(),
                Tables\Columns\TextColumn::make('post_name')->translateLabel(),
                Tables\Columns\TextColumn::make('content')->translateLabel(),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()->translateLabel(),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()->translateLabel(),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
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
            'index' => Pages\ListPosts::route('/'),
            'create' => Pages\CreatePost::route('/create'),
            'view' => Pages\ViewPost::route('/{record}'),
            'edit' => Pages\EditPost::route('/{record}/edit'),
        ];
    }    
}
