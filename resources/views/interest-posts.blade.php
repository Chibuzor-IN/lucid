<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous" />
  <link href="https://unpkg.com/ionicons@4.5.9-1/dist/css/ionicons.min.css" rel="stylesheet" />
  <link href="https://fonts.googleapis.com/css?family=Roboto:400,500,700&display=swap" rel="stylesheet" />
  <link rel="short icon" type="image/png" sizes="16x16" href="{{ secure_asset('img/lucid-logo.svg') }}">
  <link href="{{ secure_asset('css/style.css') }}" rel="stylesheet">
  <link href="{{ secure_asset('css/main-style.css') }}" rel="stylesheet">
  <link href="{{ secure_asset('css/tabletcss.css') }}" rel="stylesheet">
  <title>Explore</title>

  <style>
  .grid {
    display: grid;
  }

  .drop {
    width: 120px;
    text-transform: capitalize;
    right: 5%;
    position: absolute;
  }
  </style>
</head>

<body>
  <!-- Beginning of Navbar -->
  <nav class="navbar navbar-expand-lg navbar-light pt-2">
    <div class="container">
      <ul class="navbar-nav ml-auto">
        <li class="nav-item dropdown">
          <a class="nav-link dropdown-toggle pt-0" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
            <img src="{{ secure_asset('img/lucid-logo.svg') }}" alt="The Lucid Logo" class="img-fluid" width="40px" />
          </a>
        </li>
      </ul>
    </div>
  </nav>
  <!-- End of Navbar -->
  <div>
    <h4 class="ml-4 mb-3 pl-1">{{ $interest  }}</h4>
    <!-- Begin content -->
    
    <div class="tab-contentd">
      <!-- Posts Page -->
      <div class="tab-pane" role="tabpanel" id="page">
        <div class="row mx-3">
          <div class="col-xs-12 col-md-8 grid">
            <div class="post mt-5" id="posts">
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
                    no records could be found
                </div>
            @endforelse
            </div>
          </div>
          <div class="col-xs-12 col-md-4 bg-light" style="height: 30vh;">
            <p class="font-weight-bold">Popular Topics</p>
          </div>
        </div>
      </div>
      <!-- End Posts page -->
    </div>
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>

</body>

</html>
