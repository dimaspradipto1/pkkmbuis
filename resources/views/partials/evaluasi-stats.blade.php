<!-- Evaluasi Stat Cards -->
<div class="row g-3 mb-4">
    <!-- Total Mahasiswa -->
    <div class="col-12 col-sm-6 col-xl-4">
        <div class="card border-0 shadow-sm rounded-4 h-100 mb-0" style="border-left: 5px solid #3b82f6 !important; transition: all 0.2s ease;">
            <div class="card-body d-flex align-items-center p-3">
                <div class="rounded-circle d-flex align-items-center justify-content-center me-3 text-white shadow-sm" style="width: 48px; height: 48px; min-width: 48px; background: linear-gradient(135deg, #3b82f6, #1d4ed8);">
                    <i class="bi bi-people-fill fs-5"></i>
                </div>
                <div>
                    <div class="text-uppercase fw-bold text-muted" style="font-size: 0.72rem; letter-spacing: 0.5px;">TOTAL MAHASISWA</div>
                    <div class="fs-4 fw-bold text-dark lh-1 mt-1">{{ number_format($totalMahasiswa ?? 0) }}</div>
                </div>
            </div>
        </div>
    </div>

    <!-- Sudah Mengisi Evaluasi -->
    <div class="col-12 col-sm-6 col-xl-4">
        <div class="card border-0 shadow-sm rounded-4 h-100 mb-0" style="border-left: 5px solid #10b981 !important; transition: all 0.2s ease;">
            <div class="card-body d-flex align-items-center p-3">
                <div class="rounded-circle d-flex align-items-center justify-content-center me-3 text-white shadow-sm" style="width: 48px; height: 48px; min-width: 48px; background: linear-gradient(135deg, #10b981, #059669);">
                    <i class="bi bi-check-circle-fill fs-5"></i>
                </div>
                <div>
                    <div class="text-uppercase fw-bold text-muted" style="font-size: 0.72rem; letter-spacing: 0.5px;">SUDAH MENGISI EVALUASI</div>
                    <div class="fs-4 fw-bold text-success lh-1 mt-1">{{ number_format($sudahMengisi ?? 0) }}</div>
                </div>
            </div>
        </div>
    </div>

    <!-- Belum Mengisi Evaluasi -->
    <div class="col-12 col-sm-6 col-xl-4">
        <div class="card border-0 shadow-sm rounded-4 h-100 mb-0" style="border-left: 5px solid #ef4444 !important; transition: all 0.2s ease;">
            <div class="card-body d-flex align-items-center p-3">
                <div class="rounded-circle d-flex align-items-center justify-content-center me-3 text-white shadow-sm" style="width: 48px; height: 48px; min-width: 48px; background: linear-gradient(135deg, #ef4444, #dc2626);">
                    <i class="bi bi-x-circle-fill fs-5"></i>
                </div>
                <div>
                    <div class="text-uppercase fw-bold text-muted" style="font-size: 0.72rem; letter-spacing: 0.5px;">BELUM MENGISI EVALUASI</div>
                    <div class="fs-4 fw-bold text-danger lh-1 mt-1">{{ number_format($belumMengisi ?? 0) }}</div>
                </div>
            </div>
        </div>
    </div>
</div>
