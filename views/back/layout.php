<?php
/**
 * TECHSTORE - Admin Layout ULTRA PREMIUM
 * Style Apple / Stripe - Glassmorphism Dark Theme
 */

$currentPage = $currentPage ?? 'dashboard';
$adminName = isset($_SESSION['user']['firstname']) ? $_SESSION['user']['firstname'] : 'Admin';
$adminEmail = isset($_SESSION['user']['email']) ? $_SESSION['user']['email'] : 'admin@techstore.com';
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $pageTitle ?? 'Dashboard' ?> - TechStore Admin</title>
    
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    
    <!-- Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    
    <!-- Charts -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    
    <style>
/* ============================================
   TECHSTORE ULTRA PREMIUM - Apple/Stripe Style
   ============================================ */

@import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&family=Syne:wght@400;500;600;700;800&display=swap');

:root {
    /* Primary Colors */
    --primary: #6366f1;
    --primary-light: #818cf8;
    --primary-dark: #4f46e5;
    --primary-glow: rgba(99, 102, 241, 0.4);
    
    /* Accent Colors */
    --accent: #14b8a6;
    --accent-light: #2dd4bf;
    --accent-glow: rgba(20, 184, 166, 0.3);
    
    /* Status Colors */
    --success: #10b981;
    --warning: #f59e0b;
    --danger: #ef4444;
    --info: #0ea5e9;
    
    /* Background - Deep Dark */
    --bg-primary: #0a0a0f;
    --bg-secondary: #12121a;
    --bg-tertiary: #1a1a24;
    --bg-card: rgba(26, 26, 36, 0.6);
    
    /* Glass Effect */
    --glass-bg: rgba(255, 255, 255, 0.03);
    --glass-bg-hover: rgba(255, 255, 255, 0.06);
    --glass-border: rgba(255, 255, 255, 0.08);
    --glass-border-hover: rgba(255, 255, 255, 0.12);
    
    /* Text Colors */
    --text-primary: #ffffff;
    --text-secondary: rgba(255, 255, 255, 0.7);
    --text-tertiary: rgba(255, 255, 255, 0.5);
    --text-muted: rgba(255, 255, 255, 0.4);
    
    /* Shadows */
    --shadow-sm: 0 2px 8px rgba(0, 0, 0, 0.3);
    --shadow-md: 0 4px 20px rgba(0, 0, 0, 0.4);
    --shadow-lg: 0 8px 40px rgba(0, 0, 0, 0.5);
    --shadow-glow: 0 0 40px var(--primary-glow);
    --shadow-glow-accent: 0 0 40px var(--accent-glow);
    
    /* Border Radius */
    --radius-sm: 8px;
    --radius-md: 12px;
    --radius-lg: 16px;
    --radius-xl: 24px;
    --radius-full: 9999px;
    
    /* Transitions */
    --transition-fast: 0.15s ease;
    --transition-base: 0.25s cubic-bezier(0.4, 0, 0.2, 1);
    --transition-slow: 0.4s cubic-bezier(0.4, 0, 0.2, 1);
    
    /* Layout */
    --sidebar-width: 280px;
    --header-height: 72px;
}

/* ============================================
   RESET & BASE
   ============================================ */

*, *::before, *::after {
    box-sizing: border-box;
    margin: 0;
    padding: 0;
}

html {
    scroll-behavior: smooth;
    -webkit-font-smoothing: antialiased;
    -moz-osx-font-smoothing: grayscale;
}

body {
    font-family: 'Times New Roman', Times, serif;
    background: var(--bg-primary);
    color: var(--text-primary);
    min-height: 100vh;
    overflow-x: hidden;
    line-height: 1.5;
}

/* Animated Background */
body::before {
    content: '';
    position: fixed;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: 
        radial-gradient(ellipse 80% 50% at 20% 10%, rgba(99, 102, 241, 0.15) 0%, transparent 50%),
        radial-gradient(ellipse 60% 40% at 80% 90%, rgba(20, 184, 166, 0.1) 0%, transparent 50%),
        radial-gradient(ellipse 40% 30% at 50% 50%, rgba(14, 165, 233, 0.05) 0%, transparent 50%);
    pointer-events: none;
    z-index: 0;
    animation: bgShift 20s ease-in-out infinite alternate;
}

