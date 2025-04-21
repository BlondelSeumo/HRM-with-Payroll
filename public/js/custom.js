/**
 *
 * You can write your JS code here, DO NOT touch the default style file
 * because it will make it harder for you to update.
 *
 */

"use strict";

$(function () {
    if ($('.custom-scroll').length) {
        $(".custom-scroll").niceScroll();
        $(".custom-scroll-horizontal").niceScroll();
    }


    // loadConfirm();
    daterange();

});

function validation() {

    var forms = document.querySelectorAll('.needs-validation');


    Array.prototype.forEach.call(forms, function (form) {

        form.addEventListener('submit', function (event) {
            var submitButton = form.querySelector('button[type="submit"], input[type="submit"]');

            if (submitButton) {
                submitButton.disabled = true;
            }
            if (form.checkValidity() === false) {
                event.preventDefault();
                event.stopPropagation();
                if (submitButton) {
                    submitButton.disabled = false;
                }
            }

            form.classList.add('was-validated');
        }, false);
    });
}

$(document).ready(function () {
    if ($(".datatable").length > 0) {
        new simpleDatatables.DataTable(".datatable");
    }

    if ($(".needs-validation").length > 0) {
        validation();
    }

    // loadConfirm();
    select2();
    summernote();
    daterange();

    if ($(".d_week").length > 0) {
        $($(".d_week")).each(function (index, element) {
            var id = $(element).attr('id');

            (function () {
                const d_week = new Datepicker(document.querySelector('#' + id), {
                    buttonClass: 'btn',
                    format: 'yyyy-mm-dd',
                });
            })();

        });
    }

    if ($(".d_filter").length > 0) {
        $($(".d_filter")).each(function (index, element) {
            var id = $(element).attr('id');

            (function () {
                const d_week = new Datepicker(document.querySelector('#' + id), {
                    buttonClass: 'btn',
                    format: 'yyyy-mm',
                });
            })();

        });
    }

    if ($(".editor").length > 0) {
        $($(".editor")).each(function (index, element) {
            var id = $(element).attr('id');
            tinymce.init({
                height: "400",
                selector: '#' + id,
                content_style: 'body { font-family: "Inter", sans-serif; }',
                menubar: false,
                toolbar: ['styleselect fontselect fontsizeselect',
                    'undo redo | cut copy paste | bold italic | link image | alignleft aligncenter alignright alignjustify',
                    'bullist numlist | outdent indent | blockquote subscript superscript | advlist | autolink | lists charmap | print preview |  code'
                ],
                plugins: 'advlist autolink link image lists charmap print preview code'
            });
            0
        });
    }
});


function daterange() {
    if ($("#pc-daterangepicker-1").length > 0) {
        document.querySelector("#pc-daterangepicker-1").flatpickr({
            mode: "range"
        });
    }
}

function select2() {
    if ($(".select2").length > 0) {
        $($(".select2")).each(function (index, element) {
            var id = $(element).attr('id');
            var multipleCancelButton = new Choices(
                '#' + id, {
                removeItemButton: true,
            }
            );
        });
    }
}

// // minimum setup
// (function () {
//     const d_week = new Datepicker(document.querySelector('.pc-datepicker-1'), {
//         buttonClass: 'btn',
//     });
// })();
// (function () {
//     const d_week = new Datepicker(document.querySelector('.pc-datepicker-1_modal'), {
//         buttonClass: 'btn',
//     });
// })();

function show_toastr(type, message) {
    var f = document.getElementById('liveToast');
    var a = new bootstrap.Toast(f).show();
    if (type == 'success' || type == 'Success') {
        $('#liveToast').addClass('bg-primary');
    } else {
        $('#liveToast').addClass('bg-danger');
    }
    $('#liveToast .toast-body').html(message);
}

