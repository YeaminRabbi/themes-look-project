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
                            <th scope="col">Name</th>
                            <th scope="col">Code</th>
                            <th scope="col">Category</th>
                            <th scope="col">Attributes</th>
                        </tr>
                    </thead>
                    <tbody>
                        @if ($products)
                            @foreach ($products as $key => $item)
                                <tr>
                                    <th scope="row">{{ $key + 1 }}</th>
                                    <td>{{ $item->name }}</td>
                                    <td>{{ $item->slug }}</td>
                                    <td> {{ $item->category->name }}</td>
                                    <td>
                                        <span class="badge badge-primary">{{ $item->color->name }}</span>
                                        <span class="badge badge-success">{{ $item->size->name }}</span>
                                        <span class="badge badge-dark">Purchase: ${{ $item->purchase_price }}</span>
                                        <span class="badge badge-secondary">Selling: ${{ $item->selling_price }}</span>
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
                    <form wire:submit.prevent="storeProduct">
                        @csrf
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <input type="text" class="form-control" name="name" wire:model="name"
                                        placeholder="Enter product name" required>
                                    @error('name')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                                <div class="form-group">
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
                                    <input type="number" class="form-control" name="discount" wire:model="discount"
                                        placeholder="Enter product discount (optional)">
                                    @error('discount')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                                <div class="form-group">
                                    <input type="number" class="form-control" name="tax" wire:model="tax"
                                        placeholder="Enter product tax (optional)">
                                    @error('tax')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="row">
                                    <div class="form-group col-md-4">
                                        <select class="form-control" name="color_id" wire:model="color_id" required>
                                            <option value="" selected>--select color--</option>
                                            @if ($colors)
                                                @foreach ($colors as $item)
                                                    <option value="{{ $item->id }}">{{ $item->name }}</option>
                                                @endforeach
                                            @endif
                                        </select>
                                        @error('color_id')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>
                                    <div class="form-group col-md-4">
                                        <select class="form-control" name="size_id" wire:model="size_id" required>
                                            <option value="" selected>--select size--</option>
                                            @if ($sizes)
                                                @foreach ($sizes as $item)
                                                    <option value="{{ $item->id }}">{{ $item->name }}</option>
                                                @endforeach
                                            @endif
                                        </select>
                                        @error('size_id')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>
                                    <div class="form-group col-md-4">
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
                                    <div class="form-group col-md-4">
                                        <select class="form-control" name="unit_value" wire:model="unit_value" required>
                                            <option value="" selected>--select unit value--</option>
                                            @if ($unit_values)
                                                @foreach ($unit_values as $item)
                                                    <option value="{{ $item }}">{{ $item }}</option>
                                                @endforeach
                                            @endif
                                        </select>
                                        @error('unit_value')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>
                                    <div class="form-group col-md-4">
                                        <input type="number" class="form-control" name="selling_price"
                                            wire:model="selling_price" placeholder="Enter selling price" required>
                                        @error('selling_price')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>
                                    <div class="form-group col-md-4">
                                        <input type="number" class="form-control" name="purchase_price"
                                            wire:model="purchase_price" placeholder="Enter purchase price" required>
                                        @error('purchase_price')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>

                            </div>
                        </div>

                        <div class="d-flex justify-content-start">
                            <button type="submit" class="btn btn-success mr-2">Submit</button>
                            <button class="btn btn-sm btn-dark" wire:click="viewTable">View Products</button>

                        </div>

                    </form>
                </div>

            </div>
        @endif

    </div>

</div>
