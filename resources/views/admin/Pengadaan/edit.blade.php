@extends('layouts.admin')

@section('title', 'Edit Pengadaan')
@section('page-title', 'Edit Pengadaan')
@section('breadcrumb', '/ Data Pengadaan / Edit')

@section('content')
<div class="form-container">
    <div class="form-header">
        <h2 class="form-title">Form Edit Pengadaan #{{ $pengadaan->idpengadaan }}</h2>
        <a href="{{ route('admin.pengadaan.index') }}" class="btn-secondary">
            <i class="fas fa-arrow-left"></i> &nbsp; Kembali
        </a>
    </div>

    <form action="{{ route('admin.pengadaan.update', $pengadaan->idpengadaan) }}" method="POST" id="formPengadaan">
        @csrf
        @method('PUT') 
        
        <div class="form-card">
            @if(session('error'))
                <div class="alert alert-danger">
                    <i class="fas fa-exclamation-triangle"></i> &nbsp; {{ session('error') }}
                </div>
            @endif

            <div class="form-row">
                <div class="form-group">
                    <label for="idvendor">Vendor <span class="text-danger">*</span></label>
                    <select name="idvendor" id="idvendor" class="form-control select2-basic @error('idvendor') is-invalid @enderror" required>
                        <option value="">-- Pilih Vendor --</option>
                        @foreach($vendors as $vendor)
                            <option value="{{ $vendor->idvendor }}" {{ $pengadaan->idvendor == $vendor->idvendor ? 'selected' : '' }}>
                                {{ $vendor->nama_vendor }}
                            </option>
                        @endforeach
                    </select>
                    @error('idvendor')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="form-group">
                    <label>Tanggal Pengadaan</label>
                    <input type="text" class="form-control" value="{{ date('d M Y, H:i', strtotime($pengadaan->created_at)) }}" readonly>
                </div>
            </div>

            <div class="divider"></div>
            <h3 class="section-title">Detail Barang</h3>
            
            <div class="table-responsive">
                <table class="detail-table" id="tableBarang">
                    <thead>
                        <tr>
                            <th style="width: 30%;">Barang</th>
                            <th style="width: 15%;">Satuan</th>
                            <th style="width: 12%;">Jumlah</th>
                            <th style="width: 18%;">Harga Satuan (Rp)</th>
                            <th style="width: 18%;">Sub Total (Rp)</th>
                            <th style="width: 7%;" class="text-center">
                                <button type="button" class="btn-action btn-add-row" id="btnTambahBaris" title="Tambah Baris">
                                    <i class="fas fa-plus"></i>
                                </button>
                            </th>
                        </tr>
                    </thead>
                    <tbody id="bodyBarang">
                        @foreach($details as $detail)
                        <tr class="baris-barang">
                            <td>
                                <select name="idbarang[]" class="form-control select-barang" required>
                                    <option value="">-- Pilih Barang --</option>
                                    @foreach($barang as $b)
                                        <option value="{{ $b->idbarang }}" 
                                                data-satuan="{{ $b->nama_satuan }}" 
                                                data-harga="{{ $b->harga }}"
                                                {{ $detail->idbarang == $b->idbarang ? 'selected' : '' }}>
                                            {{ $b->nama }}
                                        </option>
                                    @endforeach
                                </select>
                            </td>
                            <td><input type="text" class="form-control satuan" value="{{ $detail->nama_satuan }}" readonly></td>
                            <td><input type="number" name="jumlah[]" class="form-control jumlah" min="1" value="{{ $detail->jumlah }}" required></td>
                            <td><input type="number" name="harga_satuan[]" class="form-control harga-satuan" min="0" value="{{ $detail->harga_satuan }}" required></td>
                            <td><input type="text" class="form-control sub-total" value="Rp {{ number_format($detail->sub_total, 0, ',', '.') }}" readonly></td>
                            <td class="text-center">
                                <button type="button" class="btn-action btn-delete-row btn-hapus-baris" title="Hapus Baris">
                                    <i class="fas fa-trash-alt"></i>
                                </button>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                    <tfoot>
                        <tr>
                            <td colspan="4" class="text-right"><strong>Subtotal:</strong></td>
                            <td colspan="2">
                                <input type="text" id="displaySubtotal" class="form-control summary-input" readonly>
                            </td>
                        </tr>
                        <tr>
                            <td colspan="4" class="text-right"><strong>PPN (10%):</strong></td>
                            <td colspan="2">
                                <input type="text" id="displayPPN" class="form-control summary-input" readonly>
                            </td>
                        </tr>
                        <tr class="total-row">
                            <td colspan="4" class="text-right"><strong>TOTAL:</strong></td>
                            <td colspan="2">
                                <input type="text" id="displayTotal" class="form-control summary-input total-input" readonly>
                            </td>
                        </tr>
                    </tfoot>
                </table>
            </div>

            <div class="form-footer">
                <button type="submit" class="btn-primary">
                    <i class="fas fa-save"></i> &nbsp; Update Pengadaan
                </button>
                <a href="{{ route('admin.pengadaan.index') }}" class="btn-secondary-outline">Batal</a>
            </div>
        </div>
    </form>
</div>
@endsection

