<ul style="padding-left: 80px;" >
  @forelse($replies as $reply)

  <div class="post-content mt-3 mb-2 pb-0">
<img src="{{ $reply->image }}" class="img-fluid" style="border-radius:50%;object-fit:cover;" alt="user" width="55" height="56"/>
<div class="post-content-body">
<p class="font-weight-bold m-0">{{ '@'.$reply->username  }} - <small class="text-muted">@php
       $created_at = $carbon->parse($reply->created_at);

       echo $created_at->format('M jS, Y h:i A');
      @endphp</small></p>
    <p class="m-0">
        {{$reply->comment}}
    </p>
</div>
</div>
@empty
@endforelse
</ul>
