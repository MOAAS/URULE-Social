


@if (isset($notification->userNotification))
    <div class="row border-bottom notification-result" data-notificationID={{$notification->notification_id}}>
        <a href="{{ $notification->userNotification->user->url }}" class="notification-body col-10 border-right py-3 profile-avatar">
            <img class="profile-picture-small" src="{{ $notification->userNotification->user->avatar }}"
                 alt="{{$notification->userNotification->user->avatar}}'s avatar">
            <p class="mt-2 mb-0 text-dark">{{ $notification->description }}</p>
        </a>

        <button type="button" class="erase-notification btn col-2">
            <i class="fas fa-eye fa-lg"></i>
        </button>
    </div>
@elseif (isset($notfication->postNotification))
    <div class="row border-bottom notification-result" data-notificationID={{$notification->notification_id}}>
        <a href="{{ $notification->postNotification->content->url }}" class="notification-body col-10 border-right py-3">
            <i class="fas fa-comment-alt fa-2x"></i>
            <p class="mt-2 mb-0 text-dark">{{ $notification->description }}</p>
        </a>

        <button type="button" class="erase-notification btn col-2">
            <i class="fas fa-eye fa-lg"></i>
        </button>
    </div>
@endif