@extends('layouts.master')

@section('content')
    <div class="row">
       
        <div class="col-md-6">
            <div class="row">
                <div class="col-md-12 mt-5">
                    <h2>Edit Category</h2>
                </div>
            </div>
            <div class="form-section">
                <form method="post" action="{{ route('category.update', $category->id) }}">
                    @csrf
                    @method('put')
                    <div class="form-group">
                      <input type="text" class="form-control" name="name" id="name" value="{{ $category->name }}" required>
                    </div>
                    <button type="submit" class="btn btn-success">Submit</button>
                    <a href="{{ route('category.index') }}" class="btn btn-dark">Return Back</a>
                  </form>
            </div>
        </div>
    </div>
@endsection