@keyframes bgShift {
    0% { opacity: 0.8; transform: scale(1); }
    100% { opacity: 1; transform: scale(1.05); }
}

/* ============================================
   LAYOUT
   ============================================ */

.admin-layout {
    display: flex;
    min-height: 100vh;
    position: relative;
    z-index: 1;
}

/* Sidebar */
.sidebar {
    width: var(--sidebar-width);
    height: 100vh;
    position: fixed;
    left: 0;
    top: 0;
    background: linear-gradient(180deg, var(--bg-secondary) 0%, rgba(18, 18, 26, 0.95) 100%);
    border-right: 1px solid var(--glass-border);
    display: flex;
    flex-direction: column;
    z-index: 1000;
    transition: transform var(--transition-base);
    backdrop-filter: blur(20px);
}

.sidebar::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    height: 1px;
    background: linear-gradient(90deg, transparent, var(--primary-light), var(--accent), transparent);
}

/* Brand */
.sidebar-brand {
    padding: 24px;
    display: flex;
    align-items: center;
    gap: 14px;
    border-bottom: 1px solid var(--glass-border);
    position: relative;
}

.brand-icon {
    width: 48px;
    height: 48px;
    background: linear-gradient(135deg, var(--primary) 0%, var(--accent) 100%);
    border-radius: var(--radius-md);
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 22px;
    color: white;
    box-shadow: 0 4px 20px var(--primary-glow);
    position: relative;
    overflow: hidden;
}

.brand-icon::after {
    content: '';
    position: absolute;
    inset: 0;
    background: linear-gradient(135deg, rgba(255,255,255,0.3), transparent);
}

.brand-text {
    font-family: 'Times New Roman', Times, serif;
    font-size: 20px;
    font-weight: 800;
    letter-spacing: 1px;
    color: white;
}

/* Navigation */
.sidebar-nav {
    flex: 1;
    padding: 16px 12px;
    overflow-y: auto;
    overflow-x: hidden;
}

.nav-section {
    margin-bottom: 24px;
}

.nav-section-title {
    font-size: 11px;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 1.5px;
    color: var(--text-muted);
    padding: 8px 16px;
    margin-bottom: 4px;
}

.nav-item {
    display: flex;
    align-items: center;
    gap: 12px;
    padding: 12px 16px;
    color: var(--text-secondary);
    text-decoration: none;
    border-radius: var(--radius-md);
    font-size: 14px;
    font-weight: 500;
    transition: all var(--transition-base);
    position: relative;
    overflow: hidden;
    margin-bottom: 4px;
}

.nav-item::before {
    content: '';
    position: absolute;
    left: 0;
    top: 50%;
    transform: translateY(-50%);
    width: 3px;
    height: 0;
    background: linear-gradient(180deg, var(--primary), var(--accent));
    border-radius: 0 4px 4px 0;
    transition: height var(--transition-base);
}

.nav-item:hover {
    color: var(--text-primary);
    background: var(--glass-bg-hover);
    transform: translateX(4px);
}

.nav-item:hover::before {
    height: 60%;
}

.nav-item.active {
    color: var(--primary-light);
    background: rgba(99, 102, 241, 0.1);
}

.nav-item.active::before {
    height: 70%;
}

.nav-item.active .nav-icon {
    color: var(--primary-light);
}

.nav-icon {
    width: 20px;
    height: 20px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 16px;
    color: var(--text-tertiary);
    transition: color var(--transition-fast);
}

.nav-badge {
    margin-left: auto;
    background: linear-gradient(135deg, var(--primary), var(--accent));
    color: white;
    font-size: 11px;
    font-weight: 700;
    padding: 2px 8px;
    border-radius: var(--radius-full);
    min-width: 20px;
    text-align: center;
}

/* Sidebar Footer */
.sidebar-footer {
    padding: 16px;
    border-top: 1px solid var(--glass-border);
}

.user-card {
    display: flex;
    align-items: center;
    gap: 12px;
    padding: 12px;
    background: var(--glass-bg);
    border-radius: var(--radius-md);
    border: 1px solid var(--glass-border);
    transition: all var(--transition-base);
    cursor: pointer;
}

