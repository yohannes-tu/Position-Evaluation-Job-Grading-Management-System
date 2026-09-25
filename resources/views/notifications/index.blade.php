@extends('layouts.app')

@section('title', 'Notifications')

@section('content')

<div class="page-header">
    <div>
        <h1>Notifications</h1>
        <p>Review important recruitment and system activities.</p>
    </div>

    @if(auth()->user()->unreadNotifications->count())
        <form
            method="POST"
            action="{{ route('notifications.read-all') }}"
        >
            @csrf

            <button type="submit" class="btn btn-secondary">
                Mark All as Read
            </button>
        </form>
    @endif
</div>

<div class="card">
    @if($notifications->count())
        <div class="notification-list">
            @foreach($notifications as $notification)
                <div
                    class="notification-item
                    {{ $notification->read_at ? 'is-read' : 'is-unread' }}"
                >
                    <div class="notification-content">
                        <h3>
                            {{ $notification->data['title'] ?? 'Notification' }}
                        </h3>

                        <p>
                            {{ $notification->data['message'] ?? '' }}
                        </p>

                        <small>
                            {{ $notification->created_at->format('d M Y, H:i') }}
                        </small>
                    </div>

                    @if(!$notification->read_at)
                        <form
                            method="POST"
                            action="{{ route(
                                'notifications.read',
                                $notification
                            ) }}"
                        >
                            @csrf

                            <button
                                type="submit"
                                class="btn btn-sm btn-primary"
                            >
                                View
                            </button>
                        </form>
                    @elseif(!empty($notification->data['url']))
                        <a
                            href="{{ $notification->data['url'] }}"
                            class="btn btn-sm btn-secondary"
                        >
                            Open
                        </a>
                    @endif
                </div>
            @endforeach
        </div>

        <div class="pagination-wrapper">
            {{ $notifications->links() }}
        </div>
    @else
        <div class="empty-state">
            <h3>No notifications</h3>
            <p>
                Important recruitment activities will appear here.
            </p>
        </div>
    @endif
</div>

@endsection