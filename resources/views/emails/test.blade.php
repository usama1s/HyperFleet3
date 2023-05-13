@component('mail::message')
# Congratulation


#Booking ID: {{ $invoice->booking_id }}

Your booking has been confirmed. You can check your booking invoice

{{-- @component('mail::button', ['url' => $invoice_path])
Invoice
@endcomponent --}}

Thanks,<br>
{{ config('app.name') }}
@endcomponent
