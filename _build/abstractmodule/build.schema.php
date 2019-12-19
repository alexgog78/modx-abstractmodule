<?php

abstract class amBuildSchema
{
    /** @var modX */
    protected $modx;

    /** @var array */
    protected $source = [
        'model' => '',
        'schema_file' => '',
    ];

    /** @var xPDOManager|null */
    private $manager;

    /** @var xPDOGenerator */
    private $generator;

    /**
     * amBuildSchema constructor.
     * @param modX $modx
     */
    public function __construct(modX &$modx)
    {
        $this->modx = &$modx;
        $this->modx->loadClass('transport.modPackageBuilder', '', false, true);
        $this->manager = $this->modx->getManager();
        $this->generator = $this->manager->getGenerator();
    }

    /**
     * return void
     */
    public function process()
    {
        $this->getSources();
        $this->validate();
        $this->generate();
    }

    /**
     * return void
     */
    private function getSources()
    {
        $this->source = [
            'model' => MODX_CORE_PATH . 'components/' . PKG_NAME_LOWER . '/model/',
            'schema_file' => MODX_CORE_PATH . 'components/' . PKG_NAME_LOWER . '/model/schema/' . PKG_NAME_LOWER . '.mysql.schema.xml',
        ];
    }

    /**
     * return void
     */
    private function validate()
    {
        if (!is_dir($this->source['model'])) {
            $this->modx->log(modX::LOG_LEVEL_ERROR, 'Model directory not found!');
            die();
        }
        if (!file_exists($this->source['schema_file'])) {
            $this->modx->log(modX::LOG_LEVEL_ERROR, 'Schema file not found!');
            die();
        }
    }

    /**
     * return void
     */
    private function generate()
    {
        $this->generator->parseSchema($this->source['schema_file'], $this->source['model']);
        $this->modx->log(modX::LOG_LEVEL_INFO, 'Model Built');
    }
}
