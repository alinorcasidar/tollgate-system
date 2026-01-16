<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CDO-MALAYBALAY Tollgate System - Login Required</title>
    <!-- Firebase SDK -->
    <script src="https://www.gstatic.com/firebasejs/9.22.0/firebase-app-compat.js"></script>
    <script src="https://www.gstatic.com/firebasejs/9.22.0/firebase-auth-compat.js"></script>
    <script src="https://www.gstatic.com/firebasejs/9.22.0/firebase-database-compat.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        /* ====== RESET & BASE STYLES ====== */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        
        body {
            background-color: #f5f7fa;
            color: #333;
            line-height: 1.6;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }
        
        .container {
            max-width: 1400px;
            margin: 0 auto;
            padding: 20px;
            flex: 1;
        }
        
        /* ====== LOGIN PAGE STYLES ====== */
        #loginPage {
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            background: linear-gradient(135deg, #1e3a8a 0%, #3b82f6 100%);
        }
        
        .login-container {
            background-color: white;
            border-radius: 16px;
            padding: 40px;
            width: 100%;
            max-width: 450px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.2);
            text-align: center;
        }
        
        .login-header {
            margin-bottom: 30px;
        }
        
        .login-header h1 {
            color: #1e3a8a;
            font-size: 2rem;
            margin-bottom: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 15px;
        }
        
        .login-header p {
            color: #6b7280;
            font-size: 1rem;
        }
        
        .login-icon {
            background: linear-gradient(135deg, #1e3a8a 0%, #3b82f6 100%);
            color: white;
            width: 70px;
            height: 70px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 20px;
            font-size: 1.8rem;
        }
        
        .login-form {
            text-align: left;
        }
        
        .form-group {
            margin-bottom: 22px;
        }
        
        label {
            display: block;
            margin-bottom: 8px;
            font-weight: 600;
            color: #4b5563;
            font-size: 0.95rem;
        }
        
        .input-with-icon {
            position: relative;
        }
        
        .input-with-icon i {
            position: absolute;
            left: 15px;
            top: 50%;
            transform: translateY(-50%);
            color: #6b7280;
        }
        
        .input-with-icon input {
            width: 100%;
            padding: 14px 14px 14px 45px;
            border: 2px solid #d1d5db;
            border-radius: 8px;
            font-size: 1rem;
            transition: border-color 0.3s;
        }
        
        .input-with-icon input:focus {
            border-color: #3b82f6;
            outline: none;
        }
        
        .login-btn {
            width: 100%;
            padding: 14px;
            background-color: #3b82f6;
            color: white;
            border: none;
            border-radius: 8px;
            font-size: 1rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            margin-top: 10px;
        }
        
        .login-btn:hover {
            background-color: #2563eb;
        }
        
        .login-footer {
            margin-top: 25px;
            text-align: center;
            color: #6b7280;
            font-size: 0.9rem;
        }
        
        .login-footer a {
            color: #3b82f6;
            text-decoration: none;
            font-weight: 600;
        }
        
        .login-footer a:hover {
            text-decoration: underline;
        }
        
        /* ====== HEADER ====== */
        header {
            background: linear-gradient(135deg, #1e3a8a 0%, #3b82f6 100%);
            color: white;
            padding: 25px;
            border-radius: 12px;
            margin-bottom: 30px;
            box-shadow: 0 6px 15px rgba(0, 0, 0, 0.1);
        }
        
        .header-content {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        
        .header-text h1 {
            font-size: 2.3rem;
            margin-bottom: 5px;
            display: flex;
            align-items: center;
            gap: 15px;
        }
        
        .header-text p {
            opacity: 0.9;
            font-size: 1.05rem;
        }
        
        .toll-price {
            background-color: #fbbf24;
            color: #1e3a8a;
            padding: 10px 20px;
            border-radius: 25px;
            font-weight: bold;
            font-size: 1.2rem;
            margin-top: 10px;
            display: inline-block;
        }
        
        .user-info {
            text-align: right;
            color: white;
        }
        
        /* ====== FIREBASE STATUS ====== */
        .firebase-status {
            padding: 15px;
            border-radius: 8px;
            margin-bottom: 20px;
            display: flex;
            align-items: center;
            gap: 10px;
            font-weight: 600;
        }
        
        .status-connected {
            background-color: #d1fae5;
            border: 2px solid #10b981;
            color: #065f46;
        }
        
        .status-disconnected {
            background-color: #fee2e2;
            border: 2px solid #ef4444;
            color: #991b1b;
        }
        
        /* ====== TABS ====== */
        .tabs-nav {
            display: flex;
            background-color: white;
            border-radius: 10px;
            overflow: hidden;
            margin-bottom: 30px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
        }
        
        .tab-btn {
            flex: 1;
            padding: 18px;
            border: none;
            background: none;
            font-size: 1.1rem;
            font-weight: 600;
            cursor: pointer;
            color: #6b7280;
            transition: all 0.3s;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
        }
        
        .tab-btn.active {
            background-color: #3b82f6;
            color: white;
        }
        
        .tab-btn:hover:not(.active) {
            background-color: #f3f4f6;
        }
        
        .tab-content {
            display: none;
        }
        
        .tab-content.active {
            display: block;
        }
        
        /* ====== TOLLGATE SYSTEM ====== */
        .main-content {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 30px;
            margin-bottom: 30px;
        }
        
        @media (max-width: 1100px) {
            .main-content {
                grid-template-columns: 1fr;
            }
        }
        
        .entrance-section {
            background-color: white;
            border-radius: 12px;
            padding: 30px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
        }
        
        .section-title {
            color: #1e3a8a;
            border-bottom: 2px solid #e5e7eb;
            padding-bottom: 15px;
            margin-bottom: 25px;
            display: flex;
            align-items: center;
            gap: 10px;
            font-size: 1.4rem;
        }
        
        .gate-indicator {
            display: flex;
            align-items: center;
            justify-content: center;
            height: 140px;
            margin: 20px 0;
            border-radius: 10px;
            font-size: 1.6rem;
            font-weight: bold;
            transition: all 0.3s ease;
        }
        
        .gate-closed {
            background-color: #fee2e2;
            color: #dc2626;
            border: 3px solid #dc2626;
        }
        
        .gate-open {
            background-color: #d1fae5;
            color: #059669;
            border: 3px solid #059669;
            animation: pulse 1.5s infinite;
        }
        
        @keyframes pulse {
            0% { box-shadow: 0 0 0 0 rgba(5, 150, 105, 0.4); }
            70% { box-shadow: 0 0 0 10px rgba(5, 150, 105, 0); }
            100% { box-shadow: 0 0 0 0 rgba(5, 150, 105, 0); }
        }
        
        /* ====== TAP TO REGISTER SECTION ====== */
        .tap-section {
            background-color: #e3f2fd;
            padding: 30px;
            border-radius: 12px;
            text-align: center;
            margin-bottom: 25px;
        }
        
        .tap-animation {
            font-size: 4rem;
            color: #3b82f6;
            margin: 20px 0;
            animation: bounce 2s infinite;
        }
        
        @keyframes bounce {
            0%, 100% { transform: translateY(0); }
            50% { transform: translateY(-10px); }
        }
        
        .scanned-card {
            background: linear-gradient(135deg, #1e3a8a 0%, #3b82f6 100%);
            color: white;
            padding: 20px;
            border-radius: 10px;
            margin: 20px 0;
            font-family: monospace;
            font-size: 1.5rem;
            font-weight: bold;
            letter-spacing: 2px;
        }
        
        /* ====== FORM ELEMENTS ====== */
        input, select, textarea {
            width: 100%;
            padding: 14px;
            border: 2px solid #d1d5db;
            border-radius: 8px;
            font-size: 1rem;
            transition: border-color 0.3s;
        }
        
        input:focus, select:focus, textarea:focus {
            border-color: #3b82f6;
            outline: none;
        }
        
        /* ====== BUTTONS ====== */
        .btn {
            padding: 14px 24px;
            border: none;
            border-radius: 8px;
            font-size: 1rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
        }
        
        .btn-primary {
            background-color: #3b82f6;
            color: white;
        }
        
        .btn-primary:hover {
            background-color: #2563eb;
        }
        
        .btn-success {
            background-color: #10b981;
            color: white;
        }
        
        .btn-success:hover {
            background-color: #059669;
        }
        
        .btn-danger {
            background-color: #ef4444;
            color: white;
        }
        
        .btn-danger:hover {
            background-color: #dc2626;
        }
        
        .btn-secondary {
            background-color: #6b7280;
            color: white;
        }
        
        .btn-secondary:hover {
            background-color: #4b5563;
        }
        
        .btn-warning {
            background-color: #f59e0b;
            color: white;
        }
        
        .btn-warning:hover {
            background-color: #d97706;
        }
        
        .btn-sm {
            padding: 8px 16px;
            font-size: 0.9rem;
        }
        
        /* ====== MESSAGES ====== */
        .message {
            padding: 15px;
            border-radius: 8px;
            margin-bottom: 20px;
            display: none;
        }
        
        .success-message {
            background-color: #d1fae5;
            border: 2px solid #10b981;
            color: #065f46;
        }
        
        .error-message {
            background-color: #fee2e2;
            border: 2px solid #ef4444;
            color: #991b1b;
        }
        
        .info-message {
            background-color: #dbeafe;
            border: 2px solid #3b82f6;
            color: #1e40af;
        }
        
        .warning-message {
            background-color: #fffbeb;
            border: 2px solid #f59e0b;
            color: #92400e;
        }
        
        /* ====== VEHICLE CLASSIFICATION CARDS ====== */
        .vehicle-class-cards {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 15px;
            margin-bottom: 25px;
        }
        
        @media (max-width: 900px) {
            .vehicle-class-cards {
                grid-template-columns: 1fr;
            }
        }
        
        .vehicle-class-card {
            border: 2px solid #e5e7eb;
            border-radius: 10px;
            padding: 20px;
            text-align: center;
            cursor: pointer;
            transition: all 0.3s;
        }
        
        .vehicle-class-card:hover {
            transform: translateY(-3px);
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
        }
        
        .vehicle-class-card.selected {
            border-color: #3b82f6;
            background-color: #f0f7ff;
        }
        
        .vehicle-class-icon {
            font-size: 2.5rem;
            margin-bottom: 15px;
            color: #3b82f6;
        }
        
        .vehicle-class-title {
            font-size: 1.2rem;
            font-weight: 600;
            margin-bottom: 10px;
            color: #1e3a8a;
        }
        
        .vehicle-class-fee {
            font-size: 1.5rem;
            font-weight: 700;
            color: #10b981;
            margin-bottom: 10px;
        }
        
        .vehicle-class-desc {
            font-size: 0.9rem;
            color: #6b7280;
            margin-bottom: 10px;
        }
        
        /* ====== TOLL FEE DISPLAY ====== */
        .toll-fee-display {
            background: linear-gradient(135deg, #1e3a8a 0%, #3b82f6 100%);
            color: white;
            padding: 20px;
            border-radius: 10px;
            margin: 20px 0;
            text-align: center;
        }
        
        .toll-fee-display h4 {
            margin-bottom: 10px;
            font-size: 1.2rem;
        }
        
        .toll-fee-amount {
            font-size: 2rem;
            font-weight: 700;
            margin-bottom: 5px;
        }
        
        .toll-fee-type {
            font-size: 0.9rem;
            opacity: 0.9;
        }
        
        /* ====== ADMIN PANEL ====== */
        .stats-cards {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
            gap: 20px;
            margin-bottom: 30px;
        }
        
        .stat-card {
            background-color: white;
            border-radius: 12px;
            padding: 25px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.06);
            display: flex;
            align-items: center;
            gap: 20px;
            transition: transform 0.3s, box-shadow 0.3s;
        }
        
        .stat-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.1);
        }
        
        .stat-icon {
            width: 60px;
            height: 60px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.8rem;
            color: white;
        }
        
        .stat-icon.users { background-color: #10b981; }
        .stat-icon.balance { background-color: #3b82f6; }
        .stat-icon.active { background-color: #8b5cf6; }
        .stat-icon.blocked { background-color: #ef4444; }
        
        .admin-main-content {
            display: grid;
            grid-template-columns: 1fr 2fr;
            gap: 30px;
            margin-bottom: 30px;
        }
        
        @media (max-width: 1100px) {
            .admin-main-content {
                grid-template-columns: 1fr;
            }
        }
        
        .card-management, .cards-list {
            background-color: white;
            border-radius: 12px;
            padding: 30px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.06);
        }
        
        .rfid-card-item {
            border: 2px solid #e5e7eb;
            border-radius: 10px;
            padding: 20px;
            margin-bottom: 15px;
            transition: all 0.3s;
            cursor: pointer;
        }
        
        .rfid-card-item:hover {
            border-color: #3b82f6;
            box-shadow: 0 4px 10px rgba(59, 130, 246, 0.1);
        }
        
        .rfid-card-item.selected {
            border-color: #3b82f6;
            background-color: #f0f7ff;
        }
        
        .rfid-card-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 15px;
        }
        
        .rfid-card-id {
            font-weight: 700;
            color: #1e3a8a;
            font-size: 1.1rem;
            font-family: monospace;
            letter-spacing: 1px;
        }
        
        .rfid-card-status {
            padding: 5px 12px;
            border-radius: 20px;
            font-size: 0.85rem;
            font-weight: 600;
        }
        
        .status-active {
            background-color: #d1fae5;
            color: #065f46;
        }
        
        .status-inactive {
            background-color: #fee2e2;
            color: #991b1b;
        }
        
        .status-blocked {
            background-color: #fef3c7;
            color: #92400e;
        }
        
        /* ====== MODAL ====== */
        .modal {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.5);
            z-index: 1000;
            justify-content: center;
            align-items: center;
        }
        
        .modal-content {
            background-color: white;
            padding: 30px;
            border-radius: 12px;
            max-width: 500px;
            width: 90%;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.2);
        }
        
        .modal-title {
            color: #1e3a8a;
            margin-bottom: 20px;
            font-size: 1.4rem;
            display: flex;
            align-items: center;
            gap: 10px;
        }
        
        .modal-buttons {
            display: flex;
            gap: 10px;
            margin-top: 25px;
        }
        
        .modal-buttons .btn {
            flex: 1;
        }
        
        /* ====== COOLDOWN INDICATOR ====== */
        .cooldown-indicator {
            background-color: #fffbeb;
            border: 2px solid #f59e0b;
            color: #92400e;
            padding: 10px 15px;
            border-radius: 8px;
            margin: 10px 0;
            display: flex;
            align-items: center;
            gap: 10px;
        }
        
        /* ====== ACTIVITY LOG ====== */
        .status-log {
            background-color: #f9fafb;
            padding: 20px;
            border-radius: 10px;
            height: 400px;
            overflow-y: auto;
            margin-top: 15px;
        }
        
        .log-entry {
            padding: 12px;
            border-bottom: 1px solid #e5e7eb;
            font-size: 0.95rem;
        }
        
        .log-time {
            color: #6b7280;
            font-size: 0.85rem;
            margin-bottom: 5px;
        }
        
        /* ====== FOOTER ====== */
        footer {
            text-align: center;
            color: #6b7280;
            margin-top: 40px;
            padding-top: 20px;
            border-top: 1px solid #e5e7eb;
            font-size: 0.9rem;
        }
        
        /* ====== NOTIFICATION TOAST ====== */
        .notification-toast {
            position: fixed;
            top: 20px;
            right: 20px;
            padding: 15px 20px;
            border-radius: 8px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.2);
            z-index: 1001;
            display: flex;
            align-items: center;
            gap: 10px;
            max-width: 400px;
            animation: slideIn 0.3s ease;
            display: none;
        }
        
        @keyframes slideIn {
            from {
                transform: translateX(100%);
                opacity: 0;
            }
            to {
                transform: translateX(0);
                opacity: 1;
            }
        }
        
        .toast-success {
            background-color: #d1fae5;
            border: 2px solid #10b981;
            color: #065f46;
        }
        
        .toast-error {
            background-color: #fee2e2;
            border: 2px solid #ef4444;
            color: #991b1b;
        }
        
        .toast-info {
            background-color: #dbeafe;
            border: 2px solid #3b82f6;
            color: #1e40af;
        }
        
        .toast-warning {
            background-color: #fffbeb;
            border: 2px solid #f59e0b;
            color: #92400e;
        }
        
        /* ====== SYSTEM STATUS ====== */
        .system-status {
            display: flex;
            gap: 10px;
            margin-bottom: 15px;
        }
        
        .status-indicator {
            padding: 8px 15px;
            border-radius: 20px;
            font-size: 0.9rem;
            font-weight: 600;
            display: flex;
            align-items: center;
            gap: 5px;
        }
        
        .status-online {
            background-color: #d1fae5;
            color: #065f46;
        }
        
        .status-offline {
            background-color: #fee2e2;
            color: #991b1b;
        }
    </style>
</head>
<body>
    <!-- ====== LOGIN PAGE ====== -->
    <div id="loginPage" class="login-page">
        <div class="login-container">
            <div class="login-icon">
                <i class="fas fa-road"></i>
            </div>
            <div class="login-header">
                <h1> CDO-MALAYBALAY Tollgate System</h1>
                <p>RFID Tap & Cash Payment System with Firebase Realtime Database</p>
                <div class="toll-price">
                    <i class="fas fa-tag"></i> Toll Fee: ₱119.00 - ₱418.00
                </div>
            </div>
            
            <form class="login-form" id="loginForm">
                <div class="form-group">
                    <label for="loginUsername">Username</label>
                    <div class="input-with-icon">
                        <i class="fas fa-user"></i>
                        <input type="text" id="loginUsername" placeholder="Enter your username" required>
                    </div>
                </div>
                
                <div class="form-group">
                    <label for="loginPassword">Password</label>
                    <div class="input-with-icon">
                        <i class="fas fa-lock"></i>
                        <input type="password" id="loginPassword" placeholder="Enter your password" required>
                    </div>
                </div>
                
                <div class="message error-message" id="loginErrorMessage" style="display: none;">
                    <i class="fas fa-exclamation-circle"></i> <span id="loginErrorText">Invalid username or password</span>
                </div>
                
                <button type="submit" class="login-btn">
                    <i class="fas fa-sign-in-alt"></i> Login
                </button>
                
                <div class="login-footer">
                    <p><i class="fas fa-info-circle"></i>cpe4cgroup5</p>
                </div>
            </form>
        </div>
    </div>
    
    <!-- ====== MAIN APPLICATION (HIDDEN INITIALLY) ====== -->
    <div id="mainApp" style="display: none;">
        <div class="container">
            <!-- Firebase Status Indicator -->
            <div id="firebaseStatus" class="firebase-status status-disconnected">
                <i class="fas fa-circle" id="statusIcon"></i>
                <span id="statusText">Connecting to Firebase...</span>
            </div>
            
            <!-- Notification Toast -->
            <div id="notificationToast" class="notification-toast">
                <i class="fas fa-info-circle" id="toastIcon"></i>
                <div style="flex: 1;">
                    <div id="toastTitle" style="font-weight: 600;"></div>
                    <div id="toastMessage" style="font-size: 0.9rem; margin-top: 5px;"></div>
                </div>
                <button class="btn btn-sm btn-secondary" onclick="closeToast()">Close</button>
            </div>
            
            <header>
                <div class="header-content">
                    <div class="header-text">
                        <h1><i class="fas fa-road"></i> CDO-MALAYBALAY Tollgate System</h1>
                        <p>RFID Tap-Only System with Firebase Realtime Database</p>
                        <div class="toll-price">
                            <i class="fas fa-tag"></i> Toll Fee: ₱119.00 - ₱418.00
                        </div>
                    </div>
                    <div class="user-info">
                        <div id="userEmail" style="font-weight: 600;">Admin User</div>
                        <div style="font-size: 0.9rem;">System: <strong id="systemStatusText">Loading...</strong></div>
                        <button class="btn btn-secondary" id="logoutBtn" style="margin-top: 10px;">
                            <i class="fas fa-sign-out-alt"></i> Logout
                        </button>
                    </div>
                </div>
            </header>
            
            <!-- System Status -->
            <div class="system-status">
                <div class="status-indicator" id="raspiStatusIndicator">
                    <i class="fas fa-microchip"></i> Raspberry Pi: <span>Checking...</span>
                </div>
                <div class="status-indicator" id="rfidStatusIndicator">
                    <i class="fas fa-id-card"></i> RFID Reader: <span>Checking...</span>
                </div>
                <div class="status-indicator" id="gateStatusIndicator">
                    <i class="fas fa-door-closed"></i> Gates: <span>Closed</span>
                </div>
            </div>
            
            <!-- Tabs Navigation -->
            <div class="tabs-nav">
                <button class="tab-btn active" data-tab="tollgate">
                    <i class="fas fa-road"></i> Tollgate System
                </button>
                <button class="tab-btn" data-tab="register">
                    <i class="fas fa-id-card"></i> Tap to Register
                </button>
                <button class="tab-btn" data-tab="admin">
                    <i class="fas fa-cog"></i> Admin Panel
                </button>
                <button class="tab-btn" data-tab="transactions">
                    <i class="fas fa-history"></i> Transactions
                </button>
            </div>
            
            <!-- Tollgate System Tab -->
            <div id="tollgate" class="tab-content active">
                <div class="main-content">
                    <!-- RFID Entrance Section (TAP ONLY) -->
                    <section class="entrance-section">
                        <h2 class="section-title"><i class="fas fa-id-card"></i> RFID Entrance (TAP ONLY)</h2>
                        
                        <div class="gate-indicator gate-closed" id="rfidGateIndicator">
                            <i class="fas fa-times-circle"></i> GATE CLOSED
                        </div>
                        
                        <div class="tap-section">
                            <h3><i class="fas fa-hand-point-up"></i> TAP RFID CARD</h3>
                            <div class="tap-animation">
                                <i class="fas fa-id-card"></i>
                            </div>
                            <p>Simply tap your RFID card on the Raspberry Pi reader</p>
                            <p style="font-size: 0.9rem; color: #6b7280; margin-top: 10px;">
                                <i class="fas fa-info-circle"></i> 20-second cooldown between taps
                            </p>
                        </div>
                        
                        <!-- Card Status Display -->
                        <div class="message info-message" id="tapStatusMessage" style="display: none;">
                            <div id="tapStatusContent">
                                <!-- Card info will appear here when tapped -->
                            </div>
                        </div>
                        
                        <div class="message success-message" id="rfidSuccessMessage" style="display: none;">
                            <i class="fas fa-check-circle"></i> <span id="successText">Payment successful! Gate opening...</span>
                        </div>
                        
                        <div class="message error-message" id="rfidErrorMessage" style="display: none;">
                            <i class="fas fa-exclamation-circle"></i> <span id="errorText"></span>
                        </div>
                        
                        <!-- Cooldown Status -->
                        <div id="cooldownStatus" style="display: none;">
                            <!-- Cooldown cards will appear here -->
                        </div>
                        
                        <div style="text-align: center; margin-top: 20px; display: flex; flex-wrap: wrap; gap: 10px; justify-content: center;">
                            <button class="btn btn-primary" id="testRfidGateBtn">
                                <i class="fas fa-door-open"></i> Test RFID Gate
                            </button>
                            <button class="btn btn-info" id="resetReaderBtn">
                                <i class="fas fa-redo"></i> Reset Reader
                            </button>   
                        </div>
                    </section>
                    
                    <!-- Cash Entrance Section (FIXED) -->
                    <section class="entrance-section">
                        <h2 class="section-title"><i class="fas fa-money-bill-wave"></i> Cash Entrance</h2>
                        
                        <div class="gate-indicator gate-closed" id="cashGateIndicator">
                            <i class="fas fa-times-circle"></i> GATE CLOSED
                        </div>
                        
                        <div class="form-group">
                            <label for="cashVehicleClass">Select Vehicle Class:</label>
                            <select id="cashVehicleClass" class="form-control">
                                <option value="class1">Class 1: Regular Cars, Jeepneys, Vans (₱119)</option>
                                <option value="class2">Class 2: Buses, Trucks (₱299)</option>
                                <option value="class3">Class 3: Large Trucks, Trailers (₱418)</option>
                            </select>
                        </div>
                        
                        <div class="toll-fee-display" id="cashTollFeeDisplay">
                            <h4>Selected Toll Fee</h4>
                            <div class="toll-fee-amount">₱119.00</div>
                            <div class="toll-fee-type">Class 1: Regular Cars, Jeepneys, Vans</div>
                        </div>
                        
                        <div class="form-group">
                            <label for="cashAmount">Enter Payment Amount (₱):</label>
                            <input type="number" id="cashAmount" placeholder="Enter amount (minimum ₱119)" min="119" step="1" value="200">
                        </div>
                        
                        <button class="btn btn-primary" id="processCashBtn" style="width: 100%;">
                            <i class="fas fa-calculator"></i> Process Cash Payment
                        </button>
                        
                        <div class="message info-message" id="cashReceipt" style="display: none;">
                            <h4><i class="fas fa-receipt"></i> Payment Receipt</h4>
                            <div style="background-color: white; padding: 20px; border-radius: 8px; margin-top: 10px; border: 1px dashed #d1d5db;">
                                <div style="display: flex; justify-content: space-between; padding: 8px 0; border-bottom: 1px solid #f3f4f6;">
                                    <span>Vehicle Class:</span>
                                    <span id="receiptVehicleClass">Class 1</span>
                                </div>
                                <div style="display: flex; justify-content: space-between; padding: 8px 0; border-bottom: 1px solid #f3f4f6;">
                                    <span>Toll Fee:</span>
                                    <span id="receiptTollFee">₱119.00</span>
                                </div>
                                <div style="display: flex; justify-content: space-between; padding: 8px 0; border-bottom: 1px solid #f3f4f6;">
                                    <span>Amount Paid:</span>
                                    <span id="amountPaidDisplay">₱0.00</span>
                                </div>
                                <div style="display: flex; justify-content: space-between; padding: 8px 0; font-weight: bold; border-top: 2px solid #1e3a8a; margin-top: 5px; padding-top: 10px;">
                                    <span>Change:</span>
                                    <span id="changeDisplay">₱0.00</span>
                                </div>
                            </div>
                            <p style="text-align: center; margin-top: 15px;">
                                <i class="fas fa-check-circle"></i> Payment processed successfully
                            </p>
                        </div>
                        
                        <div class="message success-message" id="cashSuccessMessage" style="display: none;">
                            <i class="fas fa-check-circle"></i> <span id="cashSuccessText"></span>
                        </div>
                        
                        <div class="message error-message" id="cashErrorMessage" style="display: none;">
                            <i class="fas fa-exclamation-circle"></i> <span id="cashErrorText"></span>
                        </div>
                        
                        <div style="text-align: center; margin-top: 20px;">
                            <button class="btn btn-primary" id="testCashGateBtn">
                                <i class="fas fa-door-open"></i> Test Cash Gate
                            </button>
                        </div>
                    </section>
                </div>
                
                <!-- Live Activity Log -->
                <div class="entrance-section" style="margin-top: 30px;">
                    <h2 class="section-title"><i class="fas fa-broadcast-tower"></i>Activity</h2>
                    <button class="btn btn-sm btn-secondary" id="clearActivityLog" style="margin-bottom: 10px;">
                        <i class="fas fa-trash"></i> Clear Log
                    </button>
                    <div class="status-log" id="liveActivityLog">
                        <div style="text-align: center; padding: 20px; color: #6b7280;">
                            <i class="fas fa-id-card fa-2x" style="margin-bottom: 10px;"></i>
                            <p>Waiting for RFID card taps...</p>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Tap to Register Tab -->
            <div id="register" class="tab-content">
                <div class="entrance-section">
                    <h2 class="section-title"><i class="fas fa-user-plus"></i> Tap to Register New RFID Card</h2>
                    
                    <div class="tap-section">
                        <h3>Step 1: Tap RFID Card</h3>
                        <div class="tap-animation">
                            <i class="fas fa-id-card"></i>
                        </div>
                        <p>Tap the new RFID card on the Raspberry Pi reader</p>
                        <button class="btn btn-primary" id="startRegistrationBtn">
                            <i class="fas fa-play"></i> Start Card Registration
                        </button>
                    </div>
                    
                    <div id="registrationForm" style="display: none;">
                        <div class="scanned-card" id="scannedCardDisplay">
                            TAP CARD TO GET ID
                        </div>
                        
                        <div class="form-group">
                            <label for="regCardHolder">Card Holder Name *</label>
                            <div class="input-with-icon">
                                <i class="fas fa-user"></i>
                                <input type="text" id="regCardHolder" placeholder="Enter full name">
                            </div>
                        </div>
                        
                        <div class="form-group">
                            <label for="regCardEmail">Email Address *</label>
                            <div class="input-with-icon">
                                <i class="fas fa-envelope"></i>
                                <input type="email" id="regCardEmail" placeholder="Enter email for receipts">
                            </div>
                        </div>
                        
                        <!-- Vehicle Classification Section -->
                        <h3 class="section-title"><i class="fas fa-car"></i> Vehicle Classification</h3>
                        <p style="margin-bottom: 15px; color: #6b7280;">Select the vehicle class for this RFID card:</p>
                        
                        <div class="vehicle-class-cards">
                            <div class="vehicle-class-card" data-class="class1" onclick="selectVehicleClass('class1')">
                                <div class="vehicle-class-icon">
                                    <i class="fas fa-car"></i>
                                </div>
                                <div class="vehicle-class-title">Class 1</div>
                                <div class="vehicle-class-fee">₱119.00</div>
                                <div class="vehicle-class-desc">Regular Cars, Jeepneys, Vans</div>
                                <div style="font-size: 0.85rem; color: #9ca3af;">
                                    <i class="fas fa-info-circle"></i> Click to select
                                </div>
                            </div>
                            
                            <div class="vehicle-class-card" data-class="class2" onclick="selectVehicleClass('class2')">
                                <div class="vehicle-class-icon">
                                    <i class="fas fa-bus"></i>
                                </div>
                                <div class="vehicle-class-title">Class 2</div>
                                <div class="vehicle-class-fee">₱299.00</div>
                                <div class="vehicle-class-desc">Buses, Trucks</div>
                                <div style="font-size: 0.85rem; color: #9ca3af;">
                                    <i class="fas fa-info-circle"></i> Click to select
                                </div>
                            </div>
                            
                            <div class="vehicle-class-card" data-class="class3" onclick="selectVehicleClass('class3')">
                                <div class="vehicle-class-icon">
                                    <i class="fas fa-truck"></i>
                                </div>
                                <div class="vehicle-class-title">Class 3</div>
                                <div class="vehicle-class-fee">₱418.00</div>
                                <div class="vehicle-class-desc">Large Trucks, Trailers</div>
                                <div style="font-size: 0.85rem; color: #9ca3af;">
                                    <i class="fas fa-info-circle"></i> Click to select
                                </div>
                            </div>
                        </div>
                        
                        <!-- Selected Vehicle Class Display -->
                        <div class="toll-fee-display" id="selectedClassDisplay" style="display: none;">
                            <h4>Selected Vehicle Class</h4>
                            <div class="toll-fee-amount" id="selectedClassFee">₱119.00</div>
                            <div class="toll-fee-type" id="selectedClassType">Class 1: Regular Cars, Jeepneys, Vans</div>
                        </div>
                        
                        <!-- Hidden input for vehicle class -->
                        <input type="hidden" id="regVehicleClass" value="class1">
                        
                        <div class="form-group">
                            <label for="regVehicleInfo">Vehicle Details</label>
                            <textarea id="regVehicleInfo" rows="3" placeholder="e.g., Toyota Innova, Plate No: ABC 123"></textarea>
                        </div>
                        
                        <div class="form-group">
                            <label for="regInitialBalance">Initial Balance (₱) *</label>
                            <div class="input-with-icon">
                                <i class="fas fa-wallet"></i>
                                <input type="number" id="regInitialBalance" placeholder="Enter initial balance" min="100" value="500">
                            </div>
                            <p style="font-size: 0.85rem; color: #6b7280; margin-top: 5px;">
                                <i class="fas fa-info-circle"></i> Recommended minimum: ₱500 for Class 1, ₱1000 for Class 2/3
                            </p>
                        </div>
                        
                        <div class="message success-message" id="regSuccessMessage" style="display: none;">
                            <i class="fas fa-check-circle"></i> RFID card registered successfully!
                        </div>
                        
                        <div class="message error-message" id="regErrorMessage" style="display: none;">
                            <i class="fas fa-exclamation-circle"></i> <span id="regErrorText"></span>
                        </div>
                        
                        <button class="btn btn-success" id="registerCardBtn" style="width: 100%;">
                            <i class="fas fa-save"></i> Register This RFID Card
                        </button>
                        <button class="btn btn-secondary" id="cancelRegistrationBtn" style="width: 100%; margin-top: 10px;">
                            <i class="fas fa-times"></i> Cancel Registration
                        </button>
                    </div>
                </div>
            </div>
            
            <!-- Admin Panel Tab -->
            <div id="admin" class="tab-content">
                <div class="stats-cards">
                    <div class="stat-card">
                        <div class="stat-icon users">
                            <i class="fas fa-users"></i>
                        </div>
                        <div class="stat-content">
                            <h3 id="totalCards">0</h3>
                            <p>Total RFID Cards</p>
                        </div>
                    </div>
                    
                    <div class="stat-card">
                        <div class="stat-icon balance">
                            <i class="fas fa-wallet"></i>
                        </div>
                        <div class="stat-content">
                            <h3>₱<span id="totalBalance">0</span></h3>
                            <p>Total Balance</p>
                        </div>
                    </div>
                    
                    <div class="stat-card">
                        <div class="stat-icon active">
                            <i class="fas fa-check-circle"></i>
                        </div>
                        <div class="stat-content">
                            <h3 id="activeCards">0</h3>
                            <p>Active Cards</p>
                        </div>
                    </div>
                    
                    <div class="stat-card">
                        <div class="stat-icon blocked">
                            <i class="fas fa-ban"></i>
                        </div>
                        <div class="stat-content">
                            <h3 id="blockedCards">0</h3>
                            <p>Inactive Cards</p>
                        </div>
                    </div>
                </div>
                
                <div class="admin-main-content">
                    <div class="card-management">
                        <h2 class="section-title"><i class="fas fa-edit"></i> Quick Actions</h2>
                        
                        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 15px; margin-bottom: 25px;">
                            <button class="btn btn-primary" id="addBalanceQuickBtn">
                                <i class="fas fa-plus-circle"></i> Add Balance
                            </button>
                            <button class="btn btn-warning" id="deactivateCardBtn">
                                <i class="fas fa-ban"></i> Deactivate Card
                            </button>
                            <button class="btn btn-success" id="activateCardBtn">
                                <i class="fas fa-check"></i> Activate Card
                            </button>
                            <button class="btn btn-danger" id="deleteCardBtn">
                                <i class="fas fa-trash"></i> Delete Card
                            </button>
                        </div>
                        
                        <h3 class="section-title"><i class="fas fa-search"></i> Find Card</h3>
                        <div class="form-group">
                            <div class="input-with-icon">
                                <i class="fas fa-search"></i>
                                <input type="text" id="searchCardId" placeholder="Enter RFID Card ID (8 characters)">
                            </div>
                        </div>
                        
                        <div id="cardDetails" style="display: none;">
                            <div class="card-preview">
                                <div style="display: flex; justify-content: space-between; margin-bottom: 20px;">
                                    <div style="background-color: rgba(255, 255, 255, 0.2); padding: 5px 15px; border-radius: 20px; font-size: 0.9rem;">
                                        <span id="detailCardType">Class 1</span>
                                    </div>
                                    <div class="rfid-card-status status-active" id="detailCardStatus">Active</div>
                                </div>
                                <div style="font-size: 1.8rem; font-weight: 700; text-align: center; margin: 20px 0;">
                                    ₱<span id="detailCardBalance">0.00</span>
                                </div>
                                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 15px; margin-top: 20px;">
                                    <div>
                                        <p style="font-size: 0.85rem; opacity: 0.8; margin-bottom: 5px;">Card ID</p>
                                        <p style="font-weight: 600; font-family: monospace;" id="detailCardId">A3F5892C</p>
                                    </div>
                                    <div>
                                        <p style="font-size: 0.85rem; opacity: 0.8; margin-bottom: 5px;">Card Holder</p>
                                        <p style="font-weight: 600;" id="detailCardHolder">John Doe</p>
                                    </div>
                                    <div>
                                        <p style="font-size: 0.85rem; opacity: 0.8; margin-bottom: 5px;">Email</p>
                                        <p style="font-weight: 600;" id="detailCardEmail">john@email.com</p>
                                    </div>
                                    <div>
                                        <p style="font-size: 0.85rem; opacity: 0.8; margin-bottom: 5px;">Vehicle</p>
                                        <p style="font-weight: 600;" id="detailCardVehicle">Toyota Innova</p>
                                    </div>
                                </div>
                                <div style="margin-top: 20px; padding: 15px; background-color: #f3f4f6; border-radius: 8px;">
                                    <p style="font-size: 0.85rem; opacity: 0.8; margin-bottom: 5px;">Vehicle Class</p>
                                    <p style="font-weight: 600;" id="detailCardClass">Class 1: Regular Cars (₱119.00)</p>
                                </div>
                            </div>
                            
                            <div class="form-group">
                                <label for="addBalanceAmount">Add Balance Amount (₱)</label>
                                <input type="number" id="addBalanceAmount" placeholder="Enter amount" min="100" step="100" value="500">
                            </div>
                            
                            <button class="btn btn-success" id="processAddBalanceBtn" style="width: 100%;">
                                <i class="fas fa-plus"></i> Add Balance to Card
                            </button>
                        </div>
                    </div>
                    
                    <div class="cards-list">
                        <h2 class="section-title"><i class="fas fa-list"></i> All RFID Cards</h2>
                        
                        <div style="display: flex; gap: 10px; margin-bottom: 15px;">
                            <div class="input-with-icon" style="flex: 1;">
                                <i class="fas fa-search"></i>
                                <input type="text" id="searchCards" placeholder="Search by card ID, name, or email...">
                            </div>
                            <button class="btn btn-secondary" id="refreshCardsBtn">
                                <i class="fas fa-sync-alt"></i>
                            </button>
                        </div>
                        
                        <div class="cards-list-container" id="cardsListContainer">
                            <div style="text-align: center; padding: 40px; color: #6b7280;">
                                <i class="fas fa-id-card fa-3x" style="margin-bottom: 20px;"></i>
                                <p>Loading RFID cards from Firebase...</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Transactions Tab -->
            <div id="transactions" class="tab-content">
                <div class="entrance-section">
                    <h2 class="section-title"><i class="fas fa-history"></i> Transaction History</h2>
                    
                    <div style="display: flex; gap: 10px; margin-bottom: 20px; flex-wrap: wrap;">
                        <button class="btn btn-primary" id="showRFIDTransactions">
                            <i class="fas fa-id-card"></i> RFID Payments
                        </button>
                        <button class="btn btn-warning" id="showCashTransactions">
                            <i class="fas fa-money-bill"></i> Cash Payments
                        </button>
                        <button class="btn btn-success" id="showAllTransactions">
                            <i class="fas fa-list"></i> All Transactions
                        </button>
                        <button class="btn btn-danger" id="clearTransactionsBtn">
                            <i class="fas fa-trash"></i> Clear All
                        </button>
                        <button class="btn btn-secondary" id="refreshTransactionsBtn">
                            <i class="fas fa-sync-alt"></i> Refresh
                        </button>
                    </div>
                    
                    <div class="status-log" id="transactionsLog">
                        <div style="text-align: center; padding: 40px; color: #6b7280;">
                            <i class="fas fa-history fa-3x" style="margin-bottom: 20px;"></i>
                            <p>Loading transactions from Firebase...</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Modals -->
        <div id="addBalanceModal" class="modal">
            <div class="modal-content">
                <h3 class="modal-title"><i class="fas fa-plus-circle"></i> Add Balance</h3>
                <div class="form-group">
                    <label for="modalAddAmount">Amount to Add (₱)</label>
                    <input type="number" id="modalAddAmount" placeholder="Enter amount" min="100" step="100" value="500">
                </div>
                <div class="modal-buttons">
                    <button class="btn btn-secondary" id="cancelAddBalance">Cancel</button>
                    <button class="btn btn-success" id="confirmAddBalance">Add Balance</button>
                </div>
            </div>
        </div>
        
        <div id="deleteCardModal" class="modal">
            <div class="modal-content">
                <h3 class="modal-title"><i class="fas fa-trash"></i> Delete RFID Card</h3>
                <p>Are you sure you want to delete card <strong id="deleteCardIdText"></strong>?</p>
                <p class="cooldown-indicator"><i class="fas fa-exclamation-triangle"></i> This action cannot be undone!</p>
                <div class="modal-buttons">
                    <button class="btn btn-secondary" id="cancelDeleteCard">Cancel</button>
                    <button class="btn btn-danger" id="confirmDeleteCard">Delete Card</button>
                </div>
            </div>
        </div>
        
        <div id="statusChangeModal" class="modal">
            <div class="modal-content">
                <h3 class="modal-title"><i class="fas fa-ban"></i> Change Card Status</h3>
                <p>Change status of card <strong id="statusCardIdText"></strong> to:</p>
                <div class="form-group">
                    <select id="newCardStatus">
                        <option value="active">Active</option>
                        <option value="inactive">Inactive</option>
                        <option value="blocked">Blocked</option>
                    </select>
                </div>
                <div class="modal-buttons">
                    <button class="btn btn-secondary" id="cancelStatusChange">Cancel</button>
                    <button class="btn btn-primary" id="confirmStatusChange">Update Status</button>
                </div>
            </div>
        </div>
        
        <footer>
            <p>Tollgate Tap-Only System v2.0 | Project: tollgate-System | &copy; 2025 CDO-MALAYBALAY Tollgate System</p>
        </footer>
    </div>

    <!-- ====== FIREBASE AND APPLICATION CODE ====== -->
    <script>
        // ====== FIREBASE CONFIGURATION ======
        const firebaseConfig = {
            apiKey: "AIzaSyC8pWwSVB71e-Ks1jYOHQe8_T1zbGYgD_o",
            authDomain: "tollgate-13ae4.firebaseapp.com",
            databaseURL: "https://tollgate-13ae4-default-rtdb.firebaseio.com",
            projectId: "tollgate-13ae4",
            storageBucket: "tollgate-13ae4.firebasestorage.app",
            messagingSenderId: "20251525602",
            appId: "1:20251525602:web:220170e5c785195ca139bb",
            measurementId: "G-GT76S75F9F"
        };
        
        // ====== INITIALIZE FIREBASE ======
        let app, auth, database;
        try {
            app = firebase.initializeApp(firebaseConfig);
            auth = firebase.auth();
            database = firebase.database();
            console.log("Firebase initialized successfully");
        } catch (error) {
            console.error("Firebase initialization error:", error);
        }
        
        // ====== APPLICATION VARIABLES ======
        let currentUser = null;
        let rfidCards = {};
        let transactions = {};
        
        // Toll fees based on vehicle classification
        const tollFees = {
            class1: 119,   // Regular Cars, Jeepneys, Vans
            class2: 299,   // Buses, Trucks
            class3: 418    // Large Trucks, Trailers
        };
        
        let gateOpenTimeout = null;
        let isRegistering = false;
        let currentScannedCardId = null;
        let selectedCardId = null;
        let raspiIP = "000.000.000.000"; // Your actual Raspberry Pi IP
        let notificationTimeout = null;
        let systemStatusInterval = null;
        let selectedVehicleClass = "class1"; // Default vehicle class
        
        // ====== LOGIN FUNCTIONALITY ======
        function setupLogin() {
            const loginForm = document.getElementById('loginForm');
            const loginErrorMessage = document.getElementById('loginErrorMessage');
            const loginErrorText = document.getElementById('loginErrorText');
            
            if (!loginForm) return;
            
            // Pre-fill credentials for convenience
            document.getElementById('loginUsername').value = 'cpe4cgroup5';
            document.getElementById('loginPassword').value = 'cpe4cgroup5';
            
            loginForm.addEventListener('submit', function(e) {
                e.preventDefault();
                
                const username = document.getElementById('loginUsername').value;
                const password = document.getElementById('loginPassword').value;
                
                if (username === 'cpe4cgroup5' && password === 'cpe4cgroup5') {
                    // Successful login
                    loginErrorMessage.style.display = 'none';
                    
                    // Create mock user
                    currentUser = {
                        email: "cpe4cgroup5@tollgate.com",
                        username: "cpe4cgroup5",
                        uid: "admin123"
                    };
                    
                    // Hide login page, show main app
                    document.getElementById('loginPage').style.display = 'none';
                    document.getElementById('mainApp').style.display = 'block';
                    
                    // Update UI with user info
                    if (document.getElementById('userEmail')) {
                        document.getElementById('userEmail').textContent = currentUser.username;
                    }
                    
                    // Start the application
                    initializeApplication();
                    
                    // Show welcome message
                    showToast('success', 'Login Successful', `Welcome ${currentUser.username}!`);
                    
                } else {
                    // Failed login
                    loginErrorText.textContent = 'Invalid username or password. Please try again.';
                    loginErrorMessage.style.display = 'block';
                    
                    // Shake animation for error
                    loginForm.style.animation = 'shake 0.5s';
                    setTimeout(() => {
                        loginForm.style.animation = '';
                    }, 500);
                }
            });
        }
        
        // ====== APPLICATION INITIALIZATION AFTER LOGIN ======
        function initializeApplication() {
            // Initialize all application components
            initializePage();
            
            // Start monitoring Firebase connection
            monitorFirebaseConnection();
        }
        
        // ====== DOM ELEMENTS ======
        let firebaseStatus, statusIcon, statusText, systemStatusText;
        let tabBtns, tabContents;
        let rfidGateIndicator, cashGateIndicator;
        let tapStatusMessage, tapStatusContent, rfidSuccessMessage, successText;
        let rfidErrorMessage, errorText, simulateTapBtn, testRfidGateBtn, testCashGateBtn;
        let refreshCooldownBtn, resetReaderBtn;
        let cashAmount, processCashBtn, cashReceipt, amountPaidDisplay, changeDisplay;
        let cashSuccessMessage, cashSuccessText, cashErrorMessage, cashErrorText;
        let cashVehicleClass, cashTollFeeDisplay, receiptVehicleClass, receiptTollFee;
        let startRegistrationBtn, registrationForm, scannedCardDisplay;
        let regCardHolder, regCardEmail, regInitialBalance, regVehicleInfo, registerCardBtn;
        let regSuccessMessage, regErrorMessage, regErrorText, cancelRegistrationBtn;
        let regVehicleClass, selectedClassDisplay, selectedClassFee, selectedClassType;
        let totalCardsElement, totalBalanceElement, activeCardsElement, blockedCardsElement;
        let searchCardId, cardDetails, detailCardType, detailCardStatus, detailCardBalance;
        let detailCardId, detailCardHolder, detailCardEmail, detailCardVehicle, detailCardClass;
        let addBalanceAmount, processAddBalanceBtn;
        let addBalanceQuickBtn, deactivateCardBtn, activateCardBtn, deleteCardBtn;
        let cardsListContainer, searchCardsInput, refreshCardsBtn;
        let showRFIDTransactions, showCashTransactions, showAllTransactions, transactionsLog;
        let clearTransactionsBtn, refreshTransactionsBtn, clearActivityLog;
        let liveActivityLog, cooldownStatus;
        let raspiStatusIndicator, rfidStatusIndicator, gateStatusIndicator;
        
        // Modal elements
        let addBalanceModal, deleteCardModal, statusChangeModal;
        let modalAddAmount, cancelAddBalance, confirmAddBalance;
        let deleteCardIdText, cancelDeleteCard, confirmDeleteCard;
        let statusCardIdText, newCardStatus, cancelStatusChange, confirmStatusChange;
        
        // Notification elements
        let notificationToast, toastIcon, toastTitle, toastMessage;
        
        // ====== VEHICLE CLASSIFICATION FUNCTIONS ======
        function selectVehicleClass(vehicleClass) {
            selectedVehicleClass = vehicleClass;
            
            // Update the hidden input
            document.getElementById('regVehicleClass').value = vehicleClass;
            
            // Remove selected class from all cards
            document.querySelectorAll('.vehicle-class-card').forEach(card => {
                card.classList.remove('selected');
            });
            
            // Add selected class to clicked card
            document.querySelector(`.vehicle-class-card[data-class="${vehicleClass}"]`).classList.add('selected');
            
            // Update display
            let fee, type, desc;
            
            switch(vehicleClass) {
                case 'class1':
                    fee = '₱119.00';
                    type = 'Class 1: Regular Cars, Jeepneys, Vans';
                    desc = 'Regular Cars, Jeepneys, Vans';
                    break;
                case 'class2':
                    fee = '₱299.00';
                    type = 'Class 2: Buses, Trucks';
                    desc = 'Buses, Trucks';
                    break;
                case 'class3':
                    fee = '₱418.00';
                    type = 'Class 3: Large Trucks, Trailers';
                    desc = 'Large Trucks, Trailers';
                    break;
            }
            
            selectedClassFee.textContent = fee;
            selectedClassType.textContent = type;
            selectedClassDisplay.style.display = 'block';
            
            // Update minimum initial balance recommendation
            const minBalance = vehicleClass === 'class1' ? 500 : 1000;
            document.querySelector('#regInitialBalance').min = minBalance;
            if (parseInt(document.querySelector('#regInitialBalance').value) < minBalance) {
                document.querySelector('#regInitialBalance').value = minBalance;
            }
        }
        
        function updateCashTollFeeDisplay() {
            const selectedClass = cashVehicleClass.value;
            let fee, type;
            
            switch(selectedClass) {
                case 'class1':
                    fee = '₱119.00';
                    type = 'Class 1: Regular Cars, Jeepneys, Vans';
                    break;
                case 'class2':
                    fee = '₱299.00';
                    type = 'Class 2: Buses, Trucks';
                    break;
                case 'class3':
                    fee = '₱418.00';
                    type = 'Class 3: Large Trucks, Trailers';
                    break;
            }
            
            document.querySelector('#cashTollFeeDisplay .toll-fee-amount').textContent = fee;
            document.querySelector('#cashTollFeeDisplay .toll-fee-type').textContent = type;
            
            // Update the minimum amount for cash payment
            const minAmount = tollFees[selectedClass];
            cashAmount.min = minAmount;
            cashAmount.placeholder = `Enter amount (minimum ₱${minAmount})`;
            
            // Adjust current value if it's below minimum
            if (parseInt(cashAmount.value) < minAmount) {
                cashAmount.value = minAmount + 50;
            }
        }
        
        // ====== SYSTEM STATUS MONITORING ======
        function checkSystemStatus() {
            if (!raspiIP) return;
            
            fetch(`http://${raspiIP}:5000/api/status`, { 
                method: 'GET',
                timeout: 3000 
            })
            .then(response => {
                if (!response.ok) throw new Error('Network response was not ok');
                return response.json();
            })
            .then(data => {
                // Update RasPi status
                const raspiStatus = raspiStatusIndicator.querySelector('span');
                if (data.system === "operational" || data.status === "online") {
                    raspiStatusIndicator.className = "status-indicator status-online";
                    raspiStatus.innerHTML = "Online";
                    systemStatusText.textContent = "Online";
                } else {
                    raspiStatusIndicator.className = "status-indicator status-offline";
                    raspiStatus.innerHTML = "Offline";
                    systemStatusText.textContent = "Offline";
                }
                
                // Update RFID status
                const rfidStatus = rfidStatusIndicator.querySelector('span');
                if (data.rfid === "connected") {
                    rfidStatusIndicator.className = "status-indicator status-online";
                    rfidStatus.innerHTML = "Connected";
                } else {
                    rfidStatusIndicator.className = "status-indicator status-offline";
                    rfidStatus.innerHTML = "Disconnected";
                }
                
                // Update gate status
                const gateStatus = gateStatusIndicator.querySelector('span');
                if (document.querySelector('.gate-open')) {
                    gateStatusIndicator.className = "status-indicator status-online";
                    gateStatus.innerHTML = "Open";
                } else {
                    gateStatusIndicator.className = "status-indicator status-offline";
                    gateStatus.innerHTML = "Closed";
                }
            })
            .catch(error => {
                // RasPi offline
                console.error("Raspberry Pi offline:", error);
                raspiStatusIndicator.className = "status-indicator status-offline";
                raspiStatusIndicator.querySelector('span').innerHTML = "Offline";
                rfidStatusIndicator.className = "status-indicator status-offline";
                rfidStatusIndicator.querySelector('span').innerHTML = "Offline";
                gateStatusIndicator.className = "status-indicator status-offline";
                gateStatusIndicator.querySelector('span').innerHTML = "Closed";
                systemStatusText.textContent = "Offline";
            });
        }
        
        // ====== FIREBASE CONNECTION ======
        function monitorFirebaseConnection() {
            if (!database) return;
            
            const connectedRef = database.ref(".info/connected");
            
            connectedRef.on("value", function(snap) {
                if (snap.val() === true) {
                    updateFirebaseStatus(true, "Connected to Firebase Database");
                    console.log("Firebase connected successfully!");
                    
                    // Load data
                    loadRFIDCards();
                    loadTransactions();
                    listenForRFIDActivity();
                    loadCooldownStatus();
                    
                    // Setup real-time notifications
                    setupRealTimeNotifications();
                    
                    // Start system status monitoring
                    checkSystemStatus();
                    systemStatusInterval = setInterval(checkSystemStatus, 5000);
                    
                    // Refresh cooldown status every 5 seconds
                    setInterval(loadCooldownStatus, 5000);
                    
                    // Auto-refresh cards every 10 seconds
                    setInterval(() => {
                        if (document.getElementById('admin').classList.contains('active') || 
                            document.getElementById('tollgate').classList.contains('active')) {
                            loadRFIDCards();
                        }
                    }, 10000);
                    
                } else {
                    updateFirebaseStatus(false, "Disconnected from Firebase");
                }
            });
        }
        
        function updateFirebaseStatus(connected, message) {
            if (!firebaseStatus) return;
            
            if (connected) {
                firebaseStatus.className = "firebase-status status-connected";
                statusIcon.className = "fas fa-check-circle";
                statusText.textContent = message;
            } else {
                firebaseStatus.className = "firebase-status status-disconnected";
                statusIcon.className = "fas fa-times-circle";
                statusText.textContent = message;
            }
        }
        
        // ====== REAL-TIME NOTIFICATIONS ======
        function setupRealTimeNotifications() {
            if (!database) return;
            
            // Listen for payment notifications
            database.ref('notifications').orderByChild('timestamp').limitToLast(5).on('child_added', function(snapshot) {
                const notification = snapshot.val();
                
                if (!notification || notification.processed) return;
                
                // Mark as processed
                snapshot.ref.update({ processed: true });
                
                // Show notification based on type
                if (notification.type === 'payment_notification' || notification.type === 'payment') {
                    showPaymentNotification(notification);
                } else if (notification.type === 'registration') {
                    showRegistrationNotification(notification);
                } else if (notification.type === 'system') {
                    showSystemNotification(notification);
                }
                
                // Auto-delete old notification after 10 seconds
                setTimeout(() => {
                    snapshot.ref.remove();
                }, 10000);
            });
        }
        
        function showPaymentNotification(notification) {
            if (notification.success) {
                showToast('success', 'Payment Successful', notification.message);
                
                // Update UI if on tollgate tab
                if (document.getElementById('tollgate').classList.contains('active')) {
                    if (notification.cardId === 'CASH') {
                        cashSuccessText.textContent = notification.message;
                        cashSuccessMessage.style.display = 'block';
                        cashErrorMessage.style.display = 'none';
                        openGate(cashGateIndicator);
                        
                        setTimeout(() => {
                            cashSuccessMessage.style.display = 'none';
                        }, 5000);
                    } else {
                        successText.textContent = notification.message;
                        rfidSuccessMessage.style.display = 'block';
                        rfidErrorMessage.style.display = 'none';
                        openGate(rfidGateIndicator);
                        
                        setTimeout(() => {
                            rfidSuccessMessage.style.display = 'none';
                        }, 5000);
                    }
                }
            } else {
                showToast('error', 'Payment Failed', notification.message);
                
                if (document.getElementById('tollgate').classList.contains('active')) {
                    if (notification.cardId === 'CASH') {
                        cashErrorText.textContent = notification.message;
                        cashErrorMessage.style.display = 'block';
                        cashSuccessMessage.style.display = 'none';
                        
                        setTimeout(() => {
                            cashErrorMessage.style.display = 'none';
                        }, 5000);
                    } else {
                        errorText.textContent = notification.message;
                        rfidErrorMessage.style.display = 'block';
                        rfidSuccessMessage.style.display = 'none';
                        
                        setTimeout(() => {
                            rfidErrorMessage.style.display = 'none';
                        }, 5000);
                    }
                }
            }
            
            // Reload cards and transactions to reflect changes
            setTimeout(() => {
                loadRFIDCards();
                loadTransactions();
            }, 1000);
        }
        
        function showRegistrationNotification(notification) {
            if (notification.success && isRegistering) {
                showToast('success', 'Card Scanned', 'Card scanned successfully!');
                
                // Update the registration form with card ID
                currentScannedCardId = notification.cardId;
                scannedCardDisplay.textContent = notification.cardId;
                scannedCardDisplay.style.background = 'linear-gradient(135deg, #10b981 0%, #34d399 100%)';
                
                // Check if card already exists
                if (rfidCards[notification.cardId]) {
                    showToast('warning', 'Card Already Registered', 'This card is already in the system!');
                    setTimeout(() => {
                        registrationForm.style.display = 'none';
                        isRegistering = false;
                    }, 2000);
                }
            }
        }
        
        function showSystemNotification(notification) {
            showToast('info', 'System Update', notification.message);
        }
        
        function showToast(type, title, message) {
            if (!notificationToast) return;
            
            // Clear any existing timeout
            if (notificationTimeout) {
                clearTimeout(notificationTimeout);
            }
            
            // Set toast style based on type
            notificationToast.className = `notification-toast toast-${type}`;
            
            // Set icon based on type
            const icons = {
                'success': 'fa-check-circle',
                'error': 'fa-exclamation-circle',
                'info': 'fa-info-circle',
                'warning': 'fa-exclamation-triangle'
            };
            toastIcon.className = `fas ${icons[type] || 'fa-info-circle'}`;
            
            // Set content
            toastTitle.textContent = title;
            toastMessage.textContent = message;
            
            // Show toast
            notificationToast.style.display = 'flex';
            
            // Auto-hide after 5 seconds
            notificationTimeout = setTimeout(() => {
                notificationToast.style.display = 'none';
            }, 5000);
        }
        
        function closeToast() {
            if (notificationToast) {
                notificationToast.style.display = 'none';
            }
            if (notificationTimeout) {
                clearTimeout(notificationTimeout);
            }
        }
        
        // ====== LISTEN FOR RFID ACTIVITY FROM RASPBERRY PI ======
        function listenForRFIDActivity() {
            if (!database) return;
            
            // Listen for new transactions (RFID taps)
            database.ref('transactions').orderByChild('timestamp').limitToLast(10).on('child_added', function(snapshot) {
                const transaction = snapshot.val();
                
                if (transaction.type === 'rfid' || transaction.type === 'cash') {
                    // Add to live activity log
                    addToActivityLog(transaction);
                }
            });
        }
        
        function addToActivityLog(transaction) {
            if (!liveActivityLog) return;
            
            const logEntry = document.createElement('div');
            logEntry.className = 'log-entry';
            
            const time = new Date(transaction.timestamp).toLocaleTimeString();
            const date = new Date(transaction.timestamp).toLocaleDateString();
            
            let logContent = '';
            if (transaction.type === 'rfid') {
                logContent = `
                    <div class="log-time">${date} ${time}</div>
                    <div><i class="fas fa-id-card"></i> <strong>RFID Payment</strong></div>
                    <div>Card: ${transaction.cardId} | Vehicle Class: ${transaction.vehicleClass || 'Class 1'}</div>
                    <div>Amount: ₱${transaction.amount} | Previous: ₱${transaction.previousBalance || 'N/A'} | New: ₱${transaction.newBalance || 'N/A'}</div>
                `;
                logEntry.style.borderLeft = '4px solid #10b981';
            } else if (transaction.type === 'cash') {
                logContent = `
                    <div class="log-time">${date} ${time}</div>
                    <div><i class="fas fa-money-bill-wave"></i> <strong>Cash Payment</strong></div>
                    <div>Vehicle Class: ${transaction.vehicleClass || 'Class 1'} | Paid: ₱${transaction.amountPaid} | Change: ₱${transaction.change}</div>
                `;
                logEntry.style.borderLeft = '4px solid #3b82f6';
            } else if (transaction.type === 'balance_added') {
                logContent = `
                    <div class="log-time">${date} ${time}</div>
                    <div><i class="fas fa-plus-circle"></i> <strong>Balance Added</strong></div>
                    <div>Card: ${transaction.cardId} | Amount: ₱${transaction.amount}</div>
                    <div>New Balance: ₱${transaction.newBalance}</div>
                `;
                logEntry.style.borderLeft = '4px solid #8b5cf6';
            }
            
            logEntry.innerHTML = logContent;
            logEntry.style.paddingLeft = '12px';
            liveActivityLog.insertBefore(logEntry, liveActivityLog.firstChild);
            
            // Keep only last 15 entries
            if (liveActivityLog.children.length > 15) {
                liveActivityLog.removeChild(liveActivityLog.lastChild);
            }
        }
        
        // ====== CASH PAYMENT PROCESSING (FIXED) ======
        function processCashPayment() {
            const vehicleClass = cashVehicleClass.value;
            const tollFee = tollFees[vehicleClass];
            const amount = parseFloat(cashAmount.value);
            
            if (isNaN(amount) || amount < tollFee) {
                showToast('error', 'Invalid Amount', `Minimum payment is ₱${tollFee.toFixed(2)} for selected vehicle class`);
                return;
            }
            
            const change = amount - tollFee;
            
            // Update receipt with vehicle class info
            let classText = '';
            switch(vehicleClass) {
                case 'class1': classText = 'Class 1: Regular Cars'; break;
                case 'class2': classText = 'Class 2: Buses, Trucks'; break;
                case 'class3': classText = 'Class 3: Large Trucks'; break;
            }
            
            receiptVehicleClass.textContent = classText;
            receiptTollFee.textContent = `₱${tollFee.toFixed(2)}`;
            amountPaidDisplay.textContent = `₱${amount.toFixed(2)}`;
            changeDisplay.textContent = `₱${change.toFixed(2)}`;
            
            // Show receipt immediately
            cashReceipt.style.display = 'block';
            cashSuccessMessage.style.display = 'none';
            cashErrorMessage.style.display = 'none';
            
            // Process cash payment via API
            fetch(`http://${raspiIP}:5000/api/process-cash`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({ 
                    amount: amount,
                    vehicleClass: vehicleClass,
                    tollFee: tollFee
                }),
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    cashSuccessText.textContent = data.message;
                    cashSuccessMessage.style.display = 'block';
                    cashErrorMessage.style.display = 'none';
                    
                    // The gate will open automatically via the notification system
                    showToast('success', 'Cash Payment', data.message);
                    
                    // Hide receipt after 5 seconds
                    setTimeout(() => {
                        cashReceipt.style.display = 'none';
                    }, 5000);
                } else {
                    cashErrorText.textContent = data.message;
                    cashErrorMessage.style.display = 'block';
                    cashSuccessMessage.style.display = 'none';
                    cashReceipt.style.display = 'none';
                    showToast('error', 'Payment Failed', data.message);
                }
            })
            .catch(error => {
                console.error('Error processing cash:', error);
                cashErrorText.textContent = 'Failed to process payment. Please try again.';
                cashErrorMessage.style.display = 'block';
                cashSuccessMessage.style.display = 'none';
                cashReceipt.style.display = 'none';
                showToast('error', 'Connection Error', 'Could not connect to Raspberry Pi');
            });
        }
        
        // ====== REGISTRATION FUNCTIONALITY ======
        function setupRegistration() {
            if (!startRegistrationBtn) return;
            
            startRegistrationBtn.addEventListener('click', function() {
                isRegistering = true;
                registrationForm.style.display = 'block';
                scannedCardDisplay.textContent = 'WAITING FOR CARD TAP...';
                scannedCardDisplay.style.background = 'linear-gradient(135deg, #f59e0b 0%, #fbbf24 100%)';
                regSuccessMessage.style.display = 'none';
                regErrorMessage.style.display = 'none';
                
                // Reset to default class 1
                selectVehicleClass('class1');
                
                // Ask Raspberry Pi to scan for registration
                requestRaspiScan();
            });
            
            if (registerCardBtn) {
                registerCardBtn.addEventListener('click', registerRFIDCard);
            }
            
            if (cancelRegistrationBtn) {
                cancelRegistrationBtn.addEventListener('click', function() {
                    registrationForm.style.display = 'none';
                    isRegistering = false;
                    currentScannedCardId = null;
                });
            }
        }
        
        function requestRaspiScan() {
            // Send request to Raspberry Pi to scan for registration
            fetch(`http://${raspiIP}:5000/api/scan-rfid`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({}),
            })
            .then(response => response.json())
            .then(data => {
                if (data.success && data.card_id) {
                    showToast('success', 'Card Scanned', `Card ID: ${data.card_id}`);
                    currentScannedCardId = data.card_id;
                    scannedCardDisplay.textContent = data.card_id;
                    scannedCardDisplay.style.background = 'linear-gradient(135deg, #10b981 0%, #34d399 100%)';
                    
                    // Check if card already exists
                    if (rfidCards[data.card_id]) {
                        showToast('warning', 'Card Already Registered', 'This card is already in the system!');
                        setTimeout(() => {
                            registrationForm.style.display = 'none';
                            isRegistering = false;
                        }, 2000);
                    }
                } else {
                    showToast('error', 'Scan Failed', data.message || 'Failed to scan card');
                    registrationForm.style.display = 'none';
                    isRegistering = false;
                }
            })
            .catch(error => {
                console.error('Error requesting scan:', error);
                showToast('error', 'Connection Error', 'Could not connect to Raspberry Pi');
                registrationForm.style.display = 'none';
                isRegistering = false;
            });
        }
        
        function registerRFIDCard() {
            if (!currentScannedCardId) {
                showToast('error', 'No Card', 'Please scan a card first');
                return;
            }
            
            const holder = regCardHolder.value.trim();
            const email = regCardEmail.value.trim();
            const balance = parseFloat(regInitialBalance.value);
            const vehicle = regVehicleInfo.value.trim();
            const vehicleClass = selectedVehicleClass;
            
            if (!holder || !email || isNaN(balance) || balance < 100) {
                regErrorText.textContent = 'Please fill all required fields correctly. Minimum balance is ₱100.';
                regErrorMessage.style.display = 'block';
                return;
            }
            
            // Check minimum balance based on vehicle class
            const minBalance = vehicleClass === 'class1' ? 500 : 1000;
            if (balance < minBalance) {
                regErrorText.textContent = `Minimum balance for ${vehicleClass === 'class1' ? 'Class 1' : 'Class 2/3'} is ₱${minBalance}.`;
                regErrorMessage.style.display = 'block';
                return;
            }
            
            // Get the toll fee for this vehicle class
            const classFee = tollFees[vehicleClass];
            let classDescription = '';
            switch(vehicleClass) {
                case 'class1': classDescription = 'Class 1: Regular Cars, Jeepneys, Vans'; break;
                case 'class2': classDescription = 'Class 2: Buses, Trucks'; break;
                case 'class3': classDescription = 'Class 3: Large Trucks, Trailers'; break;
            }
            
            const newCard = {
                holder: holder,
                email: email,
                balance: balance,
                type: 'regular',
                status: 'active',
                vehicle: vehicle || 'Not specified',
                vehicleClass: vehicleClass,
                vehicleClassDescription: classDescription,
                vehicleClassFee: classFee,
                dateCreated: new Date().toISOString(),
                lastUsed: null
            };
            
            database.ref('rfidCards/' + currentScannedCardId).set(newCard)
                .then(() => {
                    regSuccessMessage.style.display = 'block';
                    regErrorMessage.style.display = 'none';
                    
                    // Record transaction for initial balance
                    const transactionId = database.ref('transactions').push().key;
                    const transaction = {
                        id: transactionId,
                        type: 'balance_added',
                        cardId: currentScannedCardId,
                        cardHolder: holder,
                        amount: balance,
                        timestamp: new Date().toISOString(),
                        newBalance: balance,
                        notes: 'Initial balance on registration',
                        status: 'success'
                    };
                    
                    database.ref('transactions/' + transactionId).set(transaction);
                    
                    // Send system notification
                    const notificationId = `system_${Date.now()}`;
                    database.ref('notifications/' + notificationId).set({
                        type: 'system',
                        message: `New ${classDescription} card registered: ${currentScannedCardId} for ${holder}`,
                        timestamp: new Date().toISOString()
                    });
                    
                    // Reset form
                    regCardHolder.value = '';
                    regCardEmail.value = '';
                    regInitialBalance.value = '500';
                    regVehicleInfo.value = '';
                    registrationForm.style.display = 'none';
                    isRegistering = false;
                    
                    showToast('success', 'Card Registered', `Card ${currentScannedCardId} registered as ${classDescription}!`);
                    
                    // Refresh cards list
                    loadRFIDCards();
                    
                })
                .catch(error => {
                    console.error('Error registering card:', error);
                    regErrorText.textContent = 'Error registering card. Please try again.';
                    regErrorMessage.style.display = 'block';
                });
        }
        
        // ====== GATE CONTROL ======
        function setupGateControls() {
            // Test RFID Gate
            if (testRfidGateBtn) {
                testRfidGateBtn.addEventListener('click', function() {
                    sendGateCommand('rfid');
                });
            }
            
            // Test Cash Gate
            if (testCashGateBtn) {
                testCashGateBtn.addEventListener('click', function() {
                    sendGateCommand('cash');
                });
            }
            
            // Cash Payment
            if (processCashBtn) {
                processCashBtn.addEventListener('click', processCashPayment);
            }
            
            // Cash vehicle class change
            if (cashVehicleClass) {
                cashVehicleClass.addEventListener('change', updateCashTollFeeDisplay);
            }
            
            // Simulate RFID tap
            if (simulateTapBtn) {
                simulateTapBtn.addEventListener('click', simulateRFIDTap);
            }
            
            // Refresh cooldown
            if (refreshCooldownBtn) {
                refreshCooldownBtn.addEventListener('click', loadCooldownStatus);
            }
            
            // Reset RFID reader
            if (resetReaderBtn) {
                resetReaderBtn.addEventListener('click', function() {
                    fetch(`http://${raspiIP}:5000/api/reset-reader`, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                        }
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            showToast('success', 'Reader Reset', 'RFID reader reset successfully');
                        }
                    })
                    .catch(error => {
                        showToast('error', 'Reset Failed', 'Could not reset RFID reader');
                    });
                });
            }
        }
        
        function sendGateCommand(gateType) {
            fetch(`http://${raspiIP}:5000/api/test-gate/${gateType}`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({action: 'open', duration: 5}),
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    const gateElement = gateType === 'rfid' ? rfidGateIndicator : cashGateIndicator;
                    openGate(gateElement);
                    showToast('success', 'Gate Test', `${gateType.toUpperCase()} gate opened successfully`);
                }
            })
            .catch(error => {
                console.error('Error sending gate command:', error);
                showToast('error', 'Connection Error', 'Could not connect to Raspberry Pi');
            });
        }
        
        function simulateRFIDTap() {
            const cardIds = Object.keys(rfidCards);
            if (cardIds.length > 0) {
                const randomCardId = cardIds[Math.floor(Math.random() * cardIds.length)];
                const card = rfidCards[randomCardId];
                
                // Include vehicle class in simulation
                fetch(`http://${raspiIP}:5000/api/simulate-tap`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                    },
                    body: JSON.stringify({ 
                        card_id: randomCardId,
                        vehicle_class: card.vehicleClass || 'class1'
                    }),
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        showToast('success', 'Simulation', `Simulated tap for ${card.vehicleClassDescription || 'Class 1'} card ${randomCardId}`);
                    }
                })
                .catch(error => {
                    showToast('error', 'Simulation Failed', 'Could not simulate tap');
                });
            } else {
                showToast('warning', 'No Cards', 'No RFID cards in system. Please register cards first.');
            }
        }
        
        function openGate(gateElement) {
            gateElement.classList.remove('gate-closed');
            gateElement.classList.add('gate-open');
            gateElement.innerHTML = '<i class="fas fa-check-circle"></i> GATE OPEN';
            
            if (gateOpenTimeout) clearTimeout(gateOpenTimeout);
            
            gateOpenTimeout = setTimeout(() => {
                resetGate(gateElement);
            }, 5000);
        }
        
        function resetGate(gateElement) {
            gateElement.classList.remove('gate-open');
            gateElement.classList.add('gate-closed');
            gateElement.innerHTML = '<i class="fas fa-times-circle"></i> GATE CLOSED';
            gateOpenTimeout = null;
        }
        
        // ====== COOLDOWN STATUS ======
        function loadCooldownStatus() {
            if (!cooldownStatus) return;
            
            fetch(`http://${raspiIP}:5000/api/get-cooldown-status`)
                .then(response => response.json())
                .then(data => {
                    if (data.success && Object.keys(data.cooldowns).length > 0) {
                        let html = '<h4><i class="fas fa-clock"></i> Cards on Cooldown</h4>';
                        for (const [cardId, remaining] of Object.entries(data.cooldowns)) {
                            const cardName = rfidCards[cardId] ? rfidCards[cardId].holder : 'Unknown';
                            const vehicleClass = rfidCards[cardId] ? rfidCards[cardId].vehicleClassDescription : 'Unknown Class';
                            html += `
                                <div class="cooldown-indicator">
                                    <i class="fas fa-hourglass-half"></i>
                                    <div>
                                        <div><strong>${cardName}</strong> (${cardId})</div>
                                        <div style="font-size: 0.9rem;">${vehicleClass}</div>
                                        <div style="font-size: 0.9rem;">${remaining}s remaining</div>
                                    </div>
                                    <button class="btn btn-sm btn-warning" onclick="resetCardCooldown('${cardId}')" style="margin-left: auto;">
                                        <i class="fas fa-redo"></i> Reset
                                    </button>
                                </div>
                            `;
                        }
                        cooldownStatus.innerHTML = html;
                        cooldownStatus.style.display = 'block';
                    } else {
                        cooldownStatus.innerHTML = '';
                        cooldownStatus.style.display = 'none';
                    }
                })
                .catch(error => {
                    console.error('Error loading cooldown status:', error);
                });
        }
        
        function resetCardCooldown(cardId) {
            fetch(`http://${raspiIP}:5000/api/reset-cooldown/${cardId}`, {
                method: 'POST'
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    showToast('success', 'Cooldown Reset', `Cooldown reset for card ${cardId}`);
                    loadCooldownStatus();
                }
            })
            .catch(error => {
                console.error('Error resetting cooldown:', error);
                showToast('error', 'Reset Failed', 'Error resetting cooldown');
            });
        }
        
        // ====== ADMIN FUNCTIONS ======
        function loadRFIDCards() {
            if (!database) return;
            
            database.ref('rfidCards').on('value', function(snapshot) {
                rfidCards = snapshot.val() || {};
                
                // Update stats
                updateAdminStats();
                
                // Update cards list
                displayRFIDCards();
                
                // Update card details if a card is selected
                if (selectedCardId && rfidCards[selectedCardId]) {
                    displayCardDetails(selectedCardId);
                }
            });
        }
        
        function updateAdminStats() {
            if (!rfidCards) return;
            
            const cards = Object.values(rfidCards);
            totalCardsElement.textContent = cards.length;
            
            let totalBalance = 0;
            let activeCount = 0;
            let blockedCount = 0;
            
            cards.forEach(card => {
                totalBalance += parseFloat(card.balance) || 0;
                if (card.status === 'active') {
                    activeCount++;
                } else if (card.status === 'inactive' || card.status === 'blocked') {
                    blockedCount++;
                }
            });
            
            totalBalanceElement.textContent = totalBalance.toFixed(2);
            activeCardsElement.textContent = activeCount;
            blockedCardsElement.textContent = blockedCount;
        }
        
        function displayRFIDCards() {
            if (!cardsListContainer) return;
            
            let html = '';
            const searchTerm = searchCardsInput ? searchCardsInput.value.toLowerCase() : '';
            const cards = Object.entries(rfidCards);
            
            if (cards.length === 0) {
                html = `
                    <div style="text-align: center; padding: 40px; color: #6b7280;">
                        <i class="fas fa-id-card fa-3x" style="margin-bottom: 20px;"></i>
                        <p>No RFID cards registered yet</p>
                        <p style="font-size: 0.9rem;">Go to "Tap to Register" tab to add new cards</p>
                    </div>
                `;
            } else {
                cards.forEach(([cardId, card]) => {
                    const isSelected = cardId === selectedCardId;
                    
                    // Filter by search term
                    if (searchTerm && 
                        !cardId.toLowerCase().includes(searchTerm) &&
                        !card.holder.toLowerCase().includes(searchTerm) &&
                        !card.email.toLowerCase().includes(searchTerm)) {
                        return;
                    }
                    
                    let statusClass = 'status-active';
                    let statusText = 'Active';
                    
                    if (card.status === 'inactive') {
                        statusClass = 'status-inactive';
                        statusText = 'Inactive';
                    } else if (card.status === 'blocked') {
                        statusClass = 'status-blocked';
                        statusText = 'Blocked';
                    }
                    
                    // Get vehicle class info
                    const vehicleClass = card.vehicleClass || 'class1';
                    let classBadge = '';
                    let classColor = '#3b82f6';
                    
                    switch(vehicleClass) {
                        case 'class1': 
                            classBadge = 'Class 1';
                            classColor = '#10b981';
                            break;
                        case 'class2': 
                            classBadge = 'Class 2';
                            classColor = '#f59e0b';
                            break;
                        case 'class3': 
                            classBadge = 'Class 3';
                            classColor = '#ef4444';
                            break;
                    }
                    
                    html += `
                        <div class="rfid-card-item ${isSelected ? 'selected' : ''}" data-card-id="${cardId}" onclick="selectCard('${cardId}')">
                            <div class="rfid-card-header">
                                <div class="rfid-card-id">${cardId}</div>
                                <div class="rfid-card-status ${statusClass}">${statusText}</div>
                            </div>
                            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 10px;">
                                <div>
                                    <div style="font-weight: 600; margin-bottom: 5px;">${card.holder}</div>
                                    <div style="font-size: 0.9rem; color: #6b7280; margin-bottom: 5px;">${card.email}</div>
                                </div>
                                <div style="font-weight: 700; font-size: 1.2rem; color: #1e3a8a;">
                                    ₱${parseFloat(card.balance).toFixed(2)}
                                </div>
                            </div>
                            <div style="display: flex; justify-content: space-between; align-items: center;">
                                <div>
                                    <div style="display: flex; align-items: center; gap: 10px;">
                                        <div style="background-color: ${classColor}; color: white; padding: 3px 10px; border-radius: 20px; font-size: 0.8rem; font-weight: 600;">
                                            ${classBadge}
                                        </div>
                                        <div style="font-size: 0.85rem; color: #9ca3af;">${card.vehicle || 'No vehicle info'}</div>
                                    </div>
                                </div>
                                <div style="font-size: 0.9rem; font-weight: 600; color: ${classColor};">
                                    ₱${card.vehicleClassFee || '119'}.00
                                </div>
                            </div>
                        </div>
                    `;
                });
            }
            
            cardsListContainer.innerHTML = html;
        }
        
        function selectCard(cardId) {
            selectedCardId = cardId;
            displayCardDetails(cardId);
            
            // Update selected state in list
            const allCardItems = document.querySelectorAll('.rfid-card-item');
            allCardItems.forEach(item => {
                if (item.dataset.cardId === cardId) {
                    item.classList.add('selected');
                } else {
                    item.classList.remove('selected');
                }
            });
        }
        
        function displayCardDetails(cardId) {
            if (!rfidCards[cardId]) return;
            
            const card = rfidCards[cardId];
            cardDetails.style.display = 'block';
            
            detailCardId.textContent = cardId;
            detailCardHolder.textContent = card.holder;
            detailCardEmail.textContent = card.email;
            detailCardVehicle.textContent = card.vehicle || 'Not specified';
            detailCardBalance.textContent = parseFloat(card.balance).toFixed(2);
            
            // Set status
            let statusText = 'Active';
            let statusClass = 'status-active';
            
            if (card.status === 'inactive') {
                statusText = 'Inactive';
                statusClass = 'status-inactive';
            } else if (card.status === 'blocked') {
                statusText = 'Blocked';
                statusClass = 'status-blocked';
            }
            
            detailCardStatus.textContent = statusText;
            detailCardStatus.className = `rfid-card-status ${statusClass}`;
            
            // Set vehicle class info
            const vehicleClass = card.vehicleClass || 'class1';
            let classType = '';
            let classFee = '₱119.00';
            
            switch(vehicleClass) {
                case 'class1': 
                    classType = 'Class 1: Regular Cars, Jeepneys, Vans';
                    classFee = '₱119.00';
                    break;
                case 'class2': 
                    classType = 'Class 2: Buses, Trucks';
                    classFee = '₱299.00';
                    break;
                case 'class3': 
                    classType = 'Class 3: Large Trucks, Trailers';
                    classFee = '₱418.00';
                    break;
            }
            
            detailCardType.textContent = vehicleClass === 'class1' ? 'Class 1' : 
                                        vehicleClass === 'class2' ? 'Class 2' : 'Class 3';
            detailCardClass.textContent = `${classType} (${classFee})`;
        }
        
        function setupAdminActions() {
            // Search card by ID
            if (searchCardId) {
                searchCardId.addEventListener('input', function() {
                    const cardId = this.value.trim().toUpperCase();
                    if (cardId.length >= 6 && rfidCards[cardId]) {
                        selectCard(cardId);
                    } else if (cardId.length >= 6) {
                        cardDetails.style.display = 'none';
                        selectedCardId = null;
                    }
                });
            }
            
            // Add balance quick button
            if (addBalanceQuickBtn) {
                addBalanceQuickBtn.addEventListener('click', function() {
                    if (!selectedCardId) {
                        showToast('warning', 'No Card Selected', 'Please select a card first');
                        return;
                    }
                    
                    modalAddAmount.value = '500';
                    addBalanceModal.style.display = 'flex';
                });
            }
            
            // Deactivate card button
            if (deactivateCardBtn) {
                deactivateCardBtn.addEventListener('click', function() {
                    if (!selectedCardId) {
                        showToast('warning', 'No Card Selected', 'Please select a card first');
                        return;
                    }
                    
                    statusCardIdText.textContent = selectedCardId;
                    newCardStatus.value = 'inactive';
                    statusChangeModal.style.display = 'flex';
                });
            }
            
            // Activate card button
            if (activateCardBtn) {
                activateCardBtn.addEventListener('click', function() {
                    if (!selectedCardId) {
                        showToast('warning', 'No Card Selected', 'Please select a card first');
                        return;
                    }
                    
                    statusCardIdText.textContent = selectedCardId;
                    newCardStatus.value = 'active';
                    statusChangeModal.style.display = 'flex';
                });
            }
            
            // Delete card button
            if (deleteCardBtn) {
                deleteCardBtn.addEventListener('click', function() {
                    if (!selectedCardId) {
                        showToast('warning', 'No Card Selected', 'Please select a card first');
                        return;
                    }
                    
                    deleteCardIdText.textContent = selectedCardId;
                    deleteCardModal.style.display = 'flex';
                });
            }
            
            // Process add balance from card details
            if (processAddBalanceBtn) {
                processAddBalanceBtn.addEventListener('click', function() {
                    const amount = parseFloat(addBalanceAmount.value);
                    
                    if (isNaN(amount) || amount <= 0) {
                        showToast('error', 'Invalid Amount', 'Please enter a valid amount');
                        return;
                    }
                    
                    addBalanceToCard(selectedCardId, amount);
                });
            }
            
            // Refresh cards button
            if (refreshCardsBtn) {
                refreshCardsBtn.addEventListener('click', function() {
                    loadRFIDCards();
                    showToast('info', 'Refreshed', 'Cards list refreshed');
                });
            }
            
            // Search cards input
            if (searchCardsInput) {
                searchCardsInput.addEventListener('input', displayRFIDCards);
            }
        }
        
        function addBalanceToCard(cardId, amount) {
            if (!rfidCards[cardId]) return;
            
            const currentBalance = parseFloat(rfidCards[cardId].balance);
            const newBalance = currentBalance + amount;
            
            database.ref('rfidCards/' + cardId + '/balance').set(newBalance)
                .then(() => {
                    // Record transaction
                    const transactionId = database.ref('transactions').push().key;
                    const transaction = {
                        id: transactionId,
                        type: 'balance_added',
                        cardId: cardId,
                        cardHolder: rfidCards[cardId].holder,
                        amount: amount,
                        timestamp: new Date().toISOString(),
                        newBalance: newBalance,
                        notes: 'Admin added balance',
                        status: 'success'
                    };
                    
                    database.ref('transactions/' + transactionId).set(transaction);
                    
                    // Show success message
                    showToast('success', 'Balance Added', `Added ₱${amount.toFixed(2)} to card ${cardId}`);
                    
                    // Update UI
                    detailCardBalance.textContent = newBalance.toFixed(2);
                    
                    // Refresh cards list
                    loadRFIDCards();
                    
                    // Close modal if open
                    addBalanceModal.style.display = 'none';
                })
                .catch(error => {
                    console.error('Error adding balance:', error);
                    showToast('error', 'Error', 'Failed to add balance');
                });
        }
        
        function deleteRFIDCard(cardId) {
            if (!rfidCards[cardId]) return;
            
            database.ref('rfidCards/' + cardId).remove()
                .then(() => {
                    // Record system event
                    const transactionId = database.ref('transactions').push().key;
                    const transaction = {
                        id: transactionId,
                        type: 'card_deleted',
                        cardId: cardId,
                        cardHolder: rfidCards[cardId].holder,
                        amount: 0,
                        timestamp: new Date().toISOString(),
                        notes: 'Card deleted by admin',
                        status: 'success'
                    };
                    
                    database.ref('transactions/' + transactionId).set(transaction);
                    
                    // Show success message
                    showToast('success', 'Card Deleted', `Card ${cardId} deleted successfully`);
                    
                    // Clear selection
                    selectedCardId = null;
                    cardDetails.style.display = 'none';
                    
                    // Refresh cards list
                    loadRFIDCards();
                    
                    // Close modal
                    deleteCardModal.style.display = 'none';
                })
                .catch(error => {
                    console.error('Error deleting card:', error);
                    showToast('error', 'Error', 'Failed to delete card');
                });
        }
        
        function changeCardStatus(cardId, newStatus) {
            if (!rfidCards[cardId]) return;
            
            database.ref('rfidCards/' + cardId + '/status').set(newStatus)
                .then(() => {
                    // Record system event
                    const transactionId = database.ref('transactions').push().key;
                    const transaction = {
                        id: transactionId,
                        type: 'status_changed',
                        cardId: cardId,
                        cardHolder: rfidCards[cardId].holder,
                        amount: 0,
                        timestamp: new Date().toISOString(),
                        notes: `Status changed to ${newStatus}`,
                        status: 'success'
                    };
                    
                    database.ref('transactions/' + transactionId).set(transaction);
                    
                    // Show success message
                    showToast('success', 'Status Updated', `Card ${cardId} status changed to ${newStatus}`);
                    
                    // Update UI
                    displayCardDetails(cardId);
                    
                    // Refresh cards list
                    loadRFIDCards();
                    
                    // Close modal
                    statusChangeModal.style.display = 'none';
                })
                .catch(error => {
                    console.error('Error changing card status:', error);
                    showToast('error', 'Error', 'Failed to change card status');
                });
        }
        
        // ====== TRANSACTIONS ======
        function loadTransactions() {
            if (!database) return;
            
            database.ref('transactions').orderByChild('timestamp').limitToLast(50).on('value', function(snapshot) {
                transactions = snapshot.val() || {};
                displayTransactions('all');
            });
        }
        
        function displayTransactions(filter) {
            if (!transactionsLog) return;
            
            const transactionsArray = Object.values(transactions || {});
            
            if (transactionsArray.length === 0) {
                transactionsLog.innerHTML = `
                    <div style="text-align: center; padding: 40px; color: #6b7280;">
                        <i class="fas fa-history fa-3x" style="margin-bottom: 20px;"></i>
                        <p>No transactions recorded yet</p>
                    </div>
                `;
                return;
            }
            
            // Sort by timestamp (newest first)
            transactionsArray.sort((a, b) => {
                return new Date(b.timestamp) - new Date(a.timestamp);
            });
            
            let html = '';
            
            transactionsArray.forEach(transaction => {
                // Apply filter
                if (filter === 'rfid' && transaction.type !== 'rfid') return;
                if (filter === 'cash' && transaction.type !== 'cash') return;
                
                const time = new Date(transaction.timestamp).toLocaleTimeString();
                const date = new Date(transaction.timestamp).toLocaleDateString();
                
                let typeIcon = 'fa-history';
                let typeColor = '#6b7280';
                let typeText = 'Unknown';
                
                if (transaction.type === 'rfid') {
                    typeIcon = 'fa-id-card';
                    typeColor = '#10b981';
                    typeText = 'RFID Payment';
                } else if (transaction.type === 'cash') {
                    typeIcon = 'fa-money-bill-wave';
                    typeColor = '#3b82f6';
                    typeText = 'Cash Payment';
                } else if (transaction.type === 'balance_added') {
                    typeIcon = 'fa-plus-circle';
                    typeColor = '#8b5cf6';
                    typeText = 'Balance Added';
                } else if (transaction.type === 'card_deleted') {
                    typeIcon = 'fa-trash';
                    typeColor = '#ef4444';
                    typeText = 'Card Deleted';
                } else if (transaction.type === 'status_changed') {
                    typeIcon = 'fa-exchange-alt';
                    typeColor = '#f59e0b';
                    typeText = 'Status Changed';
                }
                
                html += `
                    <div class="log-entry">
                        <div style="display: flex; justify-content: space-between; margin-bottom: 8px;">
                            <div style="display: flex; align-items: center; gap: 8px;">
                                <i class="fas ${typeIcon}" style="color: ${typeColor};"></i>
                                <strong>${typeText}</strong>
                            </div>
                            <div style="font-size: 0.85rem; color: #6b7280;">${date} ${time}</div>
                        </div>
                        
                        <div style="font-size: 0.9rem; color: #4b5563;">
                            ${transaction.cardId ? `Card: ${transaction.cardId}<br>` : ''}
                            ${transaction.cardHolder ? `User: ${transaction.cardHolder}<br>` : ''}
                            ${transaction.vehicleClass ? `Vehicle Class: ${transaction.vehicleClass}<br>` : ''}
                            ${transaction.amount ? `Amount: ₱${parseFloat(transaction.amount).toFixed(2)}<br>` : ''}
                            ${transaction.amountPaid ? `Paid: ₱${parseFloat(transaction.amountPaid).toFixed(2)}<br>` : ''}
                            ${transaction.change ? `Change: ₱${parseFloat(transaction.change).toFixed(2)}<br>` : ''}
                            ${transaction.newBalance ? `New Balance: ₱${parseFloat(transaction.newBalance).toFixed(2)}<br>` : ''}
                            ${transaction.notes ? `Note: ${transaction.notes}<br>` : ''}
                            ${transaction.status ? `Status: ${transaction.status}` : ''}
                        </div>
                    </div>
                `;
            });
            
            transactionsLog.innerHTML = html;
        }
        
        function setupTransactionControls() {
            // Show RFID transactions
            if (showRFIDTransactions) {
                showRFIDTransactions.addEventListener('click', function() {
                    displayTransactions('rfid');
                });
            }
            
            // Show cash transactions
            if (showCashTransactions) {
                showCashTransactions.addEventListener('click', function() {
                    displayTransactions('cash');
                });
            }
            
            // Show all transactions
            if (showAllTransactions) {
                showAllTransactions.addEventListener('click', function() {
                    displayTransactions('all');
                });
            }
            
            // Refresh transactions
            if (refreshTransactionsBtn) {
                refreshTransactionsBtn.addEventListener('click', function() {
                    loadTransactions();
                    showToast('info', 'Refreshed', 'Transactions refreshed');
                });
            }
            
            // Clear all transactions
            if (clearTransactionsBtn) {
                clearTransactionsBtn.addEventListener('click', function() {
                    if (confirm('Are you sure you want to delete all transactions? This cannot be undone!')) {
                        database.ref('transactions').remove()
                            .then(() => {
                                showToast('success', 'Cleared', 'All transactions cleared');
                                loadTransactions();
                            })
                            .catch(error => {
                                showToast('error', 'Error', 'Failed to clear transactions');
                            });
                    }
                });
            }
            
            // Clear activity log
            if (clearActivityLog) {
                clearActivityLog.addEventListener('click', function() {
                    if (liveActivityLog) {
                        liveActivityLog.innerHTML = `
                            <div style="text-align: center; padding: 20px; color: #6b7280;">
                                <i class="fas fa-id-card fa-2x" style="margin-bottom: 10px;"></i>
                                <p>Waiting for RFID card taps...</p>
                        </div>
                        `;
                        showToast('info', 'Cleared', 'Activity log cleared');
                    }
                });
            }
        }
        
        // ====== MODAL CONTROLS ======
        function setupModalControls() {
            // Add Balance Modal
            if (confirmAddBalance) {
                confirmAddBalance.addEventListener('click', function() {
                    const amount = parseFloat(modalAddAmount.value);
                    
                    if (isNaN(amount) || amount <= 0) {
                        showToast('error', 'Invalid Amount', 'Please enter a valid amount');
                        return;
                    }
                    
                    if (selectedCardId) {
                        addBalanceToCard(selectedCardId, amount);
                        addBalanceModal.style.display = 'none';
                    }
                });
            }
            
            if (cancelAddBalance) {
                cancelAddBalance.addEventListener('click', function() {
                    addBalanceModal.style.display = 'none';
                });
            }
            
            // Delete Card Modal
            if (confirmDeleteCard) {
                confirmDeleteCard.addEventListener('click', function() {
                    if (selectedCardId) {
                        deleteRFIDCard(selectedCardId);
                        deleteCardModal.style.display = 'none';
                    }
                });
            }
            
            if (cancelDeleteCard) {
                cancelDeleteCard.addEventListener('click', function() {
                    deleteCardModal.style.display = 'none';
                });
            }
            
            // Status Change Modal
            if (confirmStatusChange) {
                confirmStatusChange.addEventListener('click', function() {
                    if (selectedCardId) {
                        changeCardStatus(selectedCardId, newCardStatus.value);
                        statusChangeModal.style.display = 'none';
                    }
                });
            }
            
            if (cancelStatusChange) {
                cancelStatusChange.addEventListener('click', function() {
                    statusChangeModal.style.display = 'none';
                });
            }
            
            // Close modals when clicking outside
            window.addEventListener('click', function(event) {
                const modals = document.querySelectorAll('.modal');
                modals.forEach(modal => {
                    if (event.target === modal) {
                        modal.style.display = 'none';
                    }
                });
            });
        }
        
        // ====== TAB SYSTEM ======
        function setupTabs() {
            tabBtns.forEach(btn => {
                btn.addEventListener('click', function() {
                    const tabId = this.getAttribute('data-tab');
                    
                    // Update active tab button
                    tabBtns.forEach(b => b.classList.remove('active'));
                    this.classList.add('active');
                    
                    // Show active tab content
                    tabContents.forEach(content => content.classList.remove('active'));
                    document.getElementById(tabId).classList.add('active');
                    
                    // Perform tab-specific actions
                    if (tabId === 'admin') {
                        loadRFIDCards();
                    } else if (tabId === 'transactions') {
                        loadTransactions();
                    }
                });
            });
        }
        
        // ====== LOGOUT FUNCTION ======
        function setupLogout() {
            if (!logoutBtn) return;
            
            logoutBtn.addEventListener('click', function() {
                if (confirm('Are you sure you want to log out?')) {
                    // Clear user data
                    currentUser = null;
                    selectedCardId = null;
                    
                    // Clear intervals
                    if (systemStatusInterval) {
                        clearInterval(systemStatusInterval);
                    }
                    
                    // Show login page, hide main app
                    document.getElementById('loginPage').style.display = 'flex';
                    document.getElementById('mainApp').style.display = 'none';
                    
                    // Reset login form
                    document.getElementById('loginUsername').value = '';
                    document.getElementById('loginPassword').value = '';
                    document.getElementById('loginErrorMessage').style.display = 'none';
                    
                    showToast('success', 'Logged Out', 'You have been logged out successfully');
                }
            });
        }
        
        // ====== PAGE INITIALIZATION ======
        function initializePage() {
            // Get DOM elements
            firebaseStatus = document.getElementById('firebaseStatus');
            statusIcon = document.getElementById('statusIcon');
            statusText = document.getElementById('statusText');
            systemStatusText = document.getElementById('systemStatusText');
            
            tabBtns = document.querySelectorAll('.tab-btn');
            tabContents = document.querySelectorAll('.tab-content');
            
            rfidGateIndicator = document.getElementById('rfidGateIndicator');
            cashGateIndicator = document.getElementById('cashGateIndicator');
            
            tapStatusMessage = document.getElementById('tapStatusMessage');
            tapStatusContent = document.getElementById('tapStatusContent');
            rfidSuccessMessage = document.getElementById('rfidSuccessMessage');
            successText = document.getElementById('successText');
            rfidErrorMessage = document.getElementById('rfidErrorMessage');
            errorText = document.getElementById('errorText');
            simulateTapBtn = document.getElementById('simulateTapBtn');
            testRfidGateBtn = document.getElementById('testRfidGateBtn');
            testCashGateBtn = document.getElementById('testCashGateBtn');
            refreshCooldownBtn = document.getElementById('refreshCooldownBtn');
            resetReaderBtn = document.getElementById('resetReaderBtn');
            
            cashAmount = document.getElementById('cashAmount');
            processCashBtn = document.getElementById('processCashBtn');
            cashReceipt = document.getElementById('cashReceipt');
            amountPaidDisplay = document.getElementById('amountPaidDisplay');
            changeDisplay = document.getElementById('changeDisplay');
            cashSuccessMessage = document.getElementById('cashSuccessMessage');
            cashSuccessText = document.getElementById('cashSuccessText');
            cashErrorMessage = document.getElementById('cashErrorMessage');
            cashErrorText = document.getElementById('cashErrorText');
            cashVehicleClass = document.getElementById('cashVehicleClass');
            cashTollFeeDisplay = document.getElementById('cashTollFeeDisplay');
            receiptVehicleClass = document.getElementById('receiptVehicleClass');
            receiptTollFee = document.getElementById('receiptTollFee');
            
            startRegistrationBtn = document.getElementById('startRegistrationBtn');
            registrationForm = document.getElementById('registrationForm');
            scannedCardDisplay = document.getElementById('scannedCardDisplay');
            regCardHolder = document.getElementById('regCardHolder');
            regCardEmail = document.getElementById('regCardEmail');
            regInitialBalance = document.getElementById('regInitialBalance');
            regVehicleInfo = document.getElementById('regVehicleInfo');
            registerCardBtn = document.getElementById('registerCardBtn');
            regSuccessMessage = document.getElementById('regSuccessMessage');
            regErrorMessage = document.getElementById('regErrorMessage');
            regErrorText = document.getElementById('regErrorText');
            cancelRegistrationBtn = document.getElementById('cancelRegistrationBtn');
            regVehicleClass = document.getElementById('regVehicleClass');
            selectedClassDisplay = document.getElementById('selectedClassDisplay');
            selectedClassFee = document.getElementById('selectedClassFee');
            selectedClassType = document.getElementById('selectedClassType');
            
            totalCardsElement = document.getElementById('totalCards');
            totalBalanceElement = document.getElementById('totalBalance');
            activeCardsElement = document.getElementById('activeCards');
            blockedCardsElement = document.getElementById('blockedCards');
            
            searchCardId = document.getElementById('searchCardId');
            cardDetails = document.getElementById('cardDetails');
            detailCardType = document.getElementById('detailCardType');
            detailCardStatus = document.getElementById('detailCardStatus');
            detailCardBalance = document.getElementById('detailCardBalance');
            detailCardId = document.getElementById('detailCardId');
            detailCardHolder = document.getElementById('detailCardHolder');
            detailCardEmail = document.getElementById('detailCardEmail');
            detailCardVehicle = document.getElementById('detailCardVehicle');
            detailCardClass = document.getElementById('detailCardClass');
            addBalanceAmount = document.getElementById('addBalanceAmount');
            processAddBalanceBtn = document.getElementById('processAddBalanceBtn');
            
            addBalanceQuickBtn = document.getElementById('addBalanceQuickBtn');
            deactivateCardBtn = document.getElementById('deactivateCardBtn');
            activateCardBtn = document.getElementById('activateCardBtn');
            deleteCardBtn = document.getElementById('deleteCardBtn');
            
            cardsListContainer = document.getElementById('cardsListContainer');
            searchCardsInput = document.getElementById('searchCards');
            refreshCardsBtn = document.getElementById('refreshCardsBtn');
            
            showRFIDTransactions = document.getElementById('showRFIDTransactions');
            showCashTransactions = document.getElementById('showCashTransactions');
            showAllTransactions = document.getElementById('showAllTransactions');
            transactionsLog = document.getElementById('transactionsLog');
            clearTransactionsBtn = document.getElementById('clearTransactionsBtn');
            refreshTransactionsBtn = document.getElementById('refreshTransactionsBtn');
            clearActivityLog = document.getElementById('clearActivityLog');
            
            liveActivityLog = document.getElementById('liveActivityLog');
            cooldownStatus = document.getElementById('cooldownStatus');
            
            raspiStatusIndicator = document.getElementById('raspiStatusIndicator');
            rfidStatusIndicator = document.getElementById('rfidStatusIndicator');
            gateStatusIndicator = document.getElementById('gateStatusIndicator');
            
            // Modal elements
            addBalanceModal = document.getElementById('addBalanceModal');
            deleteCardModal = document.getElementById('deleteCardModal');
            statusChangeModal = document.getElementById('statusChangeModal');
            
            modalAddAmount = document.getElementById('modalAddAmount');
            cancelAddBalance = document.getElementById('cancelAddBalance');
            confirmAddBalance = document.getElementById('confirmAddBalance');
            
            deleteCardIdText = document.getElementById('deleteCardIdText');
            cancelDeleteCard = document.getElementById('cancelDeleteCard');
            confirmDeleteCard = document.getElementById('confirmDeleteCard');
            
            statusCardIdText = document.getElementById('statusCardIdText');
            newCardStatus = document.getElementById('newCardStatus');
            cancelStatusChange = document.getElementById('cancelStatusChange');
            confirmStatusChange = document.getElementById('confirmStatusChange');
            
            // Notification elements
            notificationToast = document.getElementById('notificationToast');
            toastIcon = document.getElementById('toastIcon');
            toastTitle = document.getElementById('toastTitle');
            toastMessage = document.getElementById('toastMessage');
            
            // Setup all functionality
            setupTabs();
            setupGateControls();
            setupRegistration();
            setupAdminActions();
            setupTransactionControls();
            setupModalControls();
            setupLogout();
            
            // Initialize cash toll fee display
            updateCashTollFeeDisplay();
            
            // Show welcome toast
            setTimeout(() => {
                showToast('success', 'System Ready', `Welcome to the CDO-MALAYBALAY Tollgate System, ${currentUser.username}!`);
            }, 2000);
        }
        
        // ====== START APPLICATION ======
        document.addEventListener('DOMContentLoaded', function() {
            // Start with login functionality
            setupLogin();
            
            // Add shake animation for login error
            const style = document.createElement('style');
            style.textContent = `
                @keyframes shake {
                    0%, 100% { transform: translateX(0); }
                    10%, 30%, 50%, 70%, 90% { transform: translateX(-5px); }
                    20%, 40%, 60%, 80% { transform: translateX(5px); }
                }
            `;
            document.head.appendChild(style);
        });
        
        // ====== GLOBAL FUNCTIONS FOR INLINE EVENT HANDLERS ======
        window.resetCardCooldown = resetCardCooldown;
        window.selectCard = selectCard;
        window.closeToast = closeToast;
        window.selectVehicleClass = selectVehicleClass;
    </script>
</body>
</html>