@extends('dashboard.template')

@section('content')
    <div class="pagetitle">
        <h1>Modul Post Test Mahasiswa</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
                <li class="breadcrumb-item">Modul Post Test Mahasiswa</li>
            </ol>
        </nav>
    </div>

    <section class="section">
        <div class="row">
            <!-- Left Side: Card with Tabs -->
            <div class="col-lg-8">
                <div class="card shadow-sm border-0 rounded-3">
                    <div class="card-header bg-white border-0 pt-4 px-4 pb-0">
                        <h4 class="fw-bold mb-3" style="color: #012970;">{{ $modul_title }}</h4>
                        <!-- Tabs -->
                        <ul class="nav nav-tabs nav-tabs-bordered border-bottom-0" id="modulTab" role="tablist">
                            <li class="nav-item" role="presentation">
                                <button class="nav-link" id="materi-tab" data-bs-toggle="tab" data-bs-target="#materi"
                                    type="button" role="tab" aria-controls="materi"
                                    aria-selected="false">Materi</button>
                            </li>
                            @if ($id != 5)
                                <li class="nav-item" role="presentation">
                                    <button class="nav-link active" id="pretest-tab" data-bs-toggle="tab"
                                        data-bs-target="#pretest" type="button" role="tab" aria-controls="pretest"
                                        aria-selected="true">Pre Test</button>
                                </li>
                                <li class="nav-item" role="presentation">
                                    <button class="nav-link" id="posttest-tab" data-bs-toggle="tab" data-bs-target="#posttest"
                                        type="button" role="tab" aria-controls="posttest" aria-selected="false">Post
                                        Test</button>
                                </li>
                            @else
                                <li class="nav-item" role="presentation">
                                    <button class="nav-link active" id="tugas-tab" data-bs-toggle="tab"
                                        data-bs-target="#tugas" type="button" role="tab" aria-controls="tugas"
                                        aria-selected="true">Tugas Kelompok</button>
                                </li>
                            @endif
                        </ul>
                    </div>
                    <div class="card-body px-4 py-3">
                        <div class="tab-content pt-2" id="modulTabContent">
                            <!-- Materi Tab Content -->
                            <div class="tab-pane fade" id="materi" role="tabpanel" aria-labelledby="materi-tab">
                                <div class="py-3">
                                    <h5 class="fw-bold mb-3">Materi Modul {{ $id }}</h5>

                                    @if ($materi_file)
                                        @php
                                            $ext = strtolower(pathinfo($materi_file, PATHINFO_EXTENSION));
                                            $fileUrl = asset('storage/' . $materi_file);
                                        @endphp

                                        @if ($ext === 'pdf')
                                            {{-- PDF: tampilkan langsung di iframe --}}
                                            <div class="mb-3 d-flex justify-content-end">
                                                <a href="{{ $fileUrl }}" target="_blank"
                                                    class="btn btn-sm btn-outline-primary me-2">
                                                    <i class="bi bi-box-arrow-up-right me-1"></i> Buka di Tab Baru
                                                </a>
                                                <a href="{{ $fileUrl }}" download
                                                    class="btn btn-sm btn-success">
                                                    <i class="bi bi-download me-1"></i> Unduh Materi
                                                </a>
                                            </div>
                                            <div class="border rounded" style="height: 520px; overflow: hidden;">
                                                <iframe src="{{ $fileUrl }}" width="100%" height="100%"
                                                    style="border: none;" title="Materi Modul {{ $id }}">
                                                    <p>Browser Anda tidak mendukung tampilan PDF.
                                                        <a href="{{ $fileUrl }}">Klik di sini untuk mengunduh.</a>
                                                    </p>
                                                </iframe>
                                            </div>
                                        @else
                                            {{-- File non-PDF (PPT, DOC, XLS, ZIP): tampilkan tombol unduh --}}
                                            <div class="text-center py-5">
                                                <div class="mb-3">
                                                    @if (in_array($ext, ['ppt', 'pptx']))
                                                        <i class="bi bi-file-earmark-slides text-warning"
                                                            style="font-size: 4rem;"></i>
                                                    @elseif (in_array($ext, ['doc', 'docx']))
                                                        <i class="bi bi-file-earmark-word text-primary"
                                                            style="font-size: 4rem;"></i>
                                                    @elseif (in_array($ext, ['xls', 'xlsx']))
                                                        <i class="bi bi-file-earmark-excel text-success"
                                                            style="font-size: 4rem;"></i>
                                                    @elseif ($ext === 'zip')
                                                        <i class="bi bi-file-earmark-zip text-secondary"
                                                            style="font-size: 4rem;"></i>
                                                    @else
                                                        <i class="bi bi-file-earmark text-muted"
                                                            style="font-size: 4rem;"></i>
                                                    @endif
                                                </div>
                                                <p class="text-muted mb-1">File materi tersedia dalam format
                                                    <strong>.{{ strtoupper($ext) }}</strong>
                                                </p>
                                                <p class="text-muted small mb-4">Klik tombol di bawah untuk mengunduh
                                                    materi modul {{ $id }}.</p>
                                                <a href="{{ $fileUrl }}" download
                                                    class="btn btn-success px-4">
                                                    <i class="bi bi-download me-2"></i> Unduh Materi Modul {{ $id }}
                                                </a>
                                            </div>
                                        @endif
                                    @else
                                        {{-- Belum ada file materi --}}
                                        <div class="text-center py-5">
                                            <i class="bi bi-folder2-open text-muted" style="font-size: 4rem;"></i>
                                            <p class="text-muted mt-3 mb-0">Belum ada materi untuk Modul {{ $id }}.</p>
                                            <p class="text-muted small">Materi akan ditampilkan setelah diunggah oleh
                                                admin.</p>
                                        </div>
                                    @endif
                                </div>
                            </div>

                            @if ($id != 5)
                                <!-- Pre Test Tab Content -->
                                <div class="tab-pane fade {{ (session('active_tab') == 'pretest' || !session('active_tab')) ? 'show active' : '' }}" id="pretest" role="tabpanel"
                                    aria-labelledby="pretest-tab">
                                    <div class="py-2">
                                        @if($hasil_pre)
                                            <div class="alert alert-info border-0 shadow-sm d-flex align-items-center mb-4">
                                                <i class="bi bi-check-circle-fill fs-4 me-3"></i>
                                                <div>
                                                    <h6 class="fw-bold mb-1">Anda telah menyelesaikan Pre Test.</h6>
                                                    <p class="mb-0 small">Skor Anda: <strong>{{ $hasil_pre->skor }}</strong> (Benar {{ $hasil_pre->jumlah_benar }} dari {{ $hasil_pre->total_soal }} soal)</p>
                                                </div>
                                            </div>
                                        @endif

                                        <form action="{{ route('modulposttest.store') }}" method="POST">
                                            @csrf
                                            <input type="hidden" name="modul_id" value="{{ $id }}">
                                            <input type="hidden" name="type" value="pretest">
                                            
                                            @foreach ($questions_pre as $index => $q)
                                                <div class="mb-4 pb-3 border-bottom last-border-none">
                                                    <p class="fw-bold mb-3">{{ $index + 1 }}. {{ $q->soal }}</p>
                                                    <div class="row g-3">
                                                        @php
                                                            $saved_jawaban = $hasil_pre ? json_decode($hasil_pre->jawaban, true) : null;
                                                            $user_ans = $saved_jawaban[$q->id] ?? null;
                                                        @endphp
                                                        <div class="col-md-6">
                                                            <div class="form-check">
                                                                <input class="form-check-input" type="radio"
                                                                    name="pre_soal_{{ $q->id }}"
                                                                    id="q{{ $q->id }}_a" value="A"
                                                                    {{ $user_ans == 'A' ? 'checked' : '' }}
                                                                    {{ $hasil_pre ? 'disabled' : 'required' }}>
                                                                <label class="form-check-label text-muted"
                                                                    for="q{{ $q->id }}_a">
                                                                    <span class="fw-bold text-dark">A.</span>
                                                                    {{ $q->pilihan_a }}
                                                                </label>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <div class="form-check">
                                                                <input class="form-check-input" type="radio"
                                                                    name="pre_soal_{{ $q->id }}"
                                                                    id="q{{ $q->id }}_b" value="B"
                                                                    {{ $user_ans == 'B' ? 'checked' : '' }}
                                                                    {{ $hasil_pre ? 'disabled' : 'required' }}>
                                                                <label class="form-check-label text-muted"
                                                                    for="q{{ $q->id }}_b">
                                                                    <span class="fw-bold text-dark">B.</span>
                                                                    {{ $q->pilihan_b }}
                                                                </label>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <div class="form-check">
                                                                <input class="form-check-input" type="radio"
                                                                    name="pre_soal_{{ $q->id }}"
                                                                    id="q{{ $q->id }}_c" value="C"
                                                                    {{ $user_ans == 'C' ? 'checked' : '' }}
                                                                    {{ $hasil_pre ? 'disabled' : 'required' }}>
                                                                <label class="form-check-label text-muted"
                                                                    for="q{{ $q->id }}_c">
                                                                    <span class="fw-bold text-dark">C.</span>
                                                                    {{ $q->pilihan_c }}
                                                                </label>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <div class="form-check">
                                                                <input class="form-check-input" type="radio"
                                                                    name="pre_soal_{{ $q->id }}"
                                                                    id="q{{ $q->id }}_d" value="D"
                                                                    {{ $user_ans == 'D' ? 'checked' : '' }}
                                                                    {{ $hasil_pre ? 'disabled' : 'required' }}>
                                                                <label class="form-check-label text-muted"
                                                                    for="q{{ $q->id }}_d">
                                                                    <span class="fw-bold text-dark">D.</span>
                                                                    {{ $q->pilihan_d }}
                                                                </label>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            @endforeach
                                            @if (count($questions_pre) == 0)
                                                <p class="text-center text-muted my-5">Belum ada soal pre test.</p>
                                            @elseif (!$hasil_pre)
                                                <div class="text-end mt-4">
                                                    <button type="submit" class="btn btn-primary px-4">Submit
                                                        JAWABAN PRE TEST</button>
                                                </div>
                                            @endif
                                        </form>
                                    </div>
                                </div>

                                <!-- Post Test Tab Content -->
                                <div class="tab-pane fade {{ session('active_tab') == 'posttest' ? 'show active' : '' }}" id="posttest" role="tabpanel" aria-labelledby="posttest-tab">
                                    <div class="py-2">
                                        @if($hasil_post)
                                            <div class="alert alert-success border-0 shadow-sm d-flex align-items-center mb-4">
                                                <i class="bi bi-star-fill fs-4 me-3 text-warning"></i>
                                                <div>
                                                    <h6 class="fw-bold mb-1">Anda telah menyelesaikan Post Test.</h6>
                                                    <p class="mb-0 small">Skor Anda: <strong>{{ $hasil_post->skor }}</strong> (Benar {{ $hasil_post->jumlah_benar }} dari {{ $hasil_post->total_soal }} soal)</p>
                                                </div>
                                            </div>
                                        @endif

                                        <form action="{{ route('modulposttest.store') }}" method="POST">
                                            @csrf
                                            <input type="hidden" name="modul_id" value="{{ $id }}">
                                            <input type="hidden" name="type" value="posttest">

                                            @foreach ($questions_post as $index => $q)
                                                <div class="mb-4 pb-3 border-bottom last-border-none">
                                                    <p class="fw-bold mb-3">{{ $index + 1 }}. {{ $q->soal }}</p>
                                                    <div class="row g-3">
                                                        @php
                                                            $saved_jawaban_post = $hasil_post ? json_decode($hasil_post->jawaban, true) : null;
                                                            $user_ans_post = $saved_jawaban_post[$q->id] ?? null;
                                                        @endphp
                                                        <div class="col-md-6">
                                                            <div class="form-check">
                                                                <input class="form-check-input" type="radio"
                                                                    name="post_soal_{{ $q->id }}"
                                                                    id="post_q{{ $q->id }}_a" value="A"
                                                                    {{ $user_ans_post == 'A' ? 'checked' : '' }}
                                                                    {{ $hasil_post ? 'disabled' : 'required' }}>
                                                                <label class="form-check-label text-muted"
                                                                    for="post_q{{ $q->id }}_a">
                                                                    <span class="fw-bold text-dark">A.</span>
                                                                    {{ $q->pilihan_a }}
                                                                </label>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <div class="form-check">
                                                                <input class="form-check-input" type="radio"
                                                                    name="post_soal_{{ $q->id }}"
                                                                    id="post_q{{ $q->id }}_b" value="B"
                                                                    {{ $user_ans_post == 'B' ? 'checked' : '' }}
                                                                    {{ $hasil_post ? 'disabled' : 'required' }}>
                                                                <label class="form-check-label text-muted"
                                                                    for="post_q{{ $q->id }}_b">
                                                                    <span class="fw-bold text-dark">B.</span>
                                                                    {{ $q->pilihan_b }}
                                                                </label>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <div class="form-check">
                                                                <input class="form-check-input" type="radio"
                                                                    name="post_soal_{{ $q->id }}"
                                                                    id="post_q{{ $q->id }}_c" value="C"
                                                                    {{ $user_ans_post == 'C' ? 'checked' : '' }}
                                                                    {{ $hasil_post ? 'disabled' : 'required' }}>
                                                                <label class="form-check-label text-muted"
                                                                    for="post_q{{ $q->id }}_c">
                                                                    <span class="fw-bold text-dark">C.</span>
                                                                    {{ $q->pilihan_c }}
                                                                </label>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <div class="form-check">
                                                                <input class="form-check-input" type="radio"
                                                                    name="post_soal_{{ $q->id }}"
                                                                    id="post_q{{ $q->id }}_d" value="D"
                                                                    {{ $user_ans_post == 'D' ? 'checked' : '' }}
                                                                    {{ $hasil_post ? 'disabled' : 'required' }}>
                                                                <label class="form-check-label text-muted"
                                                                    for="post_q{{ $q->id }}_d">
                                                                    <span class="fw-bold text-dark">D.</span>
                                                                    {{ $q->pilihan_d }}
                                                                </label>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            @endforeach
                                            @if (count($questions_post) == 0)
                                                <p class="text-center text-muted my-5">Belum ada soal post test.</p>
                                            @elseif (!$hasil_post)
                                                <div class="text-end mt-4">
                                                    <button type="submit" class="btn btn-success px-4">Submit
                                                        JAWABAN POST TEST</button>
                                                </div>
                                            @endif
                                        </form>
                                    </div>
                                </div>
                            @else
                                <!-- Tugas Kelompok Tab Content -->
                                <div class="tab-pane fade show active" id="tugas" role="tabpanel" aria-labelledby="tugas-tab">
                                    <div class="py-4">
                                        @if($tugas_kelompok)
                                            <div class="alert alert-success border-0 shadow-sm d-flex align-items-center mb-4">
                                                <i class="bi bi-cloud-check-fill fs-4 me-3"></i>
                                                <div>
                                                    <h6 class="fw-bold mb-1">Tugas kelompok berhasil diunggah!</h6>
                                                    <p class="mb-0 small">Link: <a href="{{ $tugas_kelompok->link_tugas }}" target="_blank" class="fw-bold text-decoration-underline">{{ $tugas_kelompok->link_tugas }}</a></p>
                                                </div>
                                            </div>
                                        @endif

                                        <div class="card bg-light border-0">
                                            <div class="card-body p-4">
                                                <h5 class="fw-bold mb-3"><i class="bi bi-upload me-2 text-primary"></i> Upload Tugas Kelompok</h5>
                                                <p class="text-muted small mb-4">Silakan masukkan link tugas kelompok Anda (misalnya link Google Drive, Youtube, atau platform lainnya).</p>
                                                
                                                <form action="{{ route('modulposttest.upload-tugas') }}" method="POST">
                                                    @csrf
                                                    <div class="mb-3">
                                                        <label for="link_tugas" class="form-label fw-bold">Link Tugas</label>
                                                        <input type="url" name="link_tugas" id="link_tugas" class="form-control" placeholder="https://docs.google.com/..." value="{{ $tugas_kelompok->link_tugas ?? '' }}" required>
                                                        <div class="form-text mt-2">Pastikan link dapat diakses oleh admin.</div>
                                                    </div>
                                                    <div class="text-end">
                                                        <button type="submit" class="btn btn-primary px-4">
                                                            {{ $tugas_kelompok ? 'Perbarui Link Tugas' : 'Simpan Link Tugas' }}
                                                        </button>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endif
                        </div><!-- End Default Tabs -->
                    </div>
                </div>
            </div>

            <!-- Right Side: Sidebar Menu -->
            <div class="col-lg-4">
                <div class="card shadow-sm border-0 rounded-3">
                    <div class="card-body p-4">
                        <div class="text-center mb-4 pt-2">
                            <div class="d-flex align-items-center justify-content-center mb-3">
                                <img src="{{ asset('assets/img/logopkkmb.png') }}" alt="" style="max-height: 50px;"
                                    class="me-2"
                                    onerror="this.onerror=null; this.src='https://via.placeholder.com/50?text=LOGO'">
                                <h6 class="fw-bold mb-0 text-success" style="font-size: 0.8rem;">
                                    PENGENALAN KEHIDUPAN KAMPUS BAGI MAHASISWA BARU<br>
                                    (PKKMB) 2025 <span class="text-primary"><br>UNIVERSITAS IBNU SINA</span>
                                </h6>
                            </div>
                            <hr class="mt-4 mb-2">
                        </div>

                        <div class="module-list">
                            @foreach ($modules as $m)
                                <a href="?modul={{ $m['id'] }}"
                                    class="text-decoration-none module-item d-flex align-items-center py-3 {{ $id == $m['id'] ? 'active' : '' }}">
                                    <div
                                        class="module-number me-3 text-white fw-bold d-flex align-items-center justify-content-center shadow-sm">
                                        {{ $m['id'] }}
                                    </div>
                                    <div>
                                        <div class="fw-bold text-uppercase module-id" style="font-size: 0.75rem;">MODUL
                                            {{ $m['id'] }}:</div>
                                        <div class="text-muted module-title" style="font-size: 0.85rem;">
                                            {{ $m['title'] }}</div>
                                    </div>
                                </a>
                                @if (!$loop->last)
                                    <hr class="my-0 opacity-10">
                                @endif
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <style>
            .last-border-none:last-child {
                border-bottom: 0 !important;
            }

            .form-check-input {
                width: 1.25em;
                height: 1.25em;
                margin-top: 0.15em;
                border: 1px solid #ced4da;
            }

            .form-check-label {
                cursor: pointer;
                padding-left: 0.25rem;
                font-size: 0.9rem;
            }

            .module-item {
                border-radius: 10px;
                transition: all 0.2s ease-in-out;
                padding-left: 15px;
                padding-right: 15px;
            }

            .module-item:hover {
                background-color: #f8f9fa;
            }

            .module-item.active {
                background-color: #f0f7ff;
            }

            .module-number {
                width: 40px;
                height: 40px;
                background-color: #218838;
                border-radius: 50%;
                flex-shrink: 0;
            }

            .module-item.active .module-number {
                background-color: #0d6efd;
            }

            .module-item .module-id {
                color: #2b5ea9;
            }

            .module-item .module-title {
                color: #414d5f;
            }

            .nav-tabs-bordered .nav-link.active {
                color: #012970;
                background: none;
                border-bottom: 3px solid #012970;
            }

            .nav-tabs-bordered .nav-link {
                margin-bottom: -1px;
                border-bottom: 3px solid transparent;
                color: #555;
                font-weight: 600;
                padding: 10px 15px;
            }
        </style>
    </section>
@endsection


