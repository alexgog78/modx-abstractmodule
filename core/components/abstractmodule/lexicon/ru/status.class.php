<?php

class amLexiconStatus
{
    /** @var array */
    public static $languageTopics = [
        //Successful states
        'success.create' => 'Запись успешно создана',
        'success.update' => 'Данные успешно обновлены',
        'success.delete' => 'Данные успешно удалены',
        //Error states
        'error.response' => 'Неверный формат ответа',
        'error.action' => 'Дейсвие не найдено: "[[+action]]"',
        'error.create' => 'Ошибка создания записи',
        'error.update' => 'Ошибка обновления данных. Проверьте заполненные поля',
        'error.delete' => 'Ошибка удаления данных',
        'error.record' => 'Запись "[[+record]]" не найдена',
        'error.data' => 'Проверьте введенные данные',
        //System errors
        //TODO abstractmodule_err_ae
        'err_nfs' => 'Произошла ошибка',
        'err_ae' => 'Такая запись уже есть',
        'err_save' => 'Произошла ошибка при сохранении',
        'err_remove' => 'Произошла ошибка при удалении',
        'err_nf' => 'Запись не найдена',
        'err_ns' => 'Запись не определена',
        'action_err_ns' => 'Не найден процессор',
    ];
}