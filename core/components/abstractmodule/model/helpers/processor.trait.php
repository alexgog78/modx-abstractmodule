<?php

trait abstractModuleProcessorHelper
{
    /**
     * @param string $action
     * @param array $data
     * @return array|string|null
     */
    public function runProcessor(string $action, array $data = [])
    {
        if ($this->modx->error) {
            $this->modx->error->reset();
        }
        $chars = [
            "'",
            '"',
            '(',
            ')',
            ';',
            '>',
            '<',
        ];
        $action = $this->modx->sanitizeString($action, $chars);
        $data = $this->modx::sanitize($data, $this->modx->sanitizePatterns);
        array_walk_recursive($data, [
            $this->modx,
            'stripTags',
        ]);
        try {
            /** @var modProcessorResponse $response */
            $response = $this->modx->runProcessor($action, $data, [
                'processors_path' => $this->processorsPath,
            ]);
        } catch (Exception $exception) {
            return $exception->getMessage();
        }
        if (!$response) {
            return false;
        }
        return $response->getResponse();
    }
}
