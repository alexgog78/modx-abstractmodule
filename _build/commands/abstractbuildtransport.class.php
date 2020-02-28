<?php

abstract class abstractBuildTransport extends abstractCommand
{
    /** @var string|bool */
    protected $dataFolder = false;

    protected $resolversFolder = false;

    /** @var bool */
    protected $namespace = false;

    /** @var bool */
    protected $coreFiles = false;

    /** @var bool */
    protected $assetsFiles = false;

    /** @var array|bool */
    protected $menus = false;

    /** @var array|bool */
    protected $events = false;

    /** @var array|bool */
    protected $plugins = false;

    /** @var modPackageBuilder */
    private $builder;

    private $vehicles = [];

    /**
     * BuildTransport constructor.
     * @param modX $modx
     * @param array $config
     */
    public function __construct(modX &$modx, $config = [])
    {
        parent::__construct($modx, $config);
        $this->modx->loadClass('transport.modPackageBuilder', '', false, true);
        $this->builder = new modPackageBuilder($this->modx);
        if ($this->dataFolder) {
            $this->getData();
        }
    }

    public function run()
    {
        $this->builder->createPackage(PKG_NAME_LOWER, PKG_VERSION, PKG_RELEASE);

        if ($this->namespace) {
            $this->addNamespace();
        }

        if ($this->coreFiles) {
            $this->vehicles['coreFiles'] = $this->addCoreFiles();
        }
        if ($this->assetsFiles) {
            $this->vehicles['assetsFiles'] = $this->addAssetsFiles();
        }
        if ($this->menus) {
            $this->vehicles['menus'] = $this->addMenus();
        }
        if ($this->events) {
            $this->vehicles['events'] = $this->addEvents();
        }
        if ($this->plugins) {
            $this->vehicles['plugins'] = $this->addPlugins();
        }

        if ($this->resolversFolder) {
            $this->log('DataBase');
            $this->vehicles['coreFiles']->resolve('php', ['source' => $this->resolversFolder . 'resolve.tables.php']);
        }

        foreach ($this->vehicles as $vehicle) {
            if (is_array($vehicle)) {
                foreach ($vehicle as $subVehicle) {
                    $this->builder->putVehicle($subVehicle);
                }
                continue;
            }
            $this->builder->putVehicle($vehicle);
        }




        $this->builder->pack();
    }

    private function getData()
    {
        $files = scandir($this->dataFolder);
        foreach ($files as $file) {
            if (preg_match('/transport.*?\.php$/i', $file)) {
                $property = str_replace([
                    'transport.',
                    '.php',
                ], '', $file);
                /** @noinspection PhpIncludeInspection */
                $this->$property = include $this->dataFolder . $file;
            }
        }
    }

    private function addNamespace()
    {
        $this->builder->registerNamespace(PKG_NAME_LOWER, false, true, '{core_path}components/' . PKG_NAME_LOWER . '/', '{assets_path}components/' . PKG_NAME_LOWER . '/');
    }

    /**
     * @return modTransportVehicle
     */
    private function addCoreFiles()
    {
        return $this->builder->createVehicle([
            'source' => MODX_CORE_PATH . 'components/' . PKG_NAME_LOWER,
            'target' => "return MODX_CORE_PATH . 'components/';",
        ], [
            'vehicle_class' => 'xPDOFileVehicle',
        ]);
    }

    /**
     * @return modTransportVehicle
     */
    private function addAssetsFiles()
    {
        return $this->builder->createVehicle([
            'source' => MODX_ASSETS_PATH . 'components/' . PKG_NAME_LOWER,
            'target' => "return MODX_ASSETS_PATH . 'components/';",
        ], [
            'vehicle_class' => 'xPDOFileVehicle',
        ]);
    }

    /**
     * @return array
     */
    private function addMenus()
    {
        $menus = [];
        foreach ($this->menus as $menuData) {
            $menu = $this->modx->newObject('modMenu');
            $menu->fromArray(array_merge([
                'parent' => 'components',
                'namespace' => PKG_NAME_LOWER,
            ], $menuData), '', true, true);
            $vehicle = $this->builder->createVehicle($menu, [
                xPDOTransport::PRESERVE_KEYS => true,
                xPDOTransport::UPDATE_OBJECT => true,
                xPDOTransport::UNIQUE_KEY => 'text',
            ]);
            $menus[] = $vehicle;
        }
        return $menus;
    }

    /**
     * @return array
     */
    private function addEvents()
    {
        $events = [];
        foreach ($this->events as $eventData) {
            $event = $this->modx->newObject('modEvent');
            $event->fromArray(array_merge([
                'service' => 6,
                'groupname' => PKG_NAME,
            ], $eventData), '', true, true);
            $vehicle = $this->builder->createVehicle($event, [
                xPDOTransport::PRESERVE_KEYS => true,
                xPDOTransport::UPDATE_OBJECT => true,
                xPDOTransport::UNIQUE_KEY => 'name',
            ]);
            $events[] = $vehicle;
        }
        return $events;
    }

    private function addPlugins()
    {
        $plugins = [];
        foreach ($this->plugins as $pluginData) {
            $plugin = $this->modx->newObject('modPlugin');
            if ($pluginData['static_file']) {
                $pluginData['plugincode'] = $this->getFileContent($pluginData['static_file']);
            }
            $plugin->fromArray(array_merge([
                'id' => 0,
                'category' => 0,
            ], $pluginData), '', true, true);

            $events = [];
            if (!empty($pluginData['events'])) {
                foreach ($pluginData['events'] as $pluginEvent) {
                    $event = $this->modx->newObject('modPluginEvent');
                    $event->fromArray([
                        'event' => $pluginEvent,
                        'priority' => 0,
                        'propertyset' => 0,
                    ], '', true, true);
                    $events[] = $event;
                }
                $plugin->addMany($events);
            }

            $vehicle = $this->builder->createVehicle($plugin, [
                xPDOTransport::PRESERVE_KEYS => true,
                xPDOTransport::UPDATE_OBJECT => true,
                xPDOTransport::UNIQUE_KEY => 'name',
                xPDOTransport::RELATED_OBJECTS => true,
                xPDOTransport::RELATED_OBJECT_ATTRIBUTES => [
                    'PluginEvents' => [
                        xPDOTransport::PRESERVE_KEYS => true,
                        xPDOTransport::UPDATE_OBJECT => false,
                        xPDOTransport::UNIQUE_KEY => [
                            'pluginid',
                            'event',
                        ],
                    ],
                ],
            ]);
            $plugins[] = $vehicle;
        }
        return $plugins;
    }


    /**
     * @param string $filePath
     * @return string
     */
    private function getFileContent(string $filePath)
    {
        $file = trim(file_get_contents($filePath));
        preg_match('#\<\?php(.*)#is', $file, $data);
        return rtrim(rtrim(trim($data[1]), '?>'));
    }
}
