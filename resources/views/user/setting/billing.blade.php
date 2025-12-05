<div class="settings-section {{ $activeTab === 'billing' ? 'active' : '' }}" data-section="billing">
    <div class="d-flex justify-content-between align-items-center flex-wrap gap-3 mb-4">
        <div>
            <h4 class="fw-semibold mb-1">Billing & Invoices</h4>
            <p class="text-muted mb-0">View and download your billing history and invoices.</p>
        </div>
        <a href="{{ route('settings.subscriptions') }}" class="btn btn-gradient px-4">Manage Subscription</a>
    </div>
    <div class="card border-0 billing-card">
        <div class="table-responsive">
            <table class="table align-middle">
                <thead>
                    <tr>
                        <th>DATE</th>
                        <th>PLAN</th>
                        <th>CREDITS</th>
                        <th>AMOUNT</th>
                        <th>STATUS</th>
                        <th class="text-center">ACTION</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($topups as $topup)
                        <tr>
                            <td>{{ optional($topup->created_at)->format('M d, Y') ?? '—' }}</td>
                            <td>Top Up</td>
                            <td>{{ number_format($topup->credits ?? 0) }}</td>
                            <td>₹{{ number_format($topup->amount ?? 0, 2) }}</td>
                            <td>
                                <span class="status-pill status-{{ $topup->payment_status ?? 'pending' }}">
                                    {{ strtoupper($topup->payment_status ?? 'pending') }}
                                </span>
                            </td>
                            <td class="text-center">
                                <button class="btn btn-link text-decoration-none invoice-view"
                                    data-invoice="{{ $topup->order_id ?? '#' }}"
                                    data-date="{{ optional($topup->created_at)->format('M d, Y') ?? '' }}"
                                    data-plan="Top Up"
                                    data-credits="{{ $topup->credits ?? 0 }}"
                                    data-amount="{{ $topup->amount ?? 0 }}"
                                    data-status="{{ $topup->payment_status ?? 'pending' }}">
                                    <i class="fas fa-eye me-1"></i> View
                                </button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center text-muted py-4">
                                No billing history found.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<div class="invoice-modal" id="invoiceModal">
    <div class="invoice-modal-content">
        <button class="btn-close invoice-close" aria-label="Close"></button>
        <div class="text-center mb-4">
            <div class="invoice-logo mx-auto mb-3">
                <span>N</span>
            </div>
            <h5 class="fw-semibold mb-0">Nimora AI</h5>
            <p class="text-muted small mb-0">Surat, India • nimorai25@gmail.com</p>
        </div>
        <div class="d-flex justify-content-between align-items-start flex-wrap gap-3 mb-4">
            <div>
                <p class="text-muted small mb-1">Billed To</p>
                <p class="fw-semibold mb-0">{{ $user->name ?? 'User Name' }}</p>
                <p class="text-muted small mb-0">{{ $user->email ?? 'user@example.com' }}</p>
            </div>
            <div class="text-end">
                <p class="text-muted small mb-1">Invoice</p>
                <p class="fw-semibold mb-0" id="modalInvoiceNumber">#invoice</p>
                <p class="text-muted small mb-0" id="modalInvoiceDate">Date</p>
                <span class="status-pill status-pending" id="modalInvoiceStatus">PENDING</span>
            </div>
        </div>
        <div class="border rounded-3 p-3 mb-4">
            <div class="d-flex justify-content-between">
                <p class="fw-semibold mb-1">Plan Name</p>
                <p class="fw-semibold mb-1">Amount</p>
            </div>
            <div class="d-flex justify-content-between text-muted">
                <span id="modalPlanName">Top Up</span>
                <span id="modalPlanAmount">₹590</span>
            </div>
            <hr>
            <div class="d-flex justify-content-between">
                <p class="fw-semibold mb-1">Credits</p>
                <p class="fw-semibold mb-1" id="modalCredits">500</p>
            </div>
            <div class="text-muted small">Subtotal: <span id="modalSubtotal">₹500.00</span></div>
            <div class="text-muted small">GST (18%): <span id="modalGST">₹90.00</span></div>
            <div class="d-flex justify-content-between align-items-center mt-2">
                <p class="fw-semibold mb-0">Total Amount:</p>
                <p class="fw-semibold mb-0" id="modalTotal">₹590.00</p>
            </div>
        </div>
        <div class="d-flex justify-content-end gap-2">
            <button class="btn btn-light border invoice-close">Close</button>
            <button class="btn btn-gradient" id="downloadInvoiceBtn">Download PDF</button>
        </div>
    </div>
</div>

