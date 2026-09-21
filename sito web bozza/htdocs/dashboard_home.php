<?php
session_start();

// The authenticated application can populate these values in the session.
$companyName = $_SESSION['company_name'] ?? $_SESSION['username'] ?? 'Azienda cliente';
$companyLogo = $_SESSION['company_logo'] ?? null;
$systems = $_SESSION['irrigation_systems'] ?? [
    ['id' => 1, 'name' => 'Impianto principale', 'location' => 'Giardino nord', 'status' => 'Attivo', 'last_sync' => 'Sincronizzato ora'],
    ['id' => 2, 'name' => 'Serra produttiva', 'location' => 'Area serre', 'status' => 'Attivo', 'last_sync' => 'Sincronizzato 12 min fa'],
];

function esc($value) { return htmlspecialchars((string)$value, ENT_QUOTES, 'UTF-8'); }
?><!doctype html>
<html lang="it">
<head>
    <meta charset="utf-8"><meta name="viewport" content="width=device-width, initial-scale=1">
    <title>I miei sistemi · Smart Irrigation</title>
    <link rel="stylesheet" href="assets/dashboard.css">
    <link rel="preconnect" href="https://fonts.googleapis.com"><link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500;600;700&family=Manrope:wght@600;700;800&display=swap" rel="stylesheet">
</head>
<body>
<div class="app-shell">
    <main class="main-content">
        <header class="topbar"><div><span class="eyebrow">AREA CLIENTE</span><h1><?= esc($companyName) ?></h1></div><div class="avatar"><?= esc(strtoupper(substr($companyName, 0, 1))) ?></div></header>
        <div class="rule"></div>
        <section class="page-intro"><div class="brand-mark">💧</div><div><p class="eyebrow">PANORAMICA</p><h2>I miei sistemi di irrigazione</h2><p class="muted">Controlla i tuoi impianti e mantieni ogni area sempre in salute.</p></div></section>
        <section class="systems-grid">
            <?php foreach ($systems as $system): ?>
            <a class="system-card" href="dashboard_impianto.php?id=<?= esc($system['id']) ?>">
                <div class="card-top"><span class="system-icon">⌁</span><span class="status"><i></i><?= esc($system['status']) ?></span></div>
                <h3><?= esc($system['name']) ?></h3><p><?= esc($system['location']) ?></p>
                <div class="card-footer"><span><?= esc($system['last_sync']) ?></span><b>→</b></div>
            </a>
            <?php endforeach; ?>
        </section>
    </main>
    <aside class="sidebar"><div class="sidebar-brand"><span class="brand-mark small">💧</span><span>Smart<br><strong>Irrigation</strong></span></div><div class="sidebar-separator"></div>
        <nav><a class="side-link active" href="dashboard_home.php"><span>⌂</span>Home</a><a class="side-link" href="#statistics"><span>◔</span>Statistiche generali</a><a class="side-link" href="#ai"><span>✦</span>Consigli AI</a><a class="side-link" href="#contact"><span>↗</span>Contattaci</a></nav>
        <a class="side-link logout" href="logout.php"><span>↪</span>Log-out</a>
    </aside>
</div>
</body></html>
