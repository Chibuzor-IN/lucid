@extends('layouts.lucid')
@section('title')
  {{ $user->name }} - Lucid
@endsection
@php
$location= 'home';
@endphp
@section('sidebar')
@parent

@endsection
@section('content')
<!-- Editor -->




<!-- Beginning of Post Content -->

<!-- End of Post Content -->

{{-- @foreach ($posts as $feeds)
@if (empty($feeds['image']))

<div class="post-content">
  <a class="no-decoration" href="{{$feeds['link']}}">
    <div class="post-content-body">
      <p class="post-date">{{$feeds['date']}}</p>
      <h3 class="post-title">
        {{$feeds['title']}}
      </h3>
      <p class="post-body">
        {{$feeds['des']}}
      </p>
    </div>
  </a>
</div>
@else
<div class="post-content">
  <div class="post-image d-none d-lg-flex d-xl-flex d-md-flex">
    <img src="{{URL::to('/')}}/storage/{{$feeds['image']}}" class="img-fluid post-img" alt="Looking For Where To Spend Christmas in the comform of your home" />
  </div>
  <a class="no-decoration" href="{{$feeds['link']}}">
    <div class="post-content-body">
      <p class="post-date">{{$feeds['date']}}</p>
      <h3 class="post-title">
        {{$feeds['title']}}
      </h3>
      <p class="post-body">
        {{$feeds['des']}}
      </p>
    </div>
  </a>
</div>
@endif
@endforeach --}}


@foreach($userposts as $userpost)

@if($userpost['image'] !== '')

<div class="post-content">
  <div class="post-image d-none d-lg-flex d-xl-flex d-md-flex">
    <img src="{{$userpost['image']}}" class="img-fluid post-img" alt="Looking For Where To Spend Christmas in the comform of your home" />
  </div>
  <a class="no-decoration" href="/{{$user->username}}/post/{{$userpost['slug']}}">
    <div class="post-content-body">
      <p class="post-date">{{$userpost['date']}}</p>
      <h3 class="post-title">
        @php
         echo strip_tags($userpost['title'])
        @endphp
      </h3>
      <p class="post-body">
        @php
            echo  strip_tags($userpost['body'])
        @endphp
      </p>
    </div>
  </a>
</div>
@else

<div class="post-content">
  <a class="no-decoration" href="/{{$user->username}}/post/{{$userpost['slug']}}">
    <div class="post-content-body">
      <p class="post-date">{{$userpost['date']}}</p>
      <h3 class="post-title">
        @php
         echo strip_tags($userpost['title'])
        @endphp
      </h3>
      <p class="post-body">
        @php
        echo  strip_tags($userpost['body'])
        @endphp
      </p>
    </div>
  </a>
</div>

@endif

@endforeach


@php
 if(count($userposts) > 30) {
@endphp
  <div class="text-center">
    <button class="btn btn-primary pagination">
      Previous articles <i class="pl-2 icon ion-ios-arrow-forward"></i>
    </button>
  </div>
@php
 }
@endphp

@endsection
