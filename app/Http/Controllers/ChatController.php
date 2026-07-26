<?php

namespace App\Http\Controllers;

use App\Models\Message;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ChatController extends Controller
{
    public function index(Request $request)
    {
        $currentUserId = Auth::id();

        // Get all users except current user
        $users = User::where('id', '<>', $currentUserId)
            ->orderBy('name', 'asc')
            ->get();

        // Enrich users with last message & unread count
        $contacts = $users->map(function ($user) use ($currentUserId) {
            $lastMessage = Message::where(function ($q) use ($currentUserId, $user) {
                $q->where('sender_id', '=', $currentUserId)->where('receiver_id', '=', $user->id);
            })->orWhere(function ($q) use ($currentUserId, $user) {
                $q->where('sender_id', '=', $user->id)->where('receiver_id', '=', $currentUserId);
            })->latest('id')->first();

            $unreadCount = Message::where('sender_id', '=', $user->id)
                ->where('receiver_id', '=', $currentUserId)
                ->where('is_read', '=', false)
                ->count();

            $user->last_message = $lastMessage;
            $user->last_message_time = $lastMessage ? $lastMessage->created_at->format('H:i') : '';
            $user->last_message_date = $lastMessage ? $lastMessage->created_at : null;
            $user->unread_count = $unreadCount;

            return $user;
        })->sortByDesc(function ($user) {
            return $user->last_message_date ? $user->last_message_date->timestamp : 0;
        })->values();

        $activeUserId = $request->query('user_id');
        $activeUser = null;
        if ($activeUserId) {
            $activeUser = User::where('id', '=', $activeUserId)->first();
        }

        return view('pages.chat.index', compact('contacts', 'activeUser'));
    }

    public function fetchMessages(Request $request, $userId)
    {
        if (!$request->ajax() && !$request->wantsJson()) {
            return redirect()->route('chat.index', ['user_id' => $userId]);
        }

        $currentUserId = Auth::id();

        $targetUser = User::where('id', '=', $userId)->firstOrFail();

        // Mark unread incoming messages as read
        Message::where('sender_id', '=', $userId)
            ->where('receiver_id', '=', $currentUserId)
            ->where('is_read', '=', false)
            ->update(['is_read' => true]);

        // Get conversation messages
        $messages = Message::where(function ($q) use ($currentUserId, $userId) {
            $q->where('sender_id', '=', $currentUserId)->where('receiver_id', '=', $userId);
        })->orWhere(function ($q) use ($currentUserId, $userId) {
            $q->where('sender_id', '=', $userId)->where('receiver_id', '=', $currentUserId);
        })->orderBy('created_at', 'asc')->get();

        $formattedMessages = $messages->map(function ($msg) use ($currentUserId) {
            return [
                'id' => $msg->id,
                'sender_id' => $msg->sender_id,
                'receiver_id' => $msg->receiver_id,
                'message' => e($msg->message),
                'is_me' => ($msg->sender_id == $currentUserId),
                'is_read' => $msg->is_read,
                'time' => $msg->created_at->format('H:i'),
                'date' => $msg->created_at->translatedFormat('d M Y'),
            ];
        });

        return response()->json([
            'success' => true,
            'user' => [
                'id' => $targetUser->id,
                'name' => $targetUser->name,
                'role' => strtoupper($targetUser->role),
                'fakultas' => $targetUser->fakultas ?? '-',
                'program_studi' => $targetUser->program_studi ?? '-',
                'no_wa' => $targetUser->no_wa ?? '-',
            ],
            'messages' => $formattedMessages
        ]);
    }

    public function sendMessage(Request $request)
    {
        $request->validate([
            'receiver_id' => 'required|exists:users,id',
            'message' => 'required|string',
        ]);

        $currentUserId = Auth::id();

        $message = Message::create([
            'sender_id' => $currentUserId,
            'receiver_id' => $request->receiver_id,
            'message' => trim($request->message),
            'is_read' => false,
        ]);

        return response()->json([
            'success' => true,
            'message' => [
                'id' => $message->id,
                'sender_id' => $message->sender_id,
                'receiver_id' => $message->receiver_id,
                'message' => e($message->message),
                'is_me' => true,
                'is_read' => false,
                'time' => $message->created_at->format('H:i'),
            ]
        ]);
    }

    public function getUnreadTotal(Request $request)
    {
        if (!$request->ajax() && !$request->wantsJson()) {
            return redirect()->route('chat.index');
        }

        $count = Message::where('receiver_id', '=', Auth::id())
            ->where('is_read', '=', false)
            ->count();

        return response()->json(['unread_total' => $count]);
    }

    public function getUnreadDetails(Request $request)
    {
        if (!$request->ajax() && !$request->wantsJson()) {
            return redirect()->route('chat.index');
        }

        $currentUserId = Auth::id();

        $unreadCount = Message::where('receiver_id', '=', $currentUserId)
            ->where('is_read', '=', false)
            ->count();

        $unreadMessages = Message::with('sender')
            ->where('receiver_id', '=', $currentUserId)
            ->where('is_read', '=', false)
            ->latest('id')
            ->take(5)
            ->get()
            ->map(function ($msg) {
                return [
                    'id' => $msg->id,
                    'sender_id' => $msg->sender_id,
                    'sender_name' => $msg->sender->name ?? 'Pengguna',
                    'message' => \Illuminate\Support\Str::limit(e($msg->message), 35),
                    'time' => $msg->created_at->diffForHumans(),
                ];
            });

        return response()->json([
            'unread_count' => $unreadCount,
            'unread_messages' => $unreadMessages,
        ]);
    }
}
