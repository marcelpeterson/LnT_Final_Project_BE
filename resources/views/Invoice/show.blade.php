@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="card">
                <div class="card-header">
                    <h4>Invoice: {{ $invoice->number }}</h4>
                </div>

                <div class="card-body">
                    @if (session('success'))
                        <div class="alert alert-success" role="alert">
                            {{ session('success') }}
                        </div>
                    @endif

                    <div class="row mb-4">
                        <div class="col-md-6">
                            <h5>Billing Information</h5>
                            <p><strong>Customer:</strong> {{ $invoice->user->name }}</p>
                            <p><strong>Email:</strong> {{ $invoice->user->email }}</p>
                            <p><strong>Address:</strong> {{ $invoice->address }}</p>
                            <p><strong>Postcode:</strong> {{ $invoice->postcode }}</p>
                        </div>
                        <div class="col-md-6 text-md-right">
                            <h5>Invoice Details</h5>
                            <p><strong>Invoice Number:</strong> {{ $invoice->number }}</p>
                            <p><strong>Date:</strong> {{ $invoice->created_at->format('F d, Y') }}</p>
                            <p><strong>Time:</strong> {{ $invoice->created_at->format('h:i A') }}</p>
                        </div>
                    </div>

                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>Item</th>
                                    <th>Price</th>
                                    <th>Quantity</th>
                                    <th class="text-right">Subtotal</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($invoice->items as $item)
                                <tr>
                                    <td>{{ $item->name }}</td>
                                    <td>Rp {{ number_format($item->price, 0, ',', '.') }}</td>
                                    <td>{{ $item->quantity }}</td>
                                    <td class="text-right">Rp {{ number_format($item->price * $item->quantity, 0, ',', '.') }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                            <tfoot>
                                <tr>
                                    <th colspan="3" class="text-right">Total</th>
                                    <th class="text-right">Rp {{ number_format($invoice->total_amount, 0, ',', '.') }}</th>
                                </tr>
                            </tfoot>
                        </table>
                    </div>

                    <div class="mt-4">
                        <a href="{{ route('invoice.index') }}" class="btn btn-secondary">Back to Invoices</a>
                        <a href="javascript:window.print()" class="btn btn-primary ml-2">Print Invoice</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
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