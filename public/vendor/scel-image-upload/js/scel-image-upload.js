(function ($) {

    $.fn.scelUploader = function (options) {

        var settings = $.extend(true, {
            input: {
                class: ["scel-preview-item-input"],
                name: "scel-attachments"
            },
            previewZone: {
                name: "file-preview-zone"
            },
            backgroundColor: "white",
            multiple: true,
            previewItem: {
                container: {
                    class: ["scel-preview-item-container"]
                },
                subcontainer: {
                    class: ["scel-preview-item-subcontainer"]
                },
                delete: {
                    enabled: true,
                    container: {
                        class: ["scel-preview-item-delete-container"]
                    },
                    button: {
                        class: ["scel-preview-item-delete-button"],
                        content: "X"
                    }
                },
                edit: {
                    enabled: true,
                    container: {
                        class: ["scel-preview-item-edit-container"]
                    },
                    button: {
                        class: ["scel-prewview-item-edit-button"],
                        content: "E"
                    }
                },
                image: {
                    container: {
                        class: ["scel-preview-item-image-container"]
                    },
                    element: {
                        class: ["scel-preview-item-image"]
                    },
                    label: {
                        class: ["scel-preview-item-label"]
                    }
                }
            }
        }, options);

        var self = this;

        function createNewElement(name, next) {
            var newElement = $('<input type="file" ' +
                'class="' + settings.input.class.join(" ") + '" ' +
                'name="' + settings.input.name + (settings.multiple ? "[]" : "") + '" ' +
                'data-id="' + next + '" data-zone="' + settings.previewZone.name + '">'
            );
            console.log( newElement );
            newElement.appendTo("#" + settings.previewZone.name);
            newElement.click();
        }

        this.click(function () {
            var attachments = $('.' + settings.input.class[0] + '[data-zone="' + settings.previewZone.name + '"]');
            var quantity = attachments.length;
            var next = 0;
            var name = settings.input.name;
            if ((last = attachments.get((quantity * 1) - 1)) !== undefined) {
                next = $(last).attr('data-id') * 1 + 1;
            }

            if (!settings.multiple && quantity !== 0) {
                $('input[name="' + name + '"]').click();
            } else {
                createNewElement(name, next);
            }

        });

        $('#' + settings.previewZone.name).on('change', '.' + settings.input.class[0], function () {
            self.readUrl(this, "#" + settings.previewZone.name, settings);
        }).on('click', '.' + settings.previewItem.delete.container.class[0], function () {
            var confirmation = confirm("Are you sure to delete this image?");
            if (confirmation) {
                var id = $(this).find('button').attr('data-id');
                $('.' + settings.input.class[0] + '[data-id="' + id + '"][data-zone=' + settings.previewZone.name + ']').remove();
                $('.' + settings.previewItem.container.class[0] + '[data-id="' + id + '"][data-zone=' + settings.previewZone.name + ']').remove();
            }
        });

        $(document).on('click', '.' + settings.previewItem.edit.container.class[0], function () {
            //TODO
        });

        return this;
    };

    $.fn.readUrl = function (input, zone, settings) {
        if (input.files && input.files[0]) {

            for (i = 0; i < input.files.length; i++) {

                var reader = new FileReader();
                reader.readAsDataURL(input.files[i]);

                reader.onload = (function (id, file) {
                    return function (e) {
                        $(zone).find('.' + settings.previewItem.container.class[0] + '[data-id="' + id + '"]').remove();
                        $(zone).append(
                            '<div class="' + settings.previewItem.container.class.join(" ") + '" data-id="' + id + '" data-zone="' + settings.previewZone.name + '">' +
                            '    <div class="' + settings.previewItem.subcontainer.class.join(" ") + '" data-zone="' + settings.previewZone.name + '">' +
                            '        <div class="' + settings.previewItem.delete.container.class.join(" ") + '" data-zone="' + settings.previewZone.name + '">' +
                            '            <button type="button" class="' + settings.previewItem.delete.button.class.join(" ") + '" data-id="' + id + '" data-zone="' + settings.previewZone.name + '">' +
                            '                ' + settings.previewItem.delete.button.content +
                            '            </button>' +
                            '        </div>' +
                            '        <div class="' + settings.previewItem.image.container.class.join(" ") + '" data-zone="' + settings.previewZone.name + '">' +
                            '            <img src="' + e.target.result + '" class="' + settings.previewItem.image.element.class.join(" ") + '" data-zone="' + settings.previewZone.name + '">' +
                            '        </div>' +
                            '        <div class="' + settings.previewItem.image.label.class.join(" ") + '" data-zone="' + settings.previewZone.name + '">' +
                            '            <span>' + file.name + '</span>' +
                            '        </div>' +
                            '    </div>' +
                            '</div>'
                        );
                    }
                })(input.getAttribute('data-id'), input.files[i])

            }
        }
    };


}( jQuery ));