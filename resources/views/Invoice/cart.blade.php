@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="card">
                <div class="card-header">Your Shopping Cart</div>

                <div class="card-body">
                    @if (session('success'))
                        <div class="alert alert-success" role="alert">
                            {{ session('success') }}
                        </div>
                    @endif

                    @if (session('error'))
                        <div class="alert alert-danger" role="alert">
                            {{ session('error') }}
                        </div>
                    @endif

                    @if(count($cartItems) > 0)
                        <div class="table-responsive">
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th>Item</th>
                                        <th>Price</th>
                                        <th>Quantity</th>
                                        <th>Subtotal</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php $total = 0; @endphp
                                    @foreach($cartItems as $cartItem)
                                    <tr>
                                        <td>{{ $cartItem->item->name }}</td>
                                        <td>Rp {{ number_format($cartItem->item->price, 0, ',', '.') }}</td>
                                        <td>
                                            <form action="{{ route('cart.update', $cartItem->id) }}" method="POST" class="form-inline">
                                                @csrf
                                                @method('PUT')
                                                <input type="number" name="quantity" value="{{ $cartItem->quantity }}" min="1" max="{{ $cartItem->item->quantity }}" class="form-control form-control-sm" style="width: 60px;">
                                                <button type="submit" class="btn btn-sm btn-info ml-2">Update</button>
                                            </form>
                                        </td>
                                        <td>Rp {{ number_format($cartItem->item->price * $cartItem->quantity, 0, ',', '.') }}</td>
                                        <td>
                                            <form action="{{ route('cart.remove', $cartItem->id) }}" method="POST">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-danger">Remove</button>
                                            </form>
                                        </td>
                                    </tr>
                                    @php $total += $cartItem->item->price * $cartItem->quantity; @endphp
                                    @endforeach
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <th colspan="3" class="text-right">Total</th>
                                        <th>Rp {{ number_format($total, 0, ',', '.') }}</th>
                                        <th></th>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>

                        <div class="mt-4">
                            <form action="{{ route('checkout') }}" method="POST" class="checkout-form">
                                @csrf
                                <div class="form-group mb-3">
                                    <label for="address">Shipping Address</label>
                                    <textarea name="address" id="address" class="form-control" required rows="2"></textarea>
                                    @error('address')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="form-group mb-3">
                                    <label for="postcode">Postal Code</label>
                                    <input type="text" name="postcode" id="postcode" class="form-control" required>
                                    @error('postcode')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                                <button type="submit" class="btn btn-primary">Checkout</button>
                            </form>
                        </div>
                    @else
                        <div class="alert alert-info">
                            Your cart is empty. <a href="{{ route('home') }}">Continue shopping</a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection