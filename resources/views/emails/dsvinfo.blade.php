{{-- DSV IT Notification - Important Message --}}

<h2 style="margin:0 0 12px 0; font-size:18px; line-height:1.3;">
    DSV IT Notification – Important Message
</h2>

<p style="margin:0 0 16px 0;">
    <strong>Audience:</strong>
    @if($news->email_dsv)
        DSV staff
    @elseif($news->email_teachers && $news->email_phd)
        Teachers and PhD students
    @elseif($news->email_teachers)
        Teachers
    @elseif($news->email_phd)
        PhD students
    @else
        —
    @endif
</p>

<hr style="border:none; border-top:1px solid #e5e7eb; margin:16px 0;">

<h3 style="margin:0 0 6px 0; font-size:16px; line-height:1.3;">
    {{ $news->title }}
</h3>

<div style="margin:0 0 16px 0;">
    {!! $news->content !!}
</div>

<p style="margin:0 0 16px 0;">
    <strong>Author:</strong> {{ $news->author->name ?? '' }}
</p>

<p style="margin:0 0 8px 0;">
    <strong>Read the full post:</strong>
</p>

@php
    $postUrl = url('') . ($news->uri ?? '');
@endphp

<p style="margin:0 0 16px 0;">
    <a href="{{ $postUrl }}">{{ $postUrl }}</a>
</p>

@if(collect($news->dsv_attachments)->isNotEmpty())
    <hr style="border:none; border-top:1px solid #e5e7eb; margin:16px 0;">

    <h4 style="margin:0 0 8px 0; font-size:14px; line-height:1.3;">
        Attachments
    </h4>

    <p style="margin:0 0 12px 0;">
        This email contains attachment(s):
    </p>

    @foreach($news->dsv_attachments as $attach)
        @php
            $attachmentUrl = url('') . ($attach->url ?? '');
        @endphp

        <p style="margin:0 0 12px 0;">
            <strong>Title:</strong> {{ $attach->title }}<br>
            <strong>Download:</strong> <a href="{{ $attachmentUrl }}">Download attachment</a>
        </p>
    @endforeach
@endif

<hr style="border:none; border-top:1px solid #e5e7eb; margin:16px 0;">

<p style="margin:0; color:#6b7280; font-size:12px; line-height:1.4;">
    This is an automated email, please do not reply to this email.
</p>