.user-card:hover {
    background: var(--glass-bg-hover);
    border-color: var(--glass-border-hover);
}

.user-avatar {
    width: 40px;
    height: 40px;
    border-radius: var(--radius-full);
    background: linear-gradient(135deg, var(--primary), var(--accent));
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 16px;
    font-weight: 700;
    color: white;
    box-shadow: 0 2px 10px var(--primary-glow);
}

.user-info {
    flex: 1;
    min-width: 0;
}

.user-name {
    font-size: 14px;
    font-weight: 600;
    color: var(--text-primary);
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
}

.user-role {
    font-size: 12px;
    color: var(--text-tertiary);
}

.user-dropdown {
    color: var(--text-tertiary);
    font-size: 14px;
}

/* ============================================
   MAIN CONTENT
   ============================================ */

.main-wrapper {
    flex: 1;
    margin-left: var(--sidebar-width);
    min-height: 100vh;
    display: flex;
    flex-direction: column;
}

/* Header */
.top-header {
    height: var(--header-height);
    padding: 0 32px;
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 24px;
    background: rgba(10, 10, 15, 0.8);
    backdrop-filter: blur(20px);
    border-bottom: 1px solid var(--glass-border);
    position: sticky;
    top: 0;
    z-index: 100;
}

.header-left {
    display: flex;
    align-items: center;
    gap: 20px;
}

.menu-toggle {
    display: none;
    width: 40px;
    height: 40px;
    background: var(--glass-bg);
    border: 1px solid var(--glass-border);
    border-radius: var(--radius-sm);
    color: var(--text-secondary);
    font-size: 18px;
    cursor: pointer;
    transition: all var(--transition-base);
}

.menu-toggle:hover {
    background: var(--glass-bg-hover);
    color: var(--text-primary);
}

.page-title {
    font-family: 'Times New Roman', Times, serif;
    font-size: 22px;
    font-weight: 700;
    color: var(--text-primary);
}

.breadcrumb {
    display: flex;
    align-items: center;
    gap: 8px;
    font-size: 13px;
    color: var(--text-tertiary);
    margin-top: 4px;
}

.breadcrumb a {
    color: var(--text-secondary);
    text-decoration: none;
    transition: color var(--transition-fast);
}

.breadcrumb a:hover {
    color: var(--primary-light);
}

.breadcrumb span {
    color: var(--text-muted);
}

/* Search */
.search-box {
    position: relative;
    width: 320px;
}

.search-box input {
    width: 100%;
    padding: 10px 16px 10px 44px;
    background: var(--glass-bg);
    border: 1px solid var(--glass-border);
    border-radius: var(--radius-full);
    color: var(--text-primary);
    font-size: 14px;
    transition: all var(--transition-base);
}

.search-box input::placeholder {
    color: var(--text-muted);
}

.search-box input:focus {
    outline: none;
    background: var(--glass-bg-hover);
    border-color: var(--primary);
    box-shadow: 0 0 0 3px rgba(99, 102, 241, 0.15);
}

.search-box i {
    position: absolute;
    left: 16px;
    top: 50%;
    transform: translateY(-50%);
    color: var(--text-tertiary);
    font-size: 14px;
}

/* Header Actions */
.header-actions {
    display: flex;
    align-items: center;
    gap: 12px;
}

.header-btn {
    width: 42px;
    height: 42px;
    display: flex;
    align-items: center;
    justify-content: center;
    background: var(--glass-bg);
    border: 1px solid var(--glass-border);
    border-radius: var(--radius-md);
    color: var(--text-secondary);
    font-size: 16px;
    cursor: pointer;
    transition: all var(--transition-base);
    position: relative;
}

.header-btn:hover {
    background: var(--glass-bg-hover);
    color: var(--text-primary);
    transform: translateY(-2px);
}

.header-btn .badge-dot {
    position: absolute;
    top: 8px;
    right: 8px;
    width: 8px;
    height: 8px;
    background: var(--danger);
    border-radius: 50%;
    border: 2px solid var(--bg-primary);
}

/* Main Content Area */
.main-content {
    flex: 1;
    padding: 32px;
    position: relative;
    z-index: 1;
}

/* ============================================
   KPI CARDS
   ============================================ */

.kpi-grid {
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    gap: 24px;
    margin-bottom: 32px;
}

