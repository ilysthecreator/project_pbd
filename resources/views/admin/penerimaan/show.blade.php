@extends('layouts.admin')

@section('title', 'Detail Penerimaan')
@section('page-title', 'Detail Penerimaan')
@section('breadcrumb', '/ Data Penerimaan / Detail')

@section('content')
<div class="detail-container">
    <div class="detail-header">
        <h2 class="detail-title">Detail Penerimaan #{{ $penerimaan->idpenerimaan }}</h2>
        <div class="header-actions">
            <a href="{{ route('admin.penerimaan.index') }}" class="btn-secondary">
                <i class="fas fa-arrow-left"></i> &nbsp; Kembali
            </a>
            <a href="{{ route('admin.penerimaan.create') }}" class="btn-primary" style="margin-left: 10px; text-decoration:none; padding: 0.625rem 1.25rem; border-radius: 6px; background:#2563eb; color:white; font-size:0.875rem;">
                <i class="fas fa-plus"></i> Buat Baru
            </a>
        </div>
    </div>

    <div class="detail-card">
        <div class="info-grid">
            <div class="info-section">
                <div class="info-item">
                    <span class="info-label">ID Penerimaan</span>
                    <span class="info-value">#{{ $penerimaan->idpenerimaan }}</span>
                </div>
                <div class="info-item">
                    <span class="info-label">Referensi Pengadaan (PO)</span>
                    <span class="info-value">
                        <a href="{{ route('admin.pengadaan.show', $penerimaan->idpengadaan) }}" title="Lihat Detail Pengadaan">
                            #{{ $penerimaan->idpengadaan }}
                        </a>
                    </span>
                </div>
                <div class="info-item">
                    <span class="info-label">Vendor</span>
                    <span class="info-value">{{ $penerimaan->pengadaan->vendor->nama_vendor ?? '-' }}</span>
                </div>
            </div>

            <div class="info-section">
                <div class="info-item">
                    <span class="info-label">Tanggal Diterima</span>
                    <span class="info-value">{{ date('d M Y, H:i', strtotime($penerimaan->created_at)) }}</span>
                </div>
                <div class="info-item">
                    <span class="info-label">Diterima Oleh</span>
                    <span class="info-value">{{ $penerimaan->user->username ?? 'System' }}</span>
                </div>
                <div class="info-item">
                    <span class="info-label">Catatan</span>
                    <span class="info-value" style="font-style: italic; color: #666;">
                        {{ $penerimaan->catatan ?: 'Tidak ada catatan' }}
                    </span>
                </div>
            </div>
        </div>

        <div class="divider"></div>
        <h3 class="section-title">Barang yang Diterima</h3>
        
        <div class="table-responsive">
            <table class="detail-table">
                <thead>
                    <tr>
                        <th style="width: 5%;">No</th>
                        <th>Nama Barang</th>
                        <th style="width: 20%;">Satuan</th>
                        <th style="width: 20%; text-align:center;">Jumlah Masuk</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($penerimaan->details as $index => $detail)
                    <tr>
                        <td class="text-center">{{ $loop->iteration }}</td>
                        <td>
                            <strong>{{ $detail->barang->nama ?? 'Barang Dihapus' }}</strong>
                        </td>
                        <td>{{ $detail->barang->satuan->nama_satuan ?? '-' }}</td>
                        
                        {{-- PERBAIKAN: Menggunakan $detail->jumlah (sesuai DB), bukan jumlah_terima --}}
                        <td class="text-center font-weight-bold">{{ $detail->jumlah }}</td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" class="text-center">Tidak ada detail barang found.</td>
                    </tr>
                    @endforelse
                </tbody>
                <tfoot>
                    <tr class="total-row">
                        <td colspan="3" class="text-right"><strong>Total Item Masuk:</strong></td>
                        <td class="text-center total-value">
                            <strong>{{ $penerimaan->details->sum('jumlah') }}</strong>
                        </td>
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
    .text-right { text-align: right; }
    .text-center { text-align: center; }

    .font-weight-bold { font-weight: 600; }
    .info-value a { color: #2563eb; text-decoration: none; font-weight: 600; }
    .info-value a:hover { text-decoration: underline; }
    .detail-table tfoot .total-row td { background-color: #f0f9ff; padding: 1rem; font-size: 1rem; }
    .total-value { color: #2563eb; font-size: 1.125rem !important; }
</style>
@endpush