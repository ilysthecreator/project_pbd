@extends('layouts.admin')

@section('title', 'Dashboard')
@section('page-title', 'Dashboard')
@section('breadcrumb', '') {{-- Kosongkan breadcrumb untuk tampilan lebih bersih di dashboard --}}

@section('content')
    <div class="stats-grid">
        <div class="stat-card">
            <div class="stat-content">
                <div class="stat-label">Total Barang</div>
                <div class="stat-value">{{ $summary->total_barang ?? 0 }}</div>
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-content">
                <div class="stat-label">Total Satuan</div>
                <div class="stat-value">{{ $summary->total_satuan ?? 0 }}</div>
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-content">
                <div class="stat-label">Total Vendor</div>
                <div class="stat-value">{{ $summary->total_vendor ?? 0 }}</div>
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-content">
                <div class="stat-label">Total User</div>
                <div class="stat-value">{{ $summary->total_user ?? 0 }}</div>
            </div>
        </div>

        {{-- Card untuk Transaksi --}}
        <div class="stat-card">
            <div class="stat-content">
                <div class="stat-label">Total Penjualan</div>
                <div class="stat-value">{{ $summary->total_penjualan ?? 0 }}</div>
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-content">
                <div class="stat-label">Total Pengadaan</div>
                <div class="stat-value">{{ $summary->total_pengadaan ?? 0 }}</div>
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-content">
                <div class="stat-label">Total Penerimaan</div>
                <div class="stat-value">{{ $summary->total_penerimaan ?? 0 }}</div>
            </div>
        </div>
    </div>

    <div class="quick-actions">
        <h3 class="section-title">Aksi Cepat</h3>
        <div class="actions-grid">
            <a href="{{ route('admin.penjualan.create') }}" class="action-card">
                <div class="action-icon"><i class="fas fa-cash-register"></i></div>
                <div class="action-title">Buat Transaksi Penjualan</div>
                <div class="action-subtitle">Buka halaman kasir (POS)</div>
            </a>
            <a href="{{ route('admin.pengadaan.create') }}" class="action-card">
                <div class="action-icon"><i class="fas fa-shopping-cart"></i></div>
                <div class="action-title">Buat Pengadaan</div>
                <div class="action-subtitle">Transaksi pembelian barang</div>
            </a>
            <a href="{{ route('admin.barang.create') }}" class="action-card">
                <div class="action-icon"><i class="fas fa-box-open"></i></div>
                <div class="action-title">Tambah Barang Baru</div>
                <div class="action-subtitle">Input produk atau item baru</div>
            </a>
            <a href="{{ route('admin.vendor.create') }}" class="action-card">
                <div class="action-icon"><i class="fas fa-building"></i></div>
                <div class="action-title">Tambah Vendor Baru</div>
                <div class="action-subtitle">Daftarkan mitra atau supplier</div>
            </a>
        </div>
    </div>

    <div class="activity-container">
        <h3 class="section-title">Aktivitas Terbaru</h3>
        <div class="activity-grid">
            <div class="activity-card">
                <div class="activity-header">
                    <h4>Barang Terbaru Ditambahkan</h4>
                    <a href="{{ route('admin.barang.index') }}" class="view-all">Lihat Semua</a>
                </div>
                <div class="activity-list">
                    @forelse($barangTerbaru as $barang)
                        <div class="activity-item">
                            <div class="activity-content">
                                <div class="activity-name">{{ $barang->nama }}</div>
                                <div class="activity-meta">
                                    <span class="meta-badge">{{ $barang->satuan->nama_satuan ?? 'N/A' }}</span>
                                    <span class="meta-text">• Rp {{ number_format($barang->harga ?? 0, 0, ',', '.') }}</span>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="activity-empty">
                            <span class="empty-text">Belum ada data barang</span>
                        </div>
                    @endforelse
                </div>
            </div>

            <div class="activity-card">
                <div class="activity-header">
                    <h4>Satuan Terbaru Ditambahkan</h4>
                    <a href="{{ route('admin.satuan.index') }}" class="view-all">Lihat Semua</a>
                </div>
                <div class="activity-list">
                    @forelse($satuanTerbaru as $satuan)
                        <div class="activity-item">
                            <div class="activity-content">
                                <div class="activity-name">{{ $satuan->nama_satuan }}</div>
                                <div class="activity-meta">
                                    <span class="meta-badge">{{ $satuan->jumlah_barang ?? 0 }} barang terkait</span>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="activity-empty">
                            <span class="empty-text">Belum ada data satuan</span>
                        </div>
                    @endforelse
                </div>
            </div>

            <div class="activity-card">
                <div class="activity-header">
                    <h4>Vendor Terbaru Ditambahkan</h4>
                    <a href="{{ route('admin.vendor.index') }}" class="view-all">Lihat Semua</a>
                </div>
                <div class="activity-list">
                    @forelse($vendorTerbaru as $vendor)
                        <div class="activity-item">
                            <div class="activity-content">
                                <div class="activity-name">{{ $vendor->nama_vendor }}</div>
                                <div class="activity-meta">
                                    <span class="status-badge status-{{ $vendor->status == 1 ? 'active' : 'inactive' }}">
                                        {{ $vendor->status == 1 ? 'Aktif' : 'Nonaktif' }}
                                    </span>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="activity-empty">
                            <span class="empty-text">Belum ada data vendor</span>
                        </div>
                    @endforelse
                </div>
            </div>

            <div class="activity-card">
                <div class="activity-header">
                    <h4>User Terbaru Ditambahkan</h4>
                    <a href="{{ route('admin.user.index') }}" class="view-all">Lihat Semua</a>
                </div>
                <div class="activity-list">
                    @forelse($userTerbaru as $user)
                        <div class="activity-item">
                            <div class="activity-content">
                                <div class="activity-name">{{ $user->username }}</div>
                                <div class="activity-meta">
                                    <span class="status-badge status-{{ $user->iduser == 1 ? 'active' : 'inactive' }}">
                                        {{ $user->iduser == 1 ? 'Super Admin' : 'Admin' }}
                                    </span>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="activity-empty">
                            <span class="empty-text">Belum ada data user</span>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
