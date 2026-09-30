@extends('layouts.backend')
@section('title', 'Message List')

@section('content')

<style>
/* ══════════════════════════════════════════
   Gmail-style Split Pane — Contact Inbox
══════════════════════════════════════════ */
* { box-sizing: border-box; }

.gmail-wrap {
    display: flex;
    height: calc(100vh - 80px);
    background: #f6f8fc;
    font-family: 'Google Sans', Roboto, sans-serif;
    gap: 0;
    border-radius: 12px;
    overflow: hidden;
    box-shadow: 0 2px 10px rgba(0,0,0,0.1);
    margin: 12px;
}

/* ── LEFT PANEL ── */
.inbox-list {
    width: 380px;
    min-width: 280px;
    flex-shrink: 0;
    display: flex;
    flex-direction: column;
    background: #fff;
    border-right: 1px solid #e8eaed;
    overflow: hidden;
}

.inbox-header {
    padding: 14px 16px 10px;
    display: flex;
    align-items: center;
    justify-content: space-between;
    flex-shrink: 0;
}

.inbox-header h6 {
    font-size: 15px;
    font-weight: 600;
    color: #202124;
    margin: 0;
}

.badge-count {
    background: #1a73e8;
    color: #fff;
    font-size: 10px;
    font-weight: 700;
    padding: 2px 7px;
    border-radius: 10px;
}

/* ── FILTER & SEARCH ── */
.inbox-filters {
    padding: 0 16px 10px;
    display: flex;
    gap: 8px;
}

.filter-chip {
    font-size: 12px;
    padding: 5px 12px;
    border-radius: 16px;
    background: #f1f3f4;
    color: #5f6368;
    cursor: pointer;
    user-select: none;
    border: 1px solid transparent;
    transition: all 0.2s;
    display: inline-block;
    font-weight: 500;
}

.filter-chip input { display: none; }

.filter-chip:has(input:checked) {
    background: #e8f0fe;
    color: #1a73e8;
    border-color: #1a73e8;
}

.inbox-search {
    padding: 0 16px 12px;
    border-bottom: 1px solid #e8eaed;
}

.search-input-wrapper {
    position: relative;
    display: flex;
    align-items: center;
}

.search-input-wrapper .search-icon {
    position: absolute;
    left: 12px;
    color: #5f6368;
    font-size: 14px;
}

.search-input-wrapper input {
    width: 100%;
    padding: 10px 12px 10px 36px;
    border: 1px solid #f1f3f4;
    background: #f1f3f4;
    border-radius: 8px;
    font-size: 13px;
    color: #202124;
    outline: none;
    transition: all 0.2s;
}

.search-input-wrapper input:focus {
    background: #fff;
    border-color: #1a73e8;
    box-shadow: 0 1px 3px rgba(0,0,0,0.1);
}

.inbox-scroll {
    flex: 1;
    overflow-y: auto;
    overflow-x: hidden;
}

