<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  @if($isLocal)
  <link rel="stylesheet" href="{{ asset('css/main-style.css') }}" />
  <link rel="short icon" type="image/png" sizes="16x16" href="{{ asset('img/lucid-logo.svg') }}">
  @else
  <link rel="stylesheet" href="{{ secure_asset('css/main-style.css') }}" />
  <link rel="short icon" type="image/png" sizes="16x16" href="{{ secure_asset('img/lucid-logo.svg') }}">
  @endif
  <title>Page under construction - Lucid</title>
  <style>
    body{
      text-align: center;
    }
    .container {
      margin-top: 6vh
    }
    img{
      width: 300px;
      margin-top: 0.7rem;
    }
    a{
      color: #ffffff;
      background: var(--secondary-color);
      padding: 1rem 0.7rem;
      display: inline-block;
      text-decoration: none;
    }
    p {
      font-size: 1.2rem;
    }
    @media screen and (max-width: 425px){
      img{
        width: 250px;
      }
    }
  </style>
</head>
<body>
    <main class="container">
      <h2>I see you’ve found an incomplete page </h2>
      <img src="@if($isLocal) {{ asset('img/under-construction.svg') }} @else {{ secure_asset('img/under-construction.svg') }} @endif" alt="illustration of construction workers">
      <p>The team is working round the clock to get all pages up, do come back some time.</p>
      <a href="javascript: history.go(-1)">Click this button to return</a>
    </main>
    
</body>
</html>
