@extends('layouts.front')

@section('content')
<div class="container mx-auto py-8 px-4 md:px-8 max-w-7xl font-outfit" style="font-family: 'Outfit', sans-serif;">

    <!-- Success/Error Session Alerts -->
    @if(session('success'))
    <div class="bg-green-50 text-[#2ba351] border border-green-150 p-4 mb-6 rounded-2xl text-center font-bold text-base shadow-sm">
        {{ session('success') }}
    </div>
    @endif
    @if(session('error'))
    <div class="bg-red-50 text-red-700 border border-red-150 p-4 mb-6 rounded-2xl text-center font-bold text-base shadow-sm">
        {{ session('error') }}
    </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start">

        <div class="lg:col-span-3 bg-white p-6 rounded-3xl border border-gray-100 shadow-sm flex flex-col gap-6">

            <!-- User Quick Avatar & Info -->
            <div class="text-center pb-4 border-b border-gray-100">
                <div class="w-16 h-16 bg-[#DFE8FF] text-[#2C4798] rounded-full mx-auto flex items-center justify-center font-black text-xl mb-3 uppercase">
                    {{ substr($user->name, 0, 1) }}
                </div>
                <h6 class="font-extrabold text-gray-800 text-base leading-tight mb-1 truncate">{{ $user->name }}</h6>
                <span class="text-xs text-gray-400 truncate block">{{ $user->email }}</span>
            </div>

            <!-- Sidebar Navigation Tabs -->
            <div class="flex flex-col gap-2">
                <button type="button" id="favorites-menu-btn" onclick="switchDashboardTab('favorites-tab', this)" class="dashboard-menu-btn w-full text-left px-4 py-3 bg-[#2ba351] text-white rounded-xl text-xs md:text-base font-extrabold flex items-center gap-2 transition-all">
                    <i class="fa-solid fa-heart text-base"></i>
                    My Favorites
                </button>
                <button type="button" id="tickets-menu-btn" onclick="switchDashboardTab('tickets-tab', this)" class="dashboard-menu-btn w-full text-left px-4 py-3 bg-gray-50 text-gray-600 rounded-xl text-xs md:text-base font-bold border border-gray-100 hover:bg-gray-100 hover:text-gray-900 flex items-center gap-2 transition-all">
                    <i class="fa-solid fa-headset text-base"></i>
                    Support Tickets
                </button>
                <button type="button" id="profile-menu-btn" onclick="switchDashboardTab('profile-tab', this)" class="dashboard-menu-btn w-full text-left px-4 py-3 bg-gray-50 text-gray-600 rounded-xl text-xs md:text-base font-bold border border-gray-100 hover:bg-gray-100 hover:text-gray-900 flex items-center gap-2 transition-all">
                    <i class="fa-solid fa-user-pen text-base"></i>
                    Edit Profile
                </button>
                <button type="button" id="password-menu-btn" onclick="switchDashboardTab('password-tab', this)" class="dashboard-menu-btn w-full text-left px-4 py-3 bg-gray-50 text-gray-600 rounded-xl text-xs md:text-base font-bold border border-gray-100 hover:bg-gray-100 hover:text-gray-900 flex items-center gap-2 transition-all">
                    <i class="fa-solid fa-key text-base"></i>
                    Change Password
                </button>
                <form id="dashboardLogoutForm" action="{{ route('logout') }}" method="POST" class="hidden">
                    @csrf
                </form>

                <button type="button"
                    onclick="event.preventDefault(); document.getElementById('dashboardLogoutForm').submit();"
                    class="w-full text-left px-4 py-3 bg-red-50 text-red-600 rounded-xl text-xs md:text-base font-bold border border-red-100 hover:bg-red-100 flex items-center gap-2 transition-all mt-4">
                    <i class="fa-solid fa-right-from-bracket text-base"></i>
                    Log Out
                </button>
            </div>

        </div>

        <div class="lg:col-span-9 flex flex-col gap-6">

            <!-- ক. MY FAVORITES TAB SECTION -->
            <div id="favorites-tab" class="dashboard-section bg-white p-6 md:p-8 rounded-3xl border border-gray-100 shadow-sm">
                <h3 class="text-[#0a1d4a] text-lg md:text-[20px] font-bold border-b border-gray-100 pb-3 mb-6 uppercase tracking-wide">
                    My Favorite Properties
                </h3>

                @if($favorites->isEmpty())
                <div class="text-center py-12 text-gray-400">
                    <i class="fa-solid fa-folder-open text-4xl mb-3"></i>
                    <p class="font-semibold text-base">You haven't bookmarked any properties yet.</p>
                </div>
                @else
                <!-- Favorites Grid list -->
                <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-3 gap-6">
                    @foreach($favorites as $fav)
                    @if($fav->project)
                    @php
                    $images = $fav->project->img_paths;
                    $firstImage = !empty($images) ? $images[0] : 'assets/images/placeholder.jpg';
                    @endphp
                    <div class="border border-gray-100 rounded-2xl overflow-hidden shadow-sm flex flex-col bg-white">
                        <div class="relative aspect-[16/11] overflow-hidden">
                            <img src="{{ asset('storage/' . $firstImage) }}" class="w-full h-full object-cover">
                        </div>
                        <div class="p-4 flex-grow flex flex-col justify-between">
                            <h4 class="font-bold text-gray-800 text-base line-clamp-1 mb-2">{{ $fav->project->title }}</h4>
                            <div class="flex justify-between items-center mt-3 border-t border-gray-50 pt-3">
                                <a href="{{ route('project.details', $fav->project->slug) }}" class="text-xs font-bold text-[#2ba351] hover:underline">View Details</a>
                                <button type="button" onclick="removeFavoriteItem({{ $fav->project->id }}, this)" class="text-xs font-bold text-red-500 hover:underline">Remove</button>
                            </div>
                        </div>
                    </div>
                    @endif
                    @endforeach
                </div>
                @endif
            </div>

            <!-- খ. SUPPORT TICKETS TAB SECTION (CONVERSATION SUPPORT INCLUDED) -->
            <div id="tickets-tab" class="dashboard-section hidden bg-white p-6 md:p-8 rounded-3xl border border-gray-100 shadow-sm">

                @if(isset($activeTicket))
                {{-- ── ৩. চ্যাট মেসেঞ্জার ইন্টারফেস (যখন টিকিট সিলেক্ট করা থাকবে) ── --}}
             <div class="flex flex-col">
    <!-- Chat Header -->
    <div class="flex items-center justify-between border-b border-gray-100 pb-4 mb-6">
        <div class="flex items-center gap-3">
            <a href="{{ route('user.dashboard', ['tab' => 'tickets']) }}" class="w-10 h-10 bg-gray-50 border border-gray-100 rounded-full flex items-center justify-center text-gray-500 hover:bg-gray-100 transition-colors">
                <i class="fa-solid fa-arrow-left"></i>
            </a>
            <div>
                <span class="text-xs text-gray-400 font-bold uppercase tracking-wider block">Ticket #{{ $activeTicket->id }}</span>
                <h4 class="font-extrabold text-[#0a1d4a] text-base leading-tight">{{ $activeTicket->subject }}</h4>
            </div>
        </div>
        <span class="bg-gray-100 text-gray-700 px-3 py-1.5 rounded-xl text-xs font-bold">{{ $activeTicket->category }}</span>
    </div>

    <!-- Chat Messages Screen -->
    <div class="flex flex-col gap-4 overflow-y-auto mb-6 pr-2 custom-scrollbar" style="max-height: 380px;">
        <!-- original description -->
        <div class="flex items-start gap-3 max-w-[85%]">
            <div class="w-8 h-8 bg-gray-100 text-gray-600 rounded-full flex items-center justify-center text-xs font-bold flex-shrink-0">U</div>
            <div class="bg-gray-50 border border-gray-100 p-4 rounded-2xl rounded-tl-none">
                <p class="text-gray-700 text-sm leading-relaxed">{!! nl2br(e($activeTicket->description)) !!}</p>
                
                <!-- মূল টিকিটের অ্যাটাচমেন্ট প্রিভিউ -->
                @if($activeTicket->attachment)
                    @php
                        $ext = strtolower(pathinfo($activeTicket->attachment, PATHINFO_EXTENSION));
                        $isImg = in_array($ext, ['jpg', 'jpeg', 'png', 'webp', 'svg']);
                    @endphp
                    <div class="mt-2.5 pt-2 border-t border-gray-200">
                        @if($isImg)
                            <a href="{{ asset('storage/' . $activeTicket->attachment) }}" target="_blank" class="inline-block">
                                <img src="{{ asset('storage/' . $activeTicket->attachment) }}" class="rounded-xl border border-gray-200 w-36 h-36 object-cover shadow-sm" alt="Attachment" />
                            </a>
                        @else
                            <a href="{{ asset('storage/' . $activeTicket->attachment) }}" target="_blank" class="inline-flex items-center gap-1.5 bg-white hover:bg-gray-100 text-gray-700 px-3 py-2 rounded-xl text-xs font-semibold border transition-all text-decoration-none">
                                <i class="fa-solid fa-paperclip"></i> View Attached File ({{ strtoupper($ext) }})
                            </a>
                        @endif
                    </div>
                @endif
                <span class="text-[10px] text-gray-400 font-semibold block mt-1">{{ $activeTicket->created_at->format('d M, h:i A') }}</span>
            </div>
        </div>

        <!-- replies -->
        @foreach($activeTicket->messages as $message)
            @php $isAdmin = $message->user->hasRole('super-admin'); @endphp
            @if($isAdmin)
                <!-- Moderator Reply (Left) -->
                <div class="flex items-start gap-3 max-w-[85%]">
                    <div class="w-8 h-8 bg-blue-50 text-blue-600 rounded-full flex items-center justify-center text-xs font-bold flex-shrink-0">A</div>
                    <div class="bg-blue-50/60 border border-blue-50 p-4 rounded-2xl rounded-tl-none">
                        <span class="text-[10px] text-blue-600 font-extrabold block mb-1 uppercase tracking-wider">Moderator Reply</span>
                        <p class="text-gray-700 text-sm leading-relaxed mb-2">{{ $message->message }}</p>
                        
                        <!-- অ্যাডমিন রিপ্লাইয়ের ফাইল আপলোড ভিউ -->
                        @if($message->attachment)
                            @php
                                $ext = strtolower(pathinfo($message->attachment, PATHINFO_EXTENSION));
                                $isImg = in_array($ext, ['jpg', 'jpeg', 'png', 'webp', 'svg']);
                            @endphp
                            <div class="mt-2 pt-2 border-t border-blue-100">
                                @if($isImg)
                                    <a href="{{ asset('storage/' . $message->attachment) }}" target="_blank" class="inline-block">
                                        <img src="{{ asset('storage/' . $message->attachment) }}" class="rounded-xl border border-gray-200 w-32 h-32 object-cover shadow-sm" alt="Admin Attachment" />
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
                <!-- Customer Reply (Right) -->
                <div class="flex items-start gap-3 max-w-[85%] ml-auto flex-row-reverse">
                    <div class="w-8 h-8 bg-green-50 text-[#2ba351] rounded-full flex items-center justify-center text-xs font-bold flex-shrink-0">U</div>
                    <div class="bg-[#2ba351] text-white p-4 rounded-2xl rounded-tr-none shadow-sm">
                        <p class="text-sm leading-relaxed mb-2">{{ $message->message }}</p>
                        
                        <!-- কাস্টমার রিপ্লাইয়ের ফাইল আপলোড ভিউ -->
                        @if($message->attachment)
                            @php
                                $ext = strtolower(pathinfo($message->attachment, PATHINFO_EXTENSION));
                                $isImg = in_array($ext, ['jpg', 'jpeg', 'png', 'webp', 'svg']);
                            @endphp
                            <div class="mt-2 pt-2 border-t border-white/20">
                                @if($isImg)
                                    <a href="{{ asset('storage/' . $message->attachment) }}" target="_blank" class="inline-block">
                                        <img src="{{ asset('storage/' . $message->attachment) }}" class="rounded-xl border border-white/30 w-32 h-32 object-cover shadow-sm" alt="User Attachment" />
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

    <!-- Reply input -->
    @if($activeTicket->status !== 'closed')
    <form action="{{ route('admin.tickets.reply', $activeTicket->id) }}" method="POST" enctype="multipart/form-data" class="flex flex-col gap-3">
        @csrf
        <textarea name="message" required rows="3" placeholder="Write your reply message here..." class="w-full bg-white border border-gray-200 px-5 py-3.5 rounded-2xl text-sm font-semibold text-gray-700 outline-none focus:ring-2 focus:ring-[#2ba351]/20 transition-all"></textarea>

        <!-- কাস্টমার ফাইল আপলোড ইনপুট ফিল্ড -->
        <div class="flex flex-col gap-1.5 max-w-sm">
            <label class="text-xs font-bold text-gray-500 uppercase pl-1">Attach File (Image/PDF/Doc/Zip)</label>
            <input type="file" name="attachment" class="w-full bg-white border border-gray-200 px-5 py-2.5 rounded-2xl text-sm font-semibold outline-none file:mr-4 file:py-1 file:px-2.5 file:rounded-xl file:border-0 file:text-xs file:font-semibold file:bg-gray-100 file:text-gray-700 hover:file:bg-gray-200">
            <span class="text-[10px] text-gray-400 pl-1">Max file size: 5MB</span>
        </div>

        <div class="flex justify-end">
            <button type="submit" class="bg-[#2ba351] hover:bg-[#1a285a] text-white px-6 py-3 rounded-xl font-bold text-xs uppercase tracking-wider transition-all shadow-md">
                <i class="fa-solid fa-paper-plane mr-1.5"></i> Send Message
            </button>
        </div>
    </form>
    @else
    <div class="p-4 text-center text-gray-400 text-xs border-t border-gray-100 bg-gray-50 rounded-2xl">
        <i class="fa-solid fa-lock mr-1.5"></i> This ticket conversation is closed.
    </div>
    @endif
