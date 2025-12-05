@extends('user.app')
@section('title', 'Favorites')

@section('content')
    <div class="p-4">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h4 class="fw-semibold mb-1">Favorites</h4>
                <p class="text-muted mb-0">Your saved favorite jewelry designs</p>
            </div>
        </div>

        <div class="card mb-4">
            <form id="favoritesFilterForm" method="GET" action="{{ route('favorites') }}"
                class="card-body d-flex flex-wrap align-items-center gap-3">
                <div class="flex-grow-1">
                    <input type="text" name="search" id="searchFavorites" class="form-control"
                        placeholder="Search favorites by name, metal or type..." value="{{ request('search') }}">
                </div>
                <select name="type" id="filterType" class="form-select w-auto">
                    <option value="">All Types</option>
                    <option value="ring" {{ request('type') == 'ring' ? 'selected' : '' }}>Ring</option>
                    <option value="necklace" {{ request('type') == 'necklace' ? 'selected' : '' }}>Necklace</option>
                    <option value="bangle" {{ request('type') == 'bangle' ? 'selected' : '' }}>Bangle</option>
                    <option value="earring" {{ request('type') == 'earring' ? 'selected' : '' }}>Earring</option>
                </select>
                <select name="sort" id="sortBy" class="form-select w-auto">
                    <option value="latest" {{ request('sort') == 'latest' ? 'selected' : '' }}>Latest</option>
                    <option value="oldest" {{ request('sort') == 'oldest' ? 'selected' : '' }}>Oldest</option>
                </select>
            </form>
        </div>

        <div class="row g-4" id="favoritesGrid">
            @forelse ($favorites as $catalog)
                <div class="col-xl-3 col-lg-4 col-md-6">
                    <div class="card h-100 shadow-sm border-0">
                        <div class="position-relative">
                            <img src="{{ asset('/assets/upload/catlog_studion_image/ChatGPT%20Image%20Nov%208,%202025,%2012_20_23%20PM.png') }}"
                                class="card-img-top" alt="Image">
                            <button class="btn-favorite active" data-catalog-id="{{ $catalog->id }}">
                                <i class="fas fa-star"></i>
                            </button>
                        </div>

                        <div class="card-body">
                            <h6 class="fw-semibold mb-1 text-capitalize">
                                {{ ucfirst($catalog->jewelry_type) ?? 'Untitled Design' }}
                            </h6>
                            <p class="text-muted small mb-2">Metal: {{ $catalog->metal_type ?? '-' }}</p>

                            <div class="d-flex justify-content-between align-items-center">
                                <span class="badge bg-light">
                                    {{ $catalog->created_at->format('d/m/Y') }}
                                </span>
                                <button class="btn btn-sm btn-gradient openModal" data-id="{{ $catalog->id }}"
                                    data-name="{{ $catalog->product_name }}"
                                    data-type="{{ ucfirst($catalog->jewelry_type) }}"
                                    data-metal="{{ $catalog->metal_type }}"
                                    data-desc="{{ $catalog->design_desc ?: 'No description available' }}"
                                    data-date="{{ $catalog->created_at->format('m/d/Y') }}"
                                    data-photo="{{ $catalog->photo_count ?? 3 }}">
                                    View
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-12">
                    <div class="empty-wrapper text-center py-5 px-3">
                        <div class="empty-animation mx-auto mb-4">
                            <i class="fas fa-heart"></i>
                        </div>
                        <h3 class="fw-bold mb-2">No Favorites Yet</h3>
                        <p class="text-muted mb-4">Save your favorite jewelry designs to quickly access them later.</p>
                        <a href="{{ route('catalog.library') }}" class="btn btn-gradient px-4 py-2">
                            Browse Catalog Designs
                        </a>
                    </div>
                </div>
            @endforelse
        </div>

        @if ($favorites->hasPages())
            <div class="d-flex justify-content-center mt-4">
                {{ $favorites->links('pagination::bootstrap-5') }}
            </div>
        @endif
    </div>

    <div class="modal fade" id="catalogModal" tabindex="-1">
        <div class="modal-dialog modal-xl modal-dialog-centered">
            <div class="modal-content p-4">
                <div class="modal-body">
                    <div class="row g-4">
                        <div class="col-md-6 text-center border-end">
                            <img id="modalMainImage" src="" class="img-fluid rounded mb-3 shadow-sm">
                            <h5 id="modalProductName"></h5>
                            <p id="modalDesc" class="small text-muted"></p>
                            <p id="modalDate" class="small text-muted"></p>
                        </div>
                        <div class="col-md-6">
                            <h6 class="fw-semibold mb-3">Designs</h6>
                            <div id="modalDesigns" class="d-flex flex-wrap gap-3 mb-4"></div>
                            <div class="d-flex justify-content-end gap-3">
                                <button id="exportAll" class="btn btn-outline-secondary">Export</button>
                                <button class="btn btn-gradient" data-bs-dismiss="modal">Close</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/FileSaver.js/2.0.5/FileSaver.min.js"></script>

    <script>
        $('#filterType,#sortBy').on('change', function() {
            $('#favoritesFilterForm').submit();
        });
        let t;
        $('#searchFavorites').on('input', function() {
            clearTimeout(t);
            t = setTimeout(() => $('#favoritesFilterForm').submit(), 600);
        });

        $(document).on('click', '.openModal', function() {
            const btn = $(this);
            const img =
                "{{ asset('/assets/upload/catlog_studion_image/ChatGPT%20Image%20Nov%208,%202025,%2012_20_23%20PM.png') }}";
            $('#modalMainImage').attr('src', img);
            $('#modalProductName').text(btn.data('name'));
            $('#modalDesc').text(btn.data('desc'));
            $('#modalDate').text(btn.data('date'));
            $('#modalDesigns').html(`<img src="${img}" class="img-thumbnail">`);
            $('#catalogModal').modal('show');
        });

        $(document).on('click', '.btn-favorite', function(e) {
            e.preventDefault();
            const btn = $(this);
            $.post('{{ route('toggle.favorite') }}', {
                _token: '{{ csrf_token() }}',
                catalog_id: btn.data('catalog-id')
            }, function(res) {
                if (!res.is_favorite) {
                    btn.closest('.col-xl-3').fadeOut();
                }
            });
        });
    </script>

    <style>
        .empty-animation {
            width: 120px;
            height: 120px;
            border-radius: 50%;
            background: linear-gradient(135deg, #ff9a9e, #fad0c4);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 50px;
            color: #fff;
            animation: pulse 2s infinite
        }

        @keyframes pulse {
            0% {
                transform: scale(1)
            }

            50% {
                transform: scale(1.1)
            }

            100% {
                transform: scale(1)
            }
        }

        .btn-gradient {
            background: linear-gradient(135deg, #667eea, #764ba2);
            color: #fff;
            border: none
        }
    </style>
@endsection
