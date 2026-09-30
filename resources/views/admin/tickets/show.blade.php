@extends('layouts.backend')

@section('content')
@if(auth()->user()->hasRole('super-admin') || auth()->user()->user_type === 'developer')
    {{-- ──── সুপার এডমিন ও ডেভেলপার লেআউট (ব্যাকএন্ড থিম) ──── --}}
    <div class="container-fluid px-3 px-md-4 py-4" style="font-family: 'Outfit', sans-serif;">
        <div class="row align-items-center justify-content-between mb-4 g-3 border-bottom pb-4 border-light">
            <div class="col-12 col-md-auto">
                <span class="text-muted small fw-bold d-block mb-1">Ticket Conversation #{{ $ticket->id }}</span>
                <h1 class="h3 mb-1 text-slate-900 fw-bold">{{ $ticket->subject }}</h1>
                <div class="d-flex flex-wrap align-items-center gap-2 mt-1">
                    <span class="badge bg-light text-dark border px-2 py-1 small">{{ $ticket->category }}</span>
                    @if($ticket->status === 'open')
                        <span class="badge bg-emerald-soft text-emerald px-2 py-1 rounded-pill small">Open</span>
                    @elseif($ticket->status === 'replied')
                        <span class="badge bg-sky-soft text-sky px-2 py-1 rounded-pill small">Replied</span>
                    @else
                        <span class="badge bg-light text-muted border px-2 py-1 rounded-pill small">Closed</span>
                    @endif
                </div>
            </div>
            <div class="col-12 col-md-auto d-flex gap-2">
                <a href="{{ route('admin.tickets.index') }}" class="btn btn-outline-secondary btn-sm rounded-3">
                    <i class="bi bi-arrow-left"></i> Back
                </a>
                @if($ticket->status !== 'closed')
                    <form action="{{ route('admin.tickets.close', $ticket->id) }}" method="POST">
                        @csrf
                        <button type="submit" class="btn btn-outline-danger btn-sm rounded-3">
                            <i class="bi bi-x-circle"></i> Close Ticket
                        </button>
                    </form>
                @endif
            </div>
        </div>

        <div class="row g-4">
            <div class="col-lg-8">
                <div class="card border-0 shadow-sm rounded-4 p-4 analytics-card mb-4">
                    <h5 class="fw-bold mb-4 text-dark border-bottom pb-3"><i class="bi bi-chat-text text-emerald me-2"></i>Conversation Thread</h5>

                    <div class="p-3 rounded-3 mb-4 border border-light" style="background-color: #f8fafc;">
                        <div class="d-flex align-items-center justify-content-between mb-2">
                            <span class="fw-bold text-dark small">{{ $ticket->user->name ?? 'User' }}</span>
                            <span class="text-muted small" style="font-size: 11px;">{{ $ticket->created_at->format('d M Y, h:i A') }}</span>
                        </div>
                        <p class="text-secondary small mb-2" style="line-height: 1.5;">{!! nl2br(e($ticket->description)) !!}</p>
                        
                        @if($ticket->attachment)
                            @php
                                $ext = strtolower(pathinfo($ticket->attachment, PATHINFO_EXTENSION));
                                $isImg = in_array($ext, ['jpg', 'jpeg', 'png', 'webp', 'svg']);
                            @endphp
                            <div class="mt-3 pt-2 border-top border-light">
                                @if($isImg)
                                    <a href="{{ asset('storage/' . $ticket->attachment) }}" target="_blank" class="d-inline-block">
                                        <img src="{{ asset('storage/' . $ticket->attachment) }}" class="rounded-3 border border-light" style="max-width: 150px; max-height: 150px; object-fit: cover;" />
                                    </a>
                                @else
                                    <a href="{{ asset('storage/' . $ticket->attachment) }}" target="_blank" class="inline-flex align-items-center gap-1.5 bg-white text-secondary px-3 py-2 rounded-3 border text-decoration-none small transition-all">
                                        <i class="bi bi-paperclip"></i> View Attached File ({{ strtoupper($ext) }})
                                    </a>
                                @endif
                            </div>
                        @endif
                    </div>

                    <div class="d-flex flex-column gap-3">
                        @foreach($ticket->messages as $message)
                            @php $isAdmin = $message->user->hasRole('super-admin'); @endphp
                            <div class="p-3 rounded-3 border {{ $isAdmin ? 'border-sky-subtle' : 'border-light' }}" 
                                 style="background-color: {{ $isAdmin ? '#f0f9ff' : '#ffffff' }}; margin-left: {{ $isAdmin ? '30px' : '0' }};">
                                <div class="d-flex align-items-center justify-content-between mb-2">
                                    <span class="fw-bold text-dark small">
                                        {{ $message->user->name ?? 'User' }} 
                                        @if($isAdmin) <span class="badge bg-sky-soft text-dark px-1.5 py-0.5 small rounded ms-1">Moderator</span> @endif
                                    </span>
                                    <span class="text-muted small" style="font-size: 11px;">{{ $message->created_at->format('d M Y, h:i A') }}</span>
                                </div>
                                <p class="text-secondary small mb-2" style="line-height: 1.5;">{!! nl2br(e($message->message)) !!}</p>
                                
                                @if($message->attachment)
                                    @php
                                        $ext = strtolower(pathinfo($message->attachment, PATHINFO_EXTENSION));
                                        $isImg = in_array($ext, ['jpg', 'jpeg', 'png', 'webp', 'svg']);
                                    @endphp
                                    <div class="mt-2.5 pt-2 border-top border-light border-opacity-10">
                                        @if($isImg)
                                            <a href="{{ asset('storage/' . $message->attachment) }}" target="_blank" class="d-inline-block">
                                                <img src="{{ asset('storage/' . $message->attachment) }}" class="rounded-3 border border-light" style="max-width: 140px; max-height: 140px; object-fit: cover;" />
                                            </a>
                                        @else
                                            <a href="{{ asset('storage/' . $message->attachment) }}" target="_blank" class="inline-flex align-items-center gap-1.5 bg-light text-secondary px-3 py-1.5 rounded-3 border text-decoration-none small transition-all">
                                                <i class="bi bi-paperclip"></i> Download File ({{ strtoupper($ext) }})
                                            </a>
                                        @endif
                                    </div>
                                @endif
                            </div>
                        @endforeach
                    </div>
                </div>

                @if($ticket->status !== 'closed')
                    <div class="card border-0 shadow-sm rounded-4 p-4 analytics-card">
                        <h5 class="fw-bold mb-3 text-dark"><i class="bi bi-reply text-emerald me-2"></i>Submit Reply</h5>
                        <form action="{{ route('admin.tickets.reply', $ticket->id) }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            <div class="mb-3">
                                <textarea name="message" class="form-control" rows="5" placeholder="Type your message here..." required></textarea>
                            </div>
                            <div class="mb-3">
                                <label class="form-label fw-semibold small text-secondary">Attachment File (Image/PDF/Zip)</label>
                                <input type="file" name="attachment" class="form-control">
                            </div>
                            <button type="submit" class="btn btn-accent-success px-4 py-2 rounded-3 fw-semibold border-0">
                                <i class="bi bi-send-fill me-1.5"></i> Submit Reply
                            </button>
                        </form>
                    </div>
                @else
                    <div class="p-3 bg-light rounded-3 border text-center text-muted small">
                        <i class="bi bi-lock me-1"></i> This ticket is closed.
                    </div>
                @endif
            </div>

            <div class="col-lg-4">
                <div class="card border-0 shadow-sm rounded-4 p-4 analytics-card mb-4">
                    <h5 class="fw-bold mb-3 text-dark border-bottom pb-3">Ticket Information</h5>
                    <div class="d-flex flex-column gap-3" style="font-size: 13px;">
                        <div>
                            <span class="text-muted text-uppercase d-block small fw-bold tracking-wider fs-7">Client Profile</span>
                            <p class="mb-0 fw-semibold text-dark mt-1"><i class="bi bi-person text-secondary me-1"></i> {{ $ticket->user->name ?? 'User' }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@else
    {{-- ──── কাস্টমার চ্যাট মেসেঞ্জার লেআউট (ফ্রন্টএন্ড থিম) ──── --}}
    <div class="container mx-auto py-8 px-4 md:px-8 max-w-5xl" style="font-family: 'Outfit', sans-serif;">
        <div class="bg-white rounded-3xl border border-gray-100 shadow-sm overflow-hidden flex flex-col" style="min-height: 600px;">
            <!-- Chat Header -->
            <div class="bg-gray-50/50 p-6 border-b border-gray-100 flex flex-wrap align-items-center justify-between gap-4">
                <div class="flex items-center gap-3">
                    <a href="{{ route('user.dashboard', ['tab' => 'tickets']) }}" class="w-10 h-10 bg-white border border-gray-100 rounded-full flex items-center justify-center text-gray-500 hover:bg-gray-100 transition-colors">
                        <i class="fa-solid fa-arrow-left"></i>
                    </a>
                    <div>
                        <span class="text-xs text-gray-400 font-bold uppercase tracking-wider block">Ticket #{{ $ticket->id }}</span>
                        <h4 class="font-extrabold text-[#0a1d4a] text-base leading-tight">{{ $ticket->subject }}</h4>
                    </div>
                </div>
                <div class="flex items-center gap-2">
                    <span class="bg-light text-gray-700 border px-3 py-1.5 rounded-xl text-xs font-bold">{{ $ticket->category }}</span>
                    @if($ticket->status === 'open')
                        <span class="bg-emerald-50 text-[#2ba351] px-3 py-1.5 rounded-xl text-xs font-bold border border-emerald-100">Open</span>
                    @elseif($ticket->status === 'replied')
                        <span class="bg-blue-50 text-blue-600 px-3 py-1.5 rounded-xl text-xs font-bold border border-blue-100">Replied</span>
                    @else
                        <span class="bg-gray-100 text-gray-500 px-3 py-1.5 rounded-xl text-xs font-bold border border-gray-200">Closed</span>
                    @endif
                </div>
            </div>

            <!-- Chat Messages -->
            <div class="flex-grow p-6 flex flex-col gap-4 overflow-y-auto" style="max-height: 400px;">
                <div class="flex items-start gap-3 max-w-[80%]">
                    <div class="w-8 h-8 bg-gray-100 text-gray-600 rounded-full flex items-center justify-center text-xs font-bold flex-shrink-0">U</div>
                    <div class="bg-gray-50 border border-gray-100 p-4 rounded-2xl rounded-tl-none">
                        <p class="text-gray-700 text-sm leading-relaxed mb-2">{!! nl2br(e($ticket->description)) !!}</p>
                        @if($ticket->attachment)
                            @php
                                $ext = strtolower(pathinfo($ticket->attachment, PATHINFO_EXTENSION));
                                $isImg = in_array($ext, ['jpg', 'jpeg', 'png', 'webp', 'svg']);
                            @endphp
                            <div class="mt-2.5 pt-2 border-t border-gray-200">
                                @if($isImg)
                                    <a href="{{ asset('storage/' . $ticket->attachment) }}" target="_blank" class="inline-block">
                                        <img src="{{ asset('storage/' . $ticket->attachment) }}" class="rounded-xl border border-gray-200 max-w-[150px] max-h-[150px] object-cover" />
                                    </a>
                                @else
                                    <a href="{{ asset('storage/' . $ticket->attachment) }}" target="_blank" class="inline-flex items-center gap-1.5 bg-white hover:bg-gray-100 text-gray-700 px-3 py-2 rounded-xl text-xs font-semibold border transition-all text-decoration-none">
                                        <i class="fa-solid fa-paperclip"></i> View Attached File ({{ strtoupper($ext) }})
                                    </a>
                                @endif
                            </div>
                        @endif
                        <span class="text-[10px] text-gray-400 font-semibold block mt-1">{{ $ticket->created_at->format('d M, h:i A') }}</span>
                    </div>
                </div>

                @foreach($ticket->messages as $message)
                    @php $isAdminReply = $message->user->hasRole('super-admin'); @endphp
                    @if($isAdminReply)
                        <div class="flex items-start gap-3 max-w-[80%]">
                            <div class="w-8 h-8 bg-blue-50 text-blue-600 rounded-full flex items-center justify-center text-xs font-bold flex-shrink-0">A</div>
                            <div class="bg-blue-50/50 border border-blue-50 p-4 rounded-2xl rounded-tl-none">
                                <span class="text-[10px] text-blue-600 font-extrabold block mb-1 uppercase tracking-wider">Moderator Reply</span>
                                <p class="text-gray-700 text-sm leading-relaxed mb-2">{{ $message->message }}</p>
                                @if($message->attachment)
                                    @php
                                        $ext = strtolower(pathinfo($message->attachment, PATHINFO_EXTENSION));
                                        $isImg = in_array($ext, ['jpg', 'jpeg', 'png', 'webp', 'svg']);
                                    @endphp
                                    <div class="mt-2 pt-2 border-t border-blue-100">
                                        @if($isImg)
                                            <a href="{{ asset('storage/' . $message->attachment) }}" target="_blank" class="inline-block">
                                                <img src="{{ asset('storage/' . $message->attachment) }}" class="rounded-xl border border-gray-200 max-w-[140px] max-h-[140px] object-cover" />
                                            </a>
                                        @else
                                            <a href="{{ asset('storage/' . $message->attachment) }}" target="_blank" class="inline-flex items-center gap-1.5 bg-white hover:bg-gray-100 text-gray-700 px-3 py-2 rounded-xl text-xs font-semibold border transition-all text-decoration-none">
                                                <i class="fa-solid fa-paperclip"></i> Download File ({{ strtoupper($ext) }})
                                            </a>
                                        @endif
                                    </div>
                                @endif
                                <span class="text-[10px] text-gray-400 font-semibold block mt-1">{{ $message->created_at->format('d M, h:i A') }}</span>
                            </div>
                        </div>
                    @else
                        <div class="flex items-start gap-3 max-w-[80%] ml-auto flex-row-reverse">
                            <div class="w-8 h-8 bg-green-50 text-[#2ba351] rounded-full flex items-center justify-center text-xs font-bold flex-shrink-0">U</div>
                            <div class="bg-[#2ba351] text-white p-4 rounded-2xl rounded-tr-none shadow-sm">
                                <p class="text-sm leading-relaxed mb-2">{{ $message->message }}</p>
                                @if($message->attachment)
                                    @php
                                        $ext = strtolower(pathinfo($message->attachment, PATHINFO_EXTENSION));
                                        $isImg = in_array($ext, ['jpg', 'jpeg', 'png', 'webp', 'svg']);
                                    @endphp
                                    <div class="mt-2 pt-2 border-t border-white/20">
                                        @if($isImg)
                                            <a href="{{ asset('storage/' . $message->attachment) }}" target="_blank" class="inline-block">
                                                <img src="{{ asset('storage/' . $message->attachment) }}" class="rounded-xl border border-white/30 max-w-[140px] max-h-[140px] object-cover" />
                                            </a>
                                        @else
                                            <a href="{{ asset('storage/' . $message->attachment) }}" target="_blank" class="inline-flex items-center gap-1.5 bg-white/10 hover:bg-white/20 text-white px-3 py-2 rounded-xl text-xs font-semibold border border-white/10 transition-all text-decoration-none">
                                                <i class="fa-solid fa-paperclip"></i> Download ({{ strtoupper($ext) }})
                                            </a>
                                        @endif
                                    </div>
                                @endif
                                <span class="text-[10px] text-white/70 font-semibold block mt-1 text-right">{{ $message->created_at->format('d M, h:i A') }}</span>
                            </div>
                        </div>
                    @endif
                @endforeach
            </div>

            <!-- Reply Box -->
            @if($ticket->status !== 'closed')
                <div class="p-6 border-t border-gray-100 bg-gray-50/20">
                    <form action="{{ route('admin.tickets.reply', $ticket->id) }}" method="POST" enctype="multipart/form-data" class="flex flex-col gap-3">
                        @csrf
                        <textarea name="message" required rows="3" placeholder="Write your reply message here..." class="w-full bg-white border border-gray-200 px-5 py-3.5 rounded-2xl text-sm font-semibold text-gray-700 outline-none focus:ring-2 focus:ring-[#2ba351]/20 transition-all"></textarea>
                        
                        <div class="flex flex-col gap-1.5 max-w-sm">
                            <label class="text-xs font-bold text-gray-500 uppercase pl-1">Attachment</label>
                            <input type="file" name="attachment" class="w-full bg-white border border-gray-200 px-5 py-2.5 rounded-2xl text-sm font-semibold outline-none file:mr-4 file:py-1 file:px-2.5 file:rounded-xl file:border-0 file:text-xs file:font-semibold file:bg-gray-100 file:text-gray-700 hover:file:bg-gray-200">
                        </div>

                        <div class="flex justify-end">
                            <button type="submit" class="bg-[#2ba351] hover:bg-[#1a285a] text-white px-6 py-3 rounded-xl font-bold text-xs uppercase tracking-wider transition-all shadow-md">
                                <i class="fa-solid fa-paper-plane mr-1.5"></i> Send Message
                            </button>
                        </div>
                    </form>
                </div>
            @else
                <div class="p-6 text-center text-gray-400 text-xs border-t border-gray-150">
                    <i class="fa-solid fa-lock mr-1.5"></i> This discussion was closed by support.
                </div>
            @endif
        </div>
    </div>
@endif
@endsection
