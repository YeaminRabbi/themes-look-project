@extends('layouts.master')

@section('content')
    <div class="row">
        <div class="col-md-6">
            <div class="form-section mt-5">
                <form method="post" action="{{ route('category.store') }}">
                    @csrf
                    <div class="form-group">
                      <label for="name">Category Name</label>
                      <input type="text" class="form-control" name="name" id="name" required>
                    </div>
                    <button type="submit" class="btn btn-success">Submit</button>
                    <a href="{{ route('category.index') }}" class="btn btn-dark">Return Back</a>
                  </form>
            </div>
        </div>
    </div>
@endsection