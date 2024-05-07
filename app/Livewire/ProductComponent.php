<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithFileUploads;
use App\Models\Product;
use App\Models\ProductAttribute;
use App\Models\Color;
use App\Models\Size;
use App\Models\Category;
use App\Services\ImageService;
use Illuminate\Support\Str;
use Illuminate\Http\Request;

class ProductComponent extends Component
{
    use WithFileUploads; 

    public $tableView = true;
    public $formView = false;

    public $products = null;
    public $categories = null;
    public $colors = null;
    public $sizes = null;
    public $units = null;
    public $unit_values = null;

    public $name;
    public $category_id;
    public $color_id;
    public $size_id;
    public $unit;
    public $unit_value;
    public $selling_price;
    public $purchase_price;
    public $discount;
    public $tax;
    public $file;

    public $selectedProductId = null;

    public $productAttributes = [
        [
            'color_id' => null,
            'size_id' => null,
            'purchase_price' => null,
            'selling_price' => null,
        ]
    ];

    private $unitsWithValues = [
        'kg', 
        'litter' ,
        'pieces' 
    ];

    protected $rules = [
        'name' => 'required|string|max:255',
        'category_id' => 'required|exists:categories,id',
        'unit' => 'required|in:kg,litter,pieces',
        'unit_value' => 'required',
        'discount' => 'nullable|numeric|min:0',
        'tax' => 'nullable|numeric|min:0',
        'file' => 'nullable|image',
    ];

    

    public function mount()
    {
        $this->loadAttributes();
        $this->loadUnits();
        $this->loadProducts();
    }

    public function loadProducts()
    {
        $this->products = Product::latest()->get();
    }

    public function loadAttributes()
    {
        $this->colors = Color::latest()->get();
        $this->sizes = Size::latest()->get();
        $this->categories = Category::latest()->get();
    }

    public function loadUnits()
    {
        $this->units = $this->unitsWithValues;
    }

    public function addAttribute()
    {
        $this->productAttributes[] = [
            'color_id' => null,
            'size_id' => null,
            'purchase_price' => null,
            'selling_price' => null,
        ];
    }

    public function removeAttribute($index)
    {
        unset($this->productAttributes[$index]);
        $this->productAttributes = array_values($this->productAttributes);
    }

    public function storeProduct()
    {
        if ($this->selectedProductId === null) {
            $this->createProduct();
        } else {
            $this->updateProduct();
        }
    }

    public function createProduct()
    {
        
        $this->validate();

        $imagePath = $this->file->store('products', 'public');

        $product = Product::create([
            'name' => $this->name,
            'slug' => Str::slug($this->name),
            'category_id' => $this->category_id,
            'unit' => $this->unit,
            'unit_value' => $this->unit_value,
            'discount' => $this->discount,
            'tax' => $this->tax,
            'image' => $imagePath,
        ]);

       

        // Save attributes for the product
        if($this->productAttributes){
            foreach ($this->productAttributes as $attribute) {
                $attribute['product_id'] = $product->id;
                $product->attributes()->create($attribute);
            }
        }

        // Reset form inputs and attributes
        $this->resetFormInputs();
        $this->resetAttributes();

        $this->loadProducts();
        toastr()->success('Product created successfully!');
    }

    private function resetFormInputs()
    {
        $this->name = '';
        $this->category_id = '';
        $this->size_id = '';
        $this->unit = '';
        $this->unit_value = '';
        $this->selling_price = '';
        $this->purchase_price = '';
        $this->discount = '';
        $this->tax = '';
        $this->color_id = '';
        $this->file = null;
        $this->selectedProductId = null;
    }

    private function resetAttributes()
    {
        $this->productAttributes = [
            [
                'color_id' => null,
                'size_id' => null,
                'purchase_price' => null,
                'selling_price' => null,
            ]
        ];
    }

    public function viewTable()
    {
        $this->tableView = true;
        $this->formView = false;
    }

    public function viewForm()
    {
        $this->tableView = false;
        $this->formView = true;
    }

    public function editProduct($productId)
    {
        $product = Product::findOrFail($productId);

        $this->name = $product->name;
        $this->category_id = $product->category_id;
        $this->unit = $product->unit;
        $this->unit_value = $product->unit_value;
        $this->discount = $product->discount;
        $this->tax = $product->tax;
        $this->file = null;
        
        $this->productAttributes = $product->attributes->map(function($attribute) {
            return [
                'color_id' => $attribute->color_id,
                'size_id' => $attribute->size_id,
                'purchase_price' => $attribute->purchase_price,
                'selling_price' => $attribute->selling_price,
            ];
        })->toArray();
        
        $this->selectedProductId = $product->id;
        $this->viewForm();
    }

    public function updateProduct()
    {
        $this->validate();

        $product = Product::findOrFail($this->selectedProductId);

        $product->update([
            'name' => $this->name,
            'slug' => Str::slug($this->name),
            'category_id' => $this->category_id,
            'unit' => $this->unit,
            'unit_value' => $this->unit_value,
            'discount' => $this->discount,
            'tax' => $this->tax,
        ]);

        // Handle file upload
        if ($this->file) {
            $imagePath = $this->file->store('products', 'public');
            $product->update(['image' => $imagePath]);
        }

        $product->attributes()->delete(); 

        // Create new attributes
        foreach ($this->productAttributes as $attribute) {
            $attribute['product_id'] = $product->id;
            $product->attributes()->create($attribute);
        }

        $this->resetFormInputs();
        $this->resetAttributes();

        $this->loadProducts();
        $this->viewTable();

        toastr()->success('Product updated successfully!');
    }

    public function deleteProduct(Product $product)
    {
        $product->delete();
        $this->loadProducts();
        toastr()->warning('Product removed successfully!');

    }

    public function render()
    {
        return view('livewire.product-component');
    }

    
}
