<x-guest-layout title="Login" bodyClass="page-login">
    <h1 class="auth-page-title">Login</h1>

    <form action="{{ route('login.submit') }}" method="post">
        @csrf
        @if (session('success'))
            <div class="alert alert-success mb-medium">
                {{ session('success') }}
            </div>
        @endif
        @if ($errors->any())
            <div class="alert alert-danger mb-medium">
                {{ $errors->first() }}
            </div>
        @endif
        <div class="form-group">
            <input type="email" name="email" value="{{ old('email') }}" placeholder="Your Email" autocomplete="email" required />
        </div>
        <div class="form-group">
            <input type="password" name="password" placeholder="Your Password" autocomplete="current-password" required />
        </div>
        <div class="text-right mb-medium">
            <a href="/password-reset.html" class="auth-page-password-reset">Reset Password</a>
        </div>

        <button class="btn btn-primary btn-login w-full">Login</button>
  </form>

   <x-slot:footerLink>
         Don't have an account? -
        <a href="{{ route('signup') }}"> Click here to create one</a>
    </x-slot:footerLink>
    
</x-guest-layout>
