@forelse($comments as $comment)
<div class="post-content mt-3 mb-2 pb-0">
    <img src="{{ $comment->image }}" class="img-fluid" style="border-radius:50%;object-fit:cover;" alt="user" width="55" height="56"/>
    <div class="post-content-body">
    <p class="font-weight-bold m-0">{{ $comment->username  }} - <small class="text-muted">@php
           $created_at = $carbon->parse($comment->created_at);
           
           echo $created_at->format('M jS, Y h:i A');
          @endphp</small></p>
        <p class="m-0">
            {{$comment->comment}}
        </p>
    </div>
    
</div>
<button class="btn bg-alt text-white float-right mb-5">
        Reply
    </button>
@empty
<div class="post-content">This post has no comment yet.</div>
@endforelse