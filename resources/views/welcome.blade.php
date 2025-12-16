<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Survey Hub - Global Survey Platform</title>
    <link rel="icon" href="data:,">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        :root {
            --primary: #4f46e5;
            --secondary: #06b6d4;
            --dark: #1e293b;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            overflow-x: hidden;
        }

        .navbar {
            background: linear-gradient(135deg, var(--primary), var(--secondary));
            padding: 1rem 0;
            box-shadow: 0 4px 20px rgba(0,0,0,0.1);
        }

        .navbar-brand {
            font-size: 1.8rem;
            font-weight: 700;
            color: white !important;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .hero-section {
            background: linear-gradient(135deg, var(--primary), var(--secondary));
            color: white;
            padding: 100px 0 80px;
            position: relative;
            overflow: hidden;
        }

        .hero-section::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: url('data:image/svg+xml,<svg width="100" height="100" xmlns="http://www.w3.org/2000/svg"><circle cx="50" cy="50" r="2" fill="rgba(255,255,255,0.1)"/></svg>');
            opacity: 0.4;
        }

        .hero-content {
            position: relative;
            z-index: 1;
        }

        .hero-title {
            font-size: 3.5rem;
            font-weight: 800;
            margin-bottom: 1.5rem;
            text-shadow: 2px 2px 4px rgba(0,0,0,0.2);
        }

        .hero-subtitle {
            font-size: 1.3rem;
            opacity: 0.95;
            margin-bottom: 2rem;
        }

        .stats-box {
            background: rgba(255,255,255,0.15);
            backdrop-filter: blur(10px);
            border-radius: 15px;
            padding: 1.5rem;
            margin-top: 3rem;
        }

        .stat-item {
            text-align: center;
        }

        .stat-number {
            font-size: 2.5rem;
            font-weight: 700;
        }

        .stat-label {
            font-size: 0.9rem;
            opacity: 0.9;
        }

        .features-section {
            padding: 80px 0;
            background: #f8fafc;
        }

        .feature-card {
            background: white;
            border-radius: 15px;
            padding: 2rem;
            text-align: center;
            box-shadow: 0 4px 15px rgba(0,0,0,0.08);
            transition: transform 0.3s, box-shadow 0.3s;
            height: 100%;
        }

        .feature-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 8px 30px rgba(0,0,0,0.15);
        }

        .feature-icon {
            width: 70px;
            height: 70px;
            background: linear-gradient(135deg, var(--primary), var(--secondary));
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 1.5rem;
            color: white;
            font-size: 1.8rem;
        }

        .surveys-section {
            padding: 80px 0;
        }

        .section-title {
            font-size: 2.5rem;
            font-weight: 700;
            margin-bottom: 1rem;
            color: var(--dark);
        }

        .section-subtitle {
            color: #64748b;
            margin-bottom: 3rem;
        }

        .survey-card {
            background: white;
            border-radius: 15px;
            padding: 2rem;
            margin-bottom: 2rem;
            box-shadow: 0 4px 15px rgba(0,0,0,0.08);
            transition: transform 0.3s, box-shadow 0.3s;
            border-left: 4px solid var(--primary);
        }

        .survey-card:hover {
            transform: translateX(10px);
            box-shadow: 0 8px 30px rgba(0,0,0,0.15);
        }

        .survey-title {
            font-size: 1.4rem;
            font-weight: 600;
            color: var(--dark);
            margin-bottom: 1rem;
        }

        .survey-description {
            color: #64748b;
            margin-bottom: 1.5rem;
        }

        .survey-meta {
            display: flex;
            gap: 2rem;
            flex-wrap: wrap;
            align-items: center;
        }

        .meta-item {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            color: #64748b;
            font-size: 0.9rem;
        }

        .reward-badge {
            background: linear-gradient(135deg, #10b981, #059669);
            color: white;
            padding: 0.4rem 1rem;
            border-radius: 20px;
            font-weight: 600;
        }

        .btn-primary-custom {
            background: linear-gradient(135deg, var(--primary), var(--secondary));
            border: none;
            padding: 0.8rem 2rem;
            border-radius: 25px;
            font-weight: 600;
            transition: transform 0.3s, box-shadow 0.3s;
        }

        .btn-primary-custom:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(79, 70, 229, 0.4);
        }

        .btn-outline-custom {
            border: 2px solid white;
            color: white;
            padding: 0.8rem 2rem;
            border-radius: 25px;
            font-weight: 600;
            transition: all 0.3s;
        }

        .btn-outline-custom:hover {
            background: white;
            color: var(--primary);
        }

        footer {
            background: var(--dark);
            color: white;
            padding: 3rem 0 1rem;
        }

        .modal-content {
            border-radius: 20px;
            border: none;
        }

        .modal-header {
            background: linear-gradient(135deg, var(--primary), var(--secondary));
            color: white;
            border-radius: 20px 20px 0 0;
        }

        .form-control:focus {
            border-color: var(--primary);
            box-shadow: 0 0 0 0.2rem rgba(79, 70, 229, 0.25);
        }

        @media (max-width: 768px) {
            .hero-title {
                font-size: 2.5rem;
            }
            
            .stat-number {
                font-size: 2rem;
            }
        }
          /* About Modal Specific Styles */
    #aboutModal .modal-content {
        border-radius: 20px;
        border: none;
    }

    #aboutModal .modal-header {
        background: linear-gradient(135deg, var(--primary), var(--secondary));
        color: white;
        border-radius: 20px 20px 0 0;
    }

    #aboutModal .feature-icon {
        width: 80px;
        height: 80px;
        background: linear-gradient(135deg, var(--primary), var(--secondary));
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-size: 2rem;
    }

    /* Responsive adjustments */
    @media (max-width: 768px) {
        #aboutModal .modal-lg {
            margin: 0.5rem;
        }

        #aboutModal .feature-icon {
            width: 60px;
            height: 60px;
            font-size: 1.5rem;
        }

        #aboutModal .h3 {
            font-size: 1.5rem;
        }

        #aboutModal .lead {
            font-size: 1rem;
        }
    }

    @media (max-width: 576px) {
        #aboutModal .modal-body {
            padding: 1.5rem !important;
        }

        #aboutModal .row.g-3 {
            gap: 0.5rem !important;
        }

        #aboutModal .col-6 .small {
            font-size: 0.75rem;
        }
    }

     #learnMoreModal .modal-content {
        border-radius: 20px;
        border: none;
    }

    #learnMoreModal .modal-header {
        background: linear-gradient(135deg, var(--primary), var(--secondary));
        color: white;
        border-radius: 20px 20px 0 0;
    }

    #learnMoreModal .feature-icon {
        width: 80px;
        height: 80px;
        background: linear-gradient(135deg, var(--primary), var(--secondary));
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-size: 2rem;
    }

    #learnMoreModal .accordion-button {
        background: #f8fafc;
        color: var(--dark);
        font-weight: 600;
        border-radius: 10px !important;
    }

    #learnMoreModal .accordion-button:not(.collapsed) {
        background: linear-gradient(135deg, rgba(79, 70, 229, 0.1), rgba(6, 182, 212, 0.1));
        color: var(--primary);
    }

    #learnMoreModal .accordion-button:focus {
        box-shadow: none;
        border-color: var(--primary);
    }

    #learnMoreModal .accordion-body {
        background: white;
        border-radius: 0 0 10px 10px;
    }

    /* Responsive */
    @media (max-width: 768px) {
        #learnMoreModal .modal-lg {
            margin: 0.5rem;
        }

        #learnMoreModal .feature-icon {
            width: 60px;
            height: 60px;
            font-size: 1.5rem;
        }

        #learnMoreModal .rounded-circle {
            width: 40px !important;
            height: 40px !important;
            font-size: 1rem !important;
        }
    }

    @media (max-width: 576px) {
        #learnMoreModal .modal-body {
            padding: 1.5rem !important;
        }

        #learnMoreModal .lead {
            font-size: 1rem;
        }

        #learnMoreModal .col-4 i {
            font-size: 1.5rem !important;
        }
    }
    </style>
