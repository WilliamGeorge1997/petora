<?php

namespace App\Exports;

use Modules\Product\Entities\Product;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Style\Fill;

class ProductsWithAttributesExport implements FromCollection, WithHeadings, WithColumnWidths, WithStyles
{
    protected $separatorRows = [];

    public function collection()
    {
        $data = collect();

        Product::with(['attributes.values'])->active()->chunk(50, function ($products) use ($data) {
            foreach ($products as $product) {
                if ($product->attributes->count() > 0) {

                    foreach ($product->attributes as $attribute) {
                        foreach ($attribute->values as $value) {
                            $data->push([
                                'product_id' => $product->id,
                                'product_name' => $product->title,
                                'price' => $product->price,
                                'product_active' => $product->is_active,
                                'attribute_name' => $attribute->title,
                                'attribute_value' => $value->attribute_value,
                                'attribute_value_id' => $value->id,
                                'attribute_price' => $value->price,
                            ]);
                        }
                    }
                } else {

                    $data->push([
                        'product_id' => $product->id,
                        'product_name' => $product->title,
                        'price' => $product->price,
                        'product_active' => $product->is_active,
                        'attribute_name' => '',
                        'attribute_value' => '',
                        'attribute_value_id' => '',
                        'attribute_price' => '',
                    ]);
                }


                $data->push(['', '', '', '', '', '', '', '']);
            }
        });

        return $data;
    }

    public function headings(): array
    {
        return [
            'product_id',
            'product_name',
            'price',
            'product_active',
            'attribute_name',
            'attribute_value',
            'attribute_value_id',
            'attribute_price',
        ];
    }

    public function columnWidths(): array
    {
        return [
            'A' => 12,
            'B' => 25,
            'C' => 12,
            'D' => 12,
            'E' => 20,
            'F' => 20,
            'G' => 15,
            'H' => 15,
        ];
    }

    public function styles(\PhpOffice\PhpSpreadsheet\Worksheet\Worksheet $sheet)
    {

        // $sheet->getStyle('A:J')->getProtection()->setLocked(false);


        // $sheet->getStyle('A:A')->getProtection()->setLocked(true);
        // $sheet->getStyle('G:G')->getProtection()->setLocked(true);


        // $protection = $sheet->getProtection();
        // $protection->setSheet(true);
        // $protection->setSort(false);
        // $protection->setAutoFilter(false);
        // $protection->setFormatCells(false);
        // $protection->setFormatColumns(false);
        // $protection->setFormatRows(false);
        // $protection->setInsertColumns(false);
        // $protection->setInsertRows(false);
        // $protection->setDeleteColumns(false);



        // $protection->setDeleteRows(true);

        return [
            1 => [
                'font' => ['bold' => true, 'color' => ['argb' => 'FFFFFF']],
                'alignment' => ['horizontal' => 'center'],
                'fill' => [
                    'fillType' => Fill::FILL_SOLID,
                    'startColor' => ['argb' => '366092']
                ]
            ],
            'A:H' => ['alignment' => ['horizontal' => 'center']],
        ];
    }
}
