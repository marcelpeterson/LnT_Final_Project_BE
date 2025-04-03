<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <title>Home Page</title>
</head>

@include('layouts.navbar')

<body>
    @auth
        @if (Auth::user()->role == 'admin')
            <div class="container mt-5">
                <a href="/add-item" class="btn btn-primary">Add Item</a>
            </div>
        @endif
    @endauth
    <div class="container mt-5">
        <div class="row">
            @foreach ($items as $item)
                <div class="col-md-3">
                    <div class="card mb-4" style="width: 18rem;">
                        <img src="{{ asset('storage/item_images/' . $item->photo) }}" class="card-img-top" alt="{{ $item->name }}">
                        <div class="card-body">
                          <h5 class="card-title">{{ $item->name }}</h5>
                          <p class="card-text">Rp{{ number_format($item->price, 0, ',', '.') }}</p>
                        </div>
                        <ul class="list-group list-group-flush">
                          <li class="list-group-item">Quantity: {{ $item->quantity }}</li>
                          <li class="list-group-item">Category: {{ $item->category->name }}</li>
                        </ul>
                        <div class="card-body">
                            @auth
                                @if (Auth::user()->role == 'admin')
                                    <a href="/edit-item/{{ $item->id }}" class="btn btn-primary me-1">Edit</a>
                                    <button type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#exampleModal" data-id="{{ $item->id }}">Delete</button>
                                @else
                                    <a href="/add-to-cart/{{ $item->id }}" class="btn btn-primary">Add to Cart</a>
                                @endif
                            @endauth
                        </div>
                      </div>
                </div>
            @endforeach
        </div>
    </div>

    <!-- Modal -->
    <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="exampleModalLabel">Delete Confirmation</h1>
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
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</body>
</html>