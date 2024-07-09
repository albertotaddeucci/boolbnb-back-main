@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Pagamento sponsorizzazione <span class="fw-bold fst-italic"> {{$title}}</span> </h1>
    <form id="payment-form" action="{{ route('admin.payment.process') }}" method="POST">
        @csrf
        <input type="hidden" name="apartment_id" value="{{ $apartment_id }}">
        <input type="hidden" name="sponsorship_id" value="{{ $sponsorship_id }}">
        <div class="form-group ">
            <label for="price" class="fw-bold">Prezzo da pagare:</label>
            <span id="price" class="fw-bold fst-italic">{{ $price }} €</span>
        </div>
        <div class="form-group ">

            <label for="time" class="fw-bold">Durata:</label>
            <span id="time" class="fw-bold fst-italic">{{ $h_duration }}h</span>

        </div>
        

        <div id="dropin-container"></div>
        <button type="submit" class="btn btn-primary">Acquista</button>
    </form>
</div>
@endsection

@section('scripts')
<script>
    var form = document.getElementById('payment-form');
    var client_token = "{{ $clientToken }}";

    braintree.dropin.create({
        authorization: client_token,
        container: '#dropin-container'
    }, function (createErr, instance) {
        if (createErr) {
            console.error(createErr);
            return;
        }

        form.addEventListener('submit', function (event) {
            event.preventDefault();

            instance.requestPaymentMethod(function (err, payload) {
                if (err) {
                    console.error(err);
                    return;
                }

                var nonceInput = document.createElement('input');
                nonceInput.name = 'payment_method_nonce';
                nonceInput.type = 'hidden';
                nonceInput.value = payload.nonce;
                form.appendChild(nonceInput);

                form.submit();
            });
        });
    });
</script>
@endsection

