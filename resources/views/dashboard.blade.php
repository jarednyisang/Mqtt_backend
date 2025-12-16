<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title> Survey Hub</title>
    <link rel="icon" href="data:,">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
<style>
    :root {
            --primary: #4f46e5;
            --secondary: #06b6d4;
            --success: #10b981;
            --warning: #f59e0b;
            --danger: #ef4444;
            --dark: #1e293b;
            --light-bg: #f8fafc;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: var(--light-bg);
        }

        /* Sidebar Styles */
        .sidebar {
            position: fixed;
            top: 0;
            left: 0;
            height: 100vh;
            width: 260px;
            background: linear-gradient(180deg, var(--primary), var(--secondary));
            color: white;
            padding: 0;
            z-index: 1000;
            transition: transform 0.3s;
        }

        .sidebar-header {
            padding: 1.5rem;
            border-bottom: 1px solid rgba(255,255,255,0.1);
        }

        .sidebar-brand {
            font-size: 1.5rem;
            font-weight: 700;
            color: white;
            text-decoration: none;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .sidebar-menu {
            padding: 1rem 0;
            list-style: none;
            margin: 0;
        }

        .sidebar-menu li {
            margin: 0;
        }

        .sidebar-menu a {
            display: flex;
            align-items: center;
            padding: 0.9rem 1.5rem;
            color: rgba(255,255,255,0.8);
            text-decoration: none;
            transition: all 0.3s;
            gap: 0.8rem;
        }

        .sidebar-menu a:hover,
        .sidebar-menu a.active {
            background: rgba(255,255,255,0.15);
            color: white;
            border-left: 4px solid white;
            padding-left: calc(1.5rem - 4px);
        }

        .sidebar-menu i {
            width: 20px;
            font-size: 1.1rem;
        }

        /* Main Content */
        .main-content {
            margin-left: 260px;
            padding: 2rem;
            min-height: 100vh;
        }

        .top-bar {
            background: white;
            border-radius: 15px;
            padding: 1.2rem 1.5rem;
            margin-bottom: 2rem;
            box-shadow: 0 2px 10px rgba(0,0,0,0.05);
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .user-info {
            display: flex;
            align-items: center;
            gap: 1rem;
        }

        .user-avatar {
            width: 45px;
            height: 45px;
            border-radius: 50%;
            background: linear-gradient(135deg, var(--primary), var(--secondary));
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: 600;
            font-size: 1.2rem;
        }

        /* Stats Cards */
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 1.5rem;
            margin-bottom: 2rem;
        }

        .stat-card {
            background: white;
            border-radius: 15px;
            padding: 1.5rem;
            box-shadow: 0 2px 10px rgba(0,0,0,0.05);
            transition: transform 0.3s, box-shadow 0.3s;
            position: relative;
            overflow: hidden;
        }

        .stat-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 5px 20px rgba(0,0,0,0.1);
        }

        .stat-card::before {
            content: '';
            position: absolute;
            top: 0;
            right: 0;
            width: 100px;
            height: 100px;
            background: linear-gradient(135deg, var(--primary), var(--secondary));
            opacity: 0.1;
            border-radius: 50%;
            transform: translate(30%, -30%);
        }

        .stat-icon {
            width: 50px;
            height: 50px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.5rem;
            margin-bottom: 1rem;
        }

        .stat-icon.primary {
            background: linear-gradient(135deg, var(--primary), var(--secondary));
            color: white;
        }

        .stat-icon.success {
            background: linear-gradient(135deg, #10b981, #059669);
            color: white;
        }

        .stat-icon.warning {
            background: linear-gradient(135deg, #f59e0b, #d97706);
            color: white;
        }

        .stat-icon.info {
            background: linear-gradient(135deg, #3b82f6, #2563eb);
            color: white;
        }

        .stat-label {
            color: #64748b;
            font-size: 0.9rem;
            margin-bottom: 0.5rem;
        }

        .stat-value {
            font-size: 2rem;
            font-weight: 700;
            color: var(--dark);
        }

        .stat-change {
            font-size: 0.85rem;
            color: var(--success);
            margin-top: 0.5rem;
        }

        /* Card Styles */
        .card-custom {
            background: white;
            border-radius: 15px;
            padding: 1.5rem;
            box-shadow: 0 2px 10px rgba(0,0,0,0.05);
            margin-bottom: 1.5rem;
        }

        .card-header-custom {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 1.5rem;
            padding-bottom: 1rem;
            border-bottom: 2px solid var(--light-bg);
        }

        .card-title {
            font-size: 1.3rem;
            font-weight: 600;
            color: var(--dark);
            margin: 0;
        }

        /* Referral Card */
        .referral-box {
            background: linear-gradient(135deg, var(--primary), var(--secondary));
            color: white;
            border-radius: 12px;
            padding: 1.5rem;
            margin-bottom: 1rem;
        }

        .referral-link-box {
            background: rgba(255,255,255,0.15);
            border-radius: 8px;
            padding: 1rem;
            display: flex;
            align-items: center;
            gap: 1rem;
            margin-top: 1rem;
        }

        .referral-link {
            flex: 1;
            color: white;
            font-family: monospace;
            font-size: 0.9rem;
            word-break: break-all;
        }

        .btn-copy {
            background: white;
            color: var(--primary);
            border: none;
            padding: 0.5rem 1rem;
            border-radius: 6px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s;
        }

        .btn-copy:hover {
            transform: scale(1.05);
        }

        /* Survey Cards */
        .survey-item {
            background: white;
            border-radius: 12px;
            padding: 1.5rem;
            margin-bottom: 1rem;
            border-left: 4px solid var(--primary);
            transition: all 0.3s;
        }

        .survey-item:hover {
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
            transform: translateX(5px);
        }

        .survey-header {
            display: flex;
            justify-content: space-between;
            align-items: start;
            margin-bottom: 0.8rem;
        }

        .survey-title {
            font-size: 1.1rem;
            font-weight: 600;
            color: var(--dark);
            margin: 0;
        }

        .survey-reward {
            background: linear-gradient(135deg, var(--success), #059669);
            color: white;
            padding: 0.3rem 0.8rem;
            border-radius: 20px;
            font-weight: 600;
            font-size: 0.9rem;
        }

        .survey-description {
            color: #64748b;
            font-size: 0.9rem;
            margin-bottom: 1rem;
        }

        .survey-meta {
            display: flex;
            gap: 1.5rem;
            align-items: center;
            flex-wrap: wrap;
        }

        .meta-badge {
            display: flex;
            align-items: center;
            gap: 0.4rem;
            color: #64748b;
            font-size: 0.85rem;
        }

        /* Buttons */
        .btn-primary-custom {
            background: linear-gradient(135deg, var(--primary), var(--secondary));
            border: none;
            padding: 0.7rem 1.5rem;
            border-radius: 8px;
            font-weight: 600;
            color: white;
            transition: all 0.3s;
        }

        .btn-primary-custom:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(79, 70, 229, 0.3);
            color: white;
        }

        .btn-outline-custom {
            border: 2px solid var(--primary);
            color: var(--primary);
            background: transparent;
            padding: 0.7rem 1.5rem;
            border-radius: 8px;
            font-weight: 600;
            transition: all 0.3s;
        }

        .btn-outline-custom:hover {
            background: var(--primary);
            color: white;
        }

        .btn-success-custom {
            background: linear-gradient(135deg, var(--success), #059669);
            border: none;
            padding: 0.7rem 1.5rem;
            border-radius: 8px;
            font-weight: 600;
            color: white;
        }

        /* Withdrawal Form */
        .withdrawal-method {
            display: flex;
            align-items: center;
            padding: 1rem;
            border: 2px solid #e2e8f0;
            border-radius: 10px;
            margin-bottom: 1rem;
            cursor: pointer;
            transition: all 0.3s;
        }

        .withdrawal-method:hover {
            border-color: var(--primary);
            background: #f8fafc;
        }

        .withdrawal-method input[type="radio"] {
            margin-right: 1rem;
            width: 20px;
            height: 20px;
            accent-color: var(--primary);
        }

        .withdrawal-icon {
            width: 40px;
            height: 40px;
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-right: 1rem;
            font-size: 1.3rem;
        }

        /* Responsive */
        .mobile-menu-btn {
            display: none;
            background: var(--primary);
            color: white;
            border: none;
            padding: 0.7rem 1rem;
            border-radius: 8px;
            font-size: 1.2rem;
            position: fixed;
            top: 1rem;
            left: 1rem;
            z-index: 1001;
        }

        @media (max-width: 768px) {
            .sidebar {
                transform: translateX(-100%);
            }

            .sidebar.active {
                transform: translateX(0);
            }

            .main-content {
                margin-left: 0;
                padding: 1rem;
            }

            .mobile-menu-btn {
                display: block;
            }

            .stats-grid {
                grid-template-columns: 1fr;
            }

            .top-bar {
                margin-top: 3rem;
            }

            .survey-meta {
                flex-direction: column;
                align-items: flex-start;
                gap: 0.5rem;
            }
        }

        /* Animations */
        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .animate-in {
            animation: fadeIn 0.5s ease-out;
        }
   /* Donation Card Animations */
    .card-custom .fa-heart {
        animation: heartbeat 1.5s ease-in-out infinite;
    }

    @keyframes heartbeat {
        0%, 100% {
            transform: scale(1);
        }
        25% {
            transform: scale(1.1);
        }
        50% {
            transform: scale(1);
        }
    }

    /* Modal Button Hover Effect */
    #donationModal .btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 5px 15px rgba(239, 68, 68, 0.3);
        transition: all 0.3s ease;
    }

    /* Success Badge Styling */
    .alert-success {
        background: linear-gradient(135deg, #10b981, #059669);
        color: white;
        border: none;
    }
</style>
</head>
<body>

    <!-- Mobile Menu Button -->
    <button class="mobile-menu-btn" onclick="toggleSidebar()">
        <i class="fas fa-bars"></i>
    </button>

    <!-- Sidebar -->
      @include('sidebar')

    <!-- Main Content -->
    <main class="main-content">
        <!-- Top Bar -->
        <div class="top-bar">
            <div>
                <h2 class="mb-0">Welcome back, {{ $fullname }}!</h2>
                <p class="text-muted mb-0">Here's what's happening with your account today.</p>
            </div>
            <div class="user-info">
                <div>
                    <div class="fw-bold">{{ $fullname }}</div>
                    <div class="text-muted small">{{ $email }}</div>
                </div>
                <div class="user-avatar">JD</div>
            </div>
        </div>
@include('handler')
        <!-- Stats Cards -->
        <div class="stats-grid animate-in">
            <div class="stat-card">
                <div class="stat-icon primary">
                    <i class="fas fa-dollar-sign"></i>
                </div>
                <div class="stat-label">Available Balance</div>
                <div class="stat-value">${{$availablebalance}}</div>
                <div class="stat-change">
                    <i class="fas fa-arrow-up"></i> balance
                </div>
            </div>

            <div class="stat-card">
                <div class="stat-icon success">
                    <i class="fas fa-check-circle"></i>
                </div>
                <div class="stat-label">Surveys Completed</div>
                <div class="stat-value">{{$completedsurvey}}</div>
                <div class="stat-change">
                    <i class="fas fa-arrow-up"></i> completed
                </div>
            </div>

            <div class="stat-card">
                <div class="stat-icon warning">
                    <i class="fas fa-users"></i>
                </div>
                <div class="stat-label">Total Referrals</div>
                <div class="stat-value">{{ $totalreferal }}</div>
                <div class="stat-change">
                    <i class="fas fa-arrow-up"></i> friends
                </div>
            </div>

            <div class="stat-card">
                <div class="stat-icon info">
                    <i class="fas fa-chart-line"></i>
                </div>
                <div class="stat-label">Total Earnings</div>
                <div class="stat-value">${{$lifetimetotal}}</div>
                <div class="stat-change">
                    <i class="fas fa-info-circle"></i> Lifetime earnings
                </div>
            </div>
        </div>

        <!-- Main Grid -->
        <div class="row">
            <!-- Left Column -->
            <div class="col-lg-8">
                <!-- Available Surveys -->
                <div class="card-custom animate-in">
                    <div class="card-header-custom">
                        <h3 class="card-title">Available Surveys</h3>
                        <a href="#" class="text-decoration-none">View All <i class="fas fa-arrow-right ms-1"></i></a>
                    </div>
               
 @foreach ($getsurveys as $survey)
                    <div class="survey-item">
                        <div class="survey-header">
                            <h4 class="survey-title">{{ $survey->{'surveytitle'} ?? 'N/A' }}</h4>
                            <span class="survey-reward">${{ $survey->{'rewardamount'} ?? 'N/A' }}</span>
                        </div>
                        <p class="survey-description">
{{ $survey->{'surveydescription'} ?? 'N/A' }}                        </p>
                        <div class="survey-meta">
                            <span class="meta-badge">
                                <i class="fas fa-clock"></i> {{ $survey->{'minutes'} ?? 'N/A' }}   minutes
                            </span>
                            <span class="meta-badge">
                                <i class="fas fa-users"></i> {{ $survey->{'participants'} ?? 'N/A' }} participants
                            </span>
                            <span class="meta-badge">
                                <i class="fas fa-star"></i> {{ $survey->{'surveytype'} ?? 'N/A' }}
                            </span>
                                 <button 
  class="btn btn-primary-custom ms-auto start-survey-btn" 
  data-bs-toggle="modal" 
  data-bs-target="#surveyModal"
  data-id="{{ $survey->id }}" 
  data-title="{{ $survey->surveytitle }}" 
  data-amount="{{ $survey->rewardamount }}" 
  data-description="{{ $survey->surveydescription }}">
  Start Survey <i class="fas fa-arrow-right ms-1"></i>
</button>
                        </div>
                    </div>
 @endforeach
               

             
                </div>
            </div>

            <!-- Right Column -->
            <div class="col-lg-4">
                <!-- Withdraw Money -->
                <div class="card-custom animate-in">
                    <div class="card-header-custom">
                        <h3 class="card-title">Withdraw Money</h3>
                    </div>
                    <div class="mb-3">
                        <div class="text-center mb-3">
                            <div class="text-muted small">Available Balance</div>
                            <div class="h2 fw-bold text-success">${{$availablebalance}}</div>
                        </div>
                    </div>
                    <button class="btn btn-success-custom w-100 mb-2" data-bs-toggle="modal" data-bs-target="#withdrawModal">
                        <i class="fas fa-money-bill-wave me-2"></i> Withdraw Now
                    </button>
                    <div class="text-center text-muted small">
                        <i class="fas fa-info-circle"></i> Minimum withdrawal: $50.00
                    </div>
                </div>

                <!-- Referral Program -->
                <div class="card-custom animate-in">
                    <div class="card-header-custom">
                        <h3 class="card-title">Referral Program</h3>
                    </div>
                    <div class="referral-box">
                        <h5 class="mb-2">Invite Friends & Earn!</h5>
                        <p class="mb-3 small">Get $1 for each friend who joins and completes their first survey.</p>
                        <div class="referral-link-box">
                            <div class="referral-link" id="referralLink">
                              {{ $code }}
                            </div>
                            <button class="btn-copy" onclick="copyReferralLink()">
                                <i class="fas fa-copy"></i> Copy
                            </button>
                        </div>
                    </div>
                    <div class="mt-3">
                        <div class="d-flex justify-content-between mb-2">
                            <span class="text-muted">Total Referrals:</span>
                            <span class="fw-bold">{{ $totalreferal }}</span>
                        </div>
                        <div class="d-flex justify-content-between mb-2">
                            <span class="text-muted">Referral Earnings:</span>
                            <span class="fw-bold text-success">$ {{ $totalreferal * 1 }}</span>
                        </div>
                        
                        <div class="d-flex justify-content-between">
                            <span class="text-muted">Pending:</span>
                            <span class="fw-bold text-warning">{{ $pendingreferal }} friends</span>
                        </div>
                    </div>
                </div>

                <!-- Donation Program -->
<div class="card-custom animate-in">
    <div class="card-header-custom">
        <h3 class="card-title">Support Our Cause</h3>
        <span class="badge bg-info">Optional</span>
    </div>
    <div class="text-center mb-3">
        <i class="fas fa-heart" style="font-size: 3rem; color: #ef4444;"></i>
        <h5 class="mt-3 mb-2">Make a Difference Today</h5>
        <p class="text-muted small mb-3">
            We removed withdrawal fees! Instead, consider an optional donation to support our platform 
            and help women & children in Palestinian refugee camps.
        </p>
    </div>
    
    @if($donated == 1)
        <div class="alert alert-success" style="border-radius: 10px;">
            <i class="fas fa-check-circle me-2"></i>
            <strong>Thank you for your generous donation!</strong>
            <p class="mb-0 small mt-1">Your support is making a real difference. 🙏</p>
        </div>
    @else
        <button class="btn btn-primary-custom w-100" data-bs-toggle="modal" data-bs-target="#donationModal">
            <i class="fas fa-hands-helping me-2"></i> Donate (Optional)
        </button>
    @endif
    
    <div class="text-center text-muted small mt-3">
        <i class="fas fa-shield-alt"></i> 100% secure & optional donation
    </div>
</div>
                <!-- Quick Stats -->
                <div class="card-custom animate-in">
                    <div class="card-header-custom">
                        <h3 class="card-title">Quick Stats</h3>
                    </div>
                 <div class="mb-3 pb-3 border-bottom">
  <div class="d-flex justify-content-between mb-2">
    <span class="text-muted">This Week</span>
    <span class="fw-bold">{{ $thisWeekCount }} surveys</span>
  </div>

  <div class="progress" style="height: 8px;">
    @php
      // Prevent overflow above 100%
      $progress = min($thisWeekCount, 100);
    @endphp
    <div class="progress-bar" 
         role="progressbar" 
         style="width: {{ $progress }}%; 
                background: linear-gradient(90deg, var(--bs-primary), var(--bs-success));"
         aria-valuenow="{{ $progress }}" 
         aria-valuemin="0" 
         aria-valuemax="100">
    </div>
  </div>
</div>

                   <div class="mb-3 pb-3 border-bottom">
  <div class="d-flex justify-content-between mb-2">
    <span class="text-muted">This Month</span>
    <span class="fw-bold">{{ $thisMonthCount }} surveys</span>
  </div>
  <div class="progress" style="height: 8px;">
    @php
      $monthProgress = min($thisMonthCount, 100);
    @endphp
    <div class="progress-bar" 
         role="progressbar" 
         style="width: {{ $monthProgress }}%; background: linear-gradient(90deg, var(--bs-success), #059669);"
         aria-valuenow="{{ $monthProgress }}" 
         aria-valuemin="0" 
         aria-valuemax="100">
    </div>
  </div>
</div>

<div>
  <div class="d-flex justify-content-between mb-2">
    <span class="text-muted">This Year</span>
    <span class="fw-bold text-success">{{ $thisYearCount }} surveys</span>
  </div>
  <div class="progress" style="height: 8px;">
    @php
      $yearProgress = min($thisYearCount, 100);
    @endphp
    <div class="progress-bar bg-success" 
         role="progressbar" 
         style="width: {{ $yearProgress }}%;"
         aria-valuenow="{{ $yearProgress }}" 
         aria-valuemin="0" 
         aria-valuemax="100">
    </div>
  </div>
</div>

                </div>
            </div>
        </div>
    </main>

    <!-- Withdrawal Modal -->
    <div class="modal fade" id="withdrawModal" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content" style="border-radius: 20px;">
                <div class="modal-header" style="background: linear-gradient(135deg, var(--success), #059669); color: white; border-radius: 20px 20px 0 0;">
                    <h5 class="modal-title">Withdraw Funds</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body p-4">
                    <div class="text-center mb-4">
                        <div class="text-muted small">Available Balance</div>
                        <div class="h3 fw-bold text-success">${{$availablebalance}}</div>
                    </div>

                    <form action="/withdraw" method="POST">
                         @csrf
                        <div class="mb-4">
                            <label class="form-label fw-bold">Select Withdrawal Method</label>
                            
                            <label class="withdrawal-method">
                                <input type="radio" name="method" value="paypal" required>
                                <div class="withdrawal-icon" style="background: #0070ba; color: white;">
                                    <i class="fab fa-paypal"></i>
                                </div>
                                <div>
                                    <div class="fw-bold">PayPal</div>
                                    <div class="text-muted small">Instant transfer</div>
                                </div>
                            </label>

                            <label class="withdrawal-method">
                                <input type="radio" name="method" value="bank" required>
                                <div class="withdrawal-icon" style="background: #2563eb; color: white;">
                                    <i class="fas fa-university"></i>
                                </div>
                                <div>
                                    <div class="fw-bold">Bank Transfer</div>
                                    <div class="text-muted small">2-3 business days</div>
                                </div>
                            </label>

                            <label class="withdrawal-method">
                                <input type="radio" name="method" value="crypto" required>
                                <div class="withdrawal-icon" style="background: #f59e0b; color: white;">
                                    <i class="fab fa-bitcoin"></i>
                                </div>
                                <div>
                                    <div class="fw-bold">Cryptocurrency</div>
                                    <div class="text-muted small">Bitcoin, Ethereum, USDT</div>
                                </div>
                            </label>
                        </div>

                        <div class="mb-3">
                            <label for="withdrawAmount" class="form-label fw-bold">Withdrawal Amount</label>
                            <div class="input-group">
                                <span class="input-group-text">$</span>
                                <input type="number" class="form-control" id="withdrawAmount" name="amount" min="50" max="5000.00" step="0.01" required>
                            </div>
                            <div class="text-muted small mt-1">Minimum: $50.00 | Maximum: $5000.00</div>
                        </div>

                        <div class="mb-4">
                            <label for="accountDetails" class="form-label fw-bold">Account Details</label>
                            <input type="text" class="form-control" id="accountDetails" name="account_details" placeholder="Email, Account Number, or Wallet Address" required>
                        </div>

                        <button type="submit" class="btn btn-success-custom w-100">
                            <i class="fas fa-check-circle me-2"></i> Confirm Withdrawal
                        </button>
                    </form>

                    <div class="alert alert-info mt-3 mb-0" style="border-radius: 10px;">
                        <i class="fas fa-info-circle me-2"></i>
                        <small>Processing time varies by method. All withdrawals are processed securely.</small>
                    </div>
                </div>
            </div>
        </div>
    </div>

       <!-- Donation Modal -->
  <div class="modal fade" id="donationModal" tabindex="-1" aria-labelledby="donationModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content" style="border-radius: 20px;">
            <div class="modal-header" style="background: linear-gradient(135deg, #ef4444, #dc2626); color: white; border-radius: 20px 20px 0 0;">
                <h5 class="modal-title" id="donationModalLabel">
                    <i class="fas fa-heart me-2"></i> Support Survey Hub
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            
            <div class="modal-body p-4">
                <div class="text-center mb-4">
                    <i class="fas fa-heart" style="font-size: 3.5rem; color: #ef4444;"></i>
                    <h4 class="mt-3 mb-2">Help Us Grow Together</h4>
                    <p class="text-muted">
                        Your generous donation helps us maintain the platform, cover transaction fees, 
                        support underserved communities, and expand earning opportunities for everyone.
                    </p>
                </div>

                <div class="row g-3 mb-4">
                    <div class="col-4">
                        <div class="text-center p-3" style="background: #f8fafc; border-radius: 10px;">
                            <i class="fas fa-server" style="font-size: 2rem; color: var(--primary);"></i>
                            <div class="small text-muted mt-2">Platform Support</div>
                        </div>
                    </div>
                    <div class="col-4">
                        <div class="text-center p-3" style="background: #f8fafc; border-radius: 10px;">
                            <i class="fas fa-hands-helping" style="font-size: 2rem; color: var(--success);"></i>
                            <div class="small text-muted mt-2">Underserved Communities</div>
                        </div>
                    </div>
                    <div class="col-4">
                        <div class="text-center p-3" style="background: #f8fafc; border-radius: 10px;">
                            <i class="fas fa-credit-card" style="font-size: 2rem; color: var(--warning);"></i>
                            <div class="small text-muted mt-2">Transaction Fees</div>
                        </div>
                    </div>
                </div>

                <form action="/checkout" method="POST">
                    @csrf
                    
                    <div class="alert alert-info" style="border-radius: 10px;">
                        <i class="fas fa-info-circle me-2"></i>
                        <strong>Why donate?</strong>
                        <ul class="mb-0 mt-2 small">
                            <li>Support platform maintenance and new features</li>
                            <li>Help us reach and empower underserved communities</li>
                            <li>Cover transaction and processing fees</li>
                            <li>Enable faster payments and system improvements</li>
                        </ul>
                    </div>

                    <div class="text-center mb-3">
                        <p class="mb-2 text-muted small">
                            <i class="fas fa-lock"></i> Secure payment powered by Survey Hub
                        </p>
                    </div>

                    <button type="submit" class="btn w-100" style="background: linear-gradient(135deg, #ef4444, #dc2626); color: white; padding: 0.8rem; border-radius: 10px; font-weight: 600; border: none;">
                        <i class="fas fa-heart me-2"></i> Proceed to Donation
                    </button>
                </form>

                <div class="text-center mt-3">
                    <small class="text-muted">
                        <i class="fas fa-shield-alt"></i> Your transaction is 100% secure and encrypted
                    </small>
                </div>
            </div>
        </div>
    </div>
</div>


    <!-- Survey Modal -->
<div class="modal fade" id="surveyModal" tabindex="-1" aria-labelledby="surveyModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">

      <div class="modal-header bg-success text-white">
        <h5 class="modal-title" id="surveyModalLabel">Start Survey</h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>

      <form action="/createfeedback" method="POST">
        @csrf
        <!-- Hidden inputs -->
        <input type="hidden" id="surveyId" name="surveyId">
        <input type="hidden" id="surveyAmount" name="surveyAmount">

        <div class="modal-body">
          <h5 id="surveyTitleDisplay" class="text-center fw-bold mb-3"></h5>

          <!-- Survey description -->
          <p id="surveyDescriptionDisplay" class="text-muted small mb-4" style="white-space: pre-line;"></p>

          <div class="mb-3">
            <label for="surveyFeedback" class="form-label">Your Response (250–300 characters)</label>
            <textarea class="form-control" id="surveyFeedback" name="surveyFeedback" 
              rows="4" minlength="250" maxlength="300" required
              placeholder="Write your detailed response between 250 and 300 characters..."></textarea>
          </div>
        </div>

        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
          <button type="submit" class="btn btn-success">Submit Survey</button>
        </div>
      </form>

    </div>
  </div>
</div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Toggle Sidebar for Mobile
        function toggleSidebar() {
            const sidebar = document.getElementById('sidebar');
            sidebar.classList.toggle('active');
        }

        // Copy Referral Link
        function copyReferralLink() {
            const referralLink = document.getElementById('referralLink').textContent;
            navigator.clipboard.writeText(referralLink).then(function() {
                // Show success message
                const btn = event.target.closest('.btn-copy');
                const originalText = btn.innerHTML;
                btn.innerHTML = '<i class="fas fa-check"></i> Copied!';
                btn.style.background = '#10b981';
                btn.style.color = 'white';
                
                setTimeout(function() {
                    btn.innerHTML = originalText;
                    btn.style.background = 'white';
                    btn.style.color = 'var(--primary)';
                }, 2000);
            }, function(err) {
                alert('Failed to copy link');
            });
        }

        // Close sidebar when clicking outside on mobile
        document.addEventListener('click', function(event) {
            const sidebar = document.getElementById('sidebar');
            const menuBtn = document.querySelector('.mobile-menu-btn');
            
            if (window.innerWidth <= 768) {
                if (!sidebar.contains(event.target) && !menuBtn.contains(event.target)) {
                    sidebar.classList.remove('active');
                }
            }
        });
        // Add animation on scroll
        const observerOptions = {
            threshold: 0.1,
            rootMargin: '0px 0px -50px 0px'
        };

        const observer = new IntersectionObserver(function(entries) {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    entry.target.style.opacity = '1';
                    entry.target.style.transform = 'translateY(0)';
                }
            });
        }, observerOptions);

        document.querySelectorAll('.animate-in').forEach(el => {
            el.style.opacity = '0';
            el.style.transform = 'translateY(20px)';
            el.style.transition = 'opacity 0.5s ease, transform 0.5s ease';
            observer.observe(el);
        });

        //display data in take survey modal  

        document.addEventListener('DOMContentLoaded', () => {
  const modal = document.getElementById('surveyModal');
  const surveyIdInput = document.getElementById('surveyId');
  const surveyAmountInput = document.getElementById('surveyAmount');
  const titleDisplay = document.getElementById('surveyTitleDisplay');
  const descriptionDisplay = document.getElementById('surveyDescriptionDisplay');

  modal.addEventListener('show.bs.modal', event => {
    const button = event.relatedTarget;
    const id = button.getAttribute('data-id');
    const title = button.getAttribute('data-title');
    const amount = button.getAttribute('data-amount');
    const description = button.getAttribute('data-description');

    surveyIdInput.value = id;
    surveyAmountInput.value = amount;
    titleDisplay.textContent = title;
    descriptionDisplay.textContent = description || "No description provided for this survey.";
  });
});
    </script>
</body>
</html>