<div>
    
    <div class="row py-3">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    Product Section
                </div>

                <div class="card-body">
                    <div class="row">
                        <div class="col-md-12 mb-3">
                            <input type="search" class="form-control" wire:model.live="searchQuery" name="search" placeholder="Search by code or name">
                        </div>
                        <div class="col-md-12 mb-3">
                            <div class="row">
                                @if ($variants)
                                    @foreach ($variants as $item)
                                        <div class="col-md-3 mb-2">
                                            <div class="card text-center" style="cursor: pointer;" wire:click="addCart({{ $item->id }})">
                                                <div class="card-body d-flex justify-content-center">
                                                    <img src="{{ asset('storage/'. $item->product->image ?? '') }}" class="img-fluid" alt="{{ $item->product->name ?? '' }}" style="max-width:100px;max-height:100px;">
                                                </div>
                                                <div class="card-footer">
                                                    <span>{{ $item->product->name }}</span> <br>
                                                    <span>{{ $item->color->name }} / {{ $item->size->name }}</span> <br>
                                                    <div>
                                                        @php
                                                            $originalPrice = number_format($item->selling_price, 2);
                                                            $discountPercentage = $item->product->discount ?? 0;
                                                        @endphp

                                                        @if ($discountPercentage > 0)
                                                            <!-- Calculate and display discounted selling price -->
                                                            @php
                                                                $discountedPrice = $item->selling_price * ((100 - $discountPercentage) / 100);
                                                            @endphp
                                                            <span style="text-decoration: line-through;">
                                                                ${{ $originalPrice }}
                                                            </span>
                                                            <span class="text-danger">
                                                                ${{ number_format($discountedPrice, 2) }}
                                                            </span>
                                                        @else
                                                            <span>${{ $originalPrice }}</span>
                                                        @endif
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                   
                                @endif
                            </div>
                            <div class="d-flex justify-content-center mt-4">
                                {{ $variants->links() }}
                            </div>
                        </div>
                    </div>
                    
                </div>
            </div>
        </div>
        <div class="col-md-4">
            {{-- <div class="card">
                <div class="card-header">
                    Billing Section
                </div>
                <div class="card-body">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Item</th>
                                <th>QTY</th>
                                <th>Price</th>
                                <th>Delete</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if (count($cartItems) > 0)
                                @foreach ($cartItems as $item)
                                <tr>
                                    <td>1</td>
                                    
                                </tr>
                                @endforeach
                               
                            @endif
                        </tbody>
                    </table>
                </div>
            </div> --}}
            <div class="card">
                <div class="card-header">
                    Billing Section
                </div>
                <div class="card-body">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Item</th>
                                <th>QTY</th>
                                <th>Price</th>
                                <th>Delete</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($cartItems as $index => $item)
                                <tr>
                                    <td>
                                        <img src="{{ asset('storage/'. $item['image']) }}" alt="Product Image" style="max-width: 65px;max-height: 65px;">
                                        <strong>{{ \Illuminate\Support\Str::limit($item['name'], 10, '...') }}</strong></td>

                                    <td>
                                        <input type="number" min="1" max="{{ $item['selling_quantity'] }}" class="form-control" wire:model="cartItems.{{ $index }}.quantity" wire:change="handleQuantityChange({{ $index }})" style="max-width: 5rem;">

                                    </td>
                                    <td>${{ number_format($item['total_price'], 2) }}</td>
                                    <td>
                                        <button class="btn btn-danger" wire:click="removeCartItem({{ $index }})">Delete</button>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
            
        </div>
    </div>
</div>
