{{-- Keep the email layout simple and independent of CSS. --}}
@php
    $postUrl = url($news->uri ?? '');
    $attachments = collect($news->dsv_attachments);
    $authorName = $news->author->name ?? null;
@endphp
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>{{ $news->title }}</title>
</head>
<body>
    <p><strong>DSV IT notification</strong></p>

    <p>
        <strong>For:</strong>
        @if($news->email_dsv)
            All DSV staff
        @elseif($news->email_teachers && $news->email_phd)
            Teachers and PhD students
        @elseif($news->email_teachers)
            Teachers
        @elseif($news->email_phd)
            PhD students
        @else
            DSV staff
        @endif
    </p>

    <hr>

    <h2>{{ $news->title }}</h2>

    <div>
        {!! $news->content !!}
    </div>

    @if($authorName)
        <p><strong>Published by:</strong> {{ $authorName }}</p>
    @endif

    <p>
        <strong>Read the full announcement:</strong><br>
        <a href="{{ $postUrl }}">{{ $postUrl }}</a>
    </p>

    @if($attachments->isNotEmpty())
        <h3>Related files</h3>

        <p>Use the links below to download files related to this announcement.</p>

        <ul>
            @foreach($attachments as $attach)
                @php
                    $attachmentUrl = url($attach->url ?? '');
                @endphp
                <li>
                    {{ $attach->title ?: 'Download file' }}<br>
                    <a href="{{ $attachmentUrl }}">{{ $attachmentUrl }}</a>
                </li>
            @endforeach
        </ul>
    @endif

    <hr>

    <p>This is an automated notification from DSV IT. Please do not reply to this email.</p>
</body>
</html>
