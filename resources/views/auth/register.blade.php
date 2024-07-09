@extends('layouts.app')

@section('content')
<div class="container mt-4">
    <div class="row justify-content-center ">
        <div class="col-md-8">
            <div class="card shadow-lg  text-secondary fw-bold">
                <div class="card-header  "  style="background-color: #0067697b; color:#4f4f4f;">{{ __('Registrati') }}</div>

                <div class="card-body" style="background-color: #00676939;">
                    <form method="POST" action="{{ route('register') }}">
                        @csrf

                        <div class="mb-4 row ">
                            <label for="name" class="col-md-4 col-form-label text-md-right">{{ __('Nome') }}</label>

                            <div class="col-md-6">
                                <input id="name" type="text" class="form-control @error('name') is-invalid @enderror"
                                    name="name" value="{{ old('name') }}" autocomplete="name" autofocus>

                                @error('name')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>
                        </div>

                        {{-- surname --}}
                        <div class="mb-4 row">
                            <label for="surname" class="col-md-4 col-form-label text-md-right">{{ __('Cognome')
                                }}</label>

                            <div class="col-md-6">
                                <input id="surname" type="surname"
                                    class="form-control @error('surname') is-invalid @enderror" name="surname"
                                    value="{{ old('surname') }}" autocomplete="surname">

                                @error('surname')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>
                        </div>

                        {{-- e-mail --}}
                        <div class="mb-4 row">
                            <label for="email" class="col-md-4 col-form-label text-md-right">{{ __('E-Mail') }}<span
                                    class="obg-field">*</span></label>

                            <div class="col-md-6">
                                <input id="email" type="email" class="form-control @error('email') is-invalid @enderror"
                                    name="email" value="{{ old('email') }}" required autocomplete="email">
                                <span id="email-error" class="invalid-feedback" style="display: none;">Campo email
                                    obbligatorio</span>

                                @error('email')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>
                        </div>

                        {{-- password --}}
                        <div class="mb-4 row">
                            <label for="password" class="col-md-4 col-form-label text-md-right">{{ __('Password')
                                }}<span class="obg-field">*</span></label>

                            <div class="col-md-6">
                                <input id="password" type="password"
                                    class="form-control @error('password') is-invalid @enderror" name="password"
                                    required autocomplete="new-password">
                                <span id="password-error" class="invalid-feedback" style="display: none;">Campo password
                                    obbligatorio</span>

                                @error('password')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>
                        </div>

                        {{-- conferma password --}}
                        <div class="mb-4 row">
                            <label for="password-confirm" class="col-md-4 col-form-label text-md-right">{{ __('Conferma
                                Password') }}<span class="obg-field">*</span></label>

                            <div class="col-md-6">
                                <input id="password-confirm" type="password" class="form-control"
                                    name="password_confirmation" autocomplete="new-password">
                                <div id="password-confirm-error" class="invalid-feedback" style="display: none;">
                                    Le password non corrispondono.
                                </div>
                            </div>
                        </div>

                        {{-- birth-date --}}
                        <div class="mb-4 row">
                            <label for="birth_date" class="col-md-4 col-form-label text-md-right">{{ __('Data di
                                Nascita') }}</label>

                            <div class="col-md-6">
                                <input id="birth_date" type="date"
                                    class="form-control @error('birth_date') is-invalid @enderror" name="birth_date"
                                    value="{{ old('birth_date') }}" autocomplete="birth_date">
                                <span id="ageError" class="text-danger" style="display: none;">Devi avere almeno 18 anni
                                    per registrarti.</span>

                                @error('birth_date')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>
                        </div>

                        <div class="mb-4 row mb-0">
                            <div class="col-md-6 offset-md-4">
                                <button type="submit" class="btn btn-register fw-bold">
                                    {{ __('Registrati!!') }}
                                </button>
                            </div>
                        </div>
                    </form>
                    <div>
                        <small class="obg-field">
                            (*) Campo obbligatorio
                        </small>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener("DOMContentLoaded", function() {
        let form = document.querySelector("form");

        form.addEventListener("submit", function(event) {
            let email = document.getElementById("email").value;
            let password = document.getElementById("password").value;
            let confirmPassword = document.getElementById("password-confirm").value;

            let emailError = document.getElementById("email-error");
            let passwordError = document.getElementById("password-error");
            let passwordConfirmError = document.getElementById("password-confirm-error");

            let valid = true;

            if (!email) {
                emailError.style.display = "block";
                valid = false;
            } else {
                emailError.style.display = "none";
            }

            if (!password) {
                passwordError.style.display = "block";
                valid = false;
            } else {
                passwordError.style.display = "none";
            }

            if (password !== confirmPassword) {
                passwordConfirmError.style.display = "block";
                valid = false;
            } else {
                passwordConfirmError.style.display = "none";
            }

            if (!valid) {
                event.preventDefault(); // Impedisce l'invio del form se ci sono errori di validazione
            }
        });

        // Validazione dell'età
        document.getElementById('birth_date').addEventListener('change', function() {
            let date = new Date(this.value);
            let today = new Date();
            let age = today.getFullYear() - date.getFullYear();
            let monthDiff = today.getMonth() - date.getMonth();

            if (monthDiff < 0 || (monthDiff === 0 && today.getDate() < date.getDate())) {
                age--;
            }

            if (age < 18) {
                document.getElementById('ageError').style.display = 'block';
            } else {
                document.getElementById('ageError').style.display = 'none';
            }
        });

        // Aggiungere questa parte per cancellare i messaggi di errore quando l'utente modifica il contenuto dei campi
        form.addEventListener('input', function(event) {
            let target = event.target;
            if (target.id === 'email') {
                document.getElementById('email-error').style.display = 'none';
            } else if (target.id === 'password') {
                document.getElementById('password-error').style.display = 'none';
            } else if (target.id === 'password-confirm') {
                document.getElementById('password-confirm-error').style.display = 'none';
            }
        });
    });
</script>

<style>
    .obg-field {
        color: red;
    }
</style>
@endsection