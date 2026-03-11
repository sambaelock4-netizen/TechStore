<?php
/**
 * TECHSTORE - Admin Products List avec Bootstrap - Responsive
 */
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestion des Produits - TechStore Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&family=Syne:wght@400;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <style>
/* TECHSTORE PREMIUM - Glassmorphism Dark Theme */
@import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&family=Syne:wght@400;600;700;800&display=swap');

:root {
    --primary: #6c63ff;
    --primary-light: #8b85ff;
    --primary-glow: rgba(108, 99, 255, 0.4);
    --accent: #00d4aa;
    --accent-glow: rgba(0, 212, 170, 0.3);
    --danger: #ff4d6d;
    --warning: #ffb347;
    --success: #00d4aa;
    --info: #38bdf8;

    --bg-base: #08090d;
    --bg-layer1: rgba(255,255,255,0.03);
    --bg-layer2: rgba(255,255,255,0.06);
    --bg-layer3: rgba(255,255,255,0.09);

    --glass-bg: rgba(255, 255, 255, 0.04);
    --glass-border: rgba(255, 255, 255, 0.08);
    --glass-hover: rgba(255, 255, 255, 0.07);
    --glass-strong: rgba(255, 255, 255, 0.10);

    --text-primary: rgba(255,255,255,0.92);
    --text-secondary: rgba(255,255,255,0.55);
    --text-muted: rgba(255,255,255,0.3);

    --shadow-sm: 0 2px 12px rgba(0,0,0,0.3);
    --shadow-md: 0 8px 32px rgba(0,0,0,0.4);
    --shadow-lg: 0 20px 60px rgba(0,0,0,0.5);
    --shadow-glow: 0 0 30px var(--primary-glow);

    --radius-sm: 10px;
    --radius-md: 16px;
    --radius-lg: 24px;
    --radius-xl: 32px;

    --sidebar-width: 260px;
    --transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
}

*, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

html { scroll-behavior: smooth; }

body {
    font-family: 'Plus Jakarta Sans', sans-serif;
    background: var(--bg-base);
    color: var(--text-primary);
    min-height: 100vh;
    overflow-x: hidden;
    position: relative;
}

/* ── ANIMATED BACKGROUND ── */
body::before {
    content: '';
    position: fixed;
    top: -50%;
    left: -50%;
    width: 200%;
    height: 200%;
    background:
        radial-gradient(ellipse 80% 60% at 20% 20%, rgba(108,99,255,0.12) 0%, transparent 60%),
        radial-gradient(ellipse 60% 50% at 80% 80%, rgba(0,212,170,0.08) 0%, transparent 50%),
        radial-gradient(ellipse 40% 40% at 60% 10%, rgba(56,189,248,0.06) 0%, transparent 40%);
    pointer-events: none;
    z-index: 0;
    animation: bgPulse 12s ease-in-out infinite alternate;
}

@keyframes bgPulse {
    0% { opacity: 0.7; transform: scale(1); }
    100% { opacity: 1; transform: scale(1.05); }
}

/* ── SIDEBAR ── */
.sidebar, .admin-sidebar {
    width: var(--sidebar-width);
    background: rgba(12, 12, 18, 0.85);
    backdrop-filter: blur(24px);
    -webkit-backdrop-filter: blur(24px);
    border-right: 1px solid var(--glass-border);
    min-height: 100vh;
    position: fixed;
    left: 0; top: 0;
    z-index: 1000;
    transition: var(--transition);
    display: flex;
    flex-direction: column;
    overflow: hidden;
}

.sidebar::before, .admin-sidebar::before {
    content: '';
    position: absolute;
    top: 0; left: 0;
    width: 100%;
    height: 2px;
    background: linear-gradient(90deg, var(--primary), var(--accent), transparent);
    opacity: 0.8;
}

/* ── BRAND ── */
.sidebar-brand {
    padding: 24px 20px;
    border-bottom: 1px solid var(--glass-border);
    display: flex;
    align-items: center;
    gap: 14px;
    position: relative;
}

.sidebar-brand .brand-icon {
    width: 44px;
    height: 44px;
    background: linear-gradient(135deg, var(--primary), var(--accent));
    border-radius: var(--radius-sm);
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 19px;
    color: white;
    box-shadow: 0 4px 20px var(--primary-glow);
    flex-shrink: 0;
    position: relative;
    overflow: hidden;
}

.sidebar-brand .brand-icon::after {
    content: '';
    position: absolute;
    inset: 0;
    background: linear-gradient(135deg, rgba(255,255,255,0.2), transparent);
    border-radius: inherit;
}

