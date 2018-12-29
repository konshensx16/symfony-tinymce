import tinymce from "tinymce";
import "tinymce/themes/modern/theme";

import "tinymce/plugins/link";
import "tinymce/plugins/image";

require("../../node_modules/tinymce/skins/lightgray/skin.min.css");
require("../../node_modules/tinymce/skins/lightgray/content.min.css");

let form = document.querySelector('#tinymce_form')

tinymce.init({
    selector: '#post_content',
    plugins: 'image',
    toolbar: 'image',
    automatic_uploads: true,
    images_upload_url: '/attachment/' + form.dataset.postId,
    file_picker_types: 'image',
    file_picker_callback: function (cb, value, meta) {
        var input = document.createElement('input');
        input.setAttribute('type', 'file');
        input.setAttribute('accept', 'image/*');

        // Note: In modern browsers input[type="file"] is functional without
        // even adding it to the DOM, but that might not be the case in some older
        // or quirky browsers like IE, so you might want to add it to the DOM
        // just in case, and visually hide it. And do not forget do remove it
        // once you do not need it anymore.

        input.onchange = function () {
            var file = this.files[0];

            var reader = new FileReader();
            reader.onload = function () {
                // Note: Now we need to register the blob in TinyMCEs image blob
                // registry. In the next release this part hopefully won't be
                // necessary, as we are looking to handle it internally.
                var id = 'blobid' + (new Date()).getTime();
                var blobCache = tinymce.activeEditor.editorUpload.blobCache;
                var base64 = reader.result.split(',')[1];
                var blobInfo = blobCache.create(id, file, base64);
                blobCache.add(blobInfo);

                // call the callback and populate the Title field with the file name
                cb(blobInfo.blobUri(), { title: file.name });
            };
            reader.readAsDataURL(file);
        };

        input.click();
    }
})