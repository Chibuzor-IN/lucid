<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <meta http-equiv="X-UA-Compatible" content="ie=edge" />
  <meta name="msvalidate.01" content="0D04E9AD3D60609FF1D1A5D5F3705A04" />
  <meta name="google-site-verification" content="zWGhooabnrUzUwys6O7e0GEndWQGqN26crtsYinFxc0" />
  <meta name="csrf-token" content="{{ csrf_token() }}">
  @auth
  <meta name="username" content="{{ Auth::user()->username }}">
  <meta name="user_id" content="{{ Auth::user()->id }}">
  @endauth
  <meta content="@yield('img')" property='og:image' />
  <meta content="@yield('desc')" name='og:description' />
  <meta content='@yield("tags")' name='keywords' />

  <!-- twiter card -->
  <meta content='summary_large_image' name='twitter:card' />
  <meta content="@yield('img')" name='twitter:image' />
  <meta content='{{ secure_url('/') }}' name='twitter:domain' />
  <meta content='@yield("title")' name='twitter:title' />
  <meta content='@yield("desc")' name='twitter:description' />
  <meta content='' name='twitter:site' />
  <meta content='' name='twitter:creator' />

  <!-- Metadata Facebook -->
  <meta content='Lucid' property='og:site_name' />
  <meta content='@yield("url")' property="og:url" />
  <meta content='@yield("title")' property='og:title' />
  <meta content='article' property='og:type' />
  <meta content='' property='fb:admins' />
  <meta content='517404062134205' property='fb:app_id' />



  <!-- Social Media Profile Meta Tag -->
  <meta content='Nigeria' name='geo.placename' />
  <meta content='{{ $user->name }}' name='Author' />
  <meta content='general' name='rating' />
  <meta content='id' name='geo.country' />
  <meta content='en_US' property='og:locale' />
  <meta content='en_GB' property='og:locale:alternate' />
  <meta content='id_ID' property='og:locale:alternate' />
  <title>@yield('title')</title>


  <link rel="short icon" type="image/png" sizes="16x16" href="{{ secure_asset('img/lucid-logo.svg') }}">
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous" />
  <link href="https://fonts.googleapis.com/css?family=Open+Sans:400,600,700,800&display=swap" rel="stylesheet" />
  <link href="https://unpkg.com/ionicons@4.5.10-0/dist/css/ionicons.min.css" rel="stylesheet" />
  <link href="{{ secure_asset('css/style.css') }}" rel="stylesheet">
  <link href="{{ secure_asset('css/main-style.css') }}" rel="stylesheet">
  <link href="{{ secure_asset('css/tabletcss.css') }}" rel="stylesheet">
  <link href="https://cdn.quilljs.com/1.3.4/quill.snow.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-tokenfield/0.12.0/css/bootstrap-tokenfield.min.css">

  <style>
    .preloader-wrapper {
      display: none;
    }

    .preloader-active .preloader-wrapper {
      display: block;
      width: 100vw;
      height: 100vh;
      background: #000;
      position: fixed;
      color: #871e99;
      opacity: 0.60;
      z-index: 1000;
      top: 0;
      left: 0;
    }

    .spinner {
      width: 10vw;
      height: 10vw;
      border-radius: 50%;
      border: 4px solid;
      border-top-color: var(--main-color);
      border-bottom-color: var(--main-color);
      border-left-color: transparent;
      border-right-color: transparent;
      animation: rotate .5s infinite linear;
      position: absolute;
      top: 30%;
      left: 42%;
      transform: translateX(50%);

    }

    @keyframes rotate {
      0% {
        transform: rotate(0deg);
      }

      100% {
        transform: rotate(360deg);
      }
    }
  </style>

  <script>
    window.fbAsyncInit = function() {
      FB.init({
        appId: '{your-app-id}',
        cookie: true,
        xfbml: true,
        version: '{api-version}'
      });

      FB.AppEvents.logPageView();

    };

    (function(d, s, id) {
      var js, fjs = d.getElementsByTagName(s)[0];
      if (d.getElementById(id)) {
        return;
      }
      js = d.createElement(s);
      js.id = id;
      js.src = "https://connect.facebook.net/en_US/sdk.js";
      fjs.parentNode.insertBefore(js, fjs);
    }(document, 'script', 'facebook-jssdk'));
  </script>
</head>

