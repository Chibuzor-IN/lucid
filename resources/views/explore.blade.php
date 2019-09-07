<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous" />
  <link href="https://unpkg.com/ionicons@4.5.9-1/dist/css/ionicons.min.css" rel="stylesheet" />
  <link href="https://fonts.googleapis.com/css?family=Roboto:400,500,700&display=swap" rel="stylesheet" />
  @if($isLocal)
  <link rel="short icon" type="image/png" sizes="16x16" href="{{ asset('img/lucid-logo.svg') }}">
  <link href="{{ asset('css/style.css') }}" rel="stylesheet">
  <link href="{{ asset('css/main-style.css') }}" rel="stylesheet">
  <link href="{{ asset('css/tabletcss.css') }}" rel="stylesheet">
  @else
  <link rel="short icon" type="image/png" sizes="16x16" href="{{ secure_asset('img/lucid-logo.svg') }}">
  <link href="{{ secure_asset('css/style.css') }}" rel="stylesheet">
  <link href="{{ secure_asset('css/main-style.css') }}" rel="stylesheet">
  <link href="{{ secure_asset('css/tabletcss.css') }}" rel="stylesheet">
  @endif
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
  <div class="container">
    <!-- Beginning of Navbar -->
    <div class="pt-2 text-right">
      <small>Have an account? <a class="text-secondary font-weight-bold" href="/login">Sign in</a> or <a class="text-secondary font-weight-bold" href="/register">Sign Up</a></small>
    </div>
    <!-- End of Navbar -->
    <div>
      <h4 class="ml-4 mb-3 pl-1">Explore Lucid</h4>
      <!-- Begin content -->
      <!-- Posts Page -->
      <div class="row mx-3">
        <div class="col-xs-12 col-md-8" id="categories_view">
      </div>
        <div class="col-xs-12 col-md-4 bg-light h-100 mt-4 mb-3 mt-md-0 mb-md-0">
          <div class="form-group border-bottom mt-2 pb-3">
            <label for="country" class="font-weight-bold">
              <h5>Sort By :</h5>
            </label>
            <select id="country" class="form-control w-100">
              <option selected>Recent</option>
            </select>
          </div>
          <div class="form-group border-bottom mt-3 pb-2">
            <label for="categories" class="font-weight-bold">
              <h5>Categories</h5>
            </label>

            <div class="form-check">
              <input class="form-check-input" name="checkbox" type="checkbox" value="politics" id="politics">
              <label class="form-check-label" for="politics" style="text-transform:capitalize;">
                Politics
              </label>
            </div>

            <div class="form-check">
              <input class="form-check-input" name="checkbox" type="checkbox" value="sports" id="sports">
              <label class="form-check-label" for="sports">
                Sports
              </label>
            </div>
            <div class="form-check">
              <input class="form-check-input" name="checkbox" type="checkbox" value="health" id="health">
              <label class="form-check-label" for="health">
                Health
              </label>
            </div>
            <div class="form-check">
              <input class="form-check-input" name="checkbox" type="checkbox" value="technology" id="technology">
              <label class="form-check-label" for="technology">
                Technology
              </label>
            </div>
            <div class="form-check">
              <input class="form-check-input" name="checkbox" type="checkbox" value="music" id="music">
              <label class="form-check-label" for="music">
                Music
              </label>
            </div>
            <div class="form-check">
              <input class="form-check-input" name="checkbox" type="checkbox" value="lifestyle" id="lifestyle">
              <label class="form-check-label" for="lifestyle">
                Lifestyle
              </label>
            </div>
            <div class="form-check">
              <input class="form-check-input" name="checkbox" type="checkbox" value="fitness" id="fitnesss">
              <label class="form-check-label" for="fitness">
                Fitness
              </label>
            </div>

          </div>
          <div class="form-group my-3">
            <label for="hastags" class="font-weight-bold">
              <h5>hastags</h5>
            </label>
            <a href="" class="d-block text-dark">#wizkid</a>
            <a href="" class="d-block text-dark">#dmxchallenge</a>
            <a href="" class="d-block text-dark">#bbnaija</a>
            <a href="" class="d-block text-dark">#hotelsng</a>
            <a href="" class="d-block text-dark">#php</a>
            <a href="" class="d-block text-dark">#opay</a>
            <a href="" class="d-block text-dark">#gokada</a>
          </div>
        </div>
      </div>
      <!-- End Posts page -->

    </div>
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>


    <script>
      const j = jQuery.noConflict();

      function filter() {
        j.ajaxSetup({
          headers: {
            'X-CSRF-TOKEN': j('meta[name="csrf-token"]').attr('content')
          }
        });
        const selectedMethod = document.getElementById('sortMethod').value;
        j.ajax({
          type: "GET",
          url: "/filter/" + selectedMethod,
          success: function(data) {
            j("#posts").html(data);
          },

        });
      }
      j(document).ready(function() {
        j.ajaxSetup({
          headers: {
            'X-CSRF-TOKEN': j('meta[name="csrf-token"]').attr('content')
          }
        });
        j.ajax({
          type: "GET",
          url: "/filter/Recent",
          success: function(data) {
            j("#posts").html(data);
          },

        });
        const checkBox = document.getElementsByClassName('form-check-input');
        checkBox[0].setAttribute('checked','');
        checkBox[1].setAttribute('checked','');
        checkBox[3].setAttribute('checked','');

        let AllCheckedBoxes = document.querySelectorAll('.form-check-input:checked');
        let AllCheckedBoxesArray = Array.from(AllCheckedBoxes).map(el=>el.value);


        j.ajax({
          type: "GET",
          url: "/category/"+ AllCheckedBoxesArray,
          success: function(data) {
             j("#categories_view").html(data);
          },
        });

      })
    </script>
    <script>
     j(document).ready(function(){

      let checkboxes = document.querySelectorAll('input[name="checkbox"]');

      const checked = document.querySelectorAll('.form-check-input:checked');
      let checkedBoxesArray=Array.from(checked).map(el=>el.value);
      checkboxes.forEach(checkbox=>{
         checkbox.addEventListener('change',function(){
         let checkedboxes = document.querySelectorAll('.form-check-input:checked');

       if (this.checked) {
          checkedBoxesArray.push(this.value);
        }else if (this.checked == false){
           checkedBoxesArray  = Array.from(checkedboxes).map(el=>el.value);
        }

         console.log(checkedBoxesArray)
        if(checkedBoxesArray.length == 1) {
            checkedboxes[0].setAttribute('disabled','');
         }else if (checkedBoxesArray.length > 1){
            checkedboxes.forEach(disabledCheckBox=>{
              disabledCheckBox.removeAttribute('disabled');
            })
         }




         j.ajaxSetup({
          headers: {
            'X-CSRF-TOKEN': j('meta[name="csrf-token"]').attr('content')
          }
        });
        j.ajax({
          type: "GET",
          url: "/category/"+ checkedBoxesArray,
          success: function(data) {
             j("#categories_view").html(data);
          },
        });

      });
    })

     })


    </script>
</body>

</html>