$(document).on('click', 'a[data-ajax-popup="true"], button[data-ajax-popup="true"], div[data-ajax-popup="true"]', function () {

    var title1 = $(this).data("title");
    var title2 = $(this).data("bs-original-title");
    var title = (title1 != undefined) ? title1 : title2;
    var size = ($(this).data('size') == '') ? 'md' : $(this).data('size');
    var url = $(this).data('url');
    $("#commonModal .modal-title").html(title);
    $("#commonModal .modal-dialog").addClass('modal-' + size);
    $.ajax({
        url: url,
        success: function (data) {
            $('#commonModal .body').html(data);
            $("#commonModal").modal('show');
            // daterange_set();
            taskCheckbox();
            common_bind("#commonModal");
            commonLoader();
            select2();
            cancelButtonCss();

            if ($(".d_clock").length > 0) {
                // alert('hiii')
                $($(".d_clock")).each(function (index, element) {
                    var id = $(element).attr('id');


                    document.querySelector("#" + id).flatpickr({
                        enableTime: true,
                        noCalendar: true,
                    });

                });
            }
            // document.querySelector("#pc-timepicker-1").flatpickr({
            //     enableTime: true,
            //     noCalendar: true,
            // });

            $(document).ready(function () {
                validation();
            });


            if ($(".d_week").length > 0) {
                $($(".d_week")).each(function (index, element) {
                    var id = $(element).attr('id');

                    (function () {
                        const d_week = new Datepicker(document.querySelector('#' + id), {
                            buttonClass: 'btn',
                            format: 'yyyy-mm-dd',
                        });
                    })();

                });
            }

            if ($(".d_filter").length > 0) {
                $($(".d_filter")).each(function (index, element) {
                    var id = $(element).attr('id');

                    (function () {
                        const d_week = new Datepicker(document.querySelector('#' + id), {
                            buttonClass: 'btn',
                            format: 'yyyy-mm',
                        });
                    })();

                });
            }

        },
        error: function (data) {
            data = data.responseJSON;
            show_toastr('Error', data.error, 'error')
        }
    });

});



function arrayToJson(form) {
    var data = $(form).serializeArray();
    var indexed_array = {};

    $.map(data, function (n, i) {
        indexed_array[n['name']] = n['value'];
    });

    return indexed_array;
}


function common_bind() {

}


function taskCheckbox() {
    var checked = 0;
    var count = 0;
    var percentage = 0;

    count = $("#check-list input[type=checkbox]").length;
    checked = $("#check-list input[type=checkbox]:checked").length;
    percentage = parseInt(((checked / count) * 100), 10);
    if (isNaN(percentage)) {
        percentage = 0;
    }
    $(".custom-label").text(percentage + "%");
    $('#taskProgress').css('width', percentage + '%');


    $('#taskProgress').removeClass('bg-warning');
    $('#taskProgress').removeClass('bg-primary');
    $('#taskProgress').removeClass('bg-success');
    $('#taskProgress').removeClass('bg-danger');

    if (percentage <= 15) {
        $('#taskProgress').addClass('bg-danger');
    } else if (percentage > 15 && percentage <= 33) {
        $('#taskProgress').addClass('bg-warning');
    } else if (percentage > 33 && percentage <= 70) {
        $('#taskProgress').addClass('bg-primary');
    } else {
        $('#taskProgress').addClass('bg-success');
    }
}


function commonLoader() {
    $('[data-toggle="tooltip"]').tooltip();
    if ($('[data-toggle="tags"]').length > 0) {
        $('[data-toggle="tags"]').tagsinput({
            tagClass: "badge badge-primary"
        });
    }


    var e = $(".scrollbar-inner");
    e.length && e.scrollbar().scrollLock()

    var e1 = $(".custom-input-file");
    e1.length && e1.each(function () {
        var e1 = $(this);
        e1.on("change", function (t) {
            ! function (e, t, a) {
                var n, o = e.next("label"),
                    i = o.html();
                t && t.files.length > 1 ? n = (t.getAttribute("data-multiple-caption") || "").replace("{count}", t.files.length) : a.target.value && (n = a.target.value.split("\\").pop()), n ? o.find("span").html(n) : o.html(i)
            }(e1, this, t)
        }), e1.on("focus", function () {
            ! function (e) {
                e.addClass("has-focus")
            }(e1)
        }).on("blur", function () {
            ! function (e) {
                e.removeClass("has-focus")
            }(e1)
        })
    })

    var e2 = $('[data-toggle="autosize"]');
    e2.length && autosize(e2);

    if ($(".summernote-simple").length) {
        $('.summernote-simple').summernote({
            dialogsInBody: !0,
            minHeight: 200,
            toolbar: [
                ['style', ['style']],
                ['font', ['bold', 'italic', 'underline', 'strikethrough']],
                ['list', ['ul', 'ol', 'paragraph']],
                ['insert', ['link', 'unlink']],
            ],
            height: 250,
        });
    }

    if ($(".jscolor").length) {
        jscolor.installByClassName("jscolor");
    }
    summernote();
    // for Choose file
    $(document).on('change', 'input[type=file]', function () {
        var fileclass = $(this).attr('data-filename');
        var finalname = $(this).val().split('\\').pop();
        $('.' + fileclass).html(finalname);
    });
}

