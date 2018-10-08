<?php

namespace wii\plugin\api;

use wii\helpers\Inflector;

/**
 * 生成字段类
 */
class GenField
{

    protected $namespace;

    /**
     * @return mixed
     */
    public function getNamespace() {
        return $this->namespace;
    }

    /**
     * @param mixed $namespace
     *
     * @return GenField
     */
    public function setNamespace($namespace) {
        $this->namespace = $namespace;

        return $this;
    }


    public function start($configArray, $path) {
        $namespace = $this->getNamespace();
        foreach ($configArray as $row) {
            if (empty($row)) {
                continue;
            }
            $name = trim($row['name']);
            $desc = trim($row['desc']);
            $ucfirstName = Inflector::camelize($name);
            $name = lcfirst($ucfirstName);
            $result = require __DIR__ . '/GenFieldTemplate.php';
            file_put_contents("{$path}/{$ucfirstName}.php", $result);
        }
    }

}