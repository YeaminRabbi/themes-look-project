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

    public $productAttributes = [
        [
            'color_id' => null,
            'size_id' => null,
            'purchase_price' => null,
            'selling_price' => null,
        ]
    ];

    private $unitsWithValues = [
        'kg' => ['1', '2', '3', '4', '5', '6', '7', '8', '9', '10'],
        'litter' => ['1', '2', '3', '4', '5', '6', '7', '8', '9', '10'],
        'pieces' => ['1', '2', '3', '4', '5', '6', '7', '8', '9', '10'],
    ];

    protected $rules = [
        'name' => 'required|string|max:255',
        'category_id' => 'required|exists:categories,id',
        'unit' => 'required|in:kg,litter,pieces',
        'unit_value' => 'required',
        'discount' => 'nullable|numeric|min:0',
        'tax' => 'nullable|numeric|min:0',
        'file' => 'required|image',
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
        $this->units = array_keys($this->unitsWithValues);
    }

    public function handleUnitValues($unit)
    {
        $this->unit_values = $this->unitsWithValues[$unit] ?? [];
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

    public function render()
    {
        return view('livewire.product-component');
    }

    
}
