@extends('user.app')
@section('title', 'AI Photo Shoots')
@section('content')

    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        .ai-photoshoot-wrapper {
            display: flex;
            height: 100vh;
            background: #000;
            overflow: hidden;
        }

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
            gap: 0;
            padding: 12px 20px;
            border-bottom: 1px solid #1f1f1f;
            background: #0a0a0a;
            color: #fff
        }

        .tab-btn {
            flex: 1;
            padding: 10px 20px;
            background: transparent;
            border: none;
            color: #6b7280;
            font-size: 14px;
            font-weight: 500;
            cursor: pointer;
            border-radius: 8px;
            text-decoration: none !important;
            transition: all 0.2s;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
        }

        .tab-btn.active {
            background: linear-gradient(135deg, #a855f7 0%, #7c3aed 100%);
            color: #fff;
        }

        .tab-btn:hover:not(.active) {
            background: #1a1a1a;
            color: #9ca3af;
        }

        .left-content {
            padding: 24px;
            flex: 1;
            overflow-y: auto;
        }

        .step-section {
            margin-bottom: 32px;
        }

        .step-title {
            font-size: 15px;
            font-weight: 600;
            color: #fff;
            margin-bottom: 16px;
        }

        .dropdowns-row {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 12px;
            margin-bottom: 20px;
        }

        .dropdown-group {
            display: flex;
            flex-direction: column;
            gap: 6px;
        }

        .dropdown-label {
            font-size: 12px;
            color: #9ca3af;
            font-weight: 500;
        }

        .dropdown-select {
            width: 100%;
            padding: 10px 5px;
            background: #1a1a1a;
            border: 1px solid #2a2a2a;
            border-radius: 6px;
            color: #fff;
            font-size: 13px;
            cursor: pointer;
            transition: all 0.2s;
        }

        .dropdown-select:hover {
            border-color: #404040;
        }

        .dropdown-select:focus {
            outline: none;
            border-color: #a855f7;
        }

        .shoot-type-row {
            display: flex;
            gap: 10px;
            margin-bottom: 24px;
        }

        .shoot-type-pill {
            padding: 8px 20px;
            background: #1a1a1a;
            border: 1px solid #2a2a2a;
            border-radius: 20px;
            color: #9ca3af;
            font-size: 13px;
            font-weight: 500;
            cursor: pointer;
            transition: all 0.2s;
        }

        .shoot-type-pill:hover {
            border-color: #a855f7;
            color: #fff;
        }

        .shoot-type-pill.active {
            background: linear-gradient(135deg, #a855f7 0%, #7c3aed 100%);
            border-color: #a855f7;
            color: #fff;
        }

        .model-designs-area {
            background: #0f0f0f;
            border: 1px dashed #2a2a2a;
            border-radius: 12px;
            padding: 40px 20px;
            text-align: center;
            margin-bottom: 24px;
            min-height: 200px;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-direction: column;
        }

        .model-designs-area.has-models {
            padding: 16px;
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 12px;
        }

        .model-placeholder {
            color: #6b7280;
        }

        .model-placeholder i {
            font-size: 48px;
            margin-bottom: 12px;
            opacity: 0.5;
        }

        .model-placeholder p {
            font-size: 14px;
            font-weight: 500;
            margin-bottom: 4px;
        }

        .model-card {
            aspect-ratio: 1;
            border-radius: 10px;
            overflow: hidden;
            cursor: pointer;
            border: 2px solid transparent;
            transition: all 0.2s;
            position: relative;
            background: #1a1a1a;
        }

        .model-card:hover {
            border-color: #a855f7;
            transform: scale(1.02);
        }

        .model-card.selected {
            border-color: #a855f7;
            box-shadow: 0 0 0 4px rgba(168, 85, 247, 0.2);
        }

        .model-card img {
            width: 100%;
            height: 100%;
            object-fit: cover;
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
            height: auto;
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
            display: flex;
            gap: 16px;
            margin-bottom: 20px;
        }

        .config-group {
            flex: 1;
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
            padding: 8px;
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
            background: #a855f7;
            border-color: #a855f7;
            color: #fff;
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
            background: linear-gradient(135deg, #a855f7 0%, #7c3aed 100%);
            border: none;
            border-radius: 8px;
            color: #fff;
            font-size: 14px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.2s;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
        }

        .generate-btn:hover:not(:disabled) {
            transform: translateY(-1px);
            box-shadow: 0 8px 20px rgba(168, 85, 247, 0.4);
        }

        .generate-btn:disabled {
            opacity: 0.4;
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
            font-weight: 500;
            color: #9ca3af;
            margin-bottom: 8px;
        }

        .preview-empty p {
            font-size: 14px;
            color: #6b7280;
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

        html[data-theme="light"] body {
            background: #f8fafc !important;
        }

        html[data-theme="light"] .ai-photoshoot-wrapper {
            background: #f8fafc !important;
        }

        html[data-theme="light"] .left-sidebar {
            background: #ffffff !important;
            color: #111827 !important;
            border-right: 1px solid #e5e7eb !important;
        }

        html[data-theme="light"] .top-tabs {
            background: #ffffff !important;
            border-bottom: 1px solid #e5e7eb !important;
        }

        html[data-theme="light"] .tab-btn {
            color: #6b7280 !important;
        }

        html[data-theme="light"] .tab-btn:hover {
            background: #f3f4f6 !important;
        }

        html[data-theme="light"] .left-content {
            background: #ffffff !important;
        }

        html[data-theme="light"] .step-title {
            color: #111827 !important;
        }

        html[data-theme="light"] .dropdown-label,
        html[data-theme="light"] .config-label {
            color: #374151 !important;
        }

        html[data-theme="light"] .dropdown-select {
            background: #ffffff !important;
            color: #111827 !important;
            border: 1px solid #d1d5db !important;
        }

        html[data-theme="light"] .shoot-type-pill {
            background: #f3f4f6 !important;
            color: #374151 !important;
            border-color: #d1d5db !important;
        }

        html[data-theme="light"] .shoot-type-pill.active {
            background: linear-gradient(135deg, #7c3aed 0%, #5b21b6 100%) !important;
            color: #ffffff !important;
        }

        html[data-theme="light"] .model-designs-area,
        html[data-theme="light"] .upload-area {
            background: #f9fafb !important;
            border-color: #d1d5db !important;
        }

        html[data-theme="light"] .model-placeholder {
            color: #6b7280 !important;
        }

        html[data-theme="light"] .ratio-option,
        html[data-theme="light"] .format-option {
            background: #ffffff !important;
            color: #374151 !important;
            border-color: #d1d5db !important;
        }

        html[data-theme="light"] .ratio-option.active {
            background: #7c3aed !important;
            border-color: #7c3aed !important;
            color: #ffffff !important;
        }

        html[data-theme="light"] .format-option.active {
            background: #111827 !important;
            color: #ffffff !important;
        }

        html[data-theme="light"] .generate-section {
            background: #ffffff !important;
            border-top: 1px solid #e5e7eb !important;
        }

        html[data-theme="light"] .generate-btn {
            background: linear-gradient(135deg, #7c3aed 0%, #5b21b6 100%) !important;
        }

        html[data-theme="light"] .play-btn {
            background: #f3f4f6 !important;
            color: #374151 !important;
            border-color: #d1d5db !important;
        }

        html[data-theme="light"] .right-panel {
            background: #f8fafc !important;
        }

        html[data-theme="light"] .preview-area {
            background: linear-gradient(135deg,
                    rgba(124, 58, 237, 0.08) 0%,
                    rgba(91, 33, 182, 0.08) 100%) !important;
            border-color: rgba(124, 58, 237, 0.35) !important;
        }

        html[data-theme="light"] .preview-empty {
            color: #6b7280 !important;
        }

        html[data-theme="light"] .btn-share {
            background: #ffffff !important;
            color: #111827 !important;
            border: 1px solid #d1d5db !important;
        }

        html[data-theme="light"] .btn-share:hover {
            border-color: #7c3aed !important;
        }

        .tab-btn {
            color: #ffffff !important;
            border: 1px solid #a855f7 !important;
            background: transparent !important;
        }

        .tab-btn:hover {
            background: rgba(168, 85, 247, 0.15) !important;
            color: #ffffff !important;
        }

        .tab-btn.active {
            background: linear-gradient(135deg, #a855f7 0%, #7c3aed 100%) !important;
            color: #ffffff !important;
            border: 1px solid #a855f7 !important;
        }

        .tab-btn {
            color: #ffffff !important;
            border: 1px solid #a855f7 !important;
            background: transparent !important;
        }

        .tab-btn:hover {
            background: rgba(168, 85, 247, 0.15) !important;
            color: #ffffff !important;
        }

        .tab-btn.active {
            background: linear-gradient(135deg, #a855f7 0%, #7c3aed 100%) !important;
            color: #ffffff !important;
            border-color: #a855f7 !important;
        }

        html[data-theme="light"] .tab-btn {
            color: #000000 !important;
            border: 1px solid #7c3aed !important;
            background: transparent !important;
        }

        html[data-theme="light"] .tab-btn:hover {
            background: rgba(124, 58, 237, 0.12) !important;
            color: #000000 !important;
        }

        html[data-theme="light"] .tab-btn.active {
            background: linear-gradient(135deg, #7c3aed 0%, #5b21b6 100%) !important;
            color: #ffffff !important;
            border-color: #7c3aed !important;
        }

        .top-tabs {
            gap: 12px !important;
        }

        .tab-btn {
            padding: 6px 14px !important;
            font-size: 12px !important;
            border-radius: 6px !important;
        }

        .dropdown-select {
            padding-right: 12px !important;
            background-position: right 19px center !important;
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 20 20' fill='gray'%3E%3Cpath d='M5 7l5 5 5-5'/%3E%3C/svg%3E");
            background-repeat: no-repeat;
            background-size: 14px;
        }

        /* ===== MODEL SLIDER UI (STEP 3) ===== */
        .model-slider-wrapper {
            position: relative;
            background: #0f0f0f;
            border-radius: 14px;
            padding: 14px 48px;
            border: 1px solid #1f1f1f;
        }

        .model-slider {
            display: flex;
            gap: 12px;
            overflow-x: auto;
            scroll-behavior: smooth;
            scrollbar-width: none;
        }

        .model-slider::-webkit-scrollbar {
            display: none;
        }

        .model-card {
            min-width: 110px;
            height: 110px;
            border-radius: 12px;
            overflow: hidden;
            cursor: pointer;
            border: 2px solid transparent;
            background: #1a1a1a;
            transition: all 0.2s;
        }

        .model-card img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .model-card:hover {
            transform: scale(1.05);
            border-color: #a855f7;
        }

        .model-card.selected {
            border-color: #a855f7;
            box-shadow: 0 0 0 3px rgba(168, 85, 247, 0.4);
        }

        .slider-btn {
            position: absolute;
            top: 50%;
            transform: translateY(-50%);
            width: 36px;
            height: 36px;
            border-radius: 50%;
            background: #111;
            border: 1px solid #2a2a2a;
            color: #fff;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            z-index: 10;
        }

        .slider-btn:hover {
            background: #a855f7;
        }

        .slider-btn.left {
            left: 10px;
        }

        .slider-btn.right {
            right: 10px;
        }

        html[data-theme="light"] .model-slider-wrapper {
            background: #f9fafb !important;
            border: 1px solid #d1d5db !important;
        }

        html[data-theme="light"] .model-card {
            background: #ffffff !important;
            border-color: #e5e7eb !important;
        }

        html[data-theme="light"] .model-card:hover {
            border-color: #7c3aed !important;
        }

        html[data-theme="light"] .model-card.selected {
            border-color: #7c3aed !important;
            box-shadow: 0 0 0 3px rgba(124, 58, 237, 0.25) !important;
        }

        html[data-theme="light"] .slider-btn {
            background: #ffffff !important;
            border: 1px solid #d1d5db !important;
            color: #111827 !important;
        }

        html[data-theme="light"] .slider-btn:hover {
            background: #7c3aed !important;
            color: #ffffff !important;
        }

        html[data-theme="light"] .step-title {
            color: #111827 !important;
        }
    </style>

    <div class="ai-photoshoot-wrapper">
        <div class="left-sidebar">
            <div class="top-tabs">
                <button class="tab-btn active" style="text-decoration: none;">
                    <i class="bi bi-camera-fill"></i>
                    Photoshoot
                </button>

                <a href="{{ route('creative.ai.index') }}" class="tab-btn" style="text-decoration: none;">
                    <i class="bi bi-stars"></i> Creative
                </a>
            </div>

            <div class="left-content">
                <div class="step-section">
                    <div class="step-title">1. Select Your Style</div>

                    <div class="dropdowns-row">
                        <div class="dropdown-group">
                            <label class="dropdown-label">Industry</label>
                            <select class="dropdown-select" id="industry">
                                <option value="">-- Select Industry --</option>
                                <option value="Jewellery" selected>Jewellery</option>
                                <option value="Fashion">Fashion</option>
                                <option value="Accessories">Accessories</option>
                            </select>
                        </div>

                        <div class="dropdown-group">
                            <label class="dropdown-label">Category</label>
                            <select class="dropdown-select" id="category">
                                <option value="">-- Select Category --</option>
                                <option value="Women Jewellery" selected>Women Jewellery</option>
                                <option value="Men Jewellery">Men Jewellery</option>
                                <option value="Kids Jewellery">Kids Jewellery</option>
                            </select>
                        </div>

                        <div class="dropdown-group">
                            <label class="dropdown-label">Product Type</label>
                            <select class="dropdown-select" id="productType">
                                <option value="">-- Select Item --</option>
                                <option value="Necklace" selected>Necklace</option>
                                <option value="Earrings">Earrings</option>
                                <option value="Ring">Ring</option>
                                <option value="Bracelet">Bracelet</option>
                                <option value="Pendant">Pendant</option>
                                <option value="Mangalsutra">Mangalsutra</option>
                            </select>
                        </div>
                    </div>
                </div>

                <div class="step-section">
                    <div class="step-title">2. Select Shoot Type</div>

                    <div class="shoot-type-row">
                        <div class="shoot-type-pill active" data-type="Classic">Classic</div>
                        <div class="shoot-type-pill" data-type="Lifestyle">Lifestyle</div>
                        <div class="shoot-type-pill" data-type="Luxury">Luxury</div>
                        <div class="shoot-type-pill" data-type="Outdoor">Outdoor</div>
                    </div>
                </div>

                <div class="step-section">
                    <div class="step-title">3. Choose Model Design</div>

                    <div class="model-slider-wrapper">
                        <button class="slider-btn left" id="modelPrevBtn">
                            <i class="bi bi-chevron-left"></i>
                        </button>

                        <div class="model-slider" id="modelDesignsArea"></div>

                        <button class="slider-btn right" id="modelNextBtn">
                            <i class="bi bi-chevron-right"></i>
                        </button>
                    </div>
                </div>


                <div class="step-section">
                    <div class="step-title">4. Upload Image & Configure</div>

                    <p style="font-size: 12px; color: #6b7280; margin-bottom: 12px;">
                        For best results, upload a clear, full product photo
                    </p>

                    <div class="upload-area" id="uploadArea">
                        <input type="file" id="imageInput" accept="image/jpeg,image/png,image/jpg"
                            style="display:none;">
                        <div class="upload-placeholder">
                            <i class="bi bi-cloud-arrow-up"></i>
                            <div class="main-text">Drop image or <span class="upload-link" id="browseLink">browse</span>
                            </div>
                            <div class="sub-text">Supports: PNG, JPG</div>
                        </div>
                    </div>

                    <div class="config-row">
                        <div class="config-group">
                            <label class="config-label">Aspect Ratio</label>
                            <div class="ratio-grid">
                                <div class="ratio-option active" data-ratio="1:1">1:1</div>
                                <div class="ratio-option" data-ratio="4:3">4:3</div>
                                <div class="ratio-option" data-ratio="16:9">16:9</div>
                                <div class="ratio-option" data-ratio="3:4">3:4</div>
                                <div class="ratio-option" data-ratio="9:16">9:16</div>
                            </div>
                        </div>
                    </div>

                    <div class="config-row">
                        <div class="config-group">
                            <label class="config-label">Output Format</label>
                            <div class="format-grid">
                                <div class="format-option active" data-format="JPEG">JPEG</div>
                                <div class="format-option" data-format="PNG">PNG</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="generate-section">
                <button class="generate-btn" id="generateBtn" disabled>
                    <i class="bi bi-stars"></i>
                    Start Product Shoot
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
            let selectedModelDesign = null;
            let selectedShootType = 'Classic';
            let selectedRatio = '1:1';
            let selectedFormat = 'JPEG';
            let allModelDesigns = @json($modelDesigns);

            const Toast = Swal.mixin({
                toast: true,
                position: 'top-end',
                showConfirmButton: false,
                timer: 3000,
                timerProgressBar: true,
            });

            loadModelDesigns(selectedShootType);

            document.querySelectorAll('.shoot-type-pill').forEach(pill => {
                pill.addEventListener('click', function() {
                    document.querySelectorAll('.shoot-type-pill').forEach(p => p.classList.remove(
                        'active'));
                    this.classList.add('active');
                    selectedShootType = this.dataset.type;
                    selectedModelDesign = null;
                    loadModelDesigns(selectedShootType);
                    checkFormValid();
                });
            });

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

                fetch('{{ route('ai.photoshoot.upload') }}', {
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

            document.getElementById('generateBtn').addEventListener('click', function() {

                console.clear();

                console.group("🚀 GENERATE BUTTON CLICKED");

                console.log("Uploaded Image Path:", uploadedImagePath);
                console.log("Selected Model ID:", selectedModelDesign);
                console.log("Shoot Type:", selectedShootType);
                console.log("Aspect Ratio:", selectedRatio);
                console.log("Format:", selectedFormat);

                if (!uploadedImagePath || !selectedModelDesign) {
                    console.warn("❌ Validation Failed - Missing Data");

                    console.groupEnd();

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

                console.log("📤 Request Payload:", data);

                console.groupEnd();

                Swal.fire({
                    title: 'Generating...',
                    html: 'Creating your perfect shot',
                    allowOutsideClick: false,
                    didOpen: () => Swal.showLoading()
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

                        console.group("📥 API RESPONSE");
                        console.log("Full Response:", result);

                        if (result.console_logs) {
                            console.group("🧠 BACKEND DEBUG LOGS");
                            result.console_logs.forEach(log => console.log(log));
                            console.groupEnd();
                        }

                        console.groupEnd();

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
                    .catch(error => {

                        console.group("🔥 FETCH ERROR");
                        console.error(error);
                        console.groupEnd();

                        Swal.close();

                        Toast.fire({
                            icon: 'error',
                            title: 'Generation failed'
                        });
                    });
            });


            function loadModelDesigns(shootType) {
                const area = document.getElementById('modelDesignsArea');

                const categoryMap = {
                    'Classic': 'classic',
                    'Lifestyle': 'lifestyle',
                    'Luxury': 'luxury',
                    'Outdoor': 'outdoor'
                };

                const filtered = allModelDesigns.filter(m =>
                    m.category.toLowerCase() === categoryMap[shootType].toLowerCase()
                );

                area.innerHTML = '';

                if (filtered.length === 0) {
                    area.innerHTML = `<div style="color:#6b7280;font-size:14px">No models available</div>`;
                    return;
                }

                filtered.forEach(model => {
                    const card = document.createElement('div');
                    card.className = 'model-card';
                    card.dataset.modelId = model.id;

                    card.innerHTML = `<img src="${model.thumbnail}" alt="${model.name}">`;

                    card.addEventListener('click', function() {
                        document.querySelectorAll('.model-card').forEach(c => c.classList.remove(
                            'selected'));
                        this.classList.add('selected');
                        selectedModelDesign = model.id;
                        checkFormValid();
                    });

                    area.appendChild(card);
                });
            }


            function checkFormValid() {
                document.getElementById('generateBtn').disabled = !(uploadedImagePath && selectedModelDesign);
            }

            function displayResult(shoot) {
                const area = document.getElementById('previewArea');
                const imageUrl = shoot.generated_images && shoot.generated_images[0] ?
                    shoot.generated_images[0] :
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

            document.getElementById('modelPrevBtn')?.addEventListener('click', () => {
                document.getElementById('modelDesignsArea').scrollLeft -= 180;
            });

            document.getElementById('modelNextBtn')?.addEventListener('click', () => {
                document.getElementById('modelDesignsArea').scrollLeft += 180;
            });

        });
    </script>
@endsection
