<?php

require_once __DIR__ . '/default.class.php';

abstract class abstractModuleMgrUpdateController extends abstractModuleMgrDefaultController
{
    /** @var string */
    protected $objectPrimaryKey = 'id';

    /** @var string */
    protected $objectGetProcessorPath;

    /** @var array */
    protected $object = [];

    /**
     * @param array $scriptProperties
     */
    public function process(array $scriptProperties = [])
    {
        $this->object = $this->getRecord($scriptProperties);
        parent::process($scriptProperties);
    }

    /**
     * @param array $scriptProperties
     * @return mixed
     */
    protected function getRecord($scriptProperties = [])
    {
        $objectPrimaryKey = $this->objectPrimaryKey;
        $objectPrimaryValue = $scriptProperties[$objectPrimaryKey];
        $processorsPath = $this->objectGetProcessorPath;

        $response = $this->modx->runProcessor($processorsPath, [
            $objectPrimaryKey => $objectPrimaryValue,
        ], [
            'processors_path' => $this->service->processorsPath ?? '',
        ]);
        if (!$response) {
            $this->failure('Processor ' . $processorsPath . ' does not exist;');
            return false;
        }
        if ($response->isError()) {
            $this->failure($response->getMessage());
        }
        return $response->getObject();
    }
}
