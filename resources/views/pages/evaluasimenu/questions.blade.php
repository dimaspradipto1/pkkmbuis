@extends('dashboard.template')

@section('content')
<style>
    .card-header-green {
        background-color: #356B3A !important;
        color: #ffffff !important;
    }
    .card-header-green h5, 
    .card-header-green .card-title {
        color: #ffffff !important;
        margin: 0;
    }
    .question-card {
        background: #ffffff;
        border: 1px solid #e1e7ec;
        border-radius: 8px;
        padding: 18px 20px;
        margin-bottom: 16px;
        box-shadow: 0 2px 6px rgba(0,0,0,0.02);
        transition: all 0.2s ease;
    }
    .question-card:hover {
        border-color: #356B3A;
    }
    .question-badge {
        background-color: #356B3A !important;
        color: #ffffff !important;
        font-size: 0.9rem;
        font-weight: 600;
        padding: 6px 12px;
        border-radius: 6px;
    }
    .saran-badge {
        background-color: #0d6efd !important;
        color: #ffffff !important;
        font-size: 0.9rem;
        font-weight: 600;
        padding: 6px 12px;
        border-radius: 6px;
    }
</style>

<div class="pagetitle">
    <h1>Kelola Soal / Pertanyaan Evaluasi</h1>
    <nav>
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
            <li class="breadcrumb-item"><a href="{{ route('evaluasimenu.index') }}">Pengaturan Menu Evaluasi</a></li>
            <li class="breadcrumb-item active">Kelola Soal</li>
        </ol>
    </nav>
</div>

<section class="section">
    <div class="row">
        <div class="col-lg-12">
            <div class="card shadow-sm border-0">
                <div class="card-header card-header-green py-3">
                    <h5 class="card-title fw-bold" style="color: #ffffff !important;">
                        <i class="bi bi-pencil-square me-2"></i> Input / Edit Soal Evaluasi: {{ $menu->nama }}
                    </h5>
                </div>
                <div class="card-body mt-3">
                    <div class="alert alert-info border-0 shadow-sm d-flex align-items-center mb-4">
                        <i class="bi bi-info-circle-fill fs-3 me-3 text-info"></i>
                        <div>
                            <h6 class="fw-bold mb-1">Panduan Pengaturan Soal & Saran Custom</h6>
                            <p class="mb-0 small text-secondary">
                                Anda dapat menambah, mengedit, atau menghapus pertanyaan skala (1-4) dan pertanyaan Saran & Masukan (Essai). Pertanyaan yang disimpan akan langsung tampil pada formulir evaluasi mahasiswa.
                            </p>
                        </div>
                    </div>

                    <form action="{{ route('evaluasimenu.updateQuestions', $menu->id) }}" method="POST" id="questionsForm">
                        @csrf

                        <!-- SECTION 1: PERTANYAAN SKALA -->
                        <div class="d-flex justify-content-between align-items-center mb-3 pt-2">
                            <h6 class="fw-bold text-dark mb-0 fs-6">
                                <i class="bi bi-star-fill text-warning me-1"></i> Pertanyaan Skala Penilaian (1 - 4)
                            </h6>
                            <button type="button" class="btn btn-sm btn-success px-3 shadow-sm rounded-pill" onclick="addRatingQuestion()">
                                <i class="bi bi-plus-circle me-1"></i> Tambah Soal Skala
                            </button>
                        </div>

                        <div id="ratingQuestionsContainer">
                            @php $num = 1; @endphp
                            @foreach($questions as $key => $questionText)
                                <div class="question-card rating-question-item" id="rating_row_{{ $num }}">
                                    <div class="d-flex justify-content-between align-items-center mb-2">
                                        <span class="badge question-badge rating-number-label">Soal Skala #{{ $num }}</span>
                                        <button type="button" class="btn btn-sm btn-outline-danger border-0 rounded-circle btn-remove-q" onclick="removeRatingQuestion(this)" title="Hapus Soal Ini">
                                            <i class="bi bi-trash-fill fs-6"></i>
                                        </button>
                                    </div>
                                    <textarea 
                                        name="questions[]" 
                                        class="form-control bg-white" 
                                        rows="2" 
                                        placeholder="Masukkan teks soal skala..."
                                        required>{{ old('questions.'.$loop->index, $questionText) }}</textarea>
                                </div>
                                @php $num++; @endphp
                            @endforeach
                        </div>

                        <hr class="my-4">

                        <!-- SECTION 2: PERTANYAAN SARAN & MASUKAN (ESSAI) -->
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <div>
                                <h6 class="fw-bold text-dark mb-1 fs-6">
                                    <i class="bi bi-chat-left-text-fill text-primary me-1"></i> Pertanyaan Isian Teks / Saran & Masukan (Essai)
                                </h6>
                                <p class="text-muted small mb-0">Hapus semua jika tidak ingin menampilkan kolom isian saran.</p>
                            </div>
                            <button type="button" class="btn btn-sm btn-primary px-3 shadow-sm rounded-pill" onclick="addSaranQuestion()">
                                <i class="bi bi-plus-circle me-1"></i> Tambah Kolom Saran
                            </button>
                        </div>

                        <div id="saranQuestionsContainer">
                            @php $sNum = 1; @endphp
                            @foreach($saranQuestions as $sKey => $saranText)
                                <div class="question-card saran-question-item" id="saran_row_{{ $sNum }}">
                                    <div class="d-flex justify-content-between align-items-center mb-2">
                                        <span class="badge saran-badge saran-number-label">Saran #{{ $sNum }}</span>
                                        <button type="button" class="btn btn-sm btn-outline-danger border-0 rounded-circle btn-remove-q" onclick="removeSaranQuestion(this)" title="Hapus Kolom Saran Ini">
                                            <i class="bi bi-trash-fill fs-6"></i>
                                        </button>
                                    </div>
                                    <textarea 
                                        name="saran_questions[]" 
                                        class="form-control bg-white" 
                                        rows="2" 
                                        placeholder="Contoh: Berikan saran dan masukan terhadap pemateri"
                                        required>{{ old('saran_questions.'.$loop->index, $saranText) }}</textarea>
                                </div>
                                @php $sNum++; @endphp
                            @endforeach
                        </div>

                        <div class="d-flex justify-content-between align-items-center mt-4 pt-3 border-top">
                            <div>
                                <a href="{{ route('evaluasimenu.index') }}" class="btn btn-secondary px-4">
                                    <i class="bi bi-arrow-left me-1"></i> Kembali
                                </a>
                            </div>
                            <div class="d-flex gap-2">
                                <button type="button" class="btn btn-outline-danger px-3" onclick="if(confirm('Apakah Anda yakin ingin mengembalikan semua soal dan saran ke teks bawaan/default?')) { document.getElementById('resetForm').submit(); }">
                                    <i class="bi bi-arrow-counterclockwise me-1"></i> Reset ke Default
                                </button>
                                <button type="submit" class="btn btn-primary px-4 shadow-sm">
                                    <i class="bi bi-save me-1"></i> Simpan Semua Soal
                                </button>
                            </div>
                        </div>
                    </form>

                    <form id="resetForm" action="{{ route('evaluasimenu.resetQuestions', $menu->id) }}" method="POST" style="display: none;">
                        @csrf
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>

