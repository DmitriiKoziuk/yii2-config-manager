<?php
namespace DmitriiKoziuk\yii2ConfigManager\interfaces;


interface ValueStringInterface extends ValueInterface
{
    public function __construct(
        string $name,
        string $defaultValue = '',
        string $label = '',
        string $description = ''
    );
}