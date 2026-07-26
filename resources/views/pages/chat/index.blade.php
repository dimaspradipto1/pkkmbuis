@extends('dashboard.template')

@section('content')
<style>
    /* WhatsApp Web Style Layout CSS */
    .whatsapp-container {
        display: flex;
        height: calc(100vh - 185px);
        min-height: 480px;
        background: #ffffff;
        border-radius: 16px;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.08);
        overflow: hidden;
        border: 1px solid #e2e8f0;
    }

    /* Left Sidebar Contacts */
    .wa-contacts-sidebar {
        width: 350px;
        min-width: 300px;
        background: #ffffff;
        border-right: 1px solid #e2e8f0;
        display: flex;
        flex-direction: column;
    }

    .wa-sidebar-header {
        background: #f0f2f5;
        padding: 14px 16px;
        border-bottom: 1px solid #e2e8f0;
    }

    .wa-search-box {
        padding: 10px 14px;
        background: #ffffff;
        border-bottom: 1px solid #f1f5f9;
    }

    .wa-contact-list {
        flex: 1;
        overflow-y: auto;
    }

    .wa-contact-item {
        display: flex;
        align-items: center;
        gap: 12px;
        padding: 12px 16px;
        border-bottom: 1px solid #f8fafc;
        cursor: pointer;
        transition: background 0.15s ease;
    }

    .wa-contact-item:hover, .wa-contact-item.active {
        background: #f0f2f5;
    }

    .wa-avatar {
        width: 48px;
        height: 48px;
        border-radius: 50%;
        background: linear-gradient(135deg, #00A551 0%, #007A3B 100%);
        color: #ffffff;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 700;
        font-size: 1.1rem;
        flex-shrink: 0;
    }

    .wa-contact-info {
        flex: 1;
        min-width: 0;
    }

    .wa-contact-name {
        font-weight: 600;
        font-size: 0.9rem;
        color: #0f172a;
        margin-bottom: 2px;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }

    .wa-contact-lastmsg {
        font-size: 0.78rem;
        color: #64748b;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }

    .wa-contact-meta {
        display: flex;
        flex-direction: column;
        align-items: flex-end;
        gap: 4px;
        flex-shrink: 0;
    }

    .wa-contact-time {
        font-size: 0.7rem;
        color: #94a3b8;
    }

    .wa-unread-badge {
        background: #25D366;
        color: #ffffff;
        font-size: 0.68rem;
        font-weight: 700;
        border-radius: 50%;
        min-width: 20px;
        height: 20px;
        padding: 0 6px;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    /* Right Active Chat Room */
    .wa-chat-room {
        flex: 1;
        display: flex;
        flex-direction: column;
        background: #efeae2;
        background-image: radial-gradient(#cbd5e1 1px, transparent 1px);
        background-size: 20px 20px;
    }

    .wa-chat-header {
        background: #f0f2f5;
        padding: 12px 20px;
        border-bottom: 1px solid #e2e8f0;
        display: flex;
        align-items: center;
        justify-content: space-between;
    }

    .wa-chat-messages {
        flex: 1;
        padding: 20px;
        overflow-y: auto;
        display: flex;
        flex-direction: column;
        gap: 10px;
    }

    /* Message Bubbles */
    .wa-msg-bubble {
        max-width: 70%;
        padding: 9px 14px;
        border-radius: 12px;
        font-size: 0.86rem;
        line-height: 1.45;
        position: relative;
        word-wrap: break-word;
        box-shadow: 0 1px 2px rgba(0, 0, 0, 0.1);
    }

    .wa-msg-me {
        background: #d9fdd3;
        color: #111b21;
        align-self: flex-end;
        border-top-right-radius: 2px;
    }

    .wa-msg-other {
        background: #ffffff;
        color: #111b21;
        align-self: flex-start;
        border-top-left-radius: 2px;
    }

    .wa-msg-footer {
        display: flex;
        align-items: center;
        justify-content: flex-end;
        gap: 4px;
        font-size: 0.65rem;
        color: #667781;
        margin-top: 4px;
    }

    .wa-chat-footer {
        background: #f0f2f5;
        padding: 12px 16px;
        border-top: 1px solid #e2e8f0;
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .wa-input {
        background: #ffffff;
        border: 1px solid #cbd5e1;
        border-radius: 24px;
        padding: 10px 18px;
        font-size: 0.88rem;
    }

    .wa-input:focus {
        border-color: #00A551;
        box-shadow: none;
    }

    .wa-empty-state {
        flex: 1;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        background: #f8fafc;
        color: #64748b;
        text-align: center;
        padding: 30px;
    }

    /* Mobile Responsive Sidebar Toggle */
    @media (max-width: 768px) {
        .whatsapp-container {
            position: relative;
        }
        .wa-contacts-sidebar {
            width: 100%;
            display: flex;
        }
        .wa-chat-room {
            display: none;
            width: 100%;
        }
        .whatsapp-container.show-chat .wa-contacts-sidebar {
            display: none;
        }
        .whatsapp-container.show-chat .wa-chat-room {
            display: flex;
        }
    }
</style>

<div class="pagetitle">
    <h1>Pesan & Diskusi Direct (Live Chat)</h1>
    <nav>
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
            <li class="breadcrumb-item active">Pesan Live Chat</li>
        </ol>
    </nav>
</div>

<section class="section">
    <div class="whatsapp-container" id="whatsappContainer">
        {{-- Left Contacts Sidebar --}}
        <div class="wa-contacts-sidebar">
            <div class="wa-sidebar-header">
                <div class="d-flex align-items-center justify-content-between">
                    <div class="d-flex align-items-center gap-2">
                        <i class="bi bi-chat-square-text-fill text-success fs-4"></i>
                        <h6 class="fw-bold mb-0 text-dark">Direct Chat UIS</h6>
                    </div>
                    <span class="badge rounded-pill px-3 py-1.5 fw-bold" style="background: linear-gradient(135deg, #00A551 0%, #007A3B 100%); color: #ffffff !important; font-size: 0.72rem; box-shadow: 0 2px 6px rgba(0, 165, 81, 0.3);">
                        <i class="bi bi-circle-fill me-1" style="font-size: 0.45rem; vertical-align: middle;"></i> Online
                    </span>
                </div>
            </div>

            <div class="wa-search-box">
                <div class="input-group">
                    <span class="input-group-text bg-light border-0 text-muted"><i class="bi bi-search"></i></span>
                    <input type="text" id="contactSearchInput" class="form-control bg-light border-0 extra-small" placeholder="Cari pengguna atau NIM..." onkeyup="filterContacts()">
                </div>
            </div>

            <div class="wa-contact-list" id="contactList">
                @forelse($contacts as $contact)
                    <div class="wa-contact-item @if($activeUser && $activeUser->id == $contact->id) active @endif" 
                         id="contact-item-{{ $contact->id }}" 
                         onclick="selectContact({{ $contact->id }})">
                        <div class="wa-avatar">
                            {{ strtoupper(substr($contact->name, 0, 1)) }}
                        </div>
                        <div class="wa-contact-info">
                            <div class="wa-contact-name">{{ $contact->name }}</div>
                            <div class="wa-contact-lastmsg">
                                @if($contact->last_message)
                                    @if($contact->last_message->sender_id == Auth::id())
                                        <span class="text-primary me-1"><i class="bi bi-check2-all"></i></span>
                                    @endif
                                    {{ Str::limit($contact->last_message->message, 28) }}
                                @else
                                    <span class="fst-italic text-muted">Belum ada percakapan</span>
                                @endif
                            </div>
                        </div>
                        <div class="wa-contact-meta">
                            <div class="wa-contact-time">{{ $contact->last_message_time }}</div>
                            @if($contact->unread_count > 0)
                                <div class="wa-unread-badge" id="unread-badge-{{ $contact->id }}">{{ $contact->unread_count }}</div>
                            @endif
                        </div>
                    </div>
                @empty
                    <div class="p-4 text-center text-muted extra-small">
                        <i class="bi bi-person-x fs-3 d-block mb-2"></i>
                        Tidak ada pengguna lain yang ditemukan.
                    </div>
                @endforelse
            </div>
        </div>

        {{-- Right Chat Room --}}
        <div class="wa-chat-room" id="chatRoom">
            {{-- Empty State when no contact selected --}}
            <div class="wa-empty-state" id="emptyState" style="@if($activeUser) display: none; @endif">
                <div class="bg-success bg-opacity-10 p-4 rounded-circle mb-3">
                    <i class="bi bi-chat-dots-fill text-success" style="font-size: 3rem;"></i>
                </div>
                <h5 class="fw-bold text-dark mb-1">Fitur Pesan Direct PKKMB UIS</h5>
                <p class="text-muted extra-small max-w-sm mb-3">Pilih salah satu kontak mahasiswa, admin, atau panitia di sebelah kiri untuk memulai percakapan personal secara langsung.</p>
                <span class="badge bg-light text-secondary border px-3 py-2 rounded-pill extra-small"><i class="bi bi-shield-lock-fill text-success me-1"></i> Terenkripsi & Terintegrasi Sistem UIS</span>
            </div>

            {{-- Active Chat Screen --}}
            <div class="flex-column h-100" id="activeChatScreen" style="@if(!$activeUser) display: none; @else display: flex; @endif">
                {{-- Active User Header --}}
                <div class="wa-chat-header">
                    <div class="d-flex align-items-center gap-3">
                        <button type="button" class="btn btn-sm btn-light d-md-none rounded-circle" onclick="backToContacts()">
                            <i class="bi bi-arrow-left"></i>
                        </button>
                        <div class="wa-avatar" id="activeAvatar">
                            {{ $activeUser ? strtoupper(substr($activeUser->name, 0, 1)) : '?' }}
                        </div>
                        <div>
                            <h6 class="fw-bold mb-0 text-dark" id="activeUserName">{{ $activeUser->name ?? '' }}</h6>
                            <small class="text-muted extra-small d-flex align-items-center gap-2" id="activeUserMeta">
                                <span class="badge bg-primary bg-opacity-10 text-primary px-2 py-0" id="activeUserRole">{{ $activeUser ? strtoupper($activeUser->role) : '' }}</span>
                                <span id="activeUserProdi">{{ $activeUser->program_studi ?? '' }}</span>
                            </small>
                        </div>
                    </div>

                    <div class="d-flex align-items-center gap-2">
                        <a href="#" id="activeWaDirectBtn" target="_blank" class="btn btn-sm btn-outline-success rounded-pill px-3 extra-small">
                            <i class="bi bi-whatsapp me-1"></i> WA Direct
                        </a>
                    </div>
                </div>

                {{-- Messages Container --}}
                <div class="wa-chat-messages" id="messagesContainer">
                    {{-- Messages rendered dynamically via JS --}}
                </div>

                {{-- Input Bar --}}
                <div class="wa-chat-footer">
                    <input type="text" id="messageInput" class="form-control wa-input" placeholder="Ketik pesan..." onkeypress="handleKeyPress(event)">
                    <button type="button" class="btn btn-success rounded-circle d-flex align-items-center justify-content-center flex-shrink-0" style="width: 44px; height: 44px;" onclick="submitMessage()">
                        <i class="bi bi-send-fill text-white"></i>
                    </button>
                </div>
            </div>
        </div>
    </div>
</section>

<script>
    let currentActiveUserId = @json($activeUser ? $activeUser->id : null);
    let pollingInterval = null;

    document.addEventListener('DOMContentLoaded', function() {
        if (currentActiveUserId) {
            selectContact(currentActiveUserId);
        }
    });

    function selectContact(userId) {
        currentActiveUserId = userId;

        // UI highlight active contact
        document.querySelectorAll('.wa-contact-item').forEach(el => el.classList.remove('active'));
        const activeItem = document.getElementById('contact-item-' + userId);
        if (activeItem) activeItem.classList.add('active');

        // Hide unread badge
        const badge = document.getElementById('unread-badge-' + userId);
        if (badge) badge.style.display = 'none';

        // Toggle screens
        document.getElementById('emptyState').style.display = 'none';
        document.getElementById('activeChatScreen').style.display = 'flex';
        document.getElementById('whatsappContainer').classList.add('show-chat');

        // Fetch messages
        loadMessages(userId);

        // Reset Polling
        if (pollingInterval) clearInterval(pollingInterval);
        pollingInterval = setInterval(() => {
            if (currentActiveUserId) {
                loadMessages(currentActiveUserId, true);
            }
        }, 2500);
    }

    function loadMessages(userId, isSilent = false) {
        fetch(`/chat/messages/${userId}`, {
            headers: {
                'Accept': 'application/json',
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
            .then(res => res.json())
            .then(data => {
                if (data.success) {
                    // Update Header
                    document.getElementById('activeAvatar').innerText = data.user.name.charAt(0).toUpperCase();
                    document.getElementById('activeUserName').innerText = data.user.name;
                    document.getElementById('activeUserRole').innerText = data.user.role;
                    document.getElementById('activeUserProdi').innerText = data.user.program_studi;
                    
                    const waBtn = document.getElementById('activeWaDirectBtn');
                    if (data.user.no_wa && data.user.no_wa !== '-') {
                        let phone = data.user.no_wa.replace(/[^0-9]/g, '');
                        if (phone.startsWith('0')) phone = '62' + phone.substring(1);
                        waBtn.href = `https://wa.me/${phone}`;
                        waBtn.style.display = 'inline-block';
                    } else {
                        waBtn.style.display = 'none';
                    }

                    // Render Messages
                    const container = document.getElementById('messagesContainer');
                    const isAtBottom = (container.scrollHeight - container.clientHeight <= container.scrollTop + 100);

                    let html = '';
                    data.messages.forEach(msg => {
                        const bubbleClass = msg.is_me ? 'wa-msg-me' : 'wa-msg-other';
                        const checkIcon = msg.is_me ? `<span class="ms-1 ${msg.is_read ? 'text-primary' : 'text-muted'}"><i class="bi bi-check2-all"></i></span>` : '';

                        html += `
                            <div class="wa-msg-bubble ${bubbleClass}">
                                <div>${msg.message}</div>
                                <div class="wa-msg-footer">
                                    <span>${msg.time}</span>
                                    ${checkIcon}
                                </div>
                            </div>
                        `;
                    });

                    container.innerHTML = html;

                    if (!isSilent || isAtBottom) {
                        container.scrollTop = container.scrollHeight;
                    }
                }
            })
            .catch(err => console.error('Error fetching chat messages:', err));
    }

    function submitMessage() {
        const input = document.getElementById('messageInput');
        const text = input.value.trim();
        if (!text || !currentActiveUserId) return;

        input.value = '';

        // Optimistic UI Rendering for instant feedback
        const container = document.getElementById('messagesContainer');
        const now = new Date();
        const timeStr = now.getHours().toString().padStart(2, '0') + ':' + now.getMinutes().toString().padStart(2, '0');
        const tempMsgHtml = `
            <div class="wa-msg-bubble wa-msg-me">
                <div>${escapeHtml(text)}</div>
                <div class="wa-msg-footer">
                    <span>${timeStr}</span>
                    <span class="ms-1 text-muted"><i class="bi bi-clock"></i></span>
                </div>
            </div>
        `;
        container.insertAdjacentHTML('beforeend', tempMsgHtml);
        container.scrollTop = container.scrollHeight;

        fetch('/chat/send', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Accept': 'application/json',
                'X-Requested-With': 'XMLHttpRequest'
            },
            body: JSON.stringify({
                receiver_id: currentActiveUserId,
                message: text
            })
        })
        .then(res => res.json())
        .then(data => {
            if (data.success) {
                loadMessages(currentActiveUserId, true);
            }
        })
        .catch(err => console.error('Error sending message:', err));
    }

    function escapeHtml(str) {
        return str.replace(/&/g, "&amp;").replace(/</g, "&lt;").replace(/>/g, "&gt;").replace(/"/g, "&quot;").replace(/'/g, "&#039;");
    }

    function handleKeyPress(e) {
        if (e.key === 'Enter') {
            submitMessage();
        }
    }

    function backToContacts() {
        document.getElementById('whatsappContainer').classList.remove('show-chat');
    }

    function filterContacts() {
        const query = document.getElementById('contactSearchInput').value.toLowerCase();
        document.querySelectorAll('.wa-contact-item').forEach(item => {
            const name = item.querySelector('.wa-contact-name').innerText.toLowerCase();
            if (name.includes(query)) {
                item.style.display = 'flex';
            } else {
                item.style.display = 'none';
            }
        });
    }
</script>
@endsection
