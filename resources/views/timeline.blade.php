@extends('layouts.lucid')
@section('title')
@if(Auth::user() && Auth::user()->username == $user->username)
Timeline - {{ $user->username }} - Lucid
@else
{{ $user->name }} (@ {{ $user->username }}) - Lucid
@endif
@endsection
@php
$location = 'timeline';
@endphp
@section('sidebar')
@parent
@endsection
@section('content')
<style>
  .btn.btn-primary.canel-post {
    background-color: transparent !important;
    border: 1px solid red;
    color: red;
    padding: 6px 5px;
  }

  .btn.btn-primary.publish-post,
  .btn.btn-primary.save-draft,
  .btn.btn-primary.add-tags {
    background-color: #280a66 !important;
    border: 1px solid #280a66;
    padding: 6px 5px;
    color: #fff;
  }

  .main-content {
    padding-top: 30px;
    padding-bottom: 30px;
  }

  .btn-info {
    background-color: #280a66 !important;
    border: 0 !important;
  }



  .form-check-label {
    padding-right: 10px;
  }

  .mb-editor-area {
    background: #f5f5f5;
    border-radius: 5px;
  }

  #new-post-title {
    outline: 0px !important;
    -webkit-appearance: none;
    box-shadow: none !important;
  }

  .mb-editor {
    background: #ffffff;
    border-radius: 5px;
  }


  .micro-blog-enclosure {
    background-color: #E0E0E0;
    border-radius: 10px;
  }

  .editor-btns {
    background: #280a66;
    border-radius: 5px;
  }

  /*Tag styles*/
  .tags {
    padding-right: 10px;
  }

  .btn-outline-primary:not(:disabled):not(.disabled).active,
  .btn-outline-primary:not(:disabled):not(.disabled):active,
  .show>.btn-outline-primary.dropdown-toggle {
    color: #fff !important;
    background-color: #280a66 !important;
    border-color: #280a66 !important;
  }

  .btn-outline-primary {
    color: #280a66 !important;
    border-color: #280a66 !important;
  }

  .btn-outline-primary:hover {
    color: #fff !important;
    background-color: #280a66 !important;
    border-color: #280a66 !important;
  }

  /*tag styples end here..*/
  .mb-textarea {
    /* border: none; */
    font-size: 18px;
    line-height: 22px;
    resize: none;
  }

  .mb-textarea::placeholder {
    font-weight: bold;

  }

  .mb-textarea:focus {
    outline: none !important;
    border: 1px solid red;
    box-shadow: none;
  }

  .mb-icon-link {
    color: #000000;
  }

  .mb-icon-link:hover {
    color: #000000;
  }

  .icon-audio,
  .icon-photo,
  .icon-video {
    cursor: pointer;
  }

  .icon-audio {
    color: #C61639;
  }

  .icon-photo {
    color: #280a66;
  }

  .icon-video {
    color: #6C63FF;
  }

  .btn-mb-post,
  .btn-mb-submit {
    background: #3B0E75;
    color: #ffffff;
    border-radius: 5px;
    border: none;

  }

  .btn-mb-post:hover,
  .btn-mb-submit:hover,
  .btn-mb-cancel {
    background: #ffffff;
    color: #3B0E75;
    border-radius: 5px;
    border: 1px solid #3B0E75;

  }

  .mb-content {
    color: var(--mb-text-color);
    font-size: 18px;
    line-height: 140%;
  }

  .mb-image {
    object-fit: none;
    border-radius: 50%;
    width: 60px;
    height: 60px;
  }

  .mb-title {
    color: #000000;
    line-height: 1.3em;
    font-size: 24px;
  }

  .mb-title:hover {
    color: #000000;
    text-decoration: underline;
  }

  .mb-post-time {
    color: #000000;
    font-size: 14px;
    font-weight: 500;
  }

  .mb-reply {
    color: var(--primary-color);
    font-size: 14px;
    font-weight: 500;
    cursor: pointer;
  }

  .mb-reply:hover {
    color: var(--primary-color);
    text-decoration: underline;
  }

  .mb-pagination a {
    color: var(--primary-color);
    font-size: 18px;
    font-weight: 500;
  }

  .mb-pagination a:hover {
    color: var(--primary-color);
    text-decoration: underline;
  }

  /* Media Query */
  @media screen and (max-width: 768px) {
    .mb-editor {
      flex-direction: column-reverse;
      padding: 1rem 0.5rem !important;
      align-items: flex-start !important;
    }

    .textarea-control {
      align-items: flex-start !important;
      margin-top: 1rem !important;
    }

    .mb-textarea,
    .mb-audio,
    .mb-photo,
    .mb-video {
      margin: 0 0 0 4px !important;
    }

    /* .mb-textarea {
      font-size: 14px;
      padding: 0 !important;
    } */

    .reply-form {
      width: 100% !important;
    }
  }
