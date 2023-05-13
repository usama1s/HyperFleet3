@php
use App\Models\Setting;
@endphp

  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <meta name="user-id" content="{{ Auth::check() ? Auth::user()->id: '' }}">
  <meta name="base-root-path" content="{{ config('app.url') }}">

<link rel="icon" href="{{ config('app.favicon') }}" type="image/gif" sizes="16x16">

  <title>HyperFleet Manager | Dashboard</title>
  <!-- Tell the browser to be responsive to screen width -->
  <meta name="viewport" content="width=device-width, initial-scale=1">
