<?php

namespace App\Filament\Components;

use Filament\Forms\Components\SpatieMediaLibraryFileUpload;
use Illuminate\Database\Eloquent\Model;
use Intervention\Image\Drivers\Gd\Driver;
use Intervention\Image\ImageManager;
use League\Flysystem\UnableToCheckFileExistence;
use Livewire\Features\SupportFileUploads\TemporaryUploadedFile;

class CustomSpatieMediaLibraryFileUpload extends SpatieMediaLibraryFileUpload
{
    //    protected string $view = 'filament.components.spatie-image-upload';

    protected string $wrapperClass;

    protected bool $withResponsiveImages = false;

    protected bool $withSizes = true;

    protected string|\Closure|null $storageName = null;

    protected array|\Closure|null $customProperties = null;

    protected array|\Closure|null $customPropertiesFromForm = null;

    protected int $inlineItems = 1;

    protected string|\Closure|null $conversion = null;

    public static function make($name): static
    {
        return parent::make($name);
    }

    protected function setUp(): void
    {
        parent::setUp();
        $this->wrapperClass = self::generateUniqueWrapperClass();

        $this->saveUploadedFileUsing(static function (SpatieMediaLibraryFileUpload $component, TemporaryUploadedFile $file, ?Model $record, callable $get): ?string {
            if (!method_exists($record, 'addMediaFromString')) {
                return $file;
            }

            try {
                if (!$file->exists()) {
                    return null;
                }
            } catch (UnableToCheckFileExistence $exception) {
                return null;
            }

            /**
             * Основные свойства изображения
             * @var array $customProperties
             */
            $customProperties = $component->getCustomProperties();

            $manager = new ImageManager(new Driver());

            $imageInstance = $manager->read($file);

            $customProperties['original_width'] = $imageInstance->width();
            $customProperties['original_height'] = $imageInstance->height();

            /**
             * Сохранить поля с формы в custom_properties
             * @var array $customPropertiesFromForm
             */
            $customPropertiesFromForm = $component->getCustomPropertiesFromForm();

            if (count($customPropertiesFromForm) > 0) {
                foreach ($customPropertiesFromForm as $propertyName => $formName) {
                    $customProperties[$propertyName] = $get($formName);
                }
            }

            /**
             * Сохранение изображения
             * @var  $media
             */
            $media = $record
                ->addMedia($file)
                ->usingFileName($component->isHasStorageName() ?
                    $component->getStorageName() . '.' . $file->extension() :
                    $component->getUploadedFileNameForStorage($file))
                ->withCustomProperties($customProperties)
                ->usingName($component->getMediaName($file) ?? '')
                ->withResponsiveImagesIf($component->isWithResponsiveImages())
                ->toMediaCollection($component->getCollection(), $component->getDiskName());

            return $media->getAttributeValue('uuid');
        });
    }

    public function customPropertiesFromForm(array|\Closure|null $properties): static
    {
        $this->customPropertiesFromForm = $properties;

        return $this;
    }

    public function getCustomPropertiesFromForm(): array
    {
        return $this->evaluate($this->customPropertiesFromForm) ?? [];
    }

    public function getWrapperClass(): string
    {
        return $this->wrapperClass;
    }

    protected function generateUniqueWrapperClass(): string
    {
        return 'spatie-upload-' . $this->getName();
    }

    public function withResponsiveImages(\Closure|bool $withResponsiveImages = true): static
    {
        $this->withResponsiveImages = $withResponsiveImages;

        return $this;
    }

    public function usingStorageName(\Closure|string|null $storageName = null): static
    {
        $this->storageName = $storageName;

        return $this;
    }

    public function inlineItems(\Closure|int $inlineItems = 1): static
    {
        $this->inlineItems = $inlineItems;

        return $this;
    }

    public function getInlineItems(): int
    {
        return $this->inlineItems;
    }

    public function getStorageName(): ?string
    {
        return $this->evaluate($this->storageName);
    }

    public function isHasStorageName(): bool
    {
        return $this->getStorageName() !== null && $this->getStorageName() !== '';
    }

    public function getInlineItemWidthPercent(): int
    {
        return 100 / $this->getInlineItems();
    }

    public function getInlineItemWidth(): string
    {
        return $this->getInlineItemWidthPercent() . '%';
    }

    public function isWithResponsiveImages(): bool
    {
        return $this->evaluate($this->withResponsiveImages) ?? false;
    }

    public function withSizes(\Closure|bool $withSizes = true): static
    {
        $this->withSizes = $withSizes;

        return $this;
    }

    public function isWithSizes(): bool
    {
        return $this->evaluate($this->withSizes) ?? true;
    }
}
