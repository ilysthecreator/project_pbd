@extends('layouts.admin')

@section('title', 'Laporan Margin Penjualan')
@section('page-title', 'Laporan Margin Penjualan')
@section('breadcrumb', '/ Laporan / Margin Penjualan')

@section('content')
    <div class="card">
        {{-- Header dan Form Filter (TETAP SAMA) --}}
        <div class="card-header">
            <h2 class="card-title-internal">Laporan Margin Penjualan</h2>
        </div>

        <div class="card-body">
            <div class="filter-and-search-container">
                <div class="filter-group">
                    <a href="{{ route('admin.margin_penjualan.index', request()->except('status')) }}"
                       class="btn-filter {{ !$status ? 'active' : '' }}">Semua</a>
                    <a href="{{ route('admin.margin_penjualan.index', array_merge(request()->query(), ['status' => 'aktif'])) }}"
                       class="btn-filter {{ $status == 'aktif' ? 'active' : '' }}">Aktif</a>
                    <a href="{{ route('admin.margin_penjualan.index', array_merge(request()->query(), ['status' => 'tidak-aktif'])) }}"
                       class="btn-filter {{ $status == 'tidak-aktif' ? 'active' : '' }}">Tidak Aktif</a>
                </div>
                <form action="{{ route('admin.margin_penjualan.index') }}" method="GET" class="date-filter-form">
                    <input type="hidden" name="status" value="{{ $status }}">
                    <input type="date" name="start_date" class="filter-input" value="{{ request('start_date') }}">
                    <span class="filter-separator">s/d</span>
                    <input type="date" name="end_date" class="filter-input" value="{{ request('end_date') }}">
                    <button type="submit" class="btn-filter-submit"><i class="fas fa-filter"></i> Filter</button>
                    <a href="{{ route('admin.margin_penjualan.index') }}" class="btn-filter-reset" title="Reset Filter"><i class="fas fa-undo"></i></a>
                </form>
            </div>
        </div>
        
        <div class="card-body">
            <div class="table-responsive">
                <table class="table" id="marginTable">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Persen (%)</th>
                            <th>Status</th>
                            <th>Dibuat Oleh</th>
                            <th>Created At</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($marginData as $data)
                            <tr>
                                {{-- ID --}}
                                <td class="font-weight-bold" style="color: #dc2626;">
                                    {{ $data->idpenjualan }}
                                </td>

                                {{-- PERSEN (%) --}}
                                <td class="text-center">
                                    {{ number_format($data->margin_persen, 0) }}%
                                </td>

                                {{-- STATUS --}}
                                <td>
                                    @if($data->status == 'S') {{-- 'S' dianggap Aktif --}}
                                        <span class="status-badge status-active">Aktif</span>
                                    @else
                                        <span class="status-badge status-inactive">Tidak Aktif</span>
                                    @endif
                                </td>

                                {{-- DIBUAT OLEH --}}
                                <td>
                                    {{ $data->dibuat_oleh ?? 'Admin' }}
                                </td>

                                {{-- CREATED AT --}}
                                <td>
                                    {{ \Carbon\Carbon::parse($data->created_at)->format('d/m/Y H:i') }}
                                </td>

                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center py-4">
                                    <div class="empty-state">
                                        <p class="text-muted">Tidak ada data penjualan yang ditemukan.</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="mt-4">
                {{ $marginData->links() }}
            </div>
        </div>
    </div>
@endsection

