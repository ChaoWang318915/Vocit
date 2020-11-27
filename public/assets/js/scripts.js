$(function(){
    resetSticky();
    $('.ui.dropdown').dropdown();
    $('.ui.sticky')
        .sticky({
            offset       : 115,
            context: '#stickyContainer'
        })
    ;
    $('.ui.accordion')
        .accordion()
    ;

    $('.ui.progress').progress();
    $('.popup-element').each(function(){
        $(this).popup({
            inline     : true,
            position   : 'top left',
        });
    })

    // $('.btn-popup-element').each(function(){
    //     $(this).popup({
    //         inline     : true,
    //         position: 'bottom right',
    //     });
    // })

    $('.ui.checkbox').checkbox();
    $('.coupon-checkbox').checkbox({
        onChecked: function() {
           $(document).find('.coupon-input').stop(0).slideDown('fast');
        },
        onUnchecked: function() {
            $(document).find('.coupon-input').stop(0).slideUp('fast');
        },
    })
    $('.business-check').checkbox({
        onChecked: function() {
            let value = $(this).val();
            if(value === 'yes'){
                $(document).find('.business-ein').stop(0).slideDown('fast');
                $(document).find('.contact-person').stop(0).fadeOut('fast');
            }
            else{
                $(document).find('.business-ein').stop(0).slideUp('fast');
                $(document).find('.contact-person').stop(0).fadeIn('fast');
                $(document).find('.ein').val('');
            }
        }
    })
    $(document).on('click', '.choose-images-btn', function(){
        $(".image-input").trigger('click');
    })

    $(document).on('click', '.upload-for-reward', function(){
        $(document).find('.info-modal.modal')
            .modal('show');
    })

    $(document).on('click', '.upload-for-crop', function(){
        //upload for corp image
        $(".crop-image-input").trigger('click');
    })

    $(document).on('click', '.info-modal.modal .approve', function(){
        $(".image-input").trigger('click');
    })

    $(document).on('click', '.info-modal.modal .reject', function(){
        $(document).find('.info-modal.modal')
            .modal('hide');
    })

    $(document).on('click', '.choose-banner-btn', function(){
        $(".banner-input").trigger('click');
    })
    $(document).on('click', '.create-post-btn', function () {
        $(document).find('.create-post-modal').modal('show');
    })
    $(document).on('click', '.comment-reply-btn', function(){
        $(this).stop(0).slideUp('fast');
        $(document).find('.comment-area').stop(0).slideUp('fast');
        $(this).parent().find('.comment-area').stop(0).slideDown('fast');
    })
    $(document).on('click', '.comment-cancel-btn', function(){
        $(this).parents('.actions').find('.comment-reply-btn').stop(0).slideDown('fast');
        $(document).find('.comment-area').stop(0).slideUp('fast');
    })
    $(document).find('.tooltip-element').popup();
})

function resetSticky() {
    let windowWidth = $(window).width();
   if(windowWidth <  767){
       $(document).find('.sticky-card').removeClass('sticky');
   }
   else{
       $(document).find('.sticky-card').addClass('sticky');
   }
}
