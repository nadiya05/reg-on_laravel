@extends('layouts.admin')

@section('content')
<div class="container-fluid mt-3">
    <div class="row">

        {{-- SIDEBAR USER LIST --}}
        <div class="col-md-4 border-end" style="height:80vh; overflow-y:auto;">
            <h4 class="mb-3">Chat</h4>

            <form method="GET" action="{{ route('chat.index') }}" class="mb-3">
                <input type="text" name="search" value="{{ $chatSearch ?? '' }}"
                    placeholder="Cari nama user / isi pesan..." class="form-control" />
            </form>

            @forelse ($users as $u)
                <a href="{{ route('chat.show', $u->id) }}" class="text-decoration-none text-dark">
                    <div class="d-flex align-items-center p-2 mb-2 rounded 
                        {{ isset($activeUser) && $activeUser->id == $u->id ? 'bg-light' : '' }}">
                        
                        <img src="{{ $u->avatar ?? 'https://ui-avatars.com/api/?name='.$u->name }}" 
                            class="rounded-circle" width="45" height="45">

                        <div class="ms-2">
                            <strong>{{ $u->name }}</strong><br>
                            <small class="text-secondary">
                                {{ $u->chats()->latest()->first()->message ?? '-' }}
                            </small>
                        </div>
                    </div>
                </a>
            @empty
                <p class="text-center text-muted">Belum ada chat</p>
            @endforelse
        </div>


        {{-- CHAT PANEL --}}
        <div class="col-md-8 d-flex flex-column" style="height:80vh;">

            @if(isset($activeUser))
                <div class="border-bottom p-2 d-flex align-items-center">
                    <img src="{{ $activeUser->avatar ?? 'https://ui-avatars.com/api/?name='.$activeUser->name }}" 
                        width="45" height="45" class="rounded-circle">
                    <h5 class="ms-2 mb-0">{{ $activeUser->name }}</h5>

                    {{-- Delete All --}}
                    <form method="POST" action="{{ route('chat.clear', $activeUser->id) }}" 
                        class="ms-auto" onsubmit="return confirm('Hapus semua chat user ini?')">
                        @csrf @method('DELETE')
                        <button class="btn btn-sm btn-danger">Hapus Semua</button>
                    </form>
                </div>
            @endif

            {{-- CHAT MESSAGES --}}
            <div class="flex-grow-1 p-3" style="overflow-y:auto;">
                @if(isset($messages))
                    @foreach ($messages as $m)
                        <div class="d-flex mb-2 {{ $m->sender == 'admin' ? 'justify-content-end' : '' }}">
                            <div class="p-2 rounded position-relative 
                                {{ $m->sender == 'admin' ? 'bg-primary text-white' : 'bg-light' }}">

                                {{ $m->message }}

                                {{-- Waktu lengkap --}}
                                <span class="small text-muted ms-2">
                                    {{ \Carbon\Carbon::parse($m->created_at)->format('d-m-Y H:i') }}
                                </span>

                                {{-- delete single --}}
                                <form action="{{ route('admin.chat.delete', $m->id) }}" 
                                        method="POST" class="d-inline"
                                        onsubmit="return confirm('Hapus pesan ini?')">
                                    @csrf @method('DELETE')
                                    <button class="btn btn-sm text-danger border-0 p-0 ms-1"
                                            style="font-size: 10px; background: transparent">
                                        ✕
                                    </button>
                                </form>
                            </div>
                        </div>
                    @endforeach
                @endif
            </div>

            {{-- REPLY FORM --}}
            @if(isset($activeUser))
                <form action="{{ route('chat.send', $activeUser->id) }}" 
                      method="POST" class="d-flex border-top p-2">
                    @csrf
                    <input type="text" name="message" class="form-control" placeholder="Tulis pesan..." required>
                    <button class="btn btn-primary ms-2"><i class="bi bi-send"></i></button>
                </form>
            @endif

        </div>

    </div>
</div>
@endsection
