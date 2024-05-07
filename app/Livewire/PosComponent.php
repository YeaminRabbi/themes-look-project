<?php

namespace App\Livewire;

use App\Models\Product;
use App\Models\ProductAttribute;
use Livewire\WithPagination;
use Livewire\Component;

class PosComponent extends Component
{
    use WithPagination;

    // public $products = null;
    public $productsPerPage = 8;
    public $searchQuery = '';

    protected $queryString = [
        'searchQuery' => ['except' => ''],
    ];

    public function mount()
    {
        $this->resetPage();
    }   

    public function updatedSearchQuery()
    {
        $this->resetPage(); 
    }

    public function render()
    {
        $variants = ProductAttribute::query()
            ->when(!empty($this->searchQuery), function ($query) {
                // Filter by variant attributes or product name
                return $query->whereHas('product', function ($q) {
                    $q->where('name', 'like', '%' . $this->searchQuery . '%')
                    ->orWhere('slug', 'like', '%' . $this->searchQuery . '%');
                })
                ->orWhere('color_id', 'like', '%' . $this->searchQuery . '%')
                ->orWhere('size_id', 'like', '%' . $this->searchQuery . '%');
            })
            ->whereHas('product')
            ->with(['product']) // Load related product data
            ->latest()
            ->simplePaginate($this->productsPerPage);

        return view('livewire.pos-component', [
            'variants' => $variants, // Passing the paginator with variants
        ]);
    }
}
