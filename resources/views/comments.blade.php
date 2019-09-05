<script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
<!-- convert to markdown script ends -->
<script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
<ul style="padding: initial;">
@forelse($comments as $comment)

<div class="post-content mt-4 mb-2 pb-0">
    <img src="{{ $comment->image }}" class="" style="border-radius:50%;object-fit:cover;" alt="user" width="55" height="56"/>
    <div class="post-content-body">
    <p class="font-weight-bold m-0">
      <a href="{{secure_url('/').'/'.$comment->username }}"> {{ '@'.$comment->username  }}</a> - <small class="text-muted">@php
           $created_at = $carbon->parse($comment->created_at);

           echo $created_at->format('M jS, Y h:i A');
          @endphp</small></p>
        <div class="d-flex justify-content-between">
            <p class="m-0">
                {{$comment->comment}}
            </p>
            <button onclick="reply({{$comment->id}},{{$comment->sender_id}},'{{ $comment->username  }}')" class="btn font-weight-bold align-items-center text-secondary" style="font-size: 12px;">
            Reply
            </button>
        </div>
    </div>

</div>

@forelse($replies as $reply)

@if($reply->parent_comment_id == $comment->id)

<div class="ml-5 pl-4 d-flex align-items-center mb-1" id="reply">
    <img src="{{ $reply->image }}" class="img-fluid mr-2" style="border-radius:50%;object-fit:cover;" alt="user" width="35" height="35"/>
    <div class="ml-1">
    <p class="font-weight-bold m-0" style="font-size: 13px;">
      <a href="{{secure_url('/').'/'.$reply->username }}">  {{ '@'.$reply->username  }} </a>-
        <small class="text-muted">@php
        $created_at = $carbon->parse($comment->created_at);

        echo $created_at->format('M jS, Y h:i A');
        @endphp</small>
    </p>
    <p style="font-size: 13px;margin-bottom: 0;">
        {{ $reply->comment }}
    </p>
    </div>

</div>


@endif
@empty
@endforelse


@empty
<div class="post-content">This post has no comment yet.</div>
@endforelse
</ul>