</head>
<body>

    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-dark sticky-top">
        <div class="container">
            <a class="navbar-brand" href="#">
                <i class="fas fa-globe"></i> Survey Hub
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto align-items-center">
                    <li class="nav-item">
                        <a class="nav-link text-white" href="#surveys">Surveys</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link text-white" href="#features">Features</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link text-white" href="#" data-bs-toggle="modal" data-bs-target="#aboutModal">About</a>

                    </li>
                    <li class="nav-item ms-3">
                        <button class="btn btn-outline-custom" data-bs-toggle="modal" data-bs-target="#loginModal">Login</button>
                    </li>
                    <li class="nav-item ms-2">
                        <button class="btn btn-light" data-bs-toggle="modal" data-bs-target="#signupModal">Sign Up</button>
                    </li>
                </ul>
            </div>
        </div>
    </nav>
@include('handler')

    <!-- Hero Section -->
    <section class="hero-section">
        <div class="container hero-content">
            <div class="row align-items-center">
                <div class="col-lg-8 mx-auto text-center">
                    <h1 class="hero-title">Your Voice Matters Globally</h1>
                    <p class="hero-subtitle">Join millions worldwide sharing opinions that shape the future. Participate in surveys, earn rewards, and make an impact.</p>
                    <div class="d-flex gap-3 justify-content-center flex-wrap">
                        <button class="btn btn-light btn-lg px-5" data-bs-toggle="modal" data-bs-target="#signupModal">
                            Get Started <i class="fas fa-arrow-right ms-2"></i>
                        </button>
                        <button class="btn btn-outline-custom btn-lg px-5">
                            Learn More
                        </button>
                    </div>

                    <div class="stats-box">
                        <div class="row">
                            <div class="col-md-4 stat-item">
                                <div class="stat-number">5M+</div>
                                <div class="stat-label">Active Users</div>
                            </div>
                            <div class="col-md-4 stat-item">
                                <div class="stat-number">180+</div>
                                <div class="stat-label">Countries</div>
                            </div>
                            <div class="col-md-4 stat-item">
                                <div class="stat-number">50K+</div>
                                <div class="stat-label">Daily Surveys</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Features Section -->
    <section class="features-section" id="features">
        <div class="container">
            <div class="text-center mb-5">
                <h2 class="section-title">Why Choose Survey Hub?</h2>
                <p class="section-subtitle">The world's most trusted survey platform</p>
            </div>
            <div class="row g-4">
                <div class="col-md-3">
                    <div class="feature-card">
                        <div class="feature-icon">
                            <i class="fas fa-globe"></i>
                        </div>
                        <h4>Global Reach</h4>
                        <p>Connect with participants from over 180 countries worldwide</p>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="feature-card">
                        <div class="feature-icon">
                            <i class="fas fa-shield-alt"></i>
                        </div>
                        <h4>Secure & Private</h4>
                        <p>Your data is protected with enterprise-grade security</p>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="feature-card">
                        <div class="feature-icon">
                            <i class="fas fa-gift"></i>
                        </div>
                        <h4>Earn Rewards</h4>
                        <p>Get paid for your valuable opinions and insights</p>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="feature-card">
                        <div class="feature-icon">
                            <i class="fas fa-chart-line"></i>
                        </div>
                        <h4>Real Impact</h4>
                        <p>Your feedback shapes products and services globally</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Surveys Section -->
    <section class="surveys-section" id="surveys">
        <div class="container">
            <div class="text-center mb-5">
                <h2 class="section-title">Featured Surveys</h2>
                <p class="section-subtitle">Start earning by sharing your opinions today</p>
            </div>

            <div class="row">
                <div class="col-lg-10 mx-auto">
                    <!-- Survey 1 -->
                     @foreach ($getsurveys as $survey)
                    <div class="survey-card">
                        <h3 class="survey-title">{{ $survey->{'surveytitle'} ?? 'N/A' }}</h3>
                        <p class="survey-description">{{ $survey->{'surveydescription'} ?? 'N/A' }}</p>
                        <div class="survey-meta">
                            <div class="meta-item">
                                <i class="fas fa-users"></i>
                                <span>{{ $survey->{'participants'} ?? 'N/A' }} participants</span>
                            </div>
                            <div class="meta-item">
                                <i class="fas fa-clock"></i>
                                <span>{{ $survey->{'minutes'} ?? 'N/A' }} minutes</span>
                            </div>
                            <div class="reward-badge">
                                Earn ${{ $survey->{'rewardamount'} ?? 'N/A' }}
                            </div>
                            <button class="btn btn-primary-custom ms-auto" 
        data-bs-toggle="modal" 
        data-bs-target="#loginModal">
    Take Survey <i class="fas fa-arrow-right ms-2"></i>
