    <div class="dt-chat" data-chat-root data-messages-url="{{ route('user.chat.messages') }}" data-send-url="{{ route('user.chat.store') }}">
        <button class="dt-chat__launcher" type="button" data-chat-toggle aria-label="@lang('Open chat')">
            <i class="ti ti-message-2"></i>
            <span class="dt-chat__ping" data-chat-ping hidden></span>
        </button>

        <section class="dt-chat__panel @guest('web') dt-chat__panel--guest @endguest" data-chat-panel hidden aria-label="@lang('Chat with us')">
            <header class="dt-chat__header">
                <div>
                    <strong>@lang('Chat with us')</strong>
                    <small>@lang('Our support team is here to help')</small>
                </div>
                <button type="button" class="dt-chat__close" data-chat-toggle aria-label="@lang('Close chat')">&times;</button>
            </header>
            @auth('web')
                <div class="dt-chat__messages" data-chat-messages aria-live="polite">
                    <p class="dt-chat__empty" data-chat-empty>@lang('Send us a message and we will reply here.')</p>
                </div>
                <form class="dt-chat__form" data-chat-form>
                    @csrf
                    <input type="text" name="message" maxlength="2000" autocomplete="off" placeholder="@lang('Write a message...')" aria-label="@lang('Message')" required>
                    <button type="submit" aria-label="@lang('Send message')"><i class="ti ti-send"></i></button>
                </form>
                <p class="dt-chat__error" data-chat-error hidden></p>
            @else
                <div class="dt-chat__guest">
                    <p>@lang('Please log in to chat with our support team.')</p>
                    <a href="{{ route('user.login.form') }}">@lang('Log in')</a>
                </div>
            @endauth
        </section>
    </div>

    <style>
            .dt-chat{position:fixed;right:24px;bottom:24px;z-index:1050;font-family:inherit}.dt-chat__launcher{width:64px;height:64px;border:0;border-radius:50%;background:#2563eb;color:#fff;font-size:28px;box-shadow:0 10px 28px rgba(15,23,42,.25);cursor:pointer}.dt-chat__ping{position:absolute;right:2px;top:1px;width:13px;height:13px;border:2px solid #fff;border-radius:50%;background:#f97316}.dt-chat__panel{position:absolute;right:0;bottom:78px;width:min(360px,calc(100vw - 32px));height:470px;background:#fff;border:1px solid #dbe3ef;border-radius:12px;box-shadow:0 18px 45px rgba(15,23,42,.2);overflow:hidden}.dt-chat__header{display:flex;justify-content:space-between;align-items:flex-start;padding:18px 20px;background:#172b4d;color:#fff}.dt-chat__header strong{display:block;font-size:17px}.dt-chat__header small{display:block;margin-top:4px;color:#b8c7df;font-size:11px}.dt-chat__close{border:0;background:transparent;color:#b8c7df;font-size:25px;line-height:1;cursor:pointer}.dt-chat__guest{padding:36px 24px;text-align:center;color:#64748b;font-size:13px}.dt-chat__guest a{display:inline-block;margin-top:10px;padding:9px 18px;border-radius:7px;background:#2563eb;color:#fff;text-decoration:none}.dt-chat__messages{height:calc(100% - 128px);padding:16px;overflow-y:auto;background:#f6f8fb}.dt-chat__empty{text-align:center;color:#64748b;font-size:13px;margin:120px 20px}.dt-chat__bubble{max-width:82%;margin:0 0 12px;padding:10px 12px;border-radius:12px 12px 12px 3px;background:#fff;color:#1e293b;box-shadow:0 2px 5px rgba(15,23,42,.06);font-size:13px;white-space:pre-wrap;overflow-wrap:anywhere}.dt-chat__bubble--mine{margin-left:auto;border-radius:12px 12px 3px 12px;background:#2563eb;color:#fff}.dt-chat__time{display:block;margin-top:4px;font-size:10px;opacity:.65;text-align:right}.dt-chat__form{display:flex;gap:8px;padding:12px;border-top:1px solid #e2e8f0;background:#fff}.dt-chat__form input{min-width:0;flex:1;border:1px solid #dbe3ef;border-radius:7px;padding:9px 10px;font-size:13px}.dt-chat__form button{width:38px;border:0;border-radius:7px;background:#2563eb;color:#fff;cursor:pointer}.dt-chat__form button:disabled{opacity:.5}@media(max-width:575px){.dt-chat{right:16px;bottom:16px}.dt-chat__launcher{width:58px;height:58px}.dt-chat__panel{bottom:70px;height:calc(100vh - 110px);max-height:470px}}
        </style>

    <style>
        .dt-chat__panel--guest {
            height: auto;
            min-height: 0;
        }

        .dt-chat__guest {
            padding: 28px 24px 30px;
        }

        .dt-chat__guest p {
            margin: 0 auto;
            max-width: 260px;
            color: #475569;
            font-size: 14px;
            line-height: 1.55;
        }

        .dt-chat__guest a {
            font-size: 13px;
            font-weight: 600;
        }

        .dt-chat__error {
            margin: -6px 12px 10px;
            color: #dc2626;
            font-size: 11px;
        }
    </style>

    @push('page-script')
        <script>
            (() => {
                const root = document.querySelector('[data-chat-root]');
                if (!root) return;
                const panel = root.querySelector('[data-chat-panel]');
                const messages = root.querySelector('[data-chat-messages]');
                const form = root.querySelector('[data-chat-form]');
                const empty = root.querySelector('[data-chat-empty]');
                const input = form?.querySelector('input[name="message"]');
                const error = root.querySelector('[data-chat-error]');
                const ping = root.querySelector('[data-chat-ping]');
                let lastId = 0;
                let loading = false;

                if (!form) {
                    root.querySelectorAll('[data-chat-toggle]').forEach(button => button.addEventListener('click', () => { panel.hidden = !panel.hidden; }));
                    return;
                }

                const append = (item) => {
                    empty.hidden = true;
                    const bubble = document.createElement('div');
                    bubble.className = `dt-chat__bubble${item.from_admin ? '' : ' dt-chat__bubble--mine'}`;
                    bubble.textContent = item.message;
                    const time = document.createElement('span');
                    time.className = 'dt-chat__time';
                    time.textContent = item.time;
                    bubble.append(time);
                    messages.append(bubble);
                    lastId = Math.max(lastId, item.id);
                };

                const load = () => {
                    if (loading) return;
                    loading = true;
                    fetch(`${root.dataset.messagesUrl}?after=${lastId}`, {cache: 'no-store', headers: {'Accept': 'application/json'}})
                        .then(response => response.ok ? response.json() : Promise.reject())
                        .then(data => {
                            data.messages.forEach(append);
                            if (data.messages.length && panel.hidden) ping.hidden = false;
                            if (data.messages.length) messages.scrollTop = messages.scrollHeight;
                        }).catch(() => {}).finally(() => { loading = false; });
                };

                root.querySelectorAll('[data-chat-toggle]').forEach(button => button.addEventListener('click', () => {
                    panel.hidden = !panel.hidden;
                    if (!panel.hidden) { ping.hidden = true; input.focus(); load(); }
                }));
                form.addEventListener('submit', event => {
                    event.preventDefault();
                    const text = input.value.trim();
                    if (!text) return;
                    const button = form.querySelector('button');
                    button.disabled = true;
                    error.hidden = true;
                    const body = new URLSearchParams();
                    body.set('message', text);
                    fetch(root.dataset.sendUrl, {method: 'POST', headers: {'X-CSRF-TOKEN': form.querySelector('[name=_token]').value, 'Accept': 'application/json'}, body})
                        .then(async response => {
                            if (response.ok) return response.json();
                            const data = await response.json().catch(() => ({}));
                            throw new Error(data.message || Object.values(data.errors || {}).flat()[0] || 'Message could not be sent.');
                        })
                        .then(data => { append(data.message); input.value = ''; messages.scrollTop = messages.scrollHeight; })
                        .catch(sendError => { error.textContent = sendError.message; error.hidden = false; })
                        .finally(() => { button.disabled = false; input.focus(); });
                });
                load();
                window.setInterval(load, 3000);
            })();
        </script>
    @endpush