.kpi-card {
    background: var(--glass-bg);
    border: 1px solid var(--glass-border);
    border-radius: var(--radius-lg);
    padding: 24px;
    position: relative;
    overflow: hidden;
    transition: all var(--transition-base);
    animation: fadeInUp 0.6s ease forwards;
    opacity: 0;
}

.kpi-card:nth-child(1) { animation-delay: 0.1s; }
.kpi-card:nth-child(2) { animation-delay: 0.2s; }
.kpi-card:nth-child(3) { animation-delay: 0.3s; }
.kpi-card:nth-child(4) { animation-delay: 0.4s; }

.kpi-card::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    height: 3px;
    background: linear-gradient(90deg, var(--primary), var(--accent));
    opacity: 0;
    transition: opacity var(--transition-base);
}

.kpi-card:hover {
    transform: translateY(-4px);
    border-color: var(--glass-border-hover);
    box-shadow: var(--shadow-lg);
}

.kpi-card:hover::before {
    opacity: 1;
}

.kpi-header {
    display: flex;
    align-items: flex-start;
    justify-content: space-between;
    margin-bottom: 16px;
}

.kpi-icon {
    width: 52px;
    height: 52px;
    border-radius: var(--radius-md);
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 22px;
}

.kpi-icon.primary {
    background: rgba(99, 102, 241, 0.15);
    color: var(--primary-light);
}

.kpi-icon.success {
    background: rgba(16, 185, 129, 0.15);
    color: var(--success);
}

.kpi-icon.warning {
    background: rgba(245, 158, 11, 0.15);
    color: var(--warning);
}

.kpi-icon.danger {
    background: rgba(239, 68, 68, 0.15);
    color: var(--danger);
}

.kpi-icon.info {
    background: rgba(14, 165, 233, 0.15);
    color: var(--info);
}

.kpi-trend {
    display: flex;
    align-items: center;
    gap: 4px;
    font-size: 13px;
    font-weight: 600;
    padding: 4px 10px;
    border-radius: var(--radius-full);
}

.kpi-trend.up {
    background: rgba(16, 185, 129, 0.15);
    color: var(--success);
}

.kpi-trend.down {
    background: rgba(239, 68, 68, 0.15);
    color: var(--danger);
}

.kpi-value {
    font-family: 'Syne', sans-serif;
    font-size: 32px;
    font-weight: 800;
    color: var(--text-primary);
    line-height: 1.1;
    margin-bottom: 4px;
}

.kpi-label {
    font-size: 13px;
    color: var(--text-tertiary);
    font-weight: 500;
}

/* ============================================
   CARDS & CONTENT
   ============================================ */

.content-card {
    background: var(--glass-bg);
    border: 1px solid var(--glass-border);
    border-radius: var(--radius-lg);
    overflow: hidden;
    transition: all var(--transition-base);
    animation: fadeInUp 0.6s ease forwards;
    opacity: 0;
    animation-delay: 0.5s;
}

.content-card:hover {
    border-color: var(--glass-border-hover);
    box-shadow: var(--shadow-lg);
}

.card-header {
    padding: 20px 24px;
    border-bottom: 1px solid var(--glass-border);
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 16px;
}

.card-title {
    font-family: 'Syne', sans-serif;
    font-size: 16px;
    font-weight: 700;
    color: var(--text-primary);
    display: flex;
    align-items: center;
    gap: 10px;
}

.card-title i {
    color: var(--primary-light);
}

.card-body {
    padding: 24px;
}

/* ============================================
   TABLES
   ============================================ */

.table-container {
    overflow-x: auto;
    margin: -1px;
}

.data-table {
    width: 100%;
    border-collapse: collapse;
}

.data-table thead th {
    background: rgba(255, 255, 255, 0.02);
    padding: 16px 20px;
    text-align: left;
    font-size: 12px;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    color: var(--text-tertiary);
    border-bottom: 1px solid var(--glass-border);
    white-space: nowrap;
}

.data-table tbody tr {
    transition: background var(--transition-fast);
    border-bottom: 1px solid var(--glass-border);
}

.data-table tbody tr:last-child {
    border-bottom: none;
}

.data-table tbody tr:hover {
    background: var(--glass-bg-hover);
}

