@extends('layouts.app')

@section('title', 'Book Store - My Account')

@section('content')
    <!-- 👤 MY ACCOUNT SECTION -->
    <section class="container py-5">
        <h4 class="section-title">My Account</h4>
        <div class="row">
            <div class="col-md-4 mb-4">
                <div class="card border-0 shadow-sm p-3">
                    <h6 class="fw-bold mb-3">Personal Info</h6>
                    <p class="mb-1"><strong>Name:</strong> Ayush Gandhi</p>
                    <p class="mb-1"><strong>Email:</strong> ayush@example.com</p>
                    <p class="mb-3"><strong>Phone:</strong> +91 98765 43210</p>
                    <button class="btn btn-sm text-white w-100" style="background-color:#ED553B;">Edit Profile</button>
                </div>
            </div>

            <div class="col-md-8">
                <div class="card border-0 shadow-sm p-3">
                    <h6 class="fw-bold mb-3">My Orders</h6>
                    <table class="table table-borderless align-middle mb-0">
                        <thead class="bg-light">
                            <tr>
                                <th>#</th>
                                <th>Book</th>
                                <th>Date</th>
                                <th>Status</th>
                                <th>Total</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>1</td>
                                <td>Code Mastery</td>
                                <td>21 Oct 2025</td>
                                <td><span class="badge bg-success">Delivered</span></td>
                                <td>₹399</td>
                            </tr>
                            <tr>
                                <td>2</td>
                                <td>Think Like a Monk</td>
                                <td>15 Oct 2025</td>
                                <td><span class="badge bg-warning text-dark">Pending</span></td>
                                <td>₹499</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </section>
@endsection
