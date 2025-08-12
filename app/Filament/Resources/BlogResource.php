<?php

namespace App\Filament\Resources;

use App\Filament\Resources\BlogResource\Pages;
use App\Filament\Resources\BlogResource\RelationManagers;
use App\Models\Blog;
use Filament\Forms;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class BlogResource extends Resource
{
    protected static ?string $model = Blog::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Forms\Form $form): Forms\Form
    {
        return $form
            ->schema([
                TextInput::make(name: 'title')
                    ->label(label: 'Titlu')
                    ->required()
                    ->reactive()
                    ->columnSpanFull()
                    ->afterStateUpdated(fn($state, callable $set) =>
                    $set('slug', \Str::slug($state))
                    ),
                TextInput::make(name: 'slug')
                    ->label(label: 'SEO URL')
                    ->required()
                    ->columnSpanFull()
                    ->unique(ignoreRecord: true),
                DateTimePicker::make(name: 'published_at')
                    ->label(label: 'Data publicării')
                    ->columnSpanFull()
                    ->default(now()),
                RichEditor::make(name: 'content')
                    ->label(label: 'Text articol')
                    ->required()
                    ->columnSpanFull(),
                FileUpload::make(name: 'image')
                    ->label(label: 'Imagine')
                    ->directory(directory: 'blogs')
                    ->columnSpanFull()
                    ->image(),
                Toggle::make(name: 'published')
                    ->label(label: 'Publicat')
                    ->inline(condition: false),
            ]);
    }

    public static function table(Tables\Table $table): Tables\Table
    {
        return $table
            ->columns([
                ImageColumn::make(name: 'image')
                    ->label(label: 'Imagine')
                    ->square(),
                TextColumn::make(name: 'title')
                    ->label(label: 'Titlu')
                    ->sortable()
                    ->limit(length: 50)
                    ->searchable(),
                TextColumn::make(name: 'published_at')
                    ->label(label: 'Data adăugării')
                    ->sortable()
                    ->dateTime(),
                IconColumn::make(name: 'published')
                    ->label(label: 'Publicat')
                    ->sortable()
                    ->boolean(),
            ])
            ->filters([
                Filter::make(name: 'published_at')
                    ->form([
                        DatePicker::make(name: 'created_from')->label(label: 'De la data'),
                        DatePicker::make(name: 'created_until')->label(label: 'Până la data'),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query
                            ->when($data['created_from'], fn($q) => $q->whereDate('published_at', '>=', $data['created_from']))
                            ->when($data['created_until'], fn($q) => $q->whereDate('published_at', '<=', $data['created_until']));
                    }),
            ])
            ->actions([
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
            'index' => Pages\ListBlogs::route('/'),
            'create' => Pages\CreateBlog::route('/create'),
            'edit' => Pages\EditBlog::route('/{record}/edit'),
        ];
    }
}
