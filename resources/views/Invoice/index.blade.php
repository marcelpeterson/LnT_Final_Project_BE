@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="card">
                <div class="card-header">Your Invoices</div>

                <div class="card-body">
                    @if (session('success'))
                        <div class="alert alert-success" role="alert">
                            {{ session('success') }}
                        </div>
                    @endif

                    @if(count($invoices) > 0)
                        <div class="table-responsive">
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th>Invoice Number</th>
                                        <th>Date</th>
                                        <th>Total Amount</th>
                                        <th>Address</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($invoices as $invoice)
                                    <tr>
                                        <td>{{ $invoice->number }}</td>
                                        <td>{{ $invoice->created_at->format('Y-m-d') }}</td>
                                        <td>Rp {{ number_format($invoice->total_amount, 0, ',', '.') }}</td>
                                        <td>{{ $invoice->address }}, {{ $invoice->postcode }}</td>
                                        <td>
                                            <a href="{{ route('invoice.show', $invoice->id) }}" class="btn btn-sm btn-primary">View Details</a>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="alert alert-info">
                            You don't have any invoices yet.
                        </div>
                    @endif
                    
                    <div class="mt-3">
                        <a href="{{ route('home') }}" class="btn btn-secondary">Back to Shop</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection