require(['jquery'], function ($) {
    $(document).ready(function () {
        // Your existing script here
        function updateDiscountPercentage() {
            var regularPrice = parseFloat($('.old-price .price').text().replace(/[^\d.-]/g, ''));
            var finalPrice = parseFloat($('.normal-price .price').text().replace(/[^\d.-]/g, ''));

            if (!isNaN(regularPrice) && !isNaN(finalPrice) && regularPrice > finalPrice) {
                var discountPercentage = ((regularPrice - finalPrice) / regularPrice) * 100;
                $('.discount-percentage').text(discountPercentage.toFixed(2) + '% OFF');
            } else {
                $('.discount-percentage').empty();
            }
        }

        // Trigger the update on page load
        updateDiscountPercentage();

        // Event delegation for text swatch click
        $(document).on('click', '.swatch-option.text', function () {
            // Delayed update to ensure the swatch change is complete
            setTimeout(updateDiscountPercentage, 500);
        });

        // Event delegation for select dropdown change
        $(document).on('change', '.super-attribute-select', function () {
            // Delayed update to ensure the select dropdown change is complete
            setTimeout(updateDiscountPercentage, 500);
        });
    });
});
