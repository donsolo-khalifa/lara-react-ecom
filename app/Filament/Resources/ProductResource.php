<?php

namespace App\Filament\Resources;

use Filament\Forms;
use Filament\Tables;
use App\Models\Product;
use Filament\Forms\Set;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Illuminate\Support\Str;
use App\Enums\ProductStatusEnum;
use Filament\Resources\Resource;
use Filament\Resources\Pages\Page;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Select;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\RichEditor;
use Filament\Pages\SubNavigationPosition;
use Filament\Tables\Filters\SelectFilter;
use Illuminate\Database\Eloquent\Builder;
use App\Filament\Resources\ProductResource\Pages;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Tables\Columns\SpatieMediaLibraryImageColumn;
use App\Filament\Resources\ProductResource\RelationManagers;
use App\Filament\Resources\ProductResource\Pages\EditProduct;
use App\Filament\Resources\ProductResource\Pages\ProductImages;
use App\Filament\Resources\ProductResource\Pages\ProductVariations;
use App\Filament\Resources\ProductResource\Pages\ProductVariationTypes;
use App\Models\ProductVariation;

class ProductResource extends Resource
{
    protected static ?string $model = Product::class;

    protected static ?string $navigationIcon = 'heroicon-c-queue-list';

    protected static SubNavigationPosition $subNavigationPosition = SubNavigationPosition::End;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Grid::make()
                    ->schema([
                        TextInput::make('title')
                            ->live(onBlur: true)
                            ->afterStateUpdated(fn(Set $set, ?string $state) => $set('slug', Str::slug($state)))
                            ->required()
                            ->columnSpan([1, 2, 2])
                            ,
                        TextInput::make('slug')
                            ->required()
                            ->columnSpan([1, 2, 2])
                            ,
                        Select::make('department_id')
                            ->relationship('department', 'name')
                            ->label(__('Department'))
                            ->searchable()
                            ->required()
                            ->reactive()
                            ->afterStateUpdated(fn(Set $set) => $set('category_id', null))
                            ->columnSpan([1,2,2])
                            ,
                        Select::make('category_id')
                            ->relationship('category', 'name', function (Builder $query, callable $get) {
                                $departmentId = $get('department_id');
                                if ($departmentId) {
                                    $query->where('department_id', $departmentId);
                                }
                            })
                            ->label(__('Category'))
                            ->preload()
                            ->searchable()
                            ->required()
                            ->columnSpan([1,2,2])
                            ,
                    ]),
                RichEditor::make('description')
                    ->required()
                    ->toolbarButtons([
                        'blockquote',
                        'bold',
                        'bulletList',
                        'h2',
                        'h3',
                        'italic',
                        'link',
                        'orderedList',
                        'redo',
                        'strike',
                        'underLine',
                        'undo',
                        'table',
                    ])
                    ->columnSpan([
                        2
                    ]),
                TextInput::make('price')
                    ->required()
                    ->numeric()
                    ->columnSpan([1,2])
                    ,
                TextInput::make('quantity')
                    ->integer()
                    ->columnSpan([1,2])
                    ,
                Select::make('status')
                    ->options(ProductStatusEnum::labels())
                    ->default(ProductStatusEnum::Draft->value)
                    ->required()
                    ->columnSpan([1,2])
                    ,
            ]);
    }


    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                SpatieMediaLibraryImageColumn::make('images')
                ->collection('images')
                ->limit(1)
                ->conversion('thumb')
                ->label('Images'),
                TextColumn::make('title')
                    ->sortable()
                    ->words(10)
                    ->searchable(),
                TextColumn::make('status')
                    ->badge()
                    ->colors(ProductStatusEnum::colors())
                    ->searchable(),
                TextColumn::make('department.name')
                    ->searchable(),
                TextColumn::make('category.name')
                    ->searchable(),
                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable(),

            ])
            ->defaultSort('created_at', 'desc')
            ->filters([
                SelectFilter::make('status')
                    ->options(ProductStatusEnum::labels()),
                SelectFilter::make('department_id')
                    ->relationship('department', 'name')
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
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
            'index' => Pages\ListProducts::route('/'),
            'create' => Pages\CreateProduct::route('/create'),
            'edit' => Pages\EditProduct::route('/{record}/edit'),
            'images' => Pages\ProductImages::route('/{record}/images'),
            'variation-types' => Pages\ProductVariationTypes::route('/{record}/variation-types'),
            'variations' => Pages\ProductVariations::route('/{record}/variations'),
        ];
    }

    public static function getRecordSubNavigation(Page $page): array
    {
        return
            $page->generateNavigationItems([
                EditProduct::class,
                ProductImages::class,
                ProductVariationTypes::class,
                ProductVariations::class,
        ]);
    }
}
