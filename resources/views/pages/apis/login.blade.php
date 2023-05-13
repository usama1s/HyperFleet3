

<div class="card">
    <form method="POST" action="{{ route('login') }}" id="customer-login" style="display:none" >
        <div class="heading">
            <h2 class="text-center">Customer Details</h2>
        </div>

        <div class="card-header">
            <h3 class="card-title">Login Information</h3>

          </div>
        @csrf

        <div class="card-body">
    <div class="col-md-12">
    <label for="email">Email:</label>
    <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email" autofocus>

    @error('email')
    <span class="invalid-feedback" role="alert">
    <strong>{{ $message }}</strong>
    </span>
    @enderror
    </div>
    <div class="col-md-12">
    <label for="password" >{{ __('Password') }}</label>

    <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" required autocomplete="current-password">

    @error('password')
    <span class="invalid-feedback" role="alert">
        <strong>{{ $message }}</strong>
    </span>
    @enderror           
    </div>

    <div class="form-group row">
        <div class="col-md-12">
        <div class="form-check">
            <input class="form-check-input" type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>

            <label class="form-check-label" for="remember">
                {{ __('Remember Me') }}
            </label>
        </div>
        </div>
    </div>

    <div class="form-group row mb-0">
    <div class="col-md-12">
    <button type="submit" class="btn btn-primary">
        {{ __('Login') }}
    </button>

    <a href="{{url('signup')}}">Signup</a> <br>
    @if (Route::has('password.request'))
        <a class="btn btn-link" href="{{ route('password.request') }}">
            {{ __('Forgot Your Password?') }}
        </a>
    @endif
    </div>
    </div>

        </div>{{-- Card Body end --}} 

    </form>
</div>