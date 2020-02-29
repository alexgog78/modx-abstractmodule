<?php

if (!class_exists('abstractCLI')) {
    require_once dirname(__FILE__) . '/abstractcli.class.php';
}

abstract class abstractBuildModel extends abstractCLI
{
    /** @var xPDOManager|null */
    protected $manager;

    /** @var xPDOGenerator */
    protected $generator;

    /** @var string */
    protected $schemaPath = '';

    /** @var string */
    protected $modelPath = '';

    /**
     * abstractBuildModel constructor.
     * @param array $config
     */
    public function __construct($config = [])
    {
        parent::__construct($config);
        $this->manager = $this->modx->getManager();
        $this->generator = $this->manager->getGenerator();
    }

    public function run()
    {
        $status = $this->generator->parseSchema($this->schemaPath, $this->modelPath);
        if (!$status) {
            $this->log('Error: model generation', modX::LOG_LEVEL_ERROR);
            exit();
        }
        $this->log('Success: model generated');
    }
}