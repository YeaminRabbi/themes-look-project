@extends('layouts.master')

@section('content')
   <div class="row">
   
    <div class="col-md-8">
        <div class="row">
            <div class="col-md-12">
                <h2>Color Table</h2>
            </div>
        </div>
        <table class="table">
            <thead class="thead-dark">
                <tr>
                    <th scope="col">#</th>
                    <th scope="col">Name</th>
                    <th scope="col">Code</th>
                    <th scope="col">Action</th>
                </tr>
            </thead>
            <tbody>
                @if ($colors)
                    @foreach ($colors as $key => $item)
                        <tr>
                            <th scope="row">{{ $key + 1 }}</th>
                            <td>{{ $item->name }}</td>
                            <td>{{ $item->slug }}</td>
                            <td>
                                <a href="{{ route('color.edit', $item->id) }}" class="btn btn-sm btn-warning">
                                    Edit
                                </a>
                                <form action="{{ route('color.destroy', $item->id) }}"
                                    style="display: inline;" method="POST">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit"
                                        class="btn btn-danger shadow btn-sm sharp confirm-trash-button">Delete</button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                @else
                    <span>No Color Found</span>
                @endif
            </tbody>
        </table>
    
       
    </div>

    <div class="col-md-4">
        <div class="row">
            <div class="col-md-12">
                <h2>Add Color</h2>
            </div>
        </div>
        <div class="form-section">
            <form method="post" action="{{ route('color.store') }}">
                @csrf
                <div class="form-group">
                  <input type="text" class="form-control" name="name" id="name" required>
                </div>
                <div class="d-flex justify-content-start">
                    <button type="submit" class="btn btn-success">Submit</button>
                </div>
              </form>
        </div>
    </div>
   </div>
@endsection