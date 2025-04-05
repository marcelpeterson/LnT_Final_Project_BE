@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-10">
            {{-- @if (session('success'))
                <div class="alert alert-success" role="alert">
                    {{ session('success') }}
                </div>
            @endif

            @if (session('error'))
                <div class="alert alert-danger" role="alert">
                    {{ session('error') }}
                </div>
            @endif --}}

            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h4>Shop Items</h4>
                    @auth
                        @if (Auth::user()->role == 'admin')
                            <a href="/create-item" class="btn btn-primary">Add Item</a>
                        @else
                            <a href="{{ route('cart.view') }}" class="btn btn-info">
                                <i class="fas fa-shopping-cart"></i> View Cart
                            </a>
                        @endif
                    @endauth
                </div>
                <div class="card-body">
                    @if(count($items) > 0)
                        <div class="row">
                            @foreach ($items as $item)
                                <div class="col-md-3 mb-4">
                                    <div class="card h-100">
                                        <img src="{{ asset('storage/item_images/' . $item->photo) }}" class="card-img-top" alt="{{ $item->name }}" style="height: 180px; object-fit: cover;">
                                        <div class="card-body">
                                            <h5 class="card-title">{{ $item->name }}</h5>
                                            <p class="card-text">Rp {{ number_format($item->price, 0, ',', '.') }}</p>
                                            <p class="card-text">
                                                <small class="text-muted">
                                                    <strong>Category:</strong> {{ $item->category->name }}<br>
                                                    <strong>Quantity:</strong> {{ $item->quantity }}
                                                </small>
                                            </p>
                                        </div>
                                        <div class="card-footer bg-transparent">
                                            @auth
                                                @if (Auth::user()->role == 'admin')
                                                    <div class="d-flex">
                                                        <a href="/edit-item/{{ $item->id }}" class="btn btn-sm btn-primary px-3 me-2">Edit</a>
                                                        <button type="button" class="btn btn-sm btn-danger px-3" data-bs-toggle="modal" data-bs-target="#exampleModal" data-id="{{ $item->id }}">Delete</button>
                                                    </div>
                                                @else
                                                    <form action="/add-to-cart/{{ $item->id }}" method="POST" class="d-grid">
                                                        @csrf
                                                        <div class="mb-2">
                                                            <label for="quantity-{{ $item->id }}" class="form-label">Quantity</label>
                                                            <input type="number" name="quantity" id="quantity-{{ $item->id }}" class="form-control" value="1" min="1" max="{{ $item->quantity }}" required>
                                                        </div>
                                                        <button type="submit" class="btn btn-primary">Add to Cart</button>
                                                    </form>
                                                @endif
                                            @endauth
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="alert alert-info">
                            No items available at the moment.
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal -->
<div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Delete Confirmation</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                Are you sure you want to delete this item?
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <form id="deleteForm" method="DELETE" action="">
                    @csrf
                    <button type="submit" class="btn btn-danger">Confirm</button>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
const exampleModal = document.getElementById('exampleModal');
exampleModal.addEventListener('show.bs.modal', function (event) {
    const button = event.relatedTarget;
    const itemId = button.getAttribute('data-id');
    const deleteForm = document.getElementById('deleteForm');
    deleteForm.action = `/delete-item/${itemId}`;
});
</script>
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