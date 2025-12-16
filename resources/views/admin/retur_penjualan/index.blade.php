@extends('layouts.admin')

@section('title', 'Data Retur Penjualan')
@section('page-title', 'Data Retur Penjualan')
@section('breadcrumb', '/ Data Retur')

@section('content')
    <div class="card">
        <div class="card-header-container">
            <h2 class="card-title-internal">Daftar Retur Penjualan</h2>
            <a href="{{ route('admin.retur_penjualan.create') }}" class="btn-primary">
                <i class="fas fa-plus"></i> &nbsp; Buat Retur
            </a>
        </div>
        
        <div class="card-body">
            <div class="table-responsive">
                <table class="table" id="returTable">
                    <thead>
                        <tr>
                            <th>ID Retur</th>
                            <th>Tanggal</th>
                            <th>ID Penjualan</th>
                            <th>User</th>
                            <th>Total Item</th>
                            <th>Total Nilai</th>
                            <th style="width: 10%;" class="text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($returPenjualan as $item)
                        <tr>
                            <td>#{{ $item->idretur }}</td>
                            <td>{{ date('d M Y, H:i', strtotime($item->created_at)) }}</td>
                            <td>
                                <a href="{{ route('admin.penjualan.show', $item->idpenjualan) }}" title="Lihat Penjualan">
                                    #{{ $item->idpenjualan }}
                                </a>
                            </td>
                            <td>{{ $item->user->username ?? 'N/A' }}</td>
                            <td>{{ $item->total_item_diretur }} item</td>
                            <td class="text-right">Rp {{ number_format($item->total_nilai_retur, 0, ',', '.') }}</td>
                            <td class="aksi-grup">
                                <a href="{{ route('admin.retur_penjualan.show', $item->idretur) }}" class="btn-aksi btn-aksi-detail" title="Detail">
                                    <i class="fas fa-eye"></i>
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
    {{-- Menggunakan style yang mirip dengan halaman index lainnya --}}
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap4.min.css">
    <style>
        .card-header-container { display: flex; justify-content: space-between; align-items: center; padding: 1.25rem 1.5rem; border-bottom: 1px solid #e5e7eb; }
        .card-title-internal { font-size: 1.25rem; font-weight: 600; color: #1a1a1a; margin: 0; }
        .btn-primary { display: inline-flex; align-items: center; background: #2563eb; color: #fff; padding: 0.625rem 1rem; border-radius: 6px; font-weight: 500; font-size: 0.875rem; text-decoration: none; transition: background 0.2s; }
        .btn-primary:hover { background: #1e40af; }
        .card { background: #fff; border: 1px solid #e5e7eb; border-radius: 8px; overflow: hidden; box-shadow: 0 1px 3px rgba(0,0,0,0.05); }
        .card-body { padding: 1.5rem; }
        .table-responsive { width: 100%; overflow-x: auto; }
        table.dataTable { width: 100% !important; border-collapse: collapse !important; }
        table.dataTable th { text-align: left; padding: 0.75rem 1rem; background-color: #f9fafb; border-bottom: 2px solid #e5e7eb; font-size: 0.8125rem; font-weight: 600; color: #4b5563; text-transform: uppercase; letter-spacing: 0.05em; }
        table.dataTable td { padding: 0.75rem 1rem; border-bottom: 1px solid #e5e7eb; font-size: 0.875rem; color: #374151; vertical-align: middle; }
        table.dataTable tbody tr:last-child td { border-bottom: none; }
        table.dataTable tbody tr:hover { background-color: #f9fafb; }
        .text-center { text-align: center !important; }
        .text-right { text-align: right !important; }
        table.dataTable td a { color: #2563eb; text-decoration: none; font-weight: 500; }
        table.dataTable td a:hover { text-decoration: underline; }
        .aksi-grup { display: flex; gap: 0.5rem; justify-content: center; }
        .btn-aksi { display: inline-flex; align-items: center; justify-content: center; width: 32px; height: 32px; font-size: 0.875rem; border-radius: 6px; text-decoration: none; transition: all 0.2s ease; }
        .btn-aksi.btn-aksi-detail { color: #2563eb; background-color: #eff6ff; border: 1px solid #dbeafe; }
        .btn-aksi.btn-aksi-detail:hover { background-color: #dbeafe; color: #1e40af; }
    </style>
@endpush

@push('scripts')
    <script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap4.min.js"></script>
    <script>
        $(document).ready(function() {
            $('#returTable').DataTable({
                "order": [[ 0, "desc" ]],
                "language": {
                    "lengthMenu": "Tampil _MENU_ data",
                    "search": "Cari:",
                    "info": "Menampilkan _START_ - _END_ dari _TOTAL_ data",
                    "paginate": {
                        "previous": "Sebelum",
                        "next": "Berikut"
                    }
                },
                "columnDefs": [
                    { "orderable": false, "targets": 6 }, // Aksi
                    { "className": "text-right", "targets": 5 }, // Total Nilai
                    { "className": "text-center", "targets": 6 } // Aksi
                ]
            });
        });
    </script>
@endpush