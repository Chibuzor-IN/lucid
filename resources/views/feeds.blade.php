
    <!-- timeline page -->
    <div class="tab-pane show" role="tabpanel" id="timeline">
    <div class="row mt-5">
      <div class="col-md-12">
        <?php $last = count($posts);
        ?>
        @foreach($posts as $feeds)
        <div class="post-content">
          <!--           @if (empty($feeds['site_image']))
              <img src="{{ secure_asset('img/logo.jpg') }}" class="img-fluid img-thumb" alt="user" />
              @else
              <img src="{{ $feeds['site_image']}}" class="img-fluid img-thumb" alt="user" />
              @endif -->

          <img src="{{$feeds['site_image']}}" class="timeline-img" alt="{{$feeds['site']}}" />
          <div class="post-content-body mb-0">
            <span class="text-muted">{{$feeds['tags']}}</span>
            <a href="@if($isLocal) {{url('/')}}/{{$feeds['username'].$feeds['link']}} @else {{secure_url('/')}}/{{$feeds['username'].$feeds['link']}} @endif" class="no-decoration">
              <h5 class="font-weight-bold on-hover">{{\Illuminate\Support\Str::title($feeds['title'])}}</h5>
            </a>
            <p class="mb-1">
              {{$feeds['des']}}
            </p>
            <div class="row">
              <span class="col-12 col-sm-6 col-md-8">
                <small>
                <a href="@if($isLocal) {{url('/')}}/{{$feeds['username']}} @else {{secure_url('/')}}/{{$feeds['username']}}@endif" class="text-muted">{{$feeds['site']}}</a>
                <span class="font-weight-bold">.</span>
                <span class="text-muted">{{$feeds['date']}}</span>
                </small>
              </span>
              <span class="col-12 col-sm-6 col-md-4">
                @php
                $lcount = \Lucid\Notification::where(['post_id' => $feeds['id'],'action' => "Like"])->count();
                $likes = \Lucid\Notification::where(['post_id' => $feeds['id'], 'sender_id' => Auth::user()->id,'action' => "Like"])->first();
              //  dd($likes);
                @endphp
                @if(!empty($likes))
                <span id="like{{$feeds['id']}}">
                <button type='button' title='unlike this Post' onclick='like(0,{{$feeds["id"]}})' class='btn'><i class='fas fa-thumbs-up text-secondary' style='font-size: 1.2em;'></i>
                <sub id="lcount{{$feeds['id']}}">{{ $lcount }}</sub>
                </button></span>
                @else
                <span id="like{{$feeds['id']}}">
                  <button type="button" title="like this Post" onclick='like(1,{{ $feeds["id"] }})' class="btn">
                    <i class='fas fa-thumbs-up' style='font-size: 1.2em;'></i>
                    <sub id="lcount{{$feeds['id']}}">{{ $lcount }}</sub>
                  </button>
                </span>
                @endif

                @php
                $count = \Lucid\Notification::where(['post_id' => $feeds['id'],'action' => "Love"])->count();
                $love = \Lucid\Notification::where(['post_id' => $feeds['id'], 'sender_id' => Auth::user()->id,'action' => "Love"])->first();

                @endphp
                @if(!empty($love))
                <span id="love{{$feeds['id']}}">
                <button type='button' title='unlove this Post' onclick='love(0,{{$feeds["id"]}})' class='btn'>
                  <i class='fas fa-heart text-secondary' style="font-size: 1.2em;"></i>
                <sub id="count{{$feeds['id']}}">{{ $count }}</sub>
                </button></span>
                @else
                <span id="love{{$feeds['id']}}">
                  <button type="button" title="love this Post" onclick='love(1,{{ $feeds["id"] }})' class="btn">
                    <i class='fas fa-heart'style="font-size: 1.2em;"></i>
                    <sub id="count{{$feeds['id']}}">{{ $count }}</sub>
                  </button>
                </span>
                @endif
                @php
                $ccount = \Lucid\Notification::where(['post_id' => $feeds['id'],'action' => "Commented"])->count();
                @endphp
                <a href="@if($isLocal) {{url('/')}}/{{$feeds['username'].$feeds['link']}} @else {{secure_url('/')}}/{{$feeds['username'].$feeds['link']}}@endif#comment">
                  <button type="button"  class="btn">
                  <i class="fas fa-comments text-secondary" style="font-size: 1.2em;"></i>
                  <sub id="count{{$feeds['id']}}">{{ $ccount }}</sub>
                </button>
                </a>
              </span>
            </div>
          </div>
        </div>

        @endforeach
      </div>
    </div>
    </div>
     <!-- End timeline Page -->
