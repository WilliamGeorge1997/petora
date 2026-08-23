<?php

namespace App\Imports;

use Exception;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Modules\Branch\Entities\Branch;
use Modules\Branch\Entities\BranchProduct;
use Modules\Branch\Entities\BranchProductAttributeValue;
use Modules\Product\Entities\Product;
use Modules\Product\Entities\ProductAttributeValue;

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

    private function isEmptyRow($row): bool
    {
        return !collect(['product_id', 'product_name', 'price'])
            ->some(fn($key) => !empty($row[$key]));
    }

    private function processRow($row, $rowNumber)
    {
        $product = Product::find($row['product_id']);
        if (!$product) {
            throw new Exception("Product with ID {$row['product_id']} not found");
        }
        if (empty($row['price'])) {
            return;
        }
        if (!in_array($product->id, $this->processedProducts)) {
            $this->importProduct($product, $row);
            $this->processedProducts[] = $product->id;
        }
        if (!empty($row['attribute_value_id']) && !empty($row['attribute_price'])) {
            $this->importProductAttribute($row, $product);
        }
    }

    private function importProduct($product, $row)
    {
        BranchProduct::updateOrCreate(
            [
                'branch_id' => $this->branch->id,
                'product_id' => $product->id,
            ],
            [
                'price' => $row['price'],
                'is_active' => (bool)$row['product_active'],
            ]
        );
    }

    private function importProductAttribute($row, $product)
    {
        $attributeValue = ProductAttributeValue::find($row['attribute_value_id']);
        if (!$attributeValue) {
            throw new Exception("Product attribute value with ID {$row['attribute_value_id']} not found");
        }
        BranchProductAttributeValue::updateOrCreate(
            [
                'branch_id' => $this->branch->id,
                'product_id' => $product->id,
                'product_attribute_id' => $attributeValue->product_attribute_id,
                'product_attribute_value_id' => $row['attribute_value_id'],
            ],
            [
                'price' => $row['attribute_price'],
            ]
        );
    }
}