</button>

                        </div>
                    </div>

                     @endforeach

                 
                </div>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer id="about">
        <div class="container">
            <div class="row">
                <div class="col-md-4 mb-4">
                    <h4><i class="fas fa-globe me-2"></i>Survey Hub</h4>
                    <p class="mt-3">Connecting voices from around the world to create meaningful change through surveys and insights.</p>
                </div>
                <div class="col-md-4 mb-4">
                    <h5>Quick Links</h5>
                    <ul class="list-unstyled mt-3">
                        <li><a href="#" class="text-white-50 text-decoration-none">About Us</a></li>
                        <li><a href="#" class="text-white-50 text-decoration-none">How It Works</a></li>
                        <li><a href="#" class="text-white-50 text-decoration-none">Privacy Policy</a></li>
                        <li><a href="#" class="text-white-50 text-decoration-none">Terms of Service</a></li>
                    </ul>
                </div>
                <div class="col-md-4 mb-4">
                    <h5>Connect With Us</h5>
                    <div class="mt-3">
                        <a href="#" class="text-white me-3"><i class="fab fa-facebook fa-2x"></i></a>
                        <a href="#" class="text-white me-3"><i class="fab fa-twitter fa-2x"></i></a>
                        <a href="#" class="text-white me-3"><i class="fab fa-linkedin fa-2x"></i></a>
                        <a href="#" class="text-white"><i class="fab fa-instagram fa-2x"></i></a>
                    </div>
                </div>
            </div>
            <hr class="bg-white">
            <div class="text-center py-3">
                <p class="mb-0">&copy; 2025 Survey Hub. All rights reserved.</p>
            </div>
        </div>
    </footer>

    <!-- Login Modal -->
    <div class="modal fade" id="loginModal" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Login to Survey Hub</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body p-4">
                    <form action="/login" method="POST">
                         @csrf
                        <div class="mb-3">
                            <label for="loginEmail" class="form-label">Email Address</label>
                            <input type="email" class="form-control" id="loginEmail" name="email" required>
                        </div>
                        <div class="mb-3">
                            <label for="loginPassword" class="form-label">Password</label>
                            <input type="password" class="form-control" id="loginPassword" name="password" required>
                        </div>
                        <div class="mb-3 form-check">
                            <input type="checkbox" class="form-check-input" id="rememberMe" name="remember">
                            <label class="form-check-label" for="rememberMe">Remember me</label>
                        </div>
                        <button type="submit" class="btn btn-primary-custom w-100">Login</button>
                    </form>
                    <div class="text-center mt-3">