</style>
<div class="row">
  <div class="col-12 col-sm-1"></div>
  <div class="col-12 col-sm-10">

  </div>
  <div class="col-11 col-sm-1"></div>

  <!-- Feed section ends here -->
</div>

<!-- Begin content -->
<!-- Timeline Page -->
<div>
    <!-- <h4 class="ml-4 mb-3 pl-1">Explore Lucid</h4> -->
    <!-- Begin content -->
    <div class="page-tab ml-4 mb-3">
      <ul class="nav nav-tabs navbar-light" id="follow-tabs" role="tablist">
      <li class="nav-item">
          <a href="#timeline" class="nav-link tab-link active ml-1 pl-0" data-toggle="tab" role="tab" aria-controls="category" aria-selected="">
            <h6>Timeline</h6>
          </a>
      </li>
      <!-- <li class="nav-item">
          <a href="#technology" class="nav-link tab-link ml-1 pl-0" data-toggle="tab" role="tab" aria-controls="category" aria-selected="">
            <h6>Technology</h6>
          </a>
      </li>
      <li class="nav-item">
          <a href="more" class="nav-link tab-link ml-1 pl-0" data-toggle="tab" role="tab" aria-controls="category" aria-selected="">
            <h6>Add More</h6>
          </a>
      </li> -->
      </ul>
    </div>
    <div class="tab-content">
        <!-- timeline page -->
        <div class="tab-pane show active" role="tabpanel" id="timeline">
        <div class="row mt-5">
          <div class="col-md-12">
            <?php $last = count($posts);
            ?>
            @foreach ($posts as $feeds)
            <div class="post-content">
              <!--           @if (empty($feeds['site_image']))
                  <img src="{{ asset('img/logo.jpg') }}" class="img-fluid img-thumb" alt="user" />
                  @else
                  <img src="{{ $feeds['site_image']}}" class="img-fluid img-thumb" alt="user" />
                  @endif -->

              <img src="{{$feeds['site_image']}}" class="timeline-img" alt="{{$feeds['site']}}" />
              <div class="post-content-body mb-0">
                <span class="text-muted">{{$feeds['tags']}}</span>
                <a href="{{URL::to('/')}}/{{$feeds['link']}}" class="no-decoration">
                  <h5 class="font-weight-bold">{{$feeds['title']}}</h5>
                </a>
                <p class="mb-1">
                  {{$feeds['des']}}
                </p>
                <div class="row">
                  <span class="col-6 col-sm-6 col-md-8">
                    <small>
                    <a href="{{URL::to('/')}}/{{$feeds['username']}}" class="text-muted">{{$feeds['site']}}</a>
                    <span class="font-weight-bold">.</span>
                    <span class="text-muted">{{$feeds['date']}}</span>
                    </small>
                  </span>
             <!--     <span class="col-6 col-sm-6 col-md-4">
                    <a href="" class="mr-1"><i class="icon ion-md-thumbs-up text-warning" style="font-size: 1.2em;"></i> 5</a>
                    <a href="" class="mr-1"><i class="icon ion-md-heart text-danger" style="font-size: 1.2em;"></i> 5</a>
                    <a href="{{URL::to('/')}}/{{$feeds['link']}}"><i class="icon ion-md-text text-primary" style="font-size: 1.2em;"></i> 5</a>
                  </span>  -->
                </div>
              </div>
            </div>
            @endforeach
          </div>
        </div>
        </div>
         <!-- End timeline Page -->
      <!-- Category Page -->
      <!-- <div class="tab-pane show" role="tabpanel" id="category">
        <div class="row p-3 m-0">
          <div class="col-xs-6 col-md-4 mb-3">
            <img src="{{ asset('img/politics.png') }}" class="w-100" alt="politics" />
            <div class="border d-flex justify-content-between">
              <p class="font-weight-bold px-2 pt-2">Politics</p>
              <i class="icon ion-md-arrow-dropright px-3" style="font-size: 1.8em"></i>
            </div>
          </div>
          <div class="col-xs-6 col-md-4 mb-3">
            <img src="{{ asset('img/sports.png') }}" class="w-100" alt="sports" />
            <div class="border d-flex justify-content-between">
              <p class="font-weight-bold px-2 pt-2">Sports</p>
              <i class="icon ion-md-arrow-dropright px-3" style="font-size: 1.8em"></i>
            </div>
          </div>
          <div class="col-xs-6 col-md-4 mb-3">
            <img src="{{ asset('img/health.png') }}" class="w-100" alt="health" />
            <div class="border d-flex justify-content-between">
              <p class="font-weight-bold px-2 pt-2">Health</p>
              <i class="icon ion-md-arrow-dropright px-3" style="font-size: 1.8em"></i>
            </div>
          </div>
          <div class="col-xs-6 col-md-4 mb-3">
            <img src="{{ asset('img/technology.png') }}" class="w-100" alt="technology" />
            <div class="border d-flex justify-content-between">
              <p class="font-weight-bold px-2 pt-2">Technology</p>
              <i class="icon ion-md-arrow-dropright px-3" style="font-size: 1.8em"></i>
            </div>
          </div>
          <div class="col-xs-6 col-md-4 mb-3">
            <img src="{{ asset('img/music.png') }}" class="w-100" alt="music" />
            <div class="border d-flex justify-content-between">
              <p class="font-weight-bold px-2 pt-2">Music</p>
              <i class="icon ion-md-arrow-dropright px-3" style="font-size: 1.8em"></i>
            </div>
          </div>
          <div class="col-xs-6 col-md-4 mb-3">
            <img src="{{ asset('img/lifestyle.png') }}" class="w-100" alt="music" />
            <div class="border d-flex justify-content-between">
              <p class="font-weight-bold px-2 pt-2">Lifestyle</p>
              <i class="icon ion-md-arrow-dropright px-3" style="font-size: 1.8em"></i>
            </div>
          </div>
          <div class="col-xs-6 col-md-4 mb-3">
            <img src="{{ asset('img/movies.png') }}" class="w-100" alt="movies" />
            <div class="border d-flex justify-content-between">
              <p class="font-weight-bold px-2 pt-2">Movies</p>
              <i class="icon ion-md-arrow-dropright px-3" style="font-size: 1.8em"></i>
            </div>
          </div>
          <div class="col-xs-6 col-md-4 mb-3">
            <img src="{{ asset('img/fitness.png') }}" class="w-100" alt="fitness" />
            <div class="border d-flex justify-content-between">
              <p class="font-weight-bold px-2 pt-2">Fitness</p>
              <i class="icon ion-md-arrow-dropright px-3" style="font-size: 1.8em"></i>
            </div>
          </div>
        </div>
      </div> -->
      <!-- End Category Page -->

      <!-- Posts Page -->
      <!-- <div class="tab-pane" role="tabpanel" id="page">
        <div class="row mx-3">
          <div class="col-xs-12 grid">
          <select name="" id="" class="float-right bg-alt drop text-white px-2 py-1 border-0">
            <option class="text-dark bg-white cursor-pointer" value="all" selected>
              All
            </option>
            <option class="text-dark bg-white cursor-pointer" value="fitness">
              Fitness
            </option>
            <option class="text-dark bg-white cursor-pointer" value="technology">
              Technology
            </option>
            <option class="text-dark bg-white cursor-pointer" value="health">
              Health
            </option>
            <option class="text-dark bg-white cursor-pointer" value="politics">
              Politics
            </option>
            <option class="text-dark bg-white cursor-pointer" value="sports">
              Sports
            </option>
            <option class="text-dark bg-white cursor-pointer" value="movies">
              Movies
            </option>
            <option class="text-dark bg-white cursor-pointer" value="music">
              Music
            </option>
            <option class="text-dark bg-white cursor-pointer" value="lifestyle">
              Lifestyle
            </option>
          </select>
          <div class="post-content mt-5 ">
              <img src="{{ asset('img/mb-1.png') }}" class="img-fluid" alt="user" />
              <div class="post-content-body">
                <h5 class="font-weight-bold">Maybe You Don't Need Kubernetes</h5>
                <p class="">
                  Kubernetes is the 800-pound gorilla of container orchestration. It powers some of the biggest deployments worldwide, but it comes with a price tag
                </p>
                <p class="">Tyler Elliot -<small class="text-muted">March 28, 2019 </small></p>
              </div>
            </div>

            <div class="post-content">
              <img src="{{ asset('img/mb-2.png') }}" class="img-fluid" alt="user" />
              <div class="post-content-body">
                <h5 class="font-weight-bold">What Is Rust Doing Behind the Curtains? </h5>
                <p class="">
                  Rust allows for a lot of syntactic sugar, that makes it a pleasure to write. It is sometimes hard, however, to look behind the curtain and see what the compiler is really doing with our code.
                </p>
                <p class="">Jayne Lee -<small class="text-muted">March 26, 2019 </small></p>
              </div>
            </div>

            <div class="post-content">
              <img src="{{ asset('img/mb-3.png') }}" class="img-fluid" alt="user" />
              <div class="post-content-body">
                <h5 class="font-weight-bold">The Unreasonable Effectiveness of Excel Macros</h5>
                <p class="">
                  I never was a big fan of internships, partially because all the exciting companies were far away from my little village in Bavaria and partially because I was too shy to apply. Only once I applied for an internship in Ireland as part of a school program. Our teacher assigned the jobs and so my friend got one at Apple and I ended up at a medium-sized IT distributor — let's call them PcGo.
                </p>
                <p class="">Eric Elliot -<small class="text-muted">March 24, 2019 </small></p>
              </div>
            </div>

            <div class="post-content">
              <img src="{{ asset('img/mb-1.png') }}" class="img-fluid" alt="user" />
              <div class="post-content-body">
                <h5 class="font-weight-bold">Switching from a German to a US Keyboard Layout - Is It Worth It? </h5>
                <p class="">
                  For the first three decades of my life, I've used a German keyboard layout. A few months ago, I switched to a US layout. This post summarizes my thoughts around the topic. I was looking for a similar article before jumping the gun, but I couldn't find one — so I'll try to fill this gap. Why switch?
                </p>
                <p class="">Tyler Elliot -<small class="text-muted">March 22, 2019 </small></p>
              </div>
            </div>

            <div class="post-content">
              <img src="{{ asset('img/mb-2.png') }}" class="img-fluid" alt="user" />
              <div class="post-content-body">
                <h5 class="font-weight-bold">fastcat - A Faster `cat` Implementation Using Splice</h5>
                <p class="">
                  Lots of people asked me to write another piece about the internals of well-known Unix commands. Well, actually, nobody asked me, but it makes for a good intro. I'm sure you’ve read the previous parts about `yes` and `ls` — they are epic.
                </p>
                <p class="">Jaynee Lee -<small class="text-muted">March 20, 2019 </small></p>
              </div>
            </div>
          </div>
          < <div class="col-xs-12 col-md-4 bg-light" style="height: 30vh;">
            <p class="font-weight-bold">Popular Topics</p>
          </div> -->
        <!-- </div>
      </div> -->
      <!-- End Posts page -->
    </div>
<!-- End Timeline Page -->

</html>

@endsection
