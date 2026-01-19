<form action="{{ route('add.to.cart') }}" method="POST" id="addToCartForm">
    @csrf
    <input type="hidden" id="addToCartProductInput" name="product" value="" readonly>
    <input type="hidden" id="quantity" name="quantity" value="1" readonly>
    <input type="hidden" id="scrollPosition" name="scroll_position" value="" readonly>
    <input type="hidden" id="goCheckout" name="go_checkout" value="" readonly>
</form>
<form action="{{ route('set.delivery-charge') }}" method="POST" id="addDeliveryChargeForm">
    @csrf
    <input type="hidden" id="chargeInput" name="charge" value="" readonly>
</form>

<script>
    function addToCart(product, quantity = 1) {
        document.getElementById('addToCartProductInput').value = product;
        document.getElementById('quantity').value = quantity;
        document.getElementById('scrollPosition').value = window.pageYOffset;
        document.getElementById('addToCartForm').submit();
    }

    function setDeliveryCharge(charge) {
        document.getElementById('chargeInput').value = charge;
        document.getElementById('addDeliveryChargeForm').submit();
    }

    function buyNow(product, charge = 'shipping_cost_inside_dhaka') {
        document.getElementById('addToCartProductInput').value = product;
        document.getElementById('quantity').value = 1;
        document.getElementById('goCheckout').value = 1;
        document.getElementById('addToCartForm').submit();
    }

    window.addEventListener('load', function() {
        let sessionScrollPosition = "{{ session()->get('scroll_position') ?? 0 }}";
        if (sessionScrollPosition) {
            var scrollPosition = sessionScrollPosition;
            window.scrollTo(0, scrollPosition);
        }
    });
</script>
