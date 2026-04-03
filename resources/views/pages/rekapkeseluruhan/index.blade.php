@extends('dashboard.template')

@section('content')
    <div class="pagetitle d-flex justify-content-between align-items-center">
        <div>
            <h1>Rekapitulasi Keseluruhan</h1>
            <nav>
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
                    <li class="breadcrumb-item">Rekapitulasi</li>
                    <li class="breadcrumb-item active">Master Report</li>
                </ol>
            </nav>
        </div>
        <button onclick="masterExport()" class="btn btn-success text-white d-flex align-items-center gap-2 px-4 py-2">
            <i class="bi bi-file-earmark-spreadsheet-fill fs-4"></i>
            <span class="text-uppercase fw-bold ls-1">Export Laporan Lengkap (.xlsx)</span>
        </button>
    </div>

    <section class="section">
        <!-- Unified Info Legend -->
        <div class="row">
            <div class="col-12">
                <div class="alert border-0 bg-dark text-white rounded-4 p-4 mb-4 d-flex align-items-start gap-3 shadow-lg" style="border-left: 6px solid var(--uis-green) !important; background: #0f172a !important;">
                    <div class="bg-success-subtle rounded-circle p-2">
                        <i class="bi bi-info-circle-fill fs-4 text-emerald"></i>
                    </div>
                    <div>
                        <h6 class="fw-bold mb-1">Panduan Atribut Akademik & Pembobotan</h6>
                        <p class="mb-0 opacity-75 small">
                            <strong>A:</strong> Absensi (Pagi/Sore) | <strong>D:</strong> Kedisiplinan Atribut | <strong>M:</strong> Grade Pretest/Posttest | <strong>M5:</strong> Tugas Kelompok. 
                            <br>
                            <span class="text-warning fw-bold">Bobot Akhir: Test/Tugas (20%) + Absensi (50%) + Disiplin (30%).</span>
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Detail Table -->
        <div class="row">
            <div class="col-lg-12">
                <div class="card shadow-lg border-0" style="border-radius: 20px; background: rgba(255,255,255,1);">
                    <div class="card-body px-4 py-4">
                        <h5 class="fw-bold text-dark mb-4 d-flex align-items-center gap-2">
                            <i class="bi bi-journal-text text-emerald"></i>
                            Detail Aktivitas & Nilai Pre/Post Test
                        </h5>
                        <div id="detailed-table-container" class="table-responsive">
                            {!! $dtDetailed->table(['width' => '100%', 'class' => 'table table-hover align-middle table-rekap']) !!}
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Final Recap Table -->
        <div class="row">
            <div class="col-lg-12">
                <div class="card shadow-lg border-0" style="border-radius: 20px; background: rgba(255,255,255,1);">
                    <div class="card-body px-4 py-4">
                        <h5 class="fw-bold text-dark mb-4 d-flex align-items-center gap-2">
                            <i class="bi bi-award text-emerald"></i>
                            Rekapitulasi Nilai Akhir Terbobot
                        </h5>
                        <div id="final-table-container" class="table-responsive">
                            {!! $dtFinal->table(['width' => '100%', 'class' => 'table table-hover align-middle table-rekap']) !!}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    
