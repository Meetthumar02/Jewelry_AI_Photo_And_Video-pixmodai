@extends('user.app')
@section('title', 'Catalog Library')

@section('content')
    <div class="p-4">

        <!-- HEADER -->
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h4 class="fw-semibold mb-1">Catalog Library</h4>
                <p class="text-muted mb-0">Manage and explore your jewelry catalog designs</p>
            </div>
            <a href="{{ route('catalog.studio') }}">
                <button class="btn btn-gradient px-4 py-2 shadow-sm">
                    <i class="fas fa-plus me-2"></i> Upload New Catalog
                </button>
            </a>
        </div>

        <!-- SEARCH & FILTER -->
        <div class="card mb-4">
            <form id="catalogFilterForm" method="GET" action="{{ route('catalog.library') }}"
                class="card-body d-flex flex-wrap align-items-center gap-3">

                <input type="text" name="search" id="searchCatalog" class="form-control flex-grow-1"
                    placeholder="Search..." value="{{ request('search') }}">

                <select name="type" id="filterType" class="form-select w-auto">
                    <option value="">All Types</option>
                    <option value="ring" {{ request('type') == 'ring' ? 'selected' : '' }}>Ring</option>
                    <option value="necklace" {{ request('type') == 'necklace' ? 'selected' : '' }}>Necklace</option>
                    <option value="bangle" {{ request('type') == 'bangle' ? 'selected' : '' }}>Bangle</option>
                    <option value="earring" {{ request('type') == 'earring' ? 'selected' : '' }}>Earring</option>
                </select>

                <select name="sort" id="sortBy" class="form-select w-auto">
                    <option value="latest">Latest</option>
                    <option value="oldest" {{ request('sort') == 'oldest' ? 'selected' : '' }}>Oldest</option>
                </select>
            </form>
        </div>

        <!-- GRID -->
        <div class="row g-4" id="catalogGrid">
            @forelse ($catalogs as $catalog)
                @php
                    $mainImage = optional($catalog->photos->first())->image_path ?? 'no-image.png';
                @endphp


                <div class="col-xl-3 col-lg-4 col-md-6">
                    <div class="card h-100 shadow-sm">
                        <img src="{{ asset('storage/' . $mainImage) }}" class="card-img-top">

                        <div class="card-body">
                            <div class="d-flex justify-content-between mb-2">
                                <h6>{{ ucfirst($catalog->jewelry_type) }}</h6>
                                <button class="btn-favorite {{ in_array($catalog->id, $favoriteIds) ? 'active' : '' }}"
                                    data-catalog-id="{{ $catalog->id }}">
                                    <i class="fas fa-star"></i>
                                </button>
                            </div>

                            <p class="small text-muted">Metal: {{ $catalog->metal_type }}</p>

                            <button class="btn btn-sm btn-gradient openModal w-100"
                                data-name="{{ $catalog->product_name }}" data-desc="{{ $catalog->design_desc }}"
                                data-date="{{ $catalog->created_at->format('m/d/Y') }}"
                                data-images='@json($catalog->photos->pluck('image_path'))'>
                                View Designs
                            </button>
                        </div>
                    </div>
                </div>
            @empty
                <p class="text-center text-muted">No designs found</p>
            @endforelse
        </div>

        {!! $catalogs->links() !!}
    </div>

    <!-- MODAL -->
    <div class="modal fade" id="catalogModal" tabindex="-1">
        <div class="modal-dialog modal-xl modal-dialog-centered">
            <div class="modal-content p-4">
                <div class="row">
                    <div class="col-md-6 text-center">
                        <img id="modalMainImage" class="img-fluid rounded mb-2">
                        <h5 id="modalProductName"></h5>
                        <p id="modalDesc" class="text-muted"></p>
                        <p id="modalDate" class="text-muted small"></p>
                    </div>

                    <div class="col-md-6">
                        <div id="modalDesigns" class="d-flex flex-wrap gap-3"></div>
                        <div class="mt-4 text-end">
                            <button id="exportAll" class="btn btn-outline-secondary">Export</button>
                            <button class="btn btn-gradient" data-bs-dismiss="modal">Close</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- JS -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/FileSaver.js/2.0.5/FileSaver.min.js"></script>

    <script>
        $('#filterType,#sortBy').change(function() {
            $('#catalogFilterForm').submit();
        });

        $(document).on('click', '.openModal', function() {
            const images = $(this).data('images');
            $('#modalDesigns').empty();
            $('#modalProductName').text($(this).data('name'));
            $('#modalDesc').text($(this).data('desc'));
            $('#modalDate').html(`<strong>Created:</strong> ${$(this).data('date')}`);

            const first = '/storage/' + images[0];
            $('#modalMainImage').attr('src', first).attr('data-active', first);

            images.forEach((img, i) => {
                $('#modalDesigns').append(`
            <img src="/storage/${img}"
                 data-full="/storage/${img}"
                 class="img-thumbnail thumb-img"
                 style="width:120px;height:120px;cursor:pointer;">
        `);
            });

            $('#catalogModal').modal('show');
        });

        $(document).on('click', '.thumb-img', function() {
            $('#modalMainImage').attr('src', $(this).data('full'))
                .attr('data-active', $(this).data('full'));
        });

        $('#exportAll').click(async function() {
            const zip = new JSZip();
            $('.thumb-img').each(async function(i) {
                const res = await fetch($(this).data('full'));
                const blob = await res.blob();
                zip.file(`design_${i+1}.jpg`, blob);
            });
            const content = await zip.generateAsync({
                type: "blob"
            });
            saveAs(content, "catalog.zip");
        });

        $(document).on('click', '.btn-favorite', function() {
            const btn = $(this);
            $.post('{{ route('toggle.favorite') }}', {
                catalog_id: btn.data('catalog-id'),
                _token: '{{ csrf_token() }}'
            }, res => {
                btn.toggleClass('active', res.is_favorite);
            });
        });
    </script>
@endsection
