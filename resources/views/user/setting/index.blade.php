@extends('user.app')
@section('title', 'Settings')
@section('content')

    @php $activeTab = $activeTab ?? 'account'; @endphp
    <div class="settings-wrapper py-4">

        @if (session('status'))
            <div class="alert alert-success alert-dismissible fade show mb-4" role="alert">
                {{ session('status') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        @if (session('error'))
            <div class="alert alert-danger alert-dismissible fade show mb-4" role="alert">
                {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        @if ($errors->any())
            <div class="alert alert-danger alert-dismissible fade show mb-4" role="alert">
                <ul class="mb-0">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <div class="settings-header mb-4">
            <h2 class="fw-bold mb-1">Settings</h2>
            <p class="text-muted mb-0">Manage your account and preferences.</p>
        </div>

        <div class="settings-layout">
            <aside class="settings-menu card p-3">
                <p class="fw-semibold small text-muted text-uppercase mb-3">Settings Menu</p>
                <div class="nav flex-column nav-pills gap-2" role="tablist">
                    @foreach ($settingsMenu as $item)
                        <a class="settings-menu-btn {{ $activeTab === $item['id'] ? 'active' : '' }}"
                            href="{{ $item['route'] }}">
                            <span><i class="{{ $item['icon'] }} me-2"></i>{{ $item['label'] }}</span>
                            <i class="fas fa-chevron-right ms-auto small text-muted"></i>
                        </a>
                    @endforeach
                </div>
            </aside>

            <section class="settings-content card p-4">
                @include('user.setting.account')
                @include('user.setting.ai')
                @include('user.setting.notifications')
                @include('user.setting.security')
                @include('user.setting.subscriptions')
                @include('user.setting.billing')
            </section>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const avatar = document.getElementById('profileAvatar');
            const fileInput = document.getElementById('profileUploadInput');
            const removeAvatarBtn = document.getElementById('removeAvatarBtn');
            const defaultInitial = avatar ? avatar.dataset.initial : 'U';
            const aiLogoInput = document.getElementById('aiBrandLogoInput');
            const aiLogoPreview = document.getElementById('aiLogoPreview');
            const aiLogoRemove = document.querySelector('.ai-logo-remove');
            const aiNoLogoBtn = document.querySelector('.ai-logo-state');
            const invoiceModal = document.getElementById('invoiceModal');
            const invoiceCloseBtns = document.querySelectorAll('.invoice-close');
            const invoiceViewBtns = document.querySelectorAll('.invoice-view');

            const formatCurrency = (value) => {
                return `₹${Number(value || 0).toFixed(2)}`;
            };

            if (fileInput) {
                fileInput.addEventListener('change', (e) => {
                    const file = e.target.files[0];
                    if (!file || !avatar) return;
                    const reader = new FileReader();
                    reader.onload = (evt) => {
                        avatar.innerHTML = `<img src="${evt.target.result}" alt="Profile preview">`;
                    };
                    reader.readAsDataURL(file);
                });
            }

            if (removeAvatarBtn && avatar) {
                removeAvatarBtn.addEventListener('click', () => {
                    fileInput.value = '';
                    avatar.innerHTML = defaultInitial;
                });
            }

            const removeLogoInput = document.getElementById('removeLogoInput');

            const setRemoveFlag = (value) => {
                if (removeLogoInput) {
                    removeLogoInput.value = value ? '1' : '0';
                }
            };

            const showAiLogoPreview = (src) => {
                if (aiLogoPreview) {
                    aiLogoPreview.style.display = 'block';
                    const img = aiLogoPreview.querySelector('img');
                    if (img) {
                        img.src = src;
                    }
                }

                if (aiLogoRemove) {
                    aiLogoRemove.disabled = false;
                }

                if (aiNoLogoBtn) {
                    aiNoLogoBtn.classList.remove('active');
                }
                setRemoveFlag(false);
            };

            const clearAiLogoPreview = (markRemove = false) => {
                if (aiLogoPreview) {
                    aiLogoPreview.style.display = 'none';
                    const img = aiLogoPreview.querySelector('img');
                    if (img) img.src = '';
                }

                if (aiLogoRemove) {
                    aiLogoRemove.disabled = true;
                }

                if (aiNoLogoBtn) {
                    aiNoLogoBtn.classList.add('active');
                }

                if (markRemove) {
                    setRemoveFlag(true);
                }
            };

            if (aiLogoInput) {
                aiLogoInput.addEventListener('change', (e) => {
                    const file = e.target.files[0];
                    if (!file) return;
                    const maxSize = 1024 * 1024;
                    if (file.size > maxSize) {
                        alert('File size exceeds 1MB limit. Please choose a smaller file.');
                        aiLogoInput.value = '';
                        return;
                    }

                    // Validate file type
                    const allowedTypes = ['image/jpeg', 'image/jpg', 'image/png', 'image/gif'];
                    if (!allowedTypes.includes(file.type)) {
                        alert('Invalid file type. Please upload JPG, PNG, or GIF only.');
                        aiLogoInput.value = '';
                        return;
                    }

                    const reader = new FileReader();
                    reader.onload = (evt) => {
                        showAiLogoPreview(evt.target.result);
                    };

                    reader.readAsDataURL(file);

                    const form = document.getElementById('aiSettingsForm');
                    if (form) {
                        setTimeout(() => {
                            form.submit();
                        }, 300);
                    }
                });
            }

            if (aiLogoRemove) {
                aiLogoRemove.addEventListener('click', () => {
                    if (aiLogoRemove.disabled) return;
                    if (aiLogoInput) aiLogoInput.value = '';
                    clearAiLogoPreview(true);
                });
            }

            if (aiNoLogoBtn) {
                aiNoLogoBtn.addEventListener('click', () => {
                    if (aiLogoInput) aiLogoInput.value = '';
                    clearAiLogoPreview(true);
                });
            }

            const fillInvoiceModal = (btn) => {
                const amount = parseFloat(btn.dataset.amount || '0');
                const gstRate = parseFloat(btn.dataset.gst || '18');
                const base = amount / (1 + (gstRate / 100));
                const gst = amount - base;

                document.getElementById('modalInvoiceNumber').textContent = btn.dataset.invoice || '#';
                document.getElementById('modalInvoiceDate').textContent = btn.dataset.date || '';
                document.getElementById('modalPlanName').textContent = btn.dataset.plan || 'Top Up';
                document.getElementById('modalPlanAmount').textContent = formatCurrency(amount);
                document.getElementById('modalCredits').textContent = btn.dataset.credits || '0';
                document.getElementById('modalSubtotal').textContent = formatCurrency(base);
                document.getElementById('modalGST').textContent = formatCurrency(gst);
                document.getElementById('modalTotal').textContent = formatCurrency(amount);

                const status = (btn.dataset.status || 'pending').toLowerCase();
                const statusPill = document.getElementById('modalInvoiceStatus');
                statusPill.className = `status-pill status-${status}`;
                statusPill.textContent = status.toUpperCase();
            };

            invoiceViewBtns.forEach(btn => {
                btn.addEventListener('click', () => {
                    fillInvoiceModal(btn);
                    invoiceModal.classList.add('show');
                });
            });

            invoiceCloseBtns.forEach(btn => {
                btn.addEventListener('click', () => {
                    invoiceModal.classList.remove('show');
                });
            });

            if (invoiceModal) {
                invoiceModal.addEventListener('click', (e) => {
                    if (e.target === invoiceModal) {
                        invoiceModal.classList.remove('show');
                    }
                });

            }

            const downloadBtn = document.getElementById('downloadInvoiceBtn');
            if (downloadBtn) {
                downloadBtn.addEventListener('click', () => {
                    const modalContent = document.querySelector('#invoiceModal .invoice-modal-content');
                    if (!modalContent) return;
                    const printWindow = window.open('', '_blank', 'width=600,height=800');

                    printWindow.document.write(`
                    <html>
                        <head>
                            <title>Invoice</title>
                            <style>
                                body { font-family: Arial, sans-serif; padding: 24px; }
                                .invoice { max-width: 560px; margin: auto; border: 1px solid #ddd; border-radius: 16px; padding: 24px; }
                                h5 { margin-top: 0; }
                                .text-center { text-align: center; }
                                .fw-semibold { font-weight: 600; }
                            </style>
                        </head>
                        <body>
                            <div class="invoice">
                                ${modalContent.innerHTML}
                            </div>
                        </body>
                    </html>
                `);

                    printWindow.document.close();
                    printWindow.focus();
                    printWindow.print();
                    setTimeout(() => printWindow.close(), 500);

                });

            }

        });
    </script>
@endsection
