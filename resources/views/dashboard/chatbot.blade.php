@if(!request()->routeIs('chat.*'))
@php
    $dokumenGlobal = \App\Models\Dokumen::latest('id')->first();
    $waGroupUrl = $dokumenGlobal->link_wa_group ?? 'https://chat.whatsapp.com/';
    $adminPersonalWa = $dokumenGlobal->no_wa_admin ?? '';
    $adminUser = \App\Models\User::where('role', 'admin')->first() ?? \App\Models\User::where('role', 'stafbaak')->first();
    $adminUserId = $adminUser ? $adminUser->id : null;
    $customFaqs = \App\Models\ChatbotFaq::where('is_active', true)->orderBy('urutan', 'asc')->orderBy('id', 'desc')->get();
@endphp

<style>
    /* Floating Chatbot Widget CSS */
    .chatbot-floating-btn {
        position: fixed !important;
        bottom: 25px !important;
        right: 75px !important;
        z-index: 99999 !important;
        width: 54px !important;
        height: 54px !important;
        border-radius: 50% !important;
        background: linear-gradient(135deg, #00A551 0%, #007A3B 100%) !important;
        color: #ffffff !important;
        display: flex !important;
        align-items: center !important;
        justify-content: center !important;
        box-shadow: 0 8px 24px rgba(0, 165, 81, 0.5) !important;
        cursor: pointer !important;
        transition: all 0.3s cubic-bezier(0.175, 0.885, 0.32, 1.275) !important;
        border: 2px solid #ffffff !important;
    }

    @media (max-width: 768px) {
        .chatbot-floating-btn {
            bottom: 75px !important;
            right: 15px !important;
            width: 48px !important;
            height: 48px !important;
        }
        #main, .main {
            padding-bottom: 75px !important;
        }
    }

    .chatbot-floating-btn:hover {
        transform: scale(1.1) rotate(5deg) !important;
        box-shadow: 0 12px 30px rgba(0, 165, 81, 0.65) !important;
    }

    .chatbot-pulse-ring {
        position: absolute;
        width: 100%;
        height: 100%;
        border-radius: 50%;
        background: rgba(0, 165, 81, 0.5);
        animation: chatbotPulse 2s infinite ease-out;
        z-index: -1;
    }

    @keyframes chatbotPulse {
        0% { transform: scale(1); opacity: 0.8; }
        100% { transform: scale(1.6); opacity: 0; }
    }

    .chatbot-drawer {
        position: fixed;
        bottom: 80px;
        right: 75px;
        width: 370px;
        max-width: calc(100vw - 40px);
        height: 520px;
        max-height: calc(100vh - 120px);
        background: #ffffff;
        border-radius: 20px;
        box-shadow: 0 15px 35px rgba(0, 0, 0, 0.2);
        z-index: 1060;
        display: none;
        flex-direction: column;
        overflow: hidden;
        border: 1px solid #e2e8f0;
        animation: chatbotSlideUp 0.3s cubic-bezier(0.175, 0.885, 0.32, 1.275);
    }

    @keyframes chatbotSlideUp {
        from { opacity: 0; transform: translateY(20px) scale(0.95); }
        to { opacity: 1; transform: translateY(0) scale(1); }
    }

    /* Mobile Responsiveness Improvements */
    @media (max-width: 576px) {
        .chatbot-floating-btn {
            bottom: 12px;
            right: 65px;
            width: 46px;
            height: 46px;
        }

        .chatbot-drawer {
            position: fixed;
            left: 12px !important;
            right: 12px !important;
            width: auto !important;
            max-width: none !important;
            bottom: 68px !important;
            height: calc(85vh - 70px) !important;
            max-height: 520px !important;
            border-radius: 16px;
        }

        .chatbot-header {
            padding: 12px 14px;
        }

        .chatbot-body {
            padding: 12px;
        }

        .chatbot-quick-btn {
            padding: 8px 12px;
            font-size: 0.76rem;
        }
    }

    .chatbot-header {
        background: linear-gradient(135deg, #00A551 0%, #006B33 100%);
        color: #ffffff;
        padding: 16px 20px;
        display: flex;
        align-items: center;
        justify-content: space-between;
    }

    .chatbot-body {
        flex: 1;
        padding: 16px;
        overflow-y: auto;
        background: #f8fafc;
        display: flex;
        flex-direction: column;
        gap: 12px;
    }

    .chatbot-msg {
        max-width: 85%;
        padding: 12px 16px;
        border-radius: 16px;
        font-size: 0.82rem;
        line-height: 1.45;
        word-wrap: break-word;
    }

    .chatbot-msg-bot {
        background: #ffffff;
        color: #1e293b;
        align-self: flex-start;
        border-bottom-left-radius: 4px;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
        border: 1px solid #f1f5f9;
    }

    .chatbot-msg-user {
        background: #00A551;
        color: #ffffff;
        align-self: flex-end;
        border-bottom-right-radius: 4px;
        box-shadow: 0 2px 8px rgba(0, 165, 81, 0.25);
    }

    .chatbot-quick-btn {
        background: #ffffff;
        border: 1px solid #cbd5e1;
        color: #0f172a;
        padding: 8px 14px;
        border-radius: 20px;
        font-size: 0.78rem;
        font-weight: 600;
        text-align: left;
        transition: all 0.2s ease;
        width: 100%;
        display: flex;
        align-items: center;
        justify-content: space-between;
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.05);
    }

    .chatbot-quick-btn:hover {
        background: #f0fdf4;
        border-color: #00A551;
        color: #00A551;
        transform: translateX(4px);
    }

    .chatbot-quick-btn-wa {
        background: linear-gradient(135deg, #25D366 0%, #128C7E 100%) !important;
        color: #ffffff !important;
        border: none !important;
        box-shadow: 0 4px 12px rgba(37, 211, 102, 0.3) !important;
    }

    .chatbot-quick-btn-wa:hover {
        transform: scale(1.02) !important;
        box-shadow: 0 6px 16px rgba(37, 211, 102, 0.45) !important;
    }

    .chatbot-footer {
        padding: 12px 16px;
        background: #ffffff;
        border-top: 1px solid #e2e8f0;
        display: flex;
        gap: 8px;
    }
</style>

{{-- Floating Toggle Button --}}
<div class="chatbot-floating-btn position-relative" id="chatbotToggleBtn" onclick="toggleChatbotDrawer()" title="Bantuan & FAQ Asisten PKKMB">
    <div class="chatbot-pulse-ring"></div>
    <i class="bi bi-whatsapp fs-2 text-white" id="chatbotIcon"></i>
    <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger shadow-sm" id="chatbotToggleBadge" style="display: none; font-size: 0.72rem; border: 2px solid #ffffff; padding: 4px 7px;">0</span>
</div>

{{-- Notification Toast Tooltip above button --}}
<div id="chatbotFloatingToast" class="position-fixed shadow-lg bg-dark text-white rounded-3 p-2 extra-small" style="display: none; bottom: 75px; right: 75px; z-index: 1060; max-width: 230px; cursor: pointer;" onclick="toggleChatbotDrawer()">
    <div class="d-flex align-items-center gap-1 fw-bold text-success mb-1">
        <i class="bi bi-chat-dots-fill"></i> Balasan Baru dari Admin
    </div>
    <div id="chatbotToastText" class="extra-small text-white-50 text-truncate"></div>
</div>

{{-- Chat Drawer Window --}}
<div class="chatbot-drawer" id="chatbotDrawer">
    {{-- Header --}}
    <div class="chatbot-header">
        <div class="d-flex align-items-center gap-2">
            <div class="bg-white rounded-circle p-2 d-flex align-items-center justify-content-center" style="width: 38px; height: 38px;">
                <i class="bi bi-robot text-success fs-5"></i>
            </div>
            <div>
                <h6 class="fw-bold mb-0 text-white" style="font-size: 0.92rem;">Asisten PKKMB UIS 🤖</h6>
                <small class="text-white-50 extra-small d-flex align-items-center gap-1">
                    <span class="bg-success rounded-circle d-inline-block" style="width: 7px; height: 7px;"></span> Online • 24/7 Bantuan
                </small>
            </div>
        </div>
        <button type="button" class="btn-close btn-close-white" onclick="toggleChatbotDrawer()" aria-label="Close"></button>
    </div>

    {{-- Chat Body --}}
    <div class="chatbot-body" id="chatbotBody">
        {{-- Welcome Message --}}
        <div class="chatbot-msg chatbot-msg-bot">
            <strong>Halo! 👋 Selamat Datang.</strong><br>
            Saya Asisten PKKMB UIS. Silakan pilih topik pertanyaan yang ingin Anda tanyakan di bawah ini:
        </div>

        {{-- Interactive Question Buttons Container --}}
        <div class="d-flex flex-column gap-2 mt-1" id="chatbotQuestionMenu">
            {{-- Default Basic FAQs --}}
            <button class="chatbot-quick-btn" onclick="askChatbot('absen')">
                <span>📌 Cara Absensi QR & Sesi</span>
                <i class="bi bi-chevron-right text-muted extra-small"></i>
            </button>

            <button class="chatbot-quick-btn" onclick="askChatbot('test')">
                <span>📝 Info Pre-Test & Post-Test</span>
                <i class="bi bi-chevron-right text-muted extra-small"></i>
            </button>

            <button class="chatbot-quick-btn" onclick="askChatbot('dokumen')">
                <span>📁 Buku Saku & Daftar Kelompok</span>
                <i class="bi bi-chevron-right text-muted extra-small"></i>
            </button>

            <button class="chatbot-quick-btn" onclick="askChatbot('atribut')">
                <span>👔 Atribut & Seragam PKKMB</span>
                <i class="bi bi-chevron-right text-muted extra-small"></i>
            </button>

            <button class="chatbot-quick-btn" onclick="askChatbot('akun')">
                <span>🔑 Kendala Login & Password</span>
                <i class="bi bi-chevron-right text-muted extra-small"></i>
            </button>

            {{-- Custom Questions from Admin DB --}}
            @foreach($customFaqs as $cFaq)
                <button class="chatbot-quick-btn" onclick="askCustomChatbot({{ json_encode($cFaq->pertanyaan) }}, {{ json_encode($cFaq->jawaban) }})">
                    <span>💡 {{ $cFaq->pertanyaan }}</span>
                    <i class="bi bi-chevron-right text-muted extra-small"></i>
                </button>
            @endforeach

            {{-- DIRECT INTERNAL LIVE CHAT ADMIN OPTION --}}
            @if($adminUserId)
                @if(Auth::user()->role == 'admin' || Auth::user()->role == 'stafbaak')
                    <a href="{{ route('chat.index', ['user_id' => $adminUserId]) }}" class="chatbot-quick-btn border-success text-success mt-2 py-2 text-decoration-none">
                        <span>💬 Chat Personal Admin (Live Chat)</span>
                        <i class="bi bi-chat-square-text-fill"></i>
                    </a>
                @else
                    <button class="chatbot-quick-btn border-success text-success mt-2 py-2" onclick="openDrawerLiveChat()">
                        <span>💬 Chat Personal Admin (Live Chat)</span>
                        <i class="bi bi-chat-square-text-fill"></i>
                    </button>
                @endif
            @endif

            {{-- DIRECT WA GROUP REDIRECTION OPTION --}}
            <button class="chatbot-quick-btn chatbot-quick-btn-wa mt-1 py-2" onclick="redirectToWaGroup()">
                <span>🌐 Tanya Panitia via WA Group</span>
                <i class="bi bi-box-arrow-up-right"></i>
            </button>
        </div>
    </div>

    {{-- Chat Footer Form --}}
    <div class="chatbot-footer">
        <input type="text" id="chatbotInput" class="form-control rounded-pill px-3 extra-small border" placeholder="Ketik kata kunci pertanyaan..." onkeypress="handleChatbotKeyPress(event)">
        <button type="button" class="btn btn-success rounded-circle d-flex align-items-center justify-content-center p-0 flex-shrink-0" style="width: 36px; height: 36px;" onclick="sendChatbotInput()">
            <i class="bi bi-send-fill text-white extra-small"></i>
        </button>
    </div>
</div>

<script>
    const globalWaGroupUrl = @json($waGroupUrl);

    function toggleChatbotDrawer() {
        const drawer = document.getElementById('chatbotDrawer');
        const icon = document.getElementById('chatbotIcon');
        const badge = document.getElementById('chatbotToggleBadge');
        const toast = document.getElementById('chatbotFloatingToast');

        if (drawer.style.display === 'flex') {
            drawer.style.display = 'none';
            icon.className = 'bi bi-whatsapp fs-2 text-white';
        } else {
            drawer.style.display = 'flex';
            icon.className = 'bi bi-x-lg fs-2 text-white';
            if (badge) badge.style.display = 'none';
            if (toast) toast.style.display = 'none';
            scrollToChatBottom();
        }
    }

    function scrollToChatBottom() {
        const body = document.getElementById('chatbotBody');
        setTimeout(() => {
            body.scrollTop = body.scrollHeight;
        }, 50);
    }

    function appendUserMessage(text) {
        const body = document.getElementById('chatbotBody');
        const msgDiv = document.createElement('div');
        msgDiv.className = 'chatbot-msg chatbot-msg-user';
        msgDiv.innerText = text;
        body.appendChild(msgDiv);
        scrollToChatBottom();
    }

    function appendBotMessage(htmlContent) {
        const body = document.getElementById('chatbotBody');
        const msgDiv = document.createElement('div');
        msgDiv.className = 'chatbot-msg chatbot-msg-bot';
        msgDiv.innerHTML = htmlContent;
        body.appendChild(msgDiv);
        scrollToChatBottom();
    }

    function redirectToWaGroup() {
        appendUserMessage("💬 Lainnya (Tanya Panitia via WA Group)");
        appendBotMessage("Mengarahkan Anda ke WhatsApp Group Panitia PKKMB UIS... 📲");
        setTimeout(() => {
            window.open(globalWaGroupUrl, '_blank');
        }, 600);
    }

    function askChatbot(type) {
        let questionText = "";
        let answerText = "";

        if (type === 'absen') {
            questionText = "📌 Cara Absensi QR & Sesi";
            answerText = `<div class="fw-bold text-success mb-2"><i class="bi bi-qr-code-scan me-1"></i> Cara Melakukan Absensi Kehadiran:</div>
            <ol class="ps-3 mb-3 text-secondary extra-small" style="line-height: 1.6;">
                <li class="mb-1">Buka menu <strong class="text-dark">Scan Absensi Mandiri</strong> pada Dashboard.</li>
                <li class="mb-1">Arahkan kamera HP ke <strong class="text-dark">QR Code Dynamic</strong> yang ditampilkan Panitia di layar utama.</li>
                <li class="mb-1">Absensi dilakukan 2x sehari (<strong class="text-dark">Waktu Datang & Waktu Pulang</strong>).</li>
            </ol>
            <div class="d-flex flex-wrap gap-1 mt-2">
                <a href="/absen-scan" class="btn btn-sm btn-success rounded-pill px-3 extra-small"><i class="bi bi-camera me-1"></i> Scan QR Sekarang</a>
                <button onclick="redirectToWaGroup()" class="btn btn-sm btn-outline-success rounded-pill px-3 extra-small"><i class="bi bi-whatsapp me-1"></i> Tanya via WA Group</button>
            </div>`;
        } else if (type === 'test') {
            questionText = "📝 Info Pre-Test & Post-Test";
            answerText = `<div class="fw-bold text-primary mb-2"><i class="bi bi-journal-check me-1"></i> Informasi Ujian Pre-Test & Post-Test:</div>
            <ul class="ps-3 mb-3 text-secondary extra-small" style="line-height: 1.6;">
                <li class="mb-1">Terdapat <strong class="text-dark">4 Modul Ujian</strong> Pre-Test & Post-Test.</li>
                <li class="mb-1">Kerjakan <strong class="text-dark">Pre-Test</strong> terlebih dahulu untuk membuka materi pembelajaran.</li>
                <li class="mb-1"><strong class="text-dark">Post-Test</strong> dapat dikerjakan setelah Anda mempelajari materi modul.</li>
            </ul>
            <div class="d-flex flex-wrap gap-1 mt-2">
                <a href="/modulposttest" class="btn btn-sm btn-primary rounded-pill px-3 extra-small"><i class="bi bi-journal-text me-1"></i> Buka Modul & Ujian</a>
                <button onclick="redirectToWaGroup()" class="btn btn-sm btn-outline-success rounded-pill px-3 extra-small"><i class="bi bi-whatsapp me-1"></i> Tanya via WA Group</button>
            </div>`;
        } else if (type === 'dokumen') {
            questionText = "📁 Buku Saku & Daftar Kelompok";
            answerText = `<div class="fw-bold text-info mb-2"><i class="bi bi-folder-check me-1"></i> Akses Dokumen Pendukung PKKMB:</div>
            <p class="text-secondary extra-small mb-3" style="line-height: 1.6;">
                Anda dapat melihat & mengunduh <strong class="text-dark">Buku Saku PKKMB, Rundown Acara,</strong> serta <strong class="text-dark">Daftar Kelompok</strong> melalui banner Dokumen Pendukung di Dashboard.
            </p>
            <div class="d-flex flex-wrap gap-1 mt-2">
                <a href="/dashboard" class="btn btn-sm btn-info text-white rounded-pill px-3 extra-small"><i class="bi bi-file-earmark-text me-1"></i> Lihat Dokumen</a>
                <button onclick="redirectToWaGroup()" class="btn btn-sm btn-outline-success rounded-pill px-3 extra-small"><i class="bi bi-whatsapp me-1"></i> Tanya via WA Group</button>
            </div>`;
        } else if (type === 'atribut') {
            questionText = "👔 Atribut & Seragam PKKMB";
            answerText = `<div class="fw-bold text-warning text-dark mb-2"><i class="bi bi-person-badge me-1"></i> Ketentuan Atribut & Seragam PKKMB:</div>
            <ul class="ps-3 mb-3 text-secondary extra-small" style="line-height: 1.6;">
                <li class="mb-1">Baju Kemeja Putih Lengan Panjang & Celana/Rok Hitam.</li>
                <li class="mb-1">Memakai Pita / Kokarde Nametag sesuai ketentuan.</li>
                <li class="mb-1">Rincian atribut lengkap dapat dicek di Buku Saku PKKMB.</li>
            </ul>
            <div class="mt-2">
                <button onclick="redirectToWaGroup()" class="btn btn-sm btn-success rounded-pill px-3 extra-small"><i class="bi bi-whatsapp me-1"></i> Tanya via WA Group</button>
            </div>`;
        } else if (type === 'akun') {
            questionText = "🔑 Kendala Login & Password";
            answerText = `<div class="fw-bold text-danger mb-2"><i class="bi bi-key me-1"></i> Bantuan Kendala Akun & Password:</div>
            <p class="text-secondary extra-small mb-3" style="line-height: 1.6;">
                Jika Anda mengalami kendala login, lupa password, atau nomor WhatsApp belum terdaftar, silakan hubungi Panitia BAAK atau kirim pesan di Group WA Panitia.
            </p>
            <div class="mt-2">
                <button onclick="redirectToWaGroup()" class="btn btn-sm btn-success rounded-pill px-3 extra-small"><i class="bi bi-whatsapp me-1"></i> Hubungi Panitia via WA Group</button>
            </div>`;
        }

        appendUserMessage(questionText);
        setTimeout(() => {
            appendBotMessage(answerText);
        }, 200);
    }

    function askCustomChatbot(question, answer) {
        appendUserMessage(question);
        setTimeout(() => {
            const formattedAnswer = `<div class="fw-bold text-dark mb-2"><i class="bi bi-chat-left-dots text-success me-1"></i> ${question}</div>
            <div class="text-secondary extra-small mb-3" style="line-height: 1.6;">${answer.replace(/\n/g, '<br>')}</div>
            <button onclick="redirectToWaGroup()" class="btn btn-sm btn-success rounded-pill px-3 extra-small mt-1"><i class="bi bi-whatsapp me-1"></i> Tanya via WA Group</button>`;
            appendBotMessage(formattedAnswer);
        }, 200);
    }

    function redirectToWaWithText(customText) {
        let targetUrl = globalWaGroupUrl;
        if (customText) {
            const encoded = encodeURIComponent("Halo Panitia PKKMB UIS, saya ingin bertanya:\n" + customText);
            if (targetUrl.includes('wa.me/') || targetUrl.includes('api.whatsapp.com/')) {
                const separator = targetUrl.includes('?') ? '&' : '?';
                targetUrl = targetUrl + separator + 'text=' + encoded;
            }
        }
        window.open(targetUrl, '_blank');
    }

    const adminUserId = @json($adminUserId);
    const currentUserRole = @json(Auth::user()->role ?? 'mahasiswa');
    let drawerChatInterval = null;
    let lastFetchedMsgCount = 0;
    let isHistoryLoaded = false;

    function renderDrawerMessage(msg, adminName) {
        if (msg.is_me) {
            appendUserMessage(msg.message);
        } else {
            appendBotMessage(`<div class="fw-bold text-success extra-small mb-1"><i class="bi bi-person-fill me-1"></i> Admin (${adminName})</div><div>${msg.message}</div>`);
        }
    }

    function fetchDrawerMessages() {
        if (!adminUserId || currentUserRole === 'admin' || currentUserRole === 'stafbaak') return;

        fetch(`/chat/messages/${adminUserId}`, {
            headers: {
                'Accept': 'application/json',
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
        .then(res => res.json())
        .then(data => {
            if (!data.success) return;

            const messages = data.messages || [];
            const adminName = (data.user && data.user.name) ? data.user.name : 'Admin';

            // First time load: render all past messages
            if (!isHistoryLoaded) {
                isHistoryLoaded = true;
                lastFetchedMsgCount = messages.length;

                if (messages.length > 0) {
                    const body = document.getElementById('chatbotBody');
                    const divider = document.createElement('div');
                    divider.className = 'text-center my-3';
                    divider.innerHTML = `<span class="badge bg-light text-muted border extra-small px-3 py-1">Riwayat Percakapan</span>`;
                    body.appendChild(divider);

                    messages.forEach(msg => {
                        renderDrawerMessage(msg, adminName);
                    });
                    scrollToChatBottom();
                }
                return;
            }

            // Real-time poll for new incoming messages
            if (messages.length > lastFetchedMsgCount) {
                const newMsgs = messages.slice(lastFetchedMsgCount);
                lastFetchedMsgCount = messages.length;

                let hasNewAdminReply = false;
                let lastAdminReplyText = '';

                newMsgs.forEach(msg => {
                    renderDrawerMessage(msg, adminName);
                    if (!msg.is_me) {
                        hasNewAdminReply = true;
                        lastAdminReplyText = msg.message;
                    }
                });

                scrollToChatBottom();

                if (hasNewAdminReply) {
                    const drawer = document.getElementById('chatbotDrawer');
                    if (drawer && drawer.style.display !== 'flex') {
                        const badge = document.getElementById('chatbotToggleBadge');
                        const toast = document.getElementById('chatbotFloatingToast');
                        const toastText = document.getElementById('chatbotToastText');
                        if (badge) {
                            badge.innerText = '1';
                            badge.style.display = 'inline-block';
                        }
                        if (toast && toastText) {
                            toastText.innerText = lastAdminReplyText;
                            toast.style.display = 'block';
                        }
                    }
                }
            }
        })
        .catch(err => console.error('Error fetching drawer chat messages:', err));
    }

    document.addEventListener('DOMContentLoaded', () => {
        if (currentUserRole !== 'admin' && currentUserRole !== 'stafbaak' && adminUserId) {
            fetchDrawerMessages();
            if (drawerChatInterval) clearInterval(drawerChatInterval);
            drawerChatInterval = setInterval(fetchDrawerMessages, 2500);
        }
    });

    function openDrawerLiveChat() {
        appendUserMessage("💬 Chat Personal Admin (Live Chat)");
        appendBotMessage("<div class='fw-bold text-success mb-1'><i class='bi bi-headset me-1'></i> Terhubung dengan Admin</div><div class='extra-small text-secondary'>Silakan ketik pertanyaan Anda pada kolom di bawah ini. Pesan Anda akan langsung diterima oleh Admin.</div>");
    }

    function sendChatbotInput() {
        const inputEl = document.getElementById('chatbotInput');
        const query = inputEl.value.trim();
        if (!query) return;

        appendUserMessage(query);
        inputEl.value = '';

        if (!adminUserId) return;

        // Send message directly to Admin in internal Live Chat system
        fetch('/chat/send', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({
                receiver_id: adminUserId,
                message: query
            })
        })
        .then(res => res.json())
        .then(data => {
            if (data.success) {
                if (currentUserRole === 'admin' || currentUserRole === 'stafbaak') {
                    window.location.href = `/chat?user_id=${adminUserId}`;
                } else {
                    appendBotMessage(`<div class="extra-small text-muted mb-1"><i class="bi bi-check-circle-fill text-success me-1"></i> Pesan terkirim ke Admin.</div><div class="extra-small">Admin akan membalas pesan Anda langsung di jendela chat ini.</div>`);
                    startDrawerChatPolling();
                }
            }
        })
        .catch(err => {
            console.error('Error sending message to admin:', err);
        });
    }

    function handleChatbotKeyPress(e) {
        if (e.key === 'Enter') {
            sendChatbotInput();
        }
    }
</script>
@endif
