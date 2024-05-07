<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Product;
use App\Models\ProductAttribute;
use App\Models\Color;
use App\Models\Size;
use App\Models\Category;
use Illuminate\Support\Str;

class ProductComponent extends Component
{
    public $tableView = false;
    public $formView = true;

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

    public $productAttributes = [
        [
            'color_id' => null,
            'size_id' => null,
            'purchase_price' => null,
            'selling_price' => null,
        ]
    ];

    private $unitsWithValues = [
        'kg' => ['1kg', '2kg', '3kg', '4kg', '5kg', '6kg', '7kg', '8kg', '9kg', '10kg'],
        'litter' => ['1litter', '2litters', '3litters', '4litters', '5litters', '6litters', '7litters', '8litters', '9litters', '10litters'],
        'pieces' => ['1piece', '2pieces', '3pieces', '4pieces', '5pieces', '6pieces', '7pieces', '8pieces', '9pieces', '10pieces']
    ];

    protected $rules = [
        'name' => 'required|string|max:255',
        'category_id' => 'required|exists:categories,id',
        'color_id' => 'required|exists:colors,id',
        'size_id' => 'required|exists:sizes,id',
        'unit' => 'required|in:kg,litter,pieces',
        'unit_value' => 'required',
        'selling_price' => 'required|numeric|min:0',
        'purchase_price' => 'required|numeric|min:0',
        'discount' => 'nullable|numeric|min:0',
        'tax' => 'nullable|numeric|min:0',
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

    // public function addAttribute()
    // {
    //     // Add a new set of attributes
    //     $this->attributes[] = [
    //         'color_id' => null,
    //         'size_id' => null,
    //         'purchase_price' => null,
    //         'selling_price' => null,
    //     ];
    // }

    // public function removeAttribute($index)
    // {
    //     // Remove a set of attributes based on the index
    //     unset($this->attributes[$index]);
    //     $this->attributes = array_values($this->attributes);
    // }

    public function storeProduct()
    {
        $this->validate();

        $product = new Product([
            'name' => $this->name,
            'slug' => Str::slug($this->name),
            'category_id' => $this->category_id,
            'size_id' => $this->size_id,
            'color_id' => $this->color_id,
            'unit' => $this->unit,
            'unit_value' => $this->unit_value,
            'selling_price' => $this->selling_price,
            'purchase_price' => $this->purchase_price,
            'discount' => $this->discount,
            'tax' => $this->tax,
        ]);

        $product->save();

        $this->resetFormInputs();
        $this->loadProducts();

        toastr()->success('Product created successfully!');

    }

    // public function storeProduct()
    // {
    //     $this->validate();

    //     // Create the product
    //     $product = Product::create([
    //         'name' => $this->name,
    //         'slug' => Str::slug($this->name),
    //         'category_id' => $this->category_id,
    //         'unit' => $this->unit,
    //         'unit_value' => $this->unit_value,
    //         'discount' => $this->discount,
    //         'tax' => $this->tax,
    //     ]);

    //     // Save attributes for the product
    //     foreach ($this->attributes as $attribute) {
    //         $product->attributes()->create($attribute);
    //     }

    //     // Reset form inputs and attributes
    //     $this->resetFormInputs();
    //     $this->resetAttributes();

    //     $this->loadProducts();
    //     toastr()->success('Product created successfully!');
    // }

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
    }

    // private function resetAttributes()
    // {
    //     $this->attributes = [
    //         [
    //             'color_id' => null,
    //             'size_id' => null,
    //             'purchase_price' => null,
    //             'selling_price' => null,
    //         ]
    //     ];
    // }

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

    // if ($request->hasFile('file')) {
    //     $service = new ImageService();
    //     $service->store($request, $banner);          
    // }
}
