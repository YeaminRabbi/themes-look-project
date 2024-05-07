<?php

namespace App\Livewire;

use App\Models\Product;
use App\Models\ProductAttribute;
use Livewire\WithPagination;
use Livewire\Component;
use Illuminate\Support\Collection;

class PosComponent extends Component
{
    use WithPagination;

    public $cartItems = [];
    public $productsPerPage = 8;
    public $searchQuery = '';

    protected $queryString = [
        'searchQuery' => ['except' => ''],
    ];

    public function mount()
    {
        $this->cartItems = [];
        $this->resetPage();
    }   

    public function updatedSearchQuery()
    {
        $this->resetPage(); 
    }

    public function addCart(ProductAttribute $attribute)
    {
        $product =  $attribute->product;

        $originalPrice = number_format($attribute->selling_price, 2);
        $discountPercentage = $attribute->product->discount ?? 0;

        if($discountPercentage > 0)
        {
            $sellingPrice =  $attribute->selling_price * ((100 - $discountPercentage) / 100);
        }else{
            $sellingPrice = $originalPrice;
        }

        $cartItemKey = array_search($attribute->id, array_column($this->cartItems, 'attribute_id'));
        
        if ($cartItemKey !== false) {
            toastr()->warning('Product already added!');
            return;
        } else {

            $this->cartItems[] = [
                'product_id' => $product->id,
                'attribute_id' => $attribute->id,
                'image' => $product->image,
                'name' => $product->name . ' - ' . $attribute->color->name . '/' . $attribute->size->name,
                'price' => number_format($sellingPrice, 2),
                'quantity' => 1,
                'selling_quantity'=>$product->unit_value,
                'total_price' => $sellingPrice, 
            ];

            toastr()->success('Product added successfully!');

        }

    }

    public function removeCartItem($index)
    {
        unset($this->cartItems[$index]);
        $this->cartItems = array_values($this->cartItems);
        toastr()->warning('Product removed successfully!');

    }

    public function updatedCartItems($index )
    {
        if (isset($this->cartItems[$index])) {

            $newQuantity = $this->cartItems[$index]['quantity'];
            $itemPrice = $this->cartItems[$index]['price'];

            $newTotalPrice = (float)$newQuantity * (float)$itemPrice;

            $this->cartItems[$index]['total_price'] = $newTotalPrice;

            
        }
    }

    public function handleQuantityChange($index)
    {
        $this->updatedCartItems($index);

    }

    public function render()
    {
        $variants = ProductAttribute::query()
            ->when(!empty($this->searchQuery), function ($query) {
                return $query->whereHas('product', function ($q) {
                    $q->where('name', 'like', '%' . $this->searchQuery . '%')
                    ->orWhere('slug', 'like', '%' . $this->searchQuery . '%');
                });
            })
            ->whereHas('product')
            ->with(['product']) 
            ->latest()
            ->simplePaginate($this->productsPerPage);

        return view('livewire.pos-component', [
            'variants' => $variants, 
        ]);
    }
}
