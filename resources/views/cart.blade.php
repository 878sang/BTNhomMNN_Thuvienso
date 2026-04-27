@extends('layouts.app')

@section('title', 'Book Store - My Cart')

@section('content')
    <!-- 🛒 CART SECTION -->
    <section class="container py-5">
        <h4 class="section-title">My Cart</h4>
        <div class="row g-4">
            <div class="col-lg-8">
                <div class="card border-0 shadow-sm mb-3 p-3 d-flex flex-row align-items-center">
                    <img src="https://images.unsplash.com/photo-1544716278-ca5e3f4abd8c?w=400" class="rounded me-3"
                        width="80" alt="Code Mastery">
                    <div class="flex-grow-1">
                        <h6 class="fw-bold mb-1">Code Mastery</h6>
                        <p class="text-muted small mb-1">ByteBooks</p>
                        <p class="fw-semibold text-danger mb-0">₹399</p>
                    </div>
                    <div>
                        <input type="number" class="form-control form-control-sm mb-2" value="1" min="1"
                            style="width: 70px;">
                        <button class="btn btn-sm btn-outline-danger">Remove</button>
                    </div>
                </div>
                <div class="card border-0 shadow-sm mb-3 p-3 d-flex flex-row align-items-center">
                    <img src="https://images.unsplash.com/photo-1524995997946-a1c2e315a42f?w=400" class="rounded me-3"
                        width="80" alt="Creative Writing">
                    <div class="flex-grow-1">
                        <h6 class="fw-bold mb-1">Creative Writing</h6>
                        <p class="text-muted small mb-1">WordPress Press</p>
                        <p class="fw-semibold text-danger mb-0">₹299</p>
                    </div>
                    <div>
                        <input type="number" class="form-control form-control-sm mb-2" value="2" min="1"
                            style="width: 70px;">
                        <button class="btn btn-sm btn-outline-danger">Remove</button>
                    </div>
                </div>
            </div>

            <div class="col-lg-4">
                <div class="card border-0 shadow-sm p-3">
                    <h6 class="fw-bold mb-3">Order Summary</h6>
                    <div class="d-flex justify-content-between mb-2">
                        <span>Subtotal</span><span>₹997</span>
                    </div>
                    <div class="d-flex justify-content-between mb-2">
                        <span>Shipping</span><span>₹50</span>
                    </div>
                    <hr>
                    <div class="d-flex justify-content-between fw-bold mb-3">
                        <span>Total</span><span>₹1047</span>
                    </div>
                    <button class="btn text-white w-100 fw-semibold" style="background-color:#ED553B;">Proceed to Checkout</button>
                </div>
            </div>
        </div>
    </section>
@endsection
