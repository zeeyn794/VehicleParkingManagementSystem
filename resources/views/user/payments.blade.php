@extends('layouts.app')

@section('title', 'Payments')

@section('content')
    <header class="page-header">
        <h1 class="page-title">Payments</h1>
        <p class="page-subtitle">Manage payment methods and review billing activity.</p>
    </header>

    <section style="background:#fff;border:1px solid var(--gray-100);border-radius:var(--radius-lg);padding:1.25rem;">
        <p style="color:var(--gray-600);">
            The route is active and ready for cards, invoices, payment summaries, and transaction states.
        </p>
    </section>
@endsection
