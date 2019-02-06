<?php
namespace DmitriiKoziuk\yii2ConfigManager\interfaces;


interface ValueInterface
{
    public function getName(): string;

    public function getDefaultValue();

    public function getLabel(): string;

    public function getValue();

    public function setValue($value): void;

    public function getDescription(): string;
}