<body id="preloader">
  <div class="preloader-wrapper">
    <div class="spinner"></div>
  </div>
  <section id="main-content" class="container pt-0">
    <div class="row">

      @section('sidebar')
      <!-- Beginning of Sidebar -->
      <div class="col-10 col-sm-4 pb-0 mb-0 pt-2 d-none d-lg-block" id="sidebar">
        <a class="d-lg-none" id="sidebarDismiss"><i class="icon ion-md-close-circle" style="font-size: 1.8em"></i></a>
        <a href="/{{ $user->username}}" class="changeHref"><img id="user-avatar" src="{{\Illuminate\Support\Str::replaceFirst('_small_', '_large_',$user->image) }}" class="img-fluid mt-3" /></a>
        <a href="/{{ $user->username}}" class="no-decoration changeHref">
          <h3 id="user-name" class="pt-2">{{ $user->name}}</h3>
        </a>

        @if(Auth::user() && Auth::user()->username == $user->username && $user->short_bio =="")
        <p id="user-bio" class="pb-2" style="color:#a9a9a9;">
          You haven't set up a short bio about yourself, do that <a href="/{{ $user->username}}/settings" id="onSettingsPage">here</a>
        </p>
        @else
        <p id="user-bio" class="pb-2">
          {{ $user->short_bio }}
        </p>
        @endif


        <div class="divider"></div>

        <div class="sidebar-nav pt-2">
          <ul>
            @if(Auth::user() && Auth::user()->username == $user->username)
            <li class="w-100 text-center"><a class="@if($location ==  'timeline') active-nav @endif changeHref" href="/{{ $user->username}}">Timeline</a></li>
            <li class="w-100 text-center"><a class="@if($location ==  'post') active-nav @endif changeHref" href="/{{ $user->username}}/posts">Posts</a></li>
            @else
            <li class="w-100 text-center"><a class="@if($location ==  'post') active-nav @endif changeHref" href="/{{ $user->username}}">Posts</a></li>
            @endif
            <li class="w-100 text-center"><a class="@if($location ==  'thoughts') active-nav @endif changeHref" href="/{{ $user->username}}/thoughts">Thoughts</a></li>
            <!-- <li class="w-100 text-center"><a class="@if($location ==  'video') active-nav @endif changeHref" href="{{ secure_url('under-construction') }}">Videos</a></li> -->
            <li class="w-100 text-center"><a class="@if($location ==  'contact') active-nav @endif changeHref" href="/{{ $user->username}}/contact">Contact</a></li>
          </ul>
        </div>
        @if(Auth::user() && Auth::user()->username == $user->username)
        @else
        <!-- Follow Modal Trigger -->
        <div class="follow-me text-center pt-3">
          @if($fcheck == "yes")
          <button class="btn btn-primary" data-toggle="modal" data-target="#unfollowModal">UnFollow</button>
          @else
          <button class="btn btn-primary" data-toggle="modal" data-target="#followModal">Follow Me</button>
          @endif
        </div>
        @endif

        <div class="user-stats text-center mt-3 pb-0">
          <div class="d-inline-block">
            @if (empty($count))
            <a href="/{{$user->username}}/following" class="pr-2 changeHref d-block" style="line-height: 15px;">0 <small class="text-muted d-block">Following</small></a>
            @else
            <a href="/{{$user->username}}/following" class="pr-2 changeHref d-block" style="line-height: 15px;">{{$count}} <small class="text-muted d-block">Following</small></a>
            @endif
          </div>
          <div class="d-inline-block">
            @if (empty($fcount))
            <a href="/{{$user->username}}/followers" class="changeHref d-block" style="line-height: 15px;">0 <small class="text-muted">Followers</small></a>
            @else
            <a href="/{{$user->username}}/followers" class="changeHref d-block" style="line-height: 15px;">{{$fcount}} <small class="text-muted d-block">Followers</small></a>
            @endif
          </div>
          <div class="mt-3">
            <a href="https://lucid.blog"> <small class="text-muted d-flex justify-content-center"><img src="{{ secure_asset('img/logo.jpg') }}" alt="Lucid" class="img-fluid" style="filter: grayscale(100%); height: 20px;" />
                <p class="mb-0 ml-1">Powered by Lucid</p>
              </small></a>
          </div>
        </div>
      </div>
      <!-- End of Sidebar -->

      <!-- Unfollow Modal -->
      <div class="modal fade text-center" id="unfollowModal" tabindex="-1" role="dialog" aria-labelledby="followModalTitle" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
          <div class="modal-content">
            <div class="modal-body">

              <div>
                <img src="{{$user->image}}" width="100" height="100" style="border-radius:100%;" class="img-fluid" />
                <br>
                <br>
                <h4 class="text-main">Unfollow {{$user->name}}</h4>
                <p class="small"><em>Are you sure you want to Unfollow {{$user->name}} and miss out interesting post?<br /> Click the button below to unfollow</em></p>
                <form method="POST" action="{{secure_url('/')}}/{{$user->username}}/unfollow">
                  @csrf
                  <input type="hidden" name="rss" value="{{$user->name}}">
                  <button type="submit" class="btn btn-primary">UnFollow</button>
                </form>
              </div>
            </div>
          </div>
        </div>
      </div>
      <!-- End Unfollow Modal  -->

      <!-- Follow Modal -->
      <div class="modal fade text-center" id="followModal" tabindex="-1" role="dialog" aria-labelledby="followModalTitle" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
          <div class="modal-content">
            <div class="modal-body">
              <div>
                <img src="{{$user->image}}" width="100" height="100" style="border-radius:100%;" class="img-fluid" />
                <br>
                <br>
                <h4 class="text-main">Follow {{$user->name}}</h4>
                <p class="small"><em>Do you have or would love to have Lucid installed on your domain?<br /> Click the button below to follow me</em></p>
                <form method="POST" action="{{secure_url('/')}}/{{$user->username}}/addrss">
                  @csrf
                  <input type="hidden" name="rss" value="{{$user->username}}">
                  <button type="submit" class="btn btn-primary">Follow me on Lucid</button>
                </form>
              </div>
              <div class="mt-5">
                <span class="font-weight-bold mx-2">Follow Me On</span>
                <a href="#" class="social-icon m-1"><i class="icon ion-logo-rss text-dark p-1"></i></a>
                <a href="#" class="social-icon m-1"><i class="icon ion-logo-twitter text-dark p-1"></i></a>
              </div>
            </div>
          </div>
        </div>
      </div>
      <!-- End FOllow  Modal -->

      @show
      <div class="col-lg-8 pb-0">

          <!-- Beginning of Navbar -->
          <div class="container-fluid p-0 m-0 mb-5 d-flex justify-content-between justify-content-lg-end">
          <a class="d-lg-none pt-3 text-main" id="sidebarToggle"><i class="fas fa-bars" style="font-size: 1.5em"></i></a>
          @guest
          @else
          <div class="dropdown pt-3">
            @guest
            @else
            <a href="/{{ Auth::user()->username}}" class="mr-1 pr-4 text-main"><i class="fas fa-home" style="font-size: 1.5em;"></i></a>
            @endguest
            <a class="mr-5 pr-4 notification text-main" id="load" role="button" id="dropdownNotification" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><i class="fas fa-bell" style="font-size: 1.5em;"></i>
              <span class="badge badge-danger count"></span>
              <span class="sr-only">unread notifications</span></a>
            <div class="dropdown-menu dropdown-menu-right notification-menu" aria-labelledby="dropdownNotification">
              <h6 class="font-weight-bold mx-2">Notifications</h6>
              <div id="notif">

                <div class="spinner" style=" padding: 20px;  width: 2vw;
    height: 2vw;"></div>
              </div>
              <a href="{{ secure_url('under-construction') }}" class="font-weight-bold mx-2 mt-3">View all</a>
            </div>
          </div>
          @endguest
          <div class="dropdown pt-2" id="lucid-dropdown">
            <a class="nav-link dropdown-toggle pt-1 cursor-pointer" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
              <img src="{{ secure_asset('img/lucid-logo.svg') }}" alt="The Lucid Logo" class="img-fluid" width="40px" />
            </a>
            <div class="dropdown-menu dropdown-menu-right p-0" aria-labelledby="navbarDropdown">
              @guest
              <a class="dropdown-item" href="{{ secure_url('/login') }}">{{ __('Login') }}</a>
              @else
 <!--              <a class="dropdown-item changeHref border-bottom note" href="/{{ Auth::user()->username}}">Home</a> -->
              <a href="/{{ $user->username}}/settings" class="dropdown-item note changeHref border-bottom">Settings</a>
              <a class="dropdown-item note changeHref" href="/{{ $user->username}}/logout">
                {{ __('Logout') }}
              </a>

              @endguest
            </div>
          </div>
        </div>
        <!-- End of Navbar -->

        <!-- Beginning of Post Content -->
        @yield('content')
        <div class="overlay"></div>
      </div>

  </section>
  <script src="https://kit.fontawesome.com/6b3c05b3d8.js"></script>
  <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
  <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>

  <script>
    const anchor = window.location.hash;
    $(`a[href="${anchor}"]`).tab('show')
  </script>
  <script>
    const pageUrl = window.location.href
    if (pageUrl.includes('followers')) {
      $('#follow-tabs a[href="#followers"]').tab('show')
    } else(
      $('#follow-tabs a[href="#following"]').tab('show')
    )
    // $(`a[href="${anchor}"]`).tab('show')
  </script>
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
  <script>
    const a = jQuery.noConflict();

    function like(action, id) {

      url = "{{ secure_url($user->username.'/like')  }}";
      //  id=id+"&act="+action;
      a.ajax({
          url: url,
          type: "Get",
          data: {
            id: id,
            act: action
          },
          dataType: "json",
        })
        .then(
          function(data) {

            //  console.log(data);
            a('#like' + id).html(data.button);
            //  a('#count'+id).html(data.count);
          });

    }

    function love(action, id) {

      url = "{{ secure_url($user->username.'/love')  }}";
      //  id=id+"&act="+action;
      a.ajax({
          url: url,
          type: "Get",
          data: {
            id: id,
            act: action
          },
          dataType: "json",
        })
        .then(
          function(data) {

            //  console.log(data);
            a('#love' + id).html(data.button);
            //  a('#count'+id).html(data.count);
          });

    }

    function changeUrl(e) {
      history.pushState(null, null, `/${document.getElementById("username").value+'/'+e}`)
    }
  </script>
  <script>
    $(document).ready(function() {
      $('#sidebarDismiss,.overlay, [data-toggle="modal"]').on('click', function() {
        // hide sidebar
        $('#sidebar').removeClass('active-sidebar');
        // hide overlay
        $('.overlay').removeClass('active');
      });
      /*       $('[data-toggle="modal"]').on('click', function() {
              // hide sidebar
              $('#sidebar').addClass('d-none');
              // hide overlay
              $('.overlay').removeClass('active');
            }); */
      $('#sidebarToggle').on('click', function() {
        // open sidebar
        $('#sidebar').addClass('active-sidebar');
        // fade in the overlay
        $('.overlay').addClass('active');
        $('.collapse.in').toggleClass('in');
        $('a[aria-expanded=true]').attr('aria-expanded', 'false');
      });


    });
  </script>

  <script async src="https://www.googletagmanager.com/gtag/js?id=UA-28315089-7"></script>
  <script>
    window.dataLayer = window.dataLayer || [];

    function gtag() {
      dataLayer.push(arguments);
    }
    gtag('js', new Date());
    gtag('config', 'UA-28315089-7');
  </script>
  @guest
  @else
  <script>
    const s = jQuery.noConflict();
    s(document).ready(function() {
      const check = "{{ secure_url($user->username.'/notif')  }}"

      function load_unseen_notification(view = '') {
        s.ajaxSetup({
          headers: {
            'X-CSRF-TOKEN': s('meta[name="csrf-token"]').attr('content')
          }
        })
        s.ajax({
            url: check,
            method: "POST",
            data: {
              view: view
            },
            dataType: "json",
          })
          .then(
            function(data) {
              //  console.log(data);

              if (data.unseen_notification > 0) {
                s('.count').html(data.unseen_notification);
              }


            })
          .catch(function(err) {
            //console.log('Fetch Error :-S', err);
          });
      }
      const view_notif = "{{ secure_url($user->username.'/notif')  }}"

      s(document).on('click', '#load', function() {
        view = "";
        s.ajax({
            url: view_notif,
            method: "Get",
            data: {
              view: view
            },
            dataType: "json",
          })
          .then(
            function(data) {

              //    console.log(data);
              s('#notif').html(data.notification);
            });
      });

      //  setInterval(function(){
      load_unseen_notification();
      //}, 2000);

      s(document).on('click', '#notif', function() {
        s('.count').html('');
        load_unseen_notification('yes');
      });




    })
  </script>
  @endguest
</body>

</html>