@extends('layouts.lucid')
@section('title')
@if(Auth::user() && Auth::user()->username == $user->username)
{{ $post['title'] }} - {{ $user->username }} - Lucid
@else
{{ $post['title'] }} - {{ $user->name }} (@ {{ $user->username }})
@endif
@php
$location= 'singlePost';
@endphp
@endsection
@section('sidebar')
@parent

@endsection
@section('content')
<!-- Beginning of Post Content -->
<style>
.standard-color{
  background: #871e99;
  color:#fff;
  border:1px solid #871e99;
}

.standard-color:hover{
  background: #871e99 !important;
  color:#fff;
  border:1px solid #871e99 !important;
}

.text-danger{
  font-weight:400px !important;
  font-size:12px !important;

}
</style>
<div class="post-content">
    <div class="post-content-body m-0">
        <p class="post-date">
            <a href="{{URL::to('/')}}/{{$user->username}}/home" class="text-secondary"> Home </a> /
            <a href="../home" class="text-secondary"> Blog </a> / <span class="text-muted">{{ $post['title'] }}</span></p>
        <cite class="post-body">
            Published on {{ $post['date'] }}
        </cite>
        <h3 class="post-title mb-1">
            {{ $post['title'] }}
        </h3>

        <div class="blog-content">
            {!! $post['body'] !!}
        </div>
    </div>
</div>
<div class="">
    <p>Write a comment</p>
    <form method="post" action="" autocomplete="off" enctype="multipart/form-data" class="mb-3 commentForm">
        @csrf
        <div class="form-group">
            <input type="hidden" name="post_id" value="{{ $post['id']  }}">
            <textarea type="text" name="body" class="form-control h-25" placeholder=""></textarea>
            <span class="text-danger" style="display:none;"> Fill out this field to make a comment </span>

        </div>
        <div class="text-right">
        @auth
            <button type="submit" name="comment" class="btn bg-alt text-white">Comment</button>
        @endauth
        @guest
         <button type="button" name="loginpopup" id="loginpopup" class="btn bg-alt text-white">Comment</button>
        @endguest
        </div>
    </form>
</div>
<div class="">
    <h6 class="font-weight-bold">Comments: </h6>
    <div class="comments"></div>
</div>

<!-- End of Post Content -->
<script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
<!-- convert to markdown script ends -->
<script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
<script>
const j = jQuery.noConflict();
 j(document).ready(function (){
    const route = "{{ route('comment',['username'=>$user->username,'post_id'=>$post['id']])  }}"
    j.ajaxSetup({
        headers:{
            'X-CSRF-TOKEN': j('meta[name="csrf-token"]').attr('content')
        }
     })
    setInterval(() => {
     j.ajax({
         type:'GET',
         url:route,
         contentType:false,
         processData:false,
         success:function (data){
             j('.comments').html(data);
         }
     })

    }, 2000);

    const commentForm = document.querySelector('.commentForm');
    const commentBtn  = document.querySelector('button[name="comment"]');
    if(commentBtn !=null){
        commentForm.onsubmit = commentBtn.addEventListener('click',function(e){
            e.preventDefault();

            const formData = new FormData(commentForm);
            const saveComment = "{{ route('save-comment',['username'=>$user->username])  }}";
            if(formData.get('body') == "") {
                j('.text-danger').show();
            }else{
                j('.text-danger').hide();
                j.ajax({
                    type:"POST",
                    url:saveComment,
                    dataType:'json',
                    data:formData,
                    contentType:false,
                    processData:false,
                    beforeSend:function(){
                        commentBtn.setAttribute('disabled','disabled');
                    },
                    success:function(response){
                       // console.log(response);
                        commentBtn.removeAttribute('disabled');
                        commentForm.reset();
                    }
                })
            }

        })
    }


    j('#loginpopup').on('click',function(){
        swal({
            text: 'Opps! Login to comment on this post',
            icon: "info",
            button: {
            text: "Login",
            value: true,
            visible: true,
            className: "standard-color",
            closeModal: true,
            },
        });

        j('.standard-color').on('click',function(){
            window.location = "/login";
        })
    })


 })
</script>

@endsection
