{{-- DSV IT Notification --}}

<h3>DSV IT Notification</h3>

<p>
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

<hr>

<h4>{{ $news->title }}</h4>

<div>
    {!! $news->content !!}
</div>
<br>
------------------------------------------------------------
<br>
<p>
    <strong>Author:</strong> {{ $news->author->name ?? '' }}
</p>

<p><strong>Read the full post:</strong></p>

@php
    $postUrl = url('') . ($news->uri ?? '');
@endphp

<p>
    <a href="{{ $postUrl }}">{{ $postUrl }}</a>
</p>

@if(collect($news->dsv_attachments)->isNotEmpty())
    <hr>

    <h4>Attachments</h4>

    <p>This email contains attachment(s):</p>

    <ul>
        @foreach($news->dsv_attachments as $attach)
            @php
                $attachmentUrl = url('') . ($attach->url ?? '');
            @endphp

            <li>
                <p>
                    <strong>Title:</strong> {{ $attach->title }}<br>
                    <strong>Download:</strong> <a href="{{ $attachmentUrl }}">Download attachment</a>
                </p>
            </li>
        @endforeach
    </ul>
@endif

<p>
    <small>This is an automated email, please do not reply to this email.</small>
</p>
