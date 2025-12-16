@extends('layouts.admin')

@section('title', 'Data Barang')
@section('page-title', 'Daftar Barang')
@section('breadcrumb', '/ Data Master / Barang')

@section('content')
    <div class="card">
        <div class="card-header-container">
            <h2 class="card-title-internal">Daftar Barang</h2>
            <a href="{{ route('admin.barang.create') }}" class="btn-primary" title="Tambah Barang Baru">
                <i class="fas fa-plus"></i> &nbsp; Tambah Barang
            </a>
        </div>
        
        <div class="card-body">
            <div class="filter-search-container">
                <div class="filter-group">
                    <a href="{{ route('admin.barang.index') }}" 
                       class="btn-filter {{ !$status ? 'active' : '' }}">Semua</a>
                    <a href="{{ route('admin.barang.index', ['status' => 'aktif']) }}" 
                       class="btn-filter {{ $status == 'aktif' ? 'active' : '' }}">Aktif</a>
                    <a href="{{ route('admin.barang.index', ['status' => 'tidak-aktif']) }}" 
                       class="btn-filter {{ $status == 'tidak-aktif' ? 'active' : '' }}">Tidak Aktif</a>
                </div>
                <form action="{{ route('admin.barang.index') }}" method="GET" class="search-form">
                    <input type="hidden" name="status" value="{{ $status }}">
                    <input type="text" name="search" class="search-input" placeholder="Cari nama barang..." value="{{ $search ?? '' }}">
                    <button type="submit" class="search-button">
                        <i class="fas fa-search"></i>
                    </button>
                </form>
            </div>

            <div class="table-responsive">
                <table class="table" id="barangTable">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Nama Barang</th>
                            <th>Jenis</th>
                            <th>Satuan</th>
                            <th class="text-right">Harga</th>
                            <th class="text-center">Stok</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($barang as $b)
                        <tr>
                            <td>{{ $b->idbarang }}</td>
                            <td>{{ $b->nama }}</td>
                            <td>
                                <span class="badge badge-{{ $b->jenis == 'B' ? 'info' : 'warning' }}">
                                    {{ $b->jenis_lengkap }}
                                </span>
                            </td>
                            <td>{{ $b->satuan->nama_satuan ?? 'N/A' }}</td>
                            <td class="text-right">{{ $b->harga_format }}</td>
                            <td class="text-center">{{ $b->stok }}</td>
                            <td>
                                <span class="status-badge status-{{ $b->status == 1 ? 'active' : 'inactive' }}">
                                    {{ $b->status_text }}
                                </span>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" class="text-center">Tidak ada data barang yang cocok.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="mt-4">
                {{ $barang->appends(request()->query())->links() }}
            </div>
        </div>
    </div>
@endsection

