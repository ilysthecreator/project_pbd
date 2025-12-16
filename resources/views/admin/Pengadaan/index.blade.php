@extends('layouts.admin')

@section('title', 'Data Pengadaan')
@section('page-title', 'Data Pengadaan')
@section('breadcrumb', '/ Data Pengadaan')

@section('content')
    <div class="card">
        <div class="card-header-container">
            <h2 class="card-title-internal">Daftar Pengadaan</h2>
            <a href="{{ route('admin.pengadaan.create') }}" class="btn-primary" title="Tambah Pengadaan Baru">
                <i class="fas fa-plus"></i> &nbsp; Tambah Pengadaan
            </a>
        </div>
        
        <div class="card-body">
            <div class="table-responsive">
                <table class="table" id="pengadaanTable">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Tanggal</th>
                            <th>Vendor</th>
                            <th>User</th>
                            <th>Item</th>
                            <th class="text-right">Total Nilai</th>
                            <th>Status</th>
                            <th class="text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($pengadaan as $item)
                            <tr>
                                <td>{{ $item->idpengadaan }}</td>
                                <td>{{ \Carbon\Carbon::parse($item->created_at)->format('d M Y, H:i') }}</td>
                                <td>{{ $item->vendor->nama_vendor ?? 'N/A' }}</td>
                                <td>{{ $item->user->username ?? 'N/A' }}</td>
                                <td class="text-center">{{ $item->jumlah_item }}</td>
                                <td class="text-right">Rp {{ number_format($item->total_nilai, 0, ',', '.') }}</td>
                                <td class="text-center">
                                    <span class="status-badge status-{{ strtolower($item->status_text) }}">
                                        {{ $item->status_text }}
                                    </span>
                                </td>
                                <td class="aksi-grup">
                                    <a href="{{ route('admin.pengadaan.show', $item->idpengadaan) }}" class="btn-aksi btn-aksi-detail" title="Detail">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    @if ($item->canBeEdited())
                                        <a href="{{ route('admin.pengadaan.edit', $item->idpengadaan) }}" class="btn-aksi btn-aksi-edit" title="Edit">
                                            <i class="fas fa-pencil-alt"></i> 
                                        </a>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="text-center">Tidak ada data pengadaan.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

@endsection

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
    .btn-primary {
        display: inline-flex;
        align-items: center;
        background: #2563eb;
        color: #fff;
        padding: 0.625rem 1rem;
        border-radius: 6px;
        font-weight: 500;
        font-size: 0.875rem;
        text-decoration: none;
        transition: background 0.2s;
    }
    .btn-primary:hover {
        background: #1e40af;
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
    .dataTables_wrapper .row:nth-child(1) {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding-bottom: 1rem;
    }
    .dataTables_wrapper .dataTables_length,
    .dataTables_wrapper .dataTables_filter {
        margin-bottom: 0;
    }
    .dataTables_length label, 
    .dataTables_filter label {
        font-weight: 500;
        color: #374151;
        margin-bottom: 0;
        font-size: 0.875rem;
    }
    .dataTables_length select, 
    .dataTables_filter input {
        border: 1px solid #d1d5db;
        border-radius: 6px;
        padding: 0.5rem;
        margin-left: 0.5rem;
        font-size: 0.875rem;
    }
    .dataTables_filter input {
        min-width: 250px;
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
    table.dataTable tbody tr:hover {
        background-color: #f9fafb;
    }
    .text-center { text-align: center; }
    .text-right {
        text-align: right;
    }
    .status-badge {
        display: inline-block;
        padding: 0.25rem 0.625rem;
        border-radius: 12px;
        font-size: 0.75rem;
        font-weight: 600;
    }
    .status-badge.status-selesai {
        background: #dcfce7;
        color: #166534;
    }
    .status-badge.status-pending {
        background: #fefce8;
        color: #854d0e;
    }
    .status-badge.status-cancel {
        background: #fee2e2;
        color: #991b1b;
    }
    .aksi-grup {
        display: flex;
        gap: 0.5rem;
        justify-content: center;
    }
    .btn-aksi {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        width: 32px;
        height: 32px;
        font-size: 0.875rem;
        border-radius: 6px;
        text-decoration: none;
        transition: all 0.2s ease;
        border: 1px solid transparent;
        cursor: pointer;
    }
    .btn-aksi.btn-aksi-detail {
        color: #2563eb;
        background-color: #eff6ff;
        border: 1px solid #dbeafe;
    }
    .btn-aksi.btn-aksi-detail:hover {
        background-color: #dbeafe;
        color: #1e40af;
    }
    .btn-aksi.btn-aksi-edit {
        color: #ca8a04;
        background-color: #fefce8;
        border: 1px solid #fef08a;
    }
    .btn-aksi.btn-aksi-edit:hover {
        background-color: #fef9c3;
        color: #a16207;
    }
    .btn-aksi.btn-aksi-delete {
        color: #dc2626;
        background-color: #fee2e2;
        border: 1px solid #fecaca;
    }
    .btn-aksi.btn-aksi-delete:hover {
        background-color: #fecaca;
        color: #b91c1c;
    }
    .dataTables_wrapper .row:nth-child(3) {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding-top: 1rem;
    }
    .dataTables_info {
        font-size: 0.875rem;
        color: #6b7280;
    }
    .paginate_button {
        padding: 0.5rem 0.75rem;
        margin-left: 0.25rem;
        border: 1px solid #d1d5db;
        border-radius: 6px;
        color: #374151;
        text-decoration: none;
        cursor: pointer;
    }
    .paginate_button.current,
    .paginate_button:hover {
        background: #2563eb;
        color: #fff;
        border-color: #2563eb;
    }
    .paginate_button.disabled {
        opacity: 0.5;
        cursor: not-allowed;
    }
    .paginate_button.disabled:hover {
        background: transparent;
        color: #9ca3af;
        border-color: #d1d5db;
    }
    @media (max-width: 768px) {
        .card-header-container {
            flex-direction: column;
            align-items: flex-start;
            gap: 1rem;
        }
        .btn-primary {
            width: 100%;
            justify-content: center;
        }
        .dataTables_wrapper .row:nth-child(1) {
            flex-direction: column;
            gap: 1rem;
        }
        .dataTables_filter {
            width: 100%;
        }
        .dataTables_filter input {
            width: 100%;
            min-width: 0;
            margin-left: 0;
        }
    }
</style>
@endpush

@push('scripts')
<script>
    $(document).ready(function() {
        $('#pengadaanTable').DataTable({
            "order": [[ 0, "desc" ]], // Urutkan berdasarkan ID terbaru
            "language": {
                "lengthMenu": "Tampil _MENU_ data",
                "search": "Cari:",
                "info": "Menampilkan _START_ - _END_ dari _TOTAL_ data",
                "infoEmpty": "Tidak ada data",
                "infoFiltered": "(difilter dari _MAX_ total data)",
                "paginate": {
                    "previous": "Sebelum",
                    "next": "Berikut"
                }
            },
            "columnDefs": [
                { "orderable": false, "targets": 7 }
            ]
        });
    });
</script>
@endpush