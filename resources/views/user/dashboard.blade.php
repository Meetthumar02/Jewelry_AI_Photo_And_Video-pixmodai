@extends('user.app')

@section('title', 'Dashboard - Nimora Studio')
@section('content')
    <div class="dashboard-container">
        <!-- Enhanced Header -->
        <div class="dashboard-header mb-5">
            <div class="header-content">
                <h1 class="display-5 fw-bold text-dark mb-2">Dashboard</h1>
                <p class="lead text-muted mb-0">Welcome back! Here's what's happening with your designs.</p>
            </div>
            <div class="header-decoration">
                <div class="decoration-circle circle-1"></div>
                <div class="decoration-circle circle-2"></div>
                <div class="decoration-circle circle-3"></div>
            </div>
        </div>

        <div class="row g-4 mb-5">
            <!-- Left Column -->
            <div class="col-lg-12">
                <!-- Enhanced Stats Cards -->
                <div class="row g-4" style="width: 50rem">
                    <div class="col-md-6">
                        <a href="{{ route('favorites') }}" class="text-decoration-none">
                            <div class="card stats-card h-100 border-0 position-relative overflow-hidden"
                                style="border-radius: 1.5rem;">
                                <div class="card-glow"></div>
                                <div class="card-shape position-absolute top-0 end-0 w-100 h-100">
                                    <div class="shape-1"></div>
                                    <div class="shape-2"></div>
                                </div>
                                <div
                                    class="card-body d-flex justify-content-between align-items-center p-4 position-relative">
                                    <div class="text-white">
                                        <div class="small opacity-85 mb-2">Favorite Designs</div>
                                        <div class="display-4 fw-bold mt-1">{{ $favoriteCount }}</div>
                                        <div class="small opacity-85 mt-2">Your cherished creations</div>
                                    </div>
                                    <div class="stats-icon" style="color: #ffffff">
                                        <div class="icon-wrapper">
                                            <i class="fas fa-star fa-2x"></i>
                                        </div>
                                    </div>
                                </div>
                                <div class="card-footer border-0 bg-transparent pt-0">
                                    <div class="small text-white opacity-85">
                                        <i class="fas fa-arrow-right me-1"></i> View all favorites
                                    </div>
                                </div>
                            </div>
                        </a>
                    </div>

                    <div class="col-md-6">
                        <a href="{{ route('catalog.library') }}" class="text-decoration-none">
                            <div class="card stats-card h-100 border-0 position-relative overflow-hidden"
                                style="border-radius: 1.5rem;">
                                <div class="card-glow"></div>
                                <div class="card-shape position-absolute top-0 end-0 w-100 h-100">
                                    <div class="shape-1"></div>
                                    <div class="shape-2"></div>
                                </div>
                                <div
                                    class="card-body d-flex justify-content-between align-items-center p-4 position-relative">
                                    <div class="text-white">
                                        <div class="small opacity-85 mb-2">Total Catalogues</div>
                                        <div class="display-4 fw-bold mt-1">{{ $catalogCount }}</div>
                                        <div class="small opacity-85 mt-2">Organized collections</div>
                                    </div>
                                    <div class="stats-icon" style="color: #ffffff">
                                        <div class="icon-wrapper">
                                            <i class="fas fa-book-open fa-2x"></i>
                                        </div>
                                    </div>
                                </div>
                                <div class="card-footer border-0 bg-transparent pt-0">
                                    <div class="small text-white opacity-85">
                                        <i class="fas fa-arrow-right me-1"></i> Browse catalogues
                                    </div>
                                </div>
                            </div>
                        </a>
                    </div>
                </div>

                <!-- Enhanced Recent Designs Section -->
                <div class="card elegant-card border-0 mt-4" style="border-radius: 1.5rem;">
                    <div class="card-header bg-transparent border-0 p-4 pb-3">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h2 class="h3 fw-bold mb-1 text-dark">Recent Designs</h2>
                                <p class="text-muted mb-0">Your latest creative explorations</p>
                            </div>
                            <a href="{{ route('catalog.library') }}" class="btn-view-all">
                                <span>View All</span>
                                <i class="fas fa-arrow-right ms-2"></i>
                            </a>
                        </div>
                    </div>

                    <div class="card-body p-4 pt-2">
                        <div class="row g-4">
                            @forelse ($recentLibrary->take(4) as $item)
                                <div class="col-12 col-sm-6 col-md-4 col-lg-3 col-xl-3">
                                    <div class="design-card border-0 p-3 shadow-sm h-100 position-relative"
                                        style="border-radius: 1.25rem;">

                                        <div class="design-badge position-absolute top-0 start-0 m-3">
                                            <span class="badge bg-primary">
                                                <i class="fas fa-sparkle me-1"></i> New
                                            </span>
                                        </div>

                                        <div class="position-absolute top-0 end-0 m-2">
                                            <div class="dropdown">
                                                <button class="btn btn-light btn-sm rounded-circle p-2" type="button"
                                                    data-bs-toggle="dropdown" aria-expanded="false"
                                                    style="color: var(--text-secondary); background-color: rgba(255,255,255,0.95); backdrop-filter: blur(20px); border: 1px solid rgba(255,255,255,0.2);">
                                                    <i class="fas fa-ellipsis-h"></i>
                                                </button>
                                                <ul class="dropdown-menu dropdown-menu-end shadow-lg border-0">
                                                    <li>
                                                        <a class="dropdown-item toggle-favorite-dashboard" href="#"
                                                            data-catalog-id="{{ $item->id }}">
                                                            <i class="fas fa-star me-2 text-muted"></i>
                                                            <span class="favorite-text">Add to Favorites</span>
                                                        </a>
                                                    </li>
                                                    <li><a class="dropdown-item" href="#"><i
                                                                class="fas fa-share-alt me-2 text-primary"></i> Share</a>
                                                    </li>
                                                    <li>
                                                        <hr class="dropdown-divider">
                                                    </li>
                                                    <li><a class="dropdown-item text-danger" href="#"><i
                                                                class="fas fa-trash me-2"></i> Delete</a></li>
                                                </ul>
                                            </div>
                                        </div>

                                        <div class="design-preview mb-3 position-relative"
                                            style="height: 200px; border-radius: 1rem; overflow: hidden;">

                                            @if ($item->image && file_exists(public_path('uploads/catalog/' . $item->image)))
                                                <img src="{{ asset('uploads/catalog/' . $item->image) }}"
                                                    class="w-100 h-100 object-fit-cover" alt="catalog image"
                                                    style="transition: transform 0.3s ease;">
                                            @else
                                                <div class="w-100 h-100">
                                                    <img src="{{ asset('/assets/upload/catlog_studion_image/ChatGPT%20Image%20Nov%208,%202025,%2012_20_23%20PM.png') }}"
                                                        class="w-100 h-100 object-fit-cover rounded-top"
                                                        alt="Default Catalog Image">
                                                </div>
                                            @endif

                                        </div>

                                        <div class="design-info">
                                            <h6 class="fw-bold text-dark mb-1 text-truncate"
                                                title="{{ $item->product_name ?? 'Untitled Design' }}">
                                                {{ $item->product_name ?? 'Untitled Design' }}
                                            </h6>

                                            <p class="small text-muted mb-2" style="font-size: 0.75rem;">
                                                <i class="fas fa-gem me-1"></i>
                                                {{ $item->jewelry_type ?? 'Jewelry' }}
                                            </p>

                                            <p class="small text-muted mb-3" style="font-size: 0.75rem;">
                                                <i class="fas fa-calendar me-1"></i>
                                                {{ \Carbon\Carbon::parse($item->created_at)->format('d M, Y') }}
                                            </p>

                                            <button
                                                class="btn btn-primary w-100 fw-semibold py-2 shadow-sm openModalDashboard"
                                                style="border-radius: 0.75rem;" data-id="{{ $item->id }}"
                                                data-name="{{ $item->product_name ?? 'Untitled Design' }}"
                                                data-type="{{ $item->jewelry_type ?? 'Jewelry' }}"
                                                data-metal="{{ $item->metal_type ?? 'Gold' }}"
                                                data-desc="{{ $item->design_desc ?? 'No description available' }}"
                                                data-date="{{ \Carbon\Carbon::parse($item->created_at)->format('m/d/Y') }}"
                                                data-main-image="{{ $item->image ? asset('uploads/catalog/' . $item->image) : asset('/assets/upload/catlog_studion_image/ChatGPT%20Image%20Nov%208,%202025,%2012_20_23%20PM.png') }}"
                                                data-photo-count="{{ $item->photo_count ?? 3 }}">
                                                <i class="fas fa-eye me-2"></i> View Design
                                            </button>
                                        </div>

                                    </div>
                                </div>
                            @empty
                                <div class="col-12">
                                    <div class="empty-design-card text-center p-5 border-0 shadow-sm"
                                        style="border-radius: 1.25rem; background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);">
                                        <div class="empty-icon mb-3">
                                            <div class="empty-icon-wrapper">
                                                <i class="fas fa-folder-open fa-3x text-muted"></i>
                                            </div>
                                        </div>
                                        <h5 class="text-dark fw-semibold mb-2">No Designs Found</h5>
                                        <p class="text-muted mb-4">Start creating beautiful AI jewelry designs.</p>
                                        {{-- <a href="{{ route('design_studio') }}" class="btn btn-primary px-4 py-2"> --}}
                                        <i class="fas fa-plus me-2"></i> Create New Design
                                        </a>
                                    </div>
                                </div>
                            @endforelse
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- POPUP MODAL -->
    <div class="modal fade" id="catalogModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-xl modal-dialog-centered">
            <div class="modal-content p-4 rounded-4">
                <div class="modal-body">
                    <div class="row g-4">
                        <!-- LEFT: Main Image + Info -->
                        <div class="col-md-6 text-center border-end">
                            <div class="position-relative d-inline-block main-image-wrapper">
                                <img id="modalMainImage" src=""
                                    class="img-fluid rounded mb-3 shadow-sm main-hover" alt="Design"
                                    style="max-height: 360px; object-fit: contain;">
                                <div class="center-overlay">
                                    <button id="downloadSingle" class="btn btn-white rounded-circle shadow-sm me-2"
                                        title="Download">
                                        <i class="fas fa-download"></i>
                                    </button>
                                    <button id="fullView" class="btn btn-white rounded-circle shadow-sm"
                                        title="Full View">
                                        <i class="fas fa-expand"></i>
                                    </button>
                                </div>
                            </div>
                            <h5 id="modalProductName" class="fw-semibold mb-1 text-break"></h5>
                            <p id="modalDesc" class="text-muted small mb-1"></p>
                            <div class="d-flex justify-content-center gap-3 mt-2">
                                <span class="badge bg-light text-dark" id="modalType"></span>
                                <span class="badge bg-light text-dark" id="modalMetal"></span>
                            </div>
                            <p id="modalDate" class="text-muted small mt-2 mb-0"></p>
                        </div>

                        <!-- RIGHT: Designs -->
                        <div class="col-md-6">
                            <h6 class="fw-semibold mb-3">Designs in this Catalogue</h6>
                            <div class="d-flex flex-wrap gap-3 mb-4" id="modalDesigns">
                                <!-- Dynamic designs will be loaded here -->
                            </div>

                            <div class="design-specifications mt-4">
                                <h6 class="fw-semibold mb-3">Design Specifications</h6>
                                <div class="specs-grid">
                                    <div class="spec-item d-flex align-items-center mb-3 p-3 rounded-3 bg-light">
                                        <div class="spec-icon me-3">
                                            <div class="icon-circle">
                                                <i class="fas fa-gem text-primary"></i>
                                            </div>
                                        </div>
                                        <div class="flex-grow-1">
                                            <div class="small text-muted">Design Type</div>
                                            <div class="fw-semibold text-dark" id="specType"></div>
                                        </div>
                                    </div>
                                    <div class="spec-item d-flex align-items-center mb-3 p-3 rounded-3 bg-light">
                                        <div class="spec-icon me-3">
                                            <div class="icon-circle">
                                                <i class="fas fa-weight text-primary"></i>
                                            </div>
                                        </div>
                                        <div class="flex-grow-1">
                                            <div class="small text-muted">Metal Type</div>
                                            <div class="fw-semibold text-dark" id="specMetal"></div>
                                        </div>
                                    </div>
                                    <div class="spec-item d-flex align-items-center mb-3 p-3 rounded-3 bg-light">
                                        <div class="spec-icon me-3">
                                            <div class="icon-circle">
                                                <i class="fas fa-calendar text-primary"></i>
                                            </div>
                                        </div>
                                        <div class="flex-grow-1">
                                            <div class="small text-muted">Created Date</div>
                                            <div class="fw-semibold text-dark" id="specDate"></div>
                                        </div>
                                    </div>
                                    <div class="spec-item d-flex align-items-center mb-3 p-3 rounded-3 bg-light">
                                        <div class="spec-icon me-3">
                                            <div class="icon-circle">
                                                <i class="fas fa-images text-primary"></i>
                                            </div>
                                        </div>
                                        <div class="flex-grow-1">
                                            <div class="small text-muted">Total Designs</div>
                                            <div class="fw-semibold text-dark" id="specDesignCount">0</div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="d-flex justify-content-end mt-4 gap-3">
                                <button id="exportAll" class="btn btn-outline-secondary px-4">
                                    <i class="fas fa-download me-2"></i>Export All
                                </button>
                                <button class="btn btn-outline-secondary px-4">
                                    <i class="fas fa-share me-2"></i>Share
                                </button>
                                <button class="btn btn-gradient px-4" data-bs-dismiss="modal">Close</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="fullImageModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-fullscreen">
            <div class="modal-content bg-dark text-center position-relative">
                <div class="modal-body d-flex align-items-center justify-content-center">
                    <img id="fullImage" src="" class="img-fluid shadow-lg full-view-img" alt="Full view">
                </div>
                <button class="btn btn-light position-absolute top-0 end-0 m-3 rounded-circle" data-bs-dismiss="modal">
                    <i class="fas fa-times"></i>
                </button>
            </div>
        </div>
    </div>

    <!-- ZIP LIBRARIES -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/FileSaver.js/2.0.5/FileSaver.min.js"></script>

    <script>
        $(document).ready(function() {
            let currentJewelryType = "catalog";

            // Open modal for dashboard items
            $(document).on('click', '.openModalDashboard', function() {
                const btn = $(this);
                const name = btn.data('name') || 'Untitled Design';
                const type = btn.data('type') || 'Jewelry';
                const metal = btn.data('metal') || 'Not specified';
                const desc = btn.data('desc') || 'No description available';
                const date = btn.data('date');
                const mainImage = btn.data('main-image');
                const photoCount = parseInt(btn.data('photo-count')) || 3;

                // Set modal content
                $('#modalProductName').text(name);
                $('#modalDesc').text(desc);
                $('#modalDate').html(`<strong>Created:</strong> ${date}`);
                $('#modalType').text(type);
                $('#modalMetal').text(metal);

                // Set specification content
                $('#specType').text(type);
                $('#specMetal').text(metal);
                $('#specDate').text(date);
                $('#specDesignCount').text(photoCount);

                // Set main image
                $('#modalMainImage').attr('src', mainImage).attr('data-active', mainImage);

                // Generate dynamic design thumbnails based on photo_count
                const designsContainer = $('#modalDesigns');
                designsContainer.empty();

                // Create multiple design variations based on photo_count
                for (let i = 1; i <= photoCount; i++) {
                    // You can modify this to use different images for each design
                    // For now, using the same main image but you can customize this
                    const designImage = mainImage;
                    const designName = `Design ${i}`;

                    // You can add logic here to use different images for each design
                    // For example: const designImage = getDesignImage(mainImage, i);

                    const html = `
                        <div class="design-thumb" style="cursor:pointer;">
                            <img src="${designImage}" class="img-thumbnail rounded thumb-img"
                                 style="width:120px; height:120px; object-fit:cover;"
                                 data-full="${designImage}"
                                 data-design-name="${designName}"
                                 alt="${designName}">
                            <p class="small text-center mt-1 text-muted">${designName}</p>
                        </div>`;
                    designsContainer.append(html);
                }

                // Set current jewelry type for export
                currentJewelryType = type.toLowerCase().replace(/\s+/g, '_');

                // Activate first thumbnail
                $('#modalDesigns .thumb-img').first().addClass('active-thumb');

                // Show modal
                $('#catalogModal').modal('show');
            });

            // Thumbnail click handler
            $(document).on('click', '.thumb-img', function() {
                const src = $(this).data('full');
                const designName = $(this).data('design-name');

                $('#modalMainImage').attr('src', src).attr('data-active', src);
                $('.thumb-img').removeClass('active-thumb');
                $(this).addClass('active-thumb');

                // Update product name when clicking different designs
                $('#modalProductName').text(designName);
            });

            // Download single image
            $('#downloadSingle').on('click', async function() {
                const src = $('#modalMainImage').attr('data-active');
                const designName = $('#modalProductName').text().replace(/\s+/g, '_');
                try {
                    const response = await fetch(src);
                    const blob = await response.blob();
                    saveAs(blob, `${designName}.jpg`);
                } catch (error) {
                    console.error('Download failed:', error);
                    alert('Failed to download image. Please try again.');
                }
            });

            // Export all images
            $('#exportAll').on('click', async function() {
                const zip = new JSZip();
                const imgList = [];

                // Collect all design images
                $('.thumb-img').each(function() {
                    imgList.push({
                        url: $(this).data('full'),
                        name: $(this).data('design-name').replace(/\s+/g, '_')
                    });
                });

                try {
                    for (let i = 0; i < imgList.length; i++) {
                        const response = await fetch(imgList[i].url);
                        const blob = await response.blob();
                        const fileName = `${imgList[i].name}.jpg`;
                        zip.file(fileName, blob);
                    }

                    const fileName = `${currentJewelryType}_designs.zip`;
                    const content = await zip.generateAsync({
                        type: "blob"
                    });
                    saveAs(content, fileName);
                } catch (error) {
                    console.error('Export failed:', error);
                    alert('Failed to export designs. Please try again.');
                }
            });

            // Full view
            $('#fullView').on('click', function() {
                const src = $('#modalMainImage').attr('data-active');
                $('#fullImage').attr('src', src);
                $('#fullImageModal').modal('show');
            });

            // Favorite toggle for dashboard
            $(document).on('click', '.toggle-favorite-dashboard', function(e) {
                e.preventDefault();
                e.stopPropagation();

                const btn = $(this);
                const catalogId = btn.data('catalog-id');
                const favoriteText = btn.find('.favorite-text');
                const favoriteIcon = btn.find('i');

                $.ajax({
                    url: '{{ route('toggle.favorite') }}',
                    method: 'POST',
                    data: {
                        catalog_id: catalogId,
                        _token: '{{ csrf_token() }}'
                    },
                    success: function(response) {
                        if (response.success) {
                            favoriteText.text(response.is_favorite ? 'Remove from Favorites' :
                                'Add to Favorites');
                            favoriteIcon.toggleClass('text-muted text-warning', response
                                .is_favorite);
                        }
                    },
                    error: function(xhr) {
                        console.error('Error toggling favorite:', xhr.responseJSON?.message ||
                            'Unknown error');
                        alert('Failed to update favorite. Please try again.');
                    }
                });
            });

            // Helper function to get different design images (you can customize this)
            function getDesignImage(baseImage, index) {
                // This is a placeholder function - you can implement logic to get different images
                // For example, you could have multiple image versions stored
                // return `path/to/design-${index}.jpg`;
                return baseImage; // Returning same image for now
            }
        });
    </script>
@endsection