.sidebar-brand .brand-text {
    font-family: 'Syne', sans-serif;
    font-size: 18px;
    font-weight: 800;
    letter-spacing: 2px;
    background: linear-gradient(135deg, #fff 40%, var(--accent));
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    background-clip: text;
}

/* ── NAV ── */
.sidebar-nav { padding: 16px 0; flex: 1; overflow-y: auto; }

.nav-item-custom, .nav-item {
    display: flex !important;
    align-items: center;
    gap: 12px;
    padding: 12px 16px;
    color: var(--text-secondary) !important;
    text-decoration: none !important;
    transition: var(--transition);
    margin: 3px 12px;
    border-radius: var(--radius-sm);
    position: relative;
    overflow: hidden;
    border-left: 2px solid transparent !important;
    font-size: 13.5px;
    font-weight: 500;
}

.nav-item-custom::before, .nav-item::before {
    content: '';
    position: absolute;
    inset: 0;
    background: var(--glass-hover);
    opacity: 0;
    transition: opacity 0.3s;
    border-radius: inherit;
}

.nav-item-custom:hover::before, .nav-item:hover::before { opacity: 1; }
.nav-item-custom:hover, .nav-item:hover {
    color: var(--text-primary) !important;
    transform: translateX(3px);
}

.nav-item-custom.active, .nav-item.active {
    background: rgba(108, 99, 255, 0.12) !important;
    color: var(--primary-light) !important;
    border-left-color: var(--primary) !important;
    box-shadow: inset 0 0 20px rgba(108,99,255,0.05);
}

.nav-item-custom.active::after, .nav-item.active::after {
    content: '';
    position: absolute;
    right: 12px;
    width: 6px; height: 6px;
    border-radius: 50%;
    background: var(--primary);
    box-shadow: 0 0 8px var(--primary);
}

.nav-item-custom i, .nav-item i {
    width: 20px;
    text-align: center;
    font-size: 16px;
    flex-shrink: 0;
}

.nav-divider {
    height: 1px;
    background: var(--glass-border);
    margin: 8px 16px;
}

.nav-section-label {
    font-size: 10px;
    font-weight: 700;
    letter-spacing: 1.5px;
    text-transform: uppercase;
    color: var(--text-muted);
    padding: 8px 28px 4px;
}

/* ── SIDEBAR FOOTER ── */
.sidebar-footer, .mt-auto.p-3 {
    padding: 12px !important;
    border-top: 1px solid var(--glass-border);
    margin-top: auto;
}

.logout-btn {
    display: flex !important;
    align-items: center;
    gap: 12px;
    padding: 10px 14px !important;
    color: var(--text-secondary) !important;
    text-decoration: none !important;
    transition: var(--transition);
    border-radius: var(--radius-sm);
    font-size: 13.5px;
    font-weight: 500;
    margin: 2px 0;
}

.logout-btn:hover {
    background: rgba(255, 77, 109, 0.1) !important;
    color: var(--danger) !important;
}

/* ── MAIN CONTENT ── */
.main-content, .admin-main {
    margin-left: var(--sidebar-width);
    padding: 32px;
    min-height: 100vh;
    position: relative;
    z-index: 1;
    transition: var(--transition);
}

/* ── MOBILE TOGGLE ── */
.mobile-menu-toggle {
    display: none;
    position: fixed;
    top: 16px;
    left: 16px;
    z-index: 1100;
    width: 44px; height: 44px;
    background: var(--glass-strong);
    border: 1px solid var(--glass-border);
    border-radius: var(--radius-sm);
    color: var(--text-primary);
    font-size: 20px;
    cursor: pointer;
    backdrop-filter: blur(12px);
    transition: var(--transition);
    align-items: center;
    justify-content: center;
}

.mobile-menu-toggle:hover {
    background: var(--glass-hover);
    box-shadow: var(--shadow-sm);
}

.sidebar-overlay {
    display: none;
    position: fixed;
    inset: 0;
    background: rgba(0,0,0,0.6);
    backdrop-filter: blur(4px);
    z-index: 999;
    opacity: 0;
    transition: opacity 0.3s;
}

/* ── STAT CARDS ── */
.stat-card {
    background: var(--glass-bg) !important;
    backdrop-filter: blur(20px);
    -webkit-backdrop-filter: blur(20px);
    border: 1px solid var(--glass-border) !important;
    border-radius: var(--radius-lg) !important;
    padding: 24px !important;
    box-shadow: var(--shadow-sm) !important;
    transition: var(--transition) !important;
    position: relative;
    overflow: hidden;
    height: 100%;
}

.stat-card::before {
    content: '';
    position: absolute;
    top: 0; left: 0;
    width: 100%; height: 1px;
    background: linear-gradient(90deg, transparent, rgba(255,255,255,0.15), transparent);
}

.stat-card:hover {
    transform: translateY(-4px) !important;
    box-shadow: 0 16px 40px rgba(0,0,0,0.4), 0 0 0 1px rgba(255,255,255,0.1) !important;
    background: var(--glass-hover) !important;
    border-color: rgba(255,255,255,0.12) !important;
}

.stat-icon {
    width: 56px !important; height: 56px !important;
    border-radius: var(--radius-md) !important;
    display: flex !important;
    align-items: center !important;
    justify-content: center !important;
    font-size: 22px !important;
    backdrop-filter: blur(10px);
}

.stat-number {
    font-family: 'Syne', sans-serif !important;
    font-size: 28px !important;
    font-weight: 700 !important;
    color: var(--text-primary) !important;
    line-height: 1.2;
}

.stat-card p.text-muted,
.stat-label {
    color: var(--text-secondary) !important;
    font-size: 12px !important;
    font-weight: 600 !important;
    letter-spacing: 0.8px !important;
    text-transform: uppercase !important;
    margin-bottom: 6px !important;
}

.stat-change.positive { color: var(--success) !important; }
.stat-change.negative { color: var(--danger) !important; }

/* ── CONTENT CARDS ── */
.content-card {
    background: var(--glass-bg) !important;
    backdrop-filter: blur(20px);
    -webkit-backdrop-filter: blur(20px);
    border: 1px solid var(--glass-border) !important;
    border-radius: var(--radius-lg) !important;
    box-shadow: var(--shadow-sm) !important;
    overflow: hidden;
    position: relative;
}

.content-card::before {
    content: '';
    position: absolute;
    top: 0; left: 0;
    right: 0; height: 1px;
    background: linear-gradient(90deg, transparent, rgba(255,255,255,0.12), transparent);
    pointer-events: none;
    z-index: 1;
}

.content-card .card-header,
.card-header-custom {
    background: var(--glass-bg) !important;
    border-bottom: 1px solid var(--glass-border) !important;
    padding: 20px 24px !important;
}

.content-card .card-header h5,
.card-header-custom h3 {
    font-family: 'Syne', sans-serif !important;
    color: var(--text-primary) !important;
    font-size: 15px !important;
    font-weight: 700 !important;
    display: flex;
    align-items: center;
    gap: 10px;
}

.card-header-custom h3 i,
.content-card .card-header h5 i { color: var(--primary-light) !important; }

/* ── TABLES ── */
.table {
    color: var(--text-primary) !important;
    margin-bottom: 0;
}

.table thead th, .data-table thead th {
    background: rgba(255,255,255,0.03) !important;
    border-bottom: 1px solid var(--glass-border) !important;
    color: var(--text-secondary) !important;
    font-size: 11px !important;
    font-weight: 700 !important;
    text-transform: uppercase !important;
    letter-spacing: 1px !important;
    padding: 14px 20px !important;
    white-space: nowrap;
    border-top: none !important;
}

.table tbody tr, .data-table tbody tr {
    border-bottom: 1px solid rgba(255,255,255,0.04) !important;
    transition: background 0.2s;
}

.table tbody tr:hover, .data-table tbody tr:hover {
    background: rgba(255,255,255,0.03) !important;
}

.table tbody tr:last-child, .data-table tbody tr:last-child {
    border-bottom: none !important;
}

.table td, .data-table td {
    color: var(--text-primary) !important;
    padding: 14px 20px !important;
    border: none !important;
    vertical-align: middle;
    font-size: 13.5px;
}

.data-table { width: 100%; border-collapse: collapse; }

/* ── HEADER ── */
.admin-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 28px;
    padding: 0;
}

