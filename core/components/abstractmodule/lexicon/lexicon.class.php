<?php

//TODO refactor /ru folder
class amLexicon
{
    /** @var string */
    private $namespace = '';

    /** @var string */
    private $cultureKey = '';

    /** @var array */
    private $languageTopics = [];

    /**
     * amLexicon constructor.
     * @param $namespace
     * @param $cultureKey
     */
    function __construct($namespace, $cultureKey)
    {
        $this->namespace = $namespace;
        $this->cultureKey = $cultureKey;
    }

    /**
     * @return array
     */
    public function loadLanguageTopics()
    {
        $this->includeFiles();
        $this->prepareLang();
        return $this->languageTopics;
    }

    /**
     * return void
     */
    private function includeFiles()
    {
        /** @noinspection PhpIncludeInspection */
        require_once dirname(__FILE__) . '/' . $this->cultureKey . '/manager.class.php';
        $this->languageTopics = array_merge($this->languageTopics, amLexiconManager::$languageTopics);

        /** @noinspection PhpIncludeInspection */
        require_once dirname(__FILE__) . '/' . $this->cultureKey . '/field.class.php';
        $this->languageTopics = array_merge($this->languageTopics, amLexiconField::$languageTopics);

        /** @noinspection PhpIncludeInspection */
        require_once dirname(__FILE__) . '/' . $this->cultureKey . '/status.class.php';
        $this->languageTopics = array_merge($this->languageTopics, amLexiconStatus::$languageTopics);
    }

    /**
     * return void
     */
    private function prepareLang()
    {
        foreach ($this->languageTopics as $key => $value) {
            $this->languageTopics[$this->namespace . '.' . $key] = $value;
            unset($this->languageTopics[$key]);
        }
    }
}
