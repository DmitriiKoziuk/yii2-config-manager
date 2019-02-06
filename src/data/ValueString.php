<?php
namespace DmitriiKoziuk\yii2ConfigManager\data;

use yii\base\Model;
use DmitriiKoziuk\yii2ConfigManager\interfaces\ValueStringInterface;

/**
 * @property string $value
 */
class ValueString extends Model implements ValueStringInterface
{
    private $_name;
    private $_defaultValue;
    private $_label;
    private $_description;

    public function __construct(
        string $name,
        string $defaultValue = '',
        string $label = '',
        string $description = ''
    ) {
        parent::__construct([]);
        $this->_name = $name;
        $this->_defaultValue = $defaultValue;
        $this->_label = $label;
        $this->_description = $description;
    }

    public function getName(): string
    {
        return $this->_name;
    }

    /**
     * @return string|null
     */
    public function getDefaultValue()
    {
        return $this->_defaultValue;
    }

    public function getValue(): string
    {
        $value = $this->value ?? $this->getDefaultValue();
        return $value ?? '';
    }

    public function setValue($value): void
    {
        $this->value = $value;
    }

    public function getLabel(): string
    {
        return $this->_label;
    }

    public function getDescription(): string
    {
        return $this->_description;
    }
}