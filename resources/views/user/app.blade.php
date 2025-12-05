<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'mishruh Studio Admin')</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap"
        rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <link href="{{ asset('assets/css/admin.css') }}" rel="stylesheet">
</head>

<body>

    <div class="sidebar d-flex flex-column">
        <div class="p-4 d-flex align-items-center border-bottom">
            <div class="me-2 text-center logo-bg"
                style="width: 32px; height: 32px; border-radius: 50%; font-weight: 700; line-height: 32px; font-size: 1.1rem;">
                N
            </div>
            <div>
                <div class="fs-5 fw-semibold text-dark">mishruh Studio</div>
                <div class="text-muted small">AI Jewelry Design</div>
            </div>
        </div>

        <div class="p-3">
            <a href="{{ route('catalog.studio') }}" class="text-decoration-none">
                <button class="btn btn-gradient w-100 btn-lg shadow-sm" style="border-radius: 0.75rem;">
                    <i class="fas fa-plus me-2"></i> New Design
                </button>
            </a>
        </div>

        <nav class="flex-grow-1 overflow-auto">

            <div class="px-4 pt-3 pb-1 text-uppercase small text-muted fw-semibold" style="font-size: 0.7rem;">MAIN
            </div>

            <ul class="nav flex-column px-3">
                <li class="nav-item">
                    <a class="nav-link d-flex align-items-center {{ request()->routeIs('user.dashboard') ? 'active-menu' : '' }}"
                        href="{{ route('user.dashboard') }}">
                        <i class="fas fa-chart-line me-3"></i> Dashboard
                    </a>
                </li>

                <li class="nav-item">
                    <a class="nav-link d-flex align-items-center
        {{ request()->routeIs('creative.ai.*') || request()->routeIs('ai.photoshoot.*') ? 'active-menu' : '' }}"
                        href="{{ route('creative.ai.index') }}">

                        <i class="fas fa-wand-magic-sparkles me-3"></i>
                        Creative AI
                    </a>
                </li>


                <li class="nav-item">
                    <a class="nav-link d-flex align-items-center {{ request()->routeIs('catalog.library') ? 'active-menu' : '' }}"
                        href="{{ route('catalog.library') }}">
                        <i class="fas fa-warehouse me-3"></i> Catalog Library
                    </a>
                </li>

                <li class="nav-item">
                    <a class="nav-link d-flex align-items-center {{ request()->routeIs('generation.history') ? 'active-menu' : '' }}"
                        href="{{ route('generation.history') }}">
                        <i class="fas fa-clock-rotate-left me-3"></i> Generation History
                    </a>
                </li>

                <li class="nav-item">
                    <a class="nav-link d-flex align-items-center {{ request()->routeIs('favorites') ? 'active-menu' : '' }}"
                        href="{{ route('favorites') }}">
                        <i class="fas fa-heart me-3"></i> Favorites
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link d-flex align-items-center {{ request()->routeIs('contact') ? 'active-menu' : '' }}"
                        href="{{ route('contact') }}">
                        <i class="fas fa-envelope me-3"></i> Contact
                    </a>
                </li>

                <a class="nav-link d-flex align-items-center {{ request()->routeIs('settings.*') ? 'active-menu' : '' }}"
                    href="{{ route('settings.account') }}">
                    <i class="fas fa-cog me-3"></i> Settings
                </a>

            </ul>

        </nav>

        <div class="p-4 border-top">
            <div class="text-uppercase small text-muted fw-semibold mb-2" style="font-size: 0.7rem;">ACCOUNT</div>
            <a href="{{ route('logout') }}" class="nav-link text-danger py-2 px-3 d-flex align-items-center">
                <i class="fas fa-sign-out-alt me-3"></i> Sign Out
            </a>
        </div>
    </div>

    <div class="main-content">

        <nav class="navbar navbar-top">
            <div class="container-fluid justify-content-end">
                <div class="d-flex align-items-center">

                    <div class="dropdown me-2">
                        <button class="btn btn-light rounded-circle p-2" type="button" data-bs-toggle="dropdown"
                            aria-expanded="false">
                            <i class="fas fa-sun fa-lg" id="theme-icon"></i>
                        </button>
                        <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="theme-toggle">
                            <li><a class="dropdown-item" href="#" data-theme="light"><i
                                        class="fas fa-sun me-2"></i> Light Mode</a></li>
                            <li><a class="dropdown-item" href="#" data-theme="dark"><i
                                        class="fas fa-moon me-2"></i> Dark Mode</a></li>
                        </ul>
                    </div>

                    <div class="profile-trigger cursor-pointer" id="profileBtn">
                        <div class="profile-progress" data-progress="{{ $globalCredits ?? 0 }}">
                            <div class="profile-outer">
                                <div class="profile-inner">
                                    {{ strtoupper(Auth::user()->name[0] ?? 'U') }}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </nav>

        {{-- <main class="container-fluid p-4"> --}}
        <main class="container-fluid">
            @yield('content')
        </main>
    </div>
    <div id="profilePopup">
        <div class="d-flex justify-content-between align-items-center mb-2">
            <span>Current Plan</span>
            <button class="btn btn-sm btn-gradient">Subscribe Now</button>
        </div>

        <div class="small">Credits Used</div>

        <div class="d-flex align-items-center">
            <div class="progress flex-grow-1 me-2" style="height: 6px;">
                <div class="progress-bar" style="width: {{ $globalProgress ?? 0 }}%;">
                </div>
            </div>

            <span class="small">
                {{ $globalUsedCredits ?? 0 }} / {{ $globalMaxCredits ?? 100 }}
            </span>
        </div>

        <div class="mt-2 small">
            Remaining Credits <b>{{ $globalCredits ?? 0 }}</b>
        </div>

        <button class="btn btn-gradient w-100 mt-3" id="openTopUpBtn">
            <i class="fa fa-plus me-1"></i> Top Up Credits
        </button>
    </div>


    <div id="topupModal" class="mishruh-modal">
        <div class="mishruh-modal-box">

            <div class="d-flex justify-content-between align-items-center mb-2">
                <h5 class="fw-semibold">Top-Up Now</h5>
                <button class="btn btn-light p-0 modal-close" style="font-size: 20px;">×</button>
            </div>

            <div class="text-center bg-light theme-box p-2 rounded mb-3">
                <small>Available Credits</small>
                <div class="fw-bold" style="font-size: 22px;">
                    {{ $globalCredits ?? 0 }}
                </div>
            </div>

            <div class="text-center mb-2 fw-semibold">Top-up Amount</div>

            <div class="text-center mb-3">
                <span style="font-size: 30px;">₹</span>
                <input type="number" id="topupAmount" class="border-0 fw-bold"
                    style="font-size: 34px; width: 120px; text-align: center; outline: none;" value="500">
            </div>

            <div class="text-center small mb-3">
                1 ₹ = 1 Credit <br>
                You will receive <span id="receivedCredits">500</span> credits
            </div>

            <div class="border rounded p-3 mb-3">
                <div class="d-flex justify-content-between mb-1">
                    <span>Base Amount:</span>
                    <span id="baseAmount">₹500</span>
                </div>

                <div class="d-flex justify-content-between mb-1">
                    <span>GST (18%):</span>
                    <span id="gstAmount">₹90</span>
                </div>

                <div class="d-flex justify-content-between fw-bold border-top pt-2">
                    <span>Total Amount:</span>
                    <span id="totalAmount">₹590</span>
                </div>
            </div>

            <button id="payButton" class="btn btn-gradient w-100 mt-2">
                Pay ₹590.00
            </button>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://sdk.cashfree.com/js/v3/cashfree.js"></script>

    <script>
        // Global variable to store selected plan_id
        let selectedPlanId = null;
        document.addEventListener('DOMContentLoaded', () => {
            const body = document.body;
            const themeIcon = document.getElementById('theme-icon');
            const themeLinks = document.querySelectorAll('.dropdown-item[data-theme]');

            // 1. Theme Application Function
            const setTheme = (theme) => {
                document.documentElement.setAttribute('data-theme', theme);

                if (theme === 'dark') {
                    themeIcon.classList.replace('fa-sun', 'fa-moon');
                    localStorage.setItem('theme', 'dark');
                } else {
                    themeIcon.classList.replace('fa-moon', 'fa-sun');
                    localStorage.setItem('theme', 'light');
                }
            };


            // 2. Initial Load: Default to 'light' if no preference is found
            const savedTheme = localStorage.getItem('theme') || 'light';
            setTheme(savedTheme);

            // 3. Dropdown Link Click Handler
            themeLinks.forEach(link => {
                link.addEventListener('click', (e) => {
                    e.preventDefault();
                    const newTheme = link.getAttribute('data-theme');
                    setTheme(newTheme);
                });
            });
        });

        document.addEventListener("DOMContentLoaded", function() {

            const profileBtn = document.getElementById("profileBtn");
            const profilePopup = document.getElementById("profilePopup");
            let popupOpen = false;

            profileBtn.onclick = (e) => {
                e.stopPropagation();
                popupOpen = !popupOpen;
                profilePopup.style.display = popupOpen ? "block" : "none";
            };

            // Close on outside click
            document.body.onclick = () => {
                if (popupOpen) {
                    profilePopup.style.display = "none";
                    popupOpen = false;
                }
            };
        });
        document.addEventListener("DOMContentLoaded", function() {

            const openTopUpBtn = document.getElementById("openTopUpBtn");
            const topUpTriggers = document.querySelectorAll(".trigger-topup");

            const topupModal = document.getElementById("topupModal");
            const closeModal = document.querySelector(".modal-close");
            const amountInput = document.getElementById("topupAmount");
            const baseAmountEl = document.getElementById("baseAmount");
            const gstAmountEl = document.getElementById("gstAmount");
            const totalAmountEl = document.getElementById("totalAmount");
            const receivedCreditsEl = document.getElementById("receivedCredits");
            const payButton = document.getElementById("payButton");

            const showTopUpModal = () => {
                topupModal.style.display = "block";
                updateAmounts();
            };

            if (openTopUpBtn) {
                openTopUpBtn.onclick = () => {
                    selectedPlanId = null;
                    showTopUpModal();
                };
            }

            topUpTriggers.forEach(btn => {
                btn.addEventListener("click", () => {
                    const planPrice = parseFloat(btn.dataset.price || amountInput.value || 0);
                    const planCredits = parseInt(btn.dataset.credits || planPrice, 10);
                    selectedPlanId = btn.dataset.plan || null;
                    if (!isNaN(planPrice) && planPrice > 0) {
                        amountInput.value = planPrice;
                        updateAmounts(planPrice, planCredits);
                    }
                    showTopUpModal();
                });
            });

            closeModal.onclick = () => {
                topupModal.style.display = "none";
            };

            window.onclick = (e) => {
                if (e.target === topupModal) {
                    topupModal.style.display = "none";
                }
            };

            const formatCurrency = (num) => `₹${Number(num || 0).toFixed(2)}`;

            const updateAmounts = (amountVal, creditsOverride) => {
                const amount = Number(amountVal !== undefined ? amountVal : amountInput.value || 0);
                const gst = amount * 0.18;
                const total = amount + gst;
                baseAmountEl.innerText = formatCurrency(amount);
                gstAmountEl.innerText = formatCurrency(gst);
                totalAmountEl.innerText = formatCurrency(total);
                receivedCreditsEl.innerText = creditsOverride !== undefined ? creditsOverride : Math.round(
                    amount);
                if (payButton) {
                    payButton.innerText = `Pay ${formatCurrency(total)}`;
                }
            };

            updateAmounts();

            amountInput.addEventListener("input", () => {
                updateAmounts();
            });

        });
        document.addEventListener("DOMContentLoaded", () => {
            const prog = document.querySelector(".profile-progress");
            if (!prog) return;

            const credits = parseInt(prog.dataset.progress) || 0;

            // convert credits to 360° for conic-gradient
            const angle = (credits / 100) * 360;

            prog.style.background = `conic-gradient(#6a4dfd ${angle}deg, #e3e3e3 ${angle}deg)`;
        });


        // =============================
        //  PAY BUTTON CLICK
        // =============================

        document.getElementById("payButton").addEventListener("click", function() {

            // Get total amount (with GST) for payment
            let amount = parseFloat(document.getElementById("totalAmount").innerText.replace("₹", "").replace(/,/g,
                "").trim());
            // Get credits from the displayed value
            let credits = parseInt(document.getElementById("receivedCredits").innerText.replace(/,/g, "").trim()) ||
                Math.round(amount / 1.18);

            if (isNaN(amount) || amount <= 0) {
                alert("Invalid amount. Please enter a valid amount.");
                return;
            }

            if (isNaN(credits) || credits <= 0) {
                alert("Invalid credits. Please try again.");
                return;
            }

            let btn = this;
            btn.disabled = true;
            btn.innerText = "Processing...";

            fetch("{{ route('cashfree.create') }}", {
                    method: "POST",
                    headers: {
                        "Content-Type": "application/json",
                        "X-CSRF-TOKEN": "{{ csrf_token() }}"
                    },
                    body: JSON.stringify({
                        amount: amount,
                        credits: credits,
                        plan_id: selectedPlanId || null
                    })
                })
                .then(res => res.json())
                .then(data => {
                    console.log("Order creation response:", data);

                    if (data.status === true && data.payment_session_id) {
                        launchCashfreeCheckout(data.payment_session_id);
                    } else {
                        let errorMsg = data.message || "Order creation failed!";
                        if (data.cf_response) {
                            errorMsg += "\n\nCashfree Error: " + JSON.stringify(data.cf_response, null, 2);
                        }
                        alert(errorMsg);
                        btn.disabled = false;
                        btn.innerText = "Pay";
                    }

                })
                .catch(err => {
                    console.error("Fetch error:", err);
                    alert("Server Error: " + err.message);
                    btn.disabled = false;
                    btn.innerText = "Pay";
                });
        });

        function launchCashfreeCheckout(paymentSessionId) {
            if (typeof Cashfree === 'undefined') {
                alert("Cashfree SDK not loaded. Please refresh the page and try again.");
                console.error("Cashfree SDK not available");
                return;
            }

            if (!paymentSessionId || paymentSessionId.trim() === '') {
                alert("Invalid payment session. Please try again.");
                console.error("Empty payment session ID");
                return;
            }

            const cashfreeMode = "{{ env('CASHFREE_ENV', 'SANDBOX') }}" === 'PRODUCTION' ? 'production' : 'sandbox';
            console.log("Cashfree mode:", cashfreeMode);
            console.log("Payment Session ID:", paymentSessionId);

            const cashfree = Cashfree({
                mode: cashfreeMode
            });

            cashfree.checkout({
                paymentSessionId: paymentSessionId,

                layout: {
                    type: "popup",
                    width: "450px",
                    height: "650px"
                },

                onSuccess: function(result) {
                    console.log("Payment Success: ", result);

                    let orderId = result.order.order_id;

                    fetch("{{ route('cashfree.success') }}?order_id=" + orderId)
                        .then(res => res.text())
                        .then(res => {
                            alert("Payment Success! Credits Added.");
                            window.location.reload();
                        })
                        .catch(err => {
                            console.error("Success callback error:", err);
                            alert("Payment successful but verification failed. Please contact support.");
                        });
                },

                onFailure: function(error) {
                    console.error("Payment Failed: ", error);
                    alert("Payment Failed: " + (error.message || JSON.stringify(error)));
                },

                onPending: function(result) {
                    console.log("Payment Pending: ", result);
                    alert("Payment Pending! Please wait for confirmation.");
                }
            });

        }
    </script>
</body>

</html>
