@extends('layouts.master')

@section('content')
    <div class="row">
       
        <div class="col-md-6">
            <div class="row">
                <div class="col-md-12 mt-5">
                    <h2>Edit Color</h2>
                </div>
            </div>
            <div class="form-section">
                <form method="post" action="{{ route('color.update', $color->id) }}">
                    @csrf
                    @method('put')
                    <div class="form-group">
                      <input type="text" class="form-control" name="name" id="name" value="{{ $color->name }}" required>
                    </div>
                    <button type="submit" class="btn btn-success">Submit</button>
                    <a href="{{ route('color.index') }}" class="btn btn-dark">Return Back</a>
                  </form>
            </div>
        </div>
    </div>
@endsection