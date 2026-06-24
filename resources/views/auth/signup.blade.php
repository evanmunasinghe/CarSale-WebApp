<x-guest-layout title="Signup Page" bodyClass="page-signup">
    <h1 class="auth-page-title">Signup</h1>

    <form action="{{ route('signup.submit') }}" method="post">
        @csrf
        @if ($errors->any())
            <div class="alert alert-danger mb-medium">
                {{ $errors->first() }}
            </div>
        @endif
        <div class="form-group">
            <input type="email" name="email" value="{{ old('email') }}" placeholder="Your Email" autocomplete="email" required />
        </div>
        <div class="form-group">
            <input type="password" name="password" placeholder="Your Password" autocomplete="new-password" required />
        </div>
        <div class="form-group">
            <input type="password" name="password_confirmation" placeholder="Repeat Password" autocomplete="new-password" required />
        </div>
        <hr />
        <div class="form-group">
            <input type="text" name="first_name" value="{{ old('first_name') }}" placeholder="First Name" autocomplete="given-name" required />
        </div>
        <div class="form-group">
            <input type="text" name="last_name" value="{{ old('last_name') }}" placeholder="Last Name" autocomplete="family-name" required />
        </div>
        <div class="form-group">
            <input type="text" name="phone" value="{{ old('phone') }}" placeholder="Phone" autocomplete="tel" />
        </div>
        <button class="btn btn-primary btn-login w-full">Register</button>
</form>
       
       
    
    <x-slot:footerLink>
        Already have an account? -
        <a href="{{ route('login') }}"> Click here to login </a>
    </x-slot:footerLink>
</x-guest-layout>
