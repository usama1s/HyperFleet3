<button type="button" class="btn btn-primary" data-toggle="modal" data-target="#PaymentReceiveModal">
     Receive Payment
</button>

{{-- Paymeny Receive Modal --}}

<!-- Modal -->
<div class="modal fade" id="PaymentReceiveModal" tabindex="-1" role="dialog" aria-labelledby="PaymentReceiveModalLabel" aria-hidden="true">
<div class="modal-dialog" role="document">
  <div class="modal-content">
    <div class="modal-header">
      <h5 class="modal-title" id="PaymentReceiveModalLabel">Payment Receive</h5>
      <button type="button" class="close" data-dismiss="modal" aria-label="Close">
        <span aria-hidden="true">&times;</span>
      </button>
    </div>
    <form action="{{route('payment.supplier.receive')}}" method="post">
    <div class="modal-body">
        @csrf
      <label for="">Payment Amount</label>
    <input class="form-control" type="hidden" name="supplier_id" value="{{ $user->id }}">
      <input class="form-control" type="number" name="payment" placeholder="$00.00">
    </div>
    <div class="modal-footer">
      <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
      <button type="submit" class="btn btn-primary">Receive Amount</button>
    </div>
    </form>
  </div>
</div>
</div>
