@extends('layouts.admin')

@section('title', 'Detail Retur Penjualan')
@section('page-title', 'Detail Retur Penjualan')
@section('breadcrumb', '/ Data Retur / Detail')

@section('content')
<div class="detail-container">
    <div class="detail-header">
        <h2 class="detail-title">Detail Retur #{{ $retur->idretur }}</h2>
        <div class="header-actions">
            <a href="{{ route('admin.retur_penjualan.index') }}" class="btn-secondary">
                <i class="fas fa-arrow-left"></i> &nbsp; Kembali
            </a>
        </div>
    </div>

    <div class="detail-card">
        <div class="info-grid">
            <div class="info-section">
                <div class="info-item">
                    <span class="info-label">ID Retur</span>
                    <span class="info-value">#{{ $retur->idretur }}</span>
                </div>
                <div class="info-item">
                    <span class="info-label">ID Penjualan Asal</span>
                    <span class="info-value">
                        <a href="{{ route('admin.penjualan.show', $retur->idpenjualan) }}" title="Lihat Penjualan">
                            #{{ $retur->idpenjualan }}
                        </a>
                    </span>
                </div>
                 <div class="info-item">
                    <span class="info-label">Alasan Retur</span>
                    <span class="info-value">{{ $retur->alasan }}</span>
                </div>
            </div>

            <div class="info-section">
                <div class="info-item">
                    <span class="info-label">Tanggal Retur</span>
                    <span class="info-value">{{ date('d M Y, H:i', strtotime($retur->created_at)) }}</span>
                </div>
                <div class="info-item">
                    <span class="info-label">User Pemroses</span>
                    <span class="info-value">{{ $retur->user->username ?? 'N/A' }}</span>
                </div>
            </div>
        </div>

        <div class="divider"></div>
        <h3 class="section-title">Detail Barang yang Diretur</h3>
        
        <div class="table-responsive">
            <table class="detail-table">
                <thead>
                    <tr>
                        <th style="width: 5%;">No</th>
                        <th>Nama Barang</th>
                        <th style="width: 15%;">Satuan</th>
                        <th style="width: 15%; text-align:center;">Jumlah Diretur</th>
                        <th style="width: 20%; text-align:right;">Harga Satuan</th>
                        <th style="width: 20%; text-align:right;">Subtotal</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($retur->details as $index => $detail)
                    <tr>
                        <td class="text-center">{{ $loop->iteration }}</td>
                        <td>{{ $detail->barang->nama ?? 'N/A' }}</td>
                        <td>{{ $detail->barang->satuan->nama_satuan ?? 'N/A' }}</td>
                        <td class="text-center">{{ $detail->jumlah }}</td>
                        <td class="text-right">Rp {{ number_format($detail->harga_satuan, 0, ',', '.') }}</td>
                        <td class="text-right">Rp {{ number_format($detail->subtotal, 0, ',', '.') }}</td>
                    </tr>
                    @endforeach
                </tbody>
                <tfoot>
                    <tr class="total-row">
                        <td colspan="5" class="text-right"><strong>Total Nilai Retur:</strong></td>
                        <td class="text-right total-value"><strong>Rp {{ number_format($retur->total_nilai_retur, 0, ',', '.') }}</strong></td>
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
        max-width: 900px;
        margin: auto;
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
    .info-value a { color: #2563eb; text-decoration: none; font-weight: 600; }
    .info-value a:hover { text-decoration: underline; }
    .text-right { text-align: right; }
    .text-center { text-align: center; }
    .divider { height: 1px; background: #e5e7eb; margin: 2rem 0 1.5rem; }
    .section-title { font-size: 1.125rem; font-weight: 600; color: #1a1a1a; margin-bottom: 1.5rem; }
    .table-responsive { overflow-x: auto; border: 1px solid #e5e7eb; border-radius: 8px; }
    .detail-table { width: 100%; border-collapse: collapse; font-size: 0.9375rem; }
    .detail-table th, .detail-table td { padding: 0.875rem 1rem; text-align: left; border-bottom: 1px solid #e5e7eb; vertical-align: middle; }
    .detail-table thead th { background-color: #f9fafb; font-size: 0.8125rem; font-weight: 600; color: #4b5563; text-transform: uppercase; letter-spacing: 0.05em; }
    .detail-table tfoot td { background-color: #fafafa; font-size: 0.9375rem; padding: 0.75rem 1rem; font-weight: 600; }
    .detail-table tfoot .total-row td { background-color: #f0f9ff; padding: 1rem; font-size: 1rem; }
    .total-value { color: #2563eb; font-size: 1.125rem !important; }
</style>
@endpush