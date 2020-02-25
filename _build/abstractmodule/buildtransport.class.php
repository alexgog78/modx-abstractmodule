<?php

require_once dirname(__FILE__) . '/config.inc.php';

class BuildTransport extends abstractCommand
{
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
    }

    public function run()
    {
        $this->log('Start');

        $this->builder->createPackage(PKG_NAME_LOWER, PKG_VERSION, PKG_RELEASE);
        //$this->builder->registerNamespace(PKG_NAME_LOWER, false, true, '{core_path}components/' . PKG_NAME_LOWER . '/');

        $attr = array(
            xPDOTransport::UNIQUE_KEY => 'name',
            xPDOTransport::PRESERVE_KEYS => false,
            xPDOTransport::UPDATE_OBJECT => true,
            xPDOTransport::RELATED_OBJECTS => false,
           /* xPDOTransport::RELATED_OBJECT_ATTRIBUTES => array (
                'Snippets' => array(
                    xPDOTransport::PRESERVE_KEYS => false,
                    xPDOTransport::UPDATE_OBJECT => true,
                    xPDOTransport::UNIQUE_KEY => 'name',
                ),
                'Chunks' => array (
                    xPDOTransport::PRESERVE_KEYS => false,
                    xPDOTransport::UPDATE_OBJECT => true,
                    xPDOTransport::UNIQUE_KEY => 'name',
                ),
            )*/
        );
        $category = $this->modx->newObject('modNamespace');
        $category->set('id', 1);
        $category->set('name', PKG_NAME);

        $vehicle = $this->builder->createVehicle($category, $attr);

        $root = dirname(dirname(dirname(__FILE__))).'/';
        $sources= array (
            //'root' => $root,
            //'build' => $root .'_build/',
            //'resolvers' => $root . '_build/resolvers/',
            //'data' => $root . '_build/data/',
            'source_core' => $root.'core/components/quip',
            //'lexicon' => $root . 'core/components/quip/lexicon/',
            'source_assets' => $root.'assets/components/quip',
            //'docs' => $root.'core/components/quip/docs/',
        );
        unset($root); /* save memory */

        // Now pack in resolvers
        $vehicle->resolve('file', array(
            'source' => $sources['source_assets'],
            'target' => "return MODX_ASSETS_PATH . 'components/';",
        ));
        $vehicle->resolve('file', array(
            'source' => $sources['source_core'],
            'target' => "return MODX_CORE_PATH . 'components/';",
        ));


        $this->builder->pack();


        $this->log('Finish');
    }
}
