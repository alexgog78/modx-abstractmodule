<?php

if (!class_exists('AbstractCLI')) {
    require_once dirname(__FILE__) . '/abstractcli.class.php';
}

abstract class AbstractBuildTransport extends AbstractCLI
{
    /** @var string */
    protected $buildPath = '';

    /** @var bool */
    protected $namespace = false;

    /** @var bool */
    protected $coreFiles = false;

    /** @var bool */
    protected $assetsFiles = false;

    /** @var array */
    protected $data = [];

    /** @var array */
    protected $resolvers = [];

    /** @var array */
    protected $attributes = [];

    /** @var modPackageBuilder */
    private $builder;

    /** @var array */
    private $config;

    /** @var modCategory|null */
    private $category = NULL;

    /**
     * AbstractBuildTransport constructor.
     */
    public function __construct()
    {
        parent::__construct();
        $this->modx->loadClass('transport.modPackageBuilder', '', false, true);
        $this->builder = new modPackageBuilder($this->modx);
        $corePath = MODX_CORE_PATH . 'components/' . PKG_NAME_LOWER . '/';
        $this->config = [
            'pluginsPath' => $corePath . 'elements/plugins/',
            'snippetsPath' => $corePath . 'elements/snippets/',
            'chunksPath' => $corePath . 'elements/chunks/',
            'changelogPath' => $corePath . 'docs/changelog.txt',
            'licensePath' => $corePath . 'docs/license.txt',
            'readmePath' => $corePath . 'docs/readme.txt',
        ];
    }

    public function setCode()
    {
        $this->getData();
        $this->getResolvers();

        $this->builder->createPackage(PKG_NAME_LOWER, PKG_VERSION, PKG_RELEASE);

        if ($this->namespace) {
            $this->addNamespace();
        }
        if ($this->coreFiles) {
            $this->addCoreFiles();
        }
        if ($this->assetsFiles) {
            $this->addAssetsFiles();
        }

        foreach ($this->data as $key => $items) {
            $method = 'add' . ucfirst($key);
            $this->$method($items);
        }

        if ($this->category) {
            $this->addCategory();
        }

        $this->addAttributes();
        $this->addResolvers();

        $this->builder->pack();

        $this->log(PKG_NAME . ' package generated');
    }

    protected function getData()
    {

        $dataFolder = $this->buildPath . 'data/';
        if (!is_dir($dataFolder)) {
            return;
        }
        $files = scandir($dataFolder);
        foreach ($files as $file) {
            if (preg_match('/transport.*?\.php$/i', $file)) {
                $key = str_replace([
                    'transport.',
                    '.php',
                ], '', $file);
                /** @noinspection PhpIncludeInspection */
                $this->data[$key] = include $dataFolder . $file;
            }
        }
    }

    protected function getResolvers()
    {
        $resolversFolder = $this->buildPath . 'resolvers/';
        if (!is_dir($resolversFolder)) {
            return;
        }
        $files = scandir($resolversFolder);
        foreach ($files as $file) {
            if (preg_match('/resolve.*?\.php$/i', $file)) {
                $key = str_replace([
                    'resolve.',
                    '.php',
                ], '', $file);
                /** @noinspection PhpIncludeInspection */
                $this->resolvers[$key] = $resolversFolder . $file;
            }
        }
    }

    private function addNamespace()
    {
        $this->builder->registerNamespace(PKG_NAME_LOWER, false, true, '{core_path}components/' . PKG_NAME_LOWER . '/', '{assets_path}components/' . PKG_NAME_LOWER . '/');
    }

    private function addCoreFiles()
    {
        $vehicle = $this->builder->createVehicle([
            'source' => MODX_CORE_PATH . 'components/' . PKG_NAME_LOWER,
            'target' => "return MODX_CORE_PATH . 'components/';",
        ], [
            'vehicle_class' => 'xPDOFileVehicle',
        ]);
        $this->builder->putVehicle($vehicle);
    }

    private function addAssetsFiles()
    {
        $vehicle = $this->builder->createVehicle([
            'source' => MODX_ASSETS_PATH . 'components/' . PKG_NAME_LOWER,
            'target' => "return MODX_ASSETS_PATH . 'components/';",
        ], [
            'vehicle_class' => 'xPDOFileVehicle',
        ]);
        $this->builder->putVehicle($vehicle);
    }

    private function addMenus($menus)
    {
        foreach ($menus as $menuData) {
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
            $this->builder->putVehicle($vehicle);
        }
    }

    private function addEvents($events)
    {
        foreach ($events as $eventData) {
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
            $this->builder->putVehicle($vehicle);
        }
    }

