@extends('layouts.master')

@section('extra-meta')
    <meta name="csrf-token" content="{{ csrf_token() }}">
@endsection

@section('extra-script')
    <script src="https://js.stripe.com/v3/"></script>
@endsection

@section('content')
    <div class="col-md-12">
        <h1>Page de paiement</h1>
        <div class="row">
            <div class="col-md-6">
                <form action="{{ route('checkout.store') }}" method="POST" id="payment-form" class="my-4">
                    @csrf
                    <div id="card-element">
                      <!-- Elements will create input elements here -->
                    </div>

                    <!-- We'll put the error messages in this element -->
                    <div id="card-errors" role="alert"></div>

                    <button class="btn btn-success mt-4" id="submit">Procéder au paiement ( {{ getPrice(Cart::total()) }} )</button>
                </form>
            </div>
        </div>
    </div>
@endsection

@section('extra-js')
    <script>
        var stripe = Stripe('pk_test_TYooMQauvdEDq54NiTphI7jx');
        var elements = stripe.elements();

        var style = {
            base: {
                color: "#32325d",
                fontFamily: '"Helvetica Neue", Helvetica, sans-serif',
                fontSmoothing: "antialiased",
                fontSize: "16px",
                "::placeholder": {
                    color: "#aab7c4"
                }
            },
            invalid: {
                color: "#fa755a",
                iconColor: "#fa755a"
            }
        };

        var card = elements.create("card", { style: style });
        card.mount("#card-element");

        card.on('change', ({error}) => {
            const displayError = document.getElementById('card-errors');
            if (error) {
                displayError.classList.add('alert', 'alert-warning')
                displayError.textContent = error.message;
            } else {
                displayError.classList.remove('alert', 'alert-warning')
                displayError.textContent = '';
            }
        });

        var form = document.getElementById('payment-form');
        var submitButton = document.getElementById('submit')

        form.addEventListener('submit', function(ev) {
            ev.preventDefault();
            submitButton.disabled = true;
            stripe.confirmCardPayment("{{ $clientSecret }}", {
                payment_method: {
                    card: card
                }
            }).then(function(result) {
                if (result.error) {
                // Show error to your customer (e.g., insufficient funds)
                submitButton.disabled = false;
                console.log(result.error.message);
                } else {
                    // The payment has been processed!
                    if (result.paymentIntent.status === 'succeeded') {
                        var paymentIntent = result.paymentIntent;
                        var token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
                        var url = form.action;
                        var redirect = '/merci';

                        fetch(
                            url,
                            {
                                headers: {
                                    "Content-Type": "application/json",
                                    "Accept": "application/json, text-plain, */*",
                                    "X-Requested-with": "XMLHttpRequest",
                                    "X-CSRF-TOKEN": token
                                },
                                method: 'post',
                                body: JSON.stringify({
                                    paymentIntent: paymentIntent
                                })
                            }).then((data) => {
                            console.log(data)
                            window.location.href = redirect;
                        }).catch((error) => {
                            console.log(error)
                        })
                    }
                }
            });
        });
    </script>
@endsection
