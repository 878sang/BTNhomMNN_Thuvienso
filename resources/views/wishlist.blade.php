@extends('layouts.app')

@section('title', 'Book Store - My Wishlist')

@section('content')
    <!-- ❤️ WISHLIST SECTION -->
    <section class="container py-5">
        <h4 class="section-title">My Wishlist</h4>
        <div class="row row-cols-1 row-cols-sm-2 row-cols-md-3 g-4">
            <div class="col">
                <div class="book-card card border-0 shadow-sm p-2">
                    <img src="https://images.unsplash.com/photo-1553729459-efe14ef6055d?w=400" class="card-img-top"
                        alt="Think Like a Monk">
                    <div class="card-body">
                        <h6 class="fw-bold mb-1">Think Like a Monk</h6>
                        <p class="text-muted mb-1">Jay Shetty</p>
                        <p class="fw-semibold text-danger mb-2">₹499</p>
                        <div class="d-flex justify-content-between">
                            <button class="btn btn-sm text-white" style="background-color:#ED553B;">Add to Cart</button>
                            <button class="btn btn-sm btn-outline-danger">Remove</button>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col">
                <div class="book-card card border-0 shadow-sm p-2">
                    <img src="https://images.unsplash.com/photo-1512820790803-83ca734da794?w=400" class="card-img-top"
                        alt="Atomic Habits">
                    <div class="card-body">
                        <h6 class="fw-bold mb-1">Atomic Habits</h6>
                        <p class="text-muted mb-1">James Clear</p>
                        <p class="fw-semibold text-danger mb-2">₹549</p>
                        <div class="d-flex justify-content-between">
                            <button class="btn btn-sm text-white" style="background-color:#ED553B;">Add to Cart</button>
                            <button class="btn btn-sm btn-outline-danger">Remove</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
