@extends('layouts.admin')

@section('content')
<div class="container-fluid mt-3">
    <div class="row">

        {{-- SIDEBAR USER LIST --}}
        {{-- SIDEBAR USER LIST --}}
<div class="col-md-4 border-end" style="height:80vh; overflow-y:auto;">
    <h4 class="mb-3">Chat</h4>

    <form method="GET" action="{{ route('chat.index') }}" class="mb-3">
        <input type="text" name="search" value="{{ $chatSearch ?? '' }}"
               placeholder="Cari nama user / isi pesan..." class="form-control" />
    </form>

    @forelse ($users as $u)

        {{-- HITUNG UNREAD TANPA MIGRATION --}}
        @php
            $lastOpen = session("chat_last_open_{$u->id}");
            $unread = $u->chats()
                ->where('sender', 'user')
                ->when($lastOpen, function($q) use ($lastOpen) {
                    $q->where('created_at', '>', $lastOpen);
                })
                ->count();
        @endphp

        <a href="{{ route('chat.show', $u->id) }}" class="text-decoration-none text-dark">
            <div class="d-flex align-items-center p-2 mb-2 rounded 
                {{ isset($activeUser) && $activeUser->id == $u->id
                    ? 'bg-light'
                    : ($unread > 0 ? 'bg-secondary-subtle' : '') }}">

                <img src="{{ $u->foto 
                        ? asset('storage/'.$u->foto) 
                        : 'https://ui-avatars.com/api/?name='.urlencode($u->name) }}"
                     class="rounded-circle" width="45" height="45" style="object-fit:cover;">

                <div class="ms-2 flex-grow-1">
                    <strong>{{ $u->name }}</strong><br>
                    <small class="text-secondary">
                        {{ $u->chats()->latest()->first()->message ?? '-' }}
                    </small>
                </div>

                {{-- BADGE UNREAD --}}
                @if($unread > 0)
                    <span class="badge bg-danger ms-auto">{{ $unread }}</span>
                @endif

            </div>
        </a>
    @empty
        <p class="text-center text-muted">Belum ada chat</p>
    @endforelse
</div>
        {{-- CHAT PANEL --}}
        <div class="col-md-8 d-flex flex-column" style="height:80vh;">
            @if(isset($activeUser) && $activeUser)

                {{-- HEADER --}}
                <div class="border-bottom p-2 d-flex align-items-center">
                    <img src="{{ $activeUser->foto 
                        ? asset('storage/'.$activeUser->foto)
                        : 'https://ui-avatars.com/api/?name='.urlencode($activeUser->name) }}"
                         width="45" height="45" class="rounded-circle" style="object-fit:cover;">

                    <h5 class="ms-2 mb-0">{{ $activeUser->name }}</h5>

                    <form method="POST" action="{{ route('chat.clear', $activeUser->id) }}"
                        class="ms-auto" onsubmit="return confirm('Hapus semua chat user ini?')">
                        @csrf @method('DELETE')
                        <button class="btn btn-sm btn-danger">Hapus Semua</button>
                    </form>
                </div>

                {{-- CHAT MESSAGES --}}
                <div class="flex-grow-1 p-3" style="overflow-y:auto;">
                    @foreach ($messages as $m)
                        <div class="d-flex mb-3 {{ $m->sender == 'admin' ? 'justify-content-end' : 'justify-content-start' }}">

                            {{-- Avatar kiri (user) --}}
                            @if($m->sender == 'user')
                                <img src="{{ $m->user && $m->user->foto 
                                        ? asset('storage/'.$m->user->foto) 
                                        : 'https://ui-avatars.com/api/?name='.urlencode($m->user->name ?? 'User') }}"
                                     class="rounded-circle me-2"
                                     width="40" height="40" style="object-fit:cover;">
                            @endif

                            {{-- Bubble --}}
                            <div>
                                <div class="p-2 rounded 
                                    {{ $m->sender == 'admin' ? 'bg-primary text-white' : 'bg-light' }}"
                                     style="position:relative; display:inline-block; max-width:70%; word-break:break-word; padding-right:25px;">

                                    {{ $m->message }}

                                    {{-- tombol hapus --}}
                                    <form action="{{ route('chat.delete', $m->id) }}"
                                        method="POST"
                                        style="position:absolute; top:2px; right:2px;"
                                        onsubmit="return confirm('Hapus pesan ini?')">
                                        @csrf @method('DELETE')
                                        <button class="btn btn-sm text-danger border-0 p-0"
                                            style="font-size:10px;background:transparent;">✕</button>
                                    </form>
                                </div>

                                <small class="text-muted mt-1 d-block" style="font-size:11px;">
                                    {{ $m->created_at->format('d-m-Y H:i') }}
                                </small>
                            </div>

                            {{-- Avatar kanan (admin) --}}
                            @if($m->sender == 'admin')
                                <img src="{{ auth()->user()->foto
                                        ? asset('storage/'.auth()->user()->foto)
                                        : 'https://ui-avatars.com/api/?name=Admin' }}"
                                     class="rounded-circle ms-2"
                                     width="40" height="40" style="object-fit:cover;">
                            @endif

                        </div>
                    @endforeach
                </div>

                {{-- SEND --}}
                <form action="{{ route('chat.send', $activeUser->id) }}"
                      method="POST" class="d-flex border-top p-2">
                    @csrf
                    <input type="text" name="message" class="form-control" placeholder="Tulis pesan..." required>
                    <button class="btn btn-primary ms-2"><i class="bi bi-send"></i></button>
                </form>

            @else
                <div class="h-100 d-flex justify-content-center align-items-center text-muted">
                    <p>Pilih pengguna untuk melihat chat</p>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
