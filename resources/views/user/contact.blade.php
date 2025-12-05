@extends('user.app')
@section('title', 'Contact Us')

@section('content')
    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-lg-7">

                @if (session('success'))
                    <div class="alert alert-success mb-4">
                        {{ session('success') }}
                    </div>
                @endif

                <div class="contact-card shadow-lg border-0 rounded-4 overflow-hidden">

                    <div class="header-section text-center p-4">
                        <h2 class="fw-bold text-white mb-1">Get in Touch</h2>
                        <p class="text-white-50 mb-0">We’d love to hear from you. Tell us how we can help.</p>
                    </div>

                    <div class="card-body p-5">

                        <form action="{{ route('contact.store') }}" method="POST">
                            @csrf

                            {{-- How can we help you (Moved to first) --}}
                            <div class="mb-4">
                                <label class="form-label fw-semibold">How can we help you?</label>
                                <select class="form-select form-select-lg fancy-input select-small-text"
                                    name="how_can_we_help" required>
                                    <option value="">Select Option</option>
                                    <option value="Contact via email">Contact via Email</option>
                                    <option value="Book demo">Book a Demo</option>
                                    <option value="Book call for help">Book a Call for Help</option>
                                </select>

                            </div>

                            <div class="row">
                                <div class="col-md-6 mb-4">
                                    <label class="form-label fw-semibold">Your Name</label>
                                    <input type="text" class="form-control form-control-lg fancy-input" name="name"
                                        required>
                                </div>

                                <div class="col-md-6 mb-4">
                                    <label class="form-label fw-semibold">Email Address</label>
                                    <input type="email" class="form-control form-control-lg fancy-input" name="email"
                                        required>
                                </div>
                            </div>

                            <div class="mb-4">
                                <label class="form-label fw-semibold">Phone Number</label>
                                <input type="text" class="form-control form-control-lg fancy-input" name="phone">
                            </div>

                            <div class="mb-4">
                                <label class="form-label fw-semibold">Subject</label>
                                <input type="text" class="form-control form-control-lg fancy-input" name="subject">
                            </div>

                            <div class="mb-4">
                                <label class="form-label fw-semibold">Your Message</label>
                                <textarea class="form-control form-control-lg fancy-input" rows="5" name="message" required></textarea>
                            </div>

                            <button class="btn btn-gradient w-100 py-3 fw-bold rounded-3">
                                Submit Request
                            </button>
                        </form>

                    </div>
                </div>

            </div>
        </div>
    </div>
@endsection
