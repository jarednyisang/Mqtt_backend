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
  
</style>
</head>
<body>

    <!-- Mobile Menu Button -->
    <button class="mobile-menu-btn" onclick="toggleSidebar()">
        <i class="fas fa-bars"></i>
    </button>

    <!-- Sidebar -->
      @include('adminsidebar')

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

      

        <!-- Main Grid -->
        <div class="row">
            <!-- Left Column -->
            <div class="col-lg-12">
              <div class="card-custom animate-in">

        <div class="card-header-custom">
    <div class="d-flex justify-content-between align-items-center w-100">
        <button class="btn btn-primary-custom" data-bs-toggle="modal" data-bs-target="#createSurveyModal">
            <i class="fas fa-plus-circle me-2"></i> Create Survey
        </button>
    </div>
</div>

    <div class="table-responsive">
         <table class="table table-striped table-hover">
            <thead>
                <tr>
                     <th>Date</th>
                     <th>Title</th>
                    <th>Survey</th>
                   <th>Type</th>
                    <th>Amount</th>
                    <th>Participants</th>
                   <th>View</th>

                </tr>
            </thead>
             <tbody>
                   @foreach ($getsurveys as $survey)
              <tr>
                <td>{{ $survey->{'date'} ?? 'N/A' }}</td>
                <td>{{ $survey->{'surveytitle'} ?? 'N/A' }}</td>
                <td>{{ $survey->{'surveydescription'} ?? 'N/A' }}</td>
                <td>{{ $survey->{'surveytype'} ?? 'N/A' }}</td>
                <td>{{ $survey->rewardamount }}</td>
                <td>{{ $survey->participants }}</td>
<td>
  <a href="/viewsurvey/{{ $survey->id }}" class="btn btn-sm btn-primary-custom">
      <i class="fas fa-eye me-1"></i> View
  </a>
</td>


              </tr>
            @endforeach
               
            </tbody>
        </table>
    </div>
</div>

            </div>

           
        </div>
    </main>

<!-- Create Survey Modal -->
<div class="modal fade" id="createSurveyModal" tabindex="-1">
  <div class="modal-dialog modal-dialog-centered modal-lg">
    <div class="modal-content" style="border-radius: 20px;">
      <div class="modal-header" style="background: linear-gradient(135deg, var(--primary), var(--secondary)); color: white; border-radius: 20px 20px 0 0;">
        <h5 class="modal-title"><i class="fas fa-clipboard-list me-2"></i> Create New Survey</h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body p-4">

        <form action="/createsurvey" method="POST">
          @csrf
          
          <!-- Survey Title -->
          <div class="mb-3">
            <label class="form-label fw-bold">Survey Title</label>
            <input type="text" class="form-control" name="title" placeholder="Enter survey title" required>
          </div>

          <!-- Description -->
          <div class="mb-3">
            <label class="form-label fw-bold">Description</label>
            <textarea class="form-control" name="description" rows="3" placeholder="Enter short survey description" required></textarea>
          </div>

          <!-- Survey Type Dropdown -->
          <div class="mb-3">
            <label class="form-label fw-bold">Survey Type</label>
            <select class="form-select" name="type" required>
              <option value="">-- Select Survey Type --</option>
              <option value="Quick Completion">Quick Completion</option>
              <option value="Popular">Popular</option>
              <option value="Featured Survey">Featured Survey</option>
            </select>
          </div>


            <!-- Minutes to take to complete survey -->
          <div class="mb-3">
            <label class="form-label fw-bold"> Minutes</label>
            <div class="input-group">
              <span class="input-group-text">$</span>
              <input type="number" class="form-control" name="minutes" placeholder="e.g. 5" step="0.01" required>
            </div>
          </div>
          <!-- Reward Amount -->
          <div class="mb-3">
            <label class="form-label fw-bold">Reward Amount</label>
            <div class="input-group">
              <span class="input-group-text">$</span>
              <input type="number" class="form-control" name="reward" placeholder="e.g. 5.00" step="0.01" required>
            </div>
          </div>

          <!-- Number of Participants -->
          <div class="mb-3">
            <label class="form-label fw-bold">Number of Participants</label>
            <input type="number" class="form-control" name="participants" placeholder="e.g. 100" required>
          </div>

          <!-- Buttons -->
          <div class="text-end">
            <button type="button" class="btn btn-outline-custom me-2" data-bs-dismiss="modal">Cancel</button>
            <button type="submit" class="btn btn-primary-custom">
              <i class="fas fa-paper-plane me-2"></i> Create Survey
            </button>
          </div>
        </form>

      </div>
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
    </script>
</body>
</html>