.admin-header h1, .header-title h1 {
    font-family: 'Syne', sans-serif !important;
    font-size: 26px !important;
    font-weight: 800 !important;
    color: var(--text-primary) !important;
    letter-spacing: -0.5px;
    margin: 0 !important;
}

.admin-header p, .header-title p, .header-subtitle {
    color: var(--text-secondary) !important;
    font-size: 13px !important;
    margin: 4px 0 0 !important;
}

.header-actions { display: flex; gap: 10px; align-items: center; }

/* ── BUTTONS ── */
.btn {
    font-family: 'Plus Jakarta Sans', sans-serif !important;
    font-weight: 600 !important;
    border-radius: var(--radius-sm) !important;
    transition: var(--transition) !important;
    font-size: 13px !important;
    letter-spacing: 0.3px;
}

.btn-primary {
    background: linear-gradient(135deg, var(--primary), var(--primary-light)) !important;
    border: 1px solid rgba(108,99,255,0.5) !important;
    color: white !important;
    box-shadow: 0 4px 15px var(--primary-glow) !important;
}

.btn-primary:hover {
    transform: translateY(-1px);
    box-shadow: 0 8px 25px var(--primary-glow) !important;
    filter: brightness(1.1);
}

.btn-secondary, .btn-outline-secondary {
    background: var(--glass-strong) !important;
    border: 1px solid var(--glass-border) !important;
    color: var(--text-primary) !important;
    backdrop-filter: blur(10px);
}

.btn-secondary:hover, .btn-outline-secondary:hover {
    background: rgba(255,255,255,0.12) !important;
    border-color: rgba(255,255,255,0.2) !important;
    transform: translateY(-1px);
}