function summernote() {
    if ($(".summernote-simple").length) {
        $('.summernote-simple').summernote({
            dialogsInBody: !0,
            minHeight: 200,
            maxHeight: 300,
            toolbar: [
                ['style', ['style']],
                ['font', ['bold', 'italic', 'underline', 'strikethrough']],
                ['list', ['ul', 'ol', 'paragraph']],
                ['insert', ['link', 'unlink']],
            ],
            height: 250,

        });
        $('.dropdown-toggle').dropdown();
    }

    if ($(".summernote-simple-2").length) {
        $('.summernote-simple-2').summernote({
            dialogsInBody: !0,
            minHeight: 200,
            maxHeight: 300,
            toolbar: [
                ['style', ['style']],
                ['font', ['bold', 'italic', 'underline', 'strikethrough']],
                ['list', ['ul', 'ol', 'paragraph']],
                ['insert', ['link', 'unlink']],
            ],
            height: 250,

        });
    }

    if ($(".summernote-simple-3").length) {
        $('.summernote-simple-3').summernote({
            dialogsInBody: !0,
            minHeight: 200,
            maxHeight: 300,
            toolbar: [
                ['style', ['style']],
                ['font', ['bold', 'italic', 'underline', 'strikethrough']],
                ['list', ['ul', 'ol', 'paragraph']],
                ['insert', ['link', 'unlink']],
            ],
            height: 250,

        });
    }

}

$(function () {

    $(document).on("click", ".bs-pass-para", function () {
        var form = $(this).closest("form");
        const swalWithBootstrapButtons = Swal.mixin({
            customClass: {
                confirmButton: 'btn btn-success',
                cancelButton: 'btn btn-danger'
            },
            buttonsStyling: false
        })
        swalWithBootstrapButtons.fire({
            title: 'Are you sure?',
            text: "This action can not be undone. Do you want to continue?",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Yes',
            cancelButtonText: 'No',
            reverseButtons: true
        }).then((result) => {
            if (result.isConfirmed) {
                form.submit();
            }
        })
    });

});


function postAjax(url, data, cb) {
    var token = $('meta[name="csrf-token"]').attr('content');
    var jdata = {
        _token: token
    };

    for (var k in data) {
        jdata[k] = data[k];
    }

    $.ajax({
        type: 'POST',
        url: url,
        data: jdata,
        success: function (data) {
            if (typeof (data) === 'object') {
                cb(data);
            } else {
                cb(data);
            }
        },
    });
}

function deleteAjax(url, data, cb) {
    var token = $('meta[name="csrf-token"]').attr('content');
    var jdata = {
        _token: token
    };

    for (var k in data) {
        jdata[k] = data[k];
    }

    $.ajax({
        type: 'DELETE',
        url: url,
        data: jdata,
        success: function (data) {
            if (typeof (data) === 'object') {
                cb(data);
            } else {
                cb(data);
            }
        },
    });
}

// $(document).on('click', '.fc-day-grid-event', function (e) {
//     alert('cnh')
//     // if (!$(this).hasClass('project')) {
//     e.preventDefault();
//     var event = $(this);
//     var title = $(this).find('.fc-content .fc-title').html();
//     var size = 'md';
//     var url = $(this).attr('href');
//     $("#commonModal .modal-title").html(title);
//     $("#commonModal .modal-dialog").addClass('modal-' + size);
//     $.ajax({
//         url: url,
//         success: function (data) {
//             $('#commonModal .modal-body').html(data);
//             $("#commonModal").modal('show');
//             common_bind();
//             select2();
//         },
//         error: function (data) {
//             data = data.responseJSON;
//             toastrs('Error', data.error, 'error')
//         }
//     });
//     // }
// });

