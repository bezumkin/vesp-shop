<?php

namespace App\Services;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Database\Eloquent\Builder;
use KSamuel\FacetedSearch\Filter\RangeFilter;
use KSamuel\FacetedSearch\Filter\ValueFilter;
use KSamuel\FacetedSearch\Index\Factory;
use KSamuel\FacetedSearch\Index\IndexInterface;
use KSamuel\FacetedSearch\Query\AggregationQuery;
use KSamuel\FacetedSearch\Query\SearchQuery;

class CategoryFilter
{

    protected ?Category $category;
    protected array $rangeFilters = ['price', 'weight'];
    protected array $valueFilters = ['category', 'made_in'];
    protected array $booleanFilters = ['new', 'popular', 'favorite'];
    protected const CACHE_TIME = 600; // 10 min

    public function __construct(?Category $category = null)
    {
        $this->category = $category;
    }

    protected function getIndex(): IndexInterface
    {
        $factory = (new Factory())->create(Factory::ARRAY_STORAGE);
        $storage = $factory->getStorage();

        if ($cache = $this->getCache()) {
            $storage->setData($cache);
        } else {
            $products = Product::query()->where('active', true);
            if ($this->category) {
                $products->where(function (Builder $c) {
                    $c->where('category_id', $this->category->id);
                    if ($children = Category::getChildIds($this->category->id, true)) {
                        $c->orWhereIn('category_id', $children);
                    }
                    $c->orWhereHas('productCategories', function (Builder $c) {
                        $c->where('category_id', $this->category->id);
                    });
                });
            } else {
                $products->whereHas('category', static function (Builder $c) {
                    $c->where('active', true);
                });
            }

            /** @var Product $product */
            foreach ($products->cursor() as $product) {
                $record = [
                    'new' => $product->new,
                    'popular' => $product->popular,
                    'favorite' => $product->favorite,
                ];
                if ($product->price) {
                    $record['price'] = $product->price;
                }
                if (!$this->category || $this->category->id !== $product->category_id) {
                    $record['category'] = $product->category_id;
                }
                if ($product->weight) {
                    $record['weight'] = $product->weight;
                }
                if ($product->made_in) {
                    $record['made_in'] = $product->made_in;
                }
                $storage->addRecord($product->id, $record);
            }
            $storage->optimize();
            $this->setCache($storage->export());
        }

        return $factory;
    }

    protected function getCacheName(): string
    {
        $dir = rtrim(getenv('CACHE_DIR'), '/') . '/filters/';
        if (!is_dir($dir)) {
            /** @noinspection MkdirRaceConditionInspection */
            mkdir($dir);
        }

        return $dir . ($this->category ? 'category-' . $this->category->id : 'categories') . '.json';
    }

    protected function getCache(): ?array
    {
        $file = $this->getCacheName();
        if (self::CACHE_TIME && file_exists($file) && filemtime($file) + self::CACHE_TIME > time()) {
            return json_decode(file_get_contents($file), true);
        }

        return null;
    }

    protected function setCache(array $data): void
    {
        file_put_contents($this->getCacheName(), json_encode($data));
    }

    public function getFilters(array $selected = []): array
    {
        $filters = $this->makeFiltersFromSelected($selected);

        $index = $this->getIndex();
        $query = (new AggregationQuery())->filters($filters)->countItems()->sort();
        $data = $index->aggregate($query);

        return $this->makeFiltersList($data);
    }

    public function getProducts(array $selected = []): array
    {
        $filters = $this->makeFiltersFromSelected($selected);

        $index = $this->getIndex();
        $query = (new SearchQuery())->filters($filters);

        return $index->query($query);
    }

    protected function makeFiltersFromSelected(array $selected = []): array
    {
        $filters = [];
        foreach ($selected as $key => $values) {
            if (in_array($key, $this->rangeFilters, true)) {
                if (count($values) === 2) {
                    $filters[] = new RangeFilter($key, ['min' => $values[0], 'max' => $values[1]]);
                }
            } elseif (in_array($key, $this->valueFilters, true) || in_array($key, $this->booleanFilters, true)) {
                $filters[] = new ValueFilter($key, $values);
            }
        }

        return $filters;
    }

    protected function makeFiltersList(array $input): array
    {
        $output = [];

        // Prepare filters data for frontend
        foreach ($input as $key => $values) {
            $isBoolean = in_array($key, $this->booleanFilters, true);
            $isRange = in_array($key, $this->rangeFilters, true);
            if ($key === 'category') {
                $tmp = [];
                $c = Category::query()
                    ->select('id', 'uri')
                    ->whereIn('id', array_keys($values))
                    ->with('translations:category_id,lang,title');
                /** @var Category $category */
                foreach ($c->get() as $category) {
                    $tmp[] = [
                        'value' => $category->id,
                        'amount' => $values[$category->id],
                        'extra' => $category->relationsToArray(),
                    ];
                }
                $output[] = [
                    'filter' => $key,
                    'type' => 'options',
                    'values' => $tmp,
                ];
            } elseif ($isRange) {
                $tmp = array_keys($values);
                $output[] = [
                    'filter' => $key,
                    'type' => 'range',
                    'values' => [
                        'min' => (float)min($tmp),
                        'max' => (float)max($tmp),
                    ],
                ];
            } else {
                $tmp = [];
                foreach ($values as $value => $items) {
                    $tmp[] = [
                        'value' => $isBoolean ? (bool)$value : $value,
                        'amount' => $items,
                    ];
                }
                $output[] = [
                    'filter' => $key,
                    'type' => $isBoolean ? 'boolean' : 'options',
                    'values' => $tmp,
                ];
            }
        }

        return $output;
    }
}