@extends('admin.layouts.master')

@section('master')
    <div class="col-12">
        <div class="card border-0 shadow-sm">
            <div class="row g-0" style="min-height:620px">
                <aside class="col-lg-4 border-end">
                    <div class="p-3 border-bottom">
                        <h5 class="mb-0">@lang('Customer conversations')</h5>
                    </div>
                    <div class="list-group list-group-flush">
                        @forelse ($threads as $thread)
                            @php($threadUser = $thread->first()->user)
                            <a href="{{ route('admin.chat.index', ['user' => $threadUser->id]) }}" class="list-group-item list-group-item-action {{ $selectedUser == $threadUser->id ? 'active' : '' }}">
                                <div class="d-flex justify-content-between gap-2">
                                    <strong>{{ $threadUser->fullname }}</strong>
                                    <small>{{ showDateTime($thread->first()->created_at, 'H:i') }}</small>
                                </div>
                                <div class="small text-truncate mt-1">{{ $thread->first()->message }}</div>
                                <small class="opacity-75">{{ '@' . $threadUser->username }}</small>
                            </a>
                        @empty
                            <p class="text-muted text-center p-4 mb-0">@lang('No conversations yet.')</p>
                        @endforelse
                    </div>
                </aside>
                <section class="col-lg-8 d-flex flex-column" data-admin-chat data-messages-url="{{ route('admin.chat.messages') }}" data-send-url="{{ route('admin.chat.store') }}" data-user="{{ $user?->id }}">
                    @if ($user)
                        <header class="p-3 border-bottom">
                            <h5 class="mb-1">{{ $user->fullname }}</h5>
                            <small class="text-muted">{{ $user->email }} &middot; @{{ $user->username }}</small>
                        </header>
                        <div class="flex-grow-1 p-3 overflow-auto" style="max-height:470px;background:#f8fafc" data-admin-messages>
                            @foreach ($messages as $message)
                                <div class="d-flex mb-3 {{ $message->isFromAdmin() ? 'justify-content-end' : 'justify-content-start' }}">
                                    <div class="px-3 py-2 rounded-2 {{ $message->isFromAdmin() ? 'bg-primary text-white' : 'bg-white border' }}" style="max-width:75%;white-space:pre-wrap;overflow-wrap:anywhere">
                                        {{ $message->message }}
                                        <small class="d-block text-end opacity-75">{{ $message->created_at->format('H:i') }}</small>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                        <form class="d-flex gap-2 p-3 border-top" data-admin-form>
                            @csrf
                            <input type="text" name="message" class="form-control" maxlength="2000" placeholder="@lang('Reply to customer...')" required>
                            <button class="btn btn-primary" type="submit"><i class="ti ti-send"></i></button>
                        </form>
                        <p class="text-danger small px-3 mb-2" data-admin-error hidden></p>
                    @else
                        <div class="m-auto text-center text-muted">@lang('Select a conversation to start replying.')</div>
                    @endif
                </section>
            </div>
        </div>
    </div>
@endsection

@push('page-script')
    @if ($user)
        <script>
            (() => {
                const root = document.querySelector('[data-admin-chat]');
                const list = root.querySelector('[data-admin-messages]');
                const form = root.querySelector('[data-admin-form]');
                const error = root.querySelector('[data-admin-error]');
                let lastId = {{ $messages->last()?->id ?? 0 }};
                let loading = false;
                const add = item => {
                    const row = document.createElement('div');
                    row.className = `d-flex mb-3 ${item.from_admin ? 'justify-content-end' : 'justify-content-start'}`;
                    const bubble = document.createElement('div');
                    bubble.className = `px-3 py-2 rounded-2 ${item.from_admin ? 'bg-primary text-white' : 'bg-white border'}`;
                    bubble.style.cssText = 'max-width:75%;white-space:pre-wrap;overflow-wrap:anywhere';
                    bubble.textContent = item.message;
                    const time = document.createElement('small');
                    time.className = 'd-block text-end opacity-75';
                    time.textContent = item.time;
                    bubble.append(time); row.append(bubble); list.append(row); lastId = Math.max(lastId, item.id);
                };
                const load = () => {
                    if (loading) return;
                    loading = true;
                    fetch(`${root.dataset.messagesUrl}?user=${root.dataset.user}&after=${lastId}`, {cache:'no-store', headers: {'Accept':'application/json'}})
                        .then(response => response.ok ? response.json() : Promise.reject())
                        .then(data => { data.messages.forEach(add); list.scrollTop = list.scrollHeight; })
                        .catch(() => {})
                        .finally(() => { loading = false; });
                };
                form.addEventListener('submit', event => {
                    event.preventDefault(); const input = form.querySelector('input[name="message"]'); if (!input.value.trim()) return;
                    const button = form.querySelector('button'); button.disabled = true;
                    error.hidden = true;
                    const body = new URLSearchParams({user: root.dataset.user, message: input.value.trim()});
                    fetch(root.dataset.sendUrl, {method:'POST', headers:{'X-CSRF-TOKEN':form.querySelector('[name=_token]').value,'Accept':'application/json'}, body})
                        .then(async response => {
                            if (response.ok) return response.json();
                            const data = await response.json().catch(() => ({}));
                            throw new Error(data.message || Object.values(data.errors || {}).flat()[0] || 'Message could not be sent.');
                        })
                        .then(data => { add(data.message); input.value=''; list.scrollTop=list.scrollHeight; })
                        .catch(sendError => { error.textContent = sendError.message; error.hidden = false; })
                        .finally(() => { button.disabled=false; input.focus(); });
                });
                list.scrollTop = list.scrollHeight; window.setInterval(load, 3000);
            })();
        </script>
    @endif
@endpush
