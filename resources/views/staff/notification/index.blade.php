@foreach ($notifications as $notification)
    <li class="{{ $notification->read_at ? '' : 'font-weight-bold' }}">
        <a href="{{ route('staff.notifications.read', $notification->id) }}">
            {{ $notification->data['message'] }}
            <small class="text-muted d-block">
                {{ $notification->created_at->diffForHumans() }}
            </small>
        </a>
    </li>
@endforeach