</div>
                @else
                {{-- ── ১. টিকিট লিস্ট ও নতুন টিকিট তৈরি করার প্যানেল ── --}}
                <div class="flex items-center justify-between border-b border-gray-100 pb-3 mb-6">
                    <h3 class="text-[#0a1d4a] text-lg md:text-[20px] font-bold uppercase tracking-wide">
                        My Support Tickets
                    </h3>
                    <button type="button" onclick="toggleTicketForm()" id="ticketToggleBtn" class="bg-[#2ba351] hover:bg-[#1a285a] text-white text-xs font-bold px-4 py-2.5 rounded-xl transition-all">
                        <i class="fa-solid fa-plus mr-1"></i> Open New Ticket
                    </button>
                </div>

                <!-- SUB-SECTION 1: Tickets List -->
                <div id="tickets-list-container">
                    @php
                    $userTickets = \App\Models\Ticket::where('user_id', $user->id)->latest()->get();
                    @endphp

                    @if($userTickets->isEmpty())
                    <div class="text-center py-12 text-gray-400">
                        <i class="fa-solid fa-headset text-4xl mb-3"></i>
                        <p class="font-semibold text-base">You haven't opened any support tickets yet.</p>
                    </div>
                    @else
                    <div class="overflow-x-auto">
                        <table class="w-full text-left border-collapse" style="font-size: 13.5px;">
                            <thead>
                                <tr class="border-b border-gray-100 text-gray-400 uppercase text-xs font-bold">
                                    <th class="py-3">Ticket ID</th>
                                    <th class="py-3">Subject</th>
                                    <th class="py-3">Category</th>
                                    <th class="py-3">Read Status</th>
                                    <th class="py-3">Status</th>
                                    <th class="py-3 text-right">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($userTickets as $ticket)
                                @php
                                $isUnread = !$ticket->is_read_user;
                                @endphp
                                <tr class="border-b border-gray-50 hover:bg-gray-50/50 transition-colors">
                                    <td class="py-4 font-bold text-gray-700">#{{ $ticket->id }}</td>
                                    <td class="py-4 font-semibold text-gray-800">{{ $ticket->subject }}</td>
                                    <td class="py-4"><span class="bg-gray-100 text-gray-700 px-2 py-1 rounded-md text-xs font-semibold">{{ $ticket->category }}</span></td>
                                    <td class="py-4">
                                        @if($isUnread)
                                        <span class="inline-flex items-center gap-1.5 bg-red-50 text-red-600 px-2.5 py-1 rounded-full text-xs font-bold border border-red-100">
                                            <span class="w-1.5 h-1.5 bg-red-500 rounded-full animate-pulse"></span>
                                            New Message
                                        </span>
                                        @else
                                        <span class="bg-gray-50 text-gray-400 px-2.5 py-1 rounded-full text-xs font-semibold border border-gray-100">Read</span>
                                        @endif
                                    </td>
                                    <td class="py-4">
                                        @if($ticket->status === 'open')
                                        <span class="bg-emerald-50 text-[#2ba351] px-2 py-1 rounded-full text-xs font-bold border border-emerald-100">Open</span>
                                        @elseif($ticket->status === 'replied')
                                        <span class="bg-blue-50 text-blue-600 px-2 py-1 rounded-full text-xs font-bold border border-blue-100">Replied</span>
                                        @else
                                        <span class="bg-gray-100 text-gray-500 px-2 py-1 rounded-full text-xs font-bold border border-gray-200">Closed</span>
                                        @endif
                                    </td>

                                    <td class="py-4 text-right">
                                        <a href="{{ route('user.dashboard', ['ticket_id' => $ticket->id]) }}" class="text-[#2ba351] font-bold hover:underline">
                                            View Chat <i class="fa-solid fa-arrow-right-long ml-1"></i>
                                        </a>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    @endif
                </div>

                <!-- SUB-SECTION 2: Create Ticket Form -->
                <div id="tickets-form-container" class="hidden">
                    <form action="{{ route('admin.tickets.store') }}"  method="POST"  enctype="multipart/form-data" class="flex flex-col gap-4 max-w-xl">
                        @csrf
                        <div class="flex flex-col gap-1.5">
                            <label class="text-xs font-bold text-gray-500 uppercase pl-1">Subject / Title</label>
                            <input type="text" name="subject" required placeholder="Describe the topic in short" class="w-full bg-gray-50 border border-gray-200 px-5 py-3.5 rounded-2xl text-base font-bold text-gray-700 outline-none focus:ring-2 focus:ring-[#2ba351]/20 transition-all">
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div class="flex flex-col gap-1.5">
                                <label class="text-xs font-bold text-gray-500 uppercase pl-1">Category</label>
                                <select name="category" required class="w-full bg-gray-50 border border-gray-200 px-5 py-3.5 rounded-2xl text-base font-bold text-gray-700 outline-none focus:ring-2 focus:ring-[#2ba351]/20 transition-all">
                                    <option value="Technical">Technical Support</option>
                                    <option value="General">General Inquiry</option>
                                </select>
                            </div>
                            <div class="flex flex-col gap-1.5">
                                <label class="text-xs font-bold text-gray-500 uppercase pl-1">Priority</label>
                                <select name="priority" required class="w-full bg-gray-50 border border-gray-200 px-5 py-3.5 rounded-2xl text-base font-bold text-gray-700 outline-none focus:ring-2 focus:ring-[#2ba351]/20 transition-all">
                                    <option value="low">Low</option>
                                    <option value="medium" selected>Medium</option>
                                    <option value="high">High</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-12 flex flex-col gap-1.5 mt-2">
                            <label class="text-xs font-bold text-gray-500 uppercase pl-1">Attachment (Image/PDF/Doc/Zip)</label>

                            <!-- ফাইল ইনপুট -->
                            <input type="file" name="attachment" id="ticket-attachment-input" onchange="previewTicketAttachment(this)" class="w-full bg-gray-50 border border-gray-200 px-5 py-3 rounded-2xl text-sm font-semibold outline-none file:mr-4 file:py-1.5 file:px-3 file:rounded-xl file:border-0 file:text-xs file:font-semibold file:bg-gray-200 file:text-gray-700 hover:file:bg-gray-300">
                            <span class="text-[10px] text-gray-400 pl-1">Max file size: 5MB</span>

                            <!-- ডাইনামিক প্রিভিউ প্যানেল (ডিফল্ট হাইড করা থাকে) -->
                            <div id="ticket-attachment-preview" class="hidden mt-3 p-3 border border-gray-100 rounded-2xl bg-gray-50 flex items-center gap-3 max-w-sm relative transition-all">

                                <!-- ইমেজ প্রিভিউ থাম্বনেইল -->
                                <img id="ticket-preview-image" src="" class="hidden w-16 h-16 rounded-xl object-cover border border-gray-200" />

                                <!-- ডকুমেন্ট ফাইল আইকন (পিডিএফ/জিপ এর জন্য) -->
                                <div id="ticket-preview-icon" class="hidden w-16 h-16 rounded-xl bg-gray-150 flex items-center justify-center text-gray-500 text-2xl">
                                    <i class="fa-solid fa-file-lines"></i>
                                </div>

                                <!-- ফাইলের নাম ও সাইজ ডিটেইলস -->
                                <div class="flex flex-col overflow-hidden">
                                    <span id="ticket-preview-filename" class="text-xs font-bold text-gray-700 truncate max-w-[180px]">filename.jpg</span>
                                    <span id="ticket-preview-filesize" class="text-[10px] text-gray-400">0.00 MB</span>
                                </div>

                                <!-- প্রিভিউ ডিলিট/ক্লিয়ার বাটন -->
                                <button type="button" onclick="clearTicketAttachment()" class="absolute top-2.5 right-2.5 w-6 h-6 bg-red-100 text-red-500 rounded-full flex items-center justify-center hover:bg-red-200 transition shadow-sm">
                                    <i class="fa-solid fa-xmark text-xs"></i>
                                </button>
                            </div>
                        </div>
                        <div class="flex flex-col gap-1.5">
                            <label class="text-xs font-bold text-gray-500 uppercase pl-1">Explain Your Issue</label>
                            <textarea name="description" required rows="5" placeholder="Write details about your problem..." class="w-full bg-gray-50 border border-gray-200 px-5 py-3.5 rounded-2xl text-base font-bold text-gray-700 outline-none focus:ring-2 focus:ring-[#2ba351]/20 transition-all"></textarea>
                        </div>

                        <div class="mt-4 flex gap-3">
                            <button type="submit" class="bg-[#2ba351] hover:bg-[#1a285a] text-white px-8 py-3.5 rounded-2xl font-bold text-xs uppercase tracking-wider transition-all shadow-md">
                                Submit Ticket
                            </button>
                            <button type="button" onclick="toggleTicketForm()" class="bg-gray-100 hover:bg-gray-200 text-gray-700 px-6 py-3.5 rounded-2xl font-bold text-xs uppercase tracking-wider transition-all">
                                Cancel
                            </button>
                        </div>
                    </form>
                </div>
                @endif
            </div>

            <!-- গ. EDIT PROFILE TAB SECTION -->
            <div id="profile-tab" class="dashboard-section hidden bg-white p-6 md:p-8 rounded-3xl border border-gray-100 shadow-sm">
                <h3 class="text-[#0a1d4a] text-lg md:text-[20px] font-bold border-b border-gray-100 pb-3 mb-6 uppercase tracking-wide">
                    Edit Profile Details
                </h3>

                <form action="{{ route('user.profile.update') }}" method="POST" class="flex flex-col gap-4 max-w-xl">
                    @csrf
                    @method('PUT')

                    <div class="flex flex-col gap-1.5">
                        <label class="text-xs font-bold text-gray-500 uppercase pl-1">Name</label>
                        <input type="text" name="name" required value="{{ old('name', $user->name) }}" class="w-full bg-gray-50 border border-gray-200 px-5 py-3.5 rounded-2xl text-base font-bold text-gray-700 outline-none focus:ring-2 focus:ring-[#2ba351]/20 transition-all">
                        @error('name') <span class="text-red-500 text-xs mt-1 pl-1 font-semibold">{{ $message }}</span> @enderror
                    </div>

                    <div class="flex flex-col gap-1.5">
                        <label class="text-xs font-bold text-gray-500 uppercase pl-1">E-mail</label>
                        <input type="email" name="email" required value="{{ old('email', $user->email) }}" class="w-full bg-gray-50 border border-gray-200 px-5 py-3.5 rounded-2xl text-base font-bold text-gray-700 outline-none focus:ring-2 focus:ring-[#2ba351]/20 transition-all">
                        @error('email') <span class="text-red-500 text-xs mt-1 pl-1 font-semibold">{{ $message }}</span> @enderror
                    </div>

                    <div class="mt-4">
                        <button type="submit" class="bg-[#2ba351] hover:bg-[#1a285a] text-white px-8 py-3.5 rounded-2xl font-bold text-xs uppercase tracking-wider transition-all shadow-md shadow-[#2ba351]/10">
                            Update Profile
                        </button>
                    </div>
                </form>
            </div>

            <!-- ঘ. CHANGE PASSWORD TAB SECTION -->
            <div id="password-tab" class="dashboard-section hidden bg-white p-6 md:p-8 rounded-3xl border border-gray-100 shadow-sm">
                <h3 class="text-[#0a1d4a] text-lg md:text-[20px] font-bold border-b border-gray-100 pb-3 mb-6 uppercase tracking-wide">
                    Change Password
                </h3>

                <form action="{{ route('user.password.update') }}" method="POST" class="flex flex-col gap-4 max-w-xl">
                    @csrf
                    @method('PUT')

                    <div class="flex flex-col gap-1.5">
                        <label class="text-xs font-bold text-gray-500 uppercase pl-1">Current Password</label>
                        <input type="password" name="current_password" required placeholder="Enter Current Password" class="w-full bg-gray-50 border border-gray-200 px-5 py-3.5 rounded-2xl text-base font-bold text-gray-700 outline-none focus:ring-2 focus:ring-[#2ba351]/20 transition-all">
                    </div>

                    <div class="flex flex-col gap-1.5">
                        <label class="text-xs font-bold text-gray-500 uppercase pl-1">New Password</label>
                        <input type="password" name="password" required placeholder="Choose New Password (min 8 chars)" class="w-full bg-gray-50 border border-gray-200 px-5 py-3.5 rounded-2xl text-base font-bold text-gray-700 outline-none focus:ring-2 focus:ring-[#2ba351]/20 transition-all">
                    </div>

                    <div class="flex flex-col gap-1.5">
                        <label class="text-xs font-bold text-gray-500 uppercase pl-1">Confirm New Password</label>
                        <input type="password" name="password_confirmation" required placeholder="Re-type New Password" class="w-full bg-gray-50 border border-gray-200 px-5 py-3.5 rounded-2xl text-base font-bold text-gray-700 outline-none focus:ring-2 focus:ring-[#2ba351]/20 transition-all">
                    </div>

                    <div class="mt-4">
                        <button type="submit" class="bg-[#2ba351] hover:bg-[#1a285a] text-white px-8 py-3.5 rounded-2xl font-bold text-xs uppercase tracking-wider transition-all shadow-md shadow-[#2ba351]/10">
                            Change Password
                        </button>
                    </div>
                </form>
            </div>

        </div>

    </div>
