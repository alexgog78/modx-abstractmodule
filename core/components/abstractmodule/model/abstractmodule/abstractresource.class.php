<?php

abstract class abstractResource extends modResource
{
    const PKG_NAMESPACE = 'abstractmodule';
    const RESOURCE_NAMESPACE = 'resource';

    /** @var bool */
    public $showInContextMenu = true;

    /** @var bool */
    public $allowChildrenResources = true;

    /** @var abstractModule */
    protected $service;

    /**
     * abstractResource constructor.
     *
     * @param xPDO $xpdo
     */
    public function __construct(xPDO &$xpdo)
    {
        parent::__construct($xpdo);
        $this->service = $xpdo->getService($this::PKG_NAMESPACE, $this::PKG_NAMESPACE, MODX_CORE_PATH . 'components/' . $this::PKG_NAMESPACE . '/model/');
        $this->set('class_key', get_class($this));
    }

    /**
     * @param xPDO $modx
     * @return string
     */
    public static function getControllerPath(xPDO &$modx)
    {
        return MODX_CORE_PATH . 'components/' . static::PKG_NAMESPACE . '/controllers/mgr/' . static::RESOURCE_NAMESPACE . '/';
    }

    /**
     * @return array
     */
    public function getContextMenuText()
    {
        $this->service->loadLexicon($this::RESOURCE_NAMESPACE);
        return [
            'text_create' => $this->service->lexicon($this::RESOURCE_NAMESPACE),
            'text_create_here' => $this->service->lexicon($this::RESOURCE_NAMESPACE . '_create_here'),
        ];
    }

    /**
     * @return null|string
     */
    public function getResourceTypeName()
    {
        $this->service->loadLexicon($this::RESOURCE_NAMESPACE);
        return $this->service->lexicon($this::RESOURCE_NAMESPACE);
    }
}
