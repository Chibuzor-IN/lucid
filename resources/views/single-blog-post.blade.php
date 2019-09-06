@extends('layouts.lucid')

@if(Auth::user() && Auth::user()->username == $user->username)
@section('title'){{ $post['title'] }} - {{ $user->username }} - Lucid @endsection
@else
@section('title'){{ $post['title'] }} - {{ $user->name }} ({{ '@'.$user->username }}) @endsection
@endif


@if($post['image'])
@section('img'){{ $post['image'] }} @endsection
@else
@section('img'){{ secure_asset('img/logo.svg') }} @endsection
@endif
@php
$postdes = strip_tags($post['body']);
@endphp
@section('desc'){{ \Illuminate\Support\Str::limit($postdes, 300) }} @endsection

@section('tags'){{ $post['tags'] }} @endsection

@section('url'){!! URL::current() !!} @endsection


@php
$location= 'singlePost';
@endphp


@section('sidebar')
@parent

@endsection
@section('content')
<!-- Beginning of Post Content -->
<style>
    .standard-color {
        background: #871e99;
        color: #fff;
        border: 1px solid #871e99;
    }

    .standard-color:hover {
        background: #871e99 !important;
        color: #fff;
        border: 1px solid #871e99 !important;
    }

    .text-danger {
        font-weight: 400px !important;
        font-size: 12px !important;

    }
</style>
<div class="post-content">
    <div class="post-content-body m-0">
        <p class="post-date">
            <a href="{{secure_url('/')}}/{{$user->username}}/home" class="text-secondary"> Home </a> /
            <a href="../home" class="text-secondary"> Blog </a> / <span class="text-muted">{{ $post['title'] }}</span></p>
        <cite class="post-body">
            Published on {{ $post['date'] }}
        </cite>
        <h3 class="post-title mb-1">
            {{ \Illuminate\Support\Str::title($post['title']) }}
        </h3>

        <div class="blog-content">
            {!! $post['body'] !!}
        </div>
    </div>
</div>
<div class="containter-fluid d-flex justify-content-between">
    <div class="d-flex">
        <div class="mt-2 mr-3">
            <a href="https://twitter.com/share?ref_src=twsrc%5Etfw" class="twitter-share-button" data-show-count="false"><i class="icon ion-logo-twitter h2 mx-2"></i></a>
            <script async src="https://platform.twitter.com/widgets.js" charset="utf-8"></script>
        </div>

        <div class="mt-2 mr-3">
            <iframe src="https://www.facebook.com/plugins/share_button.php?href={!! URL::current() !!}&layout=button&size=small&appId=173297093603387&width=59&height=20" width="59" height="20" style="border:none;overflow:hidden" scrolling="no" frameborder="0" allowTransparency="true" allow="encrypted-media"></iframe>
        </div>

        <div class="mt-1 mr-3">
            <script src="https://platform.linkedin.com/in.js" type="text/javascript">
                lang: en_US
            </script>
            <script type="IN/Share" data-url="https://www.linkedin.com"></script>
        </div>
    </div>
    <div>
            <span id="">
                <button type='button' title='like this Post' onclick='' class='btn'><i class='fas fa-thumbs-up text-secondary' style='font-size: 1.2em;'></i>
                    <sub id="">1</sub>
                </button></span>
            <span id="">
                <button type='button' title='like this Post' onclick='' class='btn'><i class='fas fa-heart text-secondary' style='font-size: 1.2em;'></i>
                    <sub id="">1</sub>
                </button></span>
        </div>
</div>
<hr style="padding-bottom:20px">
<div class="">
    <form method="post" action="" autocomplete="off" enctype="multipart/form-data" class="mb-3 commentForm">
        @csrf
        <div class="form-group">
            <input type="hidden" name="post_id" value="{{ $post['id']  }}">
            <input type="hidden" id='parents_id' name="parents_id" value="">
            <input type="hidden" id='user_id' name="user_id" value="">
            <input type="hidden" id='type' name="action" value="Commented">
            <textarea type="text" id="body" name="body" class="form-control h-25" placeholder="Write a comment"></textarea>
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
<hr style="padding-top:20px">
<div class="">
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
    <!-- convert to markdown script ends -->
    <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
    <h6 class="font-weight-bold">Comments: </h6>
    <div class="comments">

        <div class="" style="text-align: -webkit-center">
            <div class="spinner" style="    position: inherit;"></div>
        </div>
    </div>
</div>

<!-- End of Post Content -->
<script>
    const k = jQuery.noConflict();

    function reply(c_id, user_id, username) {
        k("#parents_id").attr("value", c_id);
        k("#user_id").attr("value", user_id);
        k("#type").attr("value", "Replied");
        k("#body").focus();
        k("#body").attr("placeholder", "Replying to @" + username);

    }
</script>
<script>
    const j = jQuery.noConflict();
    j(document).ready(function() {
        function getComment() {

            const route = "{{ secure_url('/'.$user->username.'/comments',['post_id'=>$post['id']])  }}"
            j.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': j('meta[name="csrf-token"]').attr('content')
                }
            })
            setTimeout(() => {
                j.ajax({
                    type: 'GET',
                    url: route,
                    contentType: false,
                    processData: false,
                    success: function(data) {
                        j('.comments').html(data);
                    }
                })

            }, 5000);
        }
        // initial call, or just call refresh directly
        // initial call, or just call refresh directly
        setTimeout(getComment(), 5000);
        const commentForm = document.querySelector('.commentForm');
        const commentBtn = document.querySelector('button[name="comment"]');
        if (commentBtn != null) {
            commentForm.onsubmit = commentBtn.addEventListener('click', function(e) {
                e.preventDefault();

                const formData = new FormData(commentForm);
                const saveComment = "{{ secure_url('/'.$user->username.'/save-comment')  }}";
                if (formData.get('body') == "") {
                    j('.text-danger').show();
                } else {
                    j('.text-danger').hide();
                    j.ajax({
                        type: "POST",
                        url: saveComment,
                        dataType: 'json',
                        data: formData,
                        contentType: false,
                        processData: false,
                        beforeSend: function() {
                            commentBtn.setAttribute('disabled', 'disabled');
                        },
                        success: function(response) {
                            // console.log(response);
                            commentBtn.removeAttribute('disabled');
                            commentForm.reset();
                            getComment();
                        }
                    })
                }

            })
        }


        j('#loginpopup').on('click', function() {
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

            j('.standard-color').on('click', function() {
                window.location = "/login";
            })
        })


    })
</script>

@endsection