.btn-success {
    background: linear-gradient(135deg, #00b894, var(--success)) !important;
    border: 1px solid rgba(0,212,170,0.4) !important;
    color: white !important;
    box-shadow: 0 4px 15px rgba(0,212,170,0.25) !important;
}

.btn-danger {
    background: linear-gradient(135deg, #e84463, var(--danger)) !important;
    border: 1px solid rgba(255,77,109,0.4) !important;
    color: white !important;
    box-shadow: 0 4px 15px rgba(255,77,109,0.25) !important;
}

.btn-warning {
    background: linear-gradient(135deg, #e69500, var(--warning)) !important;
    border: 1px solid rgba(255,179,71,0.4) !important;
    color: white !important;
}

.btn-outline-primary {
    background: transparent !important;
    border: 1px solid rgba(108,99,255,0.5) !important;
    color: var(--primary-light) !important;
}

.btn-outline-primary:hover {
    background: rgba(108,99,255,0.1) !important;
    border-color: var(--primary) !important;
    transform: translateY(-1px);
}

.btn-sm { padding: 6px 14px !important; font-size: 12px !important; }
.btn-action {
    width: 34px !important; height: 34px !important;
    padding: 0 !important;
    display: inline-flex !important;
    align-items: center !important;
    justify-content: center !important;
    border-radius: var(--radius-sm) !important;
}

/* ── BADGES ── */
.badge {
    font-family: 'Plus Jakarta Sans', sans-serif !important;
    font-weight: 700 !important;
    font-size: 11px !important;
    letter-spacing: 0.5px !important;
    padding: 5px 10px !important;
    border-radius: 20px !important;
}

.bg-success, .badge.bg-success { background: rgba(0,212,170,0.15) !important; color: var(--success) !important; border: 1px solid rgba(0,212,170,0.25) !important; }
.bg-danger, .badge.bg-danger { background: rgba(255,77,109,0.15) !important; color: var(--danger) !important; border: 1px solid rgba(255,77,109,0.25) !important; }
.bg-warning, .badge.bg-warning { background: rgba(255,179,71,0.15) !important; color: var(--warning) !important; border: 1px solid rgba(255,179,71,0.25) !important; }
.bg-info, .badge.bg-info { background: rgba(56,189,248,0.15) !important; color: var(--info) !important; border: 1px solid rgba(56,189,248,0.25) !important; }
.bg-primary, .badge.bg-primary { background: rgba(108,99,255,0.15) !important; color: var(--primary-light) !important; border: 1px solid rgba(108,99,255,0.25) !important; }
.bg-secondary, .badge.bg-secondary { background: rgba(255,255,255,0.08) !important; color: var(--text-secondary) !important; border: 1px solid var(--glass-border) !important; }

.status-badge, .role-badge {
    padding: 5px 12px;
    border-radius: 20px;
    font-size: 11px;
    font-weight: 700;
    letter-spacing: 0.5px;
    text-transform: uppercase;
    display: inline-block;
}

/* ── FORMS ── */
.form-control, .form-select, .form-input {
    background: var(--glass-bg) !important;
    border: 1px solid var(--glass-border) !important;
    color: var(--text-primary) !important;
    border-radius: var(--radius-sm) !important;
    padding: 10px 14px !important;
    font-family: 'Plus Jakarta Sans', sans-serif !important;
    font-size: 13.5px !important;
    transition: var(--transition) !important;
    backdrop-filter: blur(10px);
}

.form-control:focus, .form-select:focus, .form-input:focus {
    background: var(--glass-strong) !important;
    border-color: var(--primary) !important;
    box-shadow: 0 0 0 3px rgba(108,99,255,0.15) !important;
    color: var(--text-primary) !important;
    outline: none !important;
}

.form-control::placeholder { color: var(--text-muted) !important; }
.form-label, label {
    color: var(--text-secondary) !important;
    font-size: 12.5px !important;
    font-weight: 600 !important;
    letter-spacing: 0.4px !important;
    margin-bottom: 6px !important;
    display: block;
}

.form-select option {
    background: #1a1a2e !important;
    color: var(--text-primary) !important;
}

.input-group-text {
    background: var(--glass-strong) !important;
    border: 1px solid var(--glass-border) !important;
    color: var(--text-secondary) !important;
}

/* ── ALERTS ── */
.alert {
    border-radius: var(--radius-md) !important;
    border: 1px solid !important;
    backdrop-filter: blur(10px);
    font-size: 13.5px;
}

.alert-success { background: rgba(0,212,170,0.08) !important; border-color: rgba(0,212,170,0.2) !important; color: var(--success) !important; }
.alert-danger { background: rgba(255,77,109,0.08) !important; border-color: rgba(255,77,109,0.2) !important; color: var(--danger) !important; }
.alert-warning { background: rgba(255,179,71,0.08) !important; border-color: rgba(255,179,71,0.2) !important; color: var(--warning) !important; }
.alert-info { background: rgba(56,189,248,0.08) !important; border-color: rgba(56,189,248,0.2) !important; color: var(--info) !important; }

/* ── QUICK ACTIONS ── */
.quick-action-btn {
    display: flex !important;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    gap: 10px;
    padding: 22px 16px !important;
    background: var(--glass-bg) !important;
    border: 1px solid var(--glass-border) !important;
    border-radius: var(--radius-md) !important;
    color: var(--text-secondary) !important;
    text-decoration: none !important;
    transition: var(--transition) !important;
    text-align: center;
    font-size: 13px;
    font-weight: 500;
}

.quick-action-btn:hover {
    background: var(--glass-strong) !important;
    border-color: rgba(255,255,255,0.15) !important;
    color: var(--text-primary) !important;
    transform: translateY(-3px);
    box-shadow: var(--shadow-md);
}

.quick-action-btn i { font-size: 26px !important; }

/* ── FILTERS BAR ── */
.filters-bar {
    background: var(--glass-bg);
    border: 1px solid var(--glass-border);
    border-radius: var(--radius-md);
    padding: 16px 20px;
    margin-bottom: 24px;
    backdrop-filter: blur(20px);
}

.filters-form { display: flex; gap: 12px; align-items: flex-end; }
.filter-group { display: flex; flex-direction: column; gap: 4px; min-width: 140px; }
.filter-group label { font-size: 11px !important; }

/* ── CARDS / PRODUCT CARDS ── */
.card {
    background: var(--glass-bg) !important;
    border: 1px solid var(--glass-border) !important;
    border-radius: var(--radius-lg) !important;
    color: var(--text-primary) !important;
}

.card-body { padding: 20px !important; }
.card-title { color: var(--text-primary) !important; font-weight: 700 !important; }
.card-text { color: var(--text-secondary) !important; }
.card-img-top { border-radius: var(--radius-lg) var(--radius-lg) 0 0 !important; }

/* ── DROPDOWN ── */
.dropdown-menu {
    background: rgba(16,16,28,0.95) !important;
    border: 1px solid var(--glass-border) !important;
    border-radius: var(--radius-md) !important;
    backdrop-filter: blur(20px);
    box-shadow: var(--shadow-lg) !important;
    padding: 8px !important;
    min-width: 180px;
}

.dropdown-item {
    color: var(--text-secondary) !important;
    border-radius: var(--radius-sm) !important;
    padding: 10px 14px !important;
    font-size: 13.5px;
    transition: var(--transition);
    display: flex;
    align-items: center;
    gap: 10px;
}

.dropdown-item:hover {
    background: var(--glass-hover) !important;
    color: var(--text-primary) !important;
}

.export-dropdown { position: relative; }
.export-dropdown .dropdown-menu { display: none; position: absolute; right: 0; top: 100%; margin-top: 8px; z-index: 100; }
.export-dropdown:hover .dropdown-menu { display: block; }

/* ── PAGINATION ── */
.pagination { gap: 4px; }
.page-link {
    background: var(--glass-bg) !important;
    border: 1px solid var(--glass-border) !important;
    color: var(--text-secondary) !important;
    border-radius: var(--radius-sm) !important;
    padding: 8px 14px !important;
    transition: var(--transition);
    font-size: 13px;
}

.page-link:hover {
    background: var(--glass-strong) !important;
    color: var(--text-primary) !important;
    border-color: rgba(255,255,255,0.15) !important;
}

.page-item.active .page-link {
    background: linear-gradient(135deg, var(--primary), var(--primary-light)) !important;
    border-color: var(--primary) !important;
    color: white !important;
    box-shadow: 0 4px 12px var(--primary-glow);
}

/* ── STATS GRID ── */
.stats-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: 20px;
    margin-bottom: 28px;
}

.charts-grid {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 20px;
    margin-bottom: 28px;
}

/* ── RANK BADGES ── */
.rank-badge {
    width: 28px; height: 28px;
    border-radius: 50%;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    font-size: 11px;
    font-weight: 700;
    background: var(--glass-strong);
    color: var(--text-secondary);
    border: 1px solid var(--glass-border);
}

.rank-badge.top {
    background: linear-gradient(135deg, #ffd700, #ffa500);
    color: #000;
    border: none;
    box-shadow: 0 2px 8px rgba(255,215,0,0.3);
}

/* ── MISC ── */
.text-muted { color: var(--text-secondary) !important; }
.text-success { color: var(--success) !important; }
.text-danger { color: var(--danger) !important; }
.text-warning { color: var(--warning) !important; }
.text-info { color: var(--info) !important; }
.text-primary { color: var(--primary-light) !important; }
.fw-bold, .fw-semibold { color: var(--text-primary) !important; }

hr { border-color: var(--glass-border) !important; }

.breadcrumb-item { color: var(--text-secondary) !important; }
.breadcrumb-item.active { color: var(--text-primary) !important; }
.breadcrumb-item + .breadcrumb-item::before { color: var(--text-muted) !important; }

/* ── PROFILE ── */
.profile-avatar {
    width: 80px; height: 80px;
    border-radius: 50%;
    background: linear-gradient(135deg, var(--primary), var(--accent));
    display: flex; align-items: center; justify-content: center;
    font-size: 32px; color: white;
    margin: 0 auto 16px;
    box-shadow: 0 8px 24px var(--primary-glow);
}

/* ── EMPTY STATE ── */
.empty-state, .empty-cell { text-align: center !important; color: var(--text-muted) !important; padding: 40px !important; }

/* ── TABLE RESPONSIVE ── */
.table-responsive { overflow-x: auto; }
.table-responsive::-webkit-scrollbar { height: 4px; }
.table-responsive::-webkit-scrollbar-track { background: transparent; }
.table-responsive::-webkit-scrollbar-thumb { background: var(--glass-border); border-radius: 2px; }

/* ── BREADCRUMB ── */
.breadcrumb { background: none !important; padding: 0 !important; margin: 0 0 6px !important; }

/* ── SCROLLBAR ── */
::-webkit-scrollbar { width: 6px; }
::-webkit-scrollbar-track { background: var(--bg-base); }
::-webkit-scrollbar-thumb { background: var(--glass-border); border-radius: 3px; }
::-webkit-scrollbar-thumb:hover { background: rgba(255,255,255,0.15); }

/* ── RESPONSIVE ── */
@media (max-width: 991px) {
    .sidebar, .admin-sidebar {
        transform: translateX(-100%);
    }
    .sidebar.show, .admin-sidebar.show {
        transform: translateX(0);
    }
    .main-content, .admin-main {
        margin-left: 0 !important;
        padding: 20px 16px !important;
        padding-top: 70px !important;
    }
    .mobile-menu-toggle { display: flex !important; }
    .sidebar-overlay { display: block; }
    .sidebar-overlay.show { opacity: 1; }
    .charts-grid { grid-template-columns: 1fr !important; }
    .stats-grid { grid-template-columns: 1fr 1fr !important; }
    .admin-header h1, .header-title h1 { font-size: 20px !important; }
}

@media (max-width: 576px) {
    .stats-grid { grid-template-columns: 1fr !important; }
    .main-content, .admin-main { padding: 16px 12px !important; padding-top: 70px !important; }
}

/* ── ANIMATION ON LOAD ── */
@keyframes fadeInUp {
    from { opacity: 0; transform: translateY(16px); }
    to { opacity: 1; transform: translateY(0); }
}

.stat-card, .content-card, .card {
    animation: fadeInUp 0.5s ease both;
}

.stat-card:nth-child(1) { animation-delay: 0.05s; }
.stat-card:nth-child(2) { animation-delay: 0.1s; }
.stat-card:nth-child(3) { animation-delay: 0.15s; }
.stat-card:nth-child(4) { animation-delay: 0.2s; }


/* ====================================================
   PATCH PREMIUM — corrections globales v2
   ==================================================== */

/* 1. FORCER LE DARK SUR TOUS LES TEXTES Bootstrap */
.text-dark { color: var(--text-primary) !important; }
.fw-bold, .fw-semibold, strong { color: var(--text-primary) !important; }
h1,h2,h3,h4,h5,h6 { color: var(--text-primary) !important; }
p { color: var(--text-secondary); }
code { background: rgba(124,111,255,0.15) !important; color: var(--primary-light) !important; border-radius: 6px; padding: 2px 8px; }
.bg-light { background: rgba(255,255,255,0.07) !important; color: var(--text-primary) !important; }

/* 2. TABLEAUX — texte blanc garanti partout */
.table, .table * { color: var(--text-primary) !important; }
.table > :not(caption) > * > * {
    background-color: transparent !important;
    color: var(--text-primary) !important;
    border-color: rgba(255,255,255,0.05) !important;
}
.table-hover > tbody > tr:hover > * {
    background-color: rgba(255,255,255,0.04) !important;
    color: var(--text-primary) !important;
}
.table thead th {
    background: rgba(255,255,255,0.04) !important;
    color: rgba(255,255,255,0.55) !important;
    font-size: 11px !important;
    font-weight: 700 !important;
    text-transform: uppercase !important;
    letter-spacing: 0.9px !important;
    padding: 16px 24px !important;
    border-bottom: 1px solid rgba(255,255,255,0.08) !important;
    border-top: none !important;
}
.table tbody td {
    color: rgba(255,255,255,0.9) !important;
    padding: 16px 24px !important;
    border-bottom: 1px solid rgba(255,255,255,0.04) !important;
    border-top: none !important;
    vertical-align: middle !important;
    font-size: 13.5px !important;
}
.table tbody tr:last-child td { border-bottom: none !important; }

/* 3. STAT ICONS — couleurs visibles sur fond sombre */
.stat-icon[style*="rgba(13, 110, 253"] { background: rgba(124,111,255,0.18) !important; color: var(--primary-light) !important; }
.stat-icon[style*="rgba(111, 66"] { background: rgba(192,132,252,0.18) !important; color: #c084fc !important; }
.stat-icon[style*="rgba(25, 135"] { background: rgba(0,212,170,0.18) !important; color: var(--success) !important; }
.stat-icon[style*="rgba(255, 193"] { background: rgba(255,179,71,0.18) !important; color: var(--warning) !important; }
.stat-icon { background: rgba(124,111,255,0.18) !important; }

/* 4. BREATHING — espacement généreux */
.main-content, .admin-main {
    padding: 40px 44px !important;
}
.row.g-3, .row.g-md-4 { --bs-gutter-x: 1.5rem; --bs-gutter-y: 1.5rem; }
.mb-4 { margin-bottom: 1.8rem !important; }
.content-card .card-header, .card-header-custom {
    padding: 22px 28px !important;
}
.content-card .card-body.p-0 { padding: 0 !important; }

/* 5. SIDEBAR FRAGMENT PAGES (stock, profile, statistics, logs) */
.admin-wrapper {
    display: flex !important;
    min-height: 100vh;
    position: relative;
    z-index: 1;
}
.admin-sidebar {
    width: 264px !important;
    background: rgba(10,10,18,0.9) !important;
    backdrop-filter: blur(28px) !important;
    -webkit-backdrop-filter: blur(28px) !important;
    border-right: 1px solid rgba(255,255,255,0.09) !important;
    min-height: 100vh;
    position: fixed !important;
    left: 0; top: 0;
    z-index: 1000 !important;
    display: flex !important;
    flex-direction: column !important;
    overflow: hidden !important;
    transition: transform 0.3s ease !important;
}
.admin-sidebar::after {
    content: '' !important;
    position: absolute !important;
    top: 0; left: 0;
    width: 100%; height: 2px !important;
    background: linear-gradient(90deg, #7c6fff, #00d4aa, transparent) !important;
}
.admin-sidebar .sidebar-brand {
    padding: 22px 20px !important;
    border-bottom: 1px solid rgba(255,255,255,0.09) !important;
    display: flex !important;
    align-items: center !important;
    gap: 13px !important;
}
.admin-sidebar .sidebar-brand .brand-icon,
.admin-sidebar .sidebar-brand > span.brand-icon {
    width: 42px !important; height: 42px !important;
    background: linear-gradient(135deg, #7c6fff, #00d4aa) !important;
    border-radius: 10px !important;
    display: flex !important;
    align-items: center !important; justify-content: center !important;
    font-size: 18px !important;
    color: white !important;
    box-shadow: 0 4px 18px rgba(124,111,255,0.35) !important;
    flex-shrink: 0 !important;
    text-decoration: none !important;
}
.admin-sidebar .sidebar-brand .brand-icon i,
.admin-sidebar .sidebar-brand > span.brand-icon i { color: white !important; }
.admin-sidebar .sidebar-brand .brand-text,
.admin-sidebar .sidebar-brand > span.brand-text {
    font-family: 'Syne', sans-serif !important;
    font-size: 17px !important; font-weight: 800 !important;
    letter-spacing: 2.5px !important;
    background: linear-gradient(135deg, #fff 40%, #00d4aa) !important;
    -webkit-background-clip: text !important;
    -webkit-text-fill-color: transparent !important;
    background-clip: text !important;
}
.admin-sidebar .nav-item {
    display: flex !important;
    align-items: center !important;
    gap: 11px !important;
    padding: 11px 15px !important;
    color: rgba(255,255,255,0.58) !important;
    text-decoration: none !important;
    transition: all 0.28s ease !important;
    margin: 2px 10px !important;
    border-radius: 10px !important;
    font-size: 13.5px !important; font-weight: 500 !important;
    border-left: 2px solid transparent !important;
}
.admin-sidebar .nav-item:hover {
    color: rgba(255,255,255,0.93) !important;
    background: rgba(255,255,255,0.075) !important;
    transform: translateX(2px) !important;
}
.admin-sidebar .nav-item.active {
    color: #a89eff !important;
    background: rgba(124,111,255,0.13) !important;
    border-left-color: #7c6fff !important;
}
.admin-sidebar .nav-item i { width: 18px !important; text-align: center !important; font-size: 15px !important; flex-shrink: 0 !important; }
.admin-sidebar .sidebar-footer {
    padding: 12px !important;
    border-top: 1px solid rgba(255,255,255,0.09) !important;
    margin-top: auto !important;
}
.admin-sidebar .logout-btn {
    display: flex !important; align-items: center !important;
    gap: 11px !important; padding: 10px 13px !important;
    color: rgba(255,255,255,0.55) !important;
    text-decoration: none !important;
    border-radius: 10px !important;
    font-size: 13.5px !important; font-weight: 500 !important;
    transition: all 0.28s ease !important;
}
.admin-sidebar .logout-btn:hover {
    background: rgba(255,77,109,0.12) !important;
    color: #ff4d6d !important;
}
.admin-main {
    margin-left: 264px !important;
    padding: 40px 44px !important;
    flex: 1 !important;
    min-height: 100vh !important;
    position: relative !important;
    z-index: 1 !important;
}

/* 6. DASHBOARD HEADER */
h2.fw-bold.text-dark {
    font-family: 'Syne', sans-serif !important;
    font-size: 26px !important; font-weight: 800 !important;
    color: rgba(255,255,255,0.93) !important;
}

/* 7. QUICK ACTION BUTTONS */
.quick-action-btn {
    display: flex !important;
    flex-direction: column !important;
    align-items: center !important;
    justify-content: center !important;
    gap: 12px !important;
    padding: 28px 16px !important;
    background: rgba(255,255,255,0.04) !important;
    border: 1px solid rgba(255,255,255,0.09) !important;
    border-radius: 16px !important;
    color: rgba(255,255,255,0.6) !important;
    text-decoration: none !important;
    transition: all 0.28s ease !important;
    text-align: center !important;
    font-size: 13px !important; font-weight: 500 !important;
    width: 100% !important;
}
.quick-action-btn:hover {
    background: rgba(255,255,255,0.08) !important;
    border-color: rgba(255,255,255,0.16) !important;
    color: rgba(255,255,255,0.93) !important;
    transform: translateY(-4px) !important;
    box-shadow: 0 12px 30px rgba(0,0,0,0.4) !important;
}
.quick-action-btn i { font-size: 28px !important; margin-bottom: 2px !important; }
.quick-action-btn .bi-plus-circle { color: #a89eff !important; }
.quick-action-btn .bi-person-plus { color: #00d4aa !important; }
.quick-action-btn .bi-tag { color: #ffb347 !important; }
.quick-action-btn .bi-graph-up-arrow { color: #38bdf8 !important; }

/* 8. CATÉGORIES — slug code tag */
.categories-table code {
    background: rgba(124,111,255,0.15) !important;
    color: #a89eff !important;
    border-radius: 6px !important;
    padding: 3px 9px !important;
    font-size: 12px !important;
}

/* 9. PROFILE PAGE */
.profile-avatar-wrap {
    text-align: center;
    padding: 32px 24px 24px;
}
.profile-avatar-icon {
    width: 90px; height: 90px;
    border-radius: 50%;
    background: linear-gradient(135deg, #7c6fff, #00d4aa);
    display: flex; align-items: center; justify-content: center;
    font-size: 36px; color: white;
    margin: 0 auto 16px;
    box-shadow: 0 8px 32px rgba(124,111,255,0.4);
}
.input-icon {
    display: flex; align-items: center; justify-content: center;
    background: rgba(255,255,255,0.08) !important;
    border: 1px solid rgba(255,255,255,0.09) !important;
    border-right: none !important;
    color: rgba(255,255,255,0.45) !important;
    padding: 0 14px !important;
    border-radius: 10px 0 0 10px !important;
    min-width: 44px;
}
.input-group .form-control { border-radius: 0 10px 10px 0 !important; }
.input-group .form-control:only-child { border-radius: 10px !important; }
.section-divider {
    height: 1px;
    background: rgba(255,255,255,0.08);
    margin: 24px 0;
}
.card-body-custom { padding: 28px !important; }

/* 10. ALERT BOX (stock page) */
.alert-box {
    border-radius: 14px;
    border: 1px solid;
    padding: 18px 22px;
    display: flex; align-items: flex-start; gap: 14px;
    margin-bottom: 24px;
}
.alert-box.warning {
    background: rgba(255,179,71,0.08) !important;
    border-color: rgba(255,179,71,0.22) !important;
}
.alert-box .alert-icon { font-size: 22px; color: #ffb347; flex-shrink: 0; margin-top: 2px; }
.alert-box .alert-content { flex: 1; }
.alert-box .alert-content strong { color: rgba(255,255,255,0.9) !important; }

/* 11. FILTERS CARD */
.card.border-0.shadow-sm,
.card.mb-4 {
    background: rgba(255,255,255,0.04) !important;
    border: 1px solid rgba(255,255,255,0.09) !important;
    border-radius: 16px !important;
    box-shadow: none !important;
}
.card.border-0.shadow-sm .card-body {
    padding: 20px 24px !important;
}

/* 12. BADGES corrigées Bootstrap override */
.badge.bg-warning.text-dark { color: #ffd080 !important; }
span.badge { display: inline-block !important; }

/* 13. STATUS ROLE BADGES avec couleurs sombres  */
.role-badge, .status-badge { display: inline-block !important; }

/* 14. MOBILE */
@media (max-width: 991px) {
    .admin-sidebar { transform: translateX(-100%) !important; }
    .admin-sidebar.show { transform: translateX(0) !important; }
    .admin-main { margin-left: 0 !important; padding: 24px 18px !important; padding-top: 70px !important; }
    .main-content { margin-left: 0 !important; padding: 24px 18px !important; padding-top: 70px !important; }
    .mobile-menu-toggle { display: flex !important; }
    .sidebar-overlay.show { opacity: 1 !important; }
}
@media (max-width: 576px) {
    .admin-main, .main-content { padding: 16px 12px !important; padding-top: 70px !important; }
}


    </style>
</head>
<body>
    <!-- Mobile Menu Toggle -->
    <button class="mobile-menu-toggle" onclick="toggleSidebar()">
        <i class="bi bi-list"></i>
    </button>
    
    <!-- Sidebar Overlay -->
    <div class="sidebar-overlay" onclick="toggleSidebar()"></div>

    <div class="d-flex">
        <!-- Sidebar -->
        <aside class="sidebar" id="sidebar">
            <div class="sidebar-brand d-flex align-items-center gap-3">
                <div class="brand-icon text-white">
                    <i class="bi bi-motherboard"></i>
                </div>
                <span class="brand-text text-white">TECHSTORE</span>
            </div>
            
            <nav class="sidebar-nav">
                <a href="<?= BASE_URL ?>/admin" class="nav-item-custom">
                    <i class="fas fa-th-large"></i>
                    <span>Dashboard</span>
                </a>
                <a href="<?= BASE_URL ?>/admin/products" class="nav-item-custom active">
                    <i class="fas fa-box"></i>
                    <span>Produits</span>
                </a>
                <a href="<?= BASE_URL ?>/admin/orders" class="nav-item-custom">
                    <i class="fas fa-shopping-cart"></i>
                    <span>Commandes</span>
                </a>
                <a href="<?= BASE_URL ?>/admin/users" class="nav-item-custom">
                    <i class="fas fa-users"></i>
                    <span>Utilisateurs</span>
                </a>
                <a href="<?= BASE_URL ?>/admin/categories" class="nav-item-custom">
                    <i class="fas fa-tags"></i>
                    <span>Catégories</span>
                </a>
                <a href="<?= BASE_URL ?>/admin/stock" class="nav-item-custom">
                    <i class="fas fa-warehouse"></i>
                    <span>Stock</span>
                </a>
                <a href="<?= BASE_URL ?>/admin/promotions" class="nav-item-custom">
                    <i class="fas fa-percent"></i>
                    <span>Promotions</span>
                </a>
                <a href="<?= BASE_URL ?>/admin/statistics" class="nav-item-custom">
                    <i class="fas fa-chart-bar"></i>
                    <span>Statistiques</span>
                </a>
                <a href="<?= BASE_URL ?>/admin/profile" class="nav-item-custom">
                    <i class="fas fa-user-cog"></i>
                    <span>Profil</span>
                </a>
            </nav>
            
            <div class="mt-auto p-3">
                <a href="<?= BASE_URL ?>/home" class="logout-btn">
                    <i class="fas fa-arrow-left"></i>
                    <span>Retour au site</span>
                </a>
                <a href="<?= BASE_URL ?>/logout" class="logout-btn">
                    <i class="fas fa-sign-out-alt"></i>
                    <span>Déconnexion</span>
                </a>
            </div>
        </aside>

        <!-- Main Content -->
        <main class="main-content flex-grow-1">
            <!-- Header -->
            <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-3">
                <div>
                    <h2 class="fw-bold mb-1">Gestion des Produits</h2>
                    <p class="text-muted mb-0">Gérez votre catalogue de produits</p>
                </div>
                <a href="<?= BASE_URL ?>/admin/product/add" class="btn btn-primary">
                    <i class="bi bi-plus-circle me-2"></i>
                    <span class="hide-mobile">Ajouter un produit</span>
                    <span class="hide-tablet hide-desktop">Ajouter</span>
                </a>
            </div>

            <!-- Filters -->
            <div class="card mb-4 border-0 shadow-sm">
                <div class="card-body">
                    <form method="GET" action="<?= BASE_URL ?>/admin/products" class="row g-2 g-md-3">
                        <div class="col-12 col-md-4">
                            <input type="text" name="search" placeholder="Rechercher..." 
                                   value="<?= htmlspecialchars($search ?? '') ?>" class="form-control">
                        </div>
                        <div class="col-6 col-md-3">
                            <select name="category" class="form-select">
                                <option value="">Catégorie</option>
                                <?php if (!empty($categories)): ?>
                                    <?php foreach ($categories as $cat): ?>
                                        <option value="<?= $cat['id'] ?>" <?= ($selectedCategory ?? '') == $cat['id'] ? 'selected' : '' ?>>
                                            <?= htmlspecialchars($cat['name']) ?>
                                        </option>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </select>
                        </div>
                        <div class="col-6 col-md-2">
                            <select name="status" class="form-select">
                                <option value="">Statut</option>
                                <option value="1" <?= ($selectedStatus ?? '') === '1' ? 'selected' : '' ?>>Actif</option>
                                <option value="0" <?= ($selectedStatus ?? '') === '0' ? 'selected' : '' ?>>Inactif</option>
                            </select>
                        </div>
                        <div class="col-12 col-md-3">
                            <button type="submit" class="btn btn-primary w-100">
                                <i class="bi bi-search me-2"></i> <span class="hide-mobile">Filtrer</span>
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Products Table -->
            <div class="content-card">
                <div class="card-body p-0">
                    <?php if (!empty($products)): ?>
                        <div class="table-responsive">
                            <table class="table table-hover mb-0">
                                <thead>
                                    <tr>
                                        <th class="hide-mobile">ID</th>
                                        <th>Image</th>
                                        <th>Nom</th>
                                        <th class="hide-tablet">Catégorie</th>
                                        <th>Prix</th>
                                        <th class="hide-mobile">Stock</th>
                                        <th class="hide-mobile">Production</th>
                                        <th class="hide-mobile">Promo</th>
                                        <th class="hide-tablet hide-mobile">Statut</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($products as $product): ?>
                                    <tr>
                                        <td class="hide-mobile"><?= $product['id'] ?></td>
                                        <td>
                                            <?php if (!empty($product['image'])): ?>
                                                <img src="<?= UPLOAD_URL ?>/<?= htmlspecialchars($product['image']) ?>" 
                                                     alt="<?= htmlspecialchars($product['name']) ?>" class="product-thumb">
                                            <?php else: ?>
                                                <div class="no-image"><i class="bi bi-image"></i></div>
                                            <?php endif; ?>
                                        </td>
                                        <td>
                                            <div class="fw-semibold"><?= htmlspecialchars($product['name']) ?></div>
                                            <?php if (!empty($product['sku'])): ?>
                                                <small class="text-muted"><?= htmlspecialchars($product['sku']) ?></small>
                                            <?php endif; ?>
                                        </td>
                                        <td class="hide-tablet"><?= htmlspecialchars($product['category_name'] ?? '-') ?></td>
                                        <td>
                                            <?php if (($product['is_promotion'] ?? 0) == 1 && !empty($product['promotion_price'])): ?>
                                                <div class="text-decoration-line-through text-muted small"><?= displayPrice($product['price'] ?? 0) ?></div>
                                                <div class="fw-bold text-danger"><?= displayPrice($product['promotion_price']) ?></div>
                                            <?php else: ?>
                                                <div class="fw-bold text-success"><?= displayPrice($product['price'] ?? 0) ?></div>
                                            <?php endif; ?>
                                        </td>
                                        <td class="hide-mobile">
                                            <?php $stock = $product['stock'] ?? 0; ?>
                                            <span class="stock-badge <?= $stock <= 5 ? 'low' : 'normal' ?>">
                                                <?= $stock ?>
                                            </span>
                                        </td>
                                        <td class="hide-mobile">
                                            <?php if (($product['is_production'] ?? 0) == 1): ?>
                                                <span class="badge badge-production"><i class="fas fa-industry"></i></span>
                                            <?php else: ?>
                                                <span class="badge bg-secondary">-</span>
                                            <?php endif; ?>
                                        </td>
                                        <td class="hide-mobile">
                                            <?php if (($product['is_promotion'] ?? 0) == 1): ?>
                                                <span class="badge badge-promotion">-<?= $product['discount'] ?? 0 ?>%</span>
                                            <?php else: ?>
                                                <span class="badge bg-secondary">-</span>
                                            <?php endif; ?>
                                        </td>
                                        <td class="hide-tablet hide-mobile">
                                            <?php if (($product['is_active'] ?? 1) == 1): ?>
                                                <span class="badge bg-success">Actif</span>
                                            <?php else: ?>
                                                <span class="badge bg-secondary">Inactif</span>
                                            <?php endif; ?>
                                        </td>
                                        <td>
                                            <div class="d-flex gap-2">
                                                <a href="<?= BASE_URL ?>/admin/product/edit/<?= $product['id'] ?>" 
                                                   class="btn btn-sm btn-outline-primary btn-action" title="Modifier">
                                                    <i class="bi bi-pencil"></i>
                                                </a>
                                                <a href="<?= BASE_URL ?>/admin/product/delete/<?= $product['id'] ?>" 
                                                   class="btn btn-sm btn-outline-danger btn-action" title="Supprimer"
                                                   onclick="return confirm('Êtes-vous sûr ?')">
                                                    <i class="bi bi-trash"></i>
                                                </a>
                                            </div>
                                        </td>
                                    </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    <?php else: ?>
                        <div class="text-center py-5">
                            <i class="bi bi-box-seam text-muted" style="font-size: 48px;"></i>
                            <p class="text-muted mt-3">Aucun produit trouvé</p>
                            <a href="<?= BASE_URL ?>/admin/product/add" class="btn btn-primary">
                                <i class="bi bi-plus-circle me-2"></i>
                                Ajouter un produit
                            </a>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </main>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        function toggleSidebar() {
            document.getElementById('sidebar').classList.toggle('show');
            document.querySelector('.sidebar-overlay').classList.toggle('show');
        }
        
        // Close sidebar when clicking outside on mobile
        document.addEventListener('click', function(e) {
            const sidebar = document.getElementById('sidebar');
            const toggle = document.querySelector('.mobile-menu-toggle');
            if (window.innerWidth <= 991) {
                if (!sidebar.contains(e.target) && !toggle.contains(e.target)) {
                    sidebar.classList.remove('show');
                    document.querySelector('.sidebar-overlay').classList.remove('show');
                }
            }
        });
        
        // Handle window resize
        window.addEventListener('resize', function() {
            if (window.innerWidth > 991) {
                document.getElementById('sidebar').classList.remove('show');
                document.querySelector('.sidebar-overlay').classList.remove('show');
            }
        });
    </script>
</body>
</html>
