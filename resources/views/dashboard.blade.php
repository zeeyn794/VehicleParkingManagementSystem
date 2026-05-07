<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ config('app.name', 'ParkMaster') }} - Dashboard</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <style>
        :root {
            --primary-color: #f53003;
            --primary-dark: #d42a02;
            --secondary-color: #22d3ee;
            --success-color: #10b981;
            --warning-color: #f59e0b;
            --danger-color: #ef4444;
            --dark-bg: #0a0a0a;
            --dark-surface: #1C1C1A;
            --light-bg: #FDFDFC;
            --light-surface: #ffffff;
            --text-primary: #1b1b18;
            --text-secondary: #706f6c;
            --text-light: #A1A09A;
            --border-color: #19140035;
            --shadow-sm: 0 1px 2px 0 rgba(0, 0, 0, 0.05);
            --shadow-md: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
            --shadow-lg: 0 10px 15px -3px rgba(0, 0, 0, 0.1);
            --shadow-xl: 0 20px 25px -5px rgba(0, 0, 0, 0.1);
            --transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            --topbar-h: 72px;
            --sidebar-w: 240px;
            --sidebar-w-collapsed: 72px;
        }

        [data-theme="dark"] {
            --light-bg: #0a0a0a;
            --light-surface: #1C1C1A;
            --dark-surface: #2a2a28;
            --text-primary: #EDEDEC;
            --text-secondary: #A1A09A;
            --text-light: #706f6c;
            --border-color: #3E3E3A;
        }
        @media print {
            body * { visibility: hidden; }
            #printableReceipt, #printableReceipt * { visibility: visible; }
            #printableReceipt {
                position: absolute;
                left: 0;
                top: 0;
                width: 100%;
                margin: 0;
                padding: 0;
            }
            .modal, .modal-content { background: white !important; box-shadow: none !important; border: none !important; }
            .modal-footer, .modal-close { display: none !important; }
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Instrument Sans', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif;
            background: var(--light-bg);
            color: var(--text-primary);
            line-height: 1.6;
            transition: var(--transition);
        }

        .dashboard-container {
            display: flex;
            min-height: 100vh;
        }

        .sidebar {
            width: var(--sidebar-w);
            background: rgba(245, 245, 245, 0.92);
            backdrop-filter: blur(10px);
            border-right: 1px solid var(--border-color);
            display: flex;
            flex-direction: column;
            transition: var(--transition);
            position: fixed;
            height: 100vh;
            z-index: 1000;
            transform: translateX(0);
        }

        [data-theme="dark"] .sidebar {
            background: rgba(18, 18, 18, 0.92);
        }

        .sidebar.collapsed {
            width: var(--sidebar-w-collapsed);
        }

        .sidebar-header {
            height: var(--topbar-h);
            padding: 0 1.5rem;
            border-bottom: 1px solid var(--border-color);
            display: flex;
            align-items: center;
            gap: 1rem;
            background: rgba(255, 255, 255, 0.3);
            backdrop-filter: blur(10px);
        }

        [data-theme="dark"] .sidebar-header {
            background: rgba(0, 0, 0, 0.3);
        }

        .logo {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            font-size: 1.5rem;
            font-weight: 700;
            color: var(--text-primary);
            letter-spacing: -0.02em;
        }

        .logo span:first-child {
            color: var(--primary-color);
        }

        .logo i {
            font-size: 2rem;
            color: var(--primary-color);
        }

        .sidebar.collapsed .logo-text {
            display: none;
        }

        .nav-menu {
            flex: 1;
            padding: 1rem 0;
        }

        .nav-item {
            display: flex;
            align-items: center;
            padding: 0.875rem 1.5rem;
            color: var(--text-secondary);
            text-decoration: none;
            transition: var(--transition);
            position: relative;
            gap: 1rem;
        }

        .nav-item:hover {
            background: rgba(245, 48, 3, 0.1);
            color: var(--primary-color);
        }

        .nav-item.active {
            background: rgba(245, 48, 3, 0.1);
            color: var(--primary-color);
        }

        .nav-item.active::before {
            content: '';
            position: absolute;
            left: 0;
            top: 0;
            bottom: 0;
            width: 4px;
            background: var(--primary-color);
        }

        .nav-item i {
            width: 20px;
            text-align: center;
        }

        .sidebar.collapsed .nav-text {
            display: none;
        }

        .main-content {
            flex: 1;
            margin-left: var(--sidebar-w);
            transition: var(--transition);
        }

        .sidebar.collapsed + .main-content {
            margin-left: var(--sidebar-w-collapsed);
        }

        .header {
            background: rgba(255, 255, 255, 0.5);
            backdrop-filter: blur(10px);
            border-bottom: 1px solid var(--border-color);
            height: var(--topbar-h);
            padding: 0 2rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
            position: sticky;
            top: 0;
            z-index: 100;
        }

        [data-theme="dark"] .header {
            background: rgba(0, 0, 0, 0.5);
        }

        .header-left {
            display: flex;
            align-items: center;
            gap: 1rem;
        }

        .menu-toggle {
            display: none;
            background: none;
            border: none;
            font-size: 1.5rem;
            color: var(--text-secondary);
            cursor: pointer;
        }

        .search-bar {
            position: relative;
            width: 300px;
        }

        .search-bar input {
            width: 100%;
            padding: 0.625rem 1rem 0.625rem 2.5rem;
            border: 1px solid var(--border-color);
            border-radius: 0.125rem;
            background: var(--light-bg);
            color: var(--text-primary);
            transition: var(--transition);
        }

        .search-bar input:focus {
            outline: none;
            border-color: var(--primary-color);
            box-shadow: 0 0 0 3px rgba(245, 48, 3, 0.1);
        }

        .search-bar i {
            position: absolute;
            left: 1rem;
            top: 50%;
            transform: translateY(-50%);
            color: var(--text-light);
        }

        .header-right {
            display: flex;
            align-items: center;
            gap: 1rem;
        }

        .notification-btn {
            position: relative;
            background: none;
            border: none;
            font-size: 1.25rem;
            color: var(--text-secondary);
            cursor: pointer;
            padding: 0.5rem;
            border-radius: 0.125rem;
            transition: var(--transition);
        }

        .notification-btn:hover {
            background: rgba(245, 48, 3, 0.1);
            color: var(--primary-color);
        }

        .notification-badge {
            position: absolute;
            top: 0.25rem;
            right: 0.25rem;
            width: 8px;
            height: 8px;
            background: var(--primary-color);
            border-radius: 50%;
            border: 2px solid var(--light-surface);
            display: {{ $allNotificationsCount > 0 ? 'block' : 'none' }};
        }

        .notification-dropdown {
            position: absolute;
            top: 70px;
            right: 2rem;
            width: 320px;
            background: var(--light-surface);
            border: 1px solid var(--border-color);
            border-radius: 0.125rem;
            box-shadow: var(--shadow-xl);
            display: none;
            z-index: 1001;
            overflow: hidden;
            animation: fadeInDropdown 0.2s ease-out;
        }

        @keyframes fadeInDropdown {
            from { opacity: 0; transform: translateY(-10px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .notification-dropdown.active {
            display: block;
        }

        .notification-dropdown-header {
            padding: 1rem;
            border-bottom: 1px solid var(--border-color);
            display: flex;
            justify-content: space-between;
            align-items: center;
            background: rgba(245, 48, 3, 0.02);
        }

        .notification-dropdown-header h4 {
            font-weight: 700;
            font-size: 0.875rem;
            color: var(--text-primary);
        }

        .notification-dropdown-list {
            max-height: 400px;
            overflow-y: auto;
        }

        .notification-dropdown-item {
            padding: 1rem;
            border-bottom: 1px solid var(--border-color);
            display: flex;
            gap: 1rem;
            transition: var(--transition);
            cursor: pointer;
            text-decoration: none;
        }

        .notification-dropdown-item:hover {
            background: rgba(245, 48, 3, 0.05);
        }

        .notification-dropdown-item:last-child {
            border-bottom: none;
        }

        .notification-dropdown-icon {
            width: 36px;
            height: 36px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1rem;
            flex-shrink: 0;
        }

        .notification-dropdown-content {
            flex: 1;
        }

        .notification-dropdown-title {
            font-weight: 600;
            font-size: 0.875rem;
            margin-bottom: 0.25rem;
            color: var(--text-primary);
        }

        .notification-dropdown-time {
            font-size: 0.75rem;
            color: var(--text-secondary);
        }

        .theme-toggle {
            background: none;
            border: none;
            font-size: 1.25rem;
            color: var(--text-secondary);
            cursor: pointer;
            padding: 0.5rem;
            border-radius: 0.125rem;
            transition: var(--transition);
        }

        .theme-toggle:hover {
            background: rgba(245, 48, 3, 0.1);
            color: var(--primary-color);
        }

        .user-profile {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            padding: 0.5rem;
            border-radius: 0.125rem;
            cursor: pointer;
            transition: var(--transition);
        }

        .user-profile:hover {
            background: rgba(245, 48, 3, 0.1);
        }

        .user-avatar {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background: linear-gradient(135deg, var(--primary-color), var(--primary-dark));
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: 600;
        }

        .user-info {
            display: flex;
            flex-direction: column;
        }

        .user-name {
            font-weight: 600;
            color: var(--text-primary);
        }

        .user-status {
            font-size: 0.875rem;
            color: var(--text-secondary);
        }

        .dashboard-content {
            padding: 2rem;
        }

        .overview-section {
            display: grid;
            grid-template-columns: repeat(2, minmax(0, 1fr));
            gap: 1.5rem;
            margin-bottom: 2rem;
        }

        .overview-card {
            background: var(--light-surface);
            border-radius: 0.125rem;
            padding: 1.5rem;
            box-shadow: var(--shadow-md);
            transition: var(--transition);
        }

        .overview-card:hover {
            transform: translateY(-2px);
            box-shadow: var(--shadow-lg);
        }

        .overview-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 1rem;
        }

        .overview-title {
            font-size: 1.125rem;
            font-weight: 600;
            color: var(--text-primary);
        }

        .overview-icon {
            width: 48px;
            height: 48px;
            border-radius: 0.125rem;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.5rem;
        }

        .overview-icon.icon-primary {
            background: rgba(245, 48, 3, 0.1);
            color: var(--primary-color);
        }

        .icon-success {
            background: rgba(16, 185, 129, 0.1);
            color: var(--success-color);
        }

        .icon-warning {
            background: rgba(245, 158, 11, 0.1);
            color: var(--warning-color);
        }

        .overview-value {
            font-size: 2rem;
            font-weight: 700;
            color: var(--text-primary);
            margin-bottom: 0.25rem;
        }

        .overview-label {
            color: var(--text-secondary);
            font-size: 0.875rem;
        }

        .parking-status {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            padding: 0.375rem 0.75rem;
            border-radius: 9999px;
            font-size: 0.875rem;
            font-weight: 500;
        }

        .status-parked {
            background: rgba(16, 185, 129, 0.1);
            color: var(--success-color);
        }

        .status-available {
            background: rgba(245, 48, 3, 0.1);
            color: var(--primary-color);
        }

        .parking-section {
            background: var(--light-surface);
            border-radius: 0.125rem;
            padding: 1.5rem;
            box-shadow: var(--shadow-md);
            margin-bottom: 2rem;
        }

        .section-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 1.5rem;
        }

        .section-title {
            font-size: 1.5rem;
            font-weight: 700;
            color: var(--text-primary);
        }

        .parking-stats {
            display: flex;
            gap: 2rem;
        }

        .stat-item {
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .stat-dot {
            width: 12px;
            height: 12px;
            border-radius: 50%;
        }

        .stat-dot.available {
            background: var(--success-color);
        }

        .stat-dot.occupied {
            background: var(--danger-color);
        }

        .stat-dot.reserved {
            background: var(--warning-color);
        }

        .parking-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(80px, 1fr));
            gap: 1rem;
            margin-bottom: 1.5rem;
        }

        .parking-slot {
            aspect-ratio: 1;
            border-radius: 0.125rem;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            transition: var(--transition);
            position: relative;
            border: 2px solid transparent;
        }

        .parking-slot:hover {
            transform: scale(1.05);
        }

        .slot-available {
            background: rgba(16, 185, 129, 0.1);
            border-color: var(--success-color);
            color: var(--success-color);
        }

        .slot-occupied {
            background: rgba(239, 68, 68, 0.1);
            border-color: var(--danger-color);
            color: var(--danger-color);
            cursor: not-allowed;
        }

        .slot-reserved {
            background: rgba(245, 158, 11, 0.1);
            border-color: var(--warning-color);
            color: var(--warning-color);
        }

        .slot-number {
            font-weight: 700;
            font-size: 1.125rem;
        }

        .slot-type {
            font-size: 0.75rem;
            opacity: 0.8;
        }

        .slot-indicator {
            position: absolute;
            top: 0.5rem;
            right: 0.5rem;
            width: 8px;
            height: 8px;
            border-radius: 50%;
        }

        .timer-section {
            background: var(--light-surface);
            border-radius: 0.125rem;
            padding: 1.5rem;
            box-shadow: var(--shadow-md);
            margin-bottom: 2rem;
        }

        .timer-display {
            text-align: center;
            padding: 2rem;
            background: linear-gradient(135deg, var(--primary-color), var(--primary-dark));
            border-radius: 0.125rem;
            color: white;
        }

        .timer-value {
            font-size: 3rem;
            font-weight: 700;
            font-family: 'Courier New', monospace;
            margin-bottom: 0.5rem;
        }

        .timer-label {
            font-size: 1.125rem;
            opacity: 0.9;
        }

        .billing-info {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
            gap: 1rem;
            margin-top: 1.5rem;
        }

        .billing-item {
            text-align: center;
            padding: 1rem;
            background: rgba(245, 48, 3, 0.05);
            border-radius: 0.125rem;
        }

        .billing-value {
            font-size: 1.5rem;
            font-weight: 700;
            color: var(--primary-color);
        }

        .billing-label {
            font-size: 0.875rem;
            color: var(--text-secondary);
        }

        .history-section {
            background: var(--light-surface);
            border-radius: 0.125rem;
            padding: 1.5rem;
            box-shadow: var(--shadow-md);
        }

        .history-controls {
            display: flex;
            gap: 1rem;
            margin-bottom: 1.5rem;
            flex-wrap: wrap;
        }

        .search-input {
            flex: 1;
            min-width: 200px;
            padding: 0.625rem 1rem;
            border: 1px solid var(--border-color);
            border-radius: 0.125rem;
            background: var(--light-bg);
            color: var(--text-primary);
        }

        .filter-select {
            padding: 0.625rem 1rem;
            border: 1px solid var(--border-color);
            border-radius: 0.125rem;
            background: var(--light-bg);
            color: var(--text-primary);
        }

        .table-container {
            overflow-x: auto;
            width: 100%;
        }

        .history-table {
            width: 100%;
            border-collapse: collapse;
            font-variant-numeric: tabular-nums;
        }

        .history-table th {
            text-align: left;
            padding: 1rem;
            border-bottom: 1px solid var(--border-color);
            font-weight: 600;
            color: var(--text-secondary);
            white-space: nowrap;
        }

        .history-table td {
            padding: 1rem;
            border-bottom: 1px solid var(--border-color);
            white-space: nowrap;
            vertical-align: middle;
        }

        .history-table tr:hover {
            background: rgba(245, 48, 3, 0.05);
        }

        .modal {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.5);
            z-index: 2000;
            align-items: center;
            justify-content: center;
        }

        .modal.active {
            display: flex;
        }

        .modal-content {
            background: var(--light-surface);
            border-radius: 0.125rem;
            padding: 2rem;
            max-width: 500px;
            width: 90%;
            max-height: 90vh;
            overflow-y: auto;
            animation: modalSlideIn 0.3s ease-out;
        }

        @keyframes modalSlideIn {
            from {
                transform: translateY(-50px);
                opacity: 0;
            }
            to {
                transform: translateY(0);
                opacity: 1;
            }
        }

        .modal-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 1.5rem;
        }

        .modal-title {
            font-size: 1.5rem;
            font-weight: 700;
            color: var(--text-primary);
        }

        .modal-close {
            background: none;
            border: none;
            font-size: 1.5rem;
            color: var(--text-secondary);
            cursor: pointer;
            padding: 0.25rem;
        }

        .form-group {
            margin-bottom: 1.5rem;
        }

        .form-label {
            display: block;
            margin-bottom: 0.5rem;
            font-weight: 500;
            color: var(--text-primary);
        }

        .form-input {
            width: 100%;
            padding: 0.75rem 1rem;
            border: 1px solid var(--border-color);
            border-radius: 0.125rem;
            background: var(--light-bg);
            color: var(--text-primary);
            transition: var(--transition);
        }

        .form-input:focus {
            outline: none;
            border-color: var(--primary-color);
            box-shadow: 0 0 0 3px rgba(245, 48, 3, 0.1);
        }

        .payment-methods {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(120px, 1fr));
            gap: 1rem;
            margin-bottom: 1.5rem;
        }

        .payment-method {
            padding: 1rem;
            border: 2px solid var(--border-color);
            border-radius: 0.125rem;
            text-align: center;
            cursor: pointer;
            transition: var(--transition);
        }

        .payment-method:hover {
            border-color: var(--primary-color);
        }

        .payment-method.selected {
            border-color: var(--primary-color);
            background: rgba(245, 48, 3, 0.1);
        }

        .payment-method i {
            font-size: 2rem;
            margin-bottom: 0.5rem;
            color: var(--primary-color);
        }

        .btn {
            padding: 0.75rem 1.5rem;
            border: none;
            border-radius: 0.125rem;
            font-weight: 600;
            cursor: pointer;
            transition: var(--transition);
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
        }

        .btn-primary {
            background: var(--primary-color);
            color: white;
        }

        .btn-primary:hover {
            background: var(--primary-dark);
        }

        .btn-secondary {
            background: var(--text-secondary);
            color: white;
        }

        .btn-secondary:hover {
            background: var(--text-primary);
        }

        .btn-success {
            background: var(--success-color);
            color: white;
        }

        .btn-success:hover {
            background: #059669;
        }

        .btn-danger {
            background: var(--danger-color);
            color: white;
        }

        .btn-danger:hover {
            background: #dc2626;
        }

        .btn-group {
            display: flex;
            gap: 1rem;
            margin-top: 1.5rem;
        }

        .btn-block {
            width: 100%;
            justify-content: center;
        }

        .notification-container {
            position: fixed;
            top: 1rem;
            right: 1rem;
            z-index: 3000;
            max-width: 400px;
        }

        .notification {
            background: var(--light-surface);
            border-radius: 0.125rem;
            padding: 1rem;
            margin-bottom: 1rem;
            box-shadow: var(--shadow-lg);
            border-left: 4px solid var(--primary-color);
            animation: slideInRight 0.3s ease-out;
        }

        @keyframes slideInRight {
            from {
                transform: translateX(100%);
                opacity: 0;
            }
            to {
                transform: translateX(0);
                opacity: 1;
            }
        }

        .notification.success {
            border-left-color: var(--success-color);
        }

        .notification.warning {
            border-left-color: var(--warning-color);
        }

        .notification.error {
            border-left-color: var(--danger-color);
        }

        .notification-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 0.5rem;
        }

        .notification-title {
            font-weight: 600;
            color: var(--text-primary);
        }

        .notification-close {
            background: none;
            border: none;
            color: var(--text-secondary);
            cursor: pointer;
        }

        .notification-message {
            color: var(--text-secondary);
            font-size: 0.875rem;
        }

        @media (max-width: 1024px) {
            .sidebar {
                transform: translateX(-100%);
            }

            .sidebar.active {
                transform: translateX(0);
            }

            .main-content {
                margin-left: 0;
            }

            .menu-toggle {
                display: block;
            }

            .search-bar {
                width: 200px;
            }

            .user-info {
                display: none;
            }
        }

        @media (max-width: 768px) {
            .dashboard-content {
                padding: 1rem;
            }

            .overview-section {
                grid-template-columns: 1fr;
            }

            .parking-grid {
                grid-template-columns: repeat(auto-fit, minmax(60px, 1fr));
            }

            .billing-info {
                grid-template-columns: 1fr;
            }

            .timer-value {
                font-size: 2rem;
            }

            .history-controls {
                flex-direction: column;
            }

            .search-input {
                width: 100%;
            }

            .modal-content {
                padding: 1.5rem;
            }
        }

        @media (max-width: 480px) {
            .header {
                padding: 1rem;
            }

            .header-right {
                gap: 0.5rem;
            }

            .parking-stats {
                flex-direction: column;
                gap: 1rem;
            }

            .btn-group {
                flex-direction: column;
            }
        }

        .loading {
            display: inline-block;
            width: 20px;
            height: 20px;
            border: 3px solid rgba(245, 48, 3, 0.3);
            border-radius: 50%;
            border-top-color: var(--primary-color);
            animation: spin 1s ease-in-out infinite;
        }

        @keyframes spin {
            to { transform: rotate(360deg); }
        }

        .page-section {
            animation: fadeIn 0.3s ease-out;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(10px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .page-section.hidden {
            display: none;
        }

        .text-center { text-align: center; }
        .text-left { text-align: left; }
        .text-right { text-align: right; }
        .hidden { display: none !important; }
        .flex { display: flex; }
        .items-center { align-items: center; }
        .justify-between { justify-content: space-between; }
        .gap-1 { gap: 0.25rem; }
        .gap-2 { gap: 0.5rem; }
        .gap-4 { gap: 1rem; }
        .mt-4 { margin-top: 1rem; }
        .mb-4 { margin-bottom: 1rem; }
        .p-4 { padding: 1rem; }
    </style>
</head>
<body>
    <div class="dashboard-container">
        <aside class="sidebar" id="sidebar">
            <div class="sidebar-header">
                <div class="logo">
                    <img src="{{ asset('images/parkmasterlogo.png') }}" alt="ParkMaster Logo" style="width: 32px; height: 32px; object-fit: cover; border-radius: 6px;">
                    <span class="logo-text"><span>Park</span>Master</span>
                </div>
            </div>
            <nav class="nav-menu">
                @if(Auth::user()->isAdmin())
                    <a href="{{ route('admin.dashboard') }}" class="nav-item {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                        <i class="fas fa-home"></i>
                        <span class="nav-text">Admin Dashboard</span>
                    </a>
                    <a href="{{ route('admin.slots') }}" class="nav-item {{ request()->routeIs('admin.slots') ? 'active' : '' }}">
                        <i class="fas fa-parking"></i>
                        <span class="nav-text">Slot Management</span>
                    </a>
                    <a href="{{ route('admin.logs') }}" class="nav-item {{ request()->routeIs('admin.logs') ? 'active' : '' }}">
                        <i class="fas fa-history"></i>
                        <span class="nav-text">Parking Logs</span>
                    </a>
                    <a href="{{ route('admin.users') }}" class="nav-item {{ request()->routeIs('admin.users') ? 'active' : '' }}">
                        <i class="fas fa-users"></i>
                        <span class="nav-text">User Management</span>
                    </a>
                @else
                    <a href="#" class="nav-item active" data-page="dashboard">
                        <i class="fas fa-home"></i>
                        <span class="nav-text">Dashboard</span>
                    </a>
                    <a href="#" class="nav-item" data-page="parking" onclick="showPage('parking')">
                        <div class="nav-icon"><i class="fas fa-car"></i></div>
                        <span class="nav-text">Parking Availability</span>
                    </a>
                    <a href="#" class="nav-item" data-page="history">
                        <i class="fas fa-history"></i>
                        <span class="nav-text">History</span>
                    </a>
                    <a href="#" class="nav-item" data-page="vehicles">
                        <i class="fas fa-car-side"></i>
                        <span class="nav-text">Vehicles</span>
                    </a>
                    <a href="#" class="nav-item" data-page="payments">
                        <i class="fas fa-credit-card"></i>
                        <span class="nav-text">Payments</span>
                    </a>
                    <a href="#" class="nav-item" data-page="profile">
                        <i class="fas fa-user"></i>
                        <span class="nav-text">Profile</span>
                    </a>
                    <a href="#" class="nav-item" data-page="settings">
                        <i class="fas fa-cog"></i>
                        <span class="nav-text">Settings</span>
                    </a>
                @endif
            </nav>
            <div class="sidebar-footer p-4">
                <button class="btn btn-danger btn-block" onclick="logout()">
                    <i class="fas fa-sign-out-alt"></i>
                    <span class="nav-text">Logout</span>
                </button>
            </div>
        </aside>

        <main class="main-content">
            <header class="header">
                <div class="header-left">
                    <button class="menu-toggle" onclick="toggleSidebar()">
                        <i class="fas fa-bars"></i>
                    </button>
                    <div style="flex: 1;"></div>
                </div>
                <div class="header-right">
                    <button class="notification-btn" onclick="toggleNotifications(event)">
                        <i class="fas fa-bell"></i>
                        <span class="notification-badge"></span>
                    </button>
                    <div class="notification-dropdown" id="notificationDropdown">
                        <div class="notification-dropdown-header">
                            <h4>Notifications</h4>
                        </div>
                        <div class="notification-dropdown-list">
                            @forelse($recentNotifications as $notif)
                            <a href="javascript:void(0)" onclick="showPage('notifications'); document.getElementById('notificationDropdown').classList.remove('active');" class="notification-dropdown-item">
                                <div class="notification-dropdown-icon" style="background: {{ $notif['bg'] }}; color: {{ $notif['color'] }};">
                                    <i class="{{ $notif['icon'] }}"></i>
                                </div>
                                <div class="notification-dropdown-content">
                                    <div class="notification-dropdown-title">{{ $notif['title'] }}</div>
                                    <div class="notification-dropdown-time">{{ $notif['time']->diffForHumans() }}</div>
                                </div>
                            </a>
                            @empty
                            <div style="padding: 2rem; text-align: center; color: var(--text-secondary);">
                                <p style="font-size: 0.8125rem;">No recent notifications</p>
                            </div>
                            @endforelse
                        </div>
                        <div style="padding: 0.75rem; text-align: center; border-top: 1px solid var(--border-color);">
                            <a href="javascript:void(0)" onclick="showPage('notifications'); document.getElementById('notificationDropdown').classList.remove('active');" style="font-size: 0.8125rem; color: var(--text-secondary); text-decoration: none; font-weight: 600;">View All Activity</a>
                        </div>
                    </div>
                    <button class="theme-toggle" onclick="toggleTheme()">
                        <i class="fas fa-moon" id="themeIcon"></i>
                    </button>
                    <div class="user-profile">
                        <div class="user-avatar">{{ substr($user->name, 0, 2) }}</div>
                        <div class="user-info">
                            <div class="user-name">{{ $user->name }}</div>
                            <div class="user-status">{{ ucfirst($user->role) }} Member</div>
                        </div>
                    </div>
                </div>
            </header>

            <div class="dashboard-content">

                <section class="page-section" id="page-dashboard">
                    <div id="timerSection" style="display: none; margin-bottom: 2rem;">
                        <div class="section-header">
                            <h2 class="section-title">Active Parking Session</h2>
                        </div>
                        
                        <div class="timer-container" style="background: linear-gradient(135deg, #f53003, #d62828); color: white; padding: 2.5rem; border-radius: 1rem; text-align: center; margin-bottom: 1.5rem; box-shadow: 0 10px 20px rgba(245, 48, 3, 0.2);">
                            <div style="font-size: 4.5rem; font-weight: 800; margin-bottom: 0.5rem; letter-spacing: 0.2rem; font-family: 'Courier New', monospace;" id="timerDisplay">00:00:00</div>
                            <div style="font-size: 1rem; opacity: 0.9; text-transform: uppercase; letter-spacing: 0.2rem; font-weight: 600;">Time Remaining</div>
                        </div>
                        
                        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(180px, 1fr)); gap: 1rem; margin-bottom: 1.5rem;">
                            <div class="overview-card" style="text-align: center; padding: 1.25rem; border-bottom: 4px solid #f53003;">
                                <div style="font-size: 1.5rem; font-weight: 700; color: #f53003; margin-bottom: 0.25rem;" id="timerFee">₱0.00</div>
                                <div style="color: var(--text-secondary); font-size: 0.75rem; font-weight: 600; text-transform: uppercase;">Current Fee</div>
                            </div>
                            <div class="overview-card" style="text-align: center; padding: 1.25rem; border-bottom: 4px solid #f53003;">
                                <div style="font-size: 1.5rem; font-weight: 700; color: #f53003; margin-bottom: 0.25rem;" id="timerRate">₱0.00</div>
                                <div style="color: var(--text-secondary); font-size: 0.75rem; font-weight: 600; text-transform: uppercase;">Hourly Rate</div>
                            </div>
                            <div class="overview-card" style="text-align: center; padding: 1.25rem; border-bottom: 4px solid #f53003;">
                                <div style="font-size: 1.5rem; font-weight: 700; color: #f53003; margin-bottom: 0.25rem;" id="timerSlot">-</div>
                                <div style="color: var(--text-secondary); font-size: 0.75rem; font-weight: 600; text-transform: uppercase;">Slot</div>
                            </div>
                            <div class="overview-card" style="text-align: center; padding: 1.25rem; border-bottom: 4px solid #f53003;">
                                <div style="font-size: 1.5rem; font-weight: 700; color: #f53003; margin-bottom: 0.25rem;" id="timerPlate">-</div>
                                <div style="color: var(--text-secondary); font-size: 0.75rem; font-weight: 600; text-transform: uppercase;">Plate</div>
                            </div>
                        </div>
                        
                        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem;">
                            <button class="btn btn-success" style="padding: 1rem; font-weight: 700; border-radius: 0.5rem;" onclick="openExtendModal()">
                                <i class="fas fa-plus-circle" style="margin-right: 0.5rem;"></i>
                                Extend
                            </button>
                            <button class="btn btn-danger" style="padding: 1rem; font-weight: 700; border-radius: 0.5rem;" onclick="confirmEndSession()">
                                <i class="fas fa-stop-circle" style="margin-right: 0.5rem;"></i>
                                End
                            </button>
                        </div>
                        
                    </div>
                <section class="overview-section">
                    <div class="overview-card">
                        <div class="overview-header">
                            <div>
                                <div class="overview-title">Account Status</div>
                                <div class="parking-status status-available">
                                    <i class="fas fa-circle"></i>
                                    Available
                                </div>
                            </div>
                            <div class="overview-icon icon-primary">
                                <i class="fas fa-user"></i>
                            </div>
                        </div>
                        <div class="overview-value">{{ $user->name }}</div>
                        <div class="overview-label">{{ ucfirst($user->role) }} Member Since {{ $user->created_at->format('Y') }}</div>
                    </div>

                    <div class="overview-card" id="currentParkingCard">
                        <div class="overview-header">
                            <div>
                                <div class="overview-title">Current Parking</div>
                                <div class="parking-status {{ $activeSessions->count() > 0 ? 'status-parked' : 'status-available' }}" id="currentParkingStatus">
                                    <i class="fas fa-circle"></i>
                                    {{ $activeSessions->count() > 0 ? 'Parked' : 'Available' }}
                                </div>
                            </div>
                            <div class="overview-icon icon-success">
                                <i class="fas fa-car"></i>
                            </div>
                        </div>
                        <div class="overview-value" id="currentParkingValue">
                            {{ $activeSessions->count() > 0 ? $activeSessions->first()->parkingSlot->slot_number : 'No Active' }}
                        </div>
                        <div class="overview-label" id="currentParkingLabel">
                            {{ $activeSessions->count() > 0 ? 'Slot ' . $activeSessions->first()->parkingSlot->slot_number . ' • Active' : 'Not Currently Parked' }}
                        </div>
                    </div>

                    @unless(Auth::user()->isAdmin())
                    <div class="overview-card">
                        <div class="overview-header">
                            <div>
                                <div class="overview-title">Total Spent</div>
                            </div>
                            <div class="overview-icon icon-warning">
                                <i class="fas fa-wallet"></i>
                            </div>
                        </div>
                        <div class="overview-value">₱{{ number_format($totalSpent, 2) }}</div>
                        <div class="overview-label">{{ $totalSessions }} Sessions Total</div>
                    </div>

                    <div class="overview-card" style="cursor: pointer;" onclick="showPage('vehicles')">
                        <div class="overview-header">
                            <div>
                                <div class="overview-title">My Vehicles</div>
                            </div>
                            <div class="overview-icon icon-primary" style="background: rgba(34, 211, 238, 0.1); color: var(--secondary-color);">
                                <i class="fas fa-car-side"></i>
                            </div>
                        </div>
                        <div class="overview-value" id="overview-vehicle-count">{{ $userVehicles->count() }}</div>
                        <div class="overview-label">Registered Vehicles</div>
                    </div>
                    @endunless
                </section>




                </section>

                <section class="page-section hidden" id="page-parking">
                    <div class="parking-section" id="parkingAvailabilityGrid">
                        <div class="section-header">
                            <h2 class="section-title">Parking Availability</h2>
                            <div class="parking-stats">
                                <div class="stat-item">
                                    <div class="stat-dot available"></div>
                                    <span>Available</span>
                                </div>
                                <div class="stat-item">
                                    <div class="stat-dot occupied"></div>
                                    <span>Occupied</span>
                                </div>
                            </div>
                        </div>
                        <div class="parking-grid" id="parkingGrid">
                        </div>
                        <button class="btn btn-primary" onclick="refreshParkingGrid()">
                            <i class="fas fa-sync-alt"></i>
                            Refresh Status
                        </button>
                    </div>
                </section>

                @unless(Auth::user()->isAdmin())
                <section class="page-section hidden" id="page-history">
                    <div class="parking-section">
                        <div class="section-header">
                            <h2 class="section-title">Full Parking History</h2>
                        </div>
                        <div class="history-controls">
                            <div class="search-box" style="position: relative; flex: 1;">
                                <label for="fullHistorySearch" class="sr-only" style="position: absolute; width: 1px; height: 1px; padding: 0; margin: -1px; overflow: hidden; clip: rect(0,0,0,0); border: 0;">Search History</label>
                                <input type="text" class="search-input" placeholder="Search by vehicle or slot..." id="fullHistorySearch" autocomplete="off">
                            </div>
                            <div class="filter-box" style="position: relative;">
                                <label for="fullHistoryFilter" class="sr-only" style="position: absolute; width: 1px; height: 1px; padding: 0; margin: -1px; overflow: hidden; clip: rect(0,0,0,0); border: 0;">Filter History</label>
                                <select class="filter-select" id="fullHistoryFilter" autocomplete="off">
                                    <option value="all">All Transactions</option>
                                    <option value="today">Today</option>
                                    <option value="week">This Week</option>
                                    <option value="month">This Month</option>
                                    <option value="year">This Year</option>
                                </select>
                            </div>
                            <button class="btn btn-secondary" onclick="exportFullHistory()">
                                <i class="fas fa-download"></i>
                                Export CSV
                            </button>
                        </div>
                        <div class="table-container">
                            <table class="history-table">
                                <thead>
                                    <tr>
                                        <th>Date</th>
                                        <th>Slot</th>
                                        <th>Vehicle</th>
                                        <th>Entry Time</th>
                                        <th>Exit Time</th>
                                        <th>Duration</th>
                                        <th>Amount</th>
                                        <th>Method</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                                <tbody id="fullHistoryTableBody">
                                    <tr>
                                        <td colspan="8" style="text-align: center; padding: 3rem; color: var(--text-secondary);">
                                            <i class="fas fa-search" style="font-size: 2rem; margin-bottom: 1rem; display: block;"></i>
                                            <p>Enter a search term or select a filter to view your full parking history</p>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </section>
                @endunless

                <section class="page-section hidden" id="page-vehicles">
                    <div class="parking-section">
                        <div class="section-header">
                            <h2 class="section-title">My Vehicles</h2>
                            <button class="btn btn-primary" onclick="openModal('addVehicleModal')">
                                <i class="fas fa-plus"></i>
                                Add Vehicle
                            </button>
                        </div>
                        <div id="vehiclesGrid" style="display: flex; flex-direction: column; gap: 1rem;">
                            @forelse($userVehicles as $vehicle)
                            <div class="overview-card" style="padding: 0.75rem; display: flex; justify-content: space-between; align-items: center;">
                                <div>
                                    <div class="overview-header" style="margin-bottom: 0.25rem;">
                                        <div>
                                            <div class="overview-title" style="font-size: 0.8rem;">{{ $vehicle->license_plate }}</div>
                                            <div class="parking-status" style="background: rgba(16, 185, 129, 0.1); color: var(--success-color); font-size: 0.7rem; padding: 0.15rem 0.4rem;">
                                                <i class="fas fa-check-circle"></i>
                                                Active
                                            </div>
                                        </div>
                                    </div>
                                    <div class="overview-value" style="font-size: 1.125rem; margin-bottom: 0.15rem;">{{ $vehicle->make }} {{ $vehicle->model }}</div>
                                    <div class="overview-label" style="font-size: 0.8rem;">Color: {{ $vehicle->color ?? 'N/A' }}</div>
                                </div>
                                <div style="text-align: right; margin-right: 0.5rem;">
                                    <span style="font-size: 0.75rem; color: var(--text-secondary); text-transform: uppercase; font-weight: 600; letter-spacing: 0.05em;">Type</span>
                                    <div style="font-size: 1.125rem; font-weight: 700; color: var(--text-primary); text-transform: capitalize;">{{ $vehicle->type ?? 'Car' }}</div>
                                </div>
                            </div>
                            @empty
                            <div id="noVehiclesMessage" style="grid-column: 1 / -1; text-align: center; padding: 3rem; color: var(--text-secondary);">
                                <p>No vehicles added yet</p>
                                <p style="font-size: 0.8rem; margin-top: 0.5rem;">Click "Add Vehicle" to get started</p>
                            </div>
                            @endforelse
                        </div>
                    </div>
                </section>

                <section class="page-section hidden" id="page-payments">
                    <div class="parking-section">
                        <div class="section-header">
                            <h2 class="section-title">Payment Methods</h2>
                            <button class="btn btn-primary" onclick="openModal('addPaymentModal')">
                                <i class="fas fa-plus"></i>
                                Add Payment Method
                            </button>
                        </div>
                        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 1.5rem; margin-bottom: 2rem;" id="paymentMethodsGrid">
                            @forelse($paymentMethods as $method)
                            <div class="overview-card" style="cursor: pointer; border-left: 4px solid {{ $method->is_primary ? 'var(--primary-color)' : 'transparent' }};">
                                <div class="overview-header">
                                    <div class="overview-icon {{ $method->type === 'card' ? 'icon-primary' : ($method->type === 'ewallet' ? 'icon-warning' : 'icon-success') }}">
                                        <i class="fas {{ $method->type === 'card' ? 'fa-credit-card' : ($method->type === 'ewallet' ? 'fa-wallet' : 'fa-university') }}"></i>
                                    </div>
                                    @if($method->is_primary)
                                        <span class="badge badge-active" style="font-size: 0.6rem;">Primary</span>
                                    @endif
                                </div>
                                <div class="overview-value" style="font-size: 1.25rem;">
                                    @if(strtolower($method->provider) === 'gcash') GCash
                                    @elseif(strtolower($method->provider) === 'paymaya') PayMaya
                                    @elseif(strtolower($method->provider) === 'grabpay') GrabPay
                                    @else {{ ucfirst($method->provider) }}
                                    @endif
                                </div>
                                <div class="overview-label">{{ $method->account_number }}</div>
                            </div>
                            @empty
                            <div class="overview-card" style="grid-column: 1 / -1; text-align: center; padding: 3rem;">
                                <div class="overview-icon" style="margin: 0 auto 1rem; background: var(--light-bg);">
                                    <i class="fas fa-credit-card" style="color: var(--text-secondary); opacity: 0.5;"></i>
                                </div>
                                <div class="overview-value" style="font-size: 1.1rem; color: var(--text-secondary);">No payment methods added</div>
                                <p style="font-size: 0.8rem; color: var(--text-secondary); margin-top: 0.5rem;">Add a payment method to speed up your future bookings</p>
                            </div>
                            @endforelse
                        </div>

                        <div class="section-header">
                            <h2 class="section-title">Payment History</h2>
                        </div>
                        <div class="table-container">
                            <table class="history-table">
                                <thead>
                                    <tr>
                                        <th>Date</th>
                                        <th>Description</th>
                                        <th>Amount</th>
                                        <th>Method</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                                <tbody id="historyTableBody">
                                    <tr>
                                        <td colspan="5" style="text-align: center; padding: 3rem; color: var(--text-secondary);">
                                            <p>No payment records yet</p>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </section>

                <section class="page-section hidden" id="page-profile">
                    <div class="parking-section">
                        <div class="section-header">
                            <h2 class="section-title">My Profile</h2>
                        </div>
                        <div style="max-width: 600px;">
                            <div class="form-group">
                                <label class="form-label">Full Name</label>
                                <input type="text" class="form-input" id="display-user-name" value="{{ $user->name }}" readonly>
                            </div>
                            <div class="form-group">
                                <label class="form-label">Email</label>
                                <input type="email" class="form-input" id="display-user-email" value="{{ $user->email }}" readonly>
                            </div>
                            <div class="form-group">
                                <label class="form-label">Role</label>
                                <input type="text" class="form-input" value="{{ ucfirst($user->role) }}" readonly>
                            </div>
                            <div class="form-group">
                                <label class="form-label">Member Since</label>
                                <input type="text" class="form-input" value="{{ $user->created_at->format('F Y') }}" readonly>
                            </div>
                            <div class="btn-group">
                                <button class="btn btn-primary" onclick="openModal('editProfileModal')">
                                    <i class="fas fa-edit"></i>
                                    Edit Profile
                                </button>
                                <button class="btn btn-secondary" onclick="openModal('changePasswordModal')">
                                    <i class="fas fa-lock"></i>
                                    Change Password
                                </button>
                            </div>
                        </div>
                    </div>
                </section>

                <section class="page-section hidden" id="page-settings">
                    <div class="parking-section">
                        <div class="section-header">
                            <h2 class="section-title">Settings</h2>
                        </div>
                        <div style="max-width: 600px;">
                            <div class="overview-card" style="margin-bottom: 1rem;">
                                <div style="display: flex; justify-content: space-between; align-items: center;">
                                    <div>
                                        <div style="font-weight: 600; margin-bottom: 0.25rem;">Dark Mode</div>
                                        <div style="color: var(--text-secondary); font-size: 0.875rem;">Toggle dark theme</div>
                                    </div>
                                    <button class="btn btn-secondary" onclick="toggleTheme()">
                                        <i class="fas fa-moon" id="settingsThemeIcon"></i>
                                        Toggle
                                    </button>
                                </div>
                            </div>
                            <div class="overview-card" style="margin-bottom: 1rem;">
                                <div style="display: flex; justify-content: space-between; align-items: center;">
                                    <div>
                                        <div style="font-weight: 600; margin-bottom: 0.25rem;">Notifications</div>
                                        <div style="color: var(--text-secondary); font-size: 0.875rem;">Enable push notifications</div>
                                    </div>
                                    <button class="btn btn-success" onclick="showNotification('Notifications enabled!', 'success')">
                                        <i class="fas fa-bell"></i>
                                        Enabled
                                    </button>
                                </div>
                            </div>
                            <div class="overview-card" style="margin-bottom: 1rem;">
                                <div style="display: flex; justify-content: space-between; align-items: center;">
                                    <div>
                                        <div style="font-weight: 600; margin-bottom: 0.25rem; color: var(--danger-color);">Delete Account</div>
                                        <div style="color: var(--text-secondary); font-size: 0.875rem;">Permanently delete your account</div>
                                    </div>
                                    <button class="btn btn-danger" onclick="showNotification('Please contact admin to delete account', 'warning')">
                                        <i class="fas fa-trash"></i>
                                        Delete
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>
                
                <section class="page-section hidden" id="page-notifications">
                    <div class="parking-section">
                        <div class="section-header">
                            <h2 class="section-title">System Notifications</h2>
                        </div>
                        <div class="activity-section">
                            @forelse($recentNotifications as $notif)
                            <div class="overview-card" style="margin-bottom: 1rem; display: flex; gap: 1.5rem; align-items: center; border-left: 4px solid {{ $notif['color'] }};">
                                <div class="overview-icon" style="background: {{ $notif['bg'] }}; color: {{ $notif['color'] }}; width: 48px; height: 48px; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 1.25rem;">
                                    <i class="{{ $notif['icon'] }}"></i>
                                </div>
                                <div style="flex: 1;">
                                    <div style="display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 0.25rem;">
                                        <h4 style="font-weight: 700; color: var(--text-primary); margin: 0;">{{ $notif['title'] }}</h4>
                                        <span style="font-size: 0.8125rem; color: var(--text-secondary);">{{ $notif['time']->diffForHumans() }}</span>
                                    </div>
                                    <p style="color: var(--text-secondary); margin: 0; font-size: 0.9375rem;">{{ $notif['message'] }}</p>
                                </div>
                            </div>
                            @empty
                            <div style="padding: 4rem; text-align: center; color: var(--text-secondary); background: var(--light-surface); border-radius: 0.125rem;">
                                <i class="fas fa-bell-slash" style="font-size: 3rem; margin-bottom: 1rem; display: block;"></i>
                                <p>No new notifications at this time.</p>
                            </div>
                            @endforelse
                        </div>
                    </div>
                </section>

            </div>
        </main>
    </div>

    <div class="modal" id="editProfileModal">
        <div class="modal-content">
            <div class="modal-header">
                <h3 class="modal-title">Edit Profile</h3>
                <button class="modal-close" onclick="closeModal('editProfileModal')">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            <form id="editProfileForm">
                @csrf
                @method('patch')
                <div class="form-group">
                    <label class="form-label">Full Name</label>
                    <input type="text" name="name" id="edit-name" class="form-input" value="{{ $user->name }}" required>
                </div>
                <div class="form-group">
                    <label class="form-label">Email Address</label>
                    <input type="email" name="email" id="edit-email" class="form-input" value="{{ $user->email }}" required>
                </div>
                <div class="btn-group">
                    <button type="button" class="btn btn-secondary" onclick="closeModal('editProfileModal')">
                        Cancel
                    </button>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save"></i>
                        Save Changes
                    </button>
                </div>
            </form>
        </div>
    </div>

    <div class="modal" id="bookingModal">
        <div class="modal-content">
            <div class="modal-header">
                <h3 class="modal-title">Book Parking Slot</h3>
                <button class="modal-close" onclick="closeModal('bookingModal')">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            <form id="bookingForm">
                <div class="form-group">
                    <label for="selectedSlotInput" class="form-label">Selected Slot</label>
                    <input type="text" class="form-input" id="selectedSlotInput" readonly autocomplete="off">
                </div>
                <div class="form-group">
                    <label for="vehicleSelect" class="form-label">Vehicle</label>
                    <select class="form-input" id="vehicleSelect" required autocomplete="off">
                        <option value="">Select Vehicle</option>
                        @foreach($userVehicles as $vehicle)
                            <option value="{{ $vehicle->id }}">{{ $vehicle->license_plate }} - {{ $vehicle->make }} {{ $vehicle->model }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group">
                    <label for="durationSelect" class="form-label">Duration</label>
                    <select class="form-input" id="durationSelect" required autocomplete="off">
                        <option value="">Select Duration</option>
                        @for($i = 1; $i <= 10; $i++)
                            <option value="{{ $i }}">{{ $i }} Hour{{ $i > 1 ? 's' : '' }}</option>
                        @endfor
                    </select>
                </div>
                <div class="form-group">
                    <label for="estimatedCost" class="form-label">Estimated Cost</label>
                    <input type="text" class="form-input" id="estimatedCost" readonly value="₱0.00" autocomplete="off">
                </div>
                <div class="btn-group">
                    <button type="button" class="btn btn-secondary" onclick="closeModal('bookingModal')">
                        Cancel
                    </button>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-credit-card"></i>
                        Proceed to Payment
                    </button>
                </div>
            </form>
        </div>
    </div>

    <div class="modal" id="paymentModal">
        <div class="modal-content">
            <div class="modal-header">
                <h3 class="modal-title">Payment Details</h3>
                <button class="modal-close" onclick="closeModal('paymentModal')">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            <form id="paymentForm">
                <div class="form-group">
                    <label class="form-label">Booking Summary</label>
                    <div style="background: var(--light-bg); padding: 1rem; border-radius: 0.5rem;">
                        <div style="display: flex; justify-content: space-between; margin-bottom: 0.5rem;">
                            <span>Slot:</span>
                            <strong id="paymentSlot">A-15</strong>
                        </div>
                        <div style="display: flex; justify-content: space-between; margin-bottom: 0.5rem;">
                            <span>Duration:</span>
                            <strong id="paymentDuration">2 Hours</strong>
                        </div>
                        <div style="display: flex; justify-content: space-between;">
                            <span>Total:</span>
                            <strong id="paymentTotal" style="color: var(--primary-color);">₱6.00</strong>
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <div id="savedMethodsContainer" style="margin-bottom: 1.5rem;">
                        <label class="form-label">Payment Method</label>
                        <div class="payment-methods" id="savedMethodsGrid" style="grid-template-columns: repeat(auto-fit, minmax(100px, 1fr)); margin-bottom: 1rem;">
                        </div>
                    </div>
                </div>
                <div class="form-group" id="cardDetails" style="display: none;">
                    <label class="form-label">Card Number</label>
                    <input type="text" class="form-input" placeholder="1234 5678 9012 3456" maxlength="19">
                </div>
                <div class="btn-group">
                    <button type="button" class="btn btn-secondary" onclick="closeModal('paymentModal')">
                        Cancel
                    </button>
                    <button type="submit" class="btn btn-success">
                        <i class="fas fa-check"></i>
                        Complete Payment
                    </button>
                </div>
            </form>
        </div>
    </div>

    {{-- ===== PARKING RECEIPT MODAL ===== --}}
    <div class="modal" id="receiptModal">
        <div class="modal-content" id="receiptContent" style="max-width: 480px;">
            <div class="modal-header" style="background: linear-gradient(135deg, #1a1a2e 0%, #16213e 100%); padding: 1.5rem; border-bottom: 2px solid var(--primary-color);">
                <div style="display: flex; align-items: center; gap: 0.75rem;">
                    <div style="width: 40px; height: 40px; background: var(--primary-color); border-radius: 50%; display: flex; align-items: center; justify-content: center;">
                        <i class="fas fa-parking" style="color: white; font-size: 1.1rem;"></i>
                    </div>
                    <div>
                        <div style="font-size: 1.2rem; font-weight: 800; color: white; letter-spacing: 1px;">ParkMaster</div>
                        <div style="font-size: 0.7rem; color: var(--primary-color); letter-spacing: 2px; text-transform: uppercase;">Official Parking Receipt</div>
                    </div>
                </div>
                <button class="modal-close" onclick="closeModal('receiptModal')" style="color: white;">
                    <i class="fas fa-times"></i>
                </button>
            </div>

            <div style="padding: 1.5rem; background: var(--card-bg);">
                {{-- Transaction ID & Date --}}
                <div style="text-align: center; margin-bottom: 1.5rem; padding-bottom: 1rem; border-bottom: 1px dashed var(--border-color);">
                    <div id="receiptTxnId" style="font-size: 1.1rem; font-weight: 700; color: var(--primary-color); letter-spacing: 2px;">TXN-000000</div>
                    <div id="receiptDate" style="font-size: 0.8rem; color: var(--text-secondary); margin-top: 0.25rem;"></div>
                </div>

                {{-- Detail Rows --}}
                <div style="display: flex; flex-direction: column; gap: 0.75rem; margin-bottom: 1.5rem;">
                    <div style="display: flex; justify-content: space-between; align-items: center;">
                        <span style="color: var(--text-secondary); font-size: 0.85rem;"><i class="fas fa-user" style="width: 16px; margin-right: 0.5rem;"></i>Customer</span>
                        <strong id="receiptUser" style="font-size: 0.9rem;"></strong>
                    </div>
                    <div style="display: flex; justify-content: space-between; align-items: center;">
                        <span style="color: var(--text-secondary); font-size: 0.85rem;"><i class="fas fa-car" style="width: 16px; margin-right: 0.5rem;"></i>Vehicle</span>
                        <strong id="receiptVehicle" style="font-size: 0.9rem;"></strong>
                    </div>
                    <div style="display: flex; justify-content: space-between; align-items: center;">
                        <span style="color: var(--text-secondary); font-size: 0.85rem;"><i class="fas fa-parking" style="width: 16px; margin-right: 0.5rem;"></i>Slot</span>
                        <strong id="receiptSlot" style="font-size: 0.9rem;"></strong>
                    </div>
                    <div style="display: flex; justify-content: space-between; align-items: center;">
                        <span style="color: var(--text-secondary); font-size: 0.85rem;"><i class="fas fa-sign-in-alt" style="width: 16px; margin-right: 0.5rem;"></i>Entry Time</span>
                        <strong id="receiptEntry" style="font-size: 0.9rem;"></strong>
                    </div>
                    <div style="display: flex; justify-content: space-between; align-items: center;">
                        <span style="color: var(--text-secondary); font-size: 0.85rem;"><i class="fas fa-sign-out-alt" style="width: 16px; margin-right: 0.5rem;"></i>Exit Time</span>
                        <strong id="receiptExit" style="font-size: 0.9rem;"></strong>
                    </div>
                    <div style="display: flex; justify-content: space-between; align-items: center;">
                        <span style="color: var(--text-secondary); font-size: 0.85rem;"><i class="fas fa-clock" style="width: 16px; margin-right: 0.5rem;"></i>Duration</span>
                        <strong id="receiptDuration" style="font-size: 0.9rem;"></strong>
                    </div>
                    <div style="display: flex; justify-content: space-between; align-items: center;">
                        <span style="color: var(--text-secondary); font-size: 0.85rem;"><i class="fas fa-wallet" style="width: 16px; margin-right: 0.5rem;"></i>Payment Method</span>
                        <strong id="receiptMethod" style="font-size: 0.9rem;"></strong>
                    </div>
                </div>

                {{-- Total --}}
                <div style="background: linear-gradient(135deg, var(--primary-color), #ff6b35); border-radius: 0.75rem; padding: 1rem 1.5rem; display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.5rem;">
                    <span style="color: white; font-weight: 600; font-size: 1rem;">Total Amount Paid</span>
                    <span id="receiptTotal" style="color: white; font-weight: 800; font-size: 1.5rem;">₱0.00</span>
                </div>

                {{-- Footer --}}
                <div style="text-align: center; padding-top: 1rem; border-top: 1px dashed var(--border-color);">
                    <p style="font-size: 0.75rem; color: var(--text-secondary);">Thank you for using ParkMaster!</p>
                    <p style="font-size: 0.7rem; color: var(--text-secondary); margin-top: 0.25rem;">Keep this receipt for your records.</p>
                </div>
            </div>

            <div class="btn-group" style="padding: 1rem 1.5rem; border-top: 1px solid var(--border-color);" id="receiptButtons">
                <button type="button" class="btn btn-secondary" onclick="closeModal('receiptModal')">
                    <i class="fas fa-times"></i> Close
                </button>
                <button type="button" class="btn btn-primary" onclick="printReceipt()">
                    <i class="fas fa-print"></i> Print Receipt
                </button>
            </div>
        </div>
    </div>

    <div class="modal" id="addVehicleModal">
        <div class="modal-content">
            <div class="modal-header">
                <h3 class="modal-title">Add New Vehicle</h3>
                <button class="modal-close" onclick="closeModal('addVehicleModal')">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            <form id="addVehicleForm">
                <div class="form-group">
                    <label class="form-label">Vehicle Type *</label>
                    <select class="form-input" id="vehicleType" required>
                        <option value="">Select Type</option>
                        @foreach($parkingRates as $type => $rate)
                            <option value="{{ $type }}">{{ ucfirst($type) }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group">
                    <label class="form-label">License Plate *</label>
                    <input type="text" class="form-input" id="licensePlate" placeholder="ABC-1234" required>
                </div>
                <div class="form-group">
                    <label class="form-label">Make</label>
                    <input type="text" class="form-input" id="vehicleMake" placeholder="Toyota, Honda, Ford...">
                </div>
                <div class="form-group">
                    <label class="form-label">Model</label>
                    <input type="text" class="form-input" id="vehicleModel" placeholder="Camry, Civic, F-150...">
                </div>
                <div class="form-group">
                    <label class="form-label">Color</label>
                    <input type="text" class="form-input" id="vehicleColor" placeholder="Red, Blue, Black...">
                </div>
                <div class="btn-group">
                    <button type="button" class="btn btn-secondary" onclick="closeModal('addVehicleModal')">
                        Cancel
                    </button>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-plus"></i>
                        Add Vehicle
                    </button>
                </div>
            </form>
        </div>
    </div>

    <div class="modal" id="addPaymentModal">
        <div class="modal-content">
            <div class="modal-header">
                <h3 class="modal-title">Add Payment Method</h3>
                <button class="modal-close" onclick="closeModal('addPaymentModal')">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            <form id="addPaymentForm">
                <div class="form-group">
                    <label for="paymentType" class="form-label">Payment Type *</label>
                    <select class="form-input" id="paymentType" required autocomplete="off">
                        <option value="">Select Payment Type</option>
                        <option value="card">Credit/Debit Card</option>
                        <option value="ewallet">E-Wallet (PayPal, GCash, etc.)</option>
                        <option value="bank">Bank Transfer</option>
                    </select>
                </div>
                <div id="cardPaymentFields" style="display: none;">
                    <div class="form-group">
                        <label for="cardNumber" class="form-label">Card Number *</label>
                        <input type="text" class="form-input" id="cardNumber" placeholder="1234 5678 9012 3456" maxlength="19" autocomplete="cc-number">
                    </div>
                    <div class="form-group">
                        <label for="cardHolder" class="form-label">Cardholder Name *</label>
                        <input type="text" class="form-input" id="cardHolder" placeholder="John Doe" autocomplete="cc-name">
                    </div>
                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem;">
                        <div class="form-group">
                            <label for="expiryDate" class="form-label">Expiry Date *</label>
                            <input type="text" class="form-input" id="expiryDate" placeholder="MM/YY" maxlength="5" autocomplete="cc-exp">
                        </div>
                        <div class="form-group">
                            <label for="cvv" class="form-label">CVV *</label>
                            <input type="text" class="form-input" id="cvv" placeholder="123" maxlength="4" autocomplete="cc-csc">
                        </div>
                    </div>
                </div>
                <div id="ewalletFields" style="display: none;">
                    <div class="form-group">
                        <label for="ewalletProvider" class="form-label">E-Wallet Provider *</label>
                        <select class="form-input" id="ewalletProvider" autocomplete="off">
                            <option value="">Select Provider</option>
                            <option value="paypal">PayPal</option>
                            <option value="gcash">GCash</option>
                            <option value="paymaya">PayMaya</option>
                            <option value="grabpay">GrabPay</option>
                            <option value="other">Other</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="ewalletAccount" class="form-label">Account Number/Email *</label>
                        <input type="text" class="form-input" id="ewalletAccount" placeholder="Enter account details" autocomplete="email">
                    </div>
                </div>
                <div id="bankFields" style="display: none;">
                    <div class="form-group">
                        <label for="bankName" class="form-label">Bank Name *</label>
                        <input type="text" class="form-input" id="bankName" placeholder="Bank of America, Chase..." autocomplete="off">
                    </div>
                    <div class="form-group">
                        <label for="bankAccount" class="form-label">Account Number *</label>
                        <input type="text" class="form-input" id="bankAccount" placeholder="Enter account number" autocomplete="off">
                    </div>
                </div>
                <div class="btn-group">
                    <button type="button" class="btn btn-secondary" onclick="closeModal('addPaymentModal')">
                        Cancel
                    </button>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-plus"></i>
                        Add Payment Method
                    </button>
                </div>
            </form>
        </div>
    </div>

    <div class="modal" id="firstPaymentSetupModal">
        <div class="modal-content">
            <div class="modal-header" style="background: linear-gradient(135deg, var(--primary-color), #ff6b35);">
                <h3 class="modal-title" style="color: white;">
                    <i class="fas fa-credit-card"></i>
                    Welcome! Set Up Your Payment Method
                </h3>
            </div>
            <div style="padding: 1.5rem;">
                <p style="margin-bottom: 1.5rem; color: var(--text-secondary);">
                    To start booking parking slots, you need to add a payment method first. This will be used for all your parking transactions.
                </p>
                <form id="firstPaymentForm">
                    <div class="form-group">
                        <label class="form-label">Payment Type *</label>
                        <select class="form-input" id="firstPaymentType" required>
                            <option value="">Select Payment Type</option>
                            <option value="card">Credit/Debit Card</option>
                            <option value="ewallet">E-Wallet</option>
                            <option value="bank">Bank Transfer</option>
                        </select>
                    </div>
                    <div id="firstCardFields" style="display: none;">
                        <div class="form-group">
                            <label class="form-label">Card Number *</label>
                            <input type="text" class="form-input" id="firstCardNumber" placeholder="1234 5678 9012 3456" maxlength="19">
                        </div>
                        <div class="form-group">
                            <label class="form-label">Cardholder Name *</label>
                            <input type="text" class="form-input" id="firstCardHolder" placeholder="John Doe">
                        </div>
                        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem;">
                            <div class="form-group">
                                <label class="form-label">Expiry Date *</label>
                                <input type="text" class="form-input" id="firstExpiryDate" placeholder="MM/YY" maxlength="5">
                            </div>
                            <div class="form-group">
                                <label class="form-label">CVV *</label>
                                <input type="text" class="form-input" id="firstCvv" placeholder="123" maxlength="4">
                            </div>
                        </div>
                    </div>
                    <div id="firstEwalletFields" style="display: none;">
                        <div class="form-group">
                            <label class="form-label">E-Wallet Provider *</label>
                            <select class="form-input" id="firstEwalletProvider">
                                <option value="">Select Provider</option>
                                <option value="paypal">PayPal</option>
                                <option value="gcash">GCash</option>
                                <option value="paymaya">PayMaya</option>
                                <option value="other">Other</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label class="form-label">Account Number/Email *</label>
                            <input type="text" class="form-input" id="firstEwalletAccount" placeholder="Enter account details">
                        </div>
                    </div>
                    <div class="btn-group" style="margin-top: 1.5rem;">
                        <button type="submit" class="btn btn-primary btn-block">
                            <i class="fas fa-check"></i>
                            Save Payment Method
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="modal" id="changePasswordModal">
        <div class="modal-content">
            <div class="modal-header">
                <h3 class="modal-title">Change Password</h3>
                <button class="modal-close" onclick="closeModal('changePasswordModal')">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            <form id="changePasswordForm">
                @csrf
                @method('put')
                <div class="form-group">
                    <label for="current_password" class="form-label">Current Password</label>
                    <div style="position: relative;">
                        <input type="password" name="current_password" id="current_password" class="form-input" required autocomplete="current-password">
                        <i class="fas fa-eye" id="toggleCurrentPassword" onclick="togglePasswordVisibility('current_password', 'toggleCurrentPassword')" style="position: absolute; right: 1rem; top: 50%; transform: translateY(-50%); cursor: pointer; color: var(--text-light);"></i>
                    </div>
                </div>
                <div class="form-group">
                    <label for="password" class="form-label">New Password</label>
                    <div style="position: relative;">
                        <input type="password" name="password" id="password" class="form-input" required autocomplete="new-password">
                        <i class="fas fa-eye" id="togglePassword" onclick="togglePasswordVisibility('password', 'togglePassword')" style="position: absolute; right: 1rem; top: 50%; transform: translateY(-50%); cursor: pointer; color: var(--text-light);"></i>
                    </div>
                </div>
                <div class="form-group">
                    <label for="password_confirmation" class="form-label">Confirm New Password</label>
                    <div style="position: relative;">
                        <input type="password" name="password_confirmation" id="password_confirmation" class="form-input" required autocomplete="new-password">
                        <i class="fas fa-eye" id="toggleConfirmPassword" onclick="togglePasswordVisibility('password_confirmation', 'toggleConfirmPassword')" style="position: absolute; right: 1rem; top: 50%; transform: translateY(-50%); cursor: pointer; color: var(--text-light);"></i>
                    </div>
                </div>
                <div class="btn-group">
                    <button type="button" class="btn btn-secondary" onclick="closeModal('changePasswordModal')">
                        Cancel
                    </button>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save"></i>
                        Update Password
                    </button>
                </div>
            </form>
        </div>
    </div>

    <div class="modal" id="extendSessionModal">
        <div class="modal-content" style="max-width: 400px;">
            <div class="modal-header" style="background: #ffffff; border-bottom: 1px solid var(--border-color);">
                <h3 class="modal-title" style="color: #1b1b18; font-weight: 700;">
                    <i class="fas fa-plus-circle" style="color: #f53003; margin-right: 0.5rem;"></i>
                    Extend Parking Session
                </h3>
                <button class="modal-close" onclick="closeModal('extendSessionModal')" style="color: #1b1b18; opacity: 0.6;">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            <form id="extendSessionForm" style="padding: 1.5rem;">
                <div class="form-group">
                    <label class="form-label" style="font-weight: 600; color: var(--text-primary);">How many additional hours?</label>
                    <select class="form-input" id="extendHours" required style="border: 2px solid var(--border-color);">
                        @for($i = 1; $i <= 10; $i++)
                            <option value="{{ $i }}">{{ $i }} Hour{{ $i > 1 ? 's' : '' }}</option>
                        @endfor
                    </select>
                </div>
                <div class="form-group" style="background: var(--light-bg); padding: 1rem; border-radius: 0.75rem; margin-top: 1rem;">
                    <label class="form-label" style="margin-bottom: 0.25rem;">Estimated Additional Fee</label>
                    <input type="text" class="form-input" id="extendFee" readonly value="₱0.00" style="background: transparent; border: none; font-size: 1.5rem; font-weight: 800; color: var(--success-color); padding: 0;">
                </div>
                <div class="btn-group" style="margin-top: 2rem;">
                    <button type="button" class="btn btn-secondary" onclick="closeModal('extendSessionModal')" style="flex: 1;">Cancel</button>
                    <button type="submit" class="btn btn-success" style="flex: 2; font-weight: 700;">
                        <i class="fas fa-check-circle"></i>
                        Confirm Extension
                    </button>
                </div>
            </form>
        </div>
    </div>


    <div class="notification-container" id="notificationContainer"></div>

    <script>
        const userVehiclesData = @json($userVehicles->map(function($v) { return ['id' => $v->id, 'type' => $v->type]; })->keyBy('id'));
        const parkingRatesData = @json($parkingRates);

        const state = {
            currentUser: {
                name: @json($user->name),
                email: @json($user->email),
                role: @json($user->role),
                balance: {{ (float)$totalSpent }},
                status: @json($activeSessions->count() > 0 ? 'parked' : 'available'),
                currentParking: @json($activeSessions->count() > 0 ? $activeSessions->first()->parkingSlot->slot_number : null)
            },
            parkingSlots: @json($allParkingSlots),
            currentSession: @json($activeSessions->first() ?? null),
            timer: null,
            selectedSlot: null,
            selectedPaymentMethod: null,
            notifications: [],
            history: [],
            historyLoaded: false,
            paymentMethods: @json($paymentMethods),
            selectedSavedMethodId: null
        };

        function addPaymentMethodToUI(method) {
            const grid = document.getElementById('paymentMethodsGrid');
            if (!grid) return;

            const emptyState = grid.querySelector('.overview-card[style*="grid-column: 1 / -1"]');
            if (emptyState) emptyState.remove();

            const iconClass = method.type === 'card' ? 'icon-primary' : (method.type === 'ewallet' ? 'icon-warning' : 'icon-success');
            const icon = method.type === 'card' ? 'fa-credit-card' : (method.type === 'ewallet' ? 'fa-wallet' : 'fa-university');

            let providerName = method.provider;
            if (providerName.toLowerCase() === 'gcash') providerName = 'GCash';
            else if (providerName.toLowerCase() === 'paymaya') providerName = 'PayMaya';
            else if (providerName.toLowerCase() === 'grabpay') providerName = 'GrabPay';
            else providerName = providerName.charAt(0).toUpperCase() + providerName.slice(1);

            const card = document.createElement('div');
            card.className = 'overview-card';
            card.style.cursor = 'pointer';
            card.style.borderLeft = method.is_primary ? '4px solid var(--primary-color)' : 'transparent';
            card.innerHTML = `
                <div class="overview-header">
                    <div class="overview-icon ${iconClass}">
                        <i class="fas ${icon}"></i>
                    </div>
                    ${method.is_primary ? '<span class="badge badge-active" style="font-size: 0.6rem;">Primary</span>' : ''}
                </div>
                <div class="overview-value" style="font-size: 1.25rem;">${providerName}</div>
                <div class="overview-label">${method.account_number}</div>
            `;
            grid.prepend(card);
        }

        function updateSavedMethodsInBooking() {
            const container = document.getElementById('savedMethodsContainer');
            if (!container) return;
            
            container.style.display = 'block';
            const grid = document.getElementById('savedMethodsGrid');
            grid.innerHTML = '';
            
            state.paymentMethods.forEach(method => {
                let providerName = method.provider;
                if (providerName.toLowerCase() === 'gcash') providerName = 'GCash';
                else if (providerName.toLowerCase() === 'paymaya') providerName = 'PayMaya';
                else if (providerName.toLowerCase() === 'grabpay') providerName = 'GrabPay';
                else providerName = providerName.charAt(0).toUpperCase() + providerName.slice(1);

                const item = document.createElement('div');
                item.className = `payment-method ${state.selectedSavedMethodId == method.id ? 'selected' : ''}`;
                item.onclick = () => selectSavedPaymentMethod(method);
                item.innerHTML = `
                    <i class="fas ${method.type === 'card' ? 'fa-credit-card' : (method.type === 'ewallet' ? 'fa-wallet' : 'fa-university')}"></i>
                    <div>${providerName}</div>
                    <div style="font-size: 0.6rem; opacity: 0.7;">${method.account_number}</div>
                `;
                grid.appendChild(item);
            });

            const cashItem = document.createElement('div');
            cashItem.className = `payment-method ${state.selectedPaymentMethod === 'cash' ? 'selected' : ''}`;
            cashItem.onclick = () => selectPaymentMethod('cash');
            cashItem.innerHTML = `
                <i class="fas fa-money-bill-wave"></i>
                <div>Cash</div>
                <div style="font-size: 0.6rem; opacity: 0.7;">Pay at exit</div>
            `;
            grid.appendChild(cashItem);
        }

        function selectSavedPaymentMethod(method) {
            state.selectedPaymentMethod = method.type;
            state.selectedSavedMethodId = method.id;
            
            document.querySelectorAll('.payment-methods .payment-method').forEach(el => el.classList.remove('selected'));
            document.getElementById('cardDetails').style.display = 'none';
            
            updateSavedMethodsInBooking();
        }

        document.addEventListener('DOMContentLoaded', function() {
            initializeDashboard();
            loadParkingData();
            loadHistoryData();
            startTimer();
            setupEventListeners();
        });

        function initializeDashboard() {
            const savedTheme = localStorage.getItem('theme') || 'light';
            if (savedTheme === 'dark') {
                document.documentElement.setAttribute('data-theme', 'dark');
                updateThemeIcon();
            }

            generateParkingGrid();
            updateSavedMethodsInBooking();
        }

        function generateParkingGrid() {
            const grid = document.getElementById('parkingGrid');
            grid.innerHTML = '';

            state.parkingSlots.forEach((slotData, index) => {
                const slot = document.createElement('div');
                const slotNumber = slotData.slot_number || slotData.slot_code;
                const status = slotData.status || 'available';
                
                slot.className = `parking-slot slot-${status}`;
                slot.dataset.slot = slotNumber;
                slot.dataset.status = status;
                slot.dataset.slotId = slotData.id;
                
                slot.innerHTML = `
                    <div class="slot-indicator" style="background: ${getStatusColor(status)}"></div>
                    <div class="slot-number">${slotNumber}</div>
                    <div class="slot-type">${slotData.type || 'Standard'}</div>
                `;
                
                if (status === 'available') {
                    slot.onclick = () => selectSlot(slotNumber, slotData.id);
                } else if (status === 'occupied') {
                    slot.onclick = () => showNotification(`Slot ${slotNumber} is currently occupied`, 'warning');
                }
                
                grid.appendChild(slot);
            });
        }

        function getRandomSlotStatus() {
            const statuses = ['available', 'occupied', 'reserved'];
            const weights = [0.5, 0.35, 0.15]; 
            const random = Math.random();
            let cumulative = 0;
            
            for (let i = 0; i < weights.length; i++) {
                cumulative += weights[i];
                if (random < cumulative) {
                    return statuses[i];
                }
            }
            return 'available';
        }

        function getStatusColor(status) {
            const colors = {
                available: 'var(--success-color)',
                occupied: 'var(--danger-color)',
                reserved: 'var(--warning-color)'
            };
            return colors[status] || 'var(--text-secondary)';
        }

        function getSlotType(index) {
            const types = ['Standard', 'Compact', 'EV', 'Disabled'];
            return types[index % types.length];
        }

        function selectSlot(slotNumber, slotId) {
            state.selectedSlot = { number: slotNumber, id: slotId };
            document.getElementById('selectedSlotInput').value = slotNumber;
            openModal('bookingModal');
        }

        function loadParkingData() {
            setTimeout(() => {
                updateParkingStats();
            }, 1000);
        }

        function updateParkingStats() {
            const slots = document.querySelectorAll('.parking-slot');
            const stats = {
                available: 0,
                occupied: 0
            };

            slots.forEach(slot => {
                const status = slot.dataset.status;
                if (stats.hasOwnProperty(status)) {
                    stats[status]++;
                }
            });

            const statElements = document.querySelectorAll('.stat-item span');
            if (statElements.length >= 1) statElements[0].textContent = `${stats.available} Available`;
            if (statElements.length >= 2) statElements[1].textContent = `${stats.occupied} Occupied`;
        }

        function loadHistoryData() {
            fetchFullHistory({});
        }

        function fetchFullHistory({ search = '', filter = '' } = {}) {
            const tbody = document.getElementById('fullHistoryTableBody');
            const histTbody = document.getElementById('historyTableBody');

            const loadingRow = `<tr><td colspan="8" style="text-align:center;padding:2rem;"><div class="loading" style="margin:0 auto"></div><p style="margin-top:1rem;color:var(--text-secondary)">Loading history...</p></td></tr>`;
            if (tbody) tbody.innerHTML = loadingRow;
            if (histTbody) histTbody.innerHTML = `<tr><td colspan="6" style="text-align:center;padding:2rem;"><div class="loading" style="margin:0 auto"></div></td></tr>`;

            let url = '/user/history/json?';
            if (search) url += `search=${encodeURIComponent(search)}&`;
            if (filter) url += `filter=${encodeURIComponent(filter)}&`;

            fetch(url, {
                headers: {
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                }
            })
            .then(r => r.json())
            .then(data => {
                if (data.success) {
                    state.history = data.history.map(log => ({
                        id: log.id,
                        date: log.date,
                        slot: log.slot,
                        vehicle: log.vehicle,
                        vehicle_details: log.vehicle_details,
                        vehicle_type: log.vehicle_type,
                        entry_time: log.entry_time,
                        exit_time: log.exit_time,
                        duration: log.duration,
                        amount: log.amount,
                        method: log.method,
                        status: log.status
                    }));
                    state.historyLoaded = true;
                    renderHistoryTable();
                    renderFullHistoryTable(state.history);
                } else {
                    if (tbody) tbody.innerHTML = `<tr><td colspan="8" style="text-align:center;padding:2rem;color:var(--danger-color)">Failed to load history.</td></tr>`;
                }
            })
            .catch(() => {
                if (tbody) tbody.innerHTML = `<tr><td colspan="8" style="text-align:center;padding:2rem;color:var(--danger-color)">Error loading history.</td></tr>`;
            });
        }


        function renderHistoryTable() {
            const tbody = document.getElementById('historyTableBody');
            if (!tbody) return;
            
            const history = state.history.slice(0, 5); 
            tbody.innerHTML = '';

            if (history.length === 0) {
                tbody.innerHTML = `<tr><td colspan="5" style="text-align:center;padding:2rem;color:var(--text-secondary)">No recent activity</td></tr>`;
                return;
            }

            history.forEach(log => {
                const row = document.createElement('tr');
                row.innerHTML = `
                    <td>${log.date}</td>
                    <td>Parking at ${log.slot}</td>
                    <td style="font-weight: 700;">${log.amount}</td>
                    <td>${log.method || 'Cash'}</td>
                    <td><span class="parking-status" style="background:rgba(16,185,129,.1);color:var(--success-color)">${log.status}</span></td>
                `;
                tbody.appendChild(row);
            });
        }

        function renderFullHistoryTable(history) {
            const fullTbody = document.getElementById('fullHistoryTableBody');
            const paymentTbody = document.getElementById('historyTableBody');
            
            if (fullTbody) fullTbody.innerHTML = '';
            if (paymentTbody) paymentTbody.innerHTML = '';

            if (!history || history.length === 0) {
                const noDataHtml = `<tr><td colspan="9" style="text-align:center;padding:3rem;color:var(--text-secondary)"><i class="fas fa-history" style="font-size:2rem;margin-bottom:1rem;display:block"></i><p>No parking history found</p></td></tr>`;
                if (fullTbody) fullTbody.innerHTML = noDataHtml;
                if (paymentTbody) paymentTbody.innerHTML = `<tr><td colspan="5" style="text-align:center;padding:3rem;color:var(--text-secondary)"><p>No payment records yet</p></td></tr>`;
                return;
            }

            history.forEach(log => {
                if (fullTbody) {
                    const row = document.createElement('tr');
                    row.innerHTML = `
                        <td>${log.date}</td>
                        <td>${log.slot}</td>
                        <td>${log.vehicle}</td>
                        <td>${log.entry_time || 'N/A'}</td>
                        <td>${log.exit_time || 'N/A'}</td>
                        <td>${log.duration}</td>
                        <td>${log.amount}</td>
                        <td>${log.method || 'Cash'}</td>
                        <td><span class="parking-status" style="background:rgba(16,185,129,.1);color:var(--success-color)">${log.status}</span></td>
                    `;
                    fullTbody.appendChild(row);
                }

                if (paymentTbody) {
                    const payRow = document.createElement('tr');
                    payRow.innerHTML = `
                        <td>${log.date}</td>
                        <td>Parking Fee (${log.vehicle} - ${log.slot})</td>
                        <td style="color: var(--primary-color); font-weight: 600;">${log.amount}</td>
                        <td>${log.method || 'Cash'}</td>
                        <td><span class="parking-status" style="background:rgba(16,185,129,.1);color:var(--success-color)">${log.status}</span></td>
                    `;
                    paymentTbody.appendChild(payRow);
                }
            });
        }

        function startTimer() {
            const timerSec = document.getElementById('timerSection');
            const noSessionMsg = document.getElementById('noActiveSessionMessage');

            if (!state.currentSession) {
                if (timerSec) timerSec.style.display = 'none';
                if (noSessionMsg) noSessionMsg.style.display = 'block';
                return;
            }
            
            if (timerSec) timerSec.style.display = 'block';
            if (noSessionMsg) noSessionMsg.style.display = 'none';
            
            document.getElementById('timerSlot').textContent = state.currentSession.slot_number || (state.currentSession.parking_slot ? state.currentSession.parking_slot.slot_number : '-');
            document.getElementById('timerPlate').textContent = state.currentSession.license_plate || (state.currentSession.vehicle ? state.currentSession.vehicle.license_plate : '-');
            
            const hourlyRate = parseFloat(state.currentSession.hourly_rate);
            if (isNaN(hourlyRate)) {
                const slot = state.parkingSlots.find(s => s.id == state.currentSession.slot_id);
                document.getElementById('timerRate').textContent = `₱${parseFloat(slot?.hourly_rate || 0).toFixed(2)}`;
            } else {
                document.getElementById('timerRate').textContent = `₱${hourlyRate.toFixed(2)}`;
            }

            if (state.timer) clearInterval(state.timer);
            
            const exitTime = new Date(state.currentSession.exit_time);
            
            state.timer = setInterval(() => {
                const now = new Date();
                const diff = exitTime - now;
                
                if (diff <= 0) {
                    clearInterval(state.timer);
                    const timerDisplay = document.getElementById('timerDisplay');
                    if (timerDisplay) timerDisplay.textContent = '00:00:00';
                    showNotification('Your parking session has expired!', 'warning');
                    return;
                }
                
                const hours = Math.floor(diff / (1000 * 60 * 60));
                const minutes = Math.floor((diff % (1000 * 60 * 60)) / (1000 * 60));
                const seconds = Math.floor((diff % (1000 * 60)) / 1000);
                
                const timerDisplay = document.getElementById('timerDisplay');
                if (timerDisplay) {
                    timerDisplay.textContent = 
                        `${String(hours).padStart(2, '0')}:${String(minutes).padStart(2, '0')}:${String(seconds).padStart(2, '0')}`;
                }
                
                const entryTime = new Date(state.currentSession.entry_time);
                const elapsedMs = Math.max(0, now - entryTime);
                const elapsedMinutes = Math.floor(elapsedMs / 60000);
                const resolvedRate = isNaN(hourlyRate) ? parseFloat(state.parkingSlots.find(s => s.id == state.currentSession.slot_id)?.hourly_rate || 0) : hourlyRate;
                const currentFee = elapsedMinutes * (resolvedRate / 60);
                const timerFee = document.getElementById('timerFee');
                if (timerFee) timerFee.textContent = `₱${parseFloat(currentFee || 0).toFixed(2)}`;
            }, 1000);
        }

        function updateOverviewCards() {
            const statusBadge = document.getElementById('currentParkingStatus');
            const valueDisplay = document.getElementById('currentParkingValue');
            const labelDisplay = document.getElementById('currentParkingLabel');

            if (statusBadge && valueDisplay && labelDisplay) {
                if (state.currentUser.status === 'parked') {
                    statusBadge.className = 'parking-status status-parked';
                    statusBadge.innerHTML = '<i class="fas fa-circle"></i> Parked';
                    valueDisplay.textContent = state.currentUser.currentParking || '-';
                    labelDisplay.textContent = `Slot ${state.currentUser.currentParking || '-'} • Active`;
                } else {
                    statusBadge.className = 'parking-status status-available';
                    statusBadge.innerHTML = '<i class="fas fa-circle"></i> Available';
                    valueDisplay.textContent = 'No Active';
                    labelDisplay.textContent = 'Not Currently Parked';
                }
            }

            fetch('/user/slots', {
                headers: { 'Accept': 'application/json', 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content') }
            })
            .then(r => r.json())
            .then(data => {
                if (data.success) {
                    state.parkingSlots = data.slots;
                    if (document.getElementById('parkingGrid')) {
                        generateParkingGrid();
                    }
                }
            })
            .catch(err => console.warn('Refresh failed:', err));
        }

        function setupEventListeners() {
            document.querySelectorAll('.nav-item').forEach(item => {
                item.addEventListener('click', function(e) {
                    const page = this.dataset.page;
                    if (page) {
                        e.preventDefault();
                        showPage(page);
                    }
                });
            });

            document.getElementById('bookingForm').addEventListener('submit', function(e) {
                e.preventDefault();

                const slotInput = document.getElementById('selectedSlotInput').value;
                const durationSelect = document.getElementById('durationSelect');
                const durationVal = parseInt(durationSelect.value);
                const durationText = durationSelect.options[durationSelect.selectedIndex].text;
                const estimatedCost = document.getElementById('estimatedCost').value;

                document.getElementById('paymentSlot').textContent = slotInput || 'N/A';
                document.getElementById('paymentDuration').textContent = durationText || '-';
                document.getElementById('paymentTotal').textContent = estimatedCost || '₱0.00';

                openModal('paymentModal');
            });

            document.getElementById('paymentForm').addEventListener('submit', function(e) {
                e.preventDefault();
                processPayment();
            });

            document.getElementById('durationSelect').addEventListener('change', function() {
                updateEstimatedCost();
            });



            const fullHistorySearch = document.getElementById('fullHistorySearch');
            if (fullHistorySearch) {
                fullHistorySearch.addEventListener('input', function(e) {
                    searchFullHistory(e.target.value);
                });
            }

            const fullHistoryFilter = document.getElementById('fullHistoryFilter');
            if (fullHistoryFilter) {
                fullHistoryFilter.addEventListener('change', function() {
                    filterFullHistory(this.value);
                });
            }

            document.getElementById('addVehicleForm').addEventListener('submit', function(e) {
                e.preventDefault();
                submitAddVehicle();
            });

            const paymentType = document.getElementById('paymentType');
            if (paymentType) {
                paymentType.addEventListener('change', function() {
                    togglePaymentFields(this.value, 'cardPaymentFields', 'ewalletFields', 'bankFields');
                });
            }

            document.getElementById('addPaymentForm').addEventListener('submit', function(e) {
                e.preventDefault();
                submitAddPayment();
            });

            const firstPaymentType = document.getElementById('firstPaymentType');
            if (firstPaymentType) {
                firstPaymentType.addEventListener('change', function() {
                    togglePaymentFields(this.value, 'firstCardFields', 'firstEwalletFields', null);
                });
            }

            document.getElementById('firstPaymentForm').addEventListener('submit', function(e) {
                e.preventDefault();
                submitFirstPayment();
            });

            const changePasswordForm = document.getElementById('changePasswordForm');
            if (changePasswordForm) {
                changePasswordForm.addEventListener('submit', function(e) {
                    e.preventDefault();
                    submitChangePassword();
                });
            }

            const editProfileForm = document.getElementById('editProfileForm');
            if (editProfileForm) {
                editProfileForm.addEventListener('submit', function(e) {
                    e.preventDefault();
                    submitEditProfile();
                });
            }

            const extendSessionForm = document.getElementById('extendSessionForm');
            if (extendSessionForm) {
                extendSessionForm.addEventListener('submit', function(e) {
                    e.preventDefault();
                    const hours = document.getElementById('extendHours').value;
                    showNotification('Extending session...', 'info');
                    const formData = new FormData();
                    formData.append('session_id', state.currentSession.id);
                    formData.append('additional_hours', hours);
                    formData.append('_token', document.querySelector('meta[name="csrf-token"]').getAttribute('content'));
                    fetch('/user/extend', {
                        method: 'POST',
                        body: formData,
                        headers: {
                            'Accept': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                        }
                    })
                    .then(r => r.json())
                    .then(data => {
                        if (data.success) {
                            showNotification(data.message, 'success');
                            closeModal('extendSessionModal');
                            const oldExitTime = new Date(state.currentSession.exit_time);
                            state.currentSession.exit_time = new Date(oldExitTime.getTime() + (parseInt(hours) * 60 * 60 * 1000));
                            startTimer();
                        } else {
                            showNotification(data.message, 'error');
                        }
                    })
                    .catch(() => showNotification('Failed to extend session.', 'error'));
                });
            }
        }

        function submitEditProfile() {
            const form = document.getElementById('editProfileForm');
            const formData = new FormData(form);
            formData.append('_method', 'PATCH');
            const name = document.getElementById('edit-name').value;
            const email = document.getElementById('edit-email').value;
            
            showNotification('Updating profile...', 'info');
            
            fetch('{{ route('profile.update') }}', {
                method: 'POST',
                body: formData,
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'Accept': 'application/json'
                }
            })
            .then(async response => {
                if (response.ok || response.status === 302) {
                    showNotification('Profile updated successfully!', 'success');
                    closeModal('editProfileModal');
                    
                    document.querySelectorAll('.user-name').forEach(el => el.textContent = name);
                    document.getElementById('display-user-name').value = name;
                    document.getElementById('display-user-email').value = email;
                    document.querySelectorAll('.user-avatar').forEach(el => el.textContent = name.substring(0, 2));
                    
                    state.currentUser.name = name;
                    state.currentUser.email = email;
                } else if (response.status === 422) {
                    const data = await response.json();
                    const errors = data.errors || {};
                    const firstError = Object.values(errors)[0][0];
                    showNotification(firstError, 'error');
                } else {
                    showNotification('Failed to update profile. Please try again.', 'error');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                showNotification('An error occurred. Please try again.', 'error');
            });
        }

        function submitChangePassword() {
            const form = document.getElementById('changePasswordForm');
            const formData = new FormData(form);
            formData.append('_method', 'PUT');
            
            showNotification('Updating password...', 'info');
            
            fetch('{{ route('password.update') }}', {
                method: 'POST',
                body: formData,
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'Accept': 'application/json'
                }
            })
            .then(async response => {
                if (response.ok || response.status === 302) {
                    showNotification('Password updated successfully!', 'success');
                    closeModal('changePasswordModal');
                    form.reset();
                } else if (response.status === 422) {
                    const data = await response.json();
                    const errors = data.errors || {};
                    const firstError = Object.values(errors)[0][0];
                    showNotification(firstError, 'error');
                } else {
                    showNotification('Failed to update password. Please check your current password.', 'error');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                showNotification('An error occurred. Please try again.', 'error');
            });
        }

        function searchFullHistory(query) {
            fetchFullHistory({ search: query, filter: document.getElementById('fullHistoryFilter')?.value || '' });
        }

        function filterFullHistory(filter) {
            fetchFullHistory({ filter, search: document.getElementById('fullHistorySearch')?.value || '' });
        }

        function updateEstimatedCost() {
            const duration = parseInt(document.getElementById('durationSelect').value);
            const vehicleId = document.getElementById('vehicleSelect').value;
            
            if (!duration || !vehicleId) {
                document.getElementById('estimatedCost').value = `₱0.00`;
                return;
            }
            
            const vehicle = userVehiclesData[vehicleId];
            if (!vehicle) {
                console.warn('Vehicle data not found for ID:', vehicleId);
            }
            
            const vehicleType = String(vehicle?.type || 'car').toLowerCase();
            
            let rate = parseFloat(parkingRatesData[vehicleType]);
            if (isNaN(rate)) {
                const slotId = state.selectedSlot?.id;
                const slot = state.parkingSlots.find(s => s.id == slotId);
                rate = parseFloat(slot?.hourly_rate || 0.00);
            }
            
            if (rate <= 0) {
                console.warn('Rate is 0 or not found for vehicle/slot. Checking global rates...');
                const firstRate = Object.values(parkingRatesData)[0];
                if (firstRate) rate = parseFloat(firstRate);
            }
            
            const cost = (duration * rate).toFixed(2);
            document.getElementById('estimatedCost').value = `₱${cost}`;
        }

        document.getElementById('vehicleSelect').addEventListener('change', updateEstimatedCost);

        function selectPaymentMethod(method) {
            state.selectedPaymentMethod = method;
            state.selectedSavedMethodId = null;

            document.querySelectorAll('.payment-method').forEach(el => el.classList.remove('selected'));
            const el = document.querySelector(`[data-method="${method}"]`);
            if (el) el.classList.add('selected');

            document.getElementById('cardDetails').style.display = method === 'card' ? 'block' : 'none';

            updateSavedMethodsInBooking();
        }

        function processPayment() {
            if (!state.selectedPaymentMethod) {
                showNotification('Please select a payment method', 'error');
                return;
            }

            if (!state.selectedSlot || !state.selectedSlot.id) {
                showNotification('Please select a parking slot', 'error');
                return;
            }

            showNotification('Processing payment...', 'info');
            
            const formData = new FormData();
            formData.append('slot_id', state.selectedSlot.id);
            formData.append('vehicle_id', document.getElementById('vehicleSelect').value);
            formData.append('duration_hours', document.getElementById('durationSelect').value);
            formData.append('payment_method', state.selectedPaymentMethod);
            formData.append('_token', document.querySelector('meta[name="csrf-token"]').getAttribute('content'));

            fetch('/user/book', {
                method: 'POST',
                body: formData,
                headers: {
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    showNotification(data.message, 'success');
                    closeModal('paymentModal');
                    closeModal('bookingModal');
                    
                    state.currentSession = data.parking_session;
                    state.currentUser.status = 'parked';
                    state.currentUser.currentParking = data.parking_session.slot_number;

                    updateOverviewCards();
                    startTimer();

                    try {
                        const now = new Date();
                        const durationSelect = document.getElementById('durationSelect');
                        const durationText = (durationSelect && durationSelect.selectedIndex >= 0) 
                            ? durationSelect.options[durationSelect.selectedIndex].text 
                            : '1 Hour';
                        
                        const estimatedCostEl = document.getElementById('estimatedCost');
                        const estimatedCost = estimatedCostEl ? estimatedCostEl.value : '₱0.00';
                        
                        const vehicleSelect = document.getElementById('vehicleSelect');
                        let plateNumber = 'N/A';
                        let makeModel = 'N/A';
                        let vehicleType = 'Car';
                        
                        if (vehicleSelect && vehicleSelect.selectedIndex >= 0) {
                            const selectedOption = vehicleSelect.options[vehicleSelect.selectedIndex];
                            const fullVehicleText = selectedOption.text;
                            if (fullVehicleText.includes(' - ')) {
                                plateNumber = fullVehicleText.split(' - ')[0].trim();
                                makeModel = fullVehicleText.split(' - ')[1]?.trim() || 'N/A';
                            } else {
                                plateNumber = fullVehicleText;
                            }
                            vehicleType = userVehiclesData[vehicleSelect.value]?.type || 'Car';
                        }

                        const durationHours = (durationSelect && durationSelect.selectedIndex >= 0) 
                            ? parseInt(durationSelect.value) 
                            : 1;
                        const exitTime = new Date(now.getTime() + durationHours * 60 * 60 * 1000);
                        const trxId = `#TRX-${(data.parking_session?.id || 0).toString().padStart(5, '0')}`;

                        document.getElementById('ticketDate').textContent = now.toLocaleDateString('en-US', { year: 'numeric', month: 'long', day: 'numeric' });
                        document.getElementById('ticketName').textContent = state.currentUser.name || 'Guest User';
                        document.getElementById('ticketId').textContent = trxId;
                        document.getElementById('ticketSlot').textContent = data.parking_session?.slot_number || 'N/A';
                        document.getElementById('ticketPlate').textContent = plateNumber;
                        document.getElementById('ticketVehicleType').textContent = vehicleType;
                        document.getElementById('ticketEntry').textContent = now.toLocaleTimeString([], {hour: '2-digit', minute:'2-digit', hour12: true});
                        document.getElementById('ticketExit').textContent = exitTime.toLocaleTimeString([], {hour: '2-digit', minute:'2-digit', hour12: true});
                        document.getElementById('ticketDuration').textContent = durationText;
                        document.getElementById('ticketTotal').textContent = estimatedCost;
                        document.getElementById('ticketMethod').textContent = state.selectedPaymentMethod === 'wallet' ? 'E-WALLET' : (state.selectedPaymentMethod || 'CASH').toUpperCase();
                        
                        const qrImg = document.getElementById('ticketQR');
                        if (qrImg) {
                            qrImg.src = `https://api.qrserver.com/v1/create-qr-code/?size=150x150&data=PARKMASTER-VERIFY-${trxId}`;
                        }

                        openModal('ticketModal');
                        closeModal('paymentModal');
                        closeModal('bookingModal');
                    } catch (err) {
                        console.error('Error generating ticket:', err);
                        showNotification('Payment successful, but failed to display receipt.', 'warning');
                        closeModal('paymentModal');
                        closeModal('bookingModal');
                    }
                } else {
                    showNotification(data.message, 'error');
                }
            })
            .catch(error => {
                console.error('Payment Error:', error);
                showNotification('Payment failed. Please try again.', 'error');
            });
        }


        function viewReceipt(logId) {
            const log = state.history.find(h => h.id == logId);
            if (!log) return;
            
            document.getElementById('ticketDate').textContent = log.date;
            document.getElementById('ticketName').textContent = state.currentUser.name;
            document.getElementById('ticketId').textContent = `#TRX-${(log.id || 0).toString().padStart(5, '0')}`;
            document.getElementById('ticketSlot').textContent = log.slot;
            document.getElementById('ticketPlate').textContent = log.vehicle;
            document.getElementById('ticketVehicleType').textContent = log.vehicle_type || 'Car';
            document.getElementById('ticketEntry').textContent = log.entry_time;
            document.getElementById('ticketExit').textContent = log.exit_time || 'N/A';
            document.getElementById('ticketDuration').textContent = log.duration;
            document.getElementById('ticketTotal').textContent = log.amount;
            document.getElementById('ticketMethod').textContent = (log.method || 'CASH').toUpperCase();
            document.getElementById('ticketChange').textContent = '₱0.00';
            
            const qrImg = document.getElementById('ticketQR');
            if (qrImg) {
                qrImg.src = `https://api.qrserver.com/v1/create-qr-code/?size=150x150&data=PARKMASTER-VERIFY-#TRX-${log.id}`;
            }
            
            openModal('ticketModal');
        }

        function openExtendModal() {
            if (!state.currentSession) return;
            
            const vehicleType = (state.currentSession.vehicle?.type || state.currentSession.vehicle_type || 'car').toLowerCase();
            
            let rate = parseFloat(parkingRatesData[vehicleType]);
            if (isNaN(rate)) {
                const slot = state.parkingSlots.find(s => s.id == state.currentSession.slot_id);
                rate = parseFloat(slot?.hourly_rate || 50.00);
            }
            
            document.getElementById('extendHours').value = "1";
            document.getElementById('extendFee').value = `₱${parseFloat(rate).toFixed(2)}`;
            
            document.getElementById('extendHours').onchange = (e) => {
                document.getElementById('extendFee').value = `₱${(parseFloat(e.target.value) * rate).toFixed(2)}`;
            };
            
            openModal('extendSessionModal');
        }


        function confirmEndSession() {
            if (!state.currentSession) return;
            
            if (confirm('Are you sure you want to end your parking session? Final fee will be calculated based on actual duration.')) {
                showNotification('Ending session...', 'info');
                
                const formData = new FormData();
                formData.append('session_id', state.currentSession.id);
                formData.append('_token', document.querySelector('meta[name="csrf-token"]').getAttribute('content'));
                
                fetch('/user/end', {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    }
                })
                .then(r => r.json())
                .then(data => {
                    if (data.success) {
                        showNotification(data.message, 'success');
                        clearInterval(state.timer);
                        state.currentSession = null;
                        state.currentUser.status = 'available';
                        state.currentUser.currentParking = null;
                        
                        startTimer();
                        updateOverviewCards();
                        loadHistoryData();
                    } else {
                        showNotification(data.message, 'error');
                    }
                });
            }
        }

        function printReceipt() {
            const originalTitle = document.title;
            document.title = "ParkMaster_Receipt";
            window.print();
            setTimeout(() => { document.title = originalTitle; }, 1000);
        }

        function refreshParkingGrid() {
            showNotification('Refreshing parking status...', 'info');
            
            fetch('/user/slots', {
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    state.parkingSlots = data.slots;
                    generateParkingGrid();
                    updateParkingStats();
                    showNotification('Parking status updated!', 'success');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                showNotification('Failed to refresh parking status', 'error');
            });
        }

        function extendParking() {
            showNotification('Extending parking time...', 'info');
            
            setTimeout(() => {
                showNotification('Parking time extended by 2 hours!', 'success');
            }, 1500);
        }

        function endParking() {
            if (confirm('Are you sure you want to end your parking session?')) {
                showNotification('Ending parking session...', 'info');
                
                setTimeout(() => {
                    clearInterval(state.timer);
                    state.currentUser.status = 'available';
                    state.currentUser.currentParking = null;
                    updateOverviewCards();
                    
                    showNotification('Parking session ended. Thank you!', 'success');
                    
                    loadHistoryData();
                }, 1500);
            }
        }




        function showNotifications() {
            showNotification('You have 3 new notifications', 'info');
        }


        function exportFullHistory() {
            const filter = document.getElementById('fullHistoryFilter').value || '';
            const search = document.getElementById('fullHistorySearch').value || '';
            window.location.href = `/user/history/export?filter=${filter}&search=${encodeURIComponent(search)}`;
            showNotification('Exporting full history to CSV...', 'info');
        }

        function showPage(pageName) {
            document.querySelectorAll('.page-section').forEach(page => {
                page.classList.add('hidden');
            });
            
            const targetPage = document.getElementById(`page-${pageName}`);
            if (targetPage) {
                targetPage.classList.remove('hidden');
            }
            
            document.querySelectorAll('.nav-item').forEach(nav => {
                nav.classList.remove('active');
                if (nav.dataset.page === pageName) {
                    nav.classList.add('active');
                }
            });
            
            if (window.innerWidth <= 1024) {
                document.getElementById('sidebar').classList.remove('active');
            }
            
            if (pageName === 'history') {
                fetchFullHistory();
                document.getElementById('fullHistorySearch').focus();
            }
        }

        function extendSession(sessionId) {
            showNotification('Extending parking session...', 'info');
            
            const formData = new FormData();
            formData.append('session_id', sessionId);
            formData.append('additional_hours', 2);
            formData.append('_token', document.querySelector('meta[name="csrf-token"]').getAttribute('content'));
            
            fetch('/user/extend', {
                method: 'POST',
                body: formData,
                headers: {
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    showNotification(data.message, 'success');
                    setTimeout(() => location.reload(), 1000);
                } else {
                    showNotification(data.message, 'error');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                showNotification('Failed to extend session', 'error');
            });
        }

        function endSession(sessionId) {
            if (!confirm('Are you sure you want to end this parking session?')) {
                return;
            }

            showNotification('Ending parking session...', 'info');

            const formData = new FormData();
            formData.append('session_id', sessionId);
            formData.append('_token', document.querySelector('meta[name="csrf-token"]').getAttribute('content'));

            fetch('/user/end', {
                method: 'POST',
                body: formData,
                headers: {
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    showNotification(`Session ended! Final fee: ₱${data.final_fee}`, 'success');
                    setTimeout(() => location.reload(), 1500);
                } else {
                    showNotification(data.message, 'error');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                showNotification('Failed to end session', 'error');
            });
        }

        function submitAddVehicle() {
            const submitBtn = document.querySelector('#addVehicleForm button[type="submit"]');
            const originalBtnContent = submitBtn.innerHTML;
            
            const vehicleType = document.getElementById('vehicleType').value;
            const licensePlate = document.getElementById('licensePlate').value;
            const make = document.getElementById('vehicleMake').value;
            const model = document.getElementById('vehicleModel').value;
            const color = document.getElementById('vehicleColor').value;

            if (!vehicleType || !licensePlate) {
                showNotification('Please fill in all required fields', 'error');
                return;
            }

            submitBtn.disabled = true;
            submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Adding...';
            showNotification('Adding vehicle...', 'info');

            const formData = new FormData();
            formData.append('type', vehicleType);
            formData.append('license_plate', licensePlate);
            formData.append('make', make);
            formData.append('model', model);
            formData.append('color', color);
            formData.append('_token', document.querySelector('meta[name="csrf-token"]').getAttribute('content'));

            fetch('/user/vehicles', {
                method: 'POST',
                body: formData,
                headers: {
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                }
            })
            .then(response => response.json())
            .then(data => {
                submitBtn.disabled = false;
                submitBtn.innerHTML = originalBtnContent;

                if (data.success) {
                    showNotification('Vehicle added successfully!', 'success');
                    closeModal('addVehicleModal');
                    document.getElementById('addVehicleForm').reset();
                    addVehicleToUI(data.vehicle);
                } else {
                    showNotification(data.message || 'Failed to add vehicle', 'error');
                }
            })
            .catch(error => {
                submitBtn.disabled = false;
                submitBtn.innerHTML = originalBtnContent;
                console.error('Error:', error);
                showNotification('Failed to add vehicle. Please try again.', 'error');
            });
        }

        function addVehicleToUI(vehicle) {
            userVehiclesData[vehicle.id] = { id: vehicle.id, type: vehicle.type };
            
            const vehicleSelect = document.getElementById('vehicleSelect');
            const option = document.createElement('option');
            option.value = vehicle.id;
            option.textContent = `${vehicle.license_plate} - ${vehicle.make || ''} ${vehicle.model || ''}`;
            vehicleSelect.appendChild(option);

            const vehiclesGrid = document.getElementById('vehiclesGrid');
            if (vehiclesGrid) {
                const noVehiclesMessage = document.getElementById('noVehiclesMessage');
                if (noVehiclesMessage) {
                    noVehiclesMessage.remove();
                }

                const vehicleCard = document.createElement('div');
                vehicleCard.className = 'overview-card';
                vehicleCard.style.animation = 'fadeInUp 0.5s ease-out forwards';
                vehicleCard.style.padding = '0.75rem';
                vehicleCard.style.display = 'flex';
                vehicleCard.style.justifyContent = 'space-between';
                vehicleCard.style.alignItems = 'center';
                vehicleCard.innerHTML = `
                    <div>
                        <div class="overview-header" style="margin-bottom: 0.25rem;">
                            <div>
                                <div class="overview-title" style="font-size: 0.8rem;">${vehicle.license_plate}</div>
                                <div class="parking-status" style="background: rgba(16, 185, 129, 0.1); color: var(--success-color); font-size: 0.7rem; padding: 0.15rem 0.4rem;">
                                    <i class="fas fa-check-circle"></i>
                                    Active
                                </div>
                            </div>
                        </div>
                        <div class="overview-value" style="font-size: 1.125rem; margin-bottom: 0.15rem;">${vehicle.make || ''} ${vehicle.model || ''}</div>
                        <div class="overview-label" style="font-size: 0.8rem;">Color: ${vehicle.color || 'N/A'}</div>
                    </div>
                    <div style="text-align: right; margin-right: 0.5rem;">
                        <span style="font-size: 0.75rem; color: var(--text-secondary); text-transform: uppercase; font-weight: 600; letter-spacing: 0.05em;">Type</span>
                        <div style="font-size: 1.125rem; font-weight: 700; color: var(--text-primary); text-transform: capitalize;">${vehicle.type || 'Car'}</div>
                    </div>
                `;
                vehiclesGrid.prepend(vehicleCard);
            }

            const overviewCount = document.getElementById('overview-vehicle-count');
            if (overviewCount) {
                overviewCount.textContent = parseInt(overviewCount.textContent) + 1;
            }

            if (vehicleSelect.options.length === 2) { 
                showNotification('You can now book parking slots with your new vehicle!', 'success');
            }
        }

        function togglePaymentFields(type, cardFieldsId, ewalletFieldsId, bankFieldsId) {
            if (cardFieldsId) document.getElementById(cardFieldsId).style.display = 'none';
            if (ewalletFieldsId) document.getElementById(ewalletFieldsId).style.display = 'none';
            if (bankFieldsId) document.getElementById(bankFieldsId).style.display = 'none';

            switch(type) {
                case 'card':
                    if (cardFieldsId) document.getElementById(cardFieldsId).style.display = 'block';
                    break;
                case 'ewallet':
                    if (ewalletFieldsId) document.getElementById(ewalletFieldsId).style.display = 'block';
                    break;
                case 'bank':
                    if (bankFieldsId) document.getElementById(bankFieldsId).style.display = 'block';
                    break;
            }
        }

        function submitAddPayment() {
            const paymentType = document.getElementById('paymentType').value;

            if (!paymentType) {
                showNotification('Please select a payment type', 'error');
                return;
            }

            let isValid = true;
            let paymentData = { type: paymentType };

            if (paymentType === 'card') {
                const cardNumber = document.getElementById('cardNumber').value;
                const cardHolder = document.getElementById('cardHolder').value;
                const expiryDate = document.getElementById('expiryDate').value;
                const cvv = document.getElementById('cvv').value;

                if (!cardNumber || !cardHolder || !expiryDate || !cvv) {
                    isValid = false;
                    showNotification('Please fill in all card details', 'error');
                }
                let cardProvider = 'Credit Card';
                if (cardNumber.startsWith('4')) cardProvider = 'Visa';
                else if (cardNumber.startsWith('5')) cardProvider = 'Mastercard';
                
                paymentData = { ...paymentData, card_number: cardNumber, cardHolder, expiryDate, cvv, card_provider: cardProvider };
            } else if (paymentType === 'ewallet') {
                const provider = document.getElementById('ewalletProvider').value;
                const account = document.getElementById('ewalletAccount').value;

                if (!provider || !account) {
                    isValid = false;
                    showNotification('Please fill in all e-wallet details', 'error');
                }
                paymentData = { ...paymentData, provider, account };
            } else if (paymentType === 'bank') {
                const bankName = document.getElementById('bankName').value;
                const bankAccount = document.getElementById('bankAccount').value;

                if (!bankName || !bankAccount) {
                    isValid = false;
                    showNotification('Please fill in all bank details', 'error');
                }
                paymentData = { ...paymentData, provider: bankName, account: bankAccount, bankName, bankAccount };
            }

            if (!isValid) return;

            showNotification('Adding payment method...', 'info');

            const formData = new FormData();
            formData.append('payment_type', paymentType);
            formData.append('payment_data', JSON.stringify(paymentData));
            formData.append('_token', document.querySelector('meta[name="csrf-token"]').getAttribute('content'));

            fetch('/user/payments', {
                method: 'POST',
                body: formData,
                headers: {
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    showNotification('Payment method added successfully!', 'success');
                    closeModal('addPaymentModal');
                    
                    state.paymentMethods.unshift(data.payment_method);
                    addPaymentMethodToUI(data.payment_method);
                    updateSavedMethodsInBooking();
                    
                    document.getElementById('addPaymentForm').reset();
                    document.getElementById('cardPaymentFields').style.display = 'none';
                    document.getElementById('ewalletFields').style.display = 'none';
                    document.getElementById('bankFields').style.display = 'none';
                } else {
                    showNotification(data.message || 'Failed to add payment method', 'error');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                showNotification('Failed to add payment method. Please try again.', 'error');
            });
        }

        function submitFirstPayment() {
            const paymentType = document.getElementById('firstPaymentType').value;

            if (!paymentType) {
                showNotification('Please select a payment type', 'error');
                return;
            }

            let isValid = true;
            let paymentData = { type: paymentType };

            if (paymentType === 'card') {
                const cardNumber = document.getElementById('firstCardNumber').value;
                const cardHolder = document.getElementById('firstCardHolder').value;
                const expiryDate = document.getElementById('firstExpiryDate').value;
                const cvv = document.getElementById('firstCvv').value;

                if (!cardNumber || !cardHolder || !expiryDate || !cvv) {
                    isValid = false;
                    showNotification('Please fill in all card details', 'error');
                }
                paymentData = { ...paymentData, cardNumber, cardHolder, expiryDate, cvv };
            } else if (paymentType === 'ewallet') {
                const provider = document.getElementById('firstEwalletProvider').value;
                const account = document.getElementById('firstEwalletAccount').value;

                if (!provider || !account) {
                    isValid = false;
                    showNotification('Please fill in all e-wallet details', 'error');
                }
                paymentData = { ...paymentData, provider, account };
            }

            if (!isValid) return;

            showNotification('Setting up your payment method...', 'info');

            const formData = new FormData();
            formData.append('payment_type', paymentType);
            formData.append('payment_data', JSON.stringify(paymentData));
            formData.append('is_primary', true);
            formData.append('_token', document.querySelector('meta[name="csrf-token"]').getAttribute('content'));

            fetch('/user/payments', {
                method: 'POST',
                body: formData,
                headers: {
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    showNotification('Payment method added successfully! You can now book parking slots.', 'success');
                    closeModal('firstPaymentSetupModal');
                    setTimeout(() => location.reload(), 1500);
                } else {
                    showNotification(data.message || 'Failed to add payment method', 'error');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                showNotification('Failed to add payment method. Please try again.', 'error');
            });
        }

        function checkFirstTimeUser() {
            const hasVehicles = {{ $userVehicles->count() > 0 ? 'true' : 'false' }};

            if (!hasVehicles) {
                setTimeout(() => {
                    openModal('firstPaymentSetupModal');
                }, 1000);
            }
        }

        function handleSearch(query) {
            const activePage = document.querySelector('.page-section:not(.hidden)')?.id;
            
            if (activePage === 'page-dashboard') {
                const slots = document.querySelectorAll('.parking-slot');
                slots.forEach(slot => {
                    const slotNumber = slot.dataset.slot.toLowerCase();
                    const slotType = slot.querySelector('.slot-type').textContent.toLowerCase();
                    if (slotNumber.includes(query.toLowerCase()) || slotType.includes(query.toLowerCase())) {
                        slot.style.display = 'flex';
                    } else {
                        slot.style.display = 'none';
                    }
                });
            } else if (activePage === 'page-history') {
                const historySearchInput = document.getElementById('fullHistorySearch');
                if (historySearchInput) {
                    historySearchInput.value = query;
                    searchFullHistory(query);
                }
            } else if (activePage === 'page-notifications') {
                const notifications = document.querySelectorAll('#page-notifications .overview-card');
                notifications.forEach(notif => {
                    const text = notif.textContent.toLowerCase();
                    notif.style.display = text.includes(query.toLowerCase()) ? 'flex' : 'none';
                });
            }
        }


        function calculateDuration(entry, exit) {
            const diff = new Date(exit) - new Date(entry);
            const hours = Math.floor(diff / (1000 * 60 * 60));
            const minutes = Math.floor((diff % (1000 * 60 * 60)) / (1000 * 60));
            return `${hours}h ${minutes}m`;
        }

        function toggleSidebar() {
            const sidebar = document.getElementById('sidebar');
            sidebar.classList.toggle('collapsed');
            sidebar.classList.toggle('active');
        }

        function toggleNotifications(event) {
            event.stopPropagation();
            const dropdown = document.getElementById('notificationDropdown');
            dropdown.classList.toggle('active');
            
            const closeDropdown = (e) => {
                if (!dropdown.contains(e.target)) {
                    dropdown.classList.remove('active');
                    document.removeEventListener('click', closeDropdown);
                }
            };
            
            if (dropdown.classList.contains('active')) {
                document.addEventListener('click', closeDropdown);
            }
        }

        function toggleTheme() {
            const currentTheme = document.documentElement.getAttribute('data-theme');
            const newTheme = currentTheme === 'dark' ? 'light' : 'dark';
            
            document.documentElement.setAttribute('data-theme', newTheme);
            localStorage.setItem('theme', newTheme);
            updateThemeIcon();
        }

        function updateThemeIcon() {
            const icon = document.getElementById('themeIcon');
            const isDark = document.documentElement.getAttribute('data-theme') === 'dark';
            icon.className = isDark ? 'fas fa-sun' : 'fas fa-moon';
        }

        function openModal(modalId) {
            document.getElementById(modalId).classList.add('active');
        }

        function closeModal(modalId) {
            document.getElementById(modalId).classList.remove('active');
        }

        function showNotification(message, type = 'info') {
            const container = document.getElementById('notificationContainer');
            const notification = document.createElement('div');
            notification.className = `notification ${type}`;
            
            const icons = {
                success: 'fas fa-check-circle',
                error: 'fas fa-exclamation-circle',
                warning: 'fas fa-exclamation-triangle',
                info: 'fas fa-info-circle'
            };
            
            notification.innerHTML = `
                <div class="notification-header">
                    <div class="notification-title">
                        <i class="${icons[type]}"></i>
                        ${type.charAt(0).toUpperCase() + type.slice(1)}
                    </div>
                    <button class="notification-close" onclick="this.parentElement.parentElement.remove()">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
                <div class="notification-message">${message}</div>
            `;
            
            container.appendChild(notification);
            
            setTimeout(() => {
                notification.remove();
            }, 5000);
        }

        function logout() {
            if (confirm('Are you sure you want to logout?')) {
                showNotification('Logging out...', 'info');
                
                const form = document.createElement('form');
                form.method = 'POST';
                form.action = '/logout';
                
                const csrfToken = document.createElement('input');
                csrfToken.type = 'hidden';
                csrfToken.name = '_token';
                csrfToken.value = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
                
                form.appendChild(csrfToken);
                document.body.appendChild(form);
                form.submit();
            }
        }

        document.addEventListener('click', function(e) {
            if (e.target.classList.contains('modal')) {
                e.target.classList.remove('active');
            }
        });

        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                document.querySelectorAll('.modal.active').forEach(modal => {
                    modal.classList.remove('active');
                });
            }
        });
        function togglePasswordVisibility(inputId, iconId) {
            const input = document.getElementById(inputId);
            const icon = document.getElementById(iconId);
            if (input.type === 'password') {
                input.type = 'text';
                icon.classList.remove('fa-eye');
                icon.classList.add('fa-eye-slash');
            } else {
                input.type = 'password';
                icon.classList.remove('fa-eye-slash');
                icon.classList.add('fa-eye');
            }
        }
    </script>

    <div id="ticketModal" class="modal" style="z-index: 9999;">
        <div class="modal-content" style="max-width: 450px; border-radius: 1rem; overflow-y: auto; border: none; box-shadow: 0 25px 50px -12px rgba(0,0,0,0.5);">
            <div id="printableReceipt" style="background: white; padding: 2.5rem; position: relative;">
                <div style="position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%) rotate(-30deg); font-size: 5rem; font-weight: 900; color: rgba(16, 185, 129, 0.05); pointer-events: none; white-space: nowrap; text-transform: uppercase;">PAID & VERIFIED</div>

                <div style="text-align: center; margin-bottom: 2rem;">
                    <div style="width: 70px; height: 70px; background: white; border-radius: 1.25rem; display: flex; align-items: center; justify-content: center; margin: 0 auto 1rem; overflow: hidden; box-shadow: 0 10px 15px -3px rgba(245, 48, 3, 0.3); border: 1px solid #eee;">
                        <img src="{{ asset('images/parkmasterlogo.png') }}" alt="ParkMaster Logo" style="width: 100%; height: 100%; object-fit: cover;">
                    </div>
                    <h2 style="margin: 0; color: #1b1b18; font-weight: 800; letter-spacing: -0.025em; font-size: 1.75rem;">PARKMASTER</h2>
                    <p style="font-size: 0.85rem; color: #f53003; font-weight: 600; margin-top: 0.25rem; letter-spacing: 0.05em; text-transform: uppercase;">Secure Parking Solutions</p>
                    <div style="display: flex; align-items: center; justify-content: center; gap: 0.5rem; margin-top: 1rem; padding: 0.5rem; background: rgba(16, 185, 129, 0.1); border-radius: 2rem; width: fit-content; margin-inline: auto;">
                        <i class="fas fa-check-circle" style="color: #10b981;"></i>
                        <span style="color: #065f46; font-weight: 700; font-size: 0.75rem; text-transform: uppercase;">Payment Confirmed</span>
                    </div>
                </div>

                <div style="border-top: 1px solid #eee; border-bottom: 1px solid #eee; padding: 1.5rem 0; margin-bottom: 1.5rem;">
                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem;">
                        <div>
                            <p style="font-size: 0.7rem; color: #706f6c; margin-bottom: 0.25rem; text-transform: uppercase; font-weight: 600;">Transaction ID</p>
                            <p style="font-weight: 700; color: #1b1b18; font-size: 0.95rem;" id="ticketId">#TRX-00000</p>
                        </div>
                        <div style="text-align: right;">
                            <p style="font-size: 0.7rem; color: #706f6c; margin-bottom: 0.25rem; text-transform: uppercase; font-weight: 600;">Date</p>
                            <p style="font-weight: 700; color: #1b1b18; font-size: 0.95rem;" id="ticketDate">May 05, 2026</p>
                        </div>
                    </div>
                </div>

                <div style="display: flex; flex-direction: column; gap: 0.875rem; margin-bottom: 2rem;">
                    <div style="display: flex; justify-content: space-between; align-items: center;">
                        <span style="font-size: 0.85rem; color: #706f6c;">Customer Name</span>
                        <span style="font-size: 0.85rem; font-weight: 600; color: #1b1b18;" id="ticketName">-</span>
                    </div>
                    <div style="display: flex; justify-content: space-between; align-items: center;">
                        <span style="font-size: 0.85rem; color: #706f6c;">Parking Slot</span>
                        <span style="font-size: 0.85rem; font-weight: 800; color: #f53003;" id="ticketSlot">-</span>
                    </div>
                    <div style="display: flex; justify-content: space-between; align-items: center;">
                        <span style="font-size: 0.85rem; color: #706f6c;">Vehicle Plate</span>
                        <span style="font-size: 0.85rem; font-weight: 600; color: #1b1b18;" id="ticketPlate">-</span>
                    </div>
                    <div style="display: flex; justify-content: space-between; align-items: center;">
                        <span style="font-size: 0.85rem; color: #706f6c;">Vehicle Type</span>
                        <span style="font-size: 0.85rem; font-weight: 600; color: #1b1b18;" id="ticketVehicleType">-</span>
                    </div>
                    <div style="height: 1px; background: #f3f4f6; margin: 0.25rem 0;"></div>
                    <div style="display: flex; justify-content: space-between; align-items: center;">
                        <span style="font-size: 0.85rem; color: #706f6c;">Entry Time</span>
                        <span style="font-size: 0.85rem; font-weight: 600; color: #1b1b18;" id="ticketEntry">-</span>
                    </div>
                    <div style="display: flex; justify-content: space-between; align-items: center;">
                        <span style="font-size: 0.85rem; color: #706f6c;">Exit Time (EST)</span>
                        <span style="font-size: 0.85rem; font-weight: 600; color: #1b1b18;" id="ticketExit">-</span>
                    </div>
                    <div style="display: flex; justify-content: space-between; align-items: center;">
                        <span style="font-size: 0.85rem; color: #706f6c;">Duration</span>
                        <span style="font-size: 0.85rem; font-weight: 600; color: #1b1b18;" id="ticketDuration">-</span>
                    </div>
                    <div style="height: 1px; background: #f3f4f6; margin: 0.25rem 0;"></div>
                    <div style="display: flex; justify-content: space-between; align-items: center;">
                        <span style="font-size: 0.85rem; color: #706f6c;">Payment Method</span>
                        <span style="font-size: 0.85rem; font-weight: 600; color: #1b1b18;" id="ticketMethod">-</span>
                    </div>
                    <div style="display: flex; justify-content: space-between; align-items: center;">
                        <span style="font-size: 0.85rem; color: #706f6c;">Payment Status</span>
                        <span style="font-size: 0.75rem; font-weight: 700; color: #10b981; background: rgba(16, 185, 129, 0.1); padding: 0.25rem 0.5rem; border-radius: 4px; text-transform: uppercase;">Paid</span>
                    </div>
                </div>

                <div style="background: #f8fafc; border-radius: 0.75rem; padding: 1.25rem; margin-bottom: 2rem; border: 1px solid #e2e8f0;">
                    <div style="display: flex; justify-content: space-between; align-items: center;">
                        <span style="font-size: 0.9rem; font-weight: 600; color: #64748b;">Total Amount Paid</span>
                        <span style="font-size: 1.5rem; font-weight: 800; color: #1b1b18;" id="ticketTotal">₱0.00</span>
                    </div>
                </div>

                <div style="text-align: center; margin-top: 1.5rem;">
                    <p style="font-size: 0.75rem; color: #64748b; font-weight: 500;">Please present this receipt to the attendant at the entrance.</p>
                    <p style="font-size: 0.65rem; color: #cbd5e1; margin-top: 1rem;">© 2026 ParkMaster. All rights reserved.</p>
                </div>
            </div>
            
            <div class="modal-footer" style="padding: 1.5rem; display: flex; gap: 1rem; background: #f1f5f9; border-top: 1px solid #e2e8f0;">
                <button class="btn btn-secondary" onclick="closeModal('ticketModal')" style="flex: 1; height: 48px; border-radius: 0.75rem; font-weight: 600; background: white; border: 1px solid #e2e8f0; color: #64748b;">Close</button>
                <button class="btn btn-primary" onclick="printReceipt()" style="flex: 1.5; height: 48px; border-radius: 0.75rem; font-weight: 700; background: #1b1b18; border-color: #1b1b18; display: flex; align-items: center; justify-content: center; gap: 0.5rem; box-shadow: 0 4px 6px -1px rgba(0,0,0,0.2);">
                    <i class="fas fa-print"></i> Print Receipt
                </button>
            </div>
        </div>
    </div>

    <style>
        @media print {
            @page {
                margin: 0;
            }
            body {
                margin: 0;
            }
            body * {
                visibility: hidden;
            }
            #printableReceipt, #printableReceipt * {
                visibility: visible;
            }
            #printableReceipt {
                position: absolute;
                left: 0;
                top: 0;
                width: 100%;
                margin: 0;
                padding: 50px 20px 20px 20px !important;
            }
            #ticketModal {
                background: white !important;
            }
            .modal-content {
                box-shadow: none !important;
                border: none !important;
            }
            .modal-footer {
                display: none !important;
            }
        }
    </style>
</body>
</html>