@endsection

@push('styles')
<style>
    /* ... (Salin semua style CSS Anda dari file dashboard.blade.php sebelumnya) ... */

    /* Stats Grid */
    .stats-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 1.5rem;
        margin-bottom: 2rem;
    }

    .stat-card {
        background: #fff;
        padding: 1.25rem;
        border-radius: 8px;
        border: 1px solid #e5e7eb;
        transition: box-shadow 0.2s ease;
        box-shadow: 0 1px 3px rgba(0,0,0,0.05);
    }
    
    .stat-card:hover {
        box-shadow: 0 4px 12px rgba(0,0,0,0.08);
    }

    .stat-content {
        flex: 1;
    }

    .stat-label {
        font-size: 0.875rem;
        color: #6b7280;
        margin-bottom: 0.25rem;
        font-weight: 500;
    }

    .stat-value {
        font-size: 1.75rem;
        font-weight: 600;
        color: #1a1a1a;
        line-height: 1;
    }

    /* Section Title */
    .section-title {
        font-size: 1.125rem;
        font-weight: 600;
        color: #1a1a1a;
        margin-bottom: 1rem;
        padding-bottom: 0.5rem;
        border-bottom: 1px solid #e5e7eb;
    }

    /* Quick Actions */
    .quick-actions {
        margin-bottom: 2rem;
    }

    .actions-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 1rem;
    }

    .action-card {
        background: #fff;
        padding: 1rem;
        border-radius: 8px;
        border: 1px solid #e5e7eb;
        text-align: left;
        transition: all 0.2s ease;
        cursor: pointer;
        text-decoration: none;
        color: inherit;
    }

    .action-card:hover {
        border-color: #2563eb; /* Warna primer dari layout utama */
        background: #f9fafb;
    }

    /* [UPDATE] Style untuk ikon Font Awesome */
    .action-icon {
        font-size: 1.75rem;
        color: #2563eb;
        margin-bottom: 0.75rem;
    }
    .action-card:hover .action-icon {
        color: #1e40af;
    }
    /* ================================== */

    .action-title {
        font-size: 0.9375rem;
        font-weight: 600;
        color: #1a1a1a;
        margin-bottom: 0.25rem;
    }

    .action-subtitle {
        font-size: 0.8125rem;
        color: #6b7280;
    }

    /* Activity Container */
    .activity-container {
        margin-bottom: 2rem;
    }

    .activity-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
        gap: 1.5rem;
    }

    .activity-card {
        background: #fff;
        border-radius: 8px;
        border: 1px solid #e5e7eb;
        overflow: hidden;
    }

    .activity-header {
        padding: 1rem 1.25rem;
        border-bottom: 1px solid #e5e7eb;
        display: flex;
        justify-content: space-between;
        align-items: center;
        background: #fff;
    }

    .activity-header h4 {
        margin: 0;
        font-size: 1rem;
        font-weight: 600;
        color: #1a1a1a;
    }

    .view-all {
        font-size: 0.875rem;
        color: #2563eb;
        text-decoration: none;
        font-weight: 500;
        transition: color 0.2s;
    }

    .view-all:hover {
        color: #1e40af;
    }

    .activity-list {
        padding: 0 0.5rem 0.5rem;
    }

    .activity-item {
        display: flex;
        align-items: flex-start;
        padding: 0.75rem;
        border-radius: 6px;
        transition: background 0.2s;
    }

    .activity-item:hover {
        background: #f9fafb;
    }

    .activity-content {
        flex: 1;
        min-width: 0;
    }

    .activity-name {
        font-size: 0.875rem;
        font-weight: 500;
        color: #1a1a1a;
        margin-bottom: 0.25rem;
        overflow: hidden;
        text-overflow: ellipsis;
        white-space: nowrap;
    }

    .activity-meta {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        font-size: 0.75rem;
        color: #6b7280;
    }

    .meta-badge {
        background: #f3f4f6;
        padding: 0.125rem 0.5rem;
        border-radius: 4px;
        font-weight: 500;
    }

    .meta-text {
        color: #6b7280;
    }

    .status-badge {
        padding: 0.25rem 0.625rem;
        border-radius: 12px;
        font-size: 0.75rem;
        font-weight: 600;
    }

    .status-badge.status-active {
        background: #dcfce7;
        color: #166534;
    }

    .status-badge.status-inactive {
        background: #fee2e2;
        color: #991b1b;
    }

    /* Empty State */
    .activity-empty {
        padding: 2rem 1.5rem;
        text-align: center;
    }

    .empty-text {
        font-size: 0.875rem;
        color: #9ca3af;
    }

    /* Responsive */
    @media (max-width: 768px) {
        .stats-grid,
        .actions-grid,
        .activity-grid {
            grid-template-columns: 1fr;
        }
    }
</style>
@endpush    