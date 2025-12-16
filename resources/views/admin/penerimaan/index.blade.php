@extends('layouts.admin')

@section('title', 'Data Penerimaan')
@section('page-title', 'Data Penerimaan')
@section('breadcrumb', '/ Data Penerimaan')

@section('content')
    <div class="card">
        <div class="card-header-container">
            <h2 class="card-title-internal">Riwayat Penerimaan Barang</h2>
            <a href="{{ route('admin.penerimaan.create') }}" class="btn-primary">
                <i class="fas fa-plus"></i> &nbsp; Terima Barang Baru
            </a>
        </div>
        
        <div class="card-body">
            <div class="table-responsive">
                <table class="table" id="penerimaanTable">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Tanggal</th>
                            <th>ID Pengadaan (PO)</th>
                            <th>Vendor</th>
                            <th>Penerima</th>
                            <th>Jml Item</th>
                            <th class="text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($penerimaan as $item)
                        <tr>
                            <td>{{ $item->idpenerimaan }}</td>
                            <td>{{ date('d M Y, H:i', strtotime($item->created_at)) }}</td>
                            <td>
                                <a href="{{ route('admin.pengadaan.show', $item->idpengadaan) }}" class="text-primary fw-bold">
                                    #{{ $item->idpengadaan }}
                                </a>
                            </td>
                            <td>{{ $item->pengadaan->vendor->nama_vendor ?? '-' }}</td>
                            <td>{{ $item->user->username ?? '-' }}</td>
                            
                            {{-- Menggunakan total_item_diterima dari withSum controller --}}
                            <td>
                                <span class="badge-qty">{{ (int)$item->total_item_diterima }}</span>
                            </td>

                            <td class="aksi-grup">
                                <a href="{{ route('admin.penerimaan.show', $item->idpenerimaan) }}" class="btn-aksi btn-aksi-detail" title="Lihat Detail">
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
    <style>
        /* Menggunakan style yang konsisten dengan halaman lain */
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
        .text-primary { color: #2563eb; }
        .fw-bold { font-weight: 600; }

        .badge-qty {
            background: #eff6ff;
            color: #1d4ed8;
            padding: 2px 8px;
            border-radius: 12px;
            font-weight: 600;
            font-size: 0.8rem;
            display: inline-block;
            min-width: 24px;
            text-align: center;
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
    </style>
@endpush

@push('scripts')
    <script>
        $(document).ready(function() {
            $('#penerimaanTable').DataTable({
                "order": [[ 0, "desc" ]], // Urutkan ID descending
                "language": {
                    "url": "//cdn.datatables.net/plug-ins/1.13.6/i18n/id.json"
                },
                "columnDefs": [ { "orderable": false, "targets": 6 } ]
            });
        });
    </script>
@endpush