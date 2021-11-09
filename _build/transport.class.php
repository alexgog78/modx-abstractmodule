<?php

class buildTransport
{
    /** @var modX */
    protected $modx;

    /** @var modPackageBuilder */
    protected $builder;

    /** @var string */
    private $packageName;

    /**
     * @param modX $modx
     */
    public function __construct(modX $modx)
    {
        $this->modx = $modx;
        $modx->loadClass('transport.modPackageBuilder', '', false, true);
        $this->builder = new modPackageBuilder($modx);
    }

    /**
     * @param string $packageName
     * @param string $packageVersion
     * @param string $packageRelease
     * @return $this
     */
    public function createPackage(string $packageName, string $packageVersion = '1.0.0', string $packageRelease = 'beta')
    {
        $this->packageName = $packageName;
        $this->builder->createPackage($packageName, $packageVersion, $packageRelease);
        $this->log('Creating  ' . $packageName . ' package');
        return $this;
    }

    /**
     * @param string $packageName
     * @return $this
     */
    public function registerNamespace()
    {
        $this->builder->registerNamespace($this->packageName, false, true, '{core_path}components/' . $this->packageName . '/', '{assets_path}components/' . $this->packageName . '/');
        return $this;
    }

    /**
     * @param array $attributes
     * @return $this
     */
    public function addAttributes(array $attributes = [])
    {
        $this->builder->setPackageAttributes($attributes);
        $this->log('Attributes added');
        return $this;
    }

    /**
     * @param string $path
     * @return $this
     */
    public function addCoreFiles(string $path)
    {
        $source = MODX_CORE_PATH . $path;
        $target = explode('/', $path);
        array_pop($target);
        $target = "return MODX_CORE_PATH . '" . implode('/', $target) . "/';";
        $vehicle = $this->builder->createVehicle([
            'source' => $source,
            'target' => $target,
        ], [
            'vehicle_class' => 'xPDOFileVehicle',
        ]);
        $this->builder->putVehicle($vehicle);
        $this->log('Core files added: ' . $source);
        return $this;
    }

    /**
     * @param string $path
     * @return $this
     */
    public function addAssetsFiles(string $path)
    {
        $source = MODX_ASSETS_PATH . $path;
        $target = explode('/', $path);
        array_pop($target);
        $target = "return MODX_ASSETS_PATH . '" . implode('/', $target) . "/';";
        $vehicle = $this->builder->createVehicle([
            'source' => $source,
            'target' => $target,
        ], [
            'vehicle_class' => 'xPDOFileVehicle',
        ]);
        $this->builder->putVehicle($vehicle);
        $this->log('Assets files added: ' . $source);
        return $this;
    }

    /**
     * @param array $settings
     * @return $this
     */
    public function addSettings(array $settings = [])
    {
        foreach ($settings as $data) {
            /** @var modSystemSetting $item */
            $item = $this->modx->newObject('modSystemSetting');
            $item->fromArray(array_merge([
                'namespace' => $this->packageName,
            ], $data), '', true, true);

            $vehicle = $this->builder->createVehicle($item, [
                xPDOTransport::PRESERVE_KEYS => true,
                xPDOTransport::UPDATE_OBJECT => false,
                xPDOTransport::UNIQUE_KEY => 'key',
            ]);
            $this->builder->putVehicle($vehicle);
            $this->log('Added modSystemSetting: ' . $item->get('key'));
        }
        return $this;
    }

    /**
     * @param array $chunks
     * @return $this
     */
    public function addChunks(array $chunks = [])
    {
        foreach ($chunks as $data) {
            /** @var modChunk $chunk */
            $chunk = $this->modx->newObject('modChunk');
            if ($data['static_file']) {
                $data['snippet'] = file_get_contents($data['static_file']);
            }
            $chunk->fromArray(array_merge($data, [
                'id' => 0,
                'category' => 0,
                'static_file' => '',
            ]), '', true, true);

            $vehicle = $this->builder->createVehicle($chunk, [
                xPDOTransport::PRESERVE_KEYS => false,
                xPDOTransport::UPDATE_OBJECT => true,
                xPDOTransport::UNIQUE_KEY => 'name',
            ]);
            $this->builder->putVehicle($vehicle);
            $this->log('Added modChunk: ' . $chunk->get('name'));
        }
        return $this;
    }

    /**
     * @param array $snippets
     * @return $this
     */
    public function addSnippets(array $snippets = [])
    {
        foreach ($snippets as $data) {
            /** @var modSnippet $snippet */
            $snippet = $this->modx->newObject('modSnippet');
            if ($data['static_file']) {
                $fileContent = trim(file_get_contents($data['static_file']));
                preg_match('#\<\?php(.*)#is', $fileContent, $code);
                $data['snippet'] = rtrim(rtrim(trim($code[1]), '?>'));
            }
            $snippet->fromArray(array_merge($data, [
                'id' => 0,
                'category' => 0,
                'static_file' => '',
            ]), '', true, true);

            $vehicle = $this->builder->createVehicle($snippet, [
                xPDOTransport::PRESERVE_KEYS => false,
                xPDOTransport::UPDATE_OBJECT => true,
                xPDOTransport::UNIQUE_KEY => 'name',
            ]);
            $this->builder->putVehicle($vehicle);
            $this->log('Added modSnippet: ' . $snippet->get('name'));
        }
        return $this;
    }

