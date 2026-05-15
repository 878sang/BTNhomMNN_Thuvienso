<form action="{{ route('books.index') }}" method="GET" id="{{ $formId ?? 'filterForm' }}">
    <!-- Search Section -->
    <div class="filter-section px-0">
        <div class="filter-title px-3">
            <i class="bi bi-search"></i>
            <span>Tìm kiếm</span>
        </div>
        <div class="px-3">
            <div class="premium-search-box d-flex align-items-center">
                <input type="text" name="search" class="form-control border-0 bg-transparent shadow-none px-2 py-1"
                       placeholder="Tên sách, tác giả..." value="{{ request('search') }}">
                <button class="search-submit-btn" type="submit">
                    <i class="bi bi-search"></i>
                </button>
            </div>
        </div>
    </div>

    <!-- Categories Section -->
    <div class="filter-section px-0">
        <div class="filter-title px-3">
            <i class="bi bi-grid-3x3-gap"></i>
            <span>Danh mục</span>
        </div>
        <div class="filter-list px-2">
            <div class="category-item">
                <input type="radio" name="category" value="" id="catAll{{ $suffix ?? '' }}"
                       class="filter-item-radio" {{ !request('category') ? 'checked' : '' }} onchange="this.form.submit()">
                <label for="catAll{{ $suffix ?? '' }}" class="filter-item-label">
                    <span class="d-flex align-items-center">
                        <span class="filter-dot"></span>
                        <span>Tất cả</span>
                    </span>
                    <span class="count-badge">{{ $totalBooksCount }}</span>
                </label>
            </div>
            @foreach($categories as $category)
                <div class="category-item">
                    <input type="radio" name="category" value="{{ $category->slug }}"
                           id="cat{{ $category->id }}{{ $suffix ?? '' }}" class="filter-item-radio"
                           {{ request('category') == $category->slug ? 'checked' : '' }}
                           onchange="this.form.submit()">
                    <label for="cat{{ $category->id }}{{ $suffix ?? '' }}" class="filter-item-label">
                        <span class="d-flex align-items-center">
                            <span class="filter-dot"></span>
                            <span>{{ $category->name }}</span>
                        </span>
                        <span class="count-badge">{{ $category->books_count }}</span>
                    </label>
                </div>
            @endforeach
        </div>
    </div>

    <!-- Format Section -->
    <div class="filter-section px-0">
        <div class="filter-title px-3">
            <i class="bi bi-file-earmark-text"></i>
            <span>Định dạng</span>
        </div>
        <div class="format-pills px-3">
            <div class="format-pill">PDF</div>
            <div class="format-pill">DOCX</div>
            <div class="format-pill">EBOOK</div>
        </div>
    </div>

    <!-- Clear Filters -->
    @if(request('search') || request('category'))
        <div class="p-3">
            <a href="{{ route('books.index') }}" class="btn-clear-filters w-100 d-flex align-items-center justify-content-center gap-2">
                <i class="bi bi-arrow-counterclockwise"></i>
                <span>XÓA BỘ LỌC</span>
            </a>
        </div>
    @endif
</form>
