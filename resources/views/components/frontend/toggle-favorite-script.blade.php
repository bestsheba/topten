<form action="{{ route('user.toggle.wishlist') }}" method="POST" id="toggleFavorite">
    @csrf
    <input type="hidden" id="toggleFavoriteProductInput" name="product" value="" readonly>
    <input type="hidden" id="scrollPosition2" name="scroll_position" value="" readonly>
</form>
<script>
    function toggleFavorite(product) {
        // Set the value of the hidden product input field
        document.getElementById('toggleFavoriteProductInput').value = product;

        // Optionally, store the current scroll position in the hidden field
        document.getElementById('scrollPosition2').value = window.pageYOffset;

        // Submit the form
        document.getElementById('toggleFavorite').submit();
    }
</script>