</div>

<!-- Custom Confirmation Modal -->
<div id="confirmModal" class="hidden fixed inset-0 z-[10000] flex items-center justify-center p-4 backdrop-blur-sm bg-black/60 transition-all duration-300 select-none">
    <div class="bg-white rounded-3xl p-6 border border-gray-100 max-w-sm w-full text-center shadow-2xl scale-95 opacity-0 transition-all duration-300">
        <div class="w-16 h-16 bg-red-50 text-red-500 rounded-full mx-auto flex items-center justify-center text-xl mb-4">
            <i class="fa-solid fa-triangle-exclamation"></i>
        </div>
        <h4 class="font-extrabold text-gray-800 text-lg mb-2">Are you sure?</h4>
        <p id="confirmModalText" class="text-gray-500 text-xs leading-relaxed mb-6">
            Do you really want to perform this action?
        </p>
        <div class="flex gap-3">
            <button type="button" onclick="closeConfirmModal()" class="w-1/2 bg-gray-100 hover:bg-gray-200 text-gray-700 py-3 rounded-xl font-bold text-xs uppercase tracking-wider transition-all">
                Cancel
            </button>
            <button type="button" onclick="executeConfirmAction()" class="w-1/2 bg-red-500 hover:bg-red-600 text-white py-3 rounded-xl font-bold text-xs uppercase tracking-wider transition-all shadow-md shadow-red-500/10">
                Yes, Remove
            </button>
        </div>
    </div>
