<?php

namespace App\Livewire;

use App\Models\Product;
use App\Models\ProductAttribute;
use App\Models\Order;
use App\Models\OrderItem;
use Livewire\WithPagination;
use Livewire\Component;
use Illuminate\Support\Collection;

class PosComponent extends Component
{
    use WithPagination;

    public $cartItems = [];
    public $productsPerPage = 8;
    public $searchQuery = '';
    public $billing = [];
    public $totalPrice= 0;

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
        $taxPercentage = $attribute->product->tax ?? 0;

        if($discountPercentage > 0)
        {
            $sellingPrice =  $attribute->selling_price * ((100 - $discountPercentage) / 100);
        }else{
            $sellingPrice = $originalPrice;
        }

        if($taxPercentage > 0)
        {
            $sellingPrice =  $sellingPrice * ((100 + $taxPercentage) / 100);
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

            $this->totalCost();
        }

    }

    public function totalCost()
    {
        $this->totalPrice  = 0;
        foreach($this->cartItems as $item)
        {
            $this->totalPrice += $item['total_price'];
        }
    }

    public function getdiscount($id)
    {
        $attribute = ProductAttribute::find($id);

        $originalPrice = number_format($attribute->selling_price, 2);
        $discountPercentage = $attribute->product->discount ?? 0;

        if ($discountPercentage > 0) {
            $discountedSellingPrice = $originalPrice * ((100 - $discountPercentage) / 100);
            $discountedAmount = $originalPrice - $discountedSellingPrice;
        } else {
            // No discount applied, so discounted amount is 0
            $discountedAmount = 0;
        }

        return $discountedAmount;
        
    }

    public function removeCartItem($index)
    {
        unset($this->cartItems[$index]);
        $this->cartItems = array_values($this->cartItems);
        $this->totalCost();
        toastr()->warning('Product removed successfully!');

    }

    public function updatedCartItems($index )
    {
        if (isset($this->cartItems[$index])) {

            $newQuantity = $this->cartItems[$index]['quantity'];
            $itemPrice = $this->cartItems[$index]['price'];

            $newTotalPrice = (float)$newQuantity * (float)$itemPrice;

            $this->cartItems[$index]['total_price'] = $newTotalPrice;
            $this->totalCost();
        }
    }

    public function handleQuantityChange($index)
    {
        $this->updatedCartItems($index);

    }

    public function placeOrder()
    {
        // Create an order
        $order = Order::create([
            'total_cost' => $this->totalPrice,
            // Add other order fields here (e.g., customer ID, etc.)
        ]);
        
        // Loop through cart items to create order items
        foreach ($this->cartItems as $item) {
            $product = Product::find($item['product_id']);

            OrderItem::create([
                'order_id' => $order->id,
                'product_id' => $item['product_id'],
                'attribute_id' => $item['attribute_id'],
                'quantity' => $item['quantity'],
                'price' => $item['price'],
                'total_price' => number_format($item['total_price'], 2),
                'tax' => $product->tax,
                'discount' => $product->discount,
            ]);

            $stockUnits = $product->unit_value;
            $product->update([
                'unit_value' => $stockUnits - $item['quantity'],
            ]);

        }

        // Clear cart after placing the order
        $this->cartItems = [];
        $this->totalPrice = 0;

        // Notify the user
        toastr()->success('Order placed successfully!');

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
            ->whereHas('product', function ($q) {
                $q->where('unit_value', '>', 0);
            })
            ->with(['product']) 
            ->latest()
            ->simplePaginate($this->productsPerPage);

        return view('livewire.pos-component', [
            'variants' => $variants, 
        ]);
    }
}