<a href="#" class="text-decoration-none" data-bs-toggle="modal" data-bs-target="#forgotPasswordModal" data-bs-dismiss="modal">Forgot Password?</a>
                    </div>
                    <hr class="my-4">
                    <p class="text-center">Don't have an account? <a href="#" data-bs-toggle="modal" data-bs-target="#signupModal" data-bs-dismiss="modal">Sign Up</a></p>
                </div>
            </div>
        </div>
    </div>
<!-- Forgot Password Modal -->
<div class="modal fade" id="forgotPasswordModal" tabindex="-1" aria-labelledby="forgotPasswordModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      
      <div class="modal-header">
        <h5 class="modal-title" id="forgotPasswordModalLabel">Reset Your Password</h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
      </div>

      <div class="modal-body p-4">
        <form id="forgotPasswordForm" method="POST" action="/forgot-password">
          @csrf

          <!-- Email -->
          <div class="mb-3">
            <label for="fpEmail" class="form-label">Email Address</label>
            <input type="email" class="form-control" id="fpEmail" name="email" placeholder="Enter your registered email" required>
          </div>

          <!-- OTP -->
          <div class="mb-3">
            <label for="fpOtp" class="form-label">OTP Code</label>
            <input type="text" class="form-control" id="fpOtp" name="otp" placeholder="Enter OTP sent to your email" required>
          </div>

          <!-- New Password -->
          <div class="mb-3">
            <label for="fpNewPassword" class="form-label">New Password</label>
            <input type="password" class="form-control" id="fpNewPassword" name="new_password" placeholder="Enter new password" required>
          </div>

          <!-- Confirm Password -->
          <div class="mb-3">
            <label for="fpConfirmPassword" class="form-label">Confirm New Password</label>
            <input type="password" class="form-control" id="fpConfirmPassword" name="new_password_confirmation" placeholder="Confirm new password" required>
          </div>

          <!-- Buttons -->
          <div class="d-flex justify-content-between mb-3">
            <button type="button" class="btn btn-outline-secondary" id="sendOtpBtn">Send OTP</button>
            <button type="button" class="btn btn-outline-secondary" id="resendOtpBtn">Resend OTP</button>
          </div>

          <button type="submit" class="btn btn-primary-custom w-100">Submit</button>
        </form>
      </div>
    </div>
  </div>
