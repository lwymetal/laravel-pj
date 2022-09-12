@component('mail::message')
# New comment on watched post

Hi {{ $user->name }},

Someone else has commented on a post you're watching.

@component('mail::button', ['url' => "{{ route('posts.show', ['post' => $comment->commentable->id]) }}"])
View post
@endcomponent

@component('mail::button', ['url' => "{{ route('users.show', ['user' => $comment->user->id]) }}"])
Profile for {{ $comment->user->name }}
@endcomponent

@component('mail::panel')
"{{ $comment->content }}"
@endcomponent

Thanks,<br>
{{ config('app.name') }}
@endcomponent
