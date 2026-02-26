Intranet Notification - Important Message
@if($news->email_dsv)
    for DSV Staff:
@elseif($news->email_teachers && $news->email_phd)
    for teachers and Phd students
@elseif($news->email_teachers)
    for teachers:
@elseif ($news->email_phd)
    for Phd Students:
@endif
<br><br>
<b>{{ $news->title }}</b>
<br>
{!! $news->content !!}
<br><br>
Author: {{$news->author->name ?? ''}}
<br><br>
You can read the entire post at:
<br>
<a href="{{ url('') }}{{ $news->uri }}">{{url('')}}{{$news->uri}}</a>
<br><br>
@if( collect($news->dsv_attachments)->isNotEmpty() )
    This email contains an attachment
    <br><br>

    @foreach($news->dsv_attachments as $attach)
        Title: {{$attach->title}}<br>
        Download at: <a href="{{url('')}}{{$attach->url}}">Download attachment</a>
        <br><br>
    @endforeach
@endif
---
<br>
This is an automated email, please do not reply to this email.