</div>
<!-- About Modal - Replace the existing one -->
<div class="modal fade" id="aboutModal" tabindex="-1" aria-labelledby="aboutModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="aboutModalLabel">
                    <i class="fas fa-info-circle me-2"></i> About Survey Hub
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-4">
                <!-- Company Info -->
                <div class="text-center mb-4">
                    <div class="feature-icon mx-auto mb-3" style="width: 80px; height: 80px;">
                        <i class="fas fa-globe"></i>
                    </div>
                    <h4 class="fw-bold mb-3">Survey Hub</h4>
                    <p class="lead text-muted">
                        Connecting Voices, Shaping the Future
                    </p>
                </div>

                <!-- Company Description -->
                <div class="mb-4">
                    <p class="mb-3">
                        <strong>Survey Hub</strong> is a research and innovation company headquartered in 
                        <strong>Silicon Valley, California, USA</strong>. We are at the forefront of advancing 
                        global progress through cutting-edge research in:
                    </p>

                    <!-- Key Areas -->
                    <div class="row g-3 mb-4">
                        <div class="col-6 col-md-3">
                            <div class="text-center p-3 rounded" style="background: #f8fafc;">
                                <i class="fas fa-seedling" style="font-size: 2rem; color: #10b981;"></i>
                                <div class="small fw-bold mt-2">Agriculture</div>
                            </div>
                        </div>
                        <div class="col-6 col-md-3">
                            <div class="text-center p-3 rounded" style="background: #f8fafc;">
                                <i class="fas fa-heartbeat" style="font-size: 2rem; color: #ef4444;"></i>
                                <div class="small fw-bold mt-2">Healthcare</div>
                            </div>
                        </div>
                        <div class="col-6 col-md-3">
                            <div class="text-center p-3 rounded" style="background: #f8fafc;">
                                <i class="fas fa-robot" style="font-size: 2rem; color: #4f46e5;"></i>
                                <div class="small fw-bold mt-2">AI & ML</div>
                            </div>
                        </div>
                        <div class="col-6 col-md-3">
                            <div class="text-center p-3 rounded" style="background: #f8fafc;">
                                <i class="fas fa-database" style="font-size: 2rem; color: #06b6d4;"></i>
                                <div class="small fw-bold mt-2">Data Science</div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Mission Statement -->
                <div class="alert alert-info rounded-3 mb-4" style="background: linear-gradient(135deg, rgba(79, 70, 229, 0.1), rgba(6, 182, 212, 0.1)); border: none;">
                    <h6 class="fw-bold mb-2">
                        <i class="fas fa-bullseye me-2"></i> Our Mission
                    </h6>
                    <p class="mb-0">
                        To connect people and organizations worldwide through research, data, and technology — 
                        empowering them to make smarter, evidence-based decisions for a sustainable and 
                        connected future.
                    </p>
                </div>

                <!-- Impact Stats -->
                <div class="row g-3 mb-4">
                    <div class="col-4">
                        <div class="text-center">
                            <div class="h3 fw-bold mb-0" style="color: #4f46e5;">5M+</div>
                            <small class="text-muted">Active Users</small>
                        </div>
                    </div>
                    <div class="col-4">
                        <div class="text-center">
                            <div class="h3 fw-bold mb-0" style="color: #10b981;">180+</div>
                            <small class="text-muted">Countries</small>
                        </div>
                    </div>
                    <div class="col-4">
                        <div class="text-center">
                            <div class="h3 fw-bold mb-0" style="color: #06b6d4;">50K+</div>
                            <small class="text-muted">Daily Surveys</small>
                        </div>
                    </div>
                </div>

                <!-- Vision Quote -->
                <div class="text-center p-3 rounded" style="background: linear-gradient(135deg, #f8fafc, #e2e8f0);">
                    <i class="fas fa-quote-left text-muted me-2"></i>
                    <em class="text-muted">
                        From AI to agriculture — we innovate for a smarter, healthier, and more connected world.
                    </em>
                    <i class="fas fa-quote-right text-muted ms-2"></i>
                </div>

                <!-- Location -->
                <div class="text-center mt-4">
                    <p class="text-muted mb-0">
                        <i class="fas fa-map-marker-alt me-2"></i>
                        Headquartered in Silicon Valley, California, USA
                    </p>
                </div>
            </div>
            <div class="modal-footer border-0 pt-0">
                <button type="button" class="btn btn-outline-secondary rounded-pill px-4" data-bs-dismiss="modal">
                    Close
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Learn More Modal -->
<div class="modal fade" id="learnMoreModal" tabindex="-1" aria-labelledby="learnMoreModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="learnMoreModalLabel">
                    <i class="fas fa-lightbulb me-2"></i> How Survey Hub Works
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-4">
                
                <!-- Introduction -->
                <div class="text-center mb-4">
                    <div class="feature-icon mx-auto mb-3" style="width: 80px; height: 80px;">
                        <i class="fas fa-rocket"></i>
                    </div>
                    <h4 class="fw-bold mb-3">Start Earning Today!</h4>
                    <p class="lead text-muted">
                        Join millions of users worldwide who are making money by sharing their opinions
                    </p>
                </div>

                <!-- How It Works Steps -->
                <div class="mb-4">
                    <h5 class="fw-bold mb-3">
                        <i class="fas fa-list-ol me-2" style="color: #4f46e5;"></i> Simple Steps to Get Started
                    </h5>

                    <!-- Step 1 -->
                    <div class="d-flex mb-4">
                        <div class="flex-shrink-0">
                            <div class="rounded-circle d-flex align-items-center justify-content-center me-3" 
                                 style="width: 50px; height: 50px; background: linear-gradient(135deg, #4f46e5, #06b6d4); color: white; font-weight: bold; font-size: 1.2rem;">
                                1
                            </div>
                        </div>
                        <div class="flex-grow-1">
                            <h6 class="fw-bold mb-2">Create Your Free Account</h6>
                            <p class="text-muted mb-0">
                                Sign up in less than 60 seconds. No credit card required. 
                                Just your email and basic information.
                            </p>
                        </div>
                    </div>

                    <!-- Step 2 -->
                    <div class="d-flex mb-4">
                        <div class="flex-shrink-0">
                            <div class="rounded-circle d-flex align-items-center justify-content-center me-3" 
                                 style="width: 50px; height: 50px; background: linear-gradient(135deg, #10b981, #059669); color: white; font-weight: bold; font-size: 1.2rem;">
                                2
                            </div>
                        </div>
                        <div class="flex-grow-1">
                            <h6 class="fw-bold mb-2">Browse Available Surveys</h6>
                            <p class="text-muted mb-0">
                                Access thousands of surveys tailored to your profile. 
                                Choose topics that interest you most.
                            </p>
                        </div>
                    </div>

                    <!-- Step 3 -->
                    <div class="d-flex mb-4">
                        <div class="flex-shrink-0">
                            <div class="rounded-circle d-flex align-items-center justify-content-center me-3" 
                                 style="width: 50px; height: 50px; background: linear-gradient(135deg, #f59e0b, #d97706); color: white; font-weight: bold; font-size: 1.2rem;">
                                3
                            </div>
                        </div>
                        <div class="flex-grow-1">
                            <h6 class="fw-bold mb-2">Share Your Opinions</h6>
                            <p class="text-muted mb-0">
                                Complete surveys by answering questions honestly. 
                                Most surveys take 5-15 minutes.
                            </p>
                        </div>
                    </div>

                    <!-- Step 4 -->
                    <div class="d-flex mb-4">
                        <div class="flex-shrink-0">
                            <div class="rounded-circle d-flex align-items-center justify-content-center me-3" 
                                 style="width: 50px; height: 50px; background: linear-gradient(135deg, #ef4444, #dc2626); color: white; font-weight: bold; font-size: 1.2rem;">
                                4
                            </div>
                        </div>
                        <div class="flex-grow-1">
                            <h6 class="fw-bold mb-2">Get Paid Instantly</h6>
                            <p class="text-muted mb-0">
                                Earn rewards immediately after completing surveys. 
                                Withdraw via PayPal, bank transfer, or crypto.
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Key Features -->
                <div class="mb-4">
                    <h5 class="fw-bold mb-3">
                        <i class="fas fa-star me-2" style="color: #f59e0b;"></i> Why Users Love Survey Hub
                    </h5>

                    <div class="row g-3">
                        <div class="col-md-6">
                            <div class="p-3 rounded h-100" style="background: #f8fafc; border-left: 4px solid #4f46e5;">
                                <h6 class="fw-bold mb-2">
                                    <i class="fas fa-dollar-sign text-success me-2"></i> High-Paying Surveys
                                </h6>
                                <p class="text-muted small mb-0">
                                    Earn $0.50 to $50 per survey. Average users make $100-$500/month
                                </p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="p-3 rounded h-100" style="background: #f8fafc; border-left: 4px solid #10b981;">
                                <h6 class="fw-bold mb-2">
                                    <i class="fas fa-clock text-primary me-2"></i> Flexible Schedule
                                </h6>
                                <p class="text-muted small mb-0">
                                    Work anytime, anywhere. Complete surveys at your own pace
                                </p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="p-3 rounded h-100" style="background: #f8fafc; border-left: 4px solid #f59e0b;">
                                <h6 class="fw-bold mb-2">
                                    <i class="fas fa-gift text-warning me-2"></i> Referral Bonuses
                                </h6>
                                <p class="text-muted small mb-0">
                                    Earn $1 for every friend you refer who completes their first survey
                                </p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="p-3 rounded h-100" style="background: #f8fafc; border-left: 4px solid #ef4444;">
                                <h6 class="fw-bold mb-2">
                                    <i class="fas fa-shield-alt text-danger me-2"></i> Secure & Private
                                </h6>
                                <p class="text-muted small mb-0">
                                    Your data is protected with bank-level encryption and security
                                </p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Earnings Info -->
                <div class="alert alert-success rounded-3 mb-4" style="background: linear-gradient(135deg, rgba(16, 185, 129, 0.1), rgba(5, 150, 105, 0.1)); border: none;">
                    <h6 class="fw-bold mb-2">
                        <i class="fas fa-money-bill-wave me-2"></i> Earnings Potential
                    </h6>
                    <ul class="mb-0 small">
                        <li><strong>Beginner:</strong> $50-$150/month (5-10 surveys weekly)</li>
                        <li><strong>Regular:</strong> $150-$300/month (10-20 surveys weekly)</li>
                        <li><strong>Active:</strong> $300-$500/month (20+ surveys weekly)</li>
                        <li><strong>Plus Referrals:</strong> Unlimited earning potential!</li>
                    </ul>
                </div>

                <!-- Payment Methods -->
                <div class="mb-4">
                    <h5 class="fw-bold mb-3">
                        <i class="fas fa-wallet me-2" style="color: #06b6d4;"></i> Flexible Payment Options
                    </h5>
                    <div class="row g-2">
                        <div class="col-4">
                            <div class="text-center p-3 rounded" style="background: #f8fafc;">
                                <i class="fab fa-paypal" style="font-size: 2rem; color: #0070ba;"></i>
                                <div class="small fw-bold mt-2">PayPal</div>
                            </div>
                        </div>
                        <div class="col-4">
                            <div class="text-center p-3 rounded" style="background: #f8fafc;">
                                <i class="fas fa-university" style="font-size: 2rem; color: #2563eb;"></i>
                                <div class="small fw-bold mt-2">Bank Transfer</div>
                            </div>
                        </div>
                        <div class="col-4">
                            <div class="text-center p-3 rounded" style="background: #f8fafc;">
                                <i class="fab fa-bitcoin" style="font-size: 2rem; color: #f59e0b;"></i>
                                <div class="small fw-bold mt-2">Crypto</div>
                            </div>
                        </div>
                    </div>
                    <p class="text-center text-muted small mt-3 mb-0">
                        <i class="fas fa-info-circle me-1"></i> Minimum withdrawal: $50 | No fees
                    </p>
                </div>

                <!-- FAQ -->
                <div class="mb-4">
                    <h5 class="fw-bold mb-3">
                        <i class="fas fa-question-circle me-2" style="color: #ef4444;"></i> Quick FAQs
                    </h5>
                    
                    <div class="accordion" id="faqAccordion">
                        <div class="accordion-item border-0 mb-2">
                            <h2 class="accordion-header">
                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faq1">
                                    Is Survey Hub really free to join?
                                </button>
                            </h2>
                            <div id="faq1" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                                <div class="accordion-body">
                                    Yes! Signing up is 100% free. No hidden fees, no credit card required. 
                                    Start earning immediately after registration.
                                </div>
                            </div>
                        </div>

                        <div class="accordion-item border-0 mb-2">
                            <h2 class="accordion-header">
                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faq2">
                                    How much can I really earn?
                                </button>
                            </h2>
                            <div id="faq2" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                                <div class="accordion-body">
                                    Most users earn $100-$500 per month depending on activity level. 
                                    Each survey pays $0.50-$50. Plus unlimited referral bonuses!
                                </div>
                            </div>
                        </div>

                        <div class="accordion-item border-0 mb-2">
                            <h2 class="accordion-header">
                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faq3">
                                    When do I get paid?
                                </button>
                            </h2>
                            <div id="faq3" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                                <div class="accordion-body">
                                    Earnings are credited instantly after survey completion. 
                                    Withdraw anytime once you reach the $50 minimum threshold.
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- CTA -->
                <div class="text-center p-4 rounded" style="background: linear-gradient(135deg, rgba(79, 70, 229, 0.1), rgba(6, 182, 212, 0.1));">
                    <h5 class="fw-bold mb-3">Ready to Start Earning?</h5>
                    <p class="text-muted mb-3">
                        Join over 5 million users already earning with Survey Hub
                    </p>
                    <button class="btn btn-primary-custom btn-lg px-5" data-bs-toggle="modal" data-bs-target="#signupModal" data-bs-dismiss="modal">
                        Create Free Account <i class="fas fa-arrow-right ms-2"></i>
                    </button>
                    <p class="text-muted small mt-3 mb-0">
                        <i class="fas fa-check-circle text-success me-1"></i> No credit card required
                    </p>
                </div>

            </div>
            <div class="modal-footer border-0 pt-0">
                <button type="button" class="btn btn-outline-secondary rounded-pill px-4" data-bs-dismiss="modal">
                    Close
                </button>
            </div>
        </div>
    </div>
