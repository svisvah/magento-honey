require(['jquery'], function ($) {
    $(document).ready(function () {
        console.log("Hello");
  
        var descriptions = []; // Array to store descriptions
  
        $("body").on("click", ".saveButton", function() {
            var descriptionInput = $(this).siblings('.description-input');
            var descriptionValue = descriptionInput.val();
  
            descriptions.push(descriptionValue);
            console.log('Descriptions:', descriptions);
  
            $.ajax({
                url: 'sampleimageuploader/image/save.php', // Update with the correct path
                method: 'POST',
                data: { descriptions: JSON.stringify(descriptions) },
                success: function(response) {
                    console.log('Descriptions saved successfully.');
                    console.log("Response is ",response);
                },
                error: function(error) {
                    console.error('Error saving descriptions:', error);
                }
            });
        });
    });
  });
  