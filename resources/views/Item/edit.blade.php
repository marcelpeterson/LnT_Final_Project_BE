@extends('layouts.app')

@section('content')
<form class="container mt-3" action="/edit-item-post/{{ $item->id }}" method="POST" enctype="multipart/form-data">
    @csrf
    <div class="mb-3">
        <label for="category" class="form-label">Category</label>
        <select class="form-select" id="category" name="category_id">
            <option selected>Select Category</option>
            @foreach ($categories as $category)
                <option value="{{ $category->id }}" {{ $item->category_id == $category->id ? 'selected' : '' }}>{{ $category->name }}</option>
            @endforeach
        </select>
        @error('category')
            <div class="text-danger">{{ $message }}</div>
        @enderror
    </div>
    
    <div class="mb-3">
        <label for="name" class="form-label">Name</label>
        <input type="text" class="form-control" id="name" name="name" value="{{ $item->name }}">
        @error('name')
            <div class="text-danger">{{ $message }}</div>
        @enderror
    </div>

    <div class="mb-3">
        <label for="price" class="form-label">Price</label>
        <input type="number" class="form-control" id="price" name="price" value="{{ $item->price }}">
        @error('price')
            <div class="text-danger">{{ $message }}</div>
        @enderror
    </div>

    <div class="mb-3">
        <label for="quantity" class="form-label">Quantity</label>
        <input type="number" class="form-control" id="quantity" name="quantity" value="{{ $item->quantity }}">
        @error('quantity')
            <div class="text-danger">{{ $message }}</div>
        @enderror
    </div>

    <div class="mb-3">
        <label for="photo" class="form-label">Photo</label>
        <input type="file" class="form-control" id="photo" name="photo">
        @if ($item->photo)
            <img src="{{ asset('storage/item_images/' . $item->photo) }}" alt="Current Photo" class="img-thumbnail mt-2" style="width: 18rem;">
        @endif
        @error('photo')
            <div class="text-danger">{{ $message }}</div>
        @enderror
    </div>

    <button type="submit" class="btn btn-primary mt-3 mb-5 px-3">Update Item</button>
</form>
@endsection

@section('styles')
<style>
    @media print {
        .btn, nav, footer {
            display: none !important;
        }
        
        .card {
            border: none !important;
        }
        
        .card-header {
            background-color: white !important;
            border-bottom: 1px solid #000 !important;
        }
    }
</style>
@endsection