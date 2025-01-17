<x-guest-layout>
    <div class="rts-sign-up-section">
        <div class="section-inner">
            <div class="logo-area">
                <a href="index.html"><img src="assets/images/logo/logo-4.svg" alt=""></a>
            </div>
            <form method="POST" action="{{ route('login') }}">
                @csrf
                <h2 class="form-title">Sign In</h2>
                <div class="form-inner">
                    <div class="single-wrapper @error('email') is-invalid @enderror">
                        <input type="email" placeholder="Your email" name="email" value="{{ old('email') }}">
                        @error('email')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>
                    <div class="single-wrapper @error('password') is-invalid @enderror">
                        <input type="password" placeholder="Password" name="password">
                        @error('password')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>
                    <div class="check">
                        <div class="check-box-area">
                            <input type="checkbox" id="remember" name="remember"
                                {{ old('remember') ? 'checked' : '' }} />
                            <label for="remember">Remember me</label>
                        </div>
                        <a href="{{ route('password.request') }}" class="forgot-password">Forgot password?</a>
                    </div>
                    <div class="form-btn">
                        <button type="submit" class="primary__btn">Log In</button>
                    </div>
                </div>
                <p class="sign-in-option">Have no account yet? <a href="{{ route('register') }}">Sign Up</a></p>
            </form>
        </div>
        <div class="copyright-area">
            <p>Copyright © {{ date('Y') }} {{ config('app.name') }}. All Rights Reserved.</p>
        </div>
    </div>
</x-guest-layout>
