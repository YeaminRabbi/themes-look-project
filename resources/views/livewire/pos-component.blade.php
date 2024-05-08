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
                            <input type="search" class="form-control" wire:model.live="searchQuery" name="search"
                                placeholder="Search by code or name">
                        </div>
                        <div class="col-md-12 mb-3">
                            <div class="row">
                                @if ($variants)
                                    @foreach ($variants as $item)
                                        <div class="col-md-3 mb-2">
                                            <div class="card text-center" style="cursor: pointer;"
                                                wire:click="addCart({{ $item->id }})">
                                                <div class="card-body d-flex justify-content-center">
                                                    <img src="{{ asset('storage/' . $item->product->image ?? '') }}"
                                                        class="img-fluid" alt="{{ $item->product->name ?? '' }}"
                                                        style="max-width:100px;max-height:100px;">
                                                </div>
                                                <div class="card-footer">
                                                    <span>{{ $item->product->name }}</span> <br>
                                                    <span>{{ $item->color->name }} / {{ $item->size->name }}</span> <br>
                                                    @php
                                                        // Get selling price, discount percentage, and tax percentage
                                                        $originalPrice = $item->selling_price;
                                                        $discountPercentage = $item->product->discount ?? 0;
                                                        $taxPercentage = $item->product->tax ?? 0;

                                                        // Calculate the final price and tax adjustments
                                                        $finalPrice = $originalPrice;
                                                        if ($discountPercentage > 0) {
                                                            // Calculate discounted price
                                                            $finalPrice *= 1 - $discountPercentage / 100;
                                                        }

                                                        // Calculate tax adjustment if there is any tax
                                                        if ($taxPercentage > 0) {
                                                            $finalPrice *= 1 + $taxPercentage / 100;
                                                            $originalPrice *= 1 + $taxPercentage / 100;
                                                        }

                                                        // Format prices for display
                                                        $formattedOriginalPrice = number_format($originalPrice, 2);
                                                        $formattedFinalPrice = number_format($finalPrice, 2);
                                                    @endphp

                                                    <!-- Display prices -->
                                                    <div>
                                                        @if ($discountPercentage > 0)
                                                            <!-- Display original price with strikethrough and discounted price -->
                                                            <span style="text-decoration: line-through;">
                                                                ${{ $formattedOriginalPrice }}
                                                            </span>
                                                            <span class="text-danger">
                                                                ${{ $formattedFinalPrice }}
                                                            </span>
                                                        @else
                                                            <!-- Display original price if there is no discount -->
                                                            <span>${{ $formattedOriginalPrice }}</span>
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
            <div class="card">
                <div class="card-header">
                    Billing Section
                </div>
                <div class="card-body">
                    {{-- <div class="row">
                        <div class="col-md-12 text-right">
                            <span style="color: blue;">Tax included</span>
                        </div>
                    </div> --}}
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
                                        <img src="{{ asset('storage/' . $item['image']) }}" alt="Product Image"
                                            style="max-width: 65px;max-height: 65px;">
                                        <strong>{{ \Illuminate\Support\Str::limit($item['name'], 10, '...') }}</strong>
                                    </td>

                                    <td>
                                        <input type="number" min="1" max="{{ $item['selling_quantity'] }}"
                                            class="form-control" wire:model="cartItems.{{ $index }}.quantity"
                                            wire:change="handleQuantityChange({{ $index }})"
                                            style="max-width: 5rem;">

                                    </td>
                                    <td>${{ number_format($item['total_price'], 2) }}</td>
                                    <td>
                                        <button class="btn btn-danger"
                                            wire:click="removeCartItem({{ $index }})">Delete</button>
                                    </td>
                                </tr>
                            @endforeach

                            <tr>
                                <td>Total: <span style="color: blue">(Tax Included)</span>
                                </td>
                                <td></td>
                                <td></td>
                                <td>
                                    <span>${{ number_format($totalPrice, 2) ?? 0 }}</span>
                                </td>
                            </tr>
                        </tbody>
                    </table>

                    <div>
                        @if (count($cartItems) > 0)
                            <a wire:click="placeOrder" class="btn btn-info" style="width: 100%;">Place Order</a>
                        @endif
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>