@push('styles')
{{-- Menggunakan style yang sama dengan halaman index lainnya untuk konsistensi --}}
<style>
    .card-header-container { display: flex; justify-content: space-between; align-items: center; padding: 1.25rem 1.5rem; border-bottom: 1px solid #e5e7eb; }
    .card-title-internal { font-size: 1.25rem; font-weight: 600; color: #1a1a1a; margin: 0; }
    .btn-primary { display: inline-flex; align-items: center; background: #2563eb; color: #fff; padding: 0.625rem 1rem; border-radius: 6px; font-weight: 500; font-size: 0.875rem; text-decoration: none; transition: background 0.2s; }
    .btn-primary:hover { background: #1e40af; }
    .card { background: #fff; border: 1px solid #e5e7eb; border-radius: 8px; overflow: hidden; box-shadow: 0 1px 3px rgba(0,0,0,0.05); }
    .card-body { padding: 1.5rem; }
    .table-responsive { width: 100%; overflow-x: auto; }
    .table { width: 100%; border-collapse: collapse; }
    .table th, .table td { padding: 0.875rem 1rem; border-bottom: 1px solid #e5e7eb; font-size: 0.875rem; vertical-align: middle; }
    .table th { text-align: left; background-color: #f9fafb; font-weight: 600; color: #4b5563; text-transform: uppercase; letter-spacing: 0.05em; }
    .table tbody tr:last-child td { border-bottom: none; }
    .table tbody tr:hover { background-color: #f9fafb; }
    .text-center { text-align: center; }
    .text-right { text-align: right; }
    .mt-4 { margin-top: 1.5rem; }

    .filter-search-container { display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.5rem; flex-wrap: wrap; gap: 1rem; }
    .filter-group { display: inline-flex; gap: 0.25rem; background-color: #e5e7eb; border-radius: 8px; padding: 0.25rem; }
    .btn-filter { padding: 0.5rem 1rem; font-size: 0.875rem; font-weight: 500; border-radius: 6px; cursor: pointer; text-decoration: none; color: #374151; transition: all 0.2s; }
    .btn-filter:hover { background-color: #d1d5db; color: #1f2937; }
    .btn-filter.active { background-color: #fff; color: #111827; box-shadow: 0 1px 3px rgba(0,0,0,0.1); }

    .search-form { display: flex; align-items: center; }
    .search-input { border: 1px solid #d1d5db; border-radius: 6px 0 0 6px; padding: 0.5rem 0.75rem; font-size: 0.875rem; transition: border-color 0.2s, box-shadow 0.2s; }
    .search-input:focus { outline: none; border-color: #2563eb; box-shadow: 0 0 0 2px rgba(37, 99, 235, 0.2); }
    .search-button { background-color: #f3f4f6; border: 1px solid #d1d5db; border-left: none; padding: 0.5rem 0.75rem; border-radius: 0 6px 6px 0; cursor: pointer; color: #4b5563; transition: background-color 0.2s; }
    .search-button:hover { background-color: #e5e7eb; }

    .status-badge { display: inline-block; padding: 0.25rem 0.625rem; border-radius: 12px; font-size: 0.75rem; font-weight: 600; }
    .status-badge.status-active { background: #dcfce7; color: #166534; }
    .status-badge.status-inactive { background: #fee2e2; color: #991b1b; }

    .badge { display: inline-block; padding: 0.25em 0.6em; font-size: 0.8125rem; font-weight: 500; line-height: 1; text-align: center; white-space: nowrap; vertical-align: baseline; border-radius: 0.25rem; }
    .badge-info { color: #1e40af; background-color: #dbeafe; }
    .badge-warning { color: #9a3412; background-color: #ffedd5; }

    .aksi-grup { display: flex; gap: 0.5rem; justify-content: center; }
    .btn-aksi { display: inline-flex; align-items: center; justify-content: center; width: 32px; height: 32px; font-size: 0.875rem; border-radius: 6px; text-decoration: none; transition: all 0.2s ease; border: 1px solid transparent; }
    .btn-aksi.btn-aksi-edit { color: #ca8a04; background-color: #fefce8; border-color: #fef08a; }
    .btn-aksi.btn-aksi-edit:hover { background-color: #fef9c3; color: #a16207; }
    .btn-aksi.btn-aksi-delete { color: #dc2626; background-color: #fee2e2; border-color: #fecaca; }
    .btn-aksi.btn-aksi-delete:hover { background-color: #fecaca; color: #b91c1c; }

    /* Pagination Styles */
    .pagination { display: flex; padding-left: 0; list-style: none; }
    .page-item .page-link { position: relative; display: block; padding: .5rem .75rem; margin-left: -1px; line-height: 1.25; color: #2563eb; background-color: #fff; border: 1px solid #dee2e6; }
    .page-item.active .page-link { z-index: 3; color: #fff; background-color: #2563eb; border-color: #2563eb; }
    .page-item.disabled .page-link { color: #6c757d; pointer-events: none; background-color: #fff; border-color: #dee2e6; }
    .page-item:first-child .page-link { border-top-left-radius: .25rem; border-bottom-left-radius: .25rem; }
    .page-item:last-child .page-link { border-top-right-radius: .25rem; border-bottom-right-radius: .25rem; }
</style>
@endpush