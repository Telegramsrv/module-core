<?php

namespace KodiCMS\Support\Loader;

use Profiler;
use KodiCMS\ModulesLoader\ModulesLoader as BaseModulesLoader;

class ModulesLoader extends BaseModulesLoader
{
    /**
     * @var string
     */
    protected $defaultContainerClass = ModuleContainer::class;

    /**
     * @param string      $moduleName
     * @param string|null $modulePath
     * @param string|null $namespace
     * @param string|null $moduleContainerClass
     * @param array       $moduleInfo
     *
     * @return $this
     */
    public function addModule($moduleName, $modulePath = null, $namespace = null, $moduleContainerClass = null, array $moduleInfo = [])
    {
        $token = Profiler::start('Modules Loader', $moduleName);

        if (is_null($namespace)) {
            $namespace = 'KodiCMS\\'.$moduleName;
        }

        parent::addModule($moduleName, $modulePath, $namespace, $moduleContainerClass, $moduleInfo);
        Profiler::stop($token);

        return $this;
    }
}
