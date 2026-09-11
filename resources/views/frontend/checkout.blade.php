@extends('layout.app')
@section('content')
<h2>Checkout</h2>
@if($errors->any())<div class="alert alert-danger">{{ $errors->first() }}</div>@endif
<form method="POST" action="{{ route('checkout.place') }}">@csrf
<div class="form-group"><label>Name</label><input name="customer_name" value="{{ old('customer_name') }}" required></div>
<div class="form-group"><label>Email</label><input type="email" name="customer_email" value="{{ old('customer_email') }}"></div>
<div class="form-group"><label>Phone</label><input name="customer_phone" value="{{ old('customer_phone') }}" required></div>
<div class="form-group"><label>Shipping Address</label><textarea name="shipping_address" required>{{ old('shipping_address') }}</textarea></div>
<div class="form-group"><label>City</label><input name="city" value="{{ old('city') }}" required></div>
<div class="form-group"><label>State</label><input name="state" value="{{ old('state') }}" required></div>
<div class="form-group"><label>Postal Code</label><input name="postal_code" value="{{ old('postal_code') }}" required></div>
<div class="form-group"><label>Payment Method</label><select name="payment_method"><option value="cod">Cash on Delivery</option></select></div>
<button class="btn btn-primary">Place Order</button>
</form>
@endsection
