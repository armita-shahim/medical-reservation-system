<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class NotificationController extends Controller
{
    public function index(Request $request)
    {
        $request->validate([
            'unread' => ['sometimes', 'boolean'],
            'per_page' => ['sometimes', 'integer', 'between:1,50'],
        ]);

        $notifications = $request->user()->notifications();

        if ($request->boolean('unread')) {
            $notifications->whereNull('read_at');
        }

        return $notifications
            ->orderByDesc('created_at')
            ->paginate($request->integer('per_page', 15));
    }

    public function markAsRead(Request $request, string $id)
    {
        $notification = $request->user()->notifications()
            ->findOrFail($id);

        $notification->markAsRead();

        return response()->json([
            'message' => 'Notification marked as read.',
        ]);
    }
}