.inbox-scroll::-webkit-scrollbar { width: 4px; }
.inbox-scroll::-webkit-scrollbar-thumb { background: #dadce0; border-radius: 4px; }

/* ── Message Row ── */
.msg-row {
    display: flex;
    align-items: flex-start;
    padding: 10px 14px;
    border-bottom: 1px solid #f1f3f4;
    cursor: pointer;
    transition: background 0.12s;
    gap: 10px;
}

.msg-row:hover { background: #f1f3f4; }
.msg-row.active { background: #e8f0fe !important; }
.msg-row.unread { background: #fff; }
.msg-row.read   { background: #f8f9fa; }

.unread-dot {
    width: 8px; height: 8px;
    border-radius: 50%;
    background: #1a73e8;
    margin-top: 6px;
    flex-shrink: 0;
}

.read-space { width: 8px; flex-shrink: 0; }

.msg-avatar {
    width: 34px; height: 34px;
    border-radius: 50%;
    display: flex; align-items: center; justify-content: center;
    font-size: 13px; font-weight: 700;
    color: #fff;
    flex-shrink: 0;
    text-transform: uppercase;
}

.msg-content { flex: 1; min-width: 0; }

.msg-top {
    display: flex;
    justify-content: space-between;
    align-items: baseline;
    margin-bottom: 2px;
}

.msg-name {
    font-size: 13px;
    color: #202124;
    font-weight: 600;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
    max-width: 170px;
}

.msg-row.read .msg-name { font-weight: 400; color: #5f6368; }

.msg-time {
    font-size: 11px;
    color: #80868b;
    flex-shrink: 0;
    margin-left: 6px;
}

.msg-row.unread .msg-time { font-weight: 600; color: #202124; }

.msg-preview {
    font-size: 12px;
    color: #5f6368;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
}

/* ── RIGHT PANEL ── */
.detail-pane {
    flex: 1;
    display: flex;
    flex-direction: column;
    background: #fff;
    overflow: hidden;
}

.detail-empty {
    flex: 1;
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    color: #80868b;
    gap: 12px;
}

.detail-empty p { font-size: 14px; margin: 0; }

.detail-content {
    display: none;
    flex-direction: column;
    height: 100%;
    overflow-y: auto;
}

.detail-content.visible { display: flex; }

.detail-topbar {
    padding: 16px 24px 12px;
    border-bottom: 1px solid #e8eaed;
    display: flex;
    align-items: flex-start;
    justify-content: space-between;
    gap: 12px;
}

.detail-subject {
    font-size: 20px;
    font-weight: 500;
    color: #202124;
    flex: 1;
}

.d-action-btn {
    width: 34px; height: 34px;
    border-radius: 50%;
    border: none;
    background: transparent;
    cursor: pointer;
    display: flex; align-items: center; justify-content: center;
    color: #5f6368;
}
.d-action-btn:hover { background: #f1f3f4; }

.detail-sender {
    padding: 14px 24px;
    border-bottom: 1px solid #f1f3f4;
    display: flex;
    align-items: center;
    gap: 12px;
}

.sender-avatar {
    width: 40px; height: 40px;
    border-radius: 50%;
    display: flex; align-items: center; justify-content: center;
    font-size: 16px; font-weight: 700;
    color: #fff;
}

.sender-info { flex: 1; min-width: 0; }
.sender-name { font-size: 14px; font-weight: 600; color: #202124; }
.sender-email { font-size: 12px; color: #5f6368; }
.sender-date { font-size: 12px; color: #80868b; }

.meta-chips {
    padding: 12px 24px;
    display: flex;
    flex-wrap: wrap;
    gap: 8px;
    border-bottom: 1px solid #f1f3f4;
}

.meta-chip {
    background: #f1f3f4;
    border-radius: 16px;
    padding: 4px 12px;
    font-size: 12px;
    color: #5f6368;
    display: flex;
    align-items: center;
    gap: 5px;
}

.meta-chip strong { color: #202124; font-weight: 500; }

.detail-body {
    padding: 0 24px 24px 24px;    
    flex: 1;
    font-size: 15px;
    color: #202124;
    line-height: 1.75;
    white-space: pre-wrap;
    word-break: break-word;
}
.detail-sub {
    padding-left: 24px;
    margin: 12px 0 4px;
    font-weight: 500;
    line-height: 1.75;
    font-size: 16px;
    color: #202124;
}

/* Avatar Colors */
.av-0 { background: #1a73e8; }
.av-1 { background: #34a853; }
.av-2 { background: #ea4335; }
.av-3 { background: #9334e6; }
.av-4 { background: #00897b; }
.av-5 { background: #e37400; }

@media (max-width: 768px) {
    .gmail-wrap { flex-direction: column; height: auto; margin: 6px; }
    .inbox-list { width: 100%; border-right: none; border-bottom: 1px solid #e8eaed; max-height: 40vh; }
    .detail-pane { min-height: 50vh; }
}
</style>

<div class="gmail-wrap" style="font-family: 'Outfit', sans-serif;">

    {{-- ════ LEFT: Message List ════ --}}
    <div class="inbox-list">
        <div class="inbox-header">
            <h6>Inbox</h6>
            @php $unread = $contacts->where('is_read', false)->count(); @endphp
            @if($unread)
                <span class="badge-count" id="badgeCount">{{ $unread }} new</span>
            @endif
        </div>

        {{-- SUPER ADMIN ONLY: COMPANY FILTER --}}
        @if(auth()->user()->hasRole('super-admin'))
            @php
                // কন্ট্রোলার থেকে eager-load না হয়ে থাকলে অটোমেটিক রিলেশন থেকে ইউনিক কোম্পানি জেনারেট করার সেফগার্ড
                $companiesList = $contacts->pluck('company')->filter()->unique('id');
            @endphp
            @if($companiesList->isNotEmpty() || isset($companies))
                <div class="inbox-company-filter px-3 pb-2">
                    <select id="companyFilter" class="form-select form-select-sm" onchange="filterContacts()" style="font-size: 12px; border-radius: 8px; border: 1px solid #e8eaed; background-color: #f1f3f4; padding: 6px 12px;">
                        <option value="all">All Companies</option>
                        @foreach($companies ?? $companiesList as $comp)
                            <option value="{{ $comp->id }}">{{ $comp->company_name ?? $comp->name }}</option>
                        @endforeach
                    </select>
                </div>
            @endif
        @endif

        {{-- READ / UNREAD STATUS FILTERS --}}
        <div class="inbox-filters">
            <label class="filter-chip">
                <input type="radio" name="filter_status" value="all" checked onchange="filterContacts()">
                All
            </label>
            <label class="filter-chip">
                <input type="radio" name="filter_status" value="unread" onchange="filterContacts()">
                Unread
            </label>
            <label class="filter-chip">
                <input type="radio" name="filter_status" value="read" onchange="filterContacts()">
                Read
            </label>
        </div>

        {{-- SEARCH BAR (Bootstrap Icons Used) --}}
        <div class="inbox-search">
            <div class="search-input-wrapper">
                <i class="bi bi-search search-icon"></i>
                <input type="text" id="searchInput" placeholder="Search by name, phone, message..." onkeyup="filterContacts()">
            </div>
        </div>

        <div class="inbox-scroll" id="inboxScroll">
            @forelse($contacts as $contact)
            @php
                $ci = $contact->id % 6;
                $initial = strtoupper(mb_substr($contact->name, 0, 1));
            @endphp
            <div class="msg-row {{ $contact->is_read ? 'read' : 'unread' }}"
                 id="row-{{ $contact->id }}"
                 onclick="openContact({{ $contact->id }})">

                @if(!$contact->is_read)
                    <div class="unread-dot" id="dot-{{ $contact->id }}"></div>
                @else
                    <div class="read-space"></div>
                @endif

                <div class="msg-avatar av-{{ $ci }}">{{ $initial }}</div>

                <div class="msg-content">
                    <div class="msg-top">
                        <span class="msg-name">{{ $contact->name }}</span>
                        <span class="msg-time">{{ $contact->created_at->format('M d') }}</span>
                    </div>
                    <div class="msg-preview">{{ Str::limit($contact->subject ?? $contact->message, 60) }}</div>
                </div>
            </div>
            @empty
            <div style="padding:40px 16px; text-align:center; color:#80868b; font-size:13px;">
                No messages found
            </div>
            @endforelse
        </div>
    </div>

    {{-- ════ RIGHT: Detail Pane ════ --}}
    <div class="detail-pane">
        {{-- Empty state (Bootstrap Icons Used) --}}
        <div class="detail-empty" id="detailEmpty">
            <i class="bi bi-envelope-open text-muted" style="font-size: 3.5rem;"></i>
            <p class="mt-2">Select a message to read</p>
        </div>

        {{-- Detail content --}}
        <div class="detail-content" id="detailContent">
            <div class="detail-topbar">
                <div class="detail-subject" id="dSubject">—</div>
                <div class="detail-actions">
                    <form id="deleteForm" method="POST" style="display:inline;" onsubmit="return confirm('Delete this message?')">
                        @csrf @method('DELETE')
                           
                                        <button type="button" class="btn btn-action btn-outline-danger btn-confirm"
                                            data-title="Move to Trash"
                                            data-message="Are you sure you want to move this record to trash folder?"
                                            data-type="danger"
                                            title="Move to trash">
                                            <i class="bi bi-trash-fill"></i>
                                        </button>
                                   
                               
                    </form>
                </div>
            </div>

            <div class="detail-sender">
                <div class="sender-avatar" id="dAvatar">A</div>
                <div class="sender-info">
                    <div class="sender-name" id="dName">—</div>
                    <div class="sender-email" id="dEmail">—</div>
                </div>
                <div class="sender-date" id="dDate">—</div>
            </div>

            {{-- Info Meta Chips (Bootstrap Icons Used) --}}
            <div class="meta-chips">
                <div class="meta-chip"><i class="bi bi-telephone text-muted me-1"></i> <strong id="dPhone">—</strong></div>
                <div class="meta-chip" id="dAddressChip" style="display:none;"><i class="bi bi-geo-alt text-muted me-1"></i> <strong id="dAddress">—</strong></div>
                @if(auth()->user()->hasRole('super-admin'))
                    <div class="meta-chip" id="dCompanyChip" style="display:none;"><i class="bi bi-building text-muted me-1"></i> <strong id="dCompany">—</strong></div>
                @endif
            </div>

            <div class="detail-sub" id="dSub">—</div>
            <div class="detail-body" id="dBody">—</div>
        </div>
    </div>
</div>
{{-- Confirm Modal --}}
<div class="modal fade" id="confirmModal" tabindex="-1" aria-labelledby="confirmModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" style="max-width: 380px;">
        <div class="modal-content border-0 shadow-lg" style="border-radius: 16px;">
            <div class="modal-body text-center p-4">
                <div id="confirmModalIconContainer"
                    class="mb-3 d-inline-flex align-items-center justify-content-center rounded-circle"
                    style="width: 72px; height: 72px; font-size: 32px; border: 1px solid;">
                    <i id="confirmModalIcon" class="bi"></i>
                </div>
                <h5 class="fw-bold text-dark mb-2" id="confirmModalTitle"></h5>
                <p class="text-muted small mb-4" id="confirmModalMessage"></p>
                <div class="d-flex gap-2 justify-content-center">
                    <button type="button" class="btn btn-light px-4 py-2 fw-semibold text-secondary"
                        data-bs-dismiss="modal" style="border-radius: 8px; font-size: 0.875rem;">Cancel</button>
                    <button type="button" class="btn px-4 py-2 fw-semibold text-white"
                        id="confirmModalBtn" style="border-radius: 8px; font-size: 0.875rem;"></button>
                </div>
            </div>
        </div>
    </div>
</div>
@push('scripts')
<script>

    const confirmModalEl = document.getElementById('confirmModal');
const confirmModal = new bootstrap.Modal(confirmModalEl);

let pendingForm = null;

document.querySelectorAll('.btn-confirm').forEach(btn => {
    btn.addEventListener('click', function () {
        const title   = this.dataset.title   || 'Are you sure?';
        const message = this.dataset.message || 'This action cannot be undone.';
        const type    = this.dataset.type    || 'danger'; // danger | warning | primary etc.

        // Find the form this button belongs to
        pendingForm = this.closest('form');

        document.getElementById('confirmModalTitle').textContent = title;
        document.getElementById('confirmModalMessage').textContent = message;

        // Icon + color based on type
        const iconEl = document.getElementById('confirmModalIcon');
        const iconContainer = document.getElementById('confirmModalIconContainer');
        const confirmBtn = document.getElementById('confirmModalBtn');

        const typeMap = {
            danger:  { icon: 'bi-trash-fill', color: '#dc3545' },
            warning: { icon: 'bi-exclamation-triangle-fill', color: '#ffc107' },
            primary: { icon: 'bi-question-circle-fill', color: '#0d6efd' }
        };
        const cfg = typeMap[type] || typeMap.danger;

        iconEl.className = 'bi ' + cfg.icon;
        iconContainer.style.color = cfg.color;
        iconContainer.style.borderColor = cfg.color;

        confirmBtn.textContent = type === 'danger' ? 'Delete' : 'Confirm';
        confirmBtn.style.backgroundColor = cfg.color;

        confirmModal.show();
    });
});

// On confirm click -> submit the pending form
document.getElementById('confirmModalBtn').addEventListener('click', function () {
    if (pendingForm) {
        pendingForm.submit();
        pendingForm = null;
    }
    confirmModal.hide();
});
// JSON ডেটা ম্যাপিং
const CONTACTS = @json($contacts->keyBy('id')->toArray());
let currentId = null;

const avColors = ['#1a73e8','#34a853','#ea4335','#9334e6','#00897b','#e37400'];

// search filter + status filter + company filter সম্বলিত লজিক
function filterContacts() {
    const searchTerm = document.getElementById('searchInput').value.toLowerCase();
    const statusFilter = document.querySelector('input[name="filter_status"]:checked').value; // 'all', 'unread', 'read'
    
    // সুপার এডমিন ফিল্টার হ্যান্ডেলার
    const companyFilterEl = document.getElementById('companyFilter');
    const companyFilter = companyFilterEl ? companyFilterEl.value : 'all';

    document.querySelectorAll('.msg-row').forEach(row => {
        const id = row.getAttribute('id').replace('row-', '');
        const c = CONTACTS[id];
        if (!c) return;

        // ১. Read Status ফিল্টার
        let matchesStatus = true;
        if (statusFilter === 'unread') {
            matchesStatus = (c.is_read == 0 || c.is_read == false);
        } else if (statusFilter === 'read') {
            matchesStatus = (c.is_read == 1 || c.is_read == true);
        }

        // ২. Company ফিল্টার (শুধুমাত্র সুপার এডমিনের জন্য)
        let matchesCompany = true;
        if (companyFilter !== 'all') {
            matchesCompany = (String(c.company_id) === String(companyFilter));
        }

        // ৩. সার্চ ইনপুট ফিল্টার
        const searchableText = [
            c.name || '', 
            c.email || '', 
            c.phone || '', 
            c.subject || '', 
            c.message || '', 
            c.address || ''
        ].join(' ').toLowerCase();
        
        const matchesSearch = searchableText.includes(searchTerm);

        // চূড়ান্ত শো/হাইড লজিক
        if (matchesStatus && matchesCompany && matchesSearch) {
            row.style.display = 'flex';
        } else {
            row.style.display = 'none';
        }
    });
}

function openContact(id) {
    const c = CONTACTS[id];
    if (!c) return;
    currentId = id;

    // Active row সিলেক্ট ও হাইলাইট করা
    document.querySelectorAll('.msg-row').forEach(r => r.classList.remove('active'));
    const row = document.getElementById('row-' + id);
    if (row) row.classList.add('active');

    // ডানপাশের প্যানেল ডেটা দ্বারা ফিল করা
    const ci = id % 6;
    document.getElementById('dAvatar').textContent = c.name.charAt(0).toUpperCase();
    document.getElementById('dAvatar').style.background = avColors[ci];

    document.getElementById('dSubject').textContent = c.subject ? c.subject : '(No Subject)';
    document.getElementById('dName').textContent    = c.name;
    document.getElementById('dEmail').textContent   = c.email || 'No email provided';
    document.getElementById('dPhone').textContent   = c.phone || 'No phone provided';
    
    document.getElementById('dSub').textContent = c.subject ? 'Subject: ' + c.subject : '';
    document.getElementById('dBody').textContent    = c.message || '';

    const d = new Date(c.created_at);
    document.getElementById('dDate').textContent = d.toLocaleString('en-BD', {
        dateStyle: 'medium', timeStyle: 'short'
    });

    // এড্রেস চেক ও শো লজিক
    if (c.address) {
        document.getElementById('dAddress').textContent = c.address;
        document.getElementById('dAddressChip').style.display = 'flex';
    } else {
        document.getElementById('dAddressChip').style.display = 'none';
    }

    // সুপার এডমিন কোম্পানি চিপ চেক
    const companyChip = document.getElementById('dCompanyChip');
    if (companyChip) {
        if (c.company) {
            document.getElementById('dCompany').textContent = c.company.company_name || c.company.name || 'Company ID: ' + c.company_id;
            companyChip.style.display = 'flex';
        } else if (c.company_id) {
            document.getElementById('dCompany').textContent = 'Company ID: ' + c.company_id;
            companyChip.style.display = 'flex';
        } else {
            companyChip.style.display = 'none';
        }
    }

    document.getElementById('deleteForm').action = `/admin/contacts/${id}`; 

    document.getElementById('detailEmpty').style.display   = 'none';
    document.getElementById('detailContent').classList.add('visible');

    if (!c.is_read) {
        markRead(id);
    }
}

function markRead(id) {
    fetch(`/admin/contacts/${id}/mark-read`, {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': '{{ csrf_token() }}',
            'X-Requested-With': 'XMLHttpRequest', 
            'Accept': 'application/json',
            'Content-Type': 'application/json'
        }
    })
    .then(res => res.json())
    .then(data => {
        if (data.success) {
            CONTACTS[id].is_read = true;
            
            const row = document.getElementById('row-' + id);
            if (row) {
                row.classList.remove('unread');
                row.classList.add('read');
            }
            
            const dot = document.getElementById('dot-' + id);
            if (dot) dot.remove();

            const readSpace = document.createElement('div');
            readSpace.className = 'read-space';
            if (row) row.insertBefore(readSpace, row.firstChild);

            const badge = document.getElementById('badgeCount');
            if (badge) {
                const cur = parseInt(badge.textContent) - 1;
                if (cur <= 0) badge.remove();
                else badge.textContent = cur + ' new';
            }
        }
    })
    .catch(error => console.error("Error updating read status:", error));
}
</script>
@endpush

@endsection
