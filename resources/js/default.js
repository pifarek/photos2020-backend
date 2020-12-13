import $ from 'jquery';
window.$ = window.jQuery = $;

import 'jquery-ui/ui/widgets/datepicker.js';
import 'jquery-ui/ui/widgets/autocomplete.js';
import 'blueimp-file-upload/js/jquery.iframe-transport.js';
import 'blueimp-file-upload/js/jquery.fileupload.js';

// Tags
(function($){
    // Add Tag
    $('section#page-tags-index form').submit(e => {
        const url = $(e.currentTarget).attr('action');
        const name = $('section#page-tags-index input[name="name"]');
        const token = $('section#page-tags-index input[name="_token"]');

        name.removeClass('is-invalid');

        if(name.val()) {
            $.ajax({
                url: url,
                type: 'post',
                data: {name: name.val(), _token: token.val()},
                success: response => {
                    if(response.status === 'ok') {
                        window.location.reload();
                    }
                },
                error: response => {
                    Swal.fire({
                        title: 'Error',
                        text: "We couldn't add a new Tag",
                        icon: 'warning'
                    });
                }
            })
        } else {
            name.addClass('is-invalid');
        }

        e.preventDefault();
    });

    // Remove Tag
    $('section#page-tags-index').on('click', '[data-action="tag-remove"]', (e) => {
        const tagId = $(e.currentTarget).attr('data-id');

        Swal.fire({
            title: 'Are you sure?',
            text: "Are you sure you want to remove selected tag?",
            icon: 'warning',
            showCancelButton: true,
        }).then((result) => {
            if (result.value) {
                $.getJSON(settings.base_url + '/tags/remove/' + tagId, response => {
                    if(response.status === 'ok') {
                        window.location.reload();
                    }
                });
            }
        })

        e.preventDefault();
    });
})(jQuery);

// Categories
(function($){
    // Remove Category
    $('section#page-categories').on('click', '[data-action="category-remove"]', (e) => {
        //const $media = $(e.currentTarget).closest('.thumbnail').parent();
        const categoryId = $(e.currentTarget).attr('data-id');

        Swal.fire({
            title: 'Are you sure?',
            text: "Are you sure you want to remove selected categories and all media?",
            icon: 'warning',
            showCancelButton: true,
        }).then((result) => {
            if (result.value) {
                $.getJSON(settings.base_url + '/categories/remove/' + categoryId, response => {
                    if(response.status === 'ok') {
                        window.location.reload();
                    }
                });
            }
        })

        e.preventDefault();
    });
})(jQuery);

// Manage Media
(function($){
    $('section#page-media-index').on('click', '[data-action="media-remove"]', (e) => {
        const $media = $(e.currentTarget).closest('.thumbnail').parent();
        const mediaId = $(e.currentTarget).attr('data-id');

        Swal.fire({
            title: 'Are you sure?',
            text: "You won't be able to revert this!",
            icon: 'warning',
            showCancelButton: true,
        }).then((result) => {
            if (result.value) {
                $.getJSON(settings.base_url + '/media/remove/' + mediaId, response => {
                    if(response.status === 'ok') {
                        $media.fadeOut(500, () => {
                            window.location.reload();
                        });
                    }
                });
            }
        })

        e.preventDefault();
    });
})(jQuery);

// Create Media
(function($){
    // Add Media
    $('section#page-media-create .fileupload').fileupload({
        dataType: 'json',
        done: function (e, data) {
            let filename = data.result.filename;

            $('#add-photo .image-preview').html('<img src="' + settings.base_url + '/upload/temporary/' + filename + '" alt="">')
            $('#add-photo .image-preview').addClass('image-preview-visible');
            $('#add-photo input[name="filename"]').val(filename);
        },
        add: function(e, data) {
            let error = false;

            if(data.originalFiles[0]['size'].length && data.originalFiles[0]['size'] > 4000000) {
                error = true;
            }

            if(!error) {
                $('section#page-media-create .fileinput-button').hide();
                data.submit();
            } else {
                $('section#page-media-create .fileinput-button').show();

                Swal.fire(
                    'Upload Error',
                    'Sorry, your file is too big.',
                    'warning'
                );
            }
        }
    });
})(jQuery);

// Edit Media
(function($){

})(jQuery);

// Common
(function($){
    // Jquery-UI datepicker
    $('.datepicker').datepicker({ dateFormat: 'dd/mm/yy' });

    // Jquery-UI autocomplete
    function split( val ) {
        return val.split( /,\s*/ );
    }
    function extractLast( term ) {
        return split( term ).pop();
    }

    // Tags
    function tags() {
        $('.tags-select').each((index, element) => {
            const $container = $(element);
            const $selected = $container.find('ul.selected-tags');
            const $hidden = $container.find('input[name="tags"]');

            let tagsArray = $hidden.val().length ? $hidden.val().split(';') : [];

            function render() {
                $selected.html('');
                if(tagsArray.length) {
                    tagsArray.forEach((id, number) => {
                        if(id) {
                            const name = $container.find('[data-action="tag-add"][data-id="' + id + '"]').data('name');
                            $selected.prepend('<li><a href="#" class="badge badge-success" data-action="tag-remove" data-id="' + id + '">' + name + '</a></li>');
                        }
                    });
                }
            }

            render();

            // Remove tag
            $container.on('click', '[data-action="tag-remove"]', e => {
                const id = $(e.currentTarget).attr('data-id');

                const index = tagsArray.indexOf(id);

                tagsArray.splice(index, 1);
                $hidden.val(tagsArray.join(';'));

                render();

                e.preventDefault();
            });

            // Add tag
            $container.on('click', '[data-action="tag-add"]', e => {
                const id = $(e.currentTarget).attr('data-id');

                if (-1 === tagsArray.indexOf(id)) {
                    tagsArray.push(id);
                    $hidden.val(tagsArray.join(';'));

                    render();
                }

                e.preventDefault();
            });
        });
    }

    tags();

    /*$( ".input-tags" )
        // don't navigate away from the field on tab when selecting an item
        .on( "keydown", function( event ) {
            if ( event.keyCode === $.ui.keyCode.TAB &&
                $( this ).autocomplete( "instance" ).menu.active ) {
                event.preventDefault();
            }
        })
        .autocomplete({
            minLength: 0,
            source: function( request, response ) {
                // delegate back to autocomplete, but extract the last term
                response( $.ui.autocomplete.filter(
                    availableTags, extractLast( request.term ) ) );
            },
            focus: function() {
                // prevent value inserted on focus
                return false;
            },
            select: function( event, ui ) {
                var terms = split( this.value );
                // remove the current input
                terms.pop();
                // add the selected item
                terms.push( ui.item.value );
                // add placeholder to get the comma-and-space at the end
                terms.push( "" );
                this.value = terms.join( ", " );
                return false;
            }
        });*/

})(jQuery);

