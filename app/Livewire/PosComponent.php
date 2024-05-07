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
       $products = Product::query()
            ->when(!empty($this->searchQuery), function ($query) {
                return $query->where('name', 'like', '%' . $this->searchQuery . '%')
                                ->orWhere('slug', 'like', '%' . $this->searchQuery . '%');
            })
            ->latest()
            ->simplePaginate($this->productsPerPage);

        return view('livewire.pos-component', [
            'products' => $products, // Passing the paginator to the view
        ]);
    }
}
