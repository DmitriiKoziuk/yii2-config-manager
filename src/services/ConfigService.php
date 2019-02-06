<?php
namespace DmitriiKoziuk\yii2ConfigManager\services;

use DmitriiKoziuk\yii2ConfigManager\interfaces\ConfigInterface;

class ConfigService
{
    /**
     * @var ConfigInterface[]
     */
    private $_configs = [];

    /**
     * @var string
     */
    private $_saveLocation;

    public function __construct(string $saveLocation)
    {
        $this->_saveLocation = $saveLocation;
    }

    public function registerConfig(ConfigInterface $config)
    {
        $loadedValues = $this->_loadConfigValues($config->getId());
        $config->patchValuesData($loadedValues);
        $this->_configs[ $config->getId() ] = $config;
    }

    /**
     * @param string $configId
     * @param string $valueName
     * @return null|string|array
     */
    public function getValue(string $configId, string $valueName)
    {
        return $this->_configs[ $configId ]->getDefaultValue($valueName);
    }

    public function getConfigList(): array
    {
        $list = [];
        foreach ($this->_configs as $config) {
            $list[ $config->getId() ] = $config->getName();
        }
        return $list;
    }

    public function getConfig(string $configId): ConfigInterface
    {
        return $this->_configs[ $configId ];
    }

    /**
     * Only update value data in config objects. Do not save changes to config files.
     * @see save() if you want to save changis to files.
     * @param string $configId
     * @param array $values
     * @return self
     */
    public function updateConfigValues(string $configId, array $values): self
    {
        $this->_configs[ $configId ]->patchValuesData($values);
        return $this;
    }

    public function save(string $configId = null)
    {
        if (empty($configId)) {
            foreach ($this->_configs as $config) {
                $this->_saveConfigValues(
                    $config->getId(),
                    $config->getAllChangedValues()
                );
            }
        } else {
            $this->_saveConfigValues(
                $configId,
                $this->_configs[ $configId ]->getAllChangedValues()
            );
        }
    }

    private function _saveConfigValues(string $configId, array $valuesData)
    {
        $file = $this->_saveLocation . '/' . $this->_getConfigFileName($configId);
        $data = serialize($valuesData);
        if (is_writeable($this->_saveLocation)) {
            file_put_contents($file, $data);
        }
    }

    private function _loadConfigValues(string $configId): array
    {
        $values = [];
        $file = $this->_saveLocation . '/' . $this->_getConfigFileName($configId);
        if (file_exists($file)) {
            $values = unserialize(file_get_contents($file));
        }
        return $values;
    }

    private function _getConfigFileName(string $configId)
    {
        return $configId . '.data';
    }
}