.data-table tbody td {
    padding: 16px 20px;
    font-size: 14px;
    color: var(--text-secondary);
    vertical-align: middle;
}

/* ============================================
   BUTTONS
   ============================================ */

.btn {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 8px;
    padding: 10px 20px;
    font-family: 'Plus Jakarta Sans', sans-serif;
    font-size: 14px;
    font-weight: 600;
    border-radius: var(--radius-md);
    border: none;
    cursor: pointer;
    transition: all var(--transition-base);
    text-decoration: none;
}

.btn-primary {
    background: linear-gradient(135deg, var(--primary) 0%, var(--primary-light) 100%);
    color: white;
    box-shadow: 0 4px 15px var(--primary-glow);
}

.btn-primary:hover {
    transform: translateY(-2px);
    box-shadow: 0 6px 25px var(--primary-glow);
}

.btn-secondary {
    background: var(--glass-bg);
    border: 1px solid var(--glass-border);
    color: var(--text-secondary);
}

.btn-secondary:hover {
    background: var(--glass-bg-hover);
    color: var(--text-primary);
    border-color: var(--glass-border-hover);
}

.btn-success {
    background: linear-gradient(135deg, var(--success), #059669);
    color: white;
    box-shadow: 0 4px 15px rgba(16, 185, 129, 0.3);
}

.btn-danger {
    background: linear-gradient(135deg, var(--danger), #dc2626);
    color: white;
    box-shadow: 0 4px 15px rgba(239, 68, 68, 0.3);
}

.btn-sm {
    padding: 6px 12px;
    font-size: 12px;
}

.btn-icon {
    width: 36px;
    height: 36px;
    padding: 0;
    border-radius: var(--radius-sm);
}

/* ============================================
   BADGES
   ============================================ */

.badge {
    display: inline-flex;
    align-items: center;
    padding: 4px 12px;
    font-size: 12px;
    font-weight: 600;
    border-radius: var(--radius-full);
    text-transform: capitalize;
}

.badge-success {
    background: rgba(16, 185, 129, 0.15);
    color: var(--success);
    border: 1px solid rgba(16, 185, 129, 0.2);
}

.badge-warning {
    background: rgba(245, 158, 11, 0.15);
    color: var(--warning);
    border: 1px solid rgba(245, 158, 11, 0.2);
}

.badge-danger {
    background: rgba(239, 68, 68, 0.15);
    color: var(--danger);
    border: 1px solid rgba(239, 68, 68, 0.2);
}

.badge-info {
    background: rgba(14, 165, 233, 0.15);
    color: var(--info);
    border: 1px solid rgba(14, 165, 233, 0.2);
}

.badge-primary {
    background: rgba(99, 102, 241, 0.15);
    color: var(--primary-light);
    border: 1px solid rgba(99, 102, 241, 0.2);
}

.badge-secondary {
    background: var(--glass-bg);
    color: var(--text-tertiary);
    border: 1px solid var(--glass-border);
}

/* ============================================
   FORMS
   ============================================ */

.form-group {
    margin-bottom: 20px;
}

.form-label {
    display: block;
    font-size: 13px;
    font-weight: 600;
    color: var(--text-secondary);
    margin-bottom: 8px;
}

.form-control {
    width: 100%;
    padding: 12px 16px;
    background: var(--glass-bg);
    border: 1px solid var(--glass-border);
    border-radius: var(--radius-md);
    color: var(--text-primary);
    font-size: 14px;
    transition: all var(--transition-base);
}

.form-control::placeholder {
    color: var(--text-muted);
}

.form-control:focus {
    outline: none;
    background: var(--glass-bg-hover);
    border-color: var(--primary);
    box-shadow: 0 0 0 3px rgba(99, 102, 241, 0.15);
}

.form-select {
    appearance: none;
    background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='16' height='16' fill='%23777777' viewBox='0 0 16 16'%3E%3Cpath d='M7.247 11.14 2.451 5.658C1.885 5.013 2.345 4 3.204 4h9.592a1 1 0 0 1 .753 1.659l-4.796 5.48a1 1 0 0 1-1.506 0z'/%3E%3C/svg%3E");
    background-repeat: no-repeat;
    background-position: right 16px center;
    padding-right: 44px;
}

/* ============================================
   CHARTS
   ============================================ */

.chart-container {
    position: relative;
    height: 300px;
}

/* ============================================
   ANIMATIONS
   ============================================ */

@keyframes fadeInUp {
    from {
        opacity: 0;
        transform: translateY(20px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

@keyframes fadeIn {
    from { opacity: 0; }
    to { opacity: 1; }
}

@keyframes slideInLeft {
    from {
        opacity: 0;
        transform: translateX(-20px);
    }
    to {
        opacity: 1;
        transform: translateX(0);
    }
}

@keyframes pulse {
    0%, 100% { opacity: 1; }
    50% { opacity: 0.5; }
}

@keyframes shimmer {
    0% { background-position: -200% 0; }
    100% { background-position: 200% 0; }
}

/* Stagger animations */
.stagger-1 { animation-delay: 0.1s; }
.stagger-2 { animation-delay: 0.2s; }
.stagger-3 { animation-delay: 0.3s; }
.stagger-4 { animation-delay: 0.4s; }
.stagger-5 { animation-delay: 0.5s; }

/* ============================================
   RESPONSIVE
   ============================================ */

@media (max-width: 1400px) {
    .kpi-grid {
        grid-template-columns: repeat(2, 1fr);
    }
}

@media (max-width: 1200px) {
    .search-box {
        width: 240px;
    }
}

@media (max-width: 991px) {
    .sidebar {
        transform: translateX(-100%);
    }
    
    .sidebar.show {
        transform: translateX(0);
    }
    
    .main-wrapper {
        margin-left: 0;
    }
    
    .menu-toggle {
        display: flex;
    }
    
    .kpi-grid {
        grid-template-columns: repeat(2, 1fr);
    }
    
    .top-header {
        padding: 0 20px;
    }
    
    .main-content {
        padding: 24px 20px;
    }
    
    .search-box {
        display: none;
    }
}

@media (max-width: 768px) {
    .kpi-grid {
        grid-template-columns: 1fr;
    }
    
    .page-title {
        font-size: 18px;
    }
    
    .header-actions {
        gap: 8px;
    }
    
    .header-btn {
        width: 38px;
        height: 38px;
    }
}

@media (max-width: 576px) {
    .top-header {
        padding: 0 16px;
    }
    
    .main-content {
        padding: 16px;
    }
    
    .kpi-card {
        padding: 20px;
    }
    
    .kpi-value {
        font-size: 28px;
    }
    
    .card-header {
        flex-direction: column;
        align-items: flex-start;
    }
}

/* ============================================
   UTILITIES
   ============================================ */

.text-primary { color: var(--primary-light) !important; }
.text-success { color: var(--success) !important; }
.text-warning { color: var(--warning) !important; }
.text-danger { color: var(--danger) !important; }
.text-info { color: var(--info) !important; }
.text-muted { color: var(--text-tertiary) !important; }
.text-white { color: var(--text-primary) !important; }

.bg-primary { background: rgba(99, 102, 241, 0.15) !important; }
.bg-success { background: rgba(16, 185, 129, 0.15) !important; }
.bg-warning { background: rgba(245, 158, 11, 0.15) !important; }
.bg-danger { background: rgba(239, 68, 68, 0.15) !important; }
.bg-info { background: rgba(14, 165, 233, 0.15) !important; }

.fw-bold { font-weight: 700 !important; }
.fw-semibold { font-weight: 600 !important; }
.fw-medium { font-weight: 500 !important; }

.mb-0 { margin-bottom: 0 !important; }
.mb-3 { margin-bottom: 16px !important; }
.mb-4 { margin-bottom: 24px !important; }
.mt-4 { margin-top: 24px !important; }
.gap-2 { gap: 8px !important; }
.gap-3 { gap: 16px !important; }

.d-flex { display: flex !important; }
.align-items-center { align-items: center !important; }
.justify-content-between { justify-content: space-between !important; }
.flex-wrap { flex-wrap: wrap !important; }

/* Scrollbar */
::-webkit-scrollbar {
    width: 6px;
    height: 6px;
}

::-webkit-scrollbar-track {
    background: transparent;
}

::-webkit-scrollbar-thumb {
    background: var(--glass-border);
    border-radius: 3px;
}

::-webkit-scrollbar-thumb:hover {
    background: var(--glass-border-hover);
}

/* Sidebar Overlay */
.sidebar-overlay {
    display: none;
    position: fixed;
    inset: 0;
    background: rgba(0, 0, 0, 0.6);
    backdrop-filter: blur(4px);
    z-index: 999;
    opacity: 0;
    transition: opacity var(--transition-base);
}

.sidebar-overlay.show {
    opacity: 1;
}

@media (max-width: 991px) {
    .sidebar-overlay {
        display: block;
    }
}
    </style>
</head>
<body>
    <!-- Sidebar Overlay (Mobile) -->
    <div class="sidebar-overlay" id="sidebarOverlay" onclick="toggleSidebar()"></div>

    <div class="admin-layout">
        <!-- Sidebar -->
        <aside class="sidebar" id="sidebar">
            <!-- Brand -->
            <div class="sidebar-brand">
                <div class="brand-icon">
                    <i class="bi bi-motherboard"></i>
                </div>
                <span class="brand-text">TECHSTORE</span>
            </div>
            
            <!-- Navigation -->
            <nav class="sidebar-nav">
                <div class="nav-section">
                    <div class="nav-section-title">Principal</div>
                    
                    <a href="<?= BASE_URL ?>/admin" class="nav-item <?= $currentPage === 'dashboard' ? 'active' : '' ?>">
                        <i class="nav-icon bi bi-grid-1x2"></i>
                        <span>Dashboard</span>
                    </a>
                    
                    <a href="<?= BASE_URL ?>/admin/products" class="nav-item <?= $currentPage === 'products' ? 'active' : '' ?>">
                        <i class="nav-icon bi bi-box-seam"></i>
                        <span>Produits</span>
                        <?php if (isset($productCount) && $productCount > 0): ?>
                        <span class="nav-badge"><?= $productCount ?></span>
                        <?php endif; ?>
                    </a>
                    
                    <a href="<?= BASE_URL ?>/admin/orders" class="nav-item <?= $currentPage === 'orders' ? 'active' : '' ?>">
                        <i class="nav-icon bi bi-bag"></i>
                        <span>Commandes</span>
                        <?php if (isset($pendingOrders) && $pendingOrders > 0): ?>
                        <span class="nav-badge"><?= $pendingOrders ?></span>
                        <?php endif; ?>
                    </a>
                    
                    <a href="<?= BASE_URL ?>/admin/users" class="nav-item <?= $currentPage === 'users' ? 'active' : '' ?>">
                        <i class="nav-icon bi bi-people"></i>
                        <span>Utilisateurs</span>
                    </a>
                </div>
                
                <div class="nav-section">
                    <div class="nav-section-title">Gestion</div>
                    
                    <a href="<?= BASE_URL ?>/admin/categories" class="nav-item <?= $currentPage === 'categories' ? 'active' : '' ?>">
                        <i class="nav-icon bi bi-tags"></i>
                        <span>Catégories</span>
                    </a>
                    
                    <a href="<?= BASE_URL ?>/admin/stock" class="nav-item <?= $currentPage === 'stock' ? 'active' : '' ?>">
                        <i class="nav-icon bi bi-warehouse"></i>
                        <span>Stock</span>
                    </a>
                    
                    <a href="<?= BASE_URL ?>/admin/promotions" class="nav-item <?= $currentPage === 'promotions' ? 'active' : '' ?>">
                        <i class="nav-icon bi bi-percent"></i>
                        <span>Promotions</span>
                    </a>
                </div>
                
                <div class="nav-section">
                    <div class="nav-section-title">Analyse</div>
                    
                    <a href="<?= BASE_URL ?>/admin/statistics" class="nav-item <?= $currentPage === 'statistics' ? 'active' : '' ?>">
                        <i class="nav-icon bi bi-graph-up-arrow"></i>
                        <span>Statistiques</span>
                    </a>
                    
                    <a href="<?= BASE_URL ?>/admin/logs" class="nav-item <?= $currentPage === 'logs' ? 'active' : '' ?>">
                        <i class="nav-icon bi bi-journal-text"></i>
                        <span>Logs</span>
                    </a>
                </div>
            </nav>
            
            <!-- Footer -->
            <div class="sidebar-footer">
                <a href="<?= BASE_URL ?>/admin/profile" class="nav-item <?= $currentPage === 'profile' ? 'active' : '' ?>">
                    <i class="nav-icon bi bi-person-gear"></i>
                    <span>Mon Profil</span>
                </a>
                
                <div class="user-card" onclick="window.location.href='<?= BASE_URL ?>/logout'">
                    <div class="user-avatar">
                        <?= strtoupper(substr($adminName, 0, 1)) ?>
                    </div>
                    <div class="user-info">
                        <div class="user-name"><?= htmlspecialchars($adminName) ?></div>
                        <div class="user-role">Administrateur</div>
                    </div>
                    <i class="bi bi-box-arrow-right user-dropdown"></i>
                </div>
            </div>
        </aside>

        <!-- Main Content -->
        <div class="main-wrapper">
            <!-- Header -->
            <header class="top-header">
                <div class="header-left">
                    <button class="menu-toggle" onclick="toggleSidebar()">
                        <i class="bi bi-list"></i>
                    </button>
                    <div>
                        <h1 class="page-title"><?= $pageTitle ?? 'Dashboard' ?></h1>
                        <?php if (isset($breadcrumb)): ?>
                        <div class="breadcrumb">
                            <a href="<?= BASE_URL ?>/admin">Admin</a>
                            <span>/</span>
                            <span><?= $pageTitle ?? 'Dashboard' ?></span>
                        </div>
                        <?php endif; ?>
                    </div>
                </div>
                
                <div class="search-box">
                    <i class="bi bi-search"></i>
                    <input type="text" placeholder="Rechercher...">
                </div>
                
                <div class="header-actions">
                    <button class="header-btn" title="Notifications">
                        <i class="bi bi-bell"></i>
                        <span class="badge-dot"></span>
                    </button>
                    <button class="header-btn" title="Messages">
                        <i class="bi bi-chat-dots"></i>
                    </button>
                    <button class="header-btn" title="Paramètres">
                        <i class="bi bi-gear"></i>
                    </button>
                    <a href="<?= BASE_URL ?>/home" class="header-btn" title="Voir le site">
                        <i class="bi bi-box-arrow-up-right"></i>
                    </a>
                </div>
            </header>

            <!-- Content -->
            <main class="main-content">
                
                <?php if (isset($alert)): ?>
                <div class="alert alert-<?= $alert['type'] ?? 'info' ?> mb-4" style="animation: fadeInUp 0.4s ease;">
                    <?= $alert['message'] ?? '' ?>
                </div>
                <?php endif; ?>
                
                <!-- Dynamic Content -->
                <?= $content ?? '' ?>
                
            </main>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    
    <script>
        // Toggle Sidebar
        function toggleSidebar() {
            const sidebar = document.getElementById('sidebar');
            const overlay = document.getElementById('sidebarOverlay');
            sidebar.classList.toggle('show');
            overlay.classList.toggle('show');
        }
        
        // Close sidebar when clicking outside on mobile
        document.addEventListener('click', function(e) {
            const sidebar = document.getElementById('sidebar');
            const toggle = document.querySelector('.menu-toggle');
            const overlay = document.getElementById('sidebarOverlay');
            
            if (window.innerWidth <= 991) {
                if (!sidebar.contains(e.target) && !toggle.contains(e.target)) {
                    sidebar.classList.remove('show');
                    overlay.classList.remove('show');
                }
            }
        });
        
        // Handle window resize
        window.addEventListener('resize', function() {
            if (window.innerWidth > 991) {
                const sidebar = document.getElementById('sidebar');
                const overlay = document.getElementById('sidebarOverlay');
                sidebar.classList.remove('show');
                overlay.classList.remove('show');
            }
        });
        
        // Add animation on scroll
        const observerOptions = {
            threshold: 0.1,
            rootMargin: '0px 0px -50px 0px'
        };
        
        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    entry.target.style.opacity = '1';
                    entry.target.style.transform = 'translateY(0)';
                }
            });
        }, observerOptions);
        
        document.querySelectorAll('.kpi-card, .content-card').forEach(el => {
            observer.observe(el);
        });
    </script>
</body>
</html>

