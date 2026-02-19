<script>
    function thousandseparator(num, digit) {
        if (digit == 1) {
            return num.toFixed(digit).replace(/(\d)(?=(\d{3})+(?!\d))/g, '$1,')
        } else if (digit == 2) {
            return num.toFixed(digit).replace(/(\d)(?=(\d{3})+(?!\d))/g, '$1,')
        } else if (digit == 3) {
            return num.toFixed(digit).replace(/(\d)(?=(\d{3})+(?!\d))/g, '$1,')
        } else if (digit == 4) {
            return num.toFixed(digit).replace(/(\d)(?=(\d{3})+(?!\d))/g, '$1,')
        } else {
            return num.toFixed(2).replace(/(\d)(?=(\d{3})+(?!\d))/g, '$1,')
        }
    }

    function numbersconvertingtoint(value, digit) {
        if (digit == 1) {
            return Math.round(((value) + Number.EPSILON) * 10) / 10;
        } else if (digit == 2) {
            return Math.round(((value) + Number.EPSILON) * 100) / 100;
        } else if (digit == 3) {
            return Math.round(((value) + Number.EPSILON) * 1000) / 1000;
        } else if (digit == 4) {
            return Math.round(((value) + Number.EPSILON) * 10000) / 10000;
        } else {
            return Math.round(((value) + Number.EPSILON) * 100) / 100;
        }
    }
</script>
<script>
    // Add click event listener to the preview image
    $('.preview-image').click(function() {
        var imageUrl = $(this).attr('src');
        if (imageUrl) {
            // Create a new image element
            var fullScreenImage = $('<img>').attr('src', imageUrl).css({
                'max-width': '100%',
                'max-height': '100%',
                'position': 'fixed',
                'top': '0',
                'left': '0',
                'bottom': '0',
                'right': '0',
                'margin': 'auto',
                'z-index': '9999'
            });

            // Create a container for the full screen image
            var fullScreenContainer = $('<div>').css({
                'background': 'rgba(0, 0, 0, 0.8)',
                'position': 'fixed',
                'top': '0',
                'left': '0',
                'bottom': '0',
                'right': '0',
                'z-index': '9998',
                'cursor': 'pointer'
            });

            // Create a close button
            var closeButton = $('<span>').css({
                'position': 'absolute',
                'top': '10px',
                'right': '10px',
                'color': '#fff',
                'font-size': '24px',
                'cursor': 'pointer'
            }).html('&times;').click(function() {
                fullScreenContainer.remove(); // Remove full screen container on click
            });

            // Append the close button to the full screen container
            fullScreenContainer.append(closeButton);

            // Append the full screen image to the container
            fullScreenContainer.append(fullScreenImage);

            // Append the full screen container to the body
            $('body').append(fullScreenContainer);
        }
    });
</script>
