<?php
namespace DmitriiKoziuk\yii2ConfigManager\data;

use DmitriiKoziuk\yii2ConfigManager\interfaces\ConfigInterface;
use DmitriiKoziuk\yii2ConfigManager\interfaces\ValueInterface;

class Config implements ConfigInterface
{
    /**
     * @var string
     */
    private $_id;

    /**
     * @var string
     */
    private $_name;

    /**
     * @var ValueInterface[]
     */
    private $_values;

    public function __construct(
        string $id,
        string $name,
        array $values
    ) {
        $this->_id = $id;
        $this->_name = $name;
        /** @var ValueInterface $value */
        foreach ($values as $value) {
            $this->_values[ $value->getName() ] = $value;
        }
    }

    public function getId(): string
    {
        return $this->_id;
    }

    public function getName(): string
    {
        return $this->_name;
    }

    public function getDefaultValue(string $valueName)
    {
        return $this->_values[ $valueName ]->getDefaultValue();
    }

    /**
     * @return ValueInterface[]
     */
    public function getAllValues(): array
    {
        return $this->_values;
    }

    public function getAllChangedValues(): array
    {
        $data = [];
        foreach ($this->getAllValues() as $value) {
            if ($value->getValue() != $value->getDefaultValue()) {
                $data[ $value->getName() ] = $value->getValue();
            }
        }
        return $data;
    }

    public function pathValueData(string $valueName, $data): void
    {
        $this->_values[ $valueName ]->setValue($data);
    }

    public function patchValuesData(array $values): void
    {
        foreach ($values as $valueName => $valueData) {
            $this->pathValueData($valueName, $valueData);
        }
    }
}