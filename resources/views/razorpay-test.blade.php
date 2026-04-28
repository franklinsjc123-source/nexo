<!DOCTYPE html>
<html>
<head>
    <title>Razorpay Test Page</title>
</head>
<body style="text-align: center; margin-top: 100px; font-family: Arial, sans-serif;">
    <h2>Razorpay Live Key Test</h2>
    <p>Clicking the button below will open the Razorpay Live Checkout modal for a test payment of INR 100.</p>
    <p><b>Please Note: Real money will be deducted if you complete this payment!</b></p>

    <button id="rzp-button1" style="padding: 10px 20px; font-size: 16px; background-color: #3399cc; color: white; border: none; border-radius: 4px; cursor: pointer;">Pay Now (₹100)</button>

    <script src="https://checkout.razorpay.com/v1/checkout.js"></script>
    <script>
    var options = {
        "key": "{{ $key }}",
        "amount": "100",
        "currency": "INR",
        "name": "Nexcart Test",
        "description": "Live Environment Test Transaction",
        "order_id": "{{ $rzp_id }}",
        "callback_url": "{{ route('razorpay-test-callback') }}",
        "prefill": {
            "name": "Test User",
            "email": "franklin@alphasoftz.in,
            "contact": "7777777777"
        },
        "theme": {
            "color": "#3399cc"
        }
    };
    var rzp1 = new Razorpay(options);
    document.getElementById('rzp-button1').onclick = function(e){
        rzp1.open();
        e.preventDefault();
    }
    </script>
</body>
</html>
