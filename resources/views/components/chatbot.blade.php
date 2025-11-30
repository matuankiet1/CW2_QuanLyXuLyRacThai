<style>
    /* Style thanh cuộn cho toàn bộ chatbot */
    .chatbot-scroll::-webkit-scrollbar {
        width: 6px;
    }

    .chatbot-scroll::-webkit-scrollbar-track {
        background: transparent;
    }

    .chatbot-scroll::-webkit-scrollbar-thumb {
        background: #475569;
        border-radius: 10px;
    }

    .chatbot-scroll::-webkit-scrollbar-thumb:hover {
        background: #64748b;
    }

    .no-scrollbar {
        scrollbar-width: none;
        /* Firefox */
    }

    .no-scrollbar::-webkit-scrollbar {
        display: none;
        /* Chrome, Edge, Safari */
    }
</style>

<div class="fixed bottom-5 right-5 z-50" id="chatbot-widget">
    {{-- Nút mở/đóng chat --}}
    <button type="button" data-chatbot-toggle
        class="flex items-center gap-2 rounded-full px-4 py-2 bg-indigo-500 text-white shadow-lg shadow-indigo-500/30 hover:bg-indigo-600 transition">
        <span class="flex h-8 w-8 items-center justify-center rounded-full bg-white/10">
            🤖
        </span>
        <span class="text-sm font-medium">
            Chatbot AI
        </span>
    </button>

    {{-- Hộp thoại chat --}}
    <div data-chatbot-panel
        class="absolute bottom-14 right-0 w-[350px] sm:w-[380px] h-[460px] bg-slate-900 border border-slate-700 rounded-2xl shadow-2xl flex flex-col overflow-hidden
               invisible opacity-0 translate-y-2 pointer-events-none
               transition-all duration-200">
        {{-- Header --}}
        <header class="px-4 py-3 border-b border-slate-800 flex items-center justify-between bg-slate-900/90">
            <div class="flex items-center gap-2">
                <div class="relative">
                    <div
                        class="h-8 w-8 rounded-2xl bg-gradient-to-tr from-indigo-500 via-sky-500 to-emerald-400 flex items-center justify-center text-white text-sm font-semibold">
                        🤖
                    </div>
                    <span
                        class="absolute -right-0.5 -bottom-0.5 h-2.5 w-2.5 rounded-full bg-emerald-400 ring-2 ring-slate-900"></span>
                </div>
                <div>
                    <p class="text-sm font-semibold text-slate-100">
                        Chatbot AI
                    </p>
                </div>
            </div>

            <button type="button" data-chatbot-close class="text-slate-400 hover:text-slate-200 text-xl leading-none">
                ×
            </button>
        </header>

        {{-- Khu vực tin nhắn --}}
        <main
            class="chat-area chatbot-scroll flex-1 px-3 py-3 space-y-3 bg-gradient-to-b from-slate-900 via-slate-900 to-slate-950 overflow-y-auto text-sm">
            {{-- Tin nhắn bot mở đầu --}}
            <div class="flex items-start gap-2">
                <div
                    class="h-7 w-7 rounded-2xl bg-gradient-to-tr from-indigo-500 to-sky-400 flex items-center justify-center text-[10px] text-white font-semibold">
                    🤖
                </div>
                <div class="max-w-[80%]">
                    <div
                        class="rounded-2xl rounded-tl-sm bg-slate-800/80 border border-slate-700 px-3 py-2 text-slate-100">
                        Xin chào 👋, mình là Chatbot AI hỗ trợ. Mình có thể gợi ý cho bạn cách để tái chế đồ vật, hãy
                        cho mình xin tên của đồ vật nhé!
                    </div>
                    <p class="text-xs text-slate-500 mt-1">
                        Bot
                    </p>
                </div>
            </div>

            {{-- Ví dụ tin nhắn user --}}
            {{-- <div class="flex items-start gap-2 justify-end">
                <div class="max-w-[80%] text-right">
                    <div class="inline-block rounded-2xl rounded-tr-sm bg-indigo-500 px-3 py-2 text-white">
                        Hãy gợi ý cho tôi một plan học Laravel trong 1 tháng.
                    </div>
                    <p class="text-xs text-slate-500 mt-1">
                        Bạn • 12:31
                    </p>
                </div>
                <div
                    class="h-7 w-7 rounded-full bg-slate-700 flex items-center justify-center text-[10px] text-slate-200">
                    U
                </div>
            </div> --}}

            {{-- Bot đang trả lời (loading) --}}
            {{-- <div class="flex items-start gap-2">
                <div
                    class="h-7 w-7 rounded-2xl bg-gradient-to-tr from-indigo-500 to-sky-400 flex items-center justify-center text-[10px] text-white font-semibold">
                    AI
                </div>
                <div class="max-w-[80%]">
                    <div
                        class="rounded-2xl rounded-tl-sm bg-slate-800/80 border border-slate-700 px-3 py-2 flex items-center gap-1.5">
                        <span class="inline-flex h-1.5 w-1.5 rounded-full bg-slate-400 animate-bounce"></span>
                        <span
                            class="inline-flex h-1.5 w-1.5 rounded-full bg-slate-500 animate-bounce [animation-delay:0.1s]"></span>
                        <span
                            class="inline-flex h-1.5 w-1.5 rounded-full bg-slate-600 animate-bounce [animation-delay:0.2s]"></span>
                    </div>
                    <p class="text-xs text-slate-500 mt-1">
                        Bot • Đang soạn...
                    </p>
                </div>
            </div> --}}
        </main>

        {{-- Input --}}

        <footer class="border-t border-slate-800 px-3 py-2 bg-slate-900/95">
            @if (!auth()->check())
                <p class="text-sm text-white mb-2">
                    Vui lòng <a href="{{ route('login') }}" class="underline text-indigo-400 hover:text-indigo-600">đăng
                        nhập</a> để sử
                    dụng
                    chatbot.
                </p>
            @else
                <p class="text-xs text-slate-500 mb-2">
                    Mẹo: Hãy hỏi cụ thể để câu trả lời chính xác hơn.
                </p>

                <form method="POST" action="#" id="chatbotForm" class="flex items-end gap-2">
                    @csrf
                    <textarea id="chatbotInput" name="message" rows="1" placeholder="Nhập câu hỏi của bạn..."
                        class="no-scrollbar w-full resize-none rounded-2xl bg-slate-900 border border-slate-700 px-3 py-2 pr-10 text-sm text-slate-100 placeholder:text-slate-500 focus:outline-none focus:ring-2 focus:ring-indigo-500/60 focus:border-indigo-400"></textarea>

                    <button type="submit" disabled
                        class="flex h-9 w-9 items-center justify-center rounded-2xl bg-gradient-to-tr from-indigo-500 via-sky-500 to-emerald-400 text-white shadow-md shadow-indigo-500/40 opacity-50 cursor-not-allowed transition">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5 -translate-x-[1px]"
                            viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M5 12h6m0 0l-3 3m3-3l-3-3m4-5.5l7.362 7.362a1.5 1.5 0 010 2.121L12 21.5" />
                        </svg>
                    </button>
                </form>

                <p class="mt-2 text-xs text-slate-500 text-left">
                    AI có thể sai. Hãy kiểm tra thông tin quan trọng.
                </p>
            @endif
        </footer>
    </div>
