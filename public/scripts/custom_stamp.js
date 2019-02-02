$('.js-stamp-shape').on('click', function () {
    var stampShape = $(this).data('stamp-shape');

    $('.sphereStampSize').css('display', 'none');
    $('.sizesParent').children('.sizeInputs').each(function (e) {
        $(this).css('display', 'none');
    });

    var isSphere = $('.js-radioButton:checked').val();


    if (isSphere == 1) {
        $('.sphereStampSize').css('display', 'block');
        if (stampShape == "Custom") {
            $('.customStampSize').css('display', 'block');
        }
    } else {
        switch (stampShape) {
            case "Square":
                $('.squareStampSize').css('display', 'block');
                break;
            case "Rectangle":
                $('.rectangleStampSize').css('display', 'block');
                break;
            case "Circle":
                $('.circleStampSize').css('display', 'block');
                break;
            case "Ellipse":
                $('.ellipseStampSize').css('display', 'block');
                break;
            case "Custom":
                $('.customStampSize').css('display', 'block');
                break;
            default:
                $('.customStampSize').css('display', 'block');
                break;
        }
    }

    var $parent = $(this).parent().parent();
    $parent.children('.item-selection-custom').children('.js-stamp-shape').each(function (e) {
        $(this).removeClass('active');
    });
    $(this).addClass('active');
    $('#stamp_shape').val(stampShape);
});

$('.js-radioButton').on('change', function () {
    var isSphere = $('.js-radioButton:checked').val();
    $('.sphereStampSize').css('display', 'none');
    $('.sizesParent').children('.sizeInputs').each(function (e) {
        $(this).css('display', 'none');
    });

    var stampShape = $('#stamp_shape').val();

    if (isSphere == 1) {
        $('.sphereStampSize').css('display', 'block');
        if (stampShape == "Custom") {
            $('.customStampSize').css('display', 'block');
        }
    } else {
        switch (stampShape) {
            case "Square":
                $('.squareStampSize').css('display', 'block');
                break;
            case "Rectangle":
                $('.rectangleStampSize').css('display', 'block');
                break;
            case "Circle":
                $('.circleStampSize').css('display', 'block');
                break;
            case "Ellipse":
                $('.ellipseStampSize').css('display', 'block');
                break;
            case "Custom":
                $('.customStampSize').css('display', 'block');
                break;
            default:
                $('.customStampSize').css('display', 'block');
                break;
        }
    }
});

$('.js-shape-element').on('click', function () {
    var $parent = $(this).parent().parent();
    $parent.children('.item-selection-custom').children('.js-shape-element').each(function (e) {
        $(this).removeClass('active');
    });
    $(this).addClass('active');
    $('#handle_shape').val($(this).data('shape'));

});

$('.js-color-element').on('click', function () {
    var $parent = $(this).parent().parent();
    $parent.children('.item-selection-custom').children('.js-color-element').each(function (e) {
        $(this).removeClass('active');
    });
    $(this).addClass('active');
    $('#handle_color').val($(this).data('color'));

});

$('#visibleButton').on('click', function (e) {
    var isSphere = $('.js-radioButton:checked').val();
    var stampShape = $('#stamp_shape').val();
    var text = '';
    text += 'Stamp Shape: ' + stampShape + '<br />';
    var sizeText = '';

    if (isSphere == 1) {
        if ($.trim($('#diameterIceBall').val()).length > 0) {

        }
        text += 'Ice Ball Diameter: ' + $('#diameterIceBall').val() + '<br />';
        if (stampShape == "Custom") {
            text += 'Size: ' + $('#customSizeNote').val() + '<br />';
        }
    } else {
        switch (stampShape) {
            case "Square":
                text += 'Size: ' + $('#sizeSquare').val() + '<br />';
                if ($.trim($('#sizeSquare').val()).length > 0) {
                    sizeText = $('#sizeSquare').val();
                }
                break;
            case "Rectangle":
                text += 'Size: ' + $('#sizeRectangleA').val() + ' x ' + $('#sizeRectangleB').val() + '<br />';
                if ($.trim($('#sizeRectangleA').val()).length > 0 && $.trim($('#sizeRectangleB').val()).length > 0) {
                    sizeText = $('#sizeRectangleA').val() + ' x ' + $('#sizeRectangleB').val();
                }
                break;
            case "Circle":
                text += 'Size: ' + $('#sizeCircle').val() + '<br />';
                if ($.trim($('#sizeCircle').val()).length > 0) {
                    sizeText = $('#sizeCircle').val();
                }
                break;
            case "Ellipse":
                text += 'Size: ' + $('#sizeEllipseA').val() + ' x ' + $('#sizeEllipseB').val() + '<br />';
                if ($.trim($('#sizeEllipseA').val()).length > 0 && $.trim($('#sizeEllipseB').val()).length > 0) {
                    sizeText = $('#sizeEllipseA').val() + ' x ' + $('#sizeEllipseB').val();
                }
                break;
            case "Custom":
                text += 'Size: ' + $('#customSizeNote').val() + '<br />';
                if ($.trim($('#customSizeNote').val()).length > 0) {
                    sizeText = $('#customSizeNote').val();
                }
                break;
            default:
                text += 'Size: ' + $('#customSizeNote').val() + '<br />';
                if ($.trim($('#customSizeNote').val()).length > 0) {
                    sizeText = $('#customSizeNote').val();
                }
                break;
        }
    }
    $('#size').val(sizeText);

    text += 'Handle: ' + $('#handle_shape').val() + $('#handle_color').val();


    e.preventDefault();
    swal({
        title: 'Are all details correct?',
        html: text,
        type: 'question',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#6c757d',
        confirmButtonText: '<i class="fas fa-check"></i>  Yes',
        allowOutsideClick: false,
    }).then((result) => {
        if (result.value) {
            $('#realButton').click();
        }
    });
});