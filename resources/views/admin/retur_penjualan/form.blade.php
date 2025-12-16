<style>
    .form-container {
        /* max-width: 1400px; */
    }
    .form-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 1.5rem;
    }
    .form-title {
        font-size: 1.5rem;
        font-weight: 600;
        color: #1a1a1a;
        margin: 0;
    }
    .form-card {
        background: #fff;
        border: 1px solid #e5e7eb;
        border-radius: 8px;
        padding: 2rem;
        box-shadow: 0 1px 3px rgba(0,0,0,0.05);
    }
    .form-row {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 1.5rem;
        margin-bottom: 1.5rem;
    }
    .form-group {
        display: flex;
        flex-direction: column;
    }
    .form-group label {
        display: block;
        font-size: 0.875rem;
        font-weight: 500;
        color: #374151;
        margin-bottom: 0.5rem;
    }
    .form-control {
        width: 100%;
        padding: 0.625rem 0.875rem;
        border: 1px solid #d1d5db;
        border-radius: 6px; /* Konsisten */
        font-size: 0.9375rem;
        transition: border-color 0.2s;
        height: 40px;
    }
    .form-control:focus {
        outline: none;
        border-color: #2563eb;
    }
    .form-control:read-only {
        background-color: #f9fafb;
        cursor: not-allowed;
    }
    .is-invalid { border-color: #ef4444; }
    .invalid-feedback {
        color: #ef4444;
        font-size: 0.8125rem;
        margin-top: 0.25rem;
    }
    .text-danger { color: #ef4444; }
    .text-center { text-align: center; }

    .divider {
        height: 1px;
        background: #e5e7eb;
        margin: 2rem 0 1.5rem;
    }
    .section-title {
        font-size: 1.125rem;
        font-weight: 600;
        color: #1a1a1a;
        margin-bottom: 1.5rem;
    }

    /* Style Tabel konsisten */
    .table-responsive {
        overflow-x: auto;
        border: 1px solid #e5e7eb;
        border-radius: 8px;
        margin-bottom: 2rem;
    }
    .detail-table {
        width: 100%;
        border-collapse: collapse;
        font-size: 0.9375rem;
    }
    .detail-table th,
    .detail-table td {
        padding: 0.875rem 1rem;
        text-align: left;
        border-bottom: 1px solid #e5e7eb;
        vertical-align: middle;
    }
    .detail-table thead th {
        background-color: #f9fafb;
        font-size: 0.8125rem;
        font-weight: 600;
        color: #4b5563;
        text-transform: uppercase;
        letter-spacing: 0.05em;
    }
    .detail-table tbody tr:hover { background-color: #f9fafb; }
    .detail-table tfoot td {
        background-color: #fafafa;
        font-size: 0.9375rem;
    }
    .detail-table tfoot .total-row td {
        background-color: #f0f9ff;
        font-weight: 600;
    }
    .text-right { text-align: right; }
    .summary-input { font-weight: 500; }
    .total-input {
        font-weight: 600;
        color: #2563eb;
        font-size: 1rem;
    }

    /* Style Tombol Footer & Header */
    .form-footer {
        display: flex;
        gap: 1rem;
        padding-top: 1.5rem;
        border-top: 1px solid #e5e7eb;
    }
    .btn-primary, .btn-secondary, .btn-secondary-outline {
        padding: 0.625rem 1.25rem;
        font-size: 0.875rem;
        font-weight: 500;
        border-radius: 6px;
        cursor: pointer;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        transition: all 0.2s;
    }
    .btn-primary {
        background: #2563eb;
        color: #fff;
        border: 1px solid #2563eb;
    }
    .btn-primary:hover { background: #1e40af; }
    .btn-secondary {
        background: #f3f4f6;
        color: #1f2937;
        border: 1px solid #d1d5db;
    }
    .btn-secondary:hover { background: #e5e7eb; }
    .btn-secondary-outline {
        background: #fff;
        color: #4b5563;
        border: 1px solid #d1d5db;
    }
    .btn-secondary-outline:hover { background: #f9fafb; }

    @media (max-width: 768px) {
        .form-row { grid-template-columns: 1fr; }
        .form-card { padding: 1.5rem; }
    }
</style>