@push('scripts')
<script>
    function updateRatingNumbers() {
        const items = document.querySelectorAll('.rating-question-item');
        items.forEach((item, idx) => {
            const label = item.querySelector('.rating-number-label');
            if (label) {
                label.textContent = `Soal Skala #${idx + 1}`;
            }
        });
    }

    function updateSaranNumbers() {
        const items = document.querySelectorAll('.saran-question-item');
        items.forEach((item, idx) => {
            const label = item.querySelector('.saran-number-label');
            if (label) {
                label.textContent = `Saran #${idx + 1}`;
            }
        });
    }

    function addRatingQuestion(text = '') {
        const container = document.getElementById('ratingQuestionsContainer');
        const count = container.querySelectorAll('.rating-question-item').length + 1;

        const div = document.createElement('div');
        div.className = 'question-card rating-question-item';
        div.innerHTML = `
            <div class="d-flex justify-content-between align-items-center mb-2">
                <span class="badge question-badge rating-number-label">Soal Skala #${count}</span>
                <button type="button" class="btn btn-sm btn-outline-danger border-0 rounded-circle btn-remove-q" onclick="removeRatingQuestion(this)" title="Hapus Soal Ini">
                    <i class="bi bi-trash-fill fs-6"></i>
                </button>
            </div>
            <textarea 
                name="questions[]" 
                class="form-control bg-white" 
                rows="2" 
                placeholder="Masukkan teks soal skala..."
                required>${text}</textarea>
        `;
        container.appendChild(div);
        updateRatingNumbers();
        div.querySelector('textarea').focus();
    }

    function removeRatingQuestion(btn) {
        const container = document.getElementById('ratingQuestionsContainer');
        const items = container.querySelectorAll('.rating-question-item');
        if (items.length <= 1) {
            alert('Minimal harus ada 1 soal skala evaluasi.');
            return;
        }
        btn.closest('.rating-question-item').remove();
        updateRatingNumbers();
    }

    function addSaranQuestion(text = '') {
        const container = document.getElementById('saranQuestionsContainer');
        const count = container.querySelectorAll('.saran-question-item').length + 1;

        const div = document.createElement('div');
        div.className = 'question-card saran-question-item';
        div.innerHTML = `
            <div class="d-flex justify-content-between align-items-center mb-2">
                <span class="badge saran-badge saran-number-label">Saran #${count}</span>
                <button type="button" class="btn btn-sm btn-outline-danger border-0 rounded-circle btn-remove-q" onclick="removeSaranQuestion(this)" title="Hapus Kolom Saran Ini">
                    <i class="bi bi-trash-fill fs-6"></i>
                </button>
            </div>
            <textarea 
                name="saran_questions[]" 
                class="form-control bg-white" 
                rows="2" 
                placeholder="Contoh: Berikan saran dan masukan..."
                required>${text}</textarea>
        `;
        container.appendChild(div);
        updateSaranNumbers();
        div.querySelector('textarea').focus();
    }

    function removeSaranQuestion(btn) {
        btn.closest('.saran-question-item').remove();
        updateSaranNumbers();
    }
</script>
@endpush
@endsection
