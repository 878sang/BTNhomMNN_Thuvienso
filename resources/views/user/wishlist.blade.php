@extends('layouts.app')

@section('title', 'Tài liệu yêu thích')

@section('content')
<section class="container py-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4 class="fw-bold"><i class="bi bi-heart-fill text-danger me-2"></i>Tài liệu yêu thích</h4>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0">
                <li class="breadcrumb-item"><a href="{{ route('home') }}">Trang chủ</a></li>
                <li class="breadcrumb-item active">Yêu thích</li>
            </ol>
        </nav>
    </div>

    <div class="row row-cols-1 row-cols-sm-2 row-cols-md-4 g-4">
        @forelse($favorites as $favorite)
        @php $book = $favorite->book; @endphp
        <div class="col">
            <div class="card h-100 border-0 shadow-sm transition-hover">
                <a href="{{ route('books.show', $book->slug) }}" class="text-decoration-none">
                    <img src="{{ asset('storage/' . $book->thumbnail) }}" class="card-img-top p-2 rounded" alt="{{ $book->title }}" style="height: 250px; object-fit: cover;">
                </a>
                <div class="card-body">
                    <h6 class="fw-bold mb-1 text-truncate">
                        <a href="{{ route('books.show', $book->slug) }}" class="text-dark text-decoration-none">{{ $book->title }}</a>
                    </h6>
                    <p class="text-muted small mb-2">{{ $book->author->name }}</p>
                    <div class="d-flex justify-content-between align-items-center">
                        <span class="text-danger fw-bold">{{ number_format($book->price_points) }} điểm</span>
                        <form action="{{ route('user.books.favorite', $book) }}" method="POST">
                            @csrf
                            <button type="submit" class="btn btn-sm btn-outline-danger border-0">
                                <i class="bi bi-heart-fill"></i>
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        @empty
        <div class="col-12 text-center py-5">
            <div class="mb-4">
                <i class="bi bi-heart text-muted" style="font-size: 5rem;"></i>
            </div>
            <h5>Bạn chưa có tài liệu yêu thích nào.</h5>
            <p class="text-muted">Hãy khám phá thư viện và lưu lại những tài liệu hữu ích nhé!</p>
            <a href="{{ route('books.index') }}" class="btn btn-primary px-4 mt-3">Khám phá ngay</a>
        </div>
        @endforelse
    </div>

    <div class="mt-5">
        {{ $favorites->links() }}
    </div>
</section>
@endsection
