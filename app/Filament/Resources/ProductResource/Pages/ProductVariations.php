<?php

namespace App\Filament\Resources\ProductResource\Pages;

use Filament\Actions;
use Filament\Forms\Form;
use Filament\Forms\Components\Select;
use App\Enums\ProductVariationTypeEnum;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Pages\EditRecord;
use App\Filament\Resources\ProductResource;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\SpatieMediaLibraryFileUpload;
use Illuminate\Database\Eloquent\Model;

class ProductVariations extends EditRecord
{
    protected static string $resource = ProductResource::class;
    protected static ?string $navigationIcon = 'heroicon-s-clipboard-document-list';
    protected static ?string $title = 'Variations';


    public function form(Form $form): Form
    {

        $types = $this->record->variationTypes;
        $fields = [];

        foreach ($types as $i => $type) {
            $fields[] = TextInput::make('variation_type_' . ($type->id) . '.id')
                ->hidden();
            $fields[] = TextInput::make('variation_type_' . ($type->id) . '.name')
                ->label($type->name);
        }
        return  $form
            ->schema([
                Repeater::make('variations')
                    ->label(false)
                    ->collapsible()
                    ->addable(false)
                    ->defaultItems(1)
                    ->schema([
                        Section::make()
                            ->schema($fields)
                            ->columns(3),
                        TextInput::make('quantity')
                            ->label('Quantity')
                            ->numeric(),
                        TextInput::make('price')
                            ->label('Price')
                            ->numeric(),
                    ])
                    ->columns(2)
                    ->columnSpan(2)
            ]);
    }

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }

    protected function mutateFormDataBeforeFill(array $data): array
    {
        $variations = $this->record->variations->toArray();
        $data['variations'] = $this->mergeCartesianWithExisting($this->record->variationTypes, $variations);
        return $data;
    }

    private function mergeCartesianWithExisting($variationTypes, $existingData): array
    {
        $defaultQuantity = $this->record->quantity;
        $defaultPrice = $this->record->price;
        $cartesianProduct = $this->cartesianProduct($variationTypes, $defaultQuantity, $defaultPrice);
        $mergeResult = [];

        foreach ($cartesianProduct as $product) {
            $optionIds = collect($product)
                ->filter(fn($value, $key) => str_starts_with($key, 'variation_type_'))
                ->map(fn($option) => $option['id'])
                ->values()
                ->toArray();

            $match = array_filter($existingData, function ($existingOption) use ($optionIds) {
                return $existingOption['variation_type_option_ids'] === $optionIds;
            });


            if (!empty($match)) {
                $existingEntry = reset($match);
                $product['id'] = $existingEntry['id'];
                $product['quantity'] = $existingEntry['quantity'];
                $product['price'] = $existingEntry['price'];
            } else {
                $product['quantity'] = $defaultQuantity;
                $product['price'] = $defaultPrice;
            }

            $mergeResult[] = $product;
        }


        return $mergeResult;
    }

    private function cartesianProduct($variationTypes, $defaultQuantity = null, $defaultPrice = null): array
    {



        $result = [[]];

        foreach ($variationTypes as $index => $variationType) {
            $temp = [];

            foreach ($variationType->options as $option) {

                foreach ($result as $combination) {
                    $newCombination = $combination + [
                        'variation_type_' . ($variationType->id) => [
                            'id' => $option->id,
                            'name' => $option->name,
                            'label' => $variationType->name,
                        ],

                    ];

                    $temp[] = $newCombination;
                }
            }
            $result = $temp;
        }

        foreach ($result as &$combination) {
            if (count($combination) === count($variationTypes)) {
                $combination['quantity'] = $defaultQuantity;
                $combination['price'] = $defaultPrice;
            }
        }

        return $result;
    }

    // protected function mutateFormDataBeforeSave(array $data): array
    // {

    //     $formattedData = [];

    //     foreach ($data['variations'] as $option) {
    //         $variationTypeOptionIds = []; // Initialize for each option to avoid carryover issues

    //         foreach ($this->record->variationTypes as $i => $variationType) {
    //             $variationTypeOptionIds[] = $option['variation_type_' . ($variationType->id)]['id'];
    //         }

    //         $quantity = $option['quantity'];
    //         $price = $option['price'];


    //         $formattedData[] = [
    //             'id' => $option['id'],
    //             'variation_type_option_ids' => $variationTypeOptionIds,
    //             'quantity' => $quantity,
    //             'price' => $price,
    //         ];
    //     }
    //     $data['variations'] = $formattedData;
    //     return $data;
    // }

    protected function mutateFormDataBeforeSave(array $data): array
    {
        $formattedData = [];

        foreach ($data['variations'] as $option) {
            $variationTypeOptionIds = []; // Initialize for each option

            // Gather variation type option IDs
            foreach ($this->record->variationTypes as $i => $variationType) {
                $variationKey = 'variation_type_' . ($variationType->id);

                if (isset($option[$variationKey])) {
                    $variationTypeOptionIds[] = $option[$variationKey]['id'];
                }
            }

            // Safely retrieve optional fields with default values
            $quantity = $option['quantity'] ?? 0;
            $price = $option['price'] ?? 0;
            $id = $option['id'] ?? null; // Default to null if `id` is not present

            // Format data
            $formattedData[] = [
                'id' => $id,
                'variation_type_option_ids' => $variationTypeOptionIds,
                'quantity' => $quantity,
                'price' => $price,
            ];
        }

        $data['variations'] = $formattedData;
        return $data;
    }


    protected function handleRecordUpdate(Model $record, array $data): Model
    {

        $variations = $data['variations'];
        unset($data['variations']);

        $variations = collect($variations)->map(function ($variation) {
            return [
                'id' => $variation['id'],
                'variation_type_option_ids' => json_encode($variation['variation_type_option_ids']),
                'quantity' => $variation['quantity'],
                'price' => $variation['price'],
            ];
        })
            ->toArray();

        // $record->update($data);
        $record->variations()->upsert($variations, ['id'], ['variation_type_option_ids', 'quantity', 'price']);

        return $record;
    }
}
