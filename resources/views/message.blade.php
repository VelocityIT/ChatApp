@foreach ($messages as $message)

@if ($message->from == Auth::id())
<li class="clearfix">
    <div class="message-data text-right">
    <span class="message-data-time">{{ date('d M y, h:i a', strtotime($message->created_at)) }}</span>
    </div>
    <div class="message other-message float-right">{{ $message->text }}</div>
</li>

@else
<li class="clearfix">
    <div class="message-data">
    <span class="message-data-time">{{ date('d M y, h:i a', strtotime($message->created_at)) }}</span>
    </div>
    <div class="message my-message">{{ $message->text }}</div>
</li>
@endif

@endforeach
