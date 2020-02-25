<?php

abstract class abstractBuildModel extends abstractCommand
{
    /** @var xPDOManager|null */
    protected $manager;

    /** @var xPDOGenerator */
    protected $generator;

    /** @var string */
    protected $schemaPath = '';

    /** @var string */
    protected $modelPath = '';

    public function __construct(modX &$modx, $config = [])
    {
        parent::__construct($modx, $config);
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
