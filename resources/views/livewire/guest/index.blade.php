<div>
    @if (Route::has('login'))
        <div class="top-right links">
            @auth
                <a href="{{ url('/dashboard') }}">Dashboard</a>
            @else
                <a href="{{ route('login') }}">Login</a>

                {{--
                @if (Route::has('register'))
                      <a href="{{ route('register') }}">Register</a>
                  @endif
                  --}}
            @endauth
        </div>
    @endif

    <div class="content">
        <div class="title m-b-md">
            AmzTracker
        </div>
    </div>
</div>
