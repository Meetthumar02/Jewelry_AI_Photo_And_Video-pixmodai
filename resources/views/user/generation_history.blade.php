@extends('user.app')
@section('title', 'Generation History')

@section('content')

    <style>
        .custom-pagination {
            list-style: none;
            display: flex;
            gap: 6px;
            padding: 0;
            margin: 0
        }

        .custom-pagination .page-item {
            display: inline-block
        }

        .custom-pagination .page-link {
            padding: 6px 12px;
            border-radius: 6px;
            border: 1px solid #ddd;
            color: #333;
            text-decoration: none
        }

        .custom-pagination .page-item.active .page-link {
            background: #6a4dfd;
            color: #fff;
            border-color: #6a4dfd
        }

        .custom-pagination .prev-next {
            background: #f3f4f6
        }
    </style>

    <div class="p-4">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h4 class="fw-semibold mb-1">Generation History</h4>
                <p class="text-muted mb-0">View all your AI photoshoot generations with date and type.</p>
            </div>
        </div>

        <div class="card mb-4">
            <div class="card-body d-flex flex-wrap align-items-center gap-3">
                <div class="flex-grow-1">
                    <input type="text" id="searchGeneration" class="form-control" placeholder="Search..."
                        value="{{ request('search') }}">
                </div>

                <select id="filterType" class="form-select w-auto">
                    <option value="">All Product Types</option>
                    <option value="ring" {{ request('type') == 'ring' ? 'selected' : '' }}>Ring</option>
                    <option value="necklace" {{ request('type') == 'necklace' ? 'selected' : '' }}>Necklace</option>
                    <option value="bangle" {{ request('type') == 'bangle' ? 'selected' : '' }}>Bangle</option>
                    <option value="earring" {{ request('type') == 'earring' ? 'selected' : '' }}>Earring</option>
                </select>

                <select id="filterMode" class="form-select w-auto">
                    <option value="">All Status</option>
                    <option value="completed" {{ request('mode') == 'completed' ? 'selected' : '' }}>Completed</option>
                    <option value="processing" {{ request('mode') == 'processing' ? 'selected' : '' }}>Processing</option>
                    <option value="failed" {{ request('mode') == 'failed' ? 'selected' : '' }}>Failed</option>
                </select>

                <select id="sortBy" class="form-select w-auto">
                    <option value="latest" {{ request('sort', 'latest') == 'latest' ? 'selected' : '' }}>Latest First</option>
                    <option value="oldest" {{ request('sort') == 'oldest' ? 'selected' : '' }}>Oldest First</option>
                </select>
            </div>
        </div>

        <div class="card">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover align-middle">
                        <thead>
                            <tr>
                                <th>Product Type</th>
                                <th>Shoot Type</th>
                                <th>Status</th>
                                <th>Model Design</th>
                                <th>Photos</th>
                                <th>Credits Used</th>
                                <th>Generated Date</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($generations as $generation)
                                <tr>
                                    <td>
                                        <strong>{{ $generation->product_type ?? '-' }}</strong><br>
                                        <small class="text-muted">{{ $generation->source === 'creative' ? 'Creative AI' : 'Photoshoot' }}</small>
                                    </td>

                                    <td>
                                        <span class="badge bg-primary text-capitalize">
                                            {{ $generation->shoot_type ?? '-' }}
                                        </span>
                                    </td>

                                    <td>
                                        <span class="badge bg-info text-capitalize">
                                            {{ $generation->status ?? '-' }}
                                        </span>
                                    </td>

                                    <td>
                                        <span class="badge bg-secondary text-capitalize">
                                            {{ $generation->model_design_id ?? '-' }}
                                        </span>
                                    </td>

                                    <td>
                                        @php
                                            $imgs = is_array($generation->generated_images)
                                                ? $generation->generated_images
                                                : (json_decode($generation->generated_images ?? '[]', true) ?: []);
                                            $imgCount = max(count($imgs), $generation->generated_images ? 1 : 0);
                                        @endphp
                                        <span class="badge bg-success">
                                            {{ $imgCount }} Photo(s)
                                        </span>
                                    </td>

                                    <td>
                                        <span class="badge bg-warning text-dark">
                                            <i class="fas fa-coins me-1"></i>
                                            {{ $generation->credits_used ?? 0 }} Credits
                                        </span>
                                    </td>

                                    <td>
                                        <small class="text-muted">
                                            {{ \Carbon\Carbon::parse($generation->created_at)->format('M d, Y') }}<br>
                                            <span>{{ \Carbon\Carbon::parse($generation->created_at)->format('h:i A') }}</span>
                                        </small>
                                    </td>

                                    <td>
                                        <button class="btn btn-sm btn-outline-primary view-details"
                                            data-product="{{ $generation->product_type }}"
                                            data-shoot="{{ $generation->shoot_type }}"
                                            data-mode="{{ $generation->status }}"
                                            data-model="{{ $generation->model_design_id }}"
                                            data-credits="{{ $generation->credits_used }}"
                                            data-date="{{ \Carbon\Carbon::parse($generation->created_at)->format('M d, Y h:i A') }}">
                                            <i class="fas fa-eye me-1"></i> View
                                        </button>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="8" class="text-center py-5 text-muted">
                                        No generation history available yet.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                {{ $generations->links() }}
            </div>
        </div>
    </div>

    <div class="modal fade" id="generationModal" tabindex="-1">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Generation Details</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <p><strong>Product:</strong> <span id="modalProduct"></span></p>
                    <p><strong>Shoot Type:</strong> <span id="modalShoot"></span></p>
                    <p><strong>Status:</strong> <span id="modalMode"></span></p>
                    <p><strong>Model:</strong> <span id="modalModel"></span></p>
                    <p><strong>Credits:</strong> <span id="modalCredits"></span></p>
                    <p><strong>Date:</strong> <span id="modalDate"></span></p>
                </div>
            </div>
        </div>
    </div>

    <script>
        var baseUrl = "{{ route('generation.history') }}";

        function buildUrlAndNavigate() {
            var params = new URLSearchParams();
            var search = document.getElementById('searchGeneration').value;
            var type = document.getElementById('filterType').value;
            var mode = document.getElementById('filterMode').value;
            var sort = document.getElementById('sortBy').value;
            if (search) params.set('search', search);
            if (type) params.set('type', type);
            if (mode) params.set('mode', mode);
            if (sort && sort !== 'latest') params.set('sort', sort);
            window.location.href = baseUrl + (params.toString() ? '?' + params.toString() : '');
        }

        ['filterType', 'filterMode', 'sortBy'].forEach(id => {
            document.getElementById(id).addEventListener('change', buildUrlAndNavigate)
        });

        let timer;
        document.getElementById('searchGeneration').addEventListener('input', function() {
            clearTimeout(timer);
            timer = setTimeout(buildUrlAndNavigate, 600);
        });

        document.querySelectorAll('.view-details').forEach(btn => {
            btn.addEventListener('click', function() {
                document.getElementById('modalProduct').textContent = this.dataset.product;
                document.getElementById('modalShoot').textContent = this.dataset.shoot;
                document.getElementById('modalMode').textContent = this.dataset.mode;
                document.getElementById('modalModel').textContent = this.dataset.model;
                document.getElementById('modalCredits').textContent = this.dataset.credits;
                document.getElementById('modalDate').textContent = this.dataset.date;
                new bootstrap.Modal(document.getElementById('generationModal')).show();
            })
        });
    </script>

@endsection
