@extends('layouts.master')

@section('content')
   <div class="row">
   
    <div class="col-md-12">
        <div class="row py-2">
            <div class="col-md-12 d-flex justify-content-between">
                <h2>Color Table</h2>
                <a href="{{ route('order.list') }}" class="btn btn-primary">Order List</a>
            </div>
        </div>
        <table class="table">
            <thead class="thead-dark">
                <tr>
                    <th scope="col">#</th>
                    <th scope="col">Product</th>
                    <th scope="col">Attribute</th>
                    <th scope="col">Tax/Discount</th>
                    <th scope="col">Qty</th>
                    <th scope="col">Price</th>
                    <th scope="col">Total Price</th>
                </tr>
            </thead>
            <tbody>
                @if ($orderItems)
                    @foreach ($orderItems as $key => $item)
                        <tr>
                            <td>{{ $key+1 }}</td>
                            <td>{{ $item->product->name }}</td>
                            <td>
                                <div>
                                    <span class="badge badge-primary">Color: {{ $item->attribute->color->name }}</span><br>
                                    <span class="badge badge-success">Size: {{ $item->attribute->size->name }}</span><br>
                                    <span class="badge badge-dark">Purchase: ${{ $item->attribute->purchase_price }}</span><br>
                                    <span class="badge badge-secondary">Selling: ${{ $item->attribute->selling_price }}</span><br>
                                </div>
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
                            <td>{{ $item->quantity }}</td>
                            <td>${{ $item->price }}</td>
                            <td>${{ $item->total_price }}</td>
                        </tr>
                    @endforeach
                @else
                    <span>No Order Item Found</span>
                @endif
            </tbody>
        </table>
        {{ $orderItems->links('pagination::bootstrap-4') }}
       
    </div>

    
   </div>
@endsection