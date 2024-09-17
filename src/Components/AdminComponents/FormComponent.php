<?php

namespace Batyukovstudio\BatMedia\Filament\Components\AdminComponents;

use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Set;
use Filament\Resources\Pages\EditRecord;
use Filament\Resources\Pages\Page;
use Illuminate\Support\Str;

class FormComponent
{
    public static function slugFormComponent(string $label='Ссылка', int $maxLength=255,int $liveDebounce=500): TextInput
    {
        return TextInput::make('slug')
            ->disabled(fn(Page $livewire) => $livewire instanceof EditRecord)
            ->label($label)
            ->maxLength($maxLength)
            ->required()
            ->live(debounce: $liveDebounce)
            ->afterStateUpdated(function ($state, callable $set) {
                $set('slug', Str::slug($state));
            })
            ->unique(ignoreRecord: true);
    }

    public static function largeSlugFormComponent(string $label='Ссылка',int $maxLength=512,int $liveDebounce=500): TextInput
    {
        return TextInput::make('slug')
            ->disabled(fn(Page $livewire) => $livewire instanceof EditRecord)
            ->label($label)
            ->maxLength($maxLength)
            ->required()
            ->live(debounce: $liveDebounce)
            ->afterStateUpdated(function ($state, callable $set) {
                $set('slug', Str::slug($state));

            })
            ->unique(ignoreRecord: true);
    }

    public static function nameFormComponent(string $label='Название',int $maxLength=255): TextInput
    {
        return TextInput::make('name')
            ->label($label)
            ->required()
            ->maxLength($maxLength);
    }

    public static function nameAndSlugFormComponents(string $label='Название',int $maxLength=255,int $liveDebounce=500): Grid
    {
        return Grid::make()->schema([
            TextInput::make('name')
                ->label($label)
                ->live(debounce: $liveDebounce)
                ->afterStateUpdated(function (Set $set, $state, $livewire) {
                    if (!($livewire instanceof EditRecord)) {
                        $set('slug', Str::slug($state));
                    }
                })
                ->required()
                ->maxLength($maxLength),
            self::slugFormComponent()
        ]);
    }

    public static function orderingFormComponent(string $label='Порядковый номер'): TextInput
    {
        return TextInput::make('ordering')
            ->label($label)
            ->required();
    }

    public static function descriptionFormComponent(string $label='Описание',int $maxLength=255): Textarea
    {
        return Textarea::make('description')
            ->label($label)
            ->maxLength($maxLength)
            ->autosize()
            ->columnSpanFull();
    }

    public static function rolesAndPermissionsFormsComponents(int $maxLength=50): Grid
    {
        return Grid::make()->schema([
            Grid::make()->schema([
                TextInput::make('name')
                    ->label('Название (системное)')
                    ->required()
                    ->maxLength($maxLength),
                TextInput::make('display_name')
                    ->label('Название')
                    ->maxLength($maxLength),
                TextInput::make('guard_name')
                    ->label('Группа')
                    ->required()
                    ->maxLength($maxLength)
            ])->columns(3),
            self::descriptionFormComponent()
        ]);
    }

    public static function fullNameFormComponents(int $maxLength=191,int $minLength=2): Grid
    {
        return Grid::make()->schema([
            TextInput::make('l_name')
                ->label('Фамилия')
                ->required()
                ->minLength($minLength)
                ->maxLength($maxLength),
            TextInput::make('f_name')
                ->label('Имя')
                ->required()
                ->minLength($minLength)
                ->maxLength($maxLength),
            TextInput::make('m_name')
                ->label('Отчество')
                ->minLength($minLength)
                ->maxLength($maxLength),
        ])->columns(3);
    }
}
