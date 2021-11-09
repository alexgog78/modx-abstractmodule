<?php

trait abstractModuleLexiconHelper
{
    /**
     * @param string $key
     * @param array $options
     * @return string|null
     */
    public function lexicon(string $key, $options = [])
    {
        return $this->modx->lexicon($this::PKG_NAMESPACE . '_' . $key, $options);
    }

    /**
     * @param string $key
     */
    public function loadLexicon(string $key)
    {
        $this->modx->lexicon->load($this::PKG_NAMESPACE . ':' . $key);
    }
}
