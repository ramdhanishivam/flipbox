jQuery(document).ready(function($){
    $('#upload-button-id').click(function(event){
        event.preventDefault();
        var image = wp.media({ //wp.media is used to handle and control the admin media modal. For instance, custom image selector/uploader controls and meta boxes. 
            title: 'Upload Image',
            multiple: false,
        }).open().on('select', function(event){
            // This will return the selected image from the Media Uploader, the result is an object
            var uploaded_image = image.state().get('selection').first();
            console.log(uploaded_image);
            // We convert uploaded_image to a JSON object to make accessing it easier
            var image_url = uploaded_image.toJSON().url;
            $('#img-front').removeClass('disp_image');
            $('#img-front').addClass('show');

            // lets assign the url value to the input field
            $('#image_url').val(image_url);
        });
    });
});
