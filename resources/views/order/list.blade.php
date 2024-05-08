@extends('layouts.master')

@section('content')
   <div class="row">
   
    <div class="col-md-12">
        <div class="row">
            <div class="col-md-12">
                <h2>Orders Table</h2>
            </div>
        </div>
        <table class="table">
            <thead class="thead-dark">
                <tr>
                    <th scope="col">#</th>
                    <th scope="col">Order ID</th>
                    <th scope="col">Total Cost</th>
                    <th scope="col">Items</th>
                    <th scope="col">Purchase Date</th>
                    <th scope="col">Action</th>
                </tr>
            </thead>
            <tbody>
                @if ($orders)
                    @foreach ($orders as $key => $item)
                        <tr>
                            <td>{{ $key+1 }}</td>
                            <td>{{ $item->id }}</td>
                            <td>${{ number_format($item->total_cost, 2) }}</td>
                            <td>{{ $item->items()->count() }}</td>
                            <td>{{ $item->created_at->format('d M, Y | H:ia') }}</td>
                            <td>
                                <a href="{{ route('order.list.items', ['order' => $item->id]) }}" class="btn btn-sm btn-warning">
                                    View Items
                                </a>
                               
                            </td>
                        </tr>
                    @endforeach
                @else
                    <span>No Order Found</span>
                @endif
            </tbody>
        </table>
        <div class="d-flex justify-content-center">
            {{ $orders->links('pagination::bootstrap-4') }}
        </div>
       
    </div>

    
   </div>
@endsection