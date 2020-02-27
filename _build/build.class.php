<?php

class Build
{
    /** @var modX */
    private $modx;

    /** @var object */
    private $command;

    /**
     * Run constructor.
     * @param string $action
     * @param array $config
     */
    public function __construct(string $action, $config = [])
    {
        $this->modx = new modX();
        $this->modx->initialize('mgr');
        $this->modx->setLogLevel(modX::LOG_LEVEL_INFO);
        $this->modx->setLogTarget('ECHO');
        $this->loadAbstractCommands();
        $this->loadCommand($action, $config);

    }

    public function run()
    {
        $this->command->run();
    }

    private function loadAbstractCommands()
    {

        $this->modx->loadClass('commands.abstractcommand', dirname(__FILE__) . '/', true, true);
        $this->modx->loadClass('commands.abstractbuildmodel', dirname(__FILE__) . '/', true, true);
        $this->modx->loadClass('commands.abstractbuildtransport', dirname(__FILE__) . '/', true, true);
    }

    /**
     * @param string $action
     * @param array $config
     */
    private function loadCommand(string $action, $config = [])
    {
        $command = $this->modx->loadClass($action, dirname(__FILE__) . '/', true, true);
        if (!$command) {
            die();
        }
        $this->command = new $command($this->modx, $config);
    }
}
