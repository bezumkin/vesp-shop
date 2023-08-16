<?php

use App\Models\Language;
use Phinx\Seed\AbstractSeed;

class Languages extends AbstractSeed
{
    protected array $data = [
        'ru' => [
            'title' => 'Русский',
            'rank' => 0,
        ],
        'en' => [
            'title' => 'English',
            'rank' => 1,
        ],
    ];


    public function run(): void
    {
        foreach ($this->data as $id => $data) {
            /** @var Language $record */
            if (!$record = Language::query()->find($id)) {
                $record = new Language();
                $record->lang = $id;
            }
            $record->fill($data);
            $record->save();
        }
    }
}
