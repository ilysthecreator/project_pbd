@extends('layouts.admin')

@section('title', 'Data Vendor')
@section('page-title', 'Daftar Vendor')
@section('breadcrumb', '/ Data Master / Vendor')

@section('content')
    <div class="card">
        <div class="card-header-container">
            <h2 class="card-title-internal">Daftar Vendor</h2>
            <a href="{{ route('admin.vendor.create') }}" class="btn-primary" title="Tambah Vendor Baru">
                <i class="fas fa-plus"></i> &nbsp; Tambah Vendor
            </a>
        </div>
        
        <div class="card-body">
            <div class="filter-container">
                <div class="filter-group">
                    <a href="{{ route('admin.vendor.index') }}" 
                       class="btn-filter {{ !$status ? 'active' : '' }}">Semua</a>
                    <a href="{{ route('admin.vendor.index', ['status' => 'aktif']) }}" 
                       class="btn-filter {{ $status == 'aktif' ? 'active' : '' }}">Aktif</a>
                    <a href="{{ route('admin.vendor.index', ['status' => 'tidak-aktif']) }}" 
                       class="btn-filter {{ $status == 'tidak-aktif' ? 'active' : '' }}">Tidak Aktif</a>
                </div>
            </div>

            <div class="table-responsive">
                <table class="table" id="vendorTable">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Nama Vendor</th>
                            <th>Badan Hukum</th>
                            <th>Status</th>
                            <th class="text-center" style="width: 15%;">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($vendor as $v)
                        <tr>
                            <td>{{ $v->idvendor }}</td>
                            <td>{{ $v->nama_vendor }}</td>
                            <td>{{ $v->badan_hukum_text }}</td>
                            <td>
                                @if($v->status == '1')
                                    <span class="status-badge status-active">Aktif</span>
                                @else 
                                    <span class="status-badge status-inactive">Tidak Aktif</span>
                                @endif
                            </td>
                            <td class="aksi-grup">
                                <a href="{{ route('admin.vendor.edit', $v->idvendor) }}" class="btn-aksi btn-aksi-edit" title="Edit">
                                    <i class="fas fa-pencil-alt"></i>
                                </a>
                                <form action="{{ route('admin.vendor.destroy', $v->idvendor) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus vendor ini?')" style="display:inline;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn-aksi btn-aksi-delete" title="Hapus">
                                        <i class="fas fa-trash-alt"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="text-center">Tidak ada data vendor yang cocok.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="mt-4">
                {{ $vendor->links() }}
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
    .table { width: 100%; border-collapse: collapse; }
    .table th, .table td { padding: 0.875rem 1rem; border-bottom: 1px solid #e5e7eb; font-size: 0.875rem; vertical-align: middle; }
    .table th { text-align: left; background-color: #f9fafb; font-weight: 600; color: #4b5563; text-transform: uppercase; letter-spacing: 0.05em; }
    .table tbody tr:last-child td { border-bottom: none; }
    .table tbody tr:hover { background-color: #f9fafb; }
    .text-center { text-align: center; }
    .mt-4 { margin-top: 1.5rem; }

    .filter-container { margin-bottom: 1.5rem; }
    .filter-group { display: inline-flex; gap: 0.25rem; background-color: #e5e7eb; border-radius: 8px; padding: 0.25rem; }
    .btn-filter {
        padding: 0.5rem 1rem;
        font-size: 0.875rem;
        font-weight: 500;
        border-radius: 6px;
        cursor: pointer;
        text-decoration: none;
        color: #374151;
        transition: all 0.2s;
    }
    .btn-filter:hover {
        background-color: #d1d5db;
        color: #1f2937;
    }
    .btn-filter.active {
        background-color: #fff;
        color: #111827;
        box-shadow: 0 1px 3px rgba(0,0,0,0.1);
    }

    .status-badge { display: inline-block; padding: 0.25rem 0.625rem; border-radius: 12px; font-size: 0.75rem; font-weight: 600; }
    .status-badge.status-active { background: #dcfce7; color: #166534; }
    .status-badge.status-inactive { background: #fee2e2; color: #991b1b; }

    .aksi-grup { display: flex; gap: 0.5rem; justify-content: center; }
    .btn-aksi { display: inline-flex; align-items: center; justify-content: center; width: 32px; height: 32px; font-size: 0.875rem; border-radius: 6px; text-decoration: none; transition: all 0.2s ease; }
    .btn-aksi.btn-aksi-edit { color: #ca8a04; background-color: #fefce8; border: 1px solid #fef08a; }
    .btn-aksi.btn-aksi-edit:hover { background-color: #fef9c3; color: #a16207; }
    .btn-aksi.btn-aksi-delete { color: #dc2626; background-color: #fee2e2; border: 1px solid #fecaca; }
    .btn-aksi.btn-aksi-delete:hover { background-color: #fecaca; color: #b91c1c; }

    .pagination { display: flex; padding-left: 0; list-style: none; }
    .page-item .page-link { position: relative; display: block; padding: .5rem .75rem; margin-left: -1px; line-height: 1.25; color: #2563eb; background-color: #fff; border: 1px solid #dee2e6; }
    .page-item.active .page-link { z-index: 3; color: #fff; background-color: #2563eb; border-color: #2563eb; }
    .page-item.disabled .page-link { color: #6c757d; pointer-events: none; background-color: #fff; border-color: #dee2e6; }
    .page-item:first-child .page-link { border-top-left-radius: .25rem; border-bottom-left-radius: .25rem; }
    .page-item:last-child .page-link { border-top-right-radius: .25rem; border-bottom-right-radius: .25rem; }
</style>
@endpush
