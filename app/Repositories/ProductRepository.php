<?php

namespace App\Repositories;

use App\Models\Product;
use App\Models\ProductVariant;
use App\Repositories\BaseRepository;
use Illuminate\Pagination\LengthAwarePaginator;
use DB;
use Exception;
use Symfony\Component\HttpKernel\Exception\UnprocessableEntityHttpException;
use Throwable;

/**
 * Class ProductRepository
 * @version January 14, 2026, 09:02 am UTC+1
 */
class ProductRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [

    ];

    /**
     * Return searchable fields
     *
     * @return array
     */
    public function getFieldsSearchable()
    {
        return $this->fieldSearchable;
    }

    /**
     * Configure the Model
     **/
    public function model()
    {
        return Product::class;
    }

    public function paginateByStore(int $storeId, int $perPage = 10): LengthAwarePaginator
    {
        return $this->model
            ->with('variants')
            ->where('store_id', $storeId)
            ->latest()
            ->paginate($perPage);
    }

    public function findWithVariants($id): Product
    {
        return $this->model
            ->with('variants')
            ->findOrFail($id);
    }

    /**
     * Create a new product with variants
     *
     * @param array $data
     * @param array $variants
     * 
     * @throws Throwable
     * 
     * @return Product
     */
    public function store(array $data, array $variants): Product
    {
        try{
            DB::beginTransaction();
            $product = $this->create($data);

            foreach ($variants as $variant) {
                $product->variants()->create($variant);
            }
            DB::commit();
            return $product;
        } catch (Exception $e) {
            DB::rollBack();

            throw new UnprocessableEntityHttpException($e->getMessage());
        }
    }

    /**
     * Update a product with variants
     *
     * @param array $data
     * @param Product $product
     * 
     * @throws Throwable
     * 
     * @return Product
     */
    public function update($data, $product): Product
    {
        try{
            DB::beginTransaction();
            $product->update($data);
            foreach ($data['variants'] as $variant) {
                ProductVariant::updateOrCreate(
                    ['id' => $variant['id'] ?? null],
                    array_merge($variant, ['product_id' => $product->id])
                );
            }
            DB::commit();
            return $product;
        } catch (Exception $e) {
            DB::rollBack();

            throw new UnprocessableEntityHttpException($e->getMessage());
        }
    }
}
