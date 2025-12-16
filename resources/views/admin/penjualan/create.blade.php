@extends('layouts.admin')

@section('title', 'Transaksi Penjualan')
@section('page-title', 'Transaksi Penjualan')
@section('breadcrumb', '/ Transaksi Penjualan')

@section('content')
<form action="{{ route('admin.penjualan.store') }}" method="POST" id="formPenjualan">
    @csrf
    <div class="row">
        <div class="col-lg-8">
            <div class="card">
                <div class="card-header-container">
                    <h2 class="card-title-internal"><i class="fas fa-shopping-cart"></i> &nbsp; Keranjang Belanja</h2>
                </div>
                <div class="card-body">
                    <div class="form-group">
                        <label for="selectBarang">Cari Barang (Berdasarkan Nama atau Kode)</label>
                        <select id="selectBarang" class="form-control">
                            <option value="">-- Pilih Barang --</option>
                            @foreach($barang as $b)
                                <option value="{{ $b->idbarang }}"
                                        data-nama="{{ $b->nama }}"
                                        data-harga="{{ $b->harga }}"
                                        data-satuan="{{ $b->nama_satuan }}"
                                        data-stok="{{ $b->stok }}">
                                    {{ $b->nama }} (Stok: {{ $b->stok }})
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="divider"></div>

                    <div class="table-responsive" style="min-height: 200px;">
                        <table class="detail-table" id="tableKeranjang">
                            <thead>
                                <tr>
                                    <th>Barang</th>
                                    <th style="width: 15%;">Harga</th>
                                    <th style="width: 15%;">Jumlah</th>
                                    <th style="width: 20%;">Sub Total</th>
                                    <th style="width: 7%;" class="text-center">Aksi</th>
                                </tr>
                            </thead>
                            <tbody id="bodyKeranjang">
                                <tr id="empty-cart-row">
                                    <td colspan="5" class="text-center empty-cart-message">Keranjang masih kosong</td>
                                </tr>
                                </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="card">
                <div class="card-header-container">
                    <h2 class="card-title-internal"><i class="fas fa-money-bill-wave"></i> &nbsp; Pembayaran</h2>
                </div>
                <div class="card-body">
                    <div class="form-group">
                        <label for="nama_pelanggan">Nama Pelanggan</label>
                        <input type="text" name="nama_pelanggan" id="nama_pelanggan" class="form-control" placeholder="Umum" value="{{ old('nama_pelanggan', 'Umum') }}">
                    </div>

                    <div class="summary-box">
                        <div class="summary-item">
                            <span class="label">Subtotal</span>
                            <span class="value" id="textSubtotal">Rp 0</span>
                        </div>
                        <div class="summary-item">
                            <span class="label">PPN (10%)</span>
                            <span class="value" id="textPPN">Rp 0</span>
                        </div>
                        <div class="summary-item total">
                            <span class="label">TOTAL</span>
                            <span class="value" id="textTotal">Rp 0</span>
                        </div> 
                    </div>
                    
                    @if(session('error'))
                        <div class="alert alert-danger" style="margin-top: 1.5rem;">
                            <i class="fas fa-exclamation-triangle"></i> &nbsp; {{ session('error') }}
                        </div>
                    @endif

                    @error('total_bayar')
                        <div class="alert alert-danger" style="margin-top: 1rem;">
                            <i class="fas fa-exclamation-triangle"></i> &nbsp; {{ $message }}
                        </div>
                    @endif

                    <button type="submit" class="btn-primary btn-block btn-lg" style="margin-top: 1.5rem;">
                        <i class="fas fa-save"></i> &nbsp; Simpan Transaksi
                    </button>
                </div>
            </div>
        </div>
    </div>
</form>
@endsection

