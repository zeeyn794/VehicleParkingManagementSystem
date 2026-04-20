@extends('layouts.app')

@section('title', 'Dashboard')

@section('extra-head')
<style>
    .hero-card, .info-card {
        background: #fff;
        border: 1px solid var(--gray-100);
        border-radius: var(--radius-lg);
        padding: 1.25rem;
        box-shadow: 0 10px 30px rgba(12, 68, 124, 0.05);
    }

    .hero-card {
        display: grid;
        gap: 1rem;
        margin-bottom: 1.25rem;
        background: linear-gradient(135deg, #ffffff 0%, #eef6fd 100%);
    }

    .hero-title {
        font-size: 1.6rem;
        line-height: 1.2;
        letter-spacing: -0.03em;
    }

    .hero-copy {
        max-width: 54rem;
        color: var(--gray-600);
    }

    .stats-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(180px, 1fr));
        gap: 1rem;
        margin-bottom: 1.25rem;
    }

    .stat-label {
        font-size: 0.75rem;
        text-transform: uppercase;
        letter-spacing: 0.08em;
        color: var(--gray-400);
        margin-bottom: 0.35rem;
    }

    .stat-value {
        font-size: 1.5rem;
        font-weight: 600;
        letter-spacing: -0.03em;
    }

    .info-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
        gap: 1rem;
    }

    .info-card h3 {
        font-size: 1rem;
        margin-bottom: 0.4rem;
    }

    .info-card p {
        color: var(--gray-600);
    }
</style>
@endsection

@section('content')
    <header class="page-header">
        <h1 class="page-title">Dashboard</h1>
        <p class="page-subtitle">A quick snapshot of your parking activity and account status.</p>
    </header>

    <section class="hero-card">
        <div>
            <div class="hero-title">Welcome back, {{ auth()->user()->name }}.</div>
            <p class="hero-copy">
                Your vehicle parking workspace is ready. Use the menu to manage active sessions, review history,
                and keep your saved vehicles and payments organized.
            </p>
        </div>
    </section>

    <section class="stats-grid">
        <article class="info-card">
            <div class="stat-label">Account</div>
            <div class="stat-value">Verified</div>
        </article>
        <article class="info-card">
            <div class="stat-label">Saved Vehicles</div>
            <div class="stat-value">0</div>
        </article>
        <article class="info-card">
            <div class="stat-label">Active Sessions</div>
            <div class="stat-value">0</div>
        </article>
        <article class="info-card">
            <div class="stat-label">Payment Methods</div>
            <div class="stat-value">0</div>
        </article>
    </section>

    <section class="info-grid">
        <article class="info-card">
            <h3>Start parking faster</h3>
            <p>Add your vehicles and preferred parking options so future booking and check-in flows are smoother.</p>
        </article>
        <article class="info-card">
            <h3>Track active sessions</h3>
            <p>Monitor current parking sessions, durations, and charges from one place as this module grows.</p>
        </article>
        <article class="info-card">
            <h3>Review recent payments</h3>
            <p>Keep an eye on your transactions and history once payment processing is connected.</p>
        </article>
    </section>
@endsection
