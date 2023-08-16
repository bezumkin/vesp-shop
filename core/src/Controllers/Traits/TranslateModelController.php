<?php

namespace App\Controllers\Traits;

use Illuminate\Database\Eloquent\Model;

/**
 * @method getProperty(string $string)
 */
trait TranslateModelController
{
    protected function afterSave(Model $record): Model
    {
        if ($translations = $this->getProperty('translations')) {
            foreach ($translations as $data) {
                $record->translations()->updateOrCreate(['lang' => $data['lang']], $data);
            }
        }

        return $record;
    }
}
