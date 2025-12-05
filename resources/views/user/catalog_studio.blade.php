@extends('user.app')
@section('title', 'AI Photo Shoots')
@section('content')

    <style>
        .ai-photoshoot-wrapper {
            display: flex;
            height: calc(100vh - 120px);
            gap: 0;
            background: #000;
        }

        .left-panel {
            width: 480px;
            background: #1a1a1a;
            color: #fff;
            overflow-y: auto;
            padding: 24px;
            border-right: 1px solid #333;
        }

        .right-panel {
            flex: 1;
            background: #000;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 40px;
        }

        .step-header {
            display: flex;
            align-items: center;
            gap: 12px;
            margin-bottom: 24px;
            padding-bottom: 16px;
            border-bottom: 1px solid #333;
        }

        .step-number {
            width: 32px;
            height: 32px;
            border-radius: 50%;
            background: linear-gradient(135deg, #a855f7 0%, #7c3aed 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 600;
            font-size: 14px;
        }

        .step-title {
            font-size: 16px;
            font-weight: 600;
            color: #fff;
        }

        .selection-group {
            margin-bottom: 24px;
        }

        .selection-label {
            font-size: 13px;
            font-weight: 500;
            color: #9ca3af;
            margin-bottom: 8px;
            display: block;
        }

        .dropdown-custom {
            width: 100%;
            background: #2a2a2a;
            border: 1px solid #404040;
            border-radius: 8px;
            padding: 12px 16px;
            color: #fff;
            font-size: 14px;
            cursor: pointer;
            transition: all 0.2s;
        }

        .dropdown-custom:hover {
            border-color: #a855f7;
        }

        .dropdown-custom:focus {
            outline: none;
            border-color: #a855f7;
            box-shadow: 0 0 0 3px rgba(168, 85, 247, 0.1);
        }

        .shoot-type-buttons {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 8px;
        }

        .shoot-type-btn {
            padding: 10px 8px;
            background: #2a2a2a;
            border: 1px solid #404040;
            border-radius: 8px;
            color: #9ca3af;
            font-size: 12px;
            font-weight: 500;
            cursor: pointer;
            transition: all 0.2s;
            text-align: center;
        }

        .shoot-type-btn:hover {
            border-color: #a855f7;
            color: #fff;
        }

        .shoot-type-btn.active {
            background: linear-gradient(135deg, #a855f7 0%, #7c3aed 100%);
            border-color: #a855f7;
            color: #fff;
        }

        .model-designs-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 12px;
            margin-top: 12px;
        }

        .model-design-card {
            aspect-ratio: 1;
            border-radius: 12px;
            overflow: hidden;
            cursor: pointer;
            border: 2px solid transparent;
            transition: all 0.2s;
            position: relative;
        }

        .model-design-card:hover {
            border-color: #a855f7;
            transform: scale(1.02);
        }

        .model-design-card.selected {
            border-color: #a855f7;
            box-shadow: 0 0 0 3px rgba(168, 85, 247, 0.2);
        }

        .model-design-card img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .upload-zone {
            border: 2px dashed #404040;
            border-radius: 12px;
            padding: 32px;
            text-align: center;
            cursor: pointer;
            transition: all 0.2s;
            background: #2a2a2a;
            margin-bottom: 20px;
        }

        .upload-zone:hover {
            border-color: #a855f7;
            background: #2f2f2f;
        }

        .upload-zone.has-image {
            border-color: #10b981;
            padding: 0;
        }

        .upload-preview {
            width: 100%;
            max-height: 300px;
            object-fit: contain;
            border-radius: 10px;
        }

        .upload-icon {
            font-size: 48px;
            color: #6b7280;
            margin-bottom: 12px;
        }

        .upload-text {
            color: #9ca3af;
            font-size: 14px;
            margin-bottom: 8px;
        }

        .upload-subtext {
            color: #6b7280;
            font-size: 12px;
        }

        .ratio-buttons {
            display: flex;
            gap: 8px;
            margin-bottom: 16px;
        }

        .ratio-btn {
            flex: 1;
            padding: 8px;
            background: #2a2a2a;
            border: 1px solid #404040;
            border-radius: 6px;
            color: #9ca3af;
            font-size: 12px;
            cursor: pointer;
            transition: all 0.2s;
        }

        .ratio-btn:hover {
            border-color: #a855f7;
            color: #fff;
        }

        .ratio-btn.active {
            background: #a855f7;
            border-color: #a855f7;
            color: #fff;
        }

        .format-buttons {
            display: flex;
            gap: 8px;
        }

        .format-btn {
            flex: 1;
            padding: 10px;
            background: #2a2a2a;
            border: 1px solid #404040;
            border-radius: 6px;
            color: #9ca3af;
            font-size: 13px;
            font-weight: 500;
            cursor: pointer;
            transition: all 0.2s;
        }

        .format-btn:hover {
            border-color: #a855f7;
            color: #fff;
        }

        .format-btn.active {
            background: #a855f7;
            border-color: #a855f7;
            color: #fff;
        }

        .generate-btn {
            width: 100%;
            padding: 16px;
            background: linear-gradient(135deg, #a855f7 0%, #7c3aed 100%);
            border: none;
            border-radius: 12px;
            color: #fff;
            font-size: 15px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.2s;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            margin-top: 24px;
        }

        .generate-btn:hover:not(:disabled) {
            transform: translateY(-2px);
            box-shadow: 0 8px 16px rgba(168, 85, 247, 0.3);
        }

        .generate-btn:disabled {
            opacity: 0.5;
            cursor: not-allowed;
        }

        .preview-container {
            max-width: 800px;
            width: 100%;
        }

        .preview-empty {
            text-align: center;
            color: #6b7280;
        }

        .preview-empty i {
            font-size: 64px;
            margin-bottom: 16px;
            opacity: 0.5;
        }

        .preview-empty h6 {
            font-size: 18px;
            font-weight: 600;
            color: #9ca3af;
            margin-bottom: 8px;
        }

        .preview-empty p {
            font-size: 14px;
            color: #6b7280;
        }

        .preview-image-wrapper {
            background: #1a1a1a;
            border-radius: 16px;
            padding: 20px;
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.4);
        }

        .preview-image {
            width: 100%;
            border-radius: 12px;
            display: block;
        }

        .preview-actions {
            display: flex;
            gap: 12px;
            margin-top: 20px;
            justify-content: center;
        }

        .preview-btn {
            padding: 12px 24px;
            border-radius: 8px;
            font-size: 14px;
            font-weight: 500;
            cursor: pointer;
            transition: all 0.2s;
            display: flex;
            align-items: center;
            gap: 8px;
            border: none;
        }

        .btn-download {
            background: linear-gradient(135deg, #10b981 0%, #059669 100%);
            color: #fff;
        }

        .btn-share {
            background: #2a2a2a;
            color: #fff;
            border: 1px solid #404040;
        }

        .btn-download:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 16px rgba(16, 185, 129, 0.3);
        }

        .btn-share:hover {
            border-color: #a855f7;
        }

        .success-badge {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 6px 12px;
            background: rgba(16, 185, 129, 0.1);
            border: 1px solid #10b981;
            border-radius: 20px;
            color: #10b981;
            font-size: 12px;
            font-weight: 500;
            margin-bottom: 12px;
        }

        /* Hide scrollbar but keep functionality */
        .left-panel::-webkit-scrollbar {
            width: 6px;
        }

        .left-panel::-webkit-scrollbar-track {
            background: #1a1a1a;
        }

        .left-panel::-webkit-scrollbar-thumb {
            background: #404040;
            border-radius: 3px;
        }

        .left-panel::-webkit-scrollbar-thumb:hover {
            background: #505050;
        }
    </style>

    <div class="ai-photoshoot-wrapper">
        <!-- LEFT PANEL -->
        <div class="left-panel">
            <!-- STEP 1: Choose Style -->
            <div class="step-section">
                <div class="step-header">
                    <div class="step-number">1</div>
                    <div class="step-title">Select Your Style</div>
                </div>

                <div class="selection-group">
                    <label class="selection-label">Industry</label>
                    <select class="dropdown-custom" id="industry">
                        <option value="Jewellery">Jewellery</option>
                        <option value="Fashion">Fashion</option>
                        <option value="Accessories">Accessories</option>
                    </select>
                </div>

                <div class="selection-group">
                    <label class="selection-label">Category</label>
                    <select class="dropdown-custom" id="category">
                        <option value="Women Jewellery">Women Jewellery</option>
                        <option value="Men Jewellery">Men Jewellery</option>
                        <option value="Kids Jewellery">Kids Jewellery</option>
                    </select>
                </div>

                <div class="selection-group">
                    <label class="selection-label">Product Type</label>
                    <select class="dropdown-custom" id="productType">
                        <option value="Necklace">Necklace</option>
                        <option value="Earrings">Earrings</option>
                        <option value="Ring">Ring</option>
                        <option value="Bracelet">Bracelet</option>
                        <option value="Pendant">Pendant</option>
                        <option value="Mangalsutra">Mangalsutra</option>
                    </select>
                </div>

                <div class="selection-group">
                    <label class="selection-label">2. Select Shoot Type</label>
                    <div class="shoot-type-buttons">
                        <div class="shoot-type-btn active" data-type="Classic">Classic</div>
                        <div class="shoot-type-btn" data-type="Lifestyle">Lifestyle</div>
                        <div class="shoot-type-btn" data-type="Luxury">Luxury</div>
                        <div class="shoot-type-btn" data-type="Outdoor">Outdoor</div>
                    </div>
                </div>
            </div>

            <!-- STEP 2: Choose Model Design -->
            <div class="step-section" style="margin-top: 32px;">
                <div class="step-header">
                    <div class="step-number">2</div>
                    <div class="step-title">Choose Model Design</div>
                </div>

                <div class="model-designs-grid" id="modelDesignsGrid">
                    <!-- Model designs will be loaded here -->
                </div>
            </div>

            <!-- STEP 3: Upload Image -->
            <div class="step-section" style="margin-top: 32px;">
                <div class="step-header">
                    <div class="step-number">3</div>
                    <div class="step-title">Upload Image & Configure</div>
                </div>

                <div class="upload-zone" id="uploadZone">
                    <input type="file" id="imageInput" accept="image/jpeg,image/png,image/jpg" style="display:none;">
                    <i class="bi bi-cloud-upload upload-icon"></i>
                    <div class="upload-text">For best results, upload a clear, full photo</div>
                    <div class="upload-subtext">Supports: JPG, PNG (Max: 10MB)</div>
                </div>

                <div class="selection-group">
                    <label class="selection-label">Aspect Ratio</label>
                    <div class="ratio-buttons">
                        <div class="ratio-btn" data-ratio="1:1">1:1</div>
                        <div class="ratio-btn active" data-ratio="4:3">4:3</div>
                        <div class="ratio-btn" data-ratio="16:9">16:9</div>
                        <div class="ratio-btn" data-ratio="3:4">3:4</div>
                        <div class="ratio-btn" data-ratio="9:16">9:16</div>
                    </div>
                </div>

                <div class="selection-group">
                    <label class="selection-label">Output Format</label>
                    <div class="format-buttons">
                        <div class="format-btn active" data-format="JPEG">JPEG</div>
                        <div class="format-btn" data-format="PNG">PNG</div>
                    </div>
                </div>

                <button class="generate-btn" id="generateBtn" disabled>
                    <i class="bi bi-stars"></i>
                    Start Product Shoot
                </button>
            </div>
        </div>

        <!-- RIGHT PANEL -->
        <div class="right-panel">
            <div class="preview-container" id="previewContainer">
                <div class="preview-empty">
                    <i class="bi bi-image"></i>
                    <h6>No images generated yet</h6>
                    <p>Configure your settings and start the shoot to see results</p>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            let uploadedImagePath = null;
            let selectedModelDesign = null;
            let selectedShootType = 'Classic';
            let selectedRatio = '4:3';
            let selectedFormat = 'JPEG';

            const Toast = Swal.mixin({
                toast: true,
                position: 'top-end',
                showConfirmButton: false,
                timer: 3000,
                timerProgressBar: true,
            });

            // Load model designs
            loadModelDesigns();

            // Shoot type selection
            document.querySelectorAll('.shoot-type-btn').forEach(btn => {
                btn.addEventListener('click', function() {
                    document.querySelectorAll('.shoot-type-btn').forEach(b => b.classList.remove(
                        'active'));
                    this.classList.add('active');
                    selectedShootType = this.dataset.type;
                    checkFormValid();
                });
            });

            // Aspect ratio selection
            document.querySelectorAll('.ratio-btn').forEach(btn => {
                btn.addEventListener('click', function() {
                    document.querySelectorAll('.ratio-btn').forEach(b => b.classList.remove(
                        'active'));
                    this.classList.add('active');
                    selectedRatio = this.dataset.ratio;
                });
            });

            // Format selection
            document.querySelectorAll('.format-btn').forEach(btn => {
                btn.addEventListener('click', function() {
                    document.querySelectorAll('.format-btn').forEach(b => b.classList.remove(
                        'active'));
                    this.classList.add('active');
                    selectedFormat = this.dataset.format;
                });
            });

            // Upload zone click
            document.getElementById('uploadZone').addEventListener('click', function() {
                document.getElementById('imageInput').click();
            });

            // Image upload
            document.getElementById('imageInput').addEventListener('change', function(e) {
                const file = e.target.files[0];
                if (!file) return;

                // Validate file
                if (file.size > 10 * 1024 * 1024) {
                    Toast.fire({
                        icon: 'error',
                        title: 'File size must be less than 10MB'
                    });
                    return;
                }

                // Upload file
                const formData = new FormData();
                formData.append('image', file);

                Toast.fire({
                    icon: 'info',
                    title: 'Uploading image...'
                });

                fetch('{{ route('ai.photoshoot.upload') }}', {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        },
                        body: formData
                    })
                    .then(res => res.json())
                    .then(data => {
                        if (data.success) {
                            uploadedImagePath = data.path;

                            // Show preview
                            const uploadZone = document.getElementById('uploadZone');
                            uploadZone.classList.add('has-image');
                            uploadZone.innerHTML =
                                `<img src="${data.url}" class="upload-preview" alt="Uploaded">`;

                            Toast.fire({
                                icon: 'success',
                                title: 'Image uploaded successfully!'
                            });
                            checkFormValid();
                        } else {
                            Toast.fire({
                                icon: 'error',
                                title: data.message || 'Upload failed'
                            });
                        }
                    })
                    .catch(err => {
                        Toast.fire({
                            icon: 'error',
                            title: 'Upload failed: ' + err.message
                        });
                    });
            });

            // Generate button
            document.getElementById('generateBtn').addEventListener('click', function() {
                if (!uploadedImagePath || !selectedModelDesign) {
                    Toast.fire({
                        icon: 'warning',
                        title: 'Please complete all steps'
                    });
                    return;
                }

                const data = {
                    industry: document.getElementById('industry').value,
                    category: document.getElementById('category').value,
                    product_type: document.getElementById('productType').value,
                    shoot_type: selectedShootType,
                    model_design_id: selectedModelDesign,
                    uploaded_image: uploadedImagePath,
                    aspect_ratio: selectedRatio,
                    output_format: selectedFormat,
                };

                Swal.fire({
                    title: 'Generating Photo Shoot...',
                    html: 'Please wait while we create your perfect shot',
                    allowOutsideClick: false,
                    didOpen: () => {
                        Swal.showLoading();
                    }
                });

                fetch('{{ route('ai.photoshoot.start') }}', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        },
                        body: JSON.stringify(data)
                    })
                    .then(res => res.json())
                    .then(result => {
                        Swal.close();

                        if (result.success) {
                            Toast.fire({
                                icon: 'success',
                                title: 'Photo shoot completed!'
                            });
                            displayResult(result.shoot);
                        } else {
                            Toast.fire({
                                icon: 'error',
                                title: result.message || 'Generation failed'
                            });
                        }
                    })
                    .catch(err => {
                        Swal.close();
                        Toast.fire({
                            icon: 'error',
                            title: 'Error: ' + err.message
                        });
                    });
            });

            function loadModelDesigns() {
                const grid = document.getElementById('modelDesignsGrid');
                const models = @json($modelDesigns);

                models.forEach(model => {
                    const card = document.createElement('div');
                    card.className = 'model-design-card';
                    card.dataset.modelId = model.id;
                    card.innerHTML = `<img src="${model.thumbnail}" alt="${model.name}">`;

                    card.addEventListener('click', function() {
                        document.querySelectorAll('.model-design-card').forEach(c => c.classList
                            .remove('selected'));
                        this.classList.add('selected');
                        selectedModelDesign = model.id;
                        checkFormValid();
                    });

                    grid.appendChild(card);
                });
            }

            function checkFormValid() {
                const btn = document.getElementById('generateBtn');
                btn.disabled = !(uploadedImagePath && selectedModelDesign);
            }

            function displayResult(shoot) {
                const container = document.getElementById('previewContainer');
                const imageUrl = shoot.generated_images && shoot.generated_images[0] ?
                    shoot.generated_images[0] :
                    '/placeholder.jpg';

                container.innerHTML = `
            <div class="preview-image-wrapper">
                <div class="success-badge">
                    <i class="bi bi-check-circle-fill"></i>
                    Image generated successfully!
                </div>
                <img src="${imageUrl}" class="preview-image" alt="Generated">
                <div class="preview-actions">
                    <button class="preview-btn btn-download" onclick="downloadImage(${shoot.id})">
                        <i class="bi bi-download"></i>
                        Download
                    </button>
                    <button class="preview-btn btn-share">
                        <i class="bi bi-share"></i>
                        Share
                    </button>
                </div>
            </div>
        `;
            }

            window.downloadImage = function(shootId) {
                window.location.href = `/ai-photoshoot/download/${shootId}`;
            };
        });
    </script>

@endsection