</div>

    <!-- Signup Modal -->
    <div class="modal fade" id="signupModal" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Join Survey Hub Today</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body p-4">
                    <form action="/register" method="POST">
                      @csrf
                        <div class="mb-3">
                            <label for="signupName" class="form-label">Full Name</label>
                            <input type="text" class="form-control" id="signupName" name="name" required>
                        </div>

                          <div class="mb-3">
                            <label for="refCode" class="form-label">Referral Code(Optional)</label>
                            <input type="text" class="form-control" id="refCode" name="refcode" >
                        </div>
                        <div class="mb-3">
                            <label for="signupEmail" class="form-label">Email Address</label>
                            <input type="email" class="form-control" id="signupEmail" name="email" required>
                        </div>
                        <div class="mb-3">
                            <label for="signupCountry" class="form-label">Country</label>
                            <select class="form-select" id="signupCountry" name="country" required>
                                <option value="">Select your country</option>
                            
                                 @foreach($countries as $country)
        <option value="{{ $country->{'id'} }}">{{ $country->{'country_name'} }}</option>
    @endforeach
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="signupPassword" class="form-label">Password</label>
                            <input type="password" class="form-control" id="signupPassword" name="password" required>
                        </div>
                        <div class="mb-3">
                            <label for="signupPasswordConfirm" class="form-label">Confirm Password</label>
                            <input type="password" class="form-control" id="signupPasswordConfirm" name="password_confirmation" required>
                        </div>
                        <div class="mb-3 form-check">
                            <input type="checkbox" class="form-check-input" id="agreeTerms" name="agreeTerms" required>
                            <label class="form-check-label" for="agreeTerms">
                                I agree to the Terms of Service and Privacy Policy
                            </label>
                        </div>
                        <button type="submit" class="btn btn-primary-custom w-100">Create Account</button>
                    </form>
                    <hr class="my-4">
                    <p class="text-center">Already have an account? <a href="#" data-bs-toggle="modal" data-bs-target="#loginModal" data-bs-dismiss="modal">Login</a></p>
                </div>
            </div>
        </div>
    </div>
<script>
  document.getElementById('sendOtpBtn').addEventListener('click', function() {
      const email = document.getElementById('fpEmail').value;
      if(!email) {
          alert('Please enter your email first.');
          return;
      }
      // Example AJAX request (you can modify this for your backend route)
      fetch('/sendPasswordMessage', {
          method: 'POST',
          headers: {
              'Content-Type': 'application/json',
              'X-CSRF-TOKEN': '{{ csrf_token() }}'
          },
          body: JSON.stringify({ email })
      })
      .then(res => res.json())
      .then(data => alert(data.message))
      .catch(err => alert('Failed to send OTP.'));
  });

  document.getElementById('resendOtpBtn').addEventListener('click', function() {
      document.getElementById('sendOtpBtn').click();
  });
</script>
<script>
// Find the Learn More button and add the data attributes
document.addEventListener('DOMContentLoaded', function() {
    const learnMoreBtns = document.querySelectorAll('.btn-outline-custom');
    learnMoreBtns.forEach(btn => {
        if (btn.textContent.includes('Learn More')) {
            btn.setAttribute('data-bs-toggle', 'modal');
            btn.setAttribute('data-bs-target', '#learnMoreModal');
        }
    });
});
</script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>