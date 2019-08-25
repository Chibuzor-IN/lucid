@forelse($posts as $post)
<div class="post-content">
    <img src="{{ secure_asset('img/mb-2.png') }}" class="img-fluid" alt="user" />
    <div class="post-content-body">
    <h5 class="font-weight-bold">{{ $post['title']  }}</h5>
    <p class="">
     {!! $post['body'] !!}
    </p>
    <p class="">{{ $post['username'] }} -<small class="text-muted">{{ $post['date'] }}</small></p>
    </div>
</div>
@empty
<div class="post-content">
    no record could be found
</div>
@endforelse
