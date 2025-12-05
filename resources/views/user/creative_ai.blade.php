@extends('user.app')
@section('title', 'Creative AI')
@section('content')

    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        .creative-ai-wrapper {
            display: flex;
            height: 100vh;
            background: #000;
            overflow: hidden;
        }

        /* LEFT SIDEBAR */
        .left-sidebar {
            width: 540px;
            background: #0a0a0a;
            color: #fff;
            overflow-y: auto;
            border-right: 1px solid #1f1f1f;
            display: flex;
            flex-direction: column;
        }

        .top-tabs {
            display: flex;
            gap: 12px;
            padding: 12px 20px;
            border-bottom: 1px solid #1f1f1f;
            background: #0a0a0a;
        }

        .tab-btn {
            padding: 6px 16px;
            background: transparent;
            border: 1px solid #3a3a3a;
            color: #9ca3af;
            font-size: 13px;
            font-weight: 500;
            cursor: pointer;
            border-radius: 6px;
            transition: all 0.2s;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 6px;
        }

        .tab-btn.active {
            background: linear-gradient(135deg, #a855f7 0%, #7c3aed 100%);
            color: #fff;
        }

        .tab-btn:hover:not(.active) {
            background: rgba(168, 85, 247, 0.15);
            border-color: #a855f7;
            color: #fff;
        }

        .left-content {
            padding: 24px;
            flex: 1;
            overflow-y: auto;
        }

        .step-section {
            margin-bottom: 28px;
        }

        .step-title {
            font-size: 15px;
            font-weight: 600;
            color: #fff;
            margin-bottom: 10px;
        }

        .prompt-label {
            font-size: 12px;
            color: #9ca3af;
            margin-bottom: 8px;
            display: block;
        }

        .prompt-textarea-wrapper {
            position: relative;
            border: 2px solid #a855f7;
            border-radius: 12px;
            background: #0f0f0f;
            padding: 16px;
        }

        .prompt-textarea {
            width: 100%;
            min-height: 120px;
            background: transparent;
            border: none;
            color: #fff;
            font-size: 13px;
            line-height: 1.6;
            resize: vertical;
            outline: none;
        }

        .prompt-textarea::placeholder {
            color: #6b7280;
        }

        .prompt-actions {
            display: flex;
            justify-content: flex-end;
            gap: 8px;
            margin-top: 12px;
        }

        .prompt-icon-btn {
            width: 32px;
            height: 32px;
            border-radius: 50%;
            background: #1a1a1a;
            border: 1px solid #2a2a2a;
            color: #9ca3af;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            transition: all 0.2s;
            font-size: 14px;
        }

        .prompt-icon-btn:hover {
            border-color: #a855f7;
            color: #fff;
        }

        .upload-description {
            font-size: 12px;
            color: #6b7280;
            margin-bottom: 12px;
        }

        .upload-area {
            background: #0f0f0f;
            border: 2px dashed #2a2a2a;
            border-radius: 12px;
            padding: 32px;
            text-align: center;
            cursor: pointer;
            transition: all 0.2s;
            margin-bottom: 20px;
        }

        .upload-area:hover {
            border-color: #a855f7;
            background: #141414;
        }

        .upload-area.has-file {
            border-color: #10b981;
            border-style: solid;
            padding: 0;
        }

        .upload-placeholder {
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 8px;
        }

        .upload-placeholder i {
            font-size: 40px;
            color: #a855f7;
            margin-bottom: 8px;
        }

        .upload-placeholder .main-text {
            color: #fff;
            font-size: 14px;
            font-weight: 500;
        }

        .upload-placeholder .sub-text {
            color: #6b7280;
            font-size: 12px;
        }

        .upload-link {
            color: #a855f7;
            text-decoration: underline;
            cursor: pointer;
        }

        .upload-preview-img {
            width: 100%;
            max-height: 300px;
            object-fit: contain;
            border-radius: 10px;
        }

        .config-row {
            margin-bottom: 20px;
        }

        .config-label {
            font-size: 12px;
            color: #9ca3af;
            margin-bottom: 8px;
            display: block;
        }

        .ratio-grid {
            display: grid;
            grid-template-columns: repeat(5, 1fr);
            gap: 6px;
        }

        .ratio-option {
            padding: 10px 8px;
            background: #1a1a1a;
            border: 1px solid #2a2a2a;
            border-radius: 6px;
            color: #9ca3af;
            font-size: 12px;
            text-align: center;
            cursor: pointer;
            transition: all 0.2s;
        }

        .ratio-option:hover {
            border-color: #404040;
            color: #fff;
        }

        .ratio-option.active {
            background: #fff;
            border-color: #fff;
            color: #000;
            font-weight: 600;
        }

        .format-grid {
            display: flex;
            gap: 8px;
        }

        .format-option {
            flex: 1;
            padding: 10px;
            background: #1a1a1a;
            border: 1px solid #2a2a2a;
            border-radius: 6px;
            color: #9ca3af;
            font-size: 13px;
            text-align: center;
            font-weight: 500;
            cursor: pointer;
            transition: all 0.2s;
        }

        .format-option:hover {
            border-color: #404040;
            color: #fff;
        }

        .format-option.active {
            background: #fff;
            border-color: #fff;
            color: #000;
            font-weight: 600;
        }

        .generate-section {
            position: sticky;
            bottom: 0;
            background: #0a0a0a;
            padding: 20px 24px;
            border-top: 1px solid #1f1f1f;
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .generate-btn {
            flex: 1;
            padding: 14px 24px;
            background: #4a5568;
            border: none;
            border-radius: 8px;
            color: #9ca3af;
            font-size: 14px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.2s;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
        }

        .generate-btn.enabled {
            background: linear-gradient(135deg, #a855f7 0%, #7c3aed 100%);
            color: #fff;
        }

        .generate-btn.enabled:hover {
            transform: translateY(-1px);
            box-shadow: 0 8px 20px rgba(168, 85, 247, 0.4);
        }

        .generate-btn:disabled {
            opacity: 0.5;
            cursor: not-allowed;
        }

        .play-btn {
            width: 48px;
            height: 48px;
            background: #1a1a1a;
            border: 1px solid #2a2a2a;
            border-radius: 8px;
            color: #9ca3af;
            font-size: 18px;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: all 0.2s;
        }

        .play-btn:hover {
            border-color: #a855f7;
            color: #fff;
        }

        .right-panel {
            flex: 1;
            background: #000;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 40px;
            position: relative;
        }

        .preview-area {
            width: 100%;
            max-width: 900px;
            height: 100%;
            max-height: 700px;
            border-radius: 16px;
            background: linear-gradient(135deg, rgba(168, 85, 247, 0.1) 0%, rgba(124, 58, 237, 0.1) 100%);
            border: 1px solid rgba(168, 85, 247, 0.2);
            display: flex;
            align-items: center;
            justify-content: center;
            position: relative;
            overflow: hidden;
        }

        .preview-empty {
            text-align: center;
            color: #6b7280;
        }

        .preview-empty i {
            font-size: 80px;
            color: #a855f7;
            opacity: 0.3;
            margin-bottom: 20px;
        }

        .preview-empty h6 {
            font-size: 16px;
            font-weight: 400;
            color: #9ca3af;
        }

        .preview-image-container {
            width: 100%;
            height: 100%;
            display: flex;
            flex-direction: column;
            padding: 24px;
        }

        .preview-success-badge {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 8px 16px;
            background: rgba(16, 185, 129, 0.1);
            border: 1px solid #10b981;
            border-radius: 24px;
            color: #10b981;
            font-size: 13px;
            font-weight: 500;
            margin-bottom: 16px;
            align-self: center;
        }

        .preview-image {
            flex: 1;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 16px;
        }

        .preview-image img {
            max-width: 100%;
            max-height: 100%;
            border-radius: 12px;
            object-fit: contain;
        }

        .preview-actions {
            display: flex;
            gap: 12px;
            justify-content: center;
        }

        .preview-btn {
            padding: 10px 24px;
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
            background: #1a1a1a;
            color: #fff;
            border: 1px solid #2a2a2a;
        }

        .btn-download:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 16px rgba(16, 185, 129, 0.3);
        }

        .btn-share:hover {
            border-color: #a855f7;
        }

        .left-content::-webkit-scrollbar {
            width: 6px;
        }

        .left-content::-webkit-scrollbar-track {
            background: #0a0a0a;
        }

        .left-content::-webkit-scrollbar-thumb {
            background: #2a2a2a;
            border-radius: 3px;
        }

        .left-content::-webkit-scrollbar-thumb:hover {
            background: #404040;
        }

        html {
            --bg-main: #ffffff;
            --bg-sidebar: #ffffff;
            --bg-panel: #f9fafb;
            --bg-input: #ffffff;
            --bg-border: #e5e7eb;

            --text-main: #111827;
            --text-secondary: #374151;
            --text-muted: #6b7280;

            --accent: #7c3aed;
            --accent-hover: rgba(124, 58, 237, 0.12);
        }

        html:not([data-theme="dark"]) .creative-ai-wrapper {
            background: var(--bg-main) !important;
        }

        html:not([data-theme="dark"]) .left-sidebar {
            background: var(--bg-sidebar) !important;
            color: var(--text-main) !important;
            border-right: 1px solid var(--bg-border) !important;
        }

        html:not([data-theme="dark"]) .top-tabs {
            background: var(--bg-sidebar) !important;
            border-bottom: 1px solid var(--bg-border) !important;
        }

        html:not([data-theme="dark"]) .tab-btn {
            color: var(--text-main) !important;
            border: 1px solid var(--accent) !important;
        }

        html:not([data-theme="dark"]) .tab-btn:hover {
            background: var(--accent-hover) !important;
        }

        html:not([data-theme="dark"]) .tab-btn.active {
            background: linear-gradient(135deg, var(--accent), #5b21b6) !important;
            color: #fff !important;
        }

        html:not([data-theme="dark"]) .step-title,
        html:not([data-theme="dark"]) .upload-description {
            color: var(--text-main) !important;
        }

        html:not([data-theme="dark"]) .prompt-label {
            color: var(--text-secondary) !important;
        }

        html:not([data-theme="dark"]) .prompt-textarea-wrapper {
            background: var(--bg-input) !important;
            border-color: var(--accent) !important;
        }

        html:not([data-theme="dark"]) .prompt-textarea {
            color: var(--text-main) !important;
        }

        html:not([data-theme="dark"]) .prompt-textarea::placeholder {
            color: var(--text-muted) !important;
        }

        html:not([data-theme="dark"]) .prompt-icon-btn {
            background: var(--bg-input) !important;
            border: 1px solid var(--bg-border) !important;
            color: var(--text-muted) !important;
        }

        html:not([data-theme="dark"]) .upload-area {
            background: var(--bg-panel) !important;
            border-color: var(--bg-border) !important;
        }

        html:not([data-theme="dark"]) .upload-area:hover {
            background: var(--bg-input) !important;
            border-color: var(--accent) !important;
        }

        html:not([data-theme="dark"]) .upload-placeholder .main-text {
            color: var(--text-main) !important;
        }

        html:not([data-theme="dark"]) .upload-placeholder .sub-text {
            color: var(--text-muted) !important;
        }

        html:not([data-theme="dark"]) .config-label {
            color: var(--text-secondary) !important;
        }

        html:not([data-theme="dark"]) .ratio-option,
        html:not([data-theme="dark"]) .format-option {
            background: var(--bg-input) !important;
            color: var(--text-secondary) !important;
            border-color: var(--bg-border) !important;
        }

        html:not([data-theme="dark"]) .ratio-option.active {
            background: var(--accent) !important;
            color: #fff !important;
        }

        html:not([data-theme="dark"]) .format-option.active {
            background: var(--text-main) !important;
            color: #fff !important;
        }

        html:not([data-theme="dark"]) .generate-section {
            background: var(--bg-sidebar) !important;
            border-top: 1px solid var(--bg-border) !important;
        }

        html:not([data-theme="dark"]) .generate-btn.enabled {
            background: linear-gradient(135deg, var(--accent), #5b21b6) !important;
            color: #fff !important;
        }

        html:not([data-theme="dark"]) .play-btn {
            background: var(--bg-input) !important;
            border-color: var(--bg-border) !important;
            color: var(--text-secondary) !important;
        }

        html:not([data-theme="dark"]) .right-panel {
            background: var(--bg-main) !important;
        }

        html:not([data-theme="dark"]) .preview-area {
            background: linear-gradient(135deg,
                    rgba(124, 58, 237, 0.08),
                    rgba(91, 33, 182, 0.08)) !important;
            border-color: rgba(124, 58, 237, 0.35) !important;
        }

        html:not([data-theme="dark"]) .preview-empty h6 {
            color: var(--text-secondary) !important;
        }

        html:not([data-theme="dark"]) .btn-share {
            background: var(--bg-input) !important;
            color: var(--text-main) !important;
            border-color: var(--bg-border) !important;
        }
    </style>

    <div class="creative-ai-wrapper">
        <div class="left-sidebar">
            <div class="top-tabs">
                <a href="{{ route('ai.photoshoot.index') }}" class="tab-btn">
                    <i class="bi bi-camera-fill"></i>
                    Photoshoot
                </a>
                <button class="tab-btn active">
                    <i class="bi bi-stars"></i>
                    Creative
                </button>
            </div>

            <div class="left-content">
                <div class="step-section">
                    <div class="step-title">1. Describe Your Vision</div>

                    <label class="prompt-label">Enter your prompt</label>

                    <div class="prompt-textarea-wrapper">
                        <textarea class="prompt-textarea" id="promptInput"
                            placeholder="e.g., A female model wearing the product, standing on a futuristic city street at night, neon lights reflecting..."></textarea>

                        <div class="prompt-actions">
                            <button class="prompt-icon-btn" title="AI Enhance" id="enhanceBtn">
                                <i class="bi bi-magic"></i>
                            </button>
                            <button class="prompt-icon-btn" title="Copy" id="copyPromptBtn">
                                <i class="bi bi-clipboard"></i>
                            </button>
                        </div>
                    </div>
                </div>

                <div class="step-section">
                    <div class="step-title">2. Upload Image & Configure</div>

                    <p class="upload-description">For best results, upload a clear, full product photo.</p>

                    <div class="upload-area" id="uploadArea">
                        <input type="file" id="imageInput" accept="image/jpeg,image/png,image/jpg" style="display:none;">
                        <div class="upload-placeholder">
                            <i class="bi bi-cloud-arrow-up"></i>
                            <div class="main-text">Drop image or <span class="upload-link" id="browseLink">browse</span>
                            </div>
                            <div class="sub-text">Supports: PNG, JPG</div>
                        </div>
                    </div>

                    <div class="config-row">
                        <label class="config-label">Aspect Ratio</label>
                        <div class="ratio-grid">
                            <div class="ratio-option active" data-ratio="1:1">1:1</div>
                            <div class="ratio-option" data-ratio="4:3">4:3</div>
                            <div class="ratio-option" data-ratio="16:9">16:9</div>
                            <div class="ratio-option" data-ratio="3:4">3:4</div>
                            <div class="ratio-option" data-ratio="9:16">9:16</div>
                        </div>
                    </div>

                    <div class="config-row">
                        <label class="config-label">Output Format</label>
                        <div class="format-grid">
                            <div class="format-option active" data-format="JPEG">JPEG</div>
                            <div class="format-option" data-format="PNG">PNG</div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="generate-section">
                <button class="generate-btn" id="generateBtn" disabled>
                    <i class="bi bi-stars"></i>
                    Generate Creative Image
                </button>
                <button class="play-btn" title="Quick Generate">
                    <i class="bi bi-play-fill"></i>
                </button>
            </div>
        </div>

        <div class="right-panel">
            <div class="preview-area" id="previewArea">
                <div class="preview-empty">
                    <i class="bi bi-image"></i>
                    <h6>Your AI-generated image will appear here.</h6>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            let uploadedImagePath = null;
            let selectedRatio = '1:1';
            let selectedFormat = 'JPEG';

            const Toast = Swal.mixin({
                toast: true,
                position: 'top-end',
                showConfirmButton: false,
                timer: 3000,
                timerProgressBar: true,
            });

            const promptInput = document.getElementById('promptInput');
            promptInput.addEventListener('input', checkFormValid);

            document.querySelectorAll('.ratio-option').forEach(option => {
                option.addEventListener('click', function() {
                    document.querySelectorAll('.ratio-option').forEach(o => o.classList.remove(
                        'active'));
                    this.classList.add('active');
                    selectedRatio = this.dataset.ratio;
                });
            });

            document.querySelectorAll('.format-option').forEach(option => {
                option.addEventListener('click', function() {
                    document.querySelectorAll('.format-option').forEach(o => o.classList.remove(
                        'active'));
                    this.classList.add('active');
                    selectedFormat = this.dataset.format;
                });
            });

            document.getElementById('uploadArea').addEventListener('click', () => document.getElementById(
                'imageInput').click());
            document.getElementById('browseLink').addEventListener('click', (e) => {
                e.stopPropagation();
                document.getElementById('imageInput').click();
            });

            document.getElementById('imageInput').addEventListener('change', function(e) {
                const file = e.target.files[0];
                if (!file) return;

                if (file.size > 10 * 1024 * 1024) {
                    Toast.fire({
                        icon: 'error',
                        title: 'File size must be less than 10MB'
                    });
                    return;
                }

                const formData = new FormData();
                formData.append('image', file);

                Swal.fire({
                    title: 'Uploading...',
                    allowOutsideClick: false,
                    didOpen: () => Swal.showLoading()
                });

                fetch('{{ route('creative.ai.upload') }}', {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        },
                        body: formData
                    })
                    .then(res => res.json())
                    .then(data => {
                        Swal.close();
                        if (data.success) {
                            uploadedImagePath = data.path;
                            const uploadArea = document.getElementById('uploadArea');
                            uploadArea.classList.add('has-file');
                            uploadArea.innerHTML =
                                `<img src="${data.url}" class="upload-preview-img" alt="Uploaded">`;
                            Toast.fire({
                                icon: 'success',
                                title: 'Image uploaded!'
                            });
                            checkFormValid();
                        } else {
                            Toast.fire({
                                icon: 'error',
                                title: data.message || 'Upload failed'
                            });
                        }
                    })
                    .catch(() => {
                        Swal.close();
                        Toast.fire({
                            icon: 'error',
                            title: 'Upload failed'
                        });
                    });
            });

            document.getElementById('copyPromptBtn').addEventListener('click', function() {
                const prompt = promptInput.value;
                if (prompt) {
                    navigator.clipboard.writeText(prompt);
                    Toast.fire({
                        icon: 'success',
                        title: 'Prompt copied!'
                    });
                }
            });

            document.getElementById('enhanceBtn').addEventListener('click', function() {
                const currentPrompt = promptInput.value.trim();
                if (!currentPrompt) {
                    Toast.fire({
                        icon: 'warning',
                        title: 'Please enter a prompt first'
                    });
                    return;
                }

                const enhanced =
                    `${currentPrompt}, ultra detailed, professional photography, high quality, 8k resolution, cinematic lighting`;
                promptInput.value = enhanced;
                Toast.fire({
                    icon: 'success',
                    title: 'Prompt enhanced!'
                });
                checkFormValid();
            });

            document.getElementById('generateBtn').addEventListener('click', function() {
                const prompt = promptInput.value.trim();

                if (!prompt || prompt.length < 10) {
                    Toast.fire({
                        icon: 'warning',
                        title: 'Please enter a detailed prompt (min 10 characters)'
                    });
                    return;
                }

                const data = {
                    prompt: prompt,
                    uploaded_image: uploadedImagePath,
                    aspect_ratio: selectedRatio,
                    output_format: selectedFormat,
                };

                Swal.fire({
                    title: 'Generating Creative Image...',
                    html: 'Please wait while AI creates your vision',
                    allowOutsideClick: false,
                    didOpen: () => Swal.showLoading()
                });

                fetch('{{ route('creative.ai.generate') }}', {
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
                                title: 'Image generated!'
                            });
                            displayResult(result.generation);
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
                            title: 'Generation failed'
                        });
                    });
            });

            function checkFormValid() {
                const prompt = promptInput.value.trim();
                const btn = document.getElementById('generateBtn');

                if (prompt.length >= 10) {
                    btn.disabled = false;
                    btn.classList.add('enabled');
                } else {
                    btn.disabled = true;
                    btn.classList.remove('enabled');
                }
            }

            function displayResult(generation) {
                const area = document.getElementById('previewArea');
                const imageUrl = generation.generated_images && generation.generated_images[0] ?
                    generation.generated_images[0] :
                    '/placeholder.jpg';

                area.innerHTML = `
            <div class="preview-image-container">
                <div class="preview-success-badge">
                    <i class="bi bi-check-circle-fill"></i>
                    Image generated successfully!
                </div>
                <div class="preview-image">
                    <img src="${imageUrl}" alt="Generated">
                </div>
                <div class="preview-actions">
                    <button class="preview-btn btn-download" onclick="downloadImage(${generation.id})">
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

            window.downloadImage = function(genId) {
                window.location.href = `/creative-ai/download/${genId}`;
            };
        });
    </script>
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const themeIcon = document.getElementById('theme-icon');
            const themeLinks = document.querySelectorAll('.dropdown-item[data-theme]');

            if (!localStorage.getItem('theme')) {
                localStorage.setItem('theme', 'light');
            }

            const setTheme = (theme) => {
                document.documentElement.setAttribute('data-theme', theme);

                if (theme === 'dark') {
                    themeIcon.classList.replace('fa-sun', 'fa-moon');
                } else {
                    themeIcon.classList.replace('fa-moon', 'fa-sun');
                }

                localStorage.setItem('theme', theme);
            };

            const savedTheme = localStorage.getItem('theme') || 'light';
            setTheme(savedTheme);

            themeLinks.forEach(link => {
                link.addEventListener('click', (e) => {
                    e.preventDefault();
                    const newTheme = link.getAttribute('data-theme');
                    setTheme(newTheme);
                });
            });
        });
    </script>
@endsection
