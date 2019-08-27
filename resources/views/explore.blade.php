<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous" />
  <link href="https://unpkg.com/ionicons@4.5.9-1/dist/css/ionicons.min.css" rel="stylesheet" />
  <link href="https://fonts.googleapis.com/css?family=Roboto:400,500,700&display=swap" rel="stylesheet" />
  <link rel="short icon" type="image/png" sizes="16x16" href="{{ secure_asset('img/luci-logo.png') }}">
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
            <img src="{{ secure_asset('img/lucid-logo.png') }}" alt="The Lucid Logo" class="img-fluid" width="40px" />
          </a>
        </li>
      </ul>
    </div>
  </nav>
  <!-- End of Navbar -->
  <div>
    <h4 class="ml-4 mb-3 pl-1">Explore Lucid</h4>
    <!-- Begin content -->
    <div class="page-tab ml-4 mb-3">
      <ul class="nav nav-tabs navbar-light" id="follow-tabs" role="tablist">
        <li class="nav-item">
          <a href="#category" class="nav-link tab-link active ml-1 pl-0" data-toggle="tab" role="tab" aria-controls="category" aria-selected="false">
            <h6>Category</h6>
          </a>
        </li>
        <li class="nav-item">
          <a href="#page" class="nav-link tab-link ml-1 pl-0" data-toggle="tab" role="tab" aria-controls="posts" aria-selected="false">
            <h6>Posts</h6>
          </a>
        </li>
      </ul>
    </div>
    <div class="tab-content">
      <!-- Category Page -->
      <div class="tab-pane show active" role="tabpanel" id="category">
        <div class="row p-3 m-0">
          <div class="col-xs-6 col-md-4 mb-3">
            <img src="{{ secure_asset('img/politics.png') }}" class="w-100" alt="politics" />
            <div class="border d-flex justify-content-between">
              <p class="font-weight-bold px-2 pt-2">Politics</p>
              <i class="icon ion-md-arrow-dropright px-3" style="font-size: 1.8em"></i>
            </div>
          </div>
          <div class="col-xs-6 col-md-4 mb-3">
            <img src="{{ secure_asset('img/sports.png') }}" class="w-100" alt="sports" />
            <div class="border d-flex justify-content-between">
              <p class="font-weight-bold px-2 pt-2">Sports</p>
              <i class="icon ion-md-arrow-dropright px-3" style="font-size: 1.8em"></i>
            </div>
          </div>
          <div class="col-xs-6 col-md-4 mb-3">
            <img src="{{ secure_asset('img/health.png') }}" class="w-100" alt="health" />
            <div class="border d-flex justify-content-between">
              <p class="font-weight-bold px-2 pt-2">Health</p>
              <i class="icon ion-md-arrow-dropright px-3" style="font-size: 1.8em"></i>
            </div>
          </div>
          <div class="col-xs-6 col-md-4 mb-3">
            <img src="{{ secure_asset('img/technology.png') }}" class="w-100" alt="technology" />
            <div class="border d-flex justify-content-between">
              <p class="font-weight-bold px-2 pt-2">Technology</p>
              <i class="icon ion-md-arrow-dropright px-3" style="font-size: 1.8em"></i>
            </div>
          </div>
          <div class="col-xs-6 col-md-4 mb-3">
            <img src="{{ secure_asset('img/music.png') }}" class="w-100" alt="music" />
            <div class="border d-flex justify-content-between">
              <p class="font-weight-bold px-2 pt-2">Music</p>
              <i class="icon ion-md-arrow-dropright px-3" style="font-size: 1.8em"></i>
            </div>
          </div>
          <div class="col-xs-6 col-md-4 mb-3">
            <img src="{{ secure_asset('img/lifestyle.png') }}" class="w-100" alt="music" />
            <div class="border d-flex justify-content-between">
              <p class="font-weight-bold px-2 pt-2">Lifestyle</p>
              <i class="icon ion-md-arrow-dropright px-3" style="font-size: 1.8em"></i>
            </div>
          </div>
          <div class="col-xs-6 col-md-4 mb-3">
            <img src="{{ secure_asset('img/movies.png') }}" class="w-100" alt="movies" />
            <div class="border d-flex justify-content-between">
              <p class="font-weight-bold px-2 pt-2">Movies</p>
              <i class="icon ion-md-arrow-dropright px-3" style="font-size: 1.8em"></i>
            </div>
          </div>
          <div class="col-xs-6 col-md-4 mb-3">
            <img src="{{ secure_asset('img/fitness.png') }}" class="w-100" alt="fitness" />
            <div class="border d-flex justify-content-between">
              <p class="font-weight-bold px-2 pt-2">Fitness</p>
              <i class="icon ion-md-arrow-dropright px-3" style="font-size: 1.8em"></i>
            </div>
          </div>
        </div>
      </div>
      <!-- End Category Page -->

      <!-- Posts Page -->
      <div class="tab-pane" role="tabpanel" id="page">
        <div class="row mx-3">
          <div class="col-xs-12 col-md-8 grid">
          <select onchange="filter()" name="" id="sortMethod" class="float-right form-control drop">
            <option value="Recent">
              recent
            </option>
            <option value="Popular">
              popular
            </option>
          </select>
            <div class="post mt-5" id="posts"></div>
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

   {{-- <script>
    const j = jQuery.noConflict();
     j(document).ready(function (){
        const check = "{{ secure_url('notif',['username'=>$user->username])  }}"
        j.ajaxSetup({
            headers:{
                'X-CSRF-TOKEN': j('meta[name="csrf-token"]').attr('content')
            }
         })

    function load_unseen_notification(view = '')
    {
    j.ajax({
      url:check,
      method:"POST",
      data:{view:view},
      dataType:"json",
      })
    .then (
      function(data) {
      //  console.log(data);

       if(data.unseen_notification > 0)
       {
        j('.count').html(data.unseen_notification);
       }


     })
    .catch(function(err) {
        //console.log('Fetch Error :-S', err);
        });
      }
      const view_notif = "{{ secure_url('getNotif',['username'=>$user->username])  }}"

      view = "";
      j.ajax({
        url:view_notif,
        method:"Get",
        data:{view:view},
        dataType:"json",
        })
      .then (
        function(data) {
      //    console.log(data);
      j(document).on('click', '#load', function(){
        j('#notif').html(data.notification);
      });

         })

      setInterval(function(){
    load_unseen_notification();
  }, 2000);

    j(document).on('click', '#notif', function(){
     j('.count').html('');
     load_unseen_notification('yes');
      });



  })

    </script> --}}
    <script>
       const j = jQuery.noConflict();
       function filter(){
        j.ajaxSetup({
          headers: {
              'X-CSRF-TOKEN': j('meta[name="csrf-token"]').attr('content')
          }
      });
      const selectedMethod = document.getElementById('sortMethod').value;
      j.ajax({
            type: "GET",
            url : "/filter/"+selectedMethod,
            success : function (data) {
              j("#posts").html(data);
            },
            
        });
       }
      j(document).ready(function(){
        j.ajaxSetup({
          headers: {
              'X-CSRF-TOKEN': j('meta[name="csrf-token"]').attr('content')
          }
      });
      j.ajax({
            type: "GET",
            url : "/filter/Recent",
            success : function (data) {
              j("#posts").html(data);
            },
            
        });
    })
    </script>
</body>

</html>