</div>

<script>
    // 
    document.addEventListener('DOMContentLoaded', function() {
        // Toogle chatbot
        const toggleBtn = document.querySelector('[data-chatbot-toggle]');
        const panel = document.querySelector('[data-chatbot-panel]');
        const closeBtn = document.querySelector('[data-chatbot-close]');

        if (!toggleBtn || !panel) return;

        function openPanel() {
            panel.classList.remove('invisible', 'opacity-0', 'translate-y-2', 'pointer-events-none');
        }

        function closePanel() {
            panel.classList.add('invisible', 'opacity-0', 'translate-y-2', 'pointer-events-none');
        }

        let isOpen = false;

        toggleBtn.addEventListener('click', function(e) {
            e.stopPropagation();
            isOpen ? closePanel() : openPanel();
            isOpen = !isOpen;
        });

        if (closeBtn) {
            closeBtn.addEventListener('click', function(e) {
                e.stopPropagation();
                closePanel();
                isOpen = false;
            });
        }

        // Click bên ngoài để đóng
        document.addEventListener('click', function(e) {
            if (!isOpen) return;
            if (!panel.contains(e.target) && !toggleBtn.contains(e.target)) {
                closePanel();
                isOpen = false;
            }
        });

        // Xử lý form
        const chatArea = document.querySelector('.chat-area');
        const chatbotForm = document.querySelector('#chatbotForm');
        const chatbotInput = chatbotForm.querySelector('#chatbotInput');
        const chatbotSubmitBtn = chatbotForm.querySelector('button[type="submit"]');

        if (!chatArea || !chatbotForm || !chatbotInput) return;

        let chatHistory = [];
        const CHAT_KEY = 'ecowaste_chat_history';

        setFirstLocaltimeOfBot();
        loadHistory();

        chatbotInput.addEventListener('input', function() {
            const query = chatbotInput.value.trim();
            if (query == '') {
                chatbotSubmitBtn.disabled = true;
                chatbotSubmitBtn.classList.add('opacity-50', 'cursor-not-allowed');
                chatbotSubmitBtn.classList.remove('hover:brightness-110', 'active:scale-95');
            } else {
                chatbotSubmitBtn.disabled = false;
                chatbotSubmitBtn.classList.remove('opacity-50', 'cursor-not-allowed');
                chatbotSubmitBtn.classList.add('hover:brightness-110', 'active:scale-95');
            }

        });

        chatbotForm.addEventListener('submit', async function(e) {
            e.preventDefault();

            const query = chatbotInput.value.trim();
            if (!query) return;

            if (chatbotSubmitBtn) {
                chatbotSubmitBtn.disabled = true;
                chatbotSubmitBtn.classList.add('opacity-50', 'cursor-not-allowed');
            }

            // Hiển thị tin nhắn user
            appendUserMessage(query);
            chatbotInput.value = '';
            chatbotInput.focus();

            // Lưu tin nhắn user vào localStorage
            saveMessage('user', query);

            // Hiển thị loading
            const loadingNode = appendBotLoading();
            try {
                const res = await fetch("{{ route('chatbot.recycle-suggestion') }}", {
                    method: "POST",
                    headers: {
                        "Content-Type": "application/json",
                        "Accept": "application/json",
                        "X-CSRF-TOKEN": "{{ csrf_token() }}"
                    },
                    body: JSON.stringify({
                        query: query
                    })
                });

                const data = await res.json();
                // console.log(data);

                // Xóa bubble loading
                loadingNode.remove();

                if (!data.success) {
                    console.log("AI không thể phân tích vật phẩm.");
                    return;
                }

                // Hiển thị tin nhắn bot từ dữ liệu AI
                appendBotMessageFromAI(data.data);
                // Lưu tin nhắn bot vào localStorage
                saveMessage('bot', data.data);
            } catch (err) {
                console.error(err);
                // Xóa bubble loading nếu còn
                if (loadingNode && loadingNode.remove) {
                    loadingNode.remove();
                }
                appendBotErrorMessage();
            } finally {
                if (chatbotSubmitBtn) {
                    chatbotSubmitBtn.disabled = false;
                    chatbotSubmitBtn.classList.remove('opacity-50', 'cursor-wait');
                }
            }
        });

        // Bấm enter để submit form
        chatbotInput.addEventListener('keydown', function(e) {
            if (e.key === 'Enter' && !e.shiftKey) {
                e.preventDefault();
                chatbotForm.requestSubmit();
            }
        });

        // Scroll xuống cuối mỗi lần thêm tin nhắn
        function scrollToBottom() {
            chatArea.scrollTop = chatArea.scrollHeight;
        }

        // Tạo bubble tin nhắn user
        function appendUserMessage(text, time = null) {
            const wrapper = document.createElement('div');
            wrapper.className = 'flex items-start gap-2 justify-end';

            const timeLabel = time ?
                new Date(time).toLocaleTimeString('vi-VN', {
                    hour: '2-digit',
                    minute: '2-digit'
                }) :
                new Date().toLocaleTimeString('vi-VN', {
                    hour: '2-digit',
                    minute: '2-digit'
                });

            wrapper.innerHTML = `
            <div class="max-w-[80%] text-right">
                <div class="inline-block rounded-2xl rounded-tr-sm bg-indigo-500 px-3 py-2 text-white">
                    ${text.replace(/\n/g, '<br>')}
                </div>
                <p class="text-xs text-slate-500 mt-1">
                    Bạn • ${timeLabel}
                </p>
            </div>
            <div
                class="h-7 w-7 rounded-full bg-slate-700 flex items-center justify-center text-[10px] text-slate-200">
                U
            </div>
        `;

            chatArea.appendChild(wrapper);
            scrollToBottom();
        }

        // Tạo bubble loading của bot
        function appendBotLoading() {
            const wrapper = document.createElement('div');
            wrapper.className = 'flex items-start gap-2';
            wrapper.setAttribute('data-bot-loading', 'true');

            wrapper.innerHTML = `
                <div
                    class="h-7 w-7 rounded-2xl bg-gradient-to-tr from-indigo-500 to-sky-400 flex items-center justify-center text-[10px] text-white font-semibold">
                    🤖
                </div>
                <div class="max-w-[80%]">
                    <div
                        class="rounded-2xl rounded-tl-sm bg-slate-800/80 border border-slate-700 px-3 py-2 flex items-center gap-1.5">
                        <span class="inline-flex h-1.5 w-1.5 rounded-full bg-slate-400 animate-bounce"></span>
                        <span class="inline-flex h-1.5 w-1.5 rounded-full bg-slate-500 animate-bounce [animation-delay:0.1s]"></span>
                        <span class="inline-flex h-1.5 w-1.5 rounded-full bg-slate-600 animate-bounce [animation-delay:0.2s]"></span>
                    </div>
                    <p class="text-xs text-slate-500 mt-1">
                        Bot • Đang soạn...
                    </p>
                </div>
            `;

            chatArea.appendChild(wrapper);
            scrollToBottom();
            return wrapper; // để lát nữa remove
        }

        // Tạo bubble trả lời của bot từ dữ liệu AI
        function appendBotMessageFromAI(data, time = null) {
            const wrapper = document.createElement('div');
            wrapper.className = 'flex items-start gap-2';

            const timeLabel = time ?
                new Date(time).toLocaleTimeString('vi-VN', {
                    hour: '2-digit',
                    minute: '2-digit'
                }) :
                new Date().toLocaleTimeString('vi-VN', {
                    hour: '2-digit',
                    minute: '2-digit'
                });

            // Build nội dung
            const lines = [];

            console.log(data);

            if (data.item_name) {
                lines.push(`
                <div><span class="font-semibold text-slate-100">Vật phẩm:</span>
                    <span class="text-slate-200"> ${data.item_name}</span>
                </div>
            `);
            }

            if (data.category) {
                lines.push(`
                <div class="mt-1"><span class="font-semibold text-slate-100">Phân loại:</span>
                    <span class="text-emerald-300"> ${data.category}</span>
                </div>
            `);
            }

            if (Array.isArray(data.how_to_recycle) && data.how_to_recycle.length) {
                lines.push(`
                <div class="mt-2 text-xs text-emerald-300 font-semibold">Gợi ý tái chế / tái sử dụng:</div>
                <ul class="list-disc pl-4 mt-1 space-y-0.5 text-xs text-slate-100">
                    ${data.how_to_recycle.map(item => `<li>${item}</li>`).join('')}
                </ul>
            `);
            }

            if (Array.isArray(data.notes) && data.notes.length) {
                if (data.category == "Không xác định") {
                    lines.push(`
                        <p class="mt-1 text-sm text-white">
                            ${data.notes}
                        </p>
                    `);
                } else {
                    lines.push(`
                        <div class="mt-2 text-xs text-slate-400 font-semibold">Lưu ý:</div>
                        <ul class="list-disc pl-4 mt-1 space-y-0.5 text-xs text-slate-400">
                            ${data.notes.map(n => `<li>${n}</li>`).join('')}
                        </ul>
                    `);
                }
            }

            if (!lines.length) {
                lines.push(
                    `<div class="text-slate-100">Xin lỗi, mình chưa có đủ thông tin để gợi ý cho vật phẩm này.</div>`
                );
            }

            wrapper.innerHTML = `
            <div
                class="h-7 w-7 rounded-2xl bg-gradient-to-tr from-indigo-500 to-sky-400 flex items-center justify-center text-[10px] text-white font-semibold">
                🤖
            </div>
            <div class="max-w-[80%]">
                <div class="rounded-2xl rounded-tl-sm bg-slate-800/80 border border-slate-700 px-3 py-2 text-slate-100">
                    ${lines.join('')}
                </div>
                <p class="text-xs text-slate-500 mt-1">
                    Bot • ${timeLabel}
                </p>
            </div>
        `;

            chatArea.appendChild(wrapper);
            scrollToBottom();
        }

        // Tạo bubble lỗi khi gọi API fail
        function appendBotErrorMessage() {
            const wrapper = document.createElement('div');
            wrapper.className = 'flex items-start gap-2';

            wrapper.innerHTML = `
            <div
                class="h-7 w-7 rounded-2xl bg-gradient-to-tr from-red-500 to-orange-400 flex items-center justify-center text-[10px] text-white font-semibold">
                AI
            </div>
            <div class="max-w-[80%]">
                <div class="rounded-2xl rounded-tl-sm bg-slate-800/80 border border-red-500/60 px-3 py-2 text-slate-100">
                    Xin lỗi, tôi đang gặp một số trục trặc. Vui lòng thử lại sau hoặc liên hệ quản trị viên.
                </div>
                <p class="text-xs text-slate-500 mt-1">
                    Bot • ${new Date().toLocaleTimeString('vi-VN', { hour: '2-digit', minute: '2-digit' })}
                </p>
            </div>
        `;

            chatArea.appendChild(wrapper);
            scrollToBottom();
        }

        function saveMessage(role, text) {
            try {
                chatHistory.push({
                    role: role,
                    content: text,
                    time: new Date().toISOString(),
                });

                localStorage.setItem(CHAT_KEY, JSON.stringify(chatHistory));
            } catch (e) {
                console.error('Save chat history error', e);
            }
        }

        function loadHistory() {
            try {
                const raw = localStorage.getItem(CHAT_KEY);
                if (!raw) return;

                chatHistory = JSON.parse(raw) || [];

                chatHistory.forEach(msg => {
                    if (msg.role === 'user') {
                        appendUserMessage(msg.content, msg.time);
                    } else if (msg.role === 'bot') {
                        appendBotMessageFromAI(msg.content, msg.time);
                    }
                });
            } catch (e) {
                console.error('Load chat history error', e);
                chatHistory = [];
            }
        }

        // function setFirstLocaltimeOfBot() {
        //     const firstBotTimeSpan = document.querySelector('.first-localtime-of-bot');
        //     if (!firstBotTimeSpan) return;

        //     const localTimeStr = new Date().toLocaleTimeString('vi-VN', {
        //         hour: '2-digit',
        //         minute: '2-digit'
        //     });

        //     firstBotTimeSpan.textContent = localTimeStr;
        // }
    });
</script>
