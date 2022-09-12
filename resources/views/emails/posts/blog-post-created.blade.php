@component('mail::message')
# Someone has created a new blog post

Be sure to proofread it.

@component('mail::button', ['url' => ''])
Button Text
@endcomponent

Thanks,<br>
{{ config('app.name') }}
@endcomponent
