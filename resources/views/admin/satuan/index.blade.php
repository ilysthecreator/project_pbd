@extends('layouts.admin')

@section('title', 'Data Satuan')
@section('page-title', 'Daftar Satuan')
@section('breadcrumb', '/ Data Master / Satuan')

@section('content')
    <div class="card">
        <div class="card-header-container">
            <h2 class="card-title-internal">Daftar Satuan</h2>
            <a href="{{ route('admin.satuan.create') }}" class="btn-primary" title="Tambah Satuan Baru">
                <i class="fas fa-plus"></i> &nbsp; Tambah Satuan
            </a>
        </div>
        
        <div class="card-body">
            <div class="filter-search-container">
                <div class="filter-group">
                    <a href="{{ route('admin.satuan.index') }}" 
                       class="btn-filter {{ !$status ? 'active' : '' }}">Semua</a>
                    <a href="{{ route('admin.satuan.index', ['status' => 'aktif']) }}" 
                       class="btn-filter {{ $status == 'aktif' ? 'active' : '' }}">Aktif</a>
                    <a href="{{ route('admin.satuan.index', ['status' => 'tidak-aktif']) }}" 
                       class="btn-filter {{ $status == 'tidak-aktif' ? 'active' : '' }}">Tidak Aktif</a>
                </div>
                <form action="{{ route('admin.satuan.index') }}" method="GET" class="search-form">
                    <input type="hidden" name="status" value="{{ $status }}">
                    <input type="text" name="search" class="search-input" placeholder="Cari nama satuan..." value="{{ $search ?? '' }}">
                    <button type="submit" class="search-button">
                        <i class="fas fa-search"></i>
                    </button>
                </form>
            </div>

            <div class="table-responsive">
                <table class="table" id="satuanTable">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Nama Satuan</th>
                            <th class="text-center">Jumlah Barang Terkait</th>
                            <th>Status</th>
                            <th class="text-center" style="width: 15%;">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($satuan as $s)
                        <tr>
                            <td>{{ $s->idsatuan }}</td>
                            <td>{{ $s->nama_satuan }}</td>
                            <td class="text-center">{{ $s->jumlah_barang }}</td>
                            <td>
                                <span class="status-badge status-{{ $s->jumlah_barang > 0 ? 'active' : 'inactive' }}">
                                    {{ $s->status_text }}
                                </span>
                            </td>
                            <td class="aksi-grup">
                                <a href="{{ route('admin.satuan.edit', $s->idsatuan) }}" class="btn-aksi btn-aksi-edit" title="Edit">
                                    <i class="fas fa-pencil-alt"></i>
                                </a>
                                @if($s->jumlah_barang == 0)
                                    <form action="{{ route('admin.satuan.destroy', $s->idsatuan) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus satuan ini?')" style="display:inline;">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn-aksi btn-aksi-delete" title="Hapus">
                                            <i class="fas fa-trash-alt"></i>
                                        </button>
                                    </form>
                                @else
                                    <button class="btn-aksi btn-aksi-disabled" disabled title="Tidak bisa dihapus (digunakan oleh {{ $s->jumlah_barang }} barang)">
                                        <i class="fas fa-trash-alt"></i>
                                    </button>
                                @endif
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="text-center">Tidak ada data satuan yang cocok.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="mt-4">
                {{ $satuan->appends(request()->query())->links() }}
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

    .aksi-grup { display: flex; gap: 0.5rem; justify-content: center; }
    .btn-aksi { display: inline-flex; align-items: center; justify-content: center; width: 32px; height: 32px; font-size: 0.875rem; border-radius: 6px; text-decoration: none; transition: all 0.2s ease; border: 1px solid transparent; cursor: pointer; }
    .btn-aksi.btn-aksi-edit { color: #ca8a04; background-color: #fefce8; border-color: #fef08a; }
    .btn-aksi.btn-aksi-edit:hover { background-color: #fef9c3; color: #a16207; }
    .btn-aksi.btn-aksi-delete { color: #dc2626; background-color: #fee2e2; border-color: #fecaca; }
    .btn-aksi.btn-aksi-delete:hover { background-color: #fecaca; color: #b91c1c; }
    .btn-aksi.btn-aksi-disabled { color: #9ca3af; background-color: #f3f4f6; border-color: #e5e7eb; cursor: not-allowed; }

    /* Pagination Styles */
    .pagination { display: flex; padding-left: 0; list-style: none; }
    .page-item .page-link { position: relative; display: block; padding: .5rem .75rem; margin-left: -1px; line-height: 1.25; color: #2563eb; background-color: #fff; border: 1px solid #dee2e6; }
    .page-item.active .page-link { z-index: 3; color: #fff; background-color: #2563eb; border-color: #2563eb; }
    .page-item.disabled .page-link { color: #6c757d; pointer-events: none; background-color: #fff; border-color: #dee2e6; }
    .page-item:first-child .page-link { border-top-left-radius: .25rem; border-bottom-left-radius: .25rem; }
    .page-item:last-child .page-link { border-top-right-radius: .25rem; border-bottom-right-radius: .25rem; }
</style>
@endpush