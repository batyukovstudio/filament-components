<?php

namespace Batyukovstudio\BatMedia\Filament\Components\AdminComponents;

use Filament\Tables\Columns\TextColumn;
use Illuminate\Support\Arr;

class TableComponent
{
    public static function idTableComponent(string $label = 'ID'): TextColumn
    {
        return
            TextColumn::make('id')
                ->label($label)
                ->sortable()
                ->toggleable();
    }

    public static function idAndNameTableComponents(): array
    {
        return Arr::collapse([
            self::idTableComponent(),
            self::nameTableComponent()
        ]);
    }

    public static function nameTableComponent(string $label = 'Название', int $limit = 45): TextColumn
    {
        return
            TextColumn::make('name')
                ->label($label)
                ->searchable()
                ->toggleable()
                ->sortable()
                ->limit($limit);
    }

    public static function slugTableComponent(string $label = 'Ссылка', int $limit = 50): TextColumn
    {
        return
            TextColumn::make('slug')
                ->sortable()
                ->searchable()
                ->toggleable()
                ->limit($limit)
                ->label($label);
    }

    public static function orderingTableComponent(string $label = 'Сортировка'): TextColumn
    {
        return
            TextColumn::make('ordering')
                ->label($label)
                ->sortable();
    }

    public static function descriptionTableComponent(int $limit = 50): TextColumn
    {
        return
            TextColumn::make('description')
                ->label('Описание')
                ->limit($limit)
                ->toggleable();
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

    public static function fullNameTableComponents(int $limit = 50): array
    {
        return [
            TextColumn::make('l_name')
                ->label('Фамилия')
                ->limit($limit)
                ->sortable()
                ->toggleable(),
            TextColumn::make('f_name')
                ->label('Имя')
                ->limit($limit)
                ->sortable()
                ->toggleable(),
            TextColumn::make('m_name')
                ->label('Отчество')
                ->limit($limit)
                ->sortable()
                ->toggleable()
        ];
    }

    public static function createdAtTableComponent(): TextColumn
    {
        return
            TextColumn::make('created_at')
                ->sortable()
                ->label('Дата создания')
                ->dateTime();
    }

    public static function publishedAtTableComponent(): TextColumn
    {
        return
            TextColumn::make('published_at')
                ->sortable()
                ->label('Дата публикации')
                ->dateTime();
    }
}
