@extends('layouts.app')

@section('content')
<div class="container mt-4 ">
    <div class="row justify-content-center ">
        <div class="col-md-8">
            <div class="card text-secondary  shadow-lg border-0 rounded-3 fw-bold "> 
                <div class="card-header " style="background-color: #0067697b; color:#4f4f4f">{{ __('Login') }}</div>

                <div class="card-body" style="background-color: #00676939;" >
                    <form method="POST" action="{{ route('login') }}">
                        @csrf

                        <div class="mb-4 row ">
                            <label for="email" class="col-md-4 col-form-label text-md-right">{{ __('E-Mail') }}</label>

                            <div class="col-md-6">
                                <input id="email" type="email" class="form-control @error('email') is-invalid @enderror"
                                    name="email" value="{{ old('email') }}" required autocomplete="email" autofocus>
                                <span id="email-error" class="invalid-feedback" style="display: none;">Campo email obbligatorio</span>

                                @error('email')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>
                        </div>

                        <div class="mb-4 row">
                            <label for="password" class="col-md-4 col-form-label text-md-right">{{ __('Password') }}</label>

                            <div class="col-md-6">
                                <input id="password" type="password"
                                    class="form-control @error('password') is-invalid @enderror" name="password"
                                    required autocomplete="current-password">
                                <span id="password-error" class="invalid-feedback" style="display: none;">Campo password obbligatorio</span>

                                @error('password')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>
                        </div>

                        <div class="mb-4 row">
                            <div class="col-md-6 offset-md-4">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="remember" id="remember" {{
                                        old('remember') ? 'checked' : '' }}>

                                    <label class="form-check-label" for="remember">
                                        {{ __('Ricordami') }}
                                    </label>
                                </div>
                            </div>
                        </div>

                        <div class="mb-4 row mb-0">
                            <div class="col-md-8 offset-md-4">
                                <button type="submit" class="btn btn-register fw-bold" style="background-color: #0067696f; border-color: #0067696f;">
                                    {{ __('Accedi') }}
                                </button>

                                @if (Route::has('password.request'))
                                <a class="btn btn-link text-secondary text-decoration-none" href="{{ route('password.request') }}">
                                    {{ __('Hai dimenticato la tua Password?') }}
                                </a>
                                @endif
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        let form = document.querySelector('form');

        form.addEventListener('submit', function(event) {
            let email = document.getElementById('email').value;
            let password = document.getElementById('password').value;

            let emailError = document.getElementById('email-error');
            let passwordError = document.getElementById('password-error');

            let valid = true;

            // Validazione dell'email
            if (!email) {
                emailError.style.display = 'block';
                valid = false;
            } else {
                emailError.style.display = 'none';
            }

            // Validazione della password
            if (!password) {
                passwordError.style.display = 'block';
                valid = false;
            } else {
                passwordError.style.display = 'none';
            }

            if (!valid) {
                event.preventDefault(); // Impedisce l'invio del form se ci sono errori di validazione
            }
        });

        // Aggiungere questa parte per cancellare i messaggi di errore quando l'utente modifica il contenuto dei campi
        form.addEventListener('input', function(event) {
            let target = event.target;
            if (target.id === 'email') {
                document.getElementById('email-error').style.display = 'none';
            } else if (target.id === 'password') {
                document.getElementById('password-error').style.display = 'none';
            }
        });
    });
</script>


@endsection

