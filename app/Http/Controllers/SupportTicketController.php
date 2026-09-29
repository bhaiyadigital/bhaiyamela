<?php

namespace App\Http\Controllers;

use App\Models\Ticket;
use App\Models\TicketMessage;
use App\Traits\HandlesImageUpload;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SupportTicketController extends Controller
{
    use HandlesImageUpload;

    public function index(Request $request)
    {
        $user = Auth::user();
        $status = $request->get('status');
        $priority = $request->get('priority');

        $query = Ticket::query();

        if (!$user->hasRole('super-admin')) {
            $query->where('user_id', $user->id);
        }

        // ফিল্টার কুয়েরি অ্যাপ্লাই
        if ($status && $status !== 'all') {
            $query->where('status', $status);
        }
        if ($priority && $priority !== 'all') {
            $query->where('priority', $priority);
        }
        $tickets = $query->with('user')->latest()->paginate(15)->withQueryString();


        return view('admin.tickets.index', compact('tickets'));
    }

    public function create()
    {
        return view('admin.tickets.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'subject'     => 'required|string|max:255',
            'category'    => 'required|string',
            'priority'    => 'required|in:low,medium,high',
            'description' => 'required|string',
            'attachment'  => 'nullable|file|mimes:jpg,jpeg,png,webp,pdf,docx,zip|max:5120',

        ]);
        $attachmentPath = null;
        if ($request->hasFile('attachment')) {
            $file = $request->file('attachment');
            $extension = strtolower($file->getClientOriginalExtension());
            $imageExtensions = ['jpg', 'jpeg', 'png', 'webp', 'svg'];

            if (in_array($extension, $imageExtensions)) {
                $attachmentPath = $this->storeAsWebp($file, 'uploads/tickets');
            } else {

                $attachmentPath = $file->store('uploads/tickets', 'public');
            }
        }
        Ticket::create([
            'user_id'     => Auth::id(),
            'subject'     => $request->subject,
            'category'    => $request->category,
            'priority'    => $request->priority,
            'status'      => 'open',
            'description' => $request->description,
            'attachment'    => $attachmentPath,

        ]);

        if (!Auth::user()->hasRole('super-admin') && Auth::user()->user_type !== 'developer') {
            return redirect()->route('user.dashboard', ['tab' => 'tickets'])->with('success', 'Support ticket opened successfully.');
        }

        return redirect()->route('admin.tickets.index')->with('success', 'Support ticket opened successfully.');
    }

    public function show($id)
    {
        $ticket = Ticket::with(['user', 'messages.user'])->findOrFail($id);

        // টিকিট দেখার পারমিশন ভ্যালিডেশন (নিজে অথবা সুপার এডমিন হতে হবে)
        if (!Auth::user()->hasRole('super-admin') && $ticket->user_id !== Auth::id()) {
            abort(403, 'Unauthorized action.');
        }
        if (Auth::user()->hasRole('super-admin')) {
            $ticket->update(['is_read_admin' => true]);
        } else {
            $ticket->update(['is_read_user' => true]);
        }


        return view('admin.tickets.show', compact('ticket'));
    }

    public function reply(Request $request, $id)
    {
        $ticket = Ticket::findOrFail($id);

        if (!Auth::user()->hasRole('super-admin') && $ticket->user_id !== Auth::id()) {
            abort(403);
        }

        $request->validate([
            'message'    => 'required|string',
            'attachment' => 'nullable|file|mimes:jpg,jpeg,png,webp,svg,pdf,docx,zip|max:5120',
        ]);

        $attachmentPath = null;
        if ($request->hasFile('attachment')) {
            $file = $request->file('attachment');
            $extension = strtolower($file->getClientOriginalExtension());
            $imageExtensions = ['jpg', 'jpeg', 'png', 'webp', 'svg'];

            if (in_array($extension, $imageExtensions)) {
                $attachmentPath = $this->storeAsWebp($file, 'uploads/tickets/replies');
            } else {
                $attachmentPath = $file->store('uploads/tickets/replies', 'public');
            }
        }

        TicketMessage::create([
            'ticket_id'  => $ticket->id,
            'user_id'    => Auth::id(),
            'message'    => $request->message,
            'attachment' => $attachmentPath,
        ]);

        if (Auth::user()->hasRole('super-admin')) {
            $ticket->update([
                'status'        => 'replied',
                'is_read_admin' => true,
                'is_read_user'  => false,
            ]);
        } else {
            $ticket->update([
                'status'        => 'open',
                'is_read_admin' => false,
                'is_read_user'  => true,
            ]);
        }

        return redirect()->back()->with('success', 'Reply submitted successfully.');
    }
    public function close($id)
    {
        $ticket = Ticket::findOrFail($id);

        if (!Auth::user()->hasRole('super-admin') && $ticket->user_id !== Auth::id()) {
            abort(403, 'Unauthorized action.');
        }

        $ticket->update(['status' => 'closed']);

        return redirect()->back()->with('success', 'Ticket closed successfully.');
    }
}