</div>
@include('partials.recaptcha')

<script>
    let confirmActionCallback = null;

    // 1. Dashboard Tab Switching
    function switchDashboardTab(targetTabId, clickedBtn) {
        document.querySelectorAll('.dashboard-section').forEach(section => {
            section.classList.add('hidden');
        });

        const targetSection = document.getElementById(targetTabId);
        if (targetSection) {
            targetSection.classList.remove('hidden');
        }

        document.querySelectorAll('.dashboard-menu-btn').forEach(btn => {
            btn.className = "dashboard-menu-btn w-full text-left px-4 py-3 bg-gray-50 text-gray-600 rounded-xl text-xs md:text-base font-bold border border-gray-100 hover:bg-gray-100 hover:text-gray-900 flex items-center gap-2 transition-all";
        });

        clickedBtn.className = "dashboard-menu-btn w-full text-left px-4 py-3 bg-[#2ba351] text-white rounded-xl text-xs md:text-base font-extrabold flex items-center gap-2 transition-all shadow-sm";

        const tabName = targetTabId.replace('-tab', '');
        const newUrl = window.location.protocol + "//" + window.location.host + window.location.pathname + '?tab=' + tabName;
        window.history.pushState({ path: newUrl }, '', newUrl);
    }
    // টিকিট অ্যাটাচমেন্ট লাইভ প্রিভিউ হ্যান্ডলার
    function previewTicketAttachment(input) {
        const previewWrapper = document.getElementById('ticket-attachment-preview');
        const previewImage = document.getElementById('ticket-preview-image');
        const previewIcon = document.getElementById('ticket-preview-icon');
        const previewFilename = document.getElementById('ticket-preview-filename');
        const previewFilesize = document.getElementById('ticket-preview-filesize');

        if (input.files && input.files[0]) {
            const file = input.files[0];

            // ফাইলের নাম ও সাইজ দেখানোর লজিক
            previewFilename.textContent = file.name;
            previewFilesize.textContent = (file.size / (1024 * 1024)).toFixed(2) + ' MB';

            // প্রিভিউ কন্টেইনার শো করা
            previewWrapper.classList.remove('hidden');

            if (file.type.startsWith('image/')) {
                // ফাইলটি ইমেজ হলে থাম্বনেইল দেখাবে
                previewImage.src = URL.createObjectURL(file);
                previewImage.classList.remove('hidden');
                previewIcon.classList.add('hidden');
            } else {
                // ফাইলটি ইমেজ না হলে ফাইল এক্সটেনশন অনুযায়ী আইকন দেখাবে
                previewImage.classList.add('hidden');
                previewIcon.classList.remove('hidden');

                const ext = file.name.split('.').pop().toLowerCase();
                if (ext === 'pdf') {
                    previewIcon.innerHTML = '<i class="fa-solid fa-file-pdf text-red-500"></i>';
                } else if (ext === 'zip' || ext === 'rar') {
                    previewIcon.innerHTML = '<i class="fa-solid fa-file-zipper text-yellow-600"></i>';
                } else {
                    previewIcon.innerHTML = '<i class="fa-solid fa-file-lines text-blue-500"></i>';
                }
            }
        }
    }

    // প্রিভিউ বাতিল ও ইনপুট ক্লিয়ার হ্যান্ডলার
    function clearTicketAttachment() {
        const input = document.getElementById('ticket-attachment-input');
        const previewWrapper = document.getElementById('ticket-attachment-preview');

        if (input) input.value = ''; // ইনপুট ডাটা ক্লিয়ার
        if (previewWrapper) previewWrapper.classList.add('hidden'); // প্রিভিউ প্যানেল হাইড
    }
    // Toggle Ticket List and Create Form
    function toggleTicketForm() {
        const listContainer = document.getElementById('tickets-list-container');
        const formContainer = document.getElementById('tickets-form-container');
        const btn = document.getElementById('ticketToggleBtn');

        if (formContainer.classList.contains('hidden')) {
            formContainer.classList.remove('hidden');
            listContainer.classList.add('hidden');
            btn.innerHTML = '<i class="fa-solid fa-list mr-1"></i> View Tickets';
        } else {
            formContainer.classList.add('hidden');
            listContainer.classList.remove('hidden');
            btn.innerHTML = '<i class="fa-solid fa-plus mr-1"></i> Open New Ticket';
        }
    }

    // 2. Open Custom Confirmation Modal
    function showConfirmModal(message, callback) {
        const modal = document.getElementById('confirmModal');
        const card = modal ? modal.querySelector('.scale-95') : null;
        const text = document.getElementById('confirmModalText');

        if (modal && card && text) {
            text.textContent = message;
            confirmActionCallback = callback;

            modal.classList.remove('hidden');
            setTimeout(() => {
                card.classList.remove('scale-95', 'opacity-0');
                card.classList.add('scale-100', 'opacity-100');
            }, 10);
        }
    }

    // 3. Close Custom Confirmation Modal
    function closeConfirmModal() {
        const modal = document.getElementById('confirmModal');
        const card = modal ? modal.querySelector('.scale-100') : null;

        if (modal && card) {
            card.classList.remove('scale-100', 'opacity-100');
            card.classList.add('scale-95', 'opacity-0');
            setTimeout(() => {
                modal.classList.add('hidden');
                confirmActionCallback = null;
            }, 300);
        }
    }

    // 4. Execute stored callback
    function executeConfirmAction() {
        if (typeof confirmActionCallback === 'function') {
            confirmActionCallback();
        }
        closeConfirmModal();
    }

    // 5. AJAX Favorite removal
    async function removeFavoriteItem(projectId, btn) {
        showConfirmModal('Are you sure you want to remove this property from your favorites?', async function() {
            try {
                const response = await fetch("{{ route('projects.favorite.toggle') }}", {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({
                        project_id: projectId
                    })
                });

                const result = await response.json();

                if (response.ok && result.success) {
                    const card = btn.closest('.border');
                    if (card) {
                        card.classList.add('scale-95', 'opacity-0');
                        setTimeout(() => {
                            card.remove();
                            const remaining = document.querySelectorAll('#favorites-tab .grid .border');
                            if (remaining.length === 0) {
                                location.reload();
                            }
                        }, 300);
                    }
                } else {
                    alert(result.message || 'Something went wrong.');
                }
            } catch (error) {
                console.error('Error removing favorite:', error);
                alert('Connection error. Please try again.');
            }
        });
    }


    document.addEventListener("DOMContentLoaded", function() {
        const urlParams = new URLSearchParams(window.location.search);
        let tab = urlParams.get('tab');
        const hasTicketId = urlParams.get('ticket_id');

        if (hasTicketId) {
            tab = 'tickets';
        }

        if (tab) {
            const targetTabId = tab + '-tab';
            const targetBtnId = tab + '-menu-btn';
            
            const targetBtn = document.getElementById(targetBtnId);
            if (targetBtn) {
                switchDashboardTab(targetTabId, targetBtn);
            }
        }
    });

    document.addEventListener('keydown', function(e) {
        const modal = document.getElementById('confirmModal');
        if (modal && !modal.classList.contains('hidden')) {
            if (e.key === 'Escape') {
                closeConfirmModal();
            }
        }
    });
</script>
@endsection
