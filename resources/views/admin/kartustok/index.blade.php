@extends('layouts.admin')

@section('title', 'Laporan Kartu Stok')
@section('page-title', 'Laporan Kartu Stok')
@section('breadcrumb', '/ Laporan / Kartu Stok')

@section('content')
    <div class="card">
        <div class="card-header-container">
            <h2 class="card-title-internal">Riwayat Pergerakan Stok</h2>
        </div>
        
        <div class="card-body">
            <div class="table-responsive">
                <table class="table" id="kartuStokTable">
                    <thead>
                        <tr>
                            <th>Waktu</th>
                            <th>Barang</th>
                            <th>Jenis Transaksi</th>
                            <th>ID Transaksi</th>
                            <th class="text-center">Masuk</th>
                            <th class="text-center">Keluar</th>
                            <th class="text-center">Stok Akhir</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($kartuStok as $item)
                        <tr>
                            <td>{{ $item->created_at->format('d M Y, H:i:s') }}</td>
                            <td>
                                {{ $item->barang->nama ?? 'Barang Dihapus' }}
                                <small class="d-block text-muted">{{ $item->barang->satuan->nama_satuan ?? '' }}</small>
                            </td>
                            <td>
                                {{-- UBAH LOGIKA IF DI SINI --}}
                                @if($item->jenis_transaksi == 'P') 
                                    <span class="badge badge-success">Penerimaan</span>
                                @elseif($item->jenis_transaksi == 'J')
                                    <span class="badge badge-danger">Penjualan</span>
                                @elseif($item->jenis_transaksi == 'R')
                                    <span class="badge badge-warning">Retur Penjualan</span>
                                @else
                                    <span class="badge badge-warning">{{ $item->jenis_transaksi_text }}</span>
                                @endif
                            </td>
                            <td>#{{ $item->idtransaksi }}</td>
                            <td class="text-center {{ $item->masuk > 0 ? 'text-success font-weight-bold' : 'text-muted' }}">
                                {{ $item->masuk > 0 ? '+'.$item->masuk : '-' }}
                            </td>
                            <td class="text-center {{ $item->keluar > 0 ? 'text-danger font-weight-bold' : 'text-muted' }}">
                                {{ $item->keluar > 0 ? '-'.$item->keluar : '-' }}
                            </td>
                            <td class="text-center font-weight-bold">{{ $item->stock }}</td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" class="text-center" style="padding: 2rem;">
                                <i class="fas fa-box-open text-muted mb-2" style="font-size: 2rem;"></i>
                                <p class="text-muted mb-0">Belum ada pergerakan stok.</p>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

        </div>
    </div>
@endsection

{{-- PUSH STYLES DAN SCRIPTS TETAP SAMA SEPERTI FILE ANDA --}}
@push('styles')
<style>
    .card-header-container {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 1.25rem 1.5rem;
        border-bottom: 1px solid #e5e7eb;
    }
    .card-title-internal {
        font-size: 1.25rem;
        font-weight: 600;
        color: #1a1a1a;
        margin: 0;
    }
    .card {
        background: #fff;
        border: 1px solid #e5e7eb;
        border-radius: 8px;
        overflow: hidden;
        box-shadow: 0 1px 3px rgba(0,0,0,0.05);
    }
    .card-body {
        padding: 1.5rem;
    }
    .table-responsive {
        width: 100%;
        overflow-x: auto;
    }
    table.dataTable {
        width: 100% !important;
        border-collapse: collapse !important;
    }
    table.dataTable th {
        text-align: left;
        padding: 0.875rem 1rem;
        background-color: #f9fafb;
        border-bottom: 1px solid #e5e7eb;
        font-size: 0.8125rem;
        font-weight: 600;
        color: #4b5563;
        text-transform: uppercase;
        letter-spacing: 0.05em;
    }
    table.dataTable td {
        padding: 0.875rem 1rem;
        border-bottom: 1px solid #e5e7eb;
        font-size: 0.875rem;
        vertical-align: middle;
    }
    table.dataTable tbody tr:last-child td {
        border-bottom: none;
    }
    .text-center { text-align: center; }
    .text-muted { color: #6b7280; }
    .font-weight-bold { font-weight: 600; }
    .text-success { color: #166534; }
    .text-danger { color: #991b1b; }

    .badge {
        display: inline-block;
        padding: 0.25rem 0.625rem;
        border-radius: 12px;
        font-size: 0.75rem;
        font-weight: 600;
    }
    .badge-success { background: #dcfce7; color: #166534; }
    .badge-danger { background: #fee2e2; color: #991b1b; }
    .badge-warning { background: #fefce8; color: #854d0e; }
</style>
@endpush

@push('scripts')
<script>
    $(document).ready(function() {
        $('#kartuStokTable').DataTable({
            "order": [[ 0, "desc" ]], // Urutkan berdasarkan waktu terbaru
            "language": {
                "url": "//cdn.datatables.net/plug-ins/1.13.6/i18n/id.json"
            }
        });
    });
</script>
@endpush