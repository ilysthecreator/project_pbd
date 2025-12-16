@extends('layouts.admin')

@section('title', 'Data Penjualan')
@section('page-title', 'Data Penjualan')
@section('breadcrumb', '/ Data Penjualan')

@section('content')
    <div class="card">
        <div class="card-header-container">
            <h2 class="card-title-internal">Daftar Transaksi Penjualan</h2>
            <a href="{{ route('admin.penjualan.create') }}" class="btn-primary">
                <i class="fas fa-plus"></i> &nbsp; Buat Transaksi Baru
            </a>
        </div>
        
        <div class="card-body">
            <div class="table-responsive">
                <table class="table" id="penjualanTable">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Tanggal</th>
                            <th>Pelanggan</th>
                            <th>Kasir</th>
                            <th>Total Nilai</th>
                            <th class="text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($penjualan as $trx)
                        <tr>
                            <td>{{ $trx->idpenjualan }}</td>
                            <td>{{ $trx->created_at->format('d M Y, H:i') }}</td>
                            <td>{{ $trx->nama_pelanggan }}</td>
                            <td>{{ $trx->user->username ?? 'N/A' }}</td>
                            <td class="text-right">Rp {{ number_format($trx->total_nilai, 0, ',', '.') }}</td>
                            <td class="aksi-grup" style="justify-content: center;">
                                <a href="{{ route('admin.penjualan.show', $trx->idpenjualan) }}" class="btn-aksi btn-aksi-detail" title="Lihat Struk">
                                    <i class="fas fa-receipt"></i>
                                </a>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection

@push('styles')
<style>
    .card-header-container { display: flex; justify-content: space-between; align-items: center; padding: 1.25rem 1.5rem; border-bottom: 1px solid #e5e7eb; }
    .card-title-internal { font-size: 1.25rem; font-weight: 600; color: #1a1a1a; margin: 0; }
    .btn-primary { display: inline-flex; align-items: center; background: #2563eb; color: #fff; padding: 0.625rem 1rem; border-radius: 6px; font-weight: 500; font-size: 0.875rem; text-decoration: none; transition: background 0.2s; }
    .btn-primary:hover { background: #1e40af; }
    .card { background: #fff; border: 1px solid #e5e7eb; border-radius: 8px; overflow: hidden; box-shadow: 0 1px 3px rgba(0,0,0,0.05); }
    .card-body { padding: 1.5rem; }
    .table-responsive { width: 100%; overflow-x: auto; }
    table.dataTable { width: 100% !important; border-collapse: collapse !important; }
    table.dataTable th { text-align: left; padding: 0.75rem 1rem; background-color: #f9fafb; border-bottom: 2px solid #e5e7eb; font-size: 0.8125rem; font-weight: 600; color: #4b5563; text-transform: uppercase; }
    table.dataTable td { padding: 0.75rem 1rem; border-bottom: 1px solid #e5e7eb; font-size: 0.875rem; color: #374151; vertical-align: middle; }
    table.dataTable tbody tr:last-child td { border-bottom: none; }
    .text-right { text-align: right; }
    .aksi-grup { display: flex; gap: 0.5rem; }
    .btn-aksi { display: inline-flex; align-items: center; justify-content: center; width: 32px; height: 32px; font-size: 0.875rem; border-radius: 6px; text-decoration: none; transition: all 0.2s; border: none; cursor: pointer; }
    .btn-aksi.btn-aksi-detail { color: #2563eb; background-color: #eff6ff; }
    .btn-aksi.btn-aksi-detail:hover { background-color: #dbeafe; }
    .btn-aksi.btn-aksi-delete { color: #dc2626; background-color: #fee2e2; }
    .btn-aksi.btn-aksi-delete:hover { background-color: #fecaca; }
</style>
@endpush

@push('scripts')
<script>
    $(document).ready(function() {
        $('#penjualanTable').DataTable({
            "order": [[ 0, "desc" ]],
            "language": { /* ... (salin bahasa dari index pengadaan) ... */ }
        });
    });
</script>
@endpush