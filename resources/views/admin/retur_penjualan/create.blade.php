@extends('layouts.admin')

@section('title', 'Buat Retur Penjualan')
@section('page-title', 'Buat Retur Penjualan')
@section('breadcrumb', '/ Data Retur / Buat')

@section('content')
<div class="form-container">
    <div class="form-header">
        <h2 class="form-title">Form Retur Penjualan</h2>
        <a href="{{ route('admin.retur_penjualan.index') }}" class="btn-secondary">
            <i class="fas fa-arrow-left"></i> &nbsp; Kembali
        </a>
    </div>

    <form action="{{ route('admin.retur_penjualan.store') }}" method="POST" id="formRetur">
        @csrf
        
        <div class="form-card">
            @if(session('error'))
                <div class="alert alert-danger">
                    <i class="fas fa-exclamation-triangle"></i> &nbsp; {{ session('error') }}
                </div>
            @endif

            <div class="form-row">
                <div class="form-group">
                    <label for="idpenjualan">Pilih Penjualan <span class="text-danger">*</span></label>
                    <select name="idpenjualan" id="idpenjualan" class="form-control select2-basic @error('idpenjualan') is-invalid @enderror" required>
                        <option value="">-- Pilih ID Penjualan --</option>
                        @foreach($penjualanList as $p)
                            {{-- 
                              PERBAIKAN: Menghapus $p->nama_pelanggan karena tidak ada di tabel
                            --}}
                            <option value="{{ $p->idpenjualan }}" {{ old('idpenjualan') == $p->idpenjualan ? 'selected' : '' }}>
                                #{{ $p->idpenjualan }} ({{ date('d M Y', strtotime($p->created_at)) }})
                            </option>
                        @endforeach
                    </select>
                    @error('idpenjualan')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="form-group">
                    <label for="alasan">Alasan Retur <span class="text-danger">*</span></label>
                    <input type="text" name="alasan" id="alasan" class="form-control @error('alasan') is-invalid @enderror" value="{{ old('alasan') }}" required>
                     @error('alasan')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <div class="divider"></div>
            <h3 class="section-title">Detail Barang Diretur</h3>
            
            <div id="detail-section" style="display: none;">
                <div class="table-responsive">
                    <table class="detail-table" id="tableBarang">
                        <thead>
                            <tr>
                                <th>Nama Barang</th>
                                <th style="width: 15%;">Satuan</th>
                                <th style="width: 15%;" class="text-center">Dibeli</th>
                                <th style="width: 18%;" class="text-center">Jumlah Diretur</th>
                            </tr>
                        </thead>
                        <tbody id="bodyBarang">
                            {{-- Baris akan di-generate oleh JavaScript --}}
                        </tbody>
                    </table>
                </div>

                <div class="form-footer">
                    <button type="submit" class="btn-primary">
                        <i class="fas fa-save"></i> &nbsp; Simpan Retur
                    </button>
                    <a href="{{ route('admin.retur_penjualan.index') }}" class="btn-secondary-outline">Batal</a>
                </div>
            </div>

            <div id="placeholder-section" class="text-center" style="padding: 3rem 0;">
                <i class="fas fa-info-circle" style="font-size: 2rem; color: #6b7280;"></i>
                <p style="margin-top: 1rem; color: #6b7280;">Pilih ID Penjualan untuk menampilkan detail barang.</p>
            </div>
        </div>
    </form>
</div>
@endsection

