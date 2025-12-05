<div class="settings-section {{ $activeTab === 'subscriptions' ? 'active' : '' }}" data-section="subscriptions">
    <div class="d-flex justify-content-between align-items-center flex-wrap gap-3 mb-4">
        <div>
            <h4 class="fw-semibold mb-1">Subscription Settings</h4>
            <p class="text-muted mb-0">Track your current plan and upgrade anytime.</p>
        </div> <button type="button" class="btn btn-gradient px-4 trigger-topup"> <i
                class="fas fa-plus-circle me-2"></i>Top Up Credits </button>
    </div>
    <div class="plan-summary card p-4 mb-4 border-0 shadow-sm">
        <div class="row g-3 align-items-center">
            <div class="col-lg-4 col-md-6">
                <p class="text-muted small mb-1">Your Current Plan</p>
                @if ($activeSubscription && $activeSubscription->plan)
                    <h5 class="mb-0 text-primary">{{ $activeSubscription->plan->name }}</h5>
                    <span class="text-muted small">{{ $activeSubscription->plan->duration_months }}
                        {{ \Illuminate\Support\Str::plural('Month', $activeSubscription->plan->duration_months) }}
                        Plan</span>
                @else
                    <h5 class="mb-0 text-primary">Free</h5>
                    <span class="text-muted small">No active subscription</span>
                @endif
            </div>
            <div class="col-lg-3 col-md-6">
                <p class="text-muted small mb-1">Remaining Credits</p>
                <h5 class="mb-0 text-success">{{ $user->total_credits ?? 60 }}</h5>
            </div>
            <div class="col-lg-3 col-md-6">
                <p class="text-muted small mb-1">Plan Expires In</p>
                @if ($activeSubscription && $activeSubscription->end_date)
                    @php
                        $endDate = \Carbon\Carbon::parse($activeSubscription->end_date);
                        $now = \Carbon\Carbon::now();
                        $daysLeft = $now->diffInDays($endDate, false);
                    @endphp
                    @if ($daysLeft > 0)
                        <h5 class="mb-0 text-success">{{ $daysLeft }}
                            {{ \Illuminate\Support\Str::plural('Day', $daysLeft) }}</h5>
                        <span class="text-muted small">Expires: {{ $endDate->format('M d, Y') }}</span>
                    @else
                        <h5 class="mb-0 text-danger">Expired</h5>
                        <span class="text-muted small">Expired: {{ $endDate->format('M d, Y') }}</span>
                    @endif
                @else
                    <h5 class="mb-0 text-warning">Inactive</h5>
                    <span class="text-muted small">No active plan</span>
                @endif
            </div>
            <div class="col-lg-2 col-md-6 text-lg-end text-md-start">
                <button type="button" class="btn btn-outline-primary w-100 trigger-topup">
                    <i class="fas fa-bolt me-2"></i>Top Up
                </button>
            </div>
        </div>
    </div>

    <div class="mb-4">
        <h5 class="fw-semibold mb-1">Choose Your Plan</h5>
        <p class="text-muted small mb-3">Select the perfect plan that fits your needs and start creating amazing designs
            today.</p>
        <div class="row g-4">
            @forelse ($plans as $plan)
                <div class="col-xl-3 col-md-6">
                    <div class="plan-card card h-100 border-0 shadow-sm position-relative overflow-hidden">

                        <div class="card-body p-2">
                            <div class="plan-head mb-3 d-flex justify-content-between align-items-center">
                                <div>
                                    <p class="fw-semibold mb-0 text-dark">{{ $plan->name }}</p>
                                    <span class="text-muted small">
                                        {{ $plan->duration_months }}
                                        {{ \Illuminate\Support\Str::plural('Month', $plan->duration_months ?? 1) }}
                                    </span>
                                </div>
                                <div class="plan-icon">
                                    <i class="fas fa-gem text-primary"></i>
                                </div>
                            </div>
                            <div class="plan-price mb-3">
                                <h4 class="mb-0 text-dark">₹{{ number_format($plan->price, 2) }}</h4>
                                <p class="text-success small mb-0 fw-semibold">
                                    ₹{{ number_format($plan->price / max(1, $plan->duration_months), 2) }} / month
                                </p>
                            </div>
                            <ul class="list-unstyled plan-benefits mb-4">
                                <li class="mb-2">
                                    <i class="fas fa-check text-success me-2"></i>
                                    <strong>{{ number_format($plan->credits) }}</strong> Credits
                                </li>
                                <li class="mb-2">
                                    <i class="fas fa-check text-success me-2"></i>
                                    {{ $plan->duration_months }} Month Access
                                </li>
                                <li class="mb-2">
                                    <i class="fas fa-check text-success me-2"></i>
                                    Priority Studio Support
                                </li>
                                <li class="mb-2">
                                    <i class="fas fa-check text-success me-2"></i>
                                    Instant Activation
                                </li>
                                <li class="mb-0">
                                    <i class="fas fa-check text-success me-2"></i>
                                    GST extra as applicable
                                </li>
                            </ul>
                            <button type="button" class="btn btn-primary w-100 py-2 fw-semibold trigger-topup"
                                style="color: white" data-plan="{{ $plan->id }}" data-price="{{ $plan->price }}"
                                data-credits="{{ $plan->credits }}">
                                Get Started <i class="fas fa-arrow-right ms-2"></i>
                            </button>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-12">
                    <div class="alert alert-light border text-center mb-0 py-4">
                        <i class="fas fa-info-circle text-muted fa-2x mb-3"></i>
                        <p class="mb-0 text-muted">No subscription plans found. Please add plans in the admin panel.</p>
                    </div>
                </div>
            @endforelse
        </div>
    </div>

    <div class="row g-4">
        <div class="col-lg-6">
            <div class="card p-4 h-100 border-0 shadow-sm">
                <div class="d-flex align-items-center mb-3">
                    <div class="bg-primary bg-opacity-10 p-2 rounded me-3">
                        <i class="fas fa-coins text-primary"></i>
                    </div>
                    <h5 class="fw-semibold mb-0">How Credits Work</h5>
                </div>
                <p class="text-muted small mb-2 fw-semibold">Our platform uses a simple credit system for image creation
                    and enhancement. Here's what each action costs:</p>
                <ul class="list-unstyled mb-4">
                    <li class="mb-2">
                        <i class="fas fa-check text-success me-2"></i>
                        <strong>20 credits</strong> = Create Image (Standard Quality)
                    </li>
                    <li class="mb-2">
                        <i class="fas fa-check text-success me-2"></i>
                        <strong>22 credits</strong> = Create Image + 4K HD Upscale
                    </li>
                    <li class="mb-0">
                        <i class="fas fa-check text-success me-2"></i>
                        <strong>5 credits</strong> = Upscale Existing Image to 4K HD
                    </li>
                </ul>
                <p class="text-muted small mb-2 fw-semibold">Example Usage</p>
                <ul class="list-unstyled mb-0">
                    <li class="mb-2">
                        <i class="fas fa-check text-success me-2"></i>
                        With 100 credits: 5 images OR 4 images (with 4K upscale)
                    </li>
                    <li class="mb-2">
                        <i class="fas fa-check text-success me-2"></i>
                        Or 20 upscales, OR a mix of all three
                    </li>
                    <li class="mb-0">
                        <i class="fas fa-check text-success me-2"></i>
                        Pay only for what you use - mix creation & enhancement as needed
                    </li>
                </ul>
            </div>
        </div>
        <div class="col-lg-6">
            <div class="card p-4 h-100 border-0 shadow-sm">
                <div class="d-flex align-items-center mb-3">
                    <div class="bg-success bg-opacity-10 p-2 rounded me-3">
                        <i class="fas fa-star text-success"></i>
                    </div>
                    <h5 class="fw-semibold mb-0">Plan Benefits</h5>
                </div>
                <ul class="list-unstyled mb-4">
                    <li class="mb-2">
                        <i class="fas fa-check text-success me-2"></i>
                        Carry forward unused credits
                    </li>
                    <li class="mb-2">
                        <i class="fas fa-check text-success me-2"></i>
                        Redeem in any AI feature
                    </li>
                    <li class="mb-2">
                        <i class="fas fa-check text-success me-2"></i>
                        Top up anytime
                    </li>
                    <li class="mb-2">
                        <i class="fas fa-check text-success me-2"></i>
                        Priority Studio Support
                    </li>
                    <li class="mb-0">
                        <i class="fas fa-check text-success me-2"></i>
                        Instant Activation
                    </li>
                </ul>
                <div class="bg-light rounded p-3 mt-auto">
                    <p class="text-muted small mb-0">
                        <i class="fas fa-info-circle text-primary me-1"></i>
                        *Prices shown are exclusive of GST
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>
