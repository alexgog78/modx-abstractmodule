<?php

trait abstractModuleControllerHelperRichText
{
    /** @var bool */
    protected $richText = false;

    /**
     * @return bool
     */
    protected function hasRichText()
    {
        return $this->richText;
    }

    protected function loadRichText()
    {
        $useEditor = $this->modx->getOption('use_editor');
        $whichEditor = $this->modx->getOption('which_editor');
        if (!$useEditor || empty($whichEditor)) {
            return;
        }
        $onRichTextEditorInit = $this->modx->invokeEvent('OnRichTextEditorInit', [
            'editor' => $whichEditor,
            'elements' => [
                'ta',
                'richtext',
            ],
        ]);
        if (is_array($onRichTextEditorInit)) {
            $onRichTextEditorInit = implode('', $onRichTextEditorInit);
        }
        $this->setPlaceholder('onRichTextEditorInit', $onRichTextEditorInit);
        $this->addHtml($onRichTextEditorInit);
    }
}
