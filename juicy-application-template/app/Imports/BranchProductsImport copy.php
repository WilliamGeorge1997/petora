<?php

namespace App\Imports;

use Exception;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Modules\Branch\Entities\Branch;
use Modules\Product\Entities\Product;

class BranchProductsImport implements ToCollection, WithHeadingRow
{
    protected $branch;
    protected $errors = [];
    protected $processedProducts = [];

    public function __construct(Branch $branch)
    {
        $this->branch = $branch;
    }

    public function collection(Collection $collection)
    {
        foreach ($collection as $rowIndex => $row) {
            $rowNumber = $rowIndex + 2;
            try {
                if ($this->isEmptyRow($row)) {
                    continue;
                }
                $this->processRow($row, $rowNumber);
            } catch (Exception $e) {
                $this->errors[] = "Row {$rowNumber}: " . $e->getMessage();
            }
        }
        if (!empty($this->errors)) {
            throw new Exception(implode("\n", $this->errors));
        }
    }

    private function isEmptyRow($row)
    {

        return empty($row['product_id']) &&
            empty($row['product_name']) &&
            empty($row['price']);
    }

    private function processRow($row, $rowNumber)
    {
        $productId = $row['product_id'];
        $product = Product::find($productId);

        if (!$product) {
            throw new Exception("Product with ID {$productId} not found");
        }

        if (empty($row['price'])) {
            return;
        }

        $this->importProduct($product, $row);

        if (!empty($row['attribute_name']) && !empty($row['attribute_value']) && !empty($row['attribute_value_id'])) {
            if (empty($row['attribute_price'])) {
                return;
            }
            $this->importProductAttribute($product, $row, $rowNumber);
        }
    }

    private function importProduct($product, $row)
    {
        $price = $row['price'];

        $isActive = (bool)$row['product_active'];

        $existingPivot = DB::table('branch_products')
            ->where('branch_id', $this->branch->id)
            ->where('product_id', $product->id)
            ->first();

        if ($existingPivot) {

            DB::table('branch_products')
                ->where('branch_id', $this->branch->id)
                ->where('product_id', $product->id)
                ->update([
                    'price' => $price,
                    'is_active' => $isActive,
                    'updated_at' => now()
                ]);
        } else {

            DB::table('branch_products')->insert([
                'branch_id' => $this->branch->id,
                'product_id' => $product->id,
                'price' => $price,
                'is_active' => $isActive,
                'created_at' => now(),
                'updated_at' => now()
            ]);
        }
    }

    private function importProductAttribute($product, $row, $rowNumber)
    {
        $attributeValueId = $row['attribute_value_id'];

        if (!empty($attributeValueId)) {
            $attributeValue = DB::table('product_attribute_values')
                ->where('id', $attributeValueId)
                ->first();
        }

        if (!$attributeValueId) {
            throw new Exception("Could not determine attribute value ID");
        }

        $price = $row['attribute_price'];

        $existingAttributePivot = DB::table('branch_product_attribute_values')
            ->where('branch_id', $this->branch->id)
            ->where('product_attribute_value_id', $attributeValueId)
            ->first();

        if ($existingAttributePivot) {

            DB::table('branch_product_attribute_values')
                ->where('branch_id', $this->branch->id)
                ->where('product_attribute_value_id', $attributeValueId)
                ->update([
                    'price' => $price,
                    'updated_at' => now()
                ]);
        } else {

            DB::table('branch_product_attribute_values')->insert([
                'branch_id' => $this->branch->id,
                'product_attribute_value_id' => $attributeValueId,
                'price' => $price,
                'created_at' => now(),
                'updated_at' => now()
            ]);
        }
    }
}