@push('styles')
{{-- (CSS Anda sudah benar, tidak perlu diubah) --}}
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<style>
    .form-container {
        /* max-width: 1400px; */
    }
    .form-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 1.5rem;
    }
    .form-title {
        font-size: 1.5rem;
        font-weight: 600;
        color: #1a1a1a;
        margin: 0;
    }
    .form-card {
        background: #fff;
        border: 1px solid #e5e7eb;
        border-radius: 8px;
        padding: 2rem;
        box-shadow: 0 1px 3px rgba(0,0,0,0.05);
    }
    .form-row {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 1.5rem;
        margin-bottom: 1.5rem;
    }
    .form-group {
        display: flex;
        flex-direction: column;
    }
    .form-group label {
        display: block;
        font-size: 0.875rem;
        font-weight: 500;
        color: #374151;
        margin-bottom: 0.5rem;
    }
    .form-control {
        width: 100%;
        padding: 0.625rem 0.875rem;
        border: 1px solid #d1d5db;
        border-radius: 6px; /* Konsisten */
        font-size: 0.9375rem;
        transition: border-color 0.2s;
        height: 40px;
    }
    .form-control:focus {
        outline: none;
        border-color: #2563eb;
    }
    .form-control:read-only {
        background-color: #f9fafb;
        cursor: not-allowed;
    }
    .is-invalid { border-color: #ef4444; }
    .invalid-feedback {
        color: #ef4444;
        font-size: 0.8125rem;
        margin-top: 0.25rem;
    }
    .text-danger { color: #ef4444; }
    .text-center { text-align: center; }

    .divider {
        height: 1px;
        background: #e5e7eb;
        margin: 2rem 0 1.5rem;
    }
    .section-title {
        font-size: 1.125rem;
        font-weight: 600;
        color: #1a1a1a;
        margin-bottom: 1.5rem;
    }

    .input-retur { text-align: center; font-weight: bold; }
    /* Style Tabel konsisten */
    .table-responsive {
        overflow-x: auto;
        border: 1px solid #e5e7eb;
        border-radius: 8px;
        margin-bottom: 2rem;
    }
    .detail-table {
        width: 100%;
        border-collapse: collapse;
        font-size: 0.9375rem;
    }
    .detail-table th,
    .detail-table td {
        padding: 0.875rem 1rem;
        text-align: left;
        border-bottom: 1px solid #e5e7eb;
        vertical-align: middle;
    }
    .detail-table thead th {
        background-color: #f9fafb;
        font-size: 0.8125rem;
        font-weight: 600;
        color: #4b5563;
        text-transform: uppercase;
        letter-spacing: 0.05em;
    }
    .detail-table tbody tr:hover { background-color: #f9fafb; }
    .detail-table tfoot td {
        background-color: #fafafa;
        font-size: 0.9375rem;
    }
    .detail-table tfoot .total-row td {
        background-color: #f0f9ff;
        font-weight: 600;
    }
    .text-right { text-align: right; }
    .summary-input { font-weight: 500; }
    .total-input {
        font-weight: 600;
        color: #2563eb;
        font-size: 1rem;
    }

    /* Style Tombol Footer & Header */
    .form-footer {
        display: flex;
        gap: 1rem;
        padding-top: 1.5rem;
        border-top: 1px solid #e5e7eb;
    }
    .btn-primary, .btn-secondary, .btn-secondary-outline {
        padding: 0.625rem 1.25rem;
        font-size: 0.875rem;
        font-weight: 500;
        border-radius: 6px;
        cursor: pointer;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        transition: all 0.2s;
    }
    .btn-primary {
        background: #2563eb;
        color: #fff;
        border: 1px solid #2563eb;
    }
    .btn-primary:hover { background: #1e40af; }
    .btn-secondary {
        background: #f3f4f6;
        color: #1f2937;
        border: 1px solid #d1d5db;
    }
    .btn-secondary:hover { background: #e5e7eb; }
    .btn-secondary-outline {
        background: #fff;
        color: #4b5563;
        border: 1px solid #d1d5db;
    }
    .btn-secondary-outline:hover { background: #f9fafb; }

    @media (max-width: 768px) {
        .form-row { grid-template-columns: 1fr; }
        .form-card { padding: 1.5rem; }
    }
</style>
@endpush

@push('scripts')
{{-- (JavaScript Anda sudah benar, tidak perlu diubah) --}}
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script>
$(document).ready(function() {
    $('.select2-basic').select2();

    $('#idpenjualan').change(function() {
        let penjualanId = $(this).val();
        if (penjualanId) {
            // Tampilkan loading (jika Anda punya overlay)
            // $('#loadingOverlay').show(); 
            
            // Nama route ini sudah benar sesuai web.php terakhir
            let url = "{{ route('admin.api.penjualan.details', ':id') }}"; 
            url = url.replace(':id', penjualanId);

            $.ajax({
                url: url, type: 'GET',
                success: function(data) {
                    $('#bodyBarang').empty();
                    if (data.details && data.details.length > 0) {
                        data.details.forEach(function(item) {
                            let newRow = `
                                <tr>
                                    <td>
                                        <input type="hidden" name="items[${item.idbarang}][idbarang]" value="${item.idbarang}">
                                        ${item.barang.nama}
                                    </td>
                                    <td>${item.barang.satuan ? item.barang.satuan.nama_satuan : 'N/A'}</td>
                                    <td class="text-center">${item.jumlah}</td>
                                    <td>
                                        <input type="number" name="items[${item.idbarang}][jumlah]" class="form-control input-retur" value="0" min="0" max="${item.jumlah}">
                                    </td>
                                </tr>
                            `;
                            $('#bodyBarang').append(newRow);
                        });
                        $('#detail-section').show();
                        $('#placeholder-section').hide();
                    } else {
                        $('#placeholder-section').show().find('p').text('Tidak ada detail barang pada penjualan ini.');
                        $('#detail-section').hide();
                    }
                    // $('#loadingOverlay').hide();
                },
                error: function() {
                    alert('Gagal mengambil data detail penjualan.');
                    // $('#loadingOverlay').hide();
                }
            });
        } else {
            $('#bodyBarang').empty();
            $('#detail-section').hide();
            $('#placeholder-section').show().find('p').text('Pilih ID Penjualan untuk menampilkan detail barang.');
        }
    });

    // Otomatis load jika ada old input (setelah validasi gagal)
    if ($('#idpenjualan').val()) {
        $('#idpenjualan').trigger('change');
    }
});
</script>
@endpush