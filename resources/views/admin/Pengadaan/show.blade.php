@extends('layouts.admin')

@section('title', 'Detail Pengadaan')
@section('page-title', 'Detail Pengadaan')
@section('breadcrumb', '/ Data Pengadaan / Detail')

@section('content')
<div class="detail-container">
    <div class="detail-header">
        <h2 class="detail-title">Detail Pengadaan #{{ $pengadaan->idpengadaan }}</h2>
        <div class="header-actions">
            <a href="{{ route('admin.pengadaan.index') }}" class="btn-secondary">
                <i class="fas fa-arrow-left"></i> &nbsp; Kembali
            </a>
            @if($pengadaan->status == 'P')
                <a href="{{ route('admin.pengadaan.edit', $pengadaan->idpengadaan) }}" class="btn-edit">
                    <i class="fas fa-pencil-alt"></i> &nbsp; Edit
                </a>
            @endif
        </div>
    </div>

    <div class="detail-card">
        <div class="info-grid">
            <div class="info-section">
                <div class="info-item">
                    <span class="info-label">ID Pengadaan</span>
                    <span class="info-value">{{ $pengadaan->idpengadaan }}</span>
                </div>
                <div class="info-item">
                    <span class="info-label">Vendor</span>
                    <span class="info-value">{{ $pengadaan->nama_vendor }}</span>
                </div>
                <div class="info-item">
                    <span class="info-label">Jenis Vendor</span>
                    <span class="info-value">
                        <span class="info-badge {{ $pengadaan->badan_hukum == 'B' ? 'badge-info' : 'badge-warning' }}">
                            {{ $pengadaan->badan_hukum == 'B' ? 'Badan Hukum' : 'Usaha Dagang' }}
                        </span>
                    </span>
                </div>
            </div>

            <div class="info-section">
                <div class="info-item">
                    <span class="info-label">Tanggal Pengadaan</span>
                    <span class="info-value">{{ date('d M Y, H:i', strtotime($pengadaan->created_at)) }}</span>
                </div>
                <div class="info-item">
                    <span class="info-label">User</span>
                    <span class="info-value">{{ $pengadaan->username }}</span>
                </div>
                <div class="info-item">
                    <span class="info-label">Status</span>
                    <span class="info-value">
                        @if($pengadaan->status == 'S')
                            <span class="status-badge status-selesai">Selesai</span>
                        @elseif($pengadaan->status == 'P')
                            <span class="status-badge status-pending">Pending</span>
                        @else
                            <span class="status-badge status-cancel">Cancel</span>
                        @endif
                    </span>
                </div>
            </div>
        </div>

        <div class="divider"></div>
        <h3 class="section-title">Detail Barang</h3>
        
        <div class="table-responsive">
            <table class="detail-table">
                <thead>
                    <tr>
                        <th style="width: 5%;">No</th>
                        <th>Nama Barang</th>
                        <th style="width: 12%;">Satuan</th>
                        <th style="width: 10%; text-align:center;">Jumlah</th>
                        <th style="width: 18%; text-align:right;">Harga Satuan</th>
                        <th style="width: 18%; text-align:right;">Sub Total</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($details as $index => $detail)
                    <tr>
                        <td class="text-center">{{ $index + 1 }}</td>
                        <td>{{ $detail->nama_barang }}</td>
                        <td>{{ $detail->nama_satuan }}</td>
                        <td class="text-center">{{ $detail->jumlah }}</td>
                        <td class="text-right">Rp {{ number_format($detail->harga_satuan, 0, ',', '.') }}</td>
                        <td class="text-right">Rp {{ number_format($detail->sub_total, 0, ',', '.') }}</td>
                    </tr>
                    @endforeach
                </tbody>
                <tfoot>
                    <tr>
                        <td colspan="5" class="text-right"><strong>Subtotal:</strong></td>
                        <td class="text-right"><strong>Rp {{ number_format($pengadaan->subtotal_nilai, 0, ',', '.') }}</strong></td>
                    </tr>
                    <tr>
                        <td colspan="5" class="text-right"><strong>PPN (10%):</strong></td>
                        <td class="text-right"><strong>Rp {{ number_format($pengadaan->ppn, 0, ',', '.') }}</strong></td>
                    </tr>
                    <tr class="total-row">
                        <td colspan="5" class="text-right"><strong>TOTAL:</strong></td>
                        <td class="text-right total-value"><strong>Rp {{ number_format($pengadaan->total_nilai, 0, ',', '.') }}</strong></td>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
    .detail-container {
        max-width: 1400px;
    }
    .detail-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 1.5rem;
    }
    .detail-title {
        font-size: 1.5rem;
        font-weight: 600;
        color: #1a1a1a;
        margin: 0;
    }
    .header-actions {
        display: flex;
        gap: 0.75rem;
    }
    .detail-card {
        background: #fff;
        border: 1px solid #e5e7eb;
        border-radius: 8px;
        padding: 2rem;
        box-shadow: 0 1px 3px rgba(0,0,0,0.05);
    }

    /* Info Grid Style */
    .info-grid {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 2rem;
        margin-bottom: 1.5rem;
    }
    .info-section {
        display: flex;
        flex-direction: column;
        gap: 1.25rem;
    }
    .info-item {
        display: flex;
        flex-direction: column;
        gap: 0.25rem;
    }
    .info-label {
        font-size: 0.875rem;
        font-weight: 500;
        color: #6b7280;
    }
    .info-value {
        font-size: 1rem;
        color: #1a1a1a;
        font-weight: 600;
    }

    /* Badge Styles */
    .status-badge {
        display: inline-block;
        padding: 0.25rem 0.625rem;
        border-radius: 12px;
        font-size: 0.75rem;
        font-weight: 600;
    }
    .status-badge.status-selesai {
        background: #dcfce7; color: #166534;
    }
    .status-badge.status-pending {
        background: #fefce8; color: #854d0e;
    }
    .status-badge.status-cancel {
        background: #fee2e2; color: #991b1b;
    }
    
    .info-badge {
        display: inline-block;
        padding: 0.25em 0.6em;
        font-size: 0.8125rem;
        font-weight: 500;
        border-radius: 6px;
    }
    .info-badge.badge-info { color: #1e40af; background-color: #dbeafe; }
    .info-badge.badge-warning { color: #9a3412; background-color: #ffedd5; }


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

    /* Table Style */
    .table-responsive {
        overflow-x: auto;
        border: 1px solid #e5e7eb;
        border-radius: 8px;
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
    .detail-table tbody tr:last-child td { border-bottom: none; }
    .detail-table thead th {
        background-color: #f9fafb;
        font-size: 0.8125rem;
        font-weight: 600;
        color: #4b5563;
        text-transform: uppercase;
        letter-spacing: 0.05em;
    }
    .detail-table tbody tr:hover {
        background-color: #f9fafb;
    }
    .detail-table tfoot td {
        background-color: #fafafa;
        font-size: 0.9375rem;
        padding: 0.75rem 1rem;
        font-weight: 600;
    }
    .detail-table tfoot .total-row td {
        background-color: #f0f9ff;
        padding: 1rem;
        font-size: 1rem;
    }
    .total-value {
        color: #2563eb;
        font-size: 1.125rem !important;
    }
    .text-right { text-align: right; }
    .text-center { text-align: center; }

    /* Button Styles */
    .btn-secondary, .btn-edit {
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
    .btn-secondary {
        background: #f3f4f6;
        color: #1f2937;
        border: 1px solid #d1d5db;
    }
    .btn-secondary:hover { background: #e5e7eb; }
    .btn-edit {
        background: #fefce8;
        color: #ca8a04;
        border: 1px solid #fef08a;
    }
    .btn-edit:hover { background: #fef9c3; }

    @media (max-width: 768px) {
        .info-grid {
            grid-template-columns: 1fr;
            gap: 1.5rem;
        }
        .detail-card { padding: 1.5rem; }
        .detail-header {
            flex-direction: column;
            align-items: flex-start;
            gap: 1rem;
        }
        .header-actions {
            width: 100%;
            flex-direction: column;
        }
        .header-actions a {
            text-align: center;
        }
    }
</style>
@endpush