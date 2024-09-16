<?php

namespace Batyukovstudio\BatMedia\Filament\Components\AdminComponents;

use Filament\Tables\Columns\TextColumn;
use Illuminate\Support\Arr;

class TableComponent
{
    public static function idTableComponent(): array
    {
        return [
            TextColumn::make('id')
                ->label('ID')
                ->sortable()
                ->toggleable()
        ];
    }

    public static function idAndNameTableComponents(): array
    {
        return Arr::collapse([
            self::idTableComponent(),
            self::nameTableComponent()
        ]);
    }

    public static function nameTableComponent(): array
    {
        return [
            TextColumn::make('name')
                ->label('Название')
                ->searchable()
                ->toggleable()
                ->sortable()
                ->limit(45),
        ];
    }

    public static function slugTableComponent(): array
    {
        return [
            TextColumn::make('slug')
                ->sortable()
                ->searchable()
                ->toggleable()
                ->limit(50)
                ->label('Ссылка')
        ];

    }

    public static function orderingTableComponent(): array
    {
        return [
            TextColumn::make('ordering')
                ->label('Сортировка')
                ->sortable()
        ];
    }

    public static function descriptionTableComponent(): array
    {
        return [
            TextColumn::make('description')
                ->label('Описание')
                ->limit(50)
                ->toggleable()
        ];
    }

    public static function rolesAndPermissionsTablesComponents(): array
    {
        $tableColumns = Arr::collapse([
            self::idTableComponent(),
            [TextColumn::make('name')
                ->label('Название (системное)')
                ->searchable()
                ->toggleable(),
                TextColumn::make('display_name')
                    ->label('Название')
                    ->searchable()
                    ->toggleable(),
                TextColumn::make('guard_name')
                    ->label('Группа')
                    ->searchable()
                    ->toggleable()],
            self::descriptionTableComponent(),
            self::createdAtTableComponent()
        ]);
        return $tableColumns;
    }

    public static function fullNameTableComponents(): array
    {
        return [
            TextColumn::make('l_name')
                ->label('Фамилия')
                ->limit(50)
                ->sortable()
                ->toggleable(),
            TextColumn::make('f_name')
                ->label('Имя')
                ->limit(50)
                ->sortable()
                ->toggleable(),
            TextColumn::make('m_name')
                ->label('Отчество')
                ->limit(50)
                ->sortable()
                ->toggleable()
        ];
    }

    public static function createdAtTableComponent(): array
    {
        return [
            TextColumn::make('created_at')
                ->sortable()
                ->label('Дата создания')
                ->dateTime()
        ];
    }

    public static function publishedAtTableComponent(): array
    {
        return [
            TextColumn::make('published_at')
                ->sortable()
                ->label('Дата публикации')
                ->dateTime()
        ];
    }
}