@push('styles')
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<style>
    /* ... (Salin semua style CSS dari create.blade.php Anda) ... */
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

    /* Style Tombol Aksi (Add/Delete) */
    .btn-action {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        width: 32px;
        height: 32px;
        border: none;
        border-radius: 6px;
        font-size: 0.875rem;
        cursor: pointer;
        transition: all 0.2s;
    }
    .btn-add-row {
        color: #166534;
        background-color: #dcfce7;
        border: 1px solid #bbf7d0;
    }
    .btn-add-row:hover {
        background-color: #bbf7d0;
        color: #15803d;
    }
    .btn-delete-row {
        color: #dc2626;
        background-color: #fee2e2;
        border: 1px solid #fecaca;
    }
    .btn-delete-row:hover {
        background-color: #fecaca;
        color: #b91c1c;
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

    /* Style Select2 */
    .select2-container {
        width: 100% !important;
    }
    .select2-container .select2-selection--single {
        height: 40px !important; 
        border: 1px solid #d1d5db !important;
        border-radius: 6px !important;
    }
    .select2-container--default .select2-selection--single .select2-selection__rendered {
        line-height: 38px !important; 
        padding-left: 0.875rem !important;
        color: #374151;
    }
    .select2-container--default .select2-selection--single .select2-selection__arrow {
        height: 38px !important;
    }
    .select2-container--default .select2-search--dropdown .select2-search__field {
        border: 1px solid #d1d5db;
    }
    .select2-container--default .select2-selection--single.select2-selection--open {
        border-color: #2563eb !important; 
    }
   
    @media (max-width: 768px) {
        .form-row { grid-template-columns: 1fr; }
        .form-card { padding: 1.5rem; }
    }
</style>
@endpush

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

<script>
$(document).ready(function() {
    // Inisialisasi Select2 untuk dropdown yang sudah ada
    $('.select-barang').select2();
    $('.select2-basic').select2(); // Untuk dropdown Vendor
    
    // Inisialisasi hitungan saat load
    hitungTotal();

    // Tambah baris barang
    $('#btnTambahBaris').click(function() {
        // Ambil template baris pertama
        let barisTemplate = $('.baris-barang:first').html();
        let barisBaru = $('<tr class="baris-barang"></tr>').html(barisTemplate);
        
        // Hapus UI Select2 yang ikut ter-clone
        barisBaru.find('span.select2').remove();
        
        // Reset nilai
        barisBaru.find('input').val('');
        barisBaru.find('.satuan').val('');
        barisBaru.find('.jumlah').val('1');
        barisBaru.find('.harga-satuan').val('0');
        barisBaru.find('.sub-total').val('');
        
        // Reset pilihan barang (penting!)
        barisBaru.find('.select-barang').val(''); 
        
        $('#bodyBarang').append(barisBaru);
        
        // Inisialisasi Select2 HANYA di baris baru
        barisBaru.find('.select-barang').select2();
    });

    // Hapus baris
    $(document).on('click', '.btn-hapus-baris', function() {
        if ($('.baris-barang').length > 1) {
            $(this).closest('tr').remove();
            hitungTotal();
        } else {
            alert('Minimal harus ada 1 barang!');
        }
    });

    // Ketika barang dipilih
    $(document).on('change', '.select-barang', function() {
        let selected = $(this).find(':selected');
        let satuan = selected.data('satuan');
        let harga = selected.data('harga');
        
        let row = $(this).closest('tr');
        row.find('.satuan').val(satuan || '');
        row.find('.harga-satuan').val(harga || 0);
        
        hitungSubTotal(row);
    });

    // Ketika jumlah atau harga berubah
    $(document).on('input', '.jumlah, .harga-satuan', function() {
        let row = $(this).closest('tr');
        hitungSubTotal(row);
    });

    // Hitung subtotal per baris
    function hitungSubTotal(row) {
        let jumlah = parseInt(row.find('.jumlah').val()) || 0;
        let harga = parseInt(row.find('.harga-satuan').val()) || 0;
        let subtotal = jumlah * harga;
        
        row.find('.sub-total').val(formatRupiah(subtotal));
        hitungTotal();
    }

    // Hitung total keseluruhan
    function hitungTotal() {
        let subtotal = 0;
        
        $('.baris-barang').each(function() {
            let jumlah = parseInt($(this).find('.jumlah').val()) || 0;
            let harga = parseInt($(this).find('.harga-satuan').val()) || 0;
            subtotal += (jumlah * harga);
        });
        
        let ppn = subtotal * 0.1;
        let total = subtotal + ppn;
        
        $('#displaySubtotal').val(formatRupiah(subtotal));
        $('#displayPPN').val(formatRupiah(ppn));
        $('#displayTotal').val(formatRupiah(total));
    }

    // Format rupiah
    function formatRupiah(angka) {
        if (!angka) return 'Rp 0';
        return 'Rp ' + angka.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
    }

    // Validasi sebelum submit
    $('#formPengadaan').submit(function(e) {
        let valid = true;
        let barangDipilih = [];
        
        $('.select-barang').each(function() {
            let val = $(this).val();
            if (val) {
                if (barangDipilih.includes(val)) {
                    alert('Barang tidak boleh duplikat!');
                    valid = false;
                    return false;
                }
                barangDipilih.push(val);
            } else if ($(this).closest('tr').find('.jumlah').val() > 0) {
                alert('Pastikan semua baris telah memilih barang.');
                valid = false;
                return false;
            }
        });
        
        if (!valid) {
            e.preventDefault();
        }
    });
});
</script>
@endpush