@endsection

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/exceljs@4.3.0/dist/exceljs.min.js"></script>
    {!! $dtDetailed->scripts() !!}
    {!! $dtFinal->scripts() !!}
    <script>
        $(document).on('draw.dt', '#rekapkeseluruhan-table', function() {
            // Synchronize the Final Recap table whenever the Detailed table is redrawn
            const finalTable = $('#rekapnilaiakhir-table').DataTable();
            if (finalTable) {
                finalTable.ajax.reload(null, false); // Reload without resetting pagination
            }
        });

        async function masterExport() {
            // ... (rest of the export function remains unchanged)
            try {
                const instances = $.fn.dataTable.settings;
                if (!instances || instances.length < 2) return;

                const dt1 = $(instances[0].nTable).DataTable(); // Detail
                const dt2 = $(instances[1].nTable).DataTable(); // Final

                const cleanText = (str) => {
                    if (typeof str !== 'string') return str;
                    return str.replace(/<[^>]*>?/gm, '').replace(/\\s+/g, ' ').trim();
                };

                const workbook = new ExcelJS.Workbook();
                const worksheet = workbook.addWorksheet('Master Rekapitulasi');

                // Header Row 1
                const row1Values = [
                    "NO", "NAMA MAHASISWA",
                    "ABSEN HARI PERTAMA", "ABSEN HARI PERTAMA",
                    "ABSEN HARI KEDUA", "ABSEN HARI KEDUA",
                    "ABSEN HARI KETIGA", "ABSEN HARI KETIGA",
                    "KEDISIPLINAN HARI PERTAMA", "KEDISIPLINAN HARI PERTAMA", "KEDISIPLINAN HARI PERTAMA",
                    "KEDISIPLINAN HARI KEDUA", "KEDISIPLINAN HARI KEDUA", "KEDISIPLINAN HARI KEDUA",
                    "KEDISIPLINAN HARI KETIGA", "KEDISIPLINAN HARI KETIGA", "KEDISIPLINAN HARI KETIGA",
                    "MODUL 1", "MODUL 1",
                    "MODUL 2", "MODUL 2",
                    "MODUL 3", "MODUL 3",
                    "MODUL 4", "MODUL 4",
                    "MODUL 5",
                    "REKAPITULASI NILAI AKHIR (BOBOT)", "REKAPITULASI NILAI AKHIR (BOBOT)", "REKAPITULASI NILAI AKHIR (BOBOT)", "REKAPITULASI NILAI AKHIR (BOBOT)"
                ];
                worksheet.addRow(row1Values);

                // Header Row 2
                const row2Values = [
                    "", "",
                    "PAGI", "SORE",
                    "PAGI", "SORE",
                    "PAGI", "SORE",
                    "ATRIBUT", "WAKTU", "PERILAKU",
                    "ATRIBUT", "WAKTU", "PERILAKU",
                    "ATRIBUT", "WAKTU", "PERILAKU",
                    "PRE", "POST",
                    "PRE", "POST",
                    "PRE", "POST",
                    "PRE", "POST",
                    "TUGAS",
                    "TEST (20%)", "ABSEN (25%)", "DISIPLIN (30%)", "TOTAL NILAI"
                ];
                worksheet.addRow(row2Values);

                // Merges
                worksheet.mergeCells('A1:A2');
                worksheet.mergeCells('B1:B2');
                worksheet.mergeCells('C1:D1');
                worksheet.mergeCells('E1:F1');
                worksheet.mergeCells('G1:H1');
                worksheet.mergeCells('I1:K1');
                worksheet.mergeCells('L1:N1');
                worksheet.mergeCells('O1:Q1');
                worksheet.mergeCells('R1:S1');
                worksheet.mergeCells('T1:U1');
                worksheet.mergeCells('V1:W1');
                worksheet.mergeCells('X1:Y1');
                worksheet.mergeCells('Z1:Z2');
                worksheet.mergeCells('AA1:AD1');

                // Styling Headers
                const headerStyle = {
                    font: { bold: true, color: { argb: 'FF000000' } },
                    fill: { type: 'pattern', pattern: 'solid', fgColor: { argb: 'FFE6E6E6' } },
                    alignment: { vertical: 'middle', horizontal: 'center', wrapText: true },
                    border: {
                        top: { style: 'thin' },
                        left: { style: 'thin' },
                        bottom: { style: 'thin' },
                        right: { style: 'thin' }
                    }
                };

                [1, 2].forEach(num => {
                    worksheet.getRow(num).eachCell({ includeEmpty: true }, (cell) => {
                        cell.style = headerStyle;
                    });
                });

                // Map Recap
                const table2Map = {};
                dt2.rows().every(function() {
                    const node = this.node();
                    if (!node) return;
                    const cells = Array.from(node.querySelectorAll('td')).map(td => cleanText(td.innerText));
                    const name = cells[1] || "";
                    table2Map[name] = [cells[2], cells[3], cells[4], cells[5]]; 
                });

                // Add Data Rows
                dt1.rows().every(function() {
                    const node = this.node();
                    if (!node) return;
                    
                    const cells1 = Array.from(node.querySelectorAll('td')).map(td => cleanText(td.innerText));
                    const name = cells1[1] || "";
                    const cells2 = table2Map[name] || ["-", "-", "-", "-"];
                    
                    const newRow = worksheet.addRow([...cells1, ...cells2]);
                    newRow.eachCell({ includeEmpty: true }, (cell, colNumber) => {
                        cell.border = {
                            top: { style: 'thin' },
                            left: { style: 'thin' },
                            bottom: { style: 'thin' },
                            right: { style: 'thin' }
                        };
                        
                        // Special Case: Column B (NAMA) is Left Aligned
                        if (colNumber === 2) {
                            cell.alignment = { vertical: 'middle', horizontal: 'left', indent: 1 };
                        } else {
                            cell.alignment = { vertical: 'middle', horizontal: 'center' };
                        }
                    });
                });

                // Set Column Widths
                worksheet.columns = [
                    { width: 5 }, { width: 45 }, // No, Name (wider for names)
                    { width: 10 }, { width: 10 }, { width: 10 }, { width: 10 }, { width: 10 }, { width: 10 }, // Absen
                    { width: 12 }, { width: 12 }, { width: 12 }, // D1
                    { width: 12 }, { width: 12 }, { width: 12 }, // D2
                    { width: 12 }, { width: 12 }, { width: 12 }, // D3
                    { width: 8 }, { width: 8 }, { width: 8 }, { width: 8 }, { width: 8 }, { width: 8 }, { width: 8 }, { width: 8 }, // Modul
                    { width: 12 }, // Tugas
                    { width: 15 }, { width: 15 }, { width: 15 }, { width: 15 } // Recap
                ];

                const buffer = await workbook.xlsx.writeBuffer();
                const blob = new Blob([buffer], { type: 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet' });
                const url = window.URL.createObjectURL(blob);
                const anchor = document.createElement('a');
                anchor.href = url;
                anchor.download = 'MASTER_PKKMB_CENTRAL_REPORT.xlsx';
                anchor.click();

            } catch (err) {
                console.error("Master Export Styling Failure:", err);
            }
        }
    </script>
@endpush

<style>
    :root {
        --uis-green: #00A551;
        --emerald-dark: #087C39;
        --uis-yellow: #FFF742;
    }

    .btn-synergy-master {
        background: linear-gradient(45deg, var(--emerald-dark), var(--uis-green));
        color: #fff;
        border: none;
        padding: 0.8rem 2rem;
        border-radius: 14px;
        transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
        box-shadow: 0 10px 20px -5px rgba(0, 165, 81, 0.4);
        border: 1px solid rgba(255, 255, 255, 0.1);
        text-shadow: 0 1px 2px rgba(0,0,0,0.2);
    }

    .btn-synergy-master:hover {
        transform: translateY(-4px) scale(1.03);
        box-shadow: 0 20px 30px -5px rgba(0, 165, 81, 0.6);
        color: #fff;
        background: linear-gradient(45deg, var(--uis-green), var(--emerald-dark));
    }

    .ls-1 { letter-spacing: 1px; }

    .table-rekap thead th {
        background: #f8fafc !important;
        color: #334155 !important;
        font-weight: 800 !important;
        text-transform: uppercase;
        font-size: 0.65rem !important;
        padding: 1.2rem 0.75rem !important;
        border-bottom: 2px solid #e2e8f0 !important;
        text-align: center !important;
    }

    .table-rekap tbody td {
        padding: 0.8rem 0.5rem !important;
        font-size: 0.8rem !important;
        font-weight: 500;
        color: #1e293b;
        border-bottom: 1px solid #f1f5f9 !important;
        text-align: center !important;
    }

    .text-emerald { color: var(--uis-green) !important; }
    
    .card {
        transition: all 0.3s ease;
        border-radius: 20px !important;
    }
    
    .card:hover {
        transform: translateY(-5px);
        box-shadow: 0 20px 40px rgba(0,0,0,0.08) !important;
    }
</style>
