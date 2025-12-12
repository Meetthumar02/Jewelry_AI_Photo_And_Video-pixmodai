@extends('user.app')
@section('title', 'AI Studio')
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" rel="stylesheet">
@section('content')

    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box
        }

        .ai-studio-wrapper {
            display: flex;
            height: 100vh;
            background: #000;
            overflow: hidden
        }

        .left-sidebar {
            width: 540px;
            background: #0a0a0a;
            color: #fff;
            overflow-y: auto;
            border-right: 1px solid #1f1f1f;
            display: flex;
            flex-direction: column
        }

        .top-tabs {
            display: flex;
            gap: 12px;
            padding: 12px 20px;
            border-bottom: 1px solid #1f1f1f;
            background: #0a0a0a
        }

        .tab-btn {
            flex: 1;
            padding: 10px 20px;
            background: transparent;
            border: 1px solid #3a3a3a;
            color: #9ca3af;
            font-size: 14px;
            font-weight: 500;
            cursor: pointer;
            border-radius: 8px;
            text-decoration: none;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            transition: all .2s
        }

        .tab-btn.active {
            background: linear-gradient(135deg, #a855f7 0%, #7c3aed 100%);
            color: #fff;
            border-color: #a855f7
        }

        .tab-btn:hover:not(.active) {
            background: rgba(168, 85, 247, .15);
            border-color: #a855f7;
            color: #fff
        }

        .left-content {
            padding: 24px;
            flex: 1;
            overflow-y: auto
        }

        .tab-content {
            display: none
        }

        .tab-content.active {
            display: block
        }

        .step-section {
            margin-bottom: 32px
        }

        .step-title {
            font-size: 15px;
            font-weight: 600;
            color: #fff;
            margin-bottom: 16px
        }

        .dropdowns-row {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 12px;
            margin-bottom: 20px
        }

        .dropdown-group {
            display: flex;
            flex-direction: column;
            gap: 6px
        }

        .dropdown-label {
            font-size: 12px;
            color: #9ca3af;
            font-weight: 500
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
            padding-right: 12px;
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 20 20' fill='gray'%3E%3Cpath d='M5 7l5 5 5-5'/%3E%3C/svg%3E");
            background-repeat: no-repeat;
            background-position: right 19px center;
            background-size: 14px
        }

        .dropdown-select:hover {
            border-color: #404040
        }

        .dropdown-select:focus {
            outline: none;
            border-color: #a855f7
        }

        .shoot-type-row {
            display: flex;
            gap: 10px;
            margin-bottom: 24px
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
            transition: all .2s
        }

        .shoot-type-pill:hover {
            border-color: #a855f7;
            color: #fff
        }

        .shoot-type-pill.active {
            background: linear-gradient(135deg, #a855f7 0%, #7c3aed 100%);
            border-color: #a855f7;
            color: #fff
        }

        .model-slider-wrapper {
            position: relative;
            background: #0f0f0f;
            border-radius: 14px;
            padding: 14px 48px;
            border: 1px solid #1f1f1f;
            margin-bottom: 24px
        }

        .model-slider {
            display: flex;
            gap: 12px;
            overflow-x: auto;
            scroll-behavior: smooth;
            scrollbar-width: none;
            padding-bottom: 6px
        }

        .model-slider::-webkit-scrollbar {
            display: none
        }

        .model-card {
            min-width: 110px;
            height: 110px;
            border-radius: 12px;
            overflow: hidden;
            cursor: pointer;
            border: 2px solid transparent;
            background: #1a1a1a;
            transition: all .2s;
            display: flex;
            align-items: center;
            justify-content: center
        }

        .model-card img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            border-radius: 10px
        }

        .model-card:hover {
            transform: scale(1.05);
            border-color: #a855f7
        }

        .model-card.selected {
            border-color: #a855f7;
            box-shadow: 0 0 0 3px rgba(168, 85, 247, .4)
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
            z-index: 10
        }

        .slider-btn:hover {
            background: #a855f7
        }

        .slider-btn.left {
            left: 10px
        }

        .slider-btn.right {
            right: 10px
        }

        .prompt-label {
            font-size: 12px;
            color: #9ca3af;
            margin-bottom: 8px;
            display: block
        }

        .prompt-textarea-wrapper {
            position: relative;
            border: 2px solid #a855f7;
            border-radius: 12px;
            background: #0f0f0f;
            padding: 16px;
            margin-bottom: 20px
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
            outline: none
        }

        .prompt-textarea::placeholder {
            color: #6b7280
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
            transition: all .2s;
            font-size: 14px
        }

        .prompt-icon-btn:hover {
            border-color: #a855f7;
            color: #fff
        }

        .upload-area {
            background: #0f0f0f;
            border: 2px dashed #2a2a2a;
            border-radius: 12px;
            padding: 32px;
            text-align: center;
            cursor: pointer;
            transition: all .2s;
            margin-bottom: 20px
        }

        .upload-area:hover {
            border-color: #a855f7;
            background: #141414
        }

        .upload-area.has-file {
            border-color: #10b981;
            border-style: solid;
            padding: 0;
            height: auto
        }

        .upload-placeholder {
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 8px
        }

        .upload-placeholder i {
            font-size: 40px;
            color: #a855f7;
            margin-bottom: 8px
        }

        .upload-placeholder .main-text {
            color: #fff;
            font-size: 14px;
            font-weight: 500
        }

        .upload-placeholder .sub-text {
            color: #6b7280;
            font-size: 12px
        }

        .upload-link {
            color: #a855f7;
            text-decoration: underline;
            cursor: pointer
        }

        .upload-preview-img {
            width: 100%;
            max-height: 300px;
            object-fit: contain;
            border-radius: 10px
        }

        .config-row {
            display: flex;
            gap: 16px;
            margin-bottom: 20px
        }

        .config-group {
            flex: 1
        }

        .config-label {
            font-size: 12px;
            color: #9ca3af;
            margin-bottom: 8px;
            display: block
        }

        .ratio-grid {
            display: grid;
            grid-template-columns: repeat(5, 1fr);
            gap: 6px
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
            transition: all .2s
        }

        .ratio-option:hover {
            border-color: #404040;
            color: #fff
        }

        .ratio-option.active {
            background: #a855f7;
            border-color: #a855f7;
            color: #fff
        }

        .format-grid {
            display: flex;
            gap: 8px
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
            transition: all .2s
        }

        .format-option:hover {
            border-color: #404040;
            color: #fff
        }

        .format-option.active {
            background: #fff;
            border-color: #fff;
            color: #000
        }

        .generate-section {
            position: sticky;
            bottom: 0;
            background: #0a0a0a;
            padding: 20px 24px;
            border-top: 1px solid #1f1f1f;
            display: flex;
            align-items: center;
            gap: 12px
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
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px
        }

        .generate-btn:hover:not(:disabled) {
            transform: translateY(-1px);
            box-shadow: 0 8px 20px rgba(168, 85, 247, .4)
        }

        .generate-btn:disabled {
            opacity: .4;
            cursor: not-allowed
        }

        .generate-btn.creative-disabled {
            background: #4a5568;
            color: #9ca3af
        }

        .generate-btn.creative-enabled {
            background: linear-gradient(135deg, #a855f7 0%, #7c3aed 100%);
            color: #fff
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
            transition: all .2s
        }

        .play-btn:hover {
            border-color: #a855f7;
            color: #fff
        }

        .right-panel {
            flex: 1;
            background: #000;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 40px;
            position: relative
        }

        .preview-area {
            width: 100%;
            max-width: 900px;
            height: 100%;
            max-height: 700px;
            border-radius: 16px;
            background: linear-gradient(135deg, rgba(168, 85, 247, .1)0%, rgba(124, 58, 237, .1)100%);
            border: 1px solid rgba(168, 85, 247, .2);
            display: flex;
            align-items: center;
            justify-content: center;
            position: relative;
            overflow: hidden
        }

        .preview-empty {
            text-align: center;
            color: #6b7280
        }

        .preview-empty i {
            font-size: 80px;
            color: #a855f7;
            opacity: .3;
            margin-bottom: 20px
        }

        .preview-empty h6 {
            font-size: 16px;
            font-weight: 500;
            color: #9ca3af;
            margin-bottom: 8px
        }

        .preview-empty p {
            font-size: 14px;
            color: #6b7280
        }

        .preview-image-container {
            width: 100%;
            height: 100%;
            display: flex;
            flex-direction: column;
            padding: 24px
        }

        .preview-success-badge {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 8px 16px;
            background: rgba(16, 185, 129, .1);
            border: 1px solid #10b981;
            border-radius: 24px;
            color: #10b981;
            font-size: 13px;
            font-weight: 500;
            margin-bottom: 16px;
            align-self: center
        }

        .preview-image {
            flex: 1;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 16px
        }

        .preview-image img {
            max-width: 100%;
            max-height: 100%;
            border-radius: 12px;
            object-fit: contain
        }

        .preview-actions {
            display: flex;
            gap: 12px;
            justify-content: center
        }

        .preview-btn {
            padding: 10px 24px;
            border-radius: 8px;
            font-size: 14px;
            font-weight: 500;
            cursor: pointer;
            transition: all .2s;
            display: flex;
            align-items: center;
            gap: 8px;
            border: none
        }

        .btn-download {
            background: linear-gradient(135deg, #10b981 0%, #059669 100%);
            color: #fff
        }

        .btn-share {
            background: #1a1a1a;
            color: #fff;
            border: 1px solid #2a2a2a
        }

        .btn-download:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 16px rgba(16, 185, 129, .3)
        }

        .btn-share:hover {
            border-color: #a855f7
        }

        .left-content::-webkit-scrollbar {
            width: 6px
        }

        .left-content::-webkit-scrollbar-track {
            background: #0a0a0a
        }

        .left-content::-webkit-scrollbar-thumb {
            background: #2a2a2a;
            border-radius: 3px
        }

        .left-content::-webkit-scrollbar-thumb:hover {
            background: #404040
        }

        html[data-theme="light"] .ai-studio-wrapper {
            background: #f8fafc
        }

        html[data-theme="light"] .left-sidebar {
            background: #fff;
            color: #111827;
            border-right: 1px solid #e5e7eb
        }

        html[data-theme="light"] .top-tabs {
            background: #fff;
            border-bottom: 1px solid #e5e7eb
        }

        html[data-theme="light"] .tab-btn {
            background: #fff;
            border: 1px solid #d1d5db;
            color: #374151
        }

        html[data-theme="light"] .tab-btn:hover {
            background: #f3f4f6
        }

        html[data-theme="light"] .tab-btn.active {
            background: linear-gradient(135deg, #7c3aed 0%, #5b21b6 100%);
            color: #fff;
            border-color: #7c3aed
        }

        html[data-theme="light"] .left-content {
            background: #fff
        }

        html[data-theme="light"] .step-title {
            color: #111827
        }

        html[data-theme="light"] .dropdown-label,
        html[data-theme="light"] .config-label,
        html[data-theme="light"] .prompt-label {
            color: #374151
        }

        html[data-theme="light"] .dropdown-select {
            background: #fff;
            border-color: #d1d5db;
            color: #111827
        }

        html[data-theme="light"] .shoot-type-pill {
            background: #fff;
            color: #374151;
            border: 1px solid #d1d5db
        }

        html[data-theme="light"] .shoot-type-pill.active {
            background: linear-gradient(135deg, #7c3aed 0%, #5b21b6 100%);
            color: #fff;
            border-color: #7c3aed
        }

        html[data-theme="light"] .model-slider-wrapper {
            background: #fff;
            border: 1px solid #d1d5db;
            box-shadow: 0 2px 6px rgba(0, 0, 0, .05)
        }

        html[data-theme="light"] .model-card {
            background: #fff;
            border-color: #e5e7eb
        }

        html[data-theme="light"] .model-card:hover {
            border-color: #7c3aed
        }

        html[data-theme="light"] .model-card.selected {
            border-color: #7c3aed;
            box-shadow: 0 0 0 3px rgba(124, 58, 237, .25)
        }

        html[data-theme="light"] .slider-btn {
            background: #fff;
            border: 1px solid #d1d5db;
            color: #4b5563
        }

        html[data-theme="light"] .slider-btn:hover {
            background: #7c3aed;
            color: #fff
        }

        html[data-theme="light"] .prompt-textarea-wrapper {
            background: #fff;
            border-color: #7c3aed
        }

        html[data-theme="light"] .prompt-textarea {
            color: #111827
        }

        html[data-theme="light"] .prompt-icon-btn {
            background: #fff;
            border-color: #d1d5db;
            color: #374151
        }

        html[data-theme="light"] .prompt-icon-btn:hover {
            background: #7c3aed;
            color: #fff
        }

        html[data-theme="light"] .upload-area {
            background: #f9fafb;
            border-color: #d1d5db
        }

        html[data-theme="light"] .upload-placeholder .main-text,
        html[data-theme="light"] .upload-placeholder .sub-text {
            color: #374151
        }

        html[data-theme="light"] .ratio-option,
        html[data-theme="light"] .format-option {
            background: #fff;
            border-color: #d1d5db;
            color: #374151
        }

        html[data-theme="light"] .ratio-option.active,
        html[data-theme="light"] .format-option.active {
            background: #7c3aed;
            color: #fff
        }

        html[data-theme="light"] .generate-section {
            background: #fff;
            border-top: 1px solid #e5e7eb
        }

        html[data-theme="light"] .play-btn {
            background: #fff;
            border: 1px solid #d1d5db;
            color: #374151
        }

        html[data-theme="light"] .play-btn:hover {
            background: #7c3aed;
            color: #fff
        }

        html[data-theme="light"] .right-panel {
            background: #f8fafc
        }

        html[data-theme="light"] .preview-area {
            background: linear-gradient(135deg, rgba(124, 58, 237, .08), rgba(91, 33, 182, .08));
            border-color: rgba(124, 58, 237, .3)
        }

        html[data-theme="light"] .preview-empty {
            color: #374151
        }

        html[data-theme="light"] .btn-share {
            background: #fff;
            border: 1px solid #d1d5db;
            color: #111827
        }

        .style-section {
            margin-bottom: 28px;
        }

        .style-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 14px;
        }

        .style-group {
            display: flex;
            flex-direction: column;
            gap: 6px;
        }

        .style-label {
            font-size: 12px;
            font-weight: 600;
            color: #bababa;
        }

        .style-dropdown {
            position: relative;
            background: #121212;
            border: 1px solid #2d2d2d;
            padding: 10px 12px;
            border-radius: 10px;
            cursor: pointer;
        }

        .style-dropdown:hover {
            border-color: #a855f7;
        }

        .dropdown-selected {
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .dropdown-selected span {
            flex: 1;
            margin-left: 10px;
            color: #fff;
            font-size: 14px;
        }

        .opt-img {
            width: 28px;
            height: 28px;
            border-radius: 6px;
            object-fit: cover;
        }

        .dropdown-menu {
            display: none;
            position: absolute;
            left: 0;
            width: 100%;
            background: #1a1a1a;
            border: 1px solid #2d2d2d;
            border-radius: 10px;
            margin-top: 6px;
            z-index: 20;
        }

        .dropdown-item {
            padding: 10px;
            display: flex;
            align-items: center;
            gap: 8px;
            cursor: pointer;
            color: #d1d1d1;
        }

        .dropdown-item:hover {
            background: #272727;
            color: #fff;
        }

        .dropdown-item.active {
            background: #3a0ca3;
            color: #fff;
        }

        .style-dropdown.open .dropdown-menu {
            display: block;
        }

        html[data-theme="light"] .style-dropdown {
            background: #ffffff !important;
            border: 1px solid #d1d5db !important;
        }

        html[data-theme="light"] .style-dropdown:hover {
            border-color: #7c3aed !important;
        }

        html[data-theme="light"] .dropdown-selected span {
            color: #111827 !important;
        }

        html[data-theme="light"] .dropdown-menu {
            background: #ffffff !important;
            border: 1px solid #d1d5db !important;
        }

        html[data-theme="light"] .dropdown-item {
            color: #374151 !important;
        }

        html[data-theme="light"] .dropdown-item:hover {
            background: #f3f4f6 !important;
            color: #111827 !important;
        }

        html[data-theme="light"] .dropdown-item.active {
            background: #7c3aed !important;
            color: #ffffff !important;
        }

        .style-chip {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 10px 14px;
            background: #ffffff;
            border: 1px solid #e5e7eb;
            border-radius: 12px;
            cursor: pointer;
            transition: 0.2s ease;
        }

        .style-chip:hover {
            border-color: #7c3aed;
            box-shadow: 0 2px 6px rgba(0, 0, 0, 0.05);
        }

        .style-chip img {
            width: 30px;
            height: 30px;
            border-radius: 6px;
            object-fit: cover;
        }

        .style-chip span {
            font-size: 14px;
            font-weight: 500;
            color: #111827;
        }

        html[data-theme="dark"] .style-chip {
            background: #121212;
            border-color: #2d2d2d;
        }

        html[data-theme="dark"] .style-chip span {
            color: #fff;
        }

        .style-dropdown .bi-chevron-down {
            transition: transform 0.25s ease;
        }

        .style-dropdown.open .bi-chevron-down {
            transform: rotate(180deg);
        }

        .scanner-line {
            width: 100%;
            height: 4px;
            margin-top: 30px;
            border-radius: 10px;

            background: linear-gradient(90deg,
                    transparent,
                    #9b4dff,
                    transparent);

            background-position: 200% 0;
            background-size: 200% 100%;

            animation: scannerMove 2.3s linear infinite;
            opacity: 0.75;
        }

        @keyframes scannerMove {
            0% {
                background-position: -200% 0;
            }

            100% {
                background-position: 200% 0;
            }
        }

        html[data-theme="light"] .scanner-line {
            background: linear-gradient(90deg,
                    transparent,
                    #7c3aed,
                    transparent);
        }

        .scanner-line {
            width: 100%;
            height: 4px;
            margin-top: 30px;
            border-radius: 10px;
            background: linear-gradient(90deg,
                    transparent,
                    #9b4dff,
                    transparent);
            background-position: 200% 0;
            background-size: 200% 100%;
            animation: scannerMove 2.3s linear infinite;
            opacity: 0.75;
        }

        @keyframes scannerMove {
            0% {
                background-position: -200% 0;
            }

            100% {
                background-position: 200% 0;
            }
        }

        .preview-area {
            position: relative !important;
            overflow: hidden;
        }

        .scanner-wrapper {
            position: absolute;
            inset: 0;
            z-index: 50;
            pointer-events: none;
        }

        .scanner-line {
            position: absolute;
            left: 0;
            width: 100%;
            height: 4px;
            border-radius: 12px;

            background: linear-gradient(90deg,
                    transparent,
                    #9b4dff,
                    transparent);
            box-shadow: 0 0 12px #9b4dff;

            transform: translateY(-10px);
            opacity: 0.9;

            animation: verticalScan 3s ease-in-out infinite;
        }

        @keyframes verticalScan {
            0% {
                transform: translateY(-10px);
                opacity: 0;
            }

            10% {
                opacity: 1;
            }

            50% {
                transform: translateY(calc(100% - 4px));
                opacity: 1;
            }

            90% {
                opacity: 1;
            }

            100% {
                transform: translateY(-10px);
                opacity: 0;
            }
        }

        html[data-theme="light"] .scanner-line {
            background: linear-gradient(90deg,
                    transparent,
                    #7c3aed,
                    transparent);
            box-shadow: 0 0 12px #7c3aed;
        }
    </style>

    <div class="ai-studio-wrapper">
        <div class="left-sidebar">
            <div class="top-tabs">
                <button class="tab-btn active" data-tab="photoshoot"><i class="bi bi-camera-fill"></i> Photoshoot</button>
                <button class="tab-btn" data-tab="creative"><i class="bi bi-stars"></i> Creative</button>
            </div>

            <div class="left-content">
                {{-- PHOTOSHOOT TAB --}}
                <div class="tab-content active" id="photoshoot-tab">
                    <div class="style-section">
                        <div class="step-title">1. Select Your Style</div>

                        <div class="style-grid">

                            <!-- Industry -->
                            <div class="style-group">
                                <label class="style-label">Industry</label>

                                <div class="style-dropdown" id="industryDropdown">
                                    <div class="dropdown-selected">
                                        <img src="/assets/upload/catlog_studion_image/model_1279825432.png" class="opt-img">
                                        <span>-- Select Industry --</span>
                                        <i class="bi bi-chevron-down"></i>
                                    </div>

                                    <div class="dropdown-menu">
                                        @foreach ($industries as $industry)
                                            <div class="dropdown-item" data-value="{{ $industry->id }}">
                                                <img src="/assets/upload/catlog_studion_image/model_1279825432.png"
                                                    class="opt-img">
                                                {{ $industry->name }}
                                            </div>
                                        @endforeach
                                    </div>
                                </div>

                            </div>

                            <!-- Category -->
                            <div class="style-group">
                                <label class="style-label">Category</label>

                                <div class="style-dropdown" id="categoryDropdown">
                                    <div class="dropdown-selected">
                                        <img src="/assets/upload/catlog_studion_image/model_1279825432.png" class="opt-img">
                                        <span>-- Select Category --</span>
                                        <i class="bi bi-chevron-down"></i>
                                    </div>

                                    <div class="dropdown-menu">
                                        <!-- Categories will be loaded dynamically -->
                                    </div>
                                </div>

                            </div>

                            <!-- Product Type -->
                            <div class="style-group">
                                <label class="style-label">Product Type</label>

                                <div class="style-dropdown" id="productDropdown">
                                    <div class="dropdown-selected">
                                        <img src="/assets/upload/catlog_studion_image/model_1279825432.png" class="opt-img">
                                        <span>-- Select Product Type --</span>
                                        <i class="bi bi-chevron-down"></i>
                                    </div>

                                    <div class="dropdown-menu">
                                        <!-- Product types will be loaded dynamically -->
                                    </div>
                                </div>

                            </div>

                        </div>
                    </div>


                    <div class="step-section">
                        <div class="step-title">2. Select Shoot Type</div>
                        <div class="shoot-type-row">
                            @foreach ($shootTypes as $type)
                                <div class="shoot-type-pill {{ $loop->first ? 'active' : '' }}" data-type="{{ $type->id }}">
                                    {{ $type->name }}
                                </div>
                            @endforeach
                        </div>
                    </div>

                    <div class="step-section">
                        <div class="step-title">3. Choose Model Design</div>
                        <div class="model-slider-wrapper">
                            <button class="slider-btn left" id="photoshoot-modelPrevBtn"><i
                                    class="bi bi-chevron-left"></i></button>
                            <div class="model-slider" id="photoshoot-modelDesignsArea">
                                <div
                                    style="display:flex;flex-direction:column;align-items:center;justify-content:center;width:100%;padding:40px;text-align:center;">
                                    <i class="bi bi-cursor" style="font-size:48px;color:#6b7280;margin-bottom:16px;"></i>
                                    <div style="color:#9ca3af;font-size:14px;font-weight:500;">Select a Style Above</div>
                                </div>
                            </div>
                            <button class="slider-btn right" id="photoshoot-modelNextBtn"><i
                                    class="bi bi-chevron-right"></i></button>
                        </div>
                    </div>

                    <div class="step-section">
                        <div class="step-title">4. Upload Image & Configure</div>
                        <p style="font-size:12px;color:#6b7280;margin-bottom:12px">For best results, upload a clear, full
                            product photo</p>
                        <div class="upload-area" id="photoshoot-uploadArea">
                            <input type="file" id="photoshoot-imageInput" accept="image/jpeg,image/png,image/jpg"
                                style="display:none">
                            <div class="upload-placeholder">
                                <i class="bi bi-cloud-arrow-up"></i>
                                <div class="main-text">Drop image or <span class="upload-link"
                                        id="photoshoot-browseLink">browse</span></div>
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

                {{-- CREATIVE TAB --}}
                <div class="tab-content" id="creative-tab">
                    <div class="step-section">
                        <div class="step-title">1. Describe Your Vision</div>
                        <label class="prompt-label">Enter your prompt</label>
                        <div class="prompt-textarea-wrapper">
                            <textarea class="prompt-textarea" id="creative-promptInput"
                                placeholder="e.g., A female model wearing the product, standing on a futuristic city street at night, neon lights reflecting..."></textarea>
                        </div>
                    </div>

                    <div class="step-section">
                        <div class="step-title">2. Upload Image & Configure</div>
                        <p style="font-size:12px;color:#6b7280;margin-bottom:12px">For best results, upload a clear, full
                            product photo</p>
                        <div class="upload-area" id="creative-uploadArea">
                            <input type="file" id="creative-imageInput" accept="image/jpeg,image/png,image/jpg"
                                style="display:none">
                            <div class="upload-placeholder">
                                <i class="bi bi-cloud-arrow-up"></i>
                                <div class="main-text">Drop image or <span class="upload-link"
                                        id="creative-browseLink">browse</span></div>
                                <div class="sub-text">Supports: PNG, JPG</div>
                            </div>
                        </div>

                        <div class="config-row">
                            <div class="config-group">
                                <label class="config-label">Aspect Ratio</label>
                                <div class="ratio-grid">
                                    <div class="ratio-option" data-ratio="1:1">1:1</div>
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
                                    <div class="format-option" data-format="JPEG">JPEG</div>
                                    <div class="format-option" data-format="PNG">PNG</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div> {{-- left-content --}}

            <div class="generate-section">
                <button class="generate-btn creative-disabled" id="photoshoot-generateBtn" disabled>
                    <i class="bi bi-stars"></i>
                    <span id="generateBtnText">Start Product Shoot</span>
                </button>
                <button class="play-btn" title="Quick Generate"><i class="bi bi-play-fill"></i></button>
            </div>
        </div> {{-- left-sidebar --}}

        <div class="right-panel">
            <div class="preview-area" id="previewArea">
                <div class="preview-empty">
                    <i class="bi bi-image"></i>
                    <h6>Your AI-generated image will appear here.</h6>

                    <!-- ANIMATED SCANNER LINE -->
                    <div class="scanner-line"></div>
                </div>
            </div>
        </div>
    </div>

    {{-- Dependencies --}}
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // Shared constants + helpers
            const Toast = Swal.mixin({
                toast: true,
                position: 'top-end',
                showConfirmButton: false,
                timer: 3000,
                timerProgressBar: true
            });
            const isRoute = (r) => window.location.pathname.includes(r);

            // Function to apply theme based on active tab (must be defined before use)
            function applyTabTheme() {
                const activeTab = document.querySelector('.tab-content.active')?.id;
                if (activeTab === 'creative-tab' || isRoute('/creative-ai')) {
                    document.documentElement.setAttribute('data-theme', 'light');
                } else {
                    // Photoshoot tab - use dark mode (remove light mode)
                    document.documentElement.removeAttribute('data-theme');
                }
            }

            // Tab switching
            document.querySelectorAll('.tab-btn').forEach(btn => {
                btn.addEventListener('click', function () {
                    const tab = this.dataset.tab;
                    document.querySelectorAll('.tab-btn').forEach(b => b.classList.remove(
                        'active'));
                    this.classList.add('active');
                    document.querySelectorAll('.tab-content').forEach(c => c.classList.remove(
                        'active'));
                    document.getElementById(tab + '-tab').classList.add('active');

                    const generateBtnText = document.getElementById('generateBtnText');
                    if (tab === 'photoshoot') {
                        generateBtnText.textContent = 'Start Product Shoot';
                    } else {
                        generateBtnText.textContent = 'Generate Creative Image';
                    }

                    // Apply theme based on active tab
                    applyTabTheme();

                    // Re-apply theme multiple times to ensure it sticks
                    setTimeout(() => applyTabTheme(), 50);
                    setTimeout(() => applyTabTheme(), 200);
                    setTimeout(() => applyTabTheme(), 500);

                    // reset preview
                    document.getElementById('previewArea').innerHTML = `
                                                                            <div class="preview-empty"><i class="bi bi-image"></i><h6>Your AI-generated image will appear here.</h6></div>
                                                                          `;
                    updateGenerateState();
                });
            });

            // Override theme initialization from app.blade.php for AI Studio pages
            if (isRoute('/ai-photoshoot') || isRoute('/creative-ai')) {
                // Wait for app.blade.php to initialize, then override
                setTimeout(() => {
                    applyTabTheme();

                    // Re-apply theme periodically to prevent override
                    setInterval(() => {
                        applyTabTheme();
                    }, 500);
                }, 200);

                // Intercept theme toggle clicks with higher priority
                document.addEventListener('click', function (e) {
                    const themeLink = e.target.closest('.dropdown-item[data-theme]');
                    if (themeLink) {
                        e.preventDefault();
                        e.stopPropagation();
                        e.stopImmediatePropagation();

                        // Immediately re-apply tab theme
                        setTimeout(() => applyTabTheme(), 10);
                        setTimeout(() => applyTabTheme(), 100);

                        // Show message that theme is locked to tab
                        const activeTab = document.querySelector('.tab-content.active')?.id;
                        Toast.fire({
                            icon: 'info',
                            title: activeTab === 'creative-tab' ?
                                'Creative AI uses light mode only' :
                                'Photoshoot uses dark mode only',
                            timer: 2000
                        });
                    }
                }, true); // Use capture phase to intercept before other handlers

                // Also intercept any direct theme attribute changes via MutationObserver
                const observer = new MutationObserver(function (mutations) {
                    mutations.forEach(function (mutation) {
                        if (mutation.type === 'attributes' && mutation.attributeName ===
                            'data-theme') {
                            // Small delay then re-apply tab theme
                            setTimeout(() => applyTabTheme(), 50);
                        }
                    });
                });

                observer.observe(document.documentElement, {
                    attributes: true,
                    attributeFilter: ['data-theme']
                });
            } else {
                // For other pages, set initial theme
                setTimeout(() => {
                    applyTabTheme();
                }, 100);
            }

            document.querySelectorAll(".style-dropdown").forEach(drop => {
                // Toggle this dropdown only
                drop.addEventListener("click", function (e) {
                    e.stopPropagation();

                    // Close others
                    document.querySelectorAll(".style-dropdown").forEach(d => {
                        if (d !== drop) d.classList.remove("open");
                    });

                    // Toggle this one
                    drop.classList.toggle("open");
                });

                // Handle item click - attached to existing static items, but new dynamic ones need listener attached on creation
                drop.querySelectorAll(".dropdown-item").forEach(item => {
                    item.addEventListener("click", function (e) {
                        handleDropdownClick(e, this);
                    });
                });
            });

            // Clicking anywhere outside closes all dropdowns
            document.addEventListener("click", function () {
                document.querySelectorAll(".style-dropdown").forEach(d => d.classList.remove("open"));
            });


            function closeAllDropdowns() {
                document.querySelectorAll(".style-dropdown").forEach(d => d.classList.remove("open"));
            }

            document.addEventListener("click", closeAllDropdowns);


            /* ==================
               PHOTOSHOOT LOGIC
               ================== */
            let photoshootData = {
                uploadedImagePath: null,
                selectedModelDesign: null,
                selectedShootType: '{{ $shootTypes->first()->id ?? 1 }}',
                selectedIndustry: null,
                selectedCategory: null,
                selectedProduct: null,
                selectedRatio: '1:1',
                selectedFormat: 'JPEG',
            };

            // Centralized Dropdown Click Handler (defined early so it can be used in event listeners)
            function handleDropdownClick(e, item) {
                e.stopPropagation();
                const parent = item.closest(".style-dropdown");
                const value = item.dataset.value;
                const name = item.innerText.trim();
                const originalImg = item.querySelector("img");

                // UI Update
                parent.querySelector(".dropdown-selected span").innerText = name;
                if (originalImg) {
                    parent.querySelector(".dropdown-selected img").src = originalImg.src;
                }
                parent.querySelectorAll(".dropdown-item").forEach(i => i.classList.remove("active"));
                item.classList.add("active");
                parent.classList.remove("open");

                // Logic Update
                const id = parent.id;
                if (id === 'industryDropdown') {
                    photoshootData.selectedIndustry = value;
                    fetchCategories(value);
                } else if (id === 'categoryDropdown') {
                    photoshootData.selectedCategory = value;
                    fetchProductTypes(value);
                } else if (id === 'productDropdown') {
                    photoshootData.selectedProduct = value;
                    loadModelDesigns();
                }
            }

            function loadModelDesigns() {
                const area = document.getElementById('photoshoot-modelDesignsArea');
                // Check if all required selections are made
                if (!photoshootData.selectedIndustry || !photoshootData.selectedCategory || !photoshootData
                    .selectedProduct) {
                    area.innerHTML = `
                                            <div style="display:flex;flex-direction:column;align-items:center;justify-content:center;width:100%;padding:40px;text-align:center;">
                                                <i class="bi bi-cursor" style="font-size:48px;color:#6b7280;margin-bottom:16px;"></i>
                                                <div style="color:#9ca3af;font-size:14px;font-weight:500;">Select a Style Above</div>
                                            </div>
                                        `;
                    return;
                }

                area.innerHTML =
                    '<div style="color:#9ca3af;text-align:center;width:100%;padding:20px;">Loading designs...</div>';

                const params = new URLSearchParams({
                    industry_id: photoshootData.selectedIndustry,
                    category_id: photoshootData.selectedCategory,
                    product_type_id: photoshootData.selectedProduct,
                    shoot_type_id: photoshootData.selectedShootType
                });

                fetch(`{{ route('creative.ai.get.model.designs') }}?${params.toString()}`)
                    .then(res => res.json())
                    .then(data => {
                        area.innerHTML = '';
                        const designs = data.modelDesigns || [];

                        if (designs.length === 0) {
                            area.innerHTML = `
                                                    <div style="display:flex;flex-direction:column;align-items:center;justify-content:center;width:100%;padding:40px;text-align:center;">
                                                        <i class="bi bi-inbox" style="font-size:48px;color:#6b7280;margin-bottom:16px;"></i>
                                                        <div style="color:#9ca3af;font-size:14px;font-weight:500;">No designs found for this combination</div>
                                                    </div>
                                                `;
                            return;
                        }

                        designs.forEach((model, idx) => {
                            const card = document.createElement('div');
                            card.className = 'model-card';
                            card.dataset.modelId = model.id;
                            // Ensure thumbnail URL is valid
                            // Use original image
                                const imageUrl = model.image;
                                card.innerHTML = `<img src="${imageUrl}" alt="${model.name}">`;

                                card.addEventListener('click', function() {
                                    document.querySelectorAll(
                                            '#photoshoot-modelDesignsArea .model-card')
                                        .forEach(c => c.classList.remove('selected'));
                                    this.classList.add('selected');
                                    photoshootData.selectedModelDesign = model.id;
                                    checkPhotoshootFormValid();
                                });

                                area.appendChild(card);

                                // Auto-select first if none selected
                                if (idx === 0 && !photoshootData.selectedModelDesign) {
                                    card.classList.add('selected');
                                    photoshootData.selectedModelDesign = model.id;
                                }
                            });
                            checkPhotoshootFormValid();
                        })
                        .catch(err => {
                            console.error(err);
                            area.innerHTML = `
                                                <div style="display:flex;flex-direction:column;align-items:center;justify-content:center;width:100%;padding:40px;text-align:center;">
                                                    <i class="bi bi-exclamation-circle" style="font-size:48px;color:#ef4444;margin-bottom:16px;"></i>
                                                    <div style="color:#ef4444;font-size:14px;font-weight:500;">Unable to load designs</div>
                                                    <div style="color:#9ca3af;font-size:12px;margin-top:8px;">Please try again</div>
                                                </div>
                                            `;
                        });
                }

                function fetchCategories(industryValue) {
                    fetch(`{{ route('creative.ai.get.categories') }}?industry_id=${industryValue}`)
                        .then(r => r.json())
                        .then(data => {
                            const dropdown = document.querySelector('#categoryDropdown .dropdown-menu');
                            const selectedSpan = document.querySelector(
                                '#categoryDropdown .dropdown-selected span');

                            dropdown.innerHTML = '';
                            // Reset category selection
                            selectedSpan.innerText = '-- Select Category --';
                            photoshootData.selectedCategory = null;

                            // Also reset product dropdown
                            document.querySelector('#productDropdown .dropdown-selected span').innerText =
                                '-- Select Product Type --';
                            document.querySelector('#productDropdown .dropdown-menu').innerHTML = '';
                            photoshootData.selectedProduct = null;

                            if (data.categories && data.categories.length > 0) {
                                data.categories.forEach(cat => {
                                    const div = document.createElement('div');
                                    div.className = 'dropdown-item';
                                    div.dataset.value = cat.id;
                                    div.innerHTML = `
                                                        <img src="/assets/upload/catlog_studion_image/model_1279825432.png" class="opt-img">
                                                        ${cat.name}
                                                    `;
                                    div.addEventListener('click', function(e) {
                                        handleDropdownClick(e, this);
                                    });
                                    dropdown.appendChild(div);
                                });
                            } else {
                                selectedSpan.innerText = 'No Categories';
                            }

                            // Update model designs to show "Select a Style Above"
                            loadModelDesigns();
                        });
                }

                function fetchProductTypes(categoryValue) {
                    if (!categoryValue) {
                        // Clear products
                        const dropdown = document.querySelector('#productDropdown .dropdown-menu');
                        document.querySelector('#productDropdown .dropdown-selected span').innerText =
                            'Select Product Type';
                        dropdown.innerHTML = '';
                        photoshootData.selectedProduct = null;
                        loadModelDesigns();
                        return;
                    }
                    fetch(`{{ route('creative.ai.get.products') }}?category_id=${categoryValue}`)
                        .then(r => r.json())
                        .then(data => {
                            const dropdown = document.querySelector('#productDropdown .dropdown-menu');
                            const selectedSpan = document.querySelector('#productDropdown .dropdown-selected span');

                            dropdown.innerHTML = '';
                            // Reset product selection
                            selectedSpan.innerText = '-- Select Product Type --';
                            photoshootData.selectedProduct = null;

                            if (data.products && data.products.length > 0) {
                                data.products.forEach(prod => {
                                    const div = document.createElement('div');
                                    div.className = 'dropdown-item';
                                    div.dataset.value = prod.id;
                                    div.innerHTML = `
                                                        <img src="/assets/upload/catlog_studion_image/model_1279825432.png" class="opt-img">
                                                        ${prod.name}
                                                    `;
                                    div.addEventListener('click', function(e) {
                                        handleDropdownClick(e, this);
                                    });
                                    dropdown.appendChild(div);
                                });
                            } else {
                                selectedSpan.innerText = 'No Products';
                            }

                            // Update model designs to show "Select a Style Above"
                            loadModelDesigns();
                        });
                }

                // Centralized Dropdown Click Handler
                function handleDropdownClick(e, item) {
                    e.stopPropagation();
                    const parent = item.closest(".style-dropdown");
                    const value = item.dataset.value;
                    const name = item.innerText.trim();
                    const originalImg = item.querySelector("img").src;

                    // UI Update
                    parent.querySelector(".dropdown-selected span").innerText = name;
                    parent.querySelector(".dropdown-selected img").src = originalImg;
                    parent.querySelectorAll(".dropdown-item").forEach(i => i.classList.remove("active"));
                    item.classList.add("active");
                    parent.classList.remove("open");

                    // Logic Update
                    const id = parent.id;
                    if (id === 'industryDropdown') {
                        photoshootData.selectedIndustry = value;
                        fetchCategories(value);
                    } else if (id === 'categoryDropdown') {
                        photoshootData.selectedCategory = value;
                        fetchProductTypes(value);
                    } else if (id === 'productDropdown') {
                        photoshootData.selectedProduct = value;
                        loadModelDesigns();
                    }
                }

                // Initial Load - commented out to require user selection first
                // loadModelDesigns();

                // shoot type pills
                document.querySelectorAll('#photoshoot-tab .shoot-type-pill').forEach(pill => {
                    pill.addEventListener('click', function() {
                        document.querySelectorAll('#photoshoot-tab .shoot-type-pill').forEach(p => p
                            .classList.remove('active'));
                        this.classList.add('active');
                        photoshootData.selectedShootType = this.dataset.type;
                        photoshootData.selectedModelDesign = null;
                        loadModelDesigns();
                        checkPhotoshootFormValid();
                    });
                });

                // ratio / format selection (photoshoot)
                document.querySelectorAll('#photoshoot-tab .ratio-option').forEach(option => {
                    option.addEventListener('click', function() {
                        document.querySelectorAll('#photoshoot-tab .ratio-option').forEach(o => o
                            .classList.remove('active'));
                        this.classList.add('active');
                        photoshootData.selectedRatio = this.dataset.ratio;
                    });
                });
                document.querySelectorAll('#photoshoot-tab .format-option').forEach(option => {
                    option.addEventListener('click', function() {
                        document.querySelectorAll('#photoshoot-tab .format-option').forEach(o => o
                            .classList.remove('active'));
                        this.classList.add('active');
                        photoshootData.selectedFormat = this.dataset.format;
                    });
                });

                // upload handlers (photoshoot)
                document.getElementById('photoshoot-uploadArea').addEventListener('click', () => document
                    .getElementById('photoshoot-imageInput').click());
                document.getElementById('photoshoot-browseLink').addEventListener('click', (e) => {
                    e.stopPropagation();
                    document.getElementById('photoshoot-imageInput').click();
                });

                document.getElementById('photoshoot-imageInput').addEventListener('change', function(e) {
                    const file = e.target.files[0];
                    if (!file) return;
                    if (file.size > 10 * 1024 * 1024) {
                        Toast.fire({
                            icon: 'error',
                            title: 'File size must be less than 10MB'
                        });
                        return;
                    }
                    const fd = new FormData();
                    fd.append('image', file);
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
                            body: fd
                        })
                        .then(r => r.json()).then(data => {
                            Swal.close();
                            if (data.success) {
                                photoshootData.uploadedImagePath = data.path;
                                const uploadArea = document.getElementById('photoshoot-uploadArea');
                                uploadArea.classList.add('has-file');
                                uploadArea.innerHTML =
                                    `<img src="${data.url}" class="upload-preview-img" alt="Uploaded">`;
                                Toast.fire({
                                    icon: 'success',
                                    title: 'Image uploaded!'
                                });
                                checkPhotoshootFormValid();
                            } else {
                                Toast.fire({
                                    icon: 'error',
                                    title: data.message || 'Upload failed'
                                });
                            }
                        }).catch(err => {
                            Swal.close();
                            Toast.fire({
                                icon: 'error',
                                title: 'Upload failed'
                            });
                        });
                });

                // photoshoot generate
                document.getElementById('photoshoot-generateBtn').addEventListener('click', function() {

                    const activeTab = document.querySelector('.tab-btn.active').dataset.tab;

                    /* --------------- PHOTOSHOOT ---------------- */
                    if (activeTab === 'photoshoot') {

                        if (!photoshootData.uploadedImagePath || !photoshootData.selectedModelDesign) {
                            Toast.fire({
                                icon: 'warning',
                                title: 'Please complete all steps'
                            });
                            return;
                        }

                        const payload = {
                            industry: document.getElementById('photoshoot-industry').value,
                            category: document.getElementById('photoshoot-category').value,
                            product_type: document.getElementById('photoshoot-productType').value,
                            shoot_type: photoshootData.selectedShootType,
                            model_design_id: photoshootData.selectedModelDesign,
                            uploaded_image: photoshootData.uploadedImagePath,
                            aspect_ratio: photoshootData.selectedRatio,
                            output_format: photoshootData.selectedFormat
                        };

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
                                body: JSON.stringify(payload)
                            })
                            .then(r => r.json())
                            .then(result => {
                                Swal.close();
                                if (result.success) {
                                    Toast.fire({
                                        icon: 'success',
                                        title: 'Photo shoot completed!'
                                    });
                                    displayResult(result.shoot, 'photoshoot');
                                } else {
                                    Toast.fire({
                                        icon: 'error',
                                        title: result.message || 'Generation failed'
                                    });
                                }
                            })
                            .catch(() => {
                                Swal.close();
                                Toast.fire({
                                    icon: 'error',
                                    title: 'Generation failed'
                                });
                            });

                        return;
                    }

                    /* --------------- CREATIVE ---------------- */
                    if (activeTab === 'creative') {

                        const prompt = creativePromptInput.value.trim();
                        if (prompt.length < 10) {
                            Toast.fire({
                                icon: 'warning',
                                title: 'Please enter a detailed prompt (min 10 chars)'
                            });
                            return;
                        }

                        const payload = {
                            prompt: prompt,
                            uploaded_image: creativeData.uploadedImagePath,
                            aspect_ratio: creativeData.selectedRatio,
                            output_format: creativeData.selectedFormat
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
                                body: JSON.stringify(payload)
                            })
                            .then(r => r.json())
                            .then(result => {
                                Swal.close();
                                if (result.success) {
                                    Toast.fire({
                                        icon: 'success',
                                        title: 'Image generated!'
                                    });
                                    displayResult(result.generation, 'creative');
                                } else {
                                    Toast.fire({
                                        icon: 'error',
                                        title: result.message || 'Generation failed'
                                    });
                                }
                            })
                            .catch(() => {
                                Swal.close();
                                Toast.fire({
                                    icon: 'error',
                                    title: 'Generation failed'
                                });
                            });
                    }
                });


                function checkPhotoshootFormValid() {
                    const hasImage = !!photoshootData.uploadedImagePath ||
                        document.getElementById('photoshoot-uploadArea').classList.contains('has-file');
                    const hasModel = !!photoshootData.selectedModelDesign;
                    document.getElementById('photoshoot-generateBtn').disabled = !(hasImage && hasModel);
                }

                document.getElementById('photoshoot-modelPrevBtn')?.addEventListener('click', () => document
                    .getElementById('photoshoot-modelDesignsArea').scrollLeft -= 180);
                document.getElementById('photoshoot-modelNextBtn')?.addEventListener('click', () => document
                    .getElementById('photoshoot-modelDesignsArea').scrollLeft += 180);

                let creativeData = {
                    uploadedImagePath: null,
                    selectedRatio: null,
                    selectedFormat: null
                };
                const creativePromptInput = document.getElementById('creative-promptInput');

                creativePromptInput.addEventListener('input', checkCreativeFormValid);

                document.querySelectorAll('#creative-tab .ratio-option').forEach(option => {
                    option.addEventListener('click', function() {
                        document.querySelectorAll('#creative-tab .ratio-option').forEach(o => o
                            .classList.remove('active'));
                        this.classList.add('active');
                        creativeData.selectedRatio = this.dataset.ratio;
                        checkCreativeFormValid();
                    });
                });
                document.querySelectorAll('#creative-tab .format-option').forEach(option => {
                    option.addEventListener('click', function() {
                        document.querySelectorAll('#creative-tab .format-option').forEach(o => o
                            .classList.remove('active'));
                        this.classList.add('active');
                        creativeData.selectedFormat = this.dataset.format;
                        checkCreativeFormValid();
                    });
                });

                document.getElementById('creative-uploadArea').addEventListener('click', () => document.getElementById(
                    'creative-imageInput').click());
                document.getElementById('creative-browseLink').addEventListener('click', (e) => {
                    e.stopPropagation();
                    document.getElementById('creative-imageInput').click();
                });

                document.getElementById('creative-imageInput').addEventListener('change', function(e) {
                    const file = e.target.files[0];
                    if (!file) return;
                    if (file.size > 10 * 1024 * 1024) {
                        Toast.fire({
                            icon: 'error',
                            title: 'File size must be less than 10MB'
                        });
                        return;
                    }
                    const fd = new FormData();
                    fd.append('image', file);
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
                            body: fd
                        })
                        .then(r => r.json()).then(data => {
                            Swal.close();
                            if (data.success) {
                                creativeData.uploadedImagePath = data.path;
                                const uploadArea = document.getElementById('creative-uploadArea');
                                uploadArea.classList.add('has-file');
                                uploadArea.innerHTML =
                                    `<img src="${data.url}" class="upload-preview-img" alt="Uploaded">`;
                                Toast.fire({
                                    icon: 'success',
                                    title: 'Image uploaded!'
                                });
                                checkCreativeFormValid();
                            } else {
                                Toast.fire({
                                    icon: 'error',
                                    title: data.message || 'Upload failed'
                                });
                            }
                        }).catch(() => {
                            Swal.close();
                            Toast.fire({
                                icon: 'error',
                                title: 'Upload failed'
                            });
                        });
                });

                document.getElementById('creative-copyPromptBtn').addEventListener('click', function() {
                    const prompt = creativePromptInput.value;
                    if (prompt) {
                        navigator.clipboard.writeText(prompt);
                        Toast.fire({
                            icon: 'success',
                            title: 'Prompt copied!'
                        })
                    }
                });
                document.getElementById('creative-enhanceBtn').addEventListener('click', function() {
                    const cur = creativePromptInput.value.trim();
                    if (!cur) {
                        Toast.fire({
                            icon: 'warning',
                            title: 'Please enter a prompt first'
                        });
                        return;
                    }
                    creativePromptInput.value =
                        `${cur}, ultra detailed, professional photography, high quality, 8k resolution, cinematic lighting`;
                    Toast.fire({
                        icon: 'success',
                        title: 'Prompt enhanced!'
                    });
                    checkCreativeFormValid();
                });

                document.getElementById('photoshoot-generateBtn').addEventListener('click', function() {
                    const activeTab = document.querySelector('.tab-content.active').id;
                    if (activeTab === 'creative-tab') {
                        const prompt = creativePromptInput.value.trim();
                        if (!prompt || prompt.length < 10) {
                            Toast.fire({
                                icon: 'warning',
                                title: 'Please enter a detailed prompt (min 10 characters)'
                            });
                            return;
                        }
                        if (!creativeData.selectedRatio) {
                            Toast.fire({
                                icon: 'warning',
                                title: 'Please select an aspect ratio'
                            });
                            return;
                        }
                        if (!creativeData.selectedFormat) {
                            Toast.fire({
                                icon: 'warning',
                                title: 'Please select an output format'
                            });
                            return;
                        }
                        const payload = {
                            prompt: prompt,
                            uploaded_image: creativeData.uploadedImagePath,
                            aspect_ratio: creativeData.selectedRatio,
                            output_format: creativeData.selectedFormat
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
                            body: JSON.stringify(payload)
                        }).then(r => r.json()).then(result => {
                            Swal.close();
                            if (result.success) {
                                Toast.fire({
                                    icon: 'success',
                                    title: 'Image generated!'
                                });
                                displayResult(result.generation, 'creative');
                            } else {
                                Toast.fire({
                                    icon: 'error',
                                    title: result.message || 'Generation failed'
                                });
                            }
                        }).catch(() => {
                            Swal.close();
                            Toast.fire({
                                icon: 'error',
                                title: 'Generation failed'
                            });
                        });
                    }
                });

                function checkCreativeFormValid() {
                    const prompt = creativePromptInput.value.trim();
                    const btn = document.getElementById('photoshoot-generateBtn');
                    if (document.querySelector('.tab-content.active').id === 'creative-tab') {
                        if (prompt.length >= 10 && creativeData.selectedRatio && creativeData.selectedFormat) {
                            btn.disabled = false;
                            btn.classList.remove('creative-disabled');
                            btn.classList.add('creative-enabled');
                        } else {
                            btn.disabled = true;
                            btn.classList.remove('creative-enabled');
                            btn.classList.add('creative-disabled');
                        }
                    }
                }

                // update generate button state when switching tabs or on changes
                function updateGenerateState() {
                    const activeTab = document.querySelector('.tab-content.active').id;
                    const btn = document.getElementById('photoshoot-generateBtn');

                    if (activeTab === 'photoshoot') {
                        checkPhotoshootFormValid(); // KEEP THIS

                    } else {
                        checkCreativeFormValid();
                    }
                }


                // common display result
                function displayResult(result, type) {
                    const area = document.getElementById('previewArea');
                    const imageUrl = (result && result.generated_images && result.generated_images[0]) ? result
                        .generated_images[0] : '/placeholder.jpg';
                    const id = result && result.id ? result.id : '0';
                    const downloadRoute = type === 'photoshoot' ? `/ai-photoshoot/download/${id}` :
                        `/creative-ai/download/${id}`;
                    area.innerHTML = `
                                                                          <div class="preview-image-container">
                                                                            <div class="preview-success-badge"><i class="bi bi-check-circle-fill"></i> Image generated successfully!</div>
                                                                            <div class="preview-image"><img src="${imageUrl}" alt="Generated"></div>
                                                                            <div class="preview-actions">
                                                                              <button class="preview-btn btn-download" onclick="window.location.href='${downloadRoute}'"><i class="bi bi-download"></i> Download</button>
                                                                              <button class="preview-btn btn-share"><i class="bi bi-share"></i> Share</button>
                                                                            </div>
                                                                          </div>
                                                                        `;
                }
                updateGenerateState();
            });

            function displayResult(result, type) {

                // REMOVE scanner animation when result comes
                document.querySelector(".scanner-wrapper")?.remove();

                const area = document.getElementById('previewArea');

                const imageUrl = (result && result.generated_images && result.generated_images[0]) ?
                    result.generated_images[0] :
                    '/placeholder.jpg';

                const id = result && result.id ? result.id : '0';

                const downloadRoute = (type === 'photoshoot') ?
                    `/ai-photoshoot/download/${id}` :
                    `/creative-ai/download/${id}`;

                area.innerHTML = `
                                                                            <div class="preview-image-container">

                                                                                <div class="preview-success-badge">
                                                                                    <i class="bi bi-check-circle-fill"></i> Image generated successfully!
                                                                                </div>

                                                                                <div class="preview-image">
                                                                                    <img src="${imageUrl}" alt="Generated Image">
                                                                                </div>

                                                                                <div class="preview-actions">
                                                                                    <button class="preview-btn btn-download"
                                                                                        onclick="window.location.href='${downloadRoute}'">
                                                                                        <i class="bi bi-download"></i> Download
                                                                                    </button>

                                                                                    <button class="preview-btn btn-share">
                                                                                        <i class="bi bi-share"></i> Share
                                                                                    </button>
                                                                                </div>
                                                                            </div>
                                                                        `;
            }
        </script>

@endsection
