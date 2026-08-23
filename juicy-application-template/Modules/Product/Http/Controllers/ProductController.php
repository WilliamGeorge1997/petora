<?php

namespace Modules\Product\Http\Controllers;

use App\Exports\ProductsWithAttributesExport;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Maatwebsite\Excel\Facades\Excel;
use Modules\Common\Helper\UploaderHelper;
use Modules\Product\DTO\AttributeDto;
use Modules\Product\DTO\AttributeValuesDto;
use Modules\Product\DTO\ProductDto;
use Modules\Product\Http\Requests\ProductAttributeRequest;
use Modules\Product\Http\Requests\ProductRequest;
use Modules\Product\Service\ProductService;
use Modules\Product\Validation\ProductValidation;
use Modules\Product\ViewModel\ProductViewModel;

class ProductController extends Controller
{
    use UploaderHelper, ProductValidation;

    private $productService;

    public function __construct(ProductService $productService)
    {
        $this->middleware(['auth:admin']);
        $this->middleware(['prevent-back-history'])->except('exportWithAttributes');
        $this->productService = $productService;
        $this->middleware('permission:Index-product|Create-product|Edit-product|Delete-product', ['only' => ['index', 'store']]);
        $this->middleware('permission:Create-product', ['only' => ['create', 'store']]);
        $this->middleware('permission:Edit-product', ['only' => ['edit', 'update', 'activate']]);
        $this->middleware('permission:Delete-product', ['only' => ['destroy']]);
    }

    public function index(Request $request)
    {
        $data = $request->all();
        $data['paginated'] = 50;
        $products = $this->productService->findAll($data, ['category']);
        if ($request->ajax()) {
            return response()->json(['data' => $products->items()]);
        }
        return view('product::products.index', compact('products'));
    }

    /**
     * Show the form for creating a new resource.
     * @return Renderable
     */
    public function create()
    {
        $viewModel = new ProductViewModel();
        return view('product::products.create', compact('viewModel'));
    }

    public function store(ProductRequest $request)
    {
        try {
            $this->productService->save((new ProductDto($request))->dataFromRequest());
        } catch (\Exception $e) {
            return redirect()->back()->withInput()->with('error', $e->getMessage());
        }

        return redirect('/admin/products')->with('created', 'created');
    }

    public function edit($id)
    {
        $product = $this->productService->findById($id, ['attributes.values']);
        $addon_ids = $product->addons()->pluck('addons.id');
        $side_ids = $product->sides()->pluck('sides.id');
        $sizeAttribute = $product->attributes->load('values')->first(function ($attribute) {
            return $attribute->getTranslation('title', 'en') === 'Size';
        });
        $viewModel = new ProductViewModel();
        return view('product::products.edit', compact('product', 'viewModel', 'addon_ids', 'sizeAttribute', 'side_ids'));
    }


    public function update(ProductRequest $request, $id)
    {
        $data = (new ProductDto($request))->dataFromRequest();
        if (isset($data['override_price']) && $data['override_price'] == 1) {
            if ($this->productService->checkOverridePriceAttribute($id, @$data['attribute_id'])) {
                return back()->withInput()->with('error', 'error');
            }
        }
        $this->productService->update($id, $data);
        return redirect('admin/products')->with('updated', 'updated');
    }

    public function destroy($id, Request $request)
    {

        $this->productService->delete($id);
        return response()->json(['data' => 'success'], 200);
    }

    public function activate($id)
    {
        $this->productService->activate($id);
        return redirect('admin/products')->with('updated', 'updated');
    }

    public function productAttributes($id)
    {
        $product = $this->productService->findById($id);
        $product_attrributes = $this->productService->productAttributes($id);
        return view('product::attributes.index', compact('product', 'product_attrributes'));
    }

    public function attributeCreate($id)
    {
        $product = $this->productService->findById($id);
        return view('product::attributes.create', compact('product'));
    }

    public function attributeStore($id, ProductAttributeRequest $request)
    {
        $data = (new AttributeDto($request))->dataFromRequest();
        if (isset($data['override_price']) && $data['override_price'] == 1) {
            if ($this->productService->checkOverridePriceAttribute($data['product_id'])) {
                return back()->withInput()->with('error', 'error');
            }
        }
        $attribute = $this->productService->attributeStore($data);
        $attribute_values_data = (new AttributeValuesDto($attribute['id'], $request))->dataFromRequest();
        $this->productService->attributeValuesStore($attribute_values_data);
        return redirect('admin/products')->with('updated', 'updated');
    }


    public function editProductAttribute($id)
    {
        $product_attribute = $this->productService->findAttribute($id, ['values']);
        return view('product::attributes.edit', compact('product_attribute'));
    }

    public function updateProductAttribute($id, ProductAttributeRequest $request)
    {
        $data = (new AttributeDto($request))->dataFromRequest();
        if (isset($data['override_price']) && $data['override_price'] == 1) {
            if ($this->productService->checkOverridePriceAttribute($data['product_id'], $id)) {
                return back()->withInput()->with('error', 'error');
            }
        }
        $attribute_values_data = (new AttributeValuesDto($id, $request))->dataFromRequest();

        try {
            $this->productService->attributeValuesUpdate($id, $attribute_values_data);
            $this->productService->attributeUpdate($id, $data);
        } catch (\Exception $e) {
            return back()->withInput()->with('error', $e->getMessage());
        }

        return redirect('admin/product/' . $data['product_id'] . '/attributes')->with('updated', 'updated');
    }

    public function deleteProductAttribute($id)
    {
        try {
            $this->productService->deleteProductAttributes($id);
            return back()->with('success', 'تم حذف الخاصية بنجاح');
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }


    public function deleteProductPhoto(request $request)
    {
        $this->productService->deleteProductPhotos($request->product_photo_id);
        return back();
    }

    // public function sliders(Request $request)
    // {
    //     $products = $this->productService->sliderProducts(100, ['category']);
    //     if ($request->ajax()) {
    //         return response()->json(['data' => $products]);
    //     }
    //     return view('product::products.sliders');
    // }

    public function exportWithAttributes()
    {
        return Excel::download(
            new ProductsWithAttributesExport(),
            'all_products_with_attributes.xlsx'
        );
    }
}
