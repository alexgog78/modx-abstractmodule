<?php

trait abstractModuleControllerHelperRichText
{
    private function loadRichTextEditor()
    {
        $useEditor = $this->modx->getOption('use_editor');
        $whichEditor = $this->modx->getOption('which_editor');
        if (!$useEditor || empty($whichEditor)) {
            return;
        }
        $onRichTextEditorInit = $this->modx->invokeEvent('OnRichTextEditorInit', [
            'editor' => $whichEditor,
            'elements' => ['ta', 'richtext'],
        ]);
        if (is_array($onRichTextEditorInit)) {
            $onRichTextEditorInit = implode('', $onRichTextEditorInit);
        }
        $this->setPlaceholder('onRichTextEditorInit', $onRichTextEditorInit);
        $this->addHtml($onRichTextEditorInit);
    }
}
