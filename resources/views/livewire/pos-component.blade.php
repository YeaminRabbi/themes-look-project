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
                                            <div class="card text-center">
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
    </div>

</div>
