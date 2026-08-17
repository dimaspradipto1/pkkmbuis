@extends('dashboard.template')

@section('content')
    <div class="pagetitle">
        <h1>Data Users</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
                <li class="breadcrumb-item">Data Users</li>
            </ol>
        </nav>
    </div>

    <section class="section">
        <!-- Stat Cards -->
        <div class="row g-3 mb-4">
            <!-- Total Mahasiswa -->
            <div class="col-md-4">
                <div class="card border-0 shadow-sm rounded-4 h-100 mb-0" style="border-left: 5px solid #2b3e50 !important; transition: all 0.2s ease;">
                    <div class="card-body d-flex align-items-center p-3">
                        <div class="rounded-circle d-flex align-items-center justify-content-center me-3 text-white shadow-sm" style="width: 48px; height: 48px; min-width: 48px; background-color: #334155;">
                            <i class="bi bi-people-fill fs-5"></i>
                        </div>
                        <div>
                            <div class="text-uppercase fw-bold text-muted" style="font-size: 0.72rem; letter-spacing: 0.5px;">TOTAL MAHASISWA</div>
                            <div class="fs-4 fw-bold text-dark lh-1 mt-1">{{ number_format($totalMahasiswa ?? 0) }}</div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Kakak Pendamping -->
            <div class="col-md-4">
                <div class="card border-0 shadow-sm rounded-4 h-100 mb-0" style="border-left: 5px solid #10b981 !important; transition: all 0.2s ease;">
                    <div class="card-body d-flex align-items-center p-3">
                        <div class="rounded-circle d-flex align-items-center justify-content-center me-3 text-white shadow-sm" style="width: 48px; height: 48px; min-width: 48px; background-color: #10b981;">
                            <i class="bi bi-person-badge-fill fs-5"></i>
                        </div>
                        <div>
                            <div class="text-uppercase fw-bold text-muted" style="font-size: 0.72rem; letter-spacing: 0.5px;">KAKAK PENDAMPING</div>
                            <div class="fs-4 fw-bold text-dark lh-1 mt-1">{{ number_format($totalKakakPendamping ?? 0) }}</div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Dosen Pendamping -->
            <div class="col-md-4">
                <div class="card border-0 shadow-sm rounded-4 h-100 mb-0" style="border-left: 5px solid #e11d48 !important; transition: all 0.2s ease;">
                    <div class="card-body d-flex align-items-center p-3">
                        <div class="rounded-circle d-flex align-items-center justify-content-center me-3 text-white shadow-sm" style="width: 48px; height: 48px; min-width: 48px; background-color: #e11d48;">
                            <i class="bi bi-mortarboard-fill fs-5"></i>
                        </div>
                        <div>
                            <div class="text-uppercase fw-bold text-muted" style="font-size: 0.72rem; letter-spacing: 0.5px;">DOSEN PENDAMPING</div>
                            <div class="fs-4 fw-bold text-dark lh-1 mt-1">{{ number_format($totalDosenPendamping ?? 0) }}</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-lg-12">

                <div class="card">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <h5 class="card-title">List Data Users</h5>
                            <div class="d-flex gap-2">
                                @if (Auth::user()->role != 'mahasiswa')
                                    <form action="{{ route('users.bulkDestroy') }}" method="POST" id="bulk-delete-form" class="d-inline">
                                        @csrf
                                        <div id="selected-ids-container"></div>
                                        <button type="submit" class="btn btn-danger rounded" id="btn-bulk-delete" style="display:none;">
                                            <i class="bi bi-trash-fill me-1"></i> Hapus Terpilih (<span id="selected-count">0</span>)
                                        </button>
                                    </form>
                                @endif
                                <button type="button" class="btn btn-success rounded" data-bs-toggle="modal" data-bs-target="#importModal">
                                    <i class="bi bi-file-earmark-excel me-1"></i> Import Excel
                                </button>
                                <a href="{{ route('users.create') }}" class="btn btn-primary rounded"><i class="bi bi-person-plus me-1"></i> Tambah User</a>
                            </div>
                        </div>
                        <div class="table-responsive">
                            {!! $dataTable->table() !!}
                        </div>

                    </div>
                </div>

            </div>
        </div>
    </section>

    <!-- Modal Import Excel -->
    <div class="modal fade" id="importModal" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 shadow">
                <div class="modal-header bg-success text-white border-0">
                    <h5 class="modal-title"><i class="bi bi-file-earmark-excel me-1"></i> Import User Excel</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="{{ route('users.import') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="modal-body p-4">
                        <div class="alert alert-info py-2 small mb-3">
                            Format header: <strong>name</strong>, <strong>email</strong>, <strong>no_wa</strong>, <strong>password</strong>, <strong>id_pendaftar</strong>, <strong>nim</strong>, <strong>nup</strong>, <strong>role</strong>, <strong>fakultas</strong>, <strong>program_studi</strong>.
                            <br>
                            <a href="{{ route('users.template') }}" class="fw-bold"><i class="bi bi-cloud-download me-1"></i> Download Template Excel</a>
                        </div>
                        <div class="mb-3">
                            <label for="file" class="form-label fw-bold small">Pilih File Excel (.xlsx, .xls, .csv)</label>
                            <input type="file" name="file" class="form-control" id="file" required>
                        </div>
                    </div>
                    <div class="modal-footer border-0">
                        <button type="button" class="btn btn-light px-3" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-success px-4 rounded">Import Data</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Modal QR Code -->

    <div class="modal fade" id="qrModal" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered modal-sm">
            <div class="modal-content text-center border-0 shadow">
                <div class="modal-header border-0 pb-0">
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body p-4">
                    <h5 class="fw-bold mb-3" id="qrName">-</h5>
                    <div id="qrcode" class="d-flex justify-content-center p-3 bg-white rounded border"></div>
                    <p class="text-muted small mt-3" id="qrReg">-</p>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/qrcodejs/1.0.0/qrcode.min.js"></script>
    <script>
        let qrcode = new QRCode(document.getElementById("qrcode"), {
            width : 200,
            height : 200
        });

        function showQR(name, reg) {
            document.getElementById('qrName').innerText = name;
            document.getElementById('qrReg').innerText = reg;
            qrcode.clear();
            qrcode.makeCode(reg); // Using registration number as the QR content
            var myModal = new bootstrap.Modal(document.getElementById('qrModal'));
            myModal.show();
        }
    </script>
     @if(app()->environment('production'))
        {!! str_replace('http:', 'https:', $dataTable->scripts()) !!}
    @else
        {!! $dataTable->scripts() !!}
    @endif
    <script>
        $(document).ready(function() {
            const bulkBtn = $('#btn-bulk-delete');
            const bulkCount = $('#selected-count');
            const bulkForm = $('#bulk-delete-form');
            const container = $('#selected-ids-container');

            function toggleBulkButton() {
                const checked = $('.record-checkbox:checked');
                const count = checked.length;
                if (count > 0) {
                    bulkCount.text(count);
                    bulkBtn.fadeIn(200);
                } else {
                    bulkBtn.fadeOut(200);
                }
            }

            $(document).on('click', '#select-all', function() {
                const isChecked = $(this).prop('checked');
                $('.record-checkbox').prop('checked', isChecked);
                toggleBulkButton();
            });

            $(document).on('change', '.record-checkbox', function() {
                const total = $('.record-checkbox').length;
                const checked = $('.record-checkbox:checked').length;
                $('#select-all').prop('checked', (total > 0 && total === checked));
                toggleBulkButton();
            });

            $(document).on('draw.dt', '#users-table', function() {
                $('#select-all').prop('checked', false);
                toggleBulkButton();
            });

            bulkForm.on('submit', function(e) {
                const checked = $('.record-checkbox:checked');
                if (checked.length === 0) {
                    e.preventDefault();
                    return false;
                }

                container.empty();
                checked.each(function() {
                    container.append(`<input type="hidden" name="ids[]" value="${$(this).val()}">`);
                });
                return true;
            });
        });
    </script>
@endpush
