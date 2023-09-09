
<x-main-layout title="checkout">
  @push('styles')
    <link rel="stylesheet" href="checkout.css" />
    <style>
       .payment {
            display: flex;
            justify-content: center;
            align-items: center;
            margin: 0;
            text-align: center;
        }
    </style>
  @endpush  
  <div class="container my-5">
        <!-- Display a payment form -->
    <div class="payment">
      <form id="payment-form" class="p-4" style="width:50%;background:#f8f8f8;border-radius:7px">
        <h1 class="mb-5 text-center">Payment Details</h1>

        <div id="link-authentication-element">
          <!--Stripe.js injects the Link Authentication Element-->
        </div>
        <div id="payment-element">
          <!--Stripe.js injects the Payment Element-->
        </div>
        <button class="btn btn-success mt-5" style="width:100%" id="submit">
          <div class="spinner hidden" id="spinner"></div>
          <span id="button-text">Pay now</span>
        </button>
        <div id="payment-message" class="hidden"></div>
      </form>  
    </div>
          
  </div>

@push('scripts')
    <script src="https://js.stripe.com/v3/"></script>
  <script>
      const stripe = Stripe("{{ config('services.stripe.publishable_key')}}");

      // The items the customer wants to buy
      const items = [{ id: "{{ $subscription->id }}" }];

      let elements;

      initialize();
      checkStatus();

      document
      .querySelector("#payment-form")
      .addEventListener("submit", handleSubmit);

      let emailAddress = '';
      // Fetches a payment intent and captures the client secret
      async function initialize() {
      const { clientSecret } = await fetch("{{ route('payments.store')}}", {
          method: "POST",
          headers: { "Content-Type": "application/json" },
          body: JSON.stringify({
              "_token":"{{ csrf_token() }}",
              "subscription_id": "{{ $subscription->id }}"
          }),
      }).then((r) => r.json());

      elements = stripe.elements({ clientSecret });

      const linkAuthenticationElement = elements.create("linkAuthentication");
      linkAuthenticationElement.mount("#link-authentication-element");

      const paymentElementOptions = {
          layout: "tabs",
      };

      const paymentElement = elements.create("payment", paymentElementOptions);
      paymentElement.mount("#payment-element");
      }

      async function handleSubmit(e) {
      e.preventDefault();
      setLoading(true);

      const { error } = await stripe.confirmPayment({
          elements,
          confirmParams: {
          // Make sure to change this to your payment completion page
          return_url: "{{ route('payments.success') }}",
          receipt_email: emailAddress,
          },
      });

      // This point will only be reached if there is an immediate error when
      // confirming the payment. Otherwise, your customer will be redirected to
      // your `return_url`. For some payment methods like iDEAL, your customer will
      // be redirected to an intermediate site first to authorize the payment, then
      // redirected to the `return_url`.
      if (error.type === "card_error" || error.type === "validation_error") {
          showMessage(error.message);
      } else {
          showMessage("An unexpected error occurred.");
      }

      setLoading(false);
      }

      // Fetches the payment intent status after payment submission
      async function checkStatus() {
      const clientSecret = new URLSearchParams(window.location.search).get(
          "payment_intent_client_secret"
      );

    if (!clientSecret) {
      return;
    }

    const { paymentIntent } = await stripe.retrievePaymentIntent(clientSecret);

    switch (paymentIntent.status) {
      case "succeeded":
        showMessage("Payment succeeded!");
        break;
      case "processing":
        showMessage("Your payment is processing.");
        break;
      case "requires_payment_method":
        showMessage("Your payment was not successful, please try again.");
        break;
      default:
        showMessage("Something went wrong.");
        break;
    }
  }

// ------- UI helpers -------

    function showMessage(messageText) {
    const messageContainer = document.querySelector("#payment-message");

    messageContainer.classList.remove("hidden");
    messageContainer.textContent = messageText;

    setTimeout(function () {
        messageContainer.classList.add("hidden");
        messageContainer.textContent = "";
    }, 4000);
    }

    // Show a spinner on payment submission
    function setLoading(isLoading) {
    if (isLoading) {
        // Disable the button and show a spinner
        document.querySelector("#submit").disabled = true;
        document.querySelector("#spinner").classList.remove("hidden");
        document.querySelector("#button-text").classList.add("hidden");
    } else {
        document.querySelector("#submit").disabled = false;
        document.querySelector("#spinner").classList.add("hidden");
        document.querySelector("#button-text").classList.remove("hidden");
    }
    }
</script>    
@endpush

</x-main-layout>