    private function addSettings($settings)
    {
        foreach ($settings as $settingData) {
            $setting = $this->modx->newObject('modSystemSetting');
            $setting->fromArray(array_merge([
                'namespace' => PKG_NAME_LOWER,
            ], $settingData), '', true, true);
            $vehicle = $this->builder->createVehicle($setting, [
                xPDOTransport::PRESERVE_KEYS => true,
                xPDOTransport::UPDATE_OBJECT => false,
                xPDOTransport::UNIQUE_KEY => 'key',
            ]);
            $this->builder->putVehicle($vehicle);
        }
    }

    private function addPlugins($plugins)
    {
        if (!$this->category) {
            $this->setCategory();
        }
        foreach ($plugins as $pluginData) {
            $plugin = $this->modx->newObject('modPlugin');
            if ($pluginData['static_file']) {
                $pluginData['plugincode'] = $this->getFileContent($this->config['pluginsPath'] . $pluginData['static_file']);
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

            $this->category->addMany($plugin);
        }
    }

    private function addSnippets($snippets)
    {
        if (!$this->category) {
            $this->setCategory();
        }
        foreach ($snippets as $snippetData) {
            $snippet = $this->modx->newObject('modSnippet');
            if ($snippetData['static_file']) {
                $snippetData['snippet'] = $this->getFileContent($this->config['snippetsPath'] . $snippetData['static_file']);
            }
            $snippet->fromArray(array_merge([
                'id' => 0,
                'category' => 0,
            ], $snippetData), '', true, true);
            $this->category->addMany($snippet);
        }
    }

    private function addChunks($chunks)
    {
        if (!$this->category) {
            $this->setCategory();
        }
        foreach ($chunks as $chunkData) {
            $chunk = $this->modx->newObject('modChunk');
            if ($chunkData['static_file']) {
                $chunkData['snippet'] = file_get_contents($this->config['chunksPath'] . $chunkData['static_file']);
            }
            $chunk->fromArray(array_merge([
                'id' => 0,
                'category' => 0,
            ], $chunkData), '', true, true);
            $this->category->addMany($chunk);
        }
    }

    private function setCategory()
    {
        $this->category = $this->modx->newObject('modCategory');
        $this->category->set('id', 1);
        $this->category->set('category', PKG_NAME);
    }

    private function addCategory()
    {
        $vehicle = $this->builder->createVehicle($this->category, [
            xPDOTransport::UNIQUE_KEY => 'category',
            xPDOTransport::PRESERVE_KEYS => false,
            xPDOTransport::UPDATE_OBJECT => true,
            xPDOTransport::RELATED_OBJECTS => true,
            xPDOTransport::RELATED_OBJECT_ATTRIBUTES => [
                'Snippets' => [
                    xPDOTransport::PRESERVE_KEYS => false,
                    xPDOTransport::UPDATE_OBJECT => true,
                    xPDOTransport::UNIQUE_KEY => 'name',
                ],
                'Chunks' => [
                    xPDOTransport::PRESERVE_KEYS => false,
                    xPDOTransport::UPDATE_OBJECT => true,
                    xPDOTransport::UNIQUE_KEY => 'name',
                ],
                'Plugins' => [
                    xPDOTransport::PRESERVE_KEYS => false,
                    xPDOTransport::UPDATE_OBJECT => true,
                    xPDOTransport::UNIQUE_KEY => 'name',
                ],
                'PluginEvents' => [
                    xPDOTransport::PRESERVE_KEYS => true,
                    xPDOTransport::UPDATE_OBJECT => true,
                    xPDOTransport::UNIQUE_KEY => [
                        'pluginid',
                        'event',
                    ],
                ],
            ],
        ]);
        $this->builder->putVehicle($vehicle);
    }

    private function addAttributes()
    {
        if (file_exists($this->config['changelogPath'])) {
            $this->attributes['changelog'] = file_get_contents($this->config['changelogPath']);
        }
        if (file_exists($this->config['licensePath'])) {
            $this->attributes['license'] = file_get_contents($this->config['licensePath']);
        }
        if (file_exists($this->config['readmePath'])) {
            $this->attributes['readme'] = file_get_contents($this->config['readmePath']);
        }
        $this->builder->setPackageAttributes($this->attributes);
    }

    private function addResolvers()
    {
        foreach ($this->resolvers as $key => $resolver) {
            $vehicle = $this->builder->createVehicle([
                'source' => $resolver,
            ], [
                'vehicle_class' => 'xPDOScriptVehicle',
            ]);
            $this->builder->putVehicle($vehicle);
        }
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
