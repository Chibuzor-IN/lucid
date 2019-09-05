@forelse($posts as $post)
<div class="post-content">
    <img src="{{ $post['user_img'] }}" class="img-fluid" style="border-radius:50%;object-fit:cover;" alt="user" width="55" height="56"/>
    <div class="post-content-body">
    <h5 class="font-weight-bold"><a class="text-dark" style="text-decoration:none;" href="{{ route('post',['username'=>$post['username'],'postTitle'=>$post['slug']]) }}">{{ $post['title']  }}</a></h5>
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
