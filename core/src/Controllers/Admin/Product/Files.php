<?php

namespace App\Controllers\Admin\Product;

use App\Controllers\Traits\ProductPropertyController;
use App\Models\File;
use App\Models\ProductFile;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Psr\Http\Message\ResponseInterface;
use Vesp\Controllers\ModelController;

class Files extends ModelController
{
    use ProductPropertyController;

    protected $scope = 'products';
    protected $model = ProductFile::class;
    protected $primaryKey = ['product_id', 'file_id'];

    protected function beforeGet(Builder $c): Builder
    {
        $c->where('product_id', $this->product->id);
        $c->with('file:id,updated_at');

        return $c;
    }

    protected function beforeCount(Builder $c): Builder
    {
        return $this->beforeGet($c);
    }

    protected function afterCount(Builder $c): Builder
    {
        $c->with('file:id,updated_at');
        $c->orderBy('rank');

        return $c;
    }

    public function put(): ResponseInterface
    {
        if (!$data = $this->getProperty('file')) {
            return $this->failure('errors.upload.no_file');
        }
        $file = new File();
        $file->uploadFile($data, $this->getProperty('metadata'));

        $this->product->productFiles()->create(
            [
                'file_id' => $file->id,
                'rank' => $this->product->productFiles()->max('rank') + 1,
            ],
        );
        $this->setProperty('file_id', $file->id);

        return $this->get();
    }

    public function post(): ResponseInterface
    {
        foreach ($this->getProperty('files') as $file_id => $rank) {
            $this->product->productFiles()->where('file_id', $file_id)->update(['rank' => $rank]);
        }

        return $this->success();
    }

    protected function beforeDelete(Model $record): ?ResponseInterface
    {
        /** @var ProductFile $record */
        $record->file->delete();

        return null;
    }
}