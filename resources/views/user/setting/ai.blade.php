<div class="settings-section {{ $activeTab === 'ai-settings' ? 'active' : '' }}" data-section="ai-settings">
    <div class="d-flex justify-content-between align-items-center flex-wrap gap-3 mb-4">
        <div>
            <h4 class="fw-semibold mb-1">AI Settings</h4>
            <p class="text-muted mb-0">Brand logo used inside generated images.</p>
        </div>
    </div>
    <form id="aiSettingsForm" class="ai-settings-card border rounded-3 p-4" action="{{ route('settings.ai.logo') }}"
        method="POST" enctype="multipart/form-data">
        @csrf
        <p class="fw-semibold mb-2">Brand Logo</p>
        <p class="text-muted small mb-4">Upload your logo to be included in generated images (max 1MB)</p>
        <input type="hidden" name="remove_logo" id="removeLogoInput" value="0">
        <div class="d-flex flex-wrap gap-2 align-items-center mb-4">
            <button type="button"
                class="btn btn-outline-secondary px-4 ai-logo-state {{ $brandLogoUrl ? '' : 'active' }}"
                data-state="none">
                No logo
            </button>
            <input type="file" id="aiBrandLogoInput" name="ai_logo" accept="image/png,image/jpeg,image/gif"
                class="d-none">
            <button type="button" class="btn btn-outline-primary px-4"
                onclick="document.getElementById('aiBrandLogoInput').click();">
                Upload Logo
            </button>

        </div>
        <small class="text-muted d-block mb-3">JPG, PNG, or GIF. Max 1MB</small>
        <div id="aiLogoPreview" class="ai-logo-preview" style="{{ $brandLogoUrl ? '' : 'display:none;' }}">
            <img src="{{ $brandLogoUrl ?? '' }}" alt="Logo preview">
        </div>
        <hr class="my-4">
        <div class="text-end">
            <button class="btn btn-gradient px-4" type="submit">Save Changes</button>
        </div>
    </form>
</div>
