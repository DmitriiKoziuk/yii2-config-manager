<?php
namespace DmitriiKoziuk\yii2ConfigManager\interfaces;

interface ConfigInterface
{
    /**
     * ConfigInterface constructor.
     * @param string $id Use for access to config value.
     * @param string $name Human readable name. View on backend config page.
     * @param ValueInterface[] $values
     */
    public function __construct(
        string $id,
        string $name,
        array $values
    );

    public function getId(): string;

    public function getName(): string;

    public function getDefaultValue(string $valueName);

    public function getValue(string $valueName);

    /**
     * @return ValueInterface[]
     */
    public function getAllValues(): array;

    public function getAllChangedValues(): array;

    /**
     * @param string $valueName
     * @param string|array $data
     */
    public function pathValueData(string $valueName, $data): void;

    public function patchValuesData(array $values): void;
}