    /**
     * @param array $plugins
     * @return $this
     */
    public function addPlugins(array $plugins = [])
    {
        foreach ($plugins as $data) {
            /** @var modPlugin $plugin */
            $plugin = $this->modx->newObject('modPlugin');
            if ($data['static_file']) {
                $fileContent = trim(file_get_contents($data['static_file']));
                preg_match('#\<\?php(.*)#is', $fileContent, $code);
                $data['plugincode'] = rtrim(rtrim(trim($code[1]), '?>'));
            }
            $plugin->fromArray(array_merge($data, [
                'id' => 0,
                'category' => 0,
                'static_file' => '',
            ]), '', true, true);

            $events = [];
            if (!empty($data['events'])) {
                foreach ($data['events'] as $pluginEvent) {
                    /** @var modPluginEvent $event */
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
                xPDOTransport::PRESERVE_KEYS => false,
                xPDOTransport::UPDATE_OBJECT => true,
                xPDOTransport::UNIQUE_KEY => 'name',
                xPDOTransport::RELATED_OBJECTS => true,
                xPDOTransport::RELATED_OBJECT_ATTRIBUTES => [
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
            $this->log('Added modPlugin: ' . $plugin->get('name'));
        }
        return $this;
    }

    /**
     * @param array $events
     * @return $this
     */
    public function addEvents(array $events = [])
    {
        foreach ($events as $data) {
            /** @var modEvent $event */
            $event = $this->modx->newObject('modEvent');
            $event->fromArray(array_merge($data, [
                'service' => 6,
                'groupname' => $this->packageName,
            ]), '', true, true);

            $vehicle = $this->builder->createVehicle($event, [
                xPDOTransport::PRESERVE_KEYS => true,
                xPDOTransport::UPDATE_OBJECT => true,
                xPDOTransport::UNIQUE_KEY => 'name',
            ]);
            $this->builder->putVehicle($vehicle);
            $this->log('Added modEvent: ' . $event->get('name'));
        }
        return $this;
    }

    /**
     * @param array $menus
     * @return $this
     */
    public function addMenus(array $menus = [])
    {
        foreach ($menus as $data) {
            /** @var modMenu $menu */
            $menu = $this->modx->newObject('modMenu');
            $menu->fromArray(array_merge([
                'parent' => 'components',
                'namespace' => $this->packageName,
            ], $data), '', true, true);

            $vehicle = $this->builder->createVehicle($menu, [
                xPDOTransport::PRESERVE_KEYS => true,
                xPDOTransport::UPDATE_OBJECT => true,
                xPDOTransport::UNIQUE_KEY => 'text',
            ]);
            $this->builder->putVehicle($vehicle);
            $this->log('Added modMenu: ' . $menu->get('text'));
        }
        return $this;
    }

    /**
     * @param array $sources
     * @return $this
     */
    public function addMediaSources(array $sources = [])
    {
        foreach ($sources as $data) {
            /** @var modMediaSource $source */
            $source = $this->modx->newObject('sources.modMediaSource');
            $source->fromArray(array_merge($data, [
                'class_key' => 'sources.modFileMediaSource',
            ]), '', true, true);

            array_walk($data['properties'], function (& $propertyData) {
                $propertyData = array_merge([
                    'type' => 'textfield',
                    'lexicon' => 'core:source',
                    'desc' => 'prop_file.' . $propertyData['name'] . '_desc',
                ], $propertyData);
            });
            $source->setProperties($data['properties']);

            $vehicle = $this->builder->createVehicle($source, [
                xPDOTransport::PRESERVE_KEYS => false,
                xPDOTransport::UPDATE_OBJECT => true,
                xPDOTransport::UNIQUE_KEY => 'name',
            ]);
            $this->builder->putVehicle($vehicle);
            $this->log('Added modMediaSource: ' . $source->get('name'));
        }
        return $this;
    }

    /**
     * @param string $name
     * @return $this
     */
    public function addCategory(string $name)
    {
        /** @var modCategory */
        $item = $this->modx->newObject('modCategory');
        $item->set('id', 1);
        $item->set('category', $name);
        $vehicle = $this->builder->createVehicle($item, [
            xPDOTransport::UNIQUE_KEY => 'category',
            xPDOTransport::PRESERVE_KEYS => false,
            xPDOTransport::UPDATE_OBJECT => true,
        ]);
        $this->builder->putVehicle($vehicle);
        $this->log('Added modCategory: ' . $item->get('category'));
        return $this;
    }

    /**
     * @param string $path
     * @return $this
     */
    public function addResolver(string $path)
    {
        $vehicle = $this->builder->createVehicle([
            'source' => $path,
        ], [
            'vehicle_class' => 'xPDOScriptVehicle',
        ]);
        $this->builder->putVehicle($vehicle);
        $this->log('Added resolver: ' . $path);
        return $this;
    }

    public function pack()
    {
        $this->builder->pack();
        $this->log('Package transport zip created');
    }

    /**
     * @param $data
     */
    protected function log($data)
    {
        if (is_array($data)) {
            $data = print_r($data, true);
        }
        $this->modx->log(modX::LOG_LEVEL_INFO, $data);
    }
}
