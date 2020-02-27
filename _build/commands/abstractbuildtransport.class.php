<?php

abstract class abstractBuildTransport extends abstractCommand
{
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
    protected $builder;

    /**
     * BuildTransport constructor.
     * @param modX $modx
     * @param array $config
     */
    public function __construct(modX &$modx, $config = [])
    {
        parent::__construct($modx, $config);
        $this->modx->loadClass('transport.modPackageBuilder','',false, true);
        $this->builder = new modPackageBuilder($this->modx);
        $this->getData();
    }

    public function run()
    {
        $this->builder->createPackage(PKG_NAME_LOWER, PKG_VERSION, PKG_RELEASE);

        if ($this->namespace) {
            $this->addNamespace();
        }
        if ($this->coreFiles) {
            $this->addCore();
        }
        if ($this->assetsFiles) {
            $this->addAssets();
        }
        if ($this->menus) {
            $this->addMenus();
        }
        if ($this->events) {
            $this->addEvents();
        }

        $this->builder->pack();
    }

    protected function getData()
    {
        return;
    }

    private function addNamespace()
    {
        $this->builder->registerNamespace(
            PKG_NAME_LOWER,
            false,
            true,
            '{core_path}components/' . PKG_NAME_LOWER . '/',
            '{assets_path}components/' . PKG_NAME_LOWER . '/'
        );
    }

    private function addCore()
    {
        $vehicle = $this->builder->createVehicle([
            'source' => MODX_CORE_PATH . 'components/' . PKG_NAME_LOWER,
            'target' => "return MODX_CORE_PATH . 'components/';",
        ], [
            'vehicle_class' => 'xPDOFileVehicle',
        ]);
        $this->builder->putVehicle($vehicle);
        unset($vehicle);
    }

    private function addAssets()
    {
        $vehicle = $this->builder->createVehicle([
            'source' => MODX_ASSETS_PATH . 'components/' . PKG_NAME_LOWER,
            'target' => "return MODX_ASSETS_PATH . 'components/';",
        ], [
            'vehicle_class' => 'xPDOFileVehicle',
        ]);
        $this->builder->putVehicle($vehicle);
        unset($vehicle);
    }

    private function addMenus()
    {
        foreach ($this->menus as $menuKey => $menuItem) {
            $menu = $this->modx->newObject('modMenu');
            $menu->fromArray(array_merge([
                'text' => $menuKey,
                'parent' => 'components',
                'namespace' => PKG_NAME_LOWER,
            ], $menuItem), '', true, true);
            $vehicle = $this->builder->createVehicle($menu, [
                xPDOTransport::PRESERVE_KEYS => true,
                xPDOTransport::UPDATE_OBJECT => true,
                xPDOTransport::UNIQUE_KEY => 'text',
            ]);
            $this->builder->putVehicle($vehicle);
        }
        unset($vehicle, $menu);
    }

    private function addEvents()
    {
        foreach ($this->events as $eventName) {
            $event = $this->modx->newObject('modEvent');
            $event->fromArray([
                'name' => $eventName,
                'service' => 6,
                'groupname' => PKG_NAME,
            ], '', true, true);
            $vehicle = $this->builder->createVehicle($event, [
                xPDOTransport::PRESERVE_KEYS => true,
                xPDOTransport::UPDATE_OBJECT => true,
                xPDOTransport::UNIQUE_KEY => 'name',
            ]);
            $this->builder->putVehicle($vehicle);
        }
    }

    //TODO
    private function addPlugins()
    {

    }
}
