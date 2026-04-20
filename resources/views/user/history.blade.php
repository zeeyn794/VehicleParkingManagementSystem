@extends('layouts.app')

@section('title', 'History')

@section('content')
    <header class="page-header">
        <h1 class="page-title">History</h1>
        <p class="page-subtitle">Review your past parking sessions and account activity.</p>
    </header>

    <section style="background:#fff;border:1px solid var(--gray-100);border-radius:var(--radius-lg);padding:1.25rem;">
        <p style="color:var(--gray-600);">
            This page is set up for a future session history table, filters, and downloadable receipts.
        </p>
    </section>
@endsection
