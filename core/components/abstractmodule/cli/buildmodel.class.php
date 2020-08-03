<?php

require_once dirname(__FILE__) . '/cli.class.php';

abstract class AbstractBuildModelCli extends AbstractCLI
{
    /** @var string */
    protected $schemaPath = MODX_CORE_PATH . 'components/' . PKG_NAME_LOWER . '/model/schema/' . PKG_NAME_LOWER . '.mysql.schema.xml';

    /** @var string */
    protected $modelPath = MODX_CORE_PATH . 'components/' . PKG_NAME_LOWER . '/model/';

    /** @var xPDOManager */
    private $manager;

    /** @var xPDOGenerator */
    private $generator;

    /**
     * AbstractBuildModel constructor.
     */
    public function __construct()
    {
        parent::__construct();
        $this->manager = $this->modx->getManager();
        $this->generator = $this->manager->getGenerator();
    }

    public function run()
    {
        $status = $this->generator->parseSchema($this->schemaPath, $this->modelPath);
        if (!$status) {
            $this->error(PKG_NAME . ' error generating model');
        }
        $this->info(PKG_NAME . ' model generated');
    }
}