@push('styles')
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<style>
    /* General */
    .divider { height: 1px; background: #e5e7eb; margin: 1.5rem 0; }
    .text-danger { color: #ef4444; }
    .text-center { text-align: center; }
    .text-right { text-align: right; }

    /* Card Styles */
    .card { background: #fff; border: 1px solid #e5e7eb; border-radius: 8px; overflow: hidden; box-shadow: 0 1px 3px rgba(0,0,0,0.05); height: 100%; }
    .card-header-container { display: flex; align-items: center; gap: 0.5rem; padding: 1rem 1.5rem; border-bottom: 1px solid #e5e7eb; }
    .card-title-internal { font-size: 1.125rem; font-weight: 600; color: #1a1a1a; margin: 0; }
    .card-body { padding: 1.5rem; }

    /* Detail Table */
    .detail-table { width: 100%; border-collapse: collapse; }
    .detail-table th, .detail-table td { padding: 0.875rem 1rem; text-align: left; border-bottom: 1px solid #e5e7eb; vertical-align: middle; }
    .detail-table thead th { background-color: #f9fafb; font-size: 0.8125rem; font-weight: 600; color: #4b5563; text-transform: uppercase; letter-spacing: 0.05em; }
    .detail-table tbody tr:last-child td { border-bottom: none; }
    
    /* Empty Cart Message */
    .empty-cart-message { padding: 3rem 1rem; color: #6b7280; font-style: italic; }

    /* Quantity Input in Table */
    .input-jumlah {
        width: 70px;
        text-align: center;
        padding: 0.5rem;
        border: 1px solid #d1d5db;
        border-radius: 6px;
        font-size: 0.9rem;
        -moz-appearance: textfield; /* Firefox */
    }
    .input-jumlah::-webkit-outer-spin-button,
    .input-jumlah::-webkit-inner-spin-button {
        -webkit-appearance: none;
        margin: 0;
    }

    /* Action Button in Table */
    .btn-aksi-delete {
        display: inline-flex; align-items: center; justify-content: center;
        width: 32px; height: 32px; border: none; border-radius: 6px;
        font-size: 0.875rem; cursor: pointer; transition: all 0.2s;
        color: #dc2626; background-color: #fee2e2;
    }
    .btn-aksi-delete:hover { background-color: #fecaca; color: #b91c1c; }

    /* Payment Summary Box */
    .summary-box {
        background-color: #f9fafb;
        border: 1px solid #e5e7eb;
        border-radius: 8px;
        padding: 1rem;
        margin: 1.5rem 0;
    }
    .summary-item {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 0.6rem 0;
        font-size: 0.9375rem;
    }
    .summary-item .label { color: #6b7280; font-weight: 500; }
    .summary-item .value { color: #1f2937; font-weight: 600; }
    .summary-item.total {
        padding-top: 1rem;
        margin-top: 0.5rem;
        border-top: 2px dashed #d1d5db;
    }
    .summary-item.total .label {
        font-size: 1.2rem;
        color: #1a1a1a;
        font-weight: 700;
    }
    .summary-item.total .value {
        font-size: 1.2rem;
        color: #2563eb;
        font-weight: 700;
    }

    /* Form Control Besar */
    .form-control-lg { padding: 0.75rem 1rem; font-size: 1.125rem; }
    .btn-block { width: 100%; }
    .btn-lg { padding: 0.75rem 1.5rem; font-size: 1rem; }

    /* Styling Select2 */
    .select2-container { width: 100% !important; }
    .select2-container .select2-selection--single { height: 40px !important; border: 1px solid #d1d5db !important; border-radius: 6px !important; }
    .select2-container--default .select2-selection--single .select2-selection__rendered { line-height: 38px !important; padding-left: 0.875rem !important; }
    .select2-container--default .select2-selection--single .select2-selection__arrow { height: 38px !important; }    
</style>
@endpush

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script>
$(document).ready(function() {
    // Inisialisasi Select2
    $('#selectBarang').select2({
        placeholder: '-- Pilih Barang --',
        allowClear: true
    });

    // Variabel global untuk total
    let total = 0;

    // Event saat barang dipilih
    $('#selectBarang').on('select2:select', function (e) {
        let data = e.params.data.element.dataset;
        tambahBarangKeKeranjang(e.params.data.id, data);
        // Reset dropdown
        $(this).val(null).trigger('change');
    });

    // Event hapus barang dari keranjang
    $(document).on('click', '.btn-aksi-delete', function() {
        $(this).closest('tr').remove();
        hitungTotal();
    });

    // Event ubah jumlah di keranjang
    $(document).on('input', '.input-jumlah', function() {
        let row = $(this).closest('tr');
        let idbarang = row.data('idbarang');
        
        // [UPDATE] Ambil stok dari data-stok di baris TR, bukan dari dropdown lagi
        let stok = parseInt(row.data('stok')) || 0; 
        let jumlah = parseInt($(this).val()) || 0;

        if (jumlah > stok) {
            alert(`Stok tidak mencukupi. Sisa stok: ${stok}`);
            $(this).val(stok);
            jumlah = stok;
        }
        
        if(jumlah < 1) {
            $(this).val(1);
            jumlah = 1;
        }
        
        let harga = parseInt(row.find('.harga-jual').val()) || 0;
        let subtotal = jumlah * harga;
        row.find('.subtotal-text').text(formatRupiah(subtotal));
        
        hitungTotal();
    });

    function tambahBarangKeKeranjang(id, data) {
        let idbarang = id;
        let nama = data.nama;
        let harga = parseInt(data.harga);
        let stok = parseInt(data.stok);
        
        if (stok <= 0) {
            alert('Stok barang ini sudah habis!');
            return;
        }

        // Cek jika barang sudah ada di keranjang
        let barisYangAda = $(`#bodyKeranjang tr[data-idbarang="${idbarang}"]`);

        if (barisYangAda.length > 0) {
            // Jika ada, tambahkan jumlahnya
            let inputJumlah = barisYangAda.find('.input-jumlah');
            let jumlahSekarang = parseInt(inputJumlah.val()) || 0;
            let jumlahBaru = jumlahSekarang + 1;

            if (jumlahBaru > stok) {
                alert(`Stok tidak mencukupi. Sisa stok: ${stok}`);
                inputJumlah.val(stok);
            } else {
                inputJumlah.val(jumlahBaru);
            }
            // Trigger input event untuk kalkulasi ulang
            inputJumlah.trigger('input');
        } else {
            // Jika belum ada, tambahkan baris baru
            let subtotal = 1 * harga;
            
            // [UPDATE] Tambahkan data-stok di TR untuk validasi
            let barisBaru = `
                <tr data-idbarang="${idbarang}" data-stok="${stok}">
                    <td>
                        ${nama}
                        <input type="hidden" name="idbarang[]" value="${idbarang}">
                        <input type="hidden" class="harga-jual" value="${harga}">
                    </td>
                    <td>${formatRupiah(harga)}</td>
                    <td>
                        <input type="number" name="jumlah[]" class="form-control input-jumlah" value="1" min="1" max="${stok}">
                    </td>
                    <td class="text-right subtotal-text">${formatRupiah(subtotal)}</td>
                    <td class="text-center">
                        <button type="button" class="btn-aksi-delete" title="Hapus">
                            <i class="fas fa-trash-alt"></i>
                        </button>
                    </td>
                </tr>
            `;
            $('#bodyKeranjang').append(barisBaru);
        }
        
        hitungTotal();
    }

    function hitungTotal() {
        // Toggle pesan keranjang kosong
        if ($('#bodyKeranjang tr[data-idbarang]').length > 0) {
            $('#empty-cart-row').hide();
        } else {
            $('#empty-cart-row').show();
        }

        let subtotal = 0;
        $('#bodyKeranjang tr').each(function() {
            let jumlah = parseInt($(this).find('.input-jumlah').val()) || 0;
            let harga = parseInt($(this).find('.harga-jual').val()) || 0;
            subtotal += (jumlah * harga);
        });

        let ppn = subtotal * 0.11;
        total = subtotal + ppn;

        $('#textSubtotal').text(formatRupiah(subtotal));
        $('#textPPN').text(formatRupiah(ppn));
        $('#textTotal').text(formatRupiah(total));
    }

    function formatRupiah(angka) {
        if (!angka) return 'Rp 0';
        // Pembulatan ke ratusan terdekat
        let rounded = Math.round(angka / 100) * 100;
        return 'Rp ' + rounded.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
    }
});
</script>
@endpush