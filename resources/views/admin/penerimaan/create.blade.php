@extends('layouts.admin')

@section('title', 'Buat Penerimaan')
@section('page-title', 'Buat Penerimaan')
@section('breadcrumb', '/ Data Penerimaan / Buat')

@section('content')
<div class="form-container">
    <div class="form-header">
        <h2 class="form-title">Form Penerimaan Barang</h2>
        <a href="{{ route('admin.penerimaan.index') }}" class="btn-secondary">
            <i class="fas fa-arrow-left"></i> &nbsp; Kembali
        </a>
    </div>

    <form action="{{ route('admin.penerimaan.store') }}" method="POST" id="formPenerimaan">
        @csrf
        
        <div class="form-card">
            {{-- [TAMBAHAN] Blok untuk menampilkan semua jenis error --}}
            @if(session('error'))
                <div class="alert alert-danger">
                    <i class="fas fa-exclamation-triangle"></i> &nbsp; {{ session('error') }}
                </div>
            @endif
            @if ($errors->any())
                <div class="alert alert-danger">
                    <strong>Terjadi Kesalahan Validasi:</strong>
                    <ul style="margin-bottom: 0; padding-left: 1.5rem;">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <div class="form-row">
                <div class="form-group">
                    <label for="idpengadaan">Pilih Pengadaan (PO) <span class="text-danger">*</span></label>
                    <select name="idpengadaan" id="idpengadaan" class="form-control select2-basic @error('idpengadaan') is-invalid @enderror" required>
                        <option value="">-- Pilih ID Pengadaan --</option>
                        @foreach($pengadaanList as $p)
                            <option value="{{ $p->idpengadaan }}" {{ old('idpengadaan') == $p->idpengadaan ? 'selected' : '' }}>
                                #{{ $p->idpengadaan }} - {{ $p->vendor->nama_vendor }} 
                                ({{ date('d M Y', strtotime($p->created_at)) }})
                                {{-- Indikator Partial --}}
                            </option>
                        @endforeach
                    </select>
                    @error('idpengadaan')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="form-group">
                    <label>Tanggal Penerimaan</label>
                    <input type="text" class="form-control" value="{{ date('d M Y, H:i') }}" readonly>
                </div>
            </div>
            <div class="form-group">
                <label for="catatan">Catatan (Opsional)</label>
                <textarea name="catatan" id="catatan" class="form-control" rows="2" placeholder="Contoh: Barang diterima dalam kondisi baik, oleh Budi.">{{ old('catatan') }}</textarea>
            </div>

            <div class="divider"></div>
            <h3 class="section-title">Detail Barang Diterima</h3>
            
            <div id="detail-section" style="display: none;">
                <div class="table-responsive">
                    <table class="detail-table" id="tableBarang">
                        <thead>
                            <tr>
                                <th>Nama Barang</th>
                                <th style="width: 15%;">Satuan</th>
                                <th style="width: 15%;" class="text-center">Jml Pesan</th>
                                <th style="width: 15%;" class="text-center">Sudah Diterima</th>
                                <th style="width: 15%;" class="text-center">Sisa</th>
                                <th style="width: 20%;" class="text-center">Terima Sekarang</th>
                            </tr>
                        </thead>
                        <tbody id="bodyBarang">
                            {{-- Baris akan di-generate oleh JavaScript --}}
                        </tbody>
                    </table>
                </div>

                <div class="form-footer">
                    <button type="submit" class="btn-primary">
                        <i class="fas fa-save"></i> &nbsp; Simpan Penerimaan
                    </button>
                </div>
            </div>

            <div id="placeholder-section" class="text-center" style="padding: 3rem 0;">
                <i class="fas fa-box-open" style="font-size: 2rem; color: #9ca3af; margin-bottom: 1rem;"></i>
                <p style="color: #6b7280;">Silakan pilih Nomor Pengadaan terlebih dahulu untuk memuat daftar barang.</p>
            </div>

            <div id="loading-section" class="text-center" style="padding: 3rem 0; display: none;">
                <i class="fas fa-circle-notch fa-spin" style="font-size: 2rem; color: #2563eb;"></i>
                <p style="margin-top: 1rem; color: #6b7280;">Memuat data barang...</p>
            </div>
        </div>
    </form>
</div>
@endsection

@push('styles')
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<style>
    /* ... style sebelumnya tetap sama ... */
    .form-container { max-width: 100%; }
    .form-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.5rem; }
    .form-title { font-size: 1.5rem; font-weight: 600; color: #1a1a1a; margin: 0; }
    .form-card { background: #fff; border: 1px solid #e5e7eb; border-radius: 8px; padding: 2rem; box-shadow: 0 1px 3px rgba(0,0,0,0.05); }
    .form-row { display: grid; grid-template-columns: repeat(2, 1fr); gap: 1.5rem; margin-bottom: 1.5rem; }
    .form-group { display: flex; flex-direction: column; }
    .form-group label { margin-bottom: 0.5rem; font-weight: 500; color: #374151; }
    .form-control { padding: 0.625rem 0.875rem; border: 1px solid #d1d5db; border-radius: 6px; width: 100%; }
    .divider { height: 1px; background: #e5e7eb; margin: 2rem 0 1.5rem; }
    .table-responsive { border: 1px solid #e5e7eb; border-radius: 8px; overflow-x: auto; margin-bottom: 1.5rem; }
    .detail-table { width: 100%; border-collapse: collapse; }
    .detail-table th { background: #f9fafb; padding: 0.75rem 1rem; text-align: left; font-weight: 600; border-bottom: 1px solid #e5e7eb; }
    .detail-table td { padding: 0.75rem 1rem; border-bottom: 1px solid #e5e7eb; vertical-align: middle; }
    .text-center { text-align: center; }
    .text-danger { color: #dc2626; }
    
    .btn-primary { background: #2563eb; color: white; border: none; padding: 0.625rem 1.25rem; border-radius: 6px; cursor: pointer; font-weight: 500; display: flex; align-items: center; }
    .btn-primary:hover { background: #1d4ed8; }
    .btn-secondary { background: #f3f4f6; color: #374151; border: 1px solid #d1d5db; padding: 0.625rem 1rem; border-radius: 6px; text-decoration: none; display: flex; align-items: center; }
    
    .bg-completed { background-color: #f0fdf4; color: #166534; } /* Hijau muda */
</style>
@endpush

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script>
$(document).ready(function() {
    $('.select2-basic').select2();

    function loadDetails(pengadaanId) {
        $('#detail-section').hide();
        $('#placeholder-section').hide();
        $('#loading-section').show();
        $('#bodyBarang').empty();

        let url = "{{ route('admin.api.pengadaan.details', ':id') }}";
        url = url.replace(':id', pengadaanId);

        $.ajax({
            url: url,
            type: 'GET',
            dataType: 'json',
            success: function(response) {
                $('#loading-section').hide();
                
                if (response.status === 'success' && response.details.length > 0) {
                    let allCompleted = true;

                    response.details.forEach(function(item, itemIndex) { // <-- Tambahkan itemIndex
                        let isFull = item.sisa <= 0;
                        if (!isFull) allCompleted = false;

                        let rowClass = isFull ? 'bg-completed' : '';
                        let readonlyAttr = isFull ? 'readonly style="background-color: #e9ecef;"' : '';
                        let inputValue = 0; 
                        // Jika sisa > 0, default value 0. Jika sisa 0, value 0.

                        let newRow = `
                            <tr class="${rowClass}">
                                <td>
                                    <input type="hidden" name="items[${itemIndex}][idbarang]" value="${item.idbarang}">
                                    <strong>${item.barang.nama}</strong>
                                </td>
                                <td>${item.barang.satuan ? item.barang.satuan.nama_satuan : '-'}</td>
                                <td class="text-center">${item.jumlah}</td>
                                <td class="text-center">${item.jumlah_diterima}</td>
                                <td class="text-center"><strong>${item.sisa}</strong></td>
                                <td>
                                    <input type="number" 
                                           name="items[${itemIndex}][jumlah_terima]" 
                                           class="form-control input-terima text-center" 
                                           value="${inputValue}" 
                                           min="0" 
                                           max="${item.sisa}"
                                           placeholder="0"
                                           ${readonlyAttr}>
                                </td>
                            </tr>
                        `;
                        $('#bodyBarang').append(newRow);
                    });

                    if(allCompleted) {
                         $('#bodyBarang').append(`<tr><td colspan="6" class="text-center text-danger py-3">Semua barang pada PO ini sudah diterima sepenuhnya.</td></tr>`);
                         $('.btn-primary').prop('disabled', true);
                    } else {
                        $('.btn-primary').prop('disabled', false);
                    }

                    $('#detail-section').show();
                } else {
                    $('#placeholder-section').show().find('p').text('Data tidak ditemukan atau error.');
                }
            },
            error: function(xhr) {
                $('#loading-section').hide();
                $('#placeholder-section').show().find('p').text('Gagal memuat data: ' + xhr.statusText);
                console.error(xhr);
            }
        });
    }

    $('#idpengadaan').change(function() {
        let val = $(this).val();
        if (val) {
            loadDetails(val);
        } else {
            $('#detail-section').hide();
            $('#placeholder-section').show();
        }
    });

    // Validasi Client Side sebelum submit
    $('#formPenerimaan').submit(function(e) {
        let hasInput = false;
        $('.input-terima').each(function() {
            let val = parseInt($(this).val()) || 0;
            if (val > 0) hasInput = true;
        });

        if (!hasInput) {
            e.preventDefault();
            alert('Mohon isi jumlah diterima pada minimal satu barang.');
        }
    });
    @if(old('idpengadaan'))
        loadDetails("{{ old('idpengadaan') }}");
    @endif
});
</script>
@endpush