<button type="button" class="btn btn-primary" data-toggle="modal" data-target="#PaymentReceiveModal">
    Pay Dues
</button>

{{-- Paymeny Receive Modal --}}

<!-- Modal -->
<div class="modal fade" id="PaymentReceiveModal" tabindex="-1" role="dialog" aria-labelledby="PaymentReceiveModalLabel" aria-hidden="true">
<div class="modal-dialog" role="document">
  <div class="modal-content">
    <div class="modal-header">
      <h5 class="modal-title" id="PaymentReceiveModalLabel">Payment</h5>
      <button type="button" class="close" data-dismiss="modal" aria-label="Close">
        <span aria-hidden="true">&times;</span>
      </button>
    </div>
    <form action="{{route('payment.supplier.pay')}}" method="post" id="payment-form">
    <div class="modal-body">
        @csrf
      <label for="">Payment Amount</label>
      <input class="form-control" type="hidden" name="supplier_id" value="{{ $user->id }}">
      <input class="form-control" type="number" name="payment" placeholder="$00.00">
      <input id="nonce" name="payment_method_nonce" type="hidden" />
      <div class="row">
        <div class="col-md-12">
            <div class="bt-drop-in-wrapper">
                <div id="bt-dropin"></div>
            </div>
        </div>
      </div>

      <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
      <script src="https://js.braintreegateway.com/web/dropin/1.8.1/js/dropin.min.js"></script>

      <script>
         var form = document.querySelector('#payment-form');
        var client_token = "{{ $clientToken }}";
        braintree.dropin.create({
          authorization: client_token,
          selector: '#bt-dropin',
          paypal: {
            flow: 'vault'
          }
        }, function (createErr, instance) {
          if (createErr) {
            console.log('Create Error', createErr);
            return;
          }
          form.addEventListener('submit', function (event) {
            event.preventDefault();
            instance.requestPaymentMethod(function (err, payload) {
              if (err) {
                console.log('Request Payment Method Error', err);
                return;
              }
              // Add the nonce to the form and submit
              document.querySelector('#nonce').value = payload.nonce;
              form.submit();
            });
          });
        });
      </script>
    </div>
    <div class="modal-footer">
      <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
      <button type="submit" class="btn btn-primary">Receive Amount</button>
    </div>
    </form>
  </div>
</div>
</div>
