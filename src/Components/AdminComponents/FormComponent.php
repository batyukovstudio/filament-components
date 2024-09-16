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
    public static function slugFormComponent(): TextInput
    {
        return TextInput::make('slug')
            ->disabled(fn(Page $livewire) => $livewire instanceof EditRecord)
            ->label('Ссылка')
            ->maxLength(255)
            ->required()
            ->live(debounce: 500)
            ->afterStateUpdated(function ($state, callable $set) {
                $set('slug', Str::slug($state));
            })
            ->unique(ignoreRecord: true);
    }

    public static function largeSlugFormComponent(): TextInput
    {
        return TextInput::make('slug')
            ->disabled(fn(Page $livewire) => $livewire instanceof EditRecord)
            ->label('Ссылка')
            ->maxLength(512)
            ->required()
            ->live(debounce: 500)
            ->afterStateUpdated(function ($state, callable $set) {
                $set('slug', Str::slug($state));

            })
            ->unique(ignoreRecord: true);
    }

    public static function nameFormComponent(): TextInput
    {
        return TextInput::make('name')
            ->label('Название')
            ->required()
            ->maxLength(255);
    }

    public static function nameAndSlugFormComponents(): Grid
    {
        return Grid::make()->schema([
            TextInput::make('name')
                ->label('Название')
                ->live(debounce: 500)
                ->afterStateUpdated(function (Set $set, $state, $livewire) {
                    if (!($livewire instanceof EditRecord)) {
                        $set('slug', Str::slug($state));
                    }
                })
                ->required()
                ->maxLength(255),
            self::slugFormComponent()
        ]);
    }

    public static function orderingFormComponent(): TextInput
    {
        return TextInput::make('ordering')
            ->label('Порядковый номер')
            ->required();
    }

    public static function descriptionFormComponent(): Textarea
    {
        return Textarea::make('description')
            ->label('Описание')
            ->maxLength(255)
            ->autosize()
            ->columnSpanFull();
    }

    public static function rolesAndPermissionsFormsComponents(): Grid
    {
        return Grid::make()->schema([
            Grid::make()->schema([
                TextInput::make('name')
                    ->label('Название (системное)')
                    ->required()
                    ->maxLength(50),
                TextInput::make('display_name')
                    ->label('Название')
                    ->maxLength(50),
                TextInput::make('guard_name')
                    ->label('Группа')
                    ->required()
                    ->maxLength(25)
            ])->columns(3),
            self::descriptionFormComponent()
        ]);
    }

    public static function fullNameFormComponents(): Grid
    {
        return Grid::make()->schema([
            TextInput::make('l_name')
                ->label('Фамилия')
                ->required()
                ->minLength(2)
                ->maxLength(191),
            TextInput::make('f_name')
                ->label('Имя')
                ->required()
                ->minLength(2)
                ->maxLength(191),
            TextInput::make('m_name')
                ->label('Отчество')
                ->minLength(2)
                ->maxLength(191),
        ])->columns(3);
    }
}
