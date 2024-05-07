<div>
    <div class="row">
        @if ($tableView)
            <div class="col-md-12">
                <div class="row mb-3 mt-2">
                    <div class="col-md-12 d-flex justify-content-between">
                        <h2>Product Table</h2>
                        <button class="btn btn-sm btn-success" wire:click="viewForm">Add Products</button>
                    </div>
                </div>
                <table class="table">
                    <thead class="thead-dark">
                        <tr>
                            <th scope="col">#</th>
                            <th scope="col">Image</th>
                            <th scope="col">Name</th>
                            <th scope="col">Code</th>
                            <th scope="col">Category</th>
                            <th scope="col">Qty</th>
                            <th scope="col">Tax/Discount</th>
                            <th scope="col">Attributes</th>
                            <th scope="col">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @if ($products)
                            @foreach ($products as $key => $item)
                                <tr>
                                    <th scope="row">{{ $key + 1 }}</th>
                                    <td><img src="{{ asset('storage/'.$item->image) }}" alt="{{ $item->name }}" style="max-width: 100px; height: auto;"></td>
                                    <td>{{ $item->name }}</td>
                                    <td>{{ $item->slug }}</td>
                                    <td> {{ $item->category->name }}</td>
                                    <td>
                                        <span>{{ $item->unit_value }} </span> 
                                    </td>
                                    <td>
                                        @if ($item->tax > 0)
                                            <p class="badge badge-info">Tax : {{ $item->tax }}%</p>
                                        @endif
                                        @if ($item->discount > 0)
                                            <p class="badge badge-warning">Discount: {{ $item->discount }}%</p>
                                        @endif

                                        @if ($item->tax <= 0 && $item->discount <= 0)
                                            <p class="badge badge-success">N/A</p>
                                            
                                        @endif
                                    </td>
                                    <td>
                                        @if ($item->attributes()->count() > 0)
                                            @foreach ($item->attributes as $attribute)
                                              <div>
                                                <span class="badge badge-primary">{{ $attribute->color->name }}</span>
                                                <span class="badge badge-success">{{ $attribute->size->name }}</span>
                                                <span class="badge badge-dark">Purchase: ${{ $attribute->purchase_price }}</span>
                                                <span class="badge badge-secondary">Selling: ${{ $attribute->selling_price }}</span>
                                              </div>
                                            @endforeach
                                        @endif
                                    </td>
                                    
                                    <td>
                                        <a class="btn btn-sm btn-primary" wire:click="editProduct({{ $item->id }})">Edit</a>
                                        <a class="btn btn-sm btn-danger" wire:click="deleteProduct({{ $item->id }})">Delete</a>
                                    </td>
                                </tr>
                            @endforeach
                        @else
                            <span>No Product Found</span>
                        @endif
                    </tbody>
                </table>


            </div>
        @elseif ($formView)
            <div class="col-md-12">
                <div class="row mb-3 mt-2">
                    <div class="col-md-12 d-flex justify-content-between">
                        <h2>Add Product</h2>
                        <button class="btn btn-sm btn-success" wire:click="viewTable">View Products</button>
                    </div>
                </div>
                <div class="form-section">
                    <form wire:submit="storeProduct">
                        @csrf
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Product Name</label>
                                    <input type="text" class="form-control" name="name" wire:model="name"
                                        placeholder="Enter product name" required>
                                    @error('name')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                                <div class="form-group">
                                    <label>Category</label>
                                    <select class="form-control" name="category_id" wire:model="category_id" required>
                                        <option value="" selected>--select category--</option>
                                        @if ($categories)
                                            @foreach ($categories as $item)
                                                <option value="{{ $item->id }}">{{ $item->name }}</option>
                                            @endforeach
                                        @endif
                                    </select>
                                    @error('category_id')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                                <div class="form-group">
                                    <label>Unit</label>
                                    <select class="form-control" name="unit" wire:model="unit" required
                                        wire:change="handleUnitValues($event.target.value)">
                                        <option value="" selected>--select unit--</option>
                                        @if ($units)
                                            @foreach ($units as $item)
                                                <option value="{{ $item }}">{{ $item }}</option>
                                            @endforeach
                                        @endif
                                    </select>
                                    @error('unit')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                                <div class="form-group">
                                    <label>Quanity/Unit Value</label>
                                    <input type="number" class="form-control" name="unit_value" wire:model="unit_value" required>
                                    @error('unit_value')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                                <div class="form-group">
                                    <label>Discount (%)</label>
                                    <input type="number" class="form-control" name="discount" wire:model="discount"
                                        placeholder="Enter product discount (optional)">
                                    @error('discount')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                                <div class="form-group">
                                    <label>Tax</label>
                                    <input type="number" class="form-control" name="tax" wire:model="tax"
                                        placeholder="Enter product tax (optional)">
                                    @error('tax')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                                <div class="form-group">
                                    <label>Image</label>
                                    <input type="file" class="form-control" name="file" wire:model="file"
                                        placeholder="Enter product image">
                                    @error('file')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>

                                <div class="d-flex justify-content-start">
                                    <button type="submit" class="btn btn-success mr-2" >Submit</button>
                                    <a class="btn btn-sm btn-dark" wire:click="viewTable">View Products</a>
                                </div>
                            </div>
                            <div class="col-md-8">
                                <div class="row mb-2">
                                    <div class="col-md-12">
                                        <a class="btn btn-primary mr-2"
                                            wire:click="addAttribute">Add Attribute</a>
                                    </div>
                                </div>
                                @foreach ($productAttributes as $index => $attribute)
                                    <div class="row mb-3">
                                        <div class="form-group col-md-3">
                                            <label>Color</label>
                                            <select class="form-control"
                                                name="productAttributes[{{ $index }}][color_id]"
                                                wire:model="productAttributes.{{ $index }}.color_id" required>
                                                <option value="" selected>--select color--</option>
                                                @if ($colors)
                                                    @foreach ($colors as $color)
                                                        <option value="{{ $color->id }}">{{ $color->name }}
                                                        </option>
                                                    @endforeach
                                                @endif
                                            </select>
                                        </div>

                                        <div class="form-group col-md-3">
                                            <label>Size</label>
                                            <select class="form-control"
                                                name="productAttributes[{{ $index }}][size_id]"
                                                wire:model="productAttributes.{{ $index }}.size_id" required>
                                                <option value="" selected>--select size--</option>
                                                @if ($sizes)
                                                    @foreach ($sizes as $size)
                                                        <option value="{{ $size->id }}">{{ $size->name }}
                                                        </option>
                                                    @endforeach
                                                @endif
                                            </select>
                                        </div>

                                        <div class="form-group col-md-2">
                                            <label>Purchase Price</label>
                                            <input type="number" class="form-control"
                                                name="productAttributes[{{ $index }}][purchase_price]"
                                                wire:model="productAttributes.{{ $index }}.purchase_price"
                                                placeholder="Enter purchase price" required>
                                        </div>

                                        <div class="form-group col-md-2">
                                            <label>Selling Price</label>
                                            <input type="number" class="form-control"
                                                name="productAttributes[{{ $index }}][selling_price]"
                                                wire:model="productAttributes.{{ $index }}.selling_price"
                                                placeholder="Enter selling price" required>
                                        </div>

                                        <div class="col-md-2">
                                            <a class="btn btn-danger mr-2 mt-3"
                                                wire:click="removeAttribute({{ $index }})">X</a>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </form>
                </div>

            </div>
        @endif

    </div>

</div>