// Google calendar
$(document).on('click', '.local_calender .fc-day-grid-event', function (e) {


    // if (!$(this).hasClass('project')) {
    e.preventDefault();
    var event = $(this);
    var title1 = $('.fc-event-title').html();
    var title2 = $(this).data("bs-original-title");
    var title = (title1 != undefined) ? title1 : title2;
    // var size = ($(this).data('size') == '') ? 'md' : $(this).data('size');
    var size = 'md';
    var url = $(this).attr('href');
    $("#commonModal .modal-title").html(title);
    $("#commonModal .modal-dialog").addClass('modal-' + size);
    $.ajax({
        url: url,
        success: function (data) {
            $('#commonModal .body').html(data);
            $("#commonModal").modal('show');
            common_bind();
        },
        error: function (data) {
            data = data.responseJSON;
            toastrs('Error', data.error, 'error')
        }
    });
    // }
});

// ChatGPT
$(document).on('click', 'a[data-ajax-popup-over="true"], button[data-ajax-popup-over="true"], div[data-ajax-popup-over="true"]', function () {

    var validate = $(this).attr('data-validate');
    var id = '';
    if (validate) {
        id = $(validate).val();
    }

    var title = $(this).data('title');
    var size = ($(this).data('size') == '') ? 'md' : $(this).data('size');
    var url = $(this).data('url');

    $("#commonModalOver .modal-title").html(title);
    $("#commonModalOver .modal-dialog").addClass('modal-' + size);

    $.ajax({
        url: url + '?id=' + id,
        success: function (data) {
            $('#commonModalOver .modal-body').html(data);
            $("#commonModalOver").modal('show');
            taskCheckbox();
        },
        error: function (data) {
            data = data.responseJSON;
            show_toastr('Error', data.error, 'error')
        }
    });

});

// cancel button js
function cancelButtonCss() {
    document.querySelectorAll('.modal-footer input[type="button"]').forEach(button => {

        if (button.value === "Cancel") {
            button.classList.remove('btn-light');
            button.classList.add('btn-secondary');
        }
    });

    document.querySelectorAll('.modal-footer button[data-bs-dismiss="modal"]').forEach(button => {
        if (button.textContent.trim() === "Cancel") {
            button.classList.remove('btn-light');
            button.classList.add('btn-secondary');
        }
    });
}

// Import Data for Dynamic function
function SetDynamicData(tbody, thead, count = 0) {
    if (count < 8) {
        var process_area = document.getElementById("process_area");
        if (process_area) {
            var theadHtml = '<tr>';
            for (let i = 0; i < Object.values(thead).length; i++) {
                theadHtml += '<th>';
                theadHtml += '<select name="set_column_data" class="form-control set_column_data" data-column_number="' + i + '">';
                theadHtml += '<option value="">Select Field</option>';
                $.each(thead, function(key, value) {
                    let formattedText = value.replace(/_/g, ' ').replace(/\b\w/g, function(letter) {
                        return letter.toUpperCase();
                    });
                    theadHtml += '<option value="' + value + '">' + formattedText + '</option>';
                });
                theadHtml += '</select>';
                theadHtml += '</th>';
            }
            theadHtml += '</tr>';

            $('#process_area .table .thead').html(theadHtml);
            $('#process_area .table .tbody').html(tbody);
        }
        else {
            setTimeout(function () {
                SetDynamicData(tbody, count + 1);
            }, 500);
        }
    }
    else {
        show_toastr('Success', '{{ __("Something went wrong, please try again!") }}', 'success');
    }
}

// Import Data
function SetData(params, count = 0) {
    if (count < 8) {
        var process_area = document.getElementById("process_area");
        if (process_area) {
            $('#process_area').html(params);
        }
        else {
            setTimeout(function () {
                SetData(params, count + 1);
            }, 500);
        }
    }
    else {
        toastrs('Success', '{{ __("Something went wrong, please try again!") }}', 'success');
    }
}
