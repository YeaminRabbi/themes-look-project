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
                                @if ($products)
                                    @foreach ($products as $product)
                                        <div class="col-md-3 mb-2">
                                            <div class="card text-center">
                                                <div class="card-body d-flex justify-content-center">
                                                    <img src="{{ asset('storage/'. $product->image) }}" class="img-fluid" alt="{{ $product->name }}" style="max-width:100px;max-height:100px;">
                                                </div>
                                                <div class="card-footer">
                                                    <p>{{ $product->name }}</p>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                   
                                @endif
                            </div>
                            <div class="d-flex justify-content-center mt-4">
                                {{ $products->links() }}
                            </div>
                        </div>
                    </div>
                    
                </div>
            </div>
        </div>
    </div>

</div>
