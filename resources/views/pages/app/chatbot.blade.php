@extends('layouts.app')

@section('title', 'Chatbot')

@section('content')
    <div class="container mt-4">
        <div class="d-flex align-items-center mb-3">
            <h4 class="mb-0">🤖 Asisten Lapor Pak</h4>
            <span class="badge bg-success ms-2">Online</span>
        </div>

        <!-- Quick Actions -->
        <div class="row mb-3">
            <div class="col-12">
                <div class="d-flex gap-2 flex-wrap">
                    <button class="btn btn-outline-primary btn-sm quick-action" data-action="status laporan">
                        📊 Status Laporan
                    </button>
                    <button class="btn btn-outline-primary btn-sm quick-action" data-action="buat laporan">
                        📝 Buat Laporan
                    </button>
                    <button class="btn btn-outline-primary btn-sm quick-action" data-action="kategori">
                        📂 Kategori
                    </button>
                    <button class="btn btn-outline-primary btn-sm quick-action" data-action="bantuan">
                        ❓ Bantuan
                    </button>
                </div>
            </div>
        </div>

        <div id="chat-box" class="p-3 mb-3 rounded border bg-light"
            style="min-height: 300px; max-height: 500px; overflow-y: auto;">
            <div class="mb-2 text-muted">
                <small>🤖 <strong>Bot:</strong> Halo! Saya asisten Lapor Pak. Ketik "bantuan" untuk melihat apa yang bisa
                    saya bantu.</small>
            </div>
        </div>

        <div class="input-group">
            <input type="text" id="user-input" class="form-control" placeholder="Tanyakan sesuatu atau ketik perintah..."
                onkeypress="handleEnterKey(event)">
            <button class="btn btn-primary" id="send-btn">
                <i class="fas fa-paper-plane"></i> Kirim
            </button>
        </div>

        <!-- Typing indicator -->
        <div id="typing-indicator" class="text-muted mt-2" style="display: none;">
            <small>🤖 Bot sedang mengetik...</small>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        function handleEnterKey(event) {
            if (event.key === 'Enter') {
                sendMessage();
            }
        }

        // Quick action buttons
        document.querySelectorAll('.quick-action').forEach(button => {
            button.addEventListener('click', function() {
                const action = this.getAttribute('data-action');
                document.getElementById('user-input').value = action;
                sendMessage();
            });
        });

        document.getElementById('send-btn').addEventListener('click', sendMessage);

        function sendMessage() {
            const inputEl = document.getElementById('user-input');
            const message = inputEl.value.trim();
            if (!message) return;

            const box = document.getElementById('chat-box');
            const typingIndicator = document.getElementById('typing-indicator');

            // Add user message
            addMessage('user', message);
            inputEl.value = "";

            // Show typing indicator
            typingIndicator.style.display = 'block';
            box.scrollTop = box.scrollHeight;

            // Send to chatbot
            fetch('{{ route('chatbot.command') }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({
                        message
                    })
                })
                .then(res => res.json())
                .then(data => {
                    typingIndicator.style.display = 'none';

                    // Add bot response
                    addMessage('bot', data.response);

                    // Handle special actions
                    if (data.action === 'show_buttons' && data.buttons) {
                        addButtons(data.buttons);
                    }

                    if (data.action === 'redirect' && data.url) {
                        setTimeout(() => {
                            window.location.href = data.url;
                        }, 2000);
                    }

                    box.scrollTop = box.scrollHeight;
                })
                .catch(error => {
                    console.error('Error:', error);
                    typingIndicator.style.display = 'none';
                    addMessage('bot', 'Maaf, terjadi kesalahan koneksi. Silakan coba lagi.', 'error');
                });
        }

        function addMessage(sender, message, type = '') {
            const box = document.getElementById('chat-box');
            const messageClass = type === 'error' ? 'text-danger' : '';
            const senderIcon = sender === 'user' ? '👤' : '🤖';
            const senderName = sender === 'user' ? 'Anda' : 'Bot';

            const messageHtml = `
                <div class="mb-3 ${messageClass}">
                    <div class="d-flex align-items-start">
                        <span class="me-2">${senderIcon}</span>
                        <div class="flex-grow-1">
                            <strong>${senderName}:</strong>
                            <div class="mt-1" style="white-space: pre-line;">${message}</div>
                        </div>
                    </div>
                </div>
            `;

            box.innerHTML += messageHtml;
        }

        function addButtons(buttons) {
            const box = document.getElementById('chat-box');
            let buttonsHtml = '<div class="mb-3"><div class="d-flex flex-wrap gap-2">';

            buttons.forEach(button => {
                if (button.url) {
                    buttonsHtml +=
                        `<a href="${button.url}" class="btn btn-sm btn-outline-primary">${button.text}</a>`;
                } else if (button.action) {
                    buttonsHtml +=
                        `<button class="btn btn-sm btn-outline-primary" onclick="executeAction('${button.action}')">${button.text}</button>`;
                }
            });

            buttonsHtml += '</div></div>';
            box.innerHTML += buttonsHtml;
        }

        function executeAction(action) {
            document.getElementById('user-input').value = action;
            sendMessage();
        }

        // Auto-focus on input
        document.getElementById('user-input').focus();
    </script>
@endsection