@push('styles')
<style>
    /* Style Asli Anda (Tidak Diubah) */
    .card-header { padding: 1.25rem 1.5rem; border-bottom: 1px solid #e5e7eb; }
    .card-title-internal { font-size: 1.25rem; font-weight: 600; color: #1a1a1a; margin: 0; }
    .card { background: #fff; border: 1px solid #e5e7eb; border-radius: 8px; overflow: hidden; box-shadow: 0 1px 3px rgba(0,0,0,0.05); }
    .card-body { padding: 1.5rem; } /* Padding di-reset untuk body pertama */
    .card-body + .card-body { padding-top: 0; } /* Hapus padding atas untuk body kedua */
    .table { width: 100%; border-collapse: collapse; }
    .table th { text-align: left; padding: 0.875rem 1rem; background-color: #f9fafb; border-bottom: 1px solid #e5e7eb; font-size: 0.8125rem; font-weight: 600; color: #4b5563; text-transform: uppercase; letter-spacing: 0.05em; }
    .table td { padding: 0.875rem 1rem; border-bottom: 1px solid #e5e7eb; font-size: 0.875rem; vertical-align: middle; } /* Changed align to middle */
    .table tbody tr:last-child td { border-bottom: none; }
    .table td a { color: #2563eb; text-decoration: none; }
    .table td a:hover { text-decoration: underline; }
    .table tbody tr:hover { background-color: #f9fafb; }

    .text-right { text-align: right; }
    .text-center { text-align: center; }
    .font-weight-bold { font-weight: 600; }
    .text-muted { color: #6b7280; }
    .mt-4 { margin-top: 1.5rem; }

    /* Filter & Search Container */
    .filter-and-search-container { display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 1rem; margin-bottom: 1rem; }
    .filter-group { display: inline-flex; gap: 0.25rem; background-color: #e5e7eb; border-radius: 8px; padding: 0.25rem; }
    .btn-filter { padding: 0.5rem 1rem; font-size: 0.875rem; font-weight: 500; border-radius: 6px; cursor: pointer; text-decoration: none; color: #374151; transition: all 0.2s; }
    .btn-filter:hover { background-color: #d1d5db; color: #1f2937; }
    .btn-filter.active { background-color: #fff; color: #111827; box-shadow: 0 1px 3px rgba(0,0,0,0.1); }

    /* Date Filter Form */
    .date-filter-form { display: flex; align-items: center; gap: 0.75rem; flex-wrap: wrap; }
    .filter-input {
        border: 1px solid #d1d5db;
        border-radius: 6px;
        padding: 0.5rem 0.75rem;
        font-size: 0.875rem;
        transition: border-color 0.2s, box-shadow 0.2s;
    }
    .filter-input:focus { outline: none; border-color: #2563eb; box-shadow: 0 0 0 2px rgba(37, 99, 235, 0.2); }
    .filter-separator { color: #6b7280; font-size: 0.875rem; }
    .btn-filter-submit {
        background: #2563eb;
        color: #fff;
        border: none;
        padding: 0.5rem 1rem;
        border-radius: 6px;
        font-weight: 500;
        font-size: 0.875rem;
        cursor: pointer;
        transition: background 0.2s;
    }
    .btn-filter-submit:hover { background: #1e40af; }
    .btn-filter-reset {
        background: #f3f4f6;
        color: #374151;
        border: 1px solid #d1d5db;
        padding: 0.5rem;
        width: 34px;
        height: 34px;
        border-radius: 6px;
        cursor: pointer;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        transition: background 0.2s;
    }
    .btn-filter-reset:hover { background: #e5e7eb; }

    /* Status Badge */
    .status-badge {
        display: inline-block;
        padding: 0.25rem 0.625rem;
        border-radius: 12px;
        font-size: 0.75rem;
        font-weight: 600;
        text-align: center;
    }    
    .status-active { background: #dcfce7; color: #166534; }
    .status-inactive { background: #fee2e2; color: #991b1b; }


    /* Pagination Styles */
    .pagination { display: flex; padding-left: 0; list-style: none; }
    .page-item .page-link { position: relative; display: block; padding: .5rem .75rem; margin-left: -1px; line-height: 1.25; color: #2563eb; background-color: #fff; border: 1px solid #dee2e6; }
    .page-item.active .page-link { z-index: 3; color: #fff; background-color: #2563eb; border-color: #2563eb; }
    .page-item.disabled .page-link { color: #6c757d; pointer-events: none; background-color: #fff; border-color: #dee2e6; }
    .page-item:first-child .page-link { border-top-left-radius: .25rem; border-bottom-left-radius: .25rem; }
    .page-item:last-child .page-link { border-top-right-radius: .25rem; border-bottom-right-radius: .25rem; }
    
    /* Custom Button Style for Aksi Column */
    .btn-action-custom {
        padding: 4px 10px;
        border-radius: 4px;
        text-decoration: none;
        font-size: 0.75rem;
        font-weight: 500;
        display: inline-block;
    }
    .btn-action-custom:hover { opacity: 0.9; color: white; }
</style>
@endpush