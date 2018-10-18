"use strict";

$(document).ready(() => {
    let
        body = $('body'),
        modal = $('.modal'),
        loginWindow = $('.login_window'),
        timer,
        draggable = false,
        dragItem = null,
        shiftY,
        shiftX,
        isOpened = false,
        leftPos,
        topPos,
        hierarchy;

    //modal window
    function openModal (content) {
        $('.modal').append(content);
    }

    body.on('submit', '.login_window', function (e) {
        e.preventDefault();
        let log = $(this).find('input[name=login]').val();
        let pass = $(this).find('input[name=password]').val();
        let url = $(this).attr('action');

        $.ajax({
            url: url,
            method: 'POST',
            data: {
                login: log,
                password: pass
            },
            success: (answer) => {
                if(answer.success) {
                    location.reload();
                    return;
                }
                showAjaxValidateError(answer);
            }
        })
    });

    body.on('click', '.addUser', (e) => {
        e.preventDefault();
        modal.css({'display':'flex'});
    });

    body.on('click', '.modal__close', function(e) {
        e.preventDefault();
        $(this).closest('.modal').css({'display':'none'});
    });

    //dich

    body.click((e) => {
        //close login block
        if(!$(e.target).closest('.login').length && !$(e.target).closest('.login_window').length) loginWindow.slideUp();
    });

    $('.login').click((e) => {
        e.preventDefault();
        loginWindow.slideToggle();
    });

    body.on('click', '.departments__item', openDepartment);

    body.on('click', '.close_department',  closeDepartmentItem);

    body.on('click','.subordinate__item', function () {
        //do not show subordinate when user drop item
        if(dragItem[0] === this && draggable) return;
        
        if($(this).find('.show_subordinate').length === 0) return;

        hierarchy = $(this).parent('.subordinate').attr('data-hierarchy');

        if(hierarchy == 6) return;
        
        showSubordinate.call(this);
        
        if(!draggable) makeSubordinateActive.call(this);
    });
    //drag'n'drop
    body.on('mousedown', '.subordinate__item', function (e) {
        makeSubordinateDraggable.call(this, e);
    });

    body.on('mouseup', function (e) {
        dropDraggableItemAndSendAjax(e);
    });

    $(document).on('mousemove', (e) => {
       if(draggable)  moveItem(e);
    });
    //upload file
    body.on('change', "input[type='file']", showUploadedImage);


    function moveItem (e) {
        dragItem.css({
            'position':'absolute',
            'left':`${e.clientX - shiftX}px`,
            'top':`${e.clientY - shiftY}px`,
            'z-index':'10'
        })
    }

    function showAjaxValidateError (result) {
        let errors = result.errors;
        if(errors) {let errorMessage = '';
            for (let err in errors) {
                errorMessage += `${err} : ${errors[err]} \n`;
            }
            alert(errorMessage);
        } else {
            alert(result.error);
        }
    }

    function openDepartment () {
        if(isOpened) {
            return;
        }
        let url = $(this).attr('data-url');

        $.ajax({
            url: url,
            type: 'GET',
            success: (html) => {
                $(this).append(html);
                $(this).find('.departments__content').addClass('visible');
            }
        });
        //set absolute position to beauty animate
        leftPos = $(this).offset().left;
        topPos = $(this).offset().top;
        $(this).css({'left':`${leftPos - 20}px`});
        $(this).css({'top':`${topPos - 20}px`});
        //disable all other items
        setTimeout(() => {
            $(this).addClass('departments__item_active');
            $('.departments__item').each((i, item) => {
                if (item !== this) {
                    $(item).addClass('departments__item_disabled');
                }
            });
            isOpened = true;
        },1);
        //make visible department items
        setTimeout(() => {
            $(this).find('.close_department').addClass('close_department_active');
        },400);
    }

    function closeDepartmentItem () {
        $(this).siblings('.departments__content').remove();
        let departmentItem = $(this).parent();
        departmentItem.find('.departments__content').removeClass('departments__content_active visible');
        $(this).removeClass('close_department_active');
        departmentItem
            .removeClass('departments__item_active')
            .css({
                'position':'absolute',
                'left':`${leftPos}px !important`,
                'top':`${topPos}px !important`
            });
        setTimeout(() => {
            departmentItem.css({
                'position':'static',
                'left':'auto',
                'top':'auto'
            });
            $('.departments__item').each((i, item) => {
                if (item !== this) {
                    $(item).removeClass('departments__item_disabled');
                }
            });
            isOpened = false;
        },400);
    }

    function showSubordinate () {
        let url = $(this).attr('data-url');
        $.ajax({
            url: url,
            type: 'GET',
            success: (html) => {
                if($(html).html() === 'No records') return false;
                $(this).closest('.departments__content').append(html);
                $('.departments__content').find('.subordinate:last-child').attr('data-hierarchy', +hierarchy + 1);

            }
        });
    }

    function makeSubordinateActive () {
        $(this).parent().find('.subordinate__item').removeClass('subordinate__item_active');
        $(this).addClass('subordinate__item_active');

        $('.subordinate').each((i, item) => {
            if($(item).attr('data-hierarchy') > hierarchy + 2) {
                $(item).remove();
            }
        });
    }

    function makeSubordinateDraggable (e) {
        dragItem = $(this);
        if (dragItem.hasClass('subordinate__item_active')) return;
        shiftY = e.clientY - dragItem.offset().top;
        shiftX = e.clientX - dragItem.offset().left;
        timer = setTimeout(() => {
            draggable = true;
        },200);
    }

    function dropDraggableItemAndSendAjax (e) {
        clearTimeout(timer);
        if(draggable) {
            setTimeout(() => {
                draggable = false;
            },10);

            let item = $(e.target).closest('.subordinate__item');
            // hide to get item under
            item.css({'display':'none'});
            let director = $(document.elementFromPoint(e.clientX, e.clientY)).closest('.subordinate__item');
            item.css({'display':'block'});

            let url = $(director).attr('data-url');
            //return item to start place if that dropped not on subordinate
            if(url === undefined) {
                setTimeout(()=> {
                    placeItemBack($(item));
                },10);
                return;
            }
            let newBossHash = getHashFromUrl(url);
            url = $(dragItem).attr('data-url');
            let employeeHash = getHashFromUrl(url);
            url = $('.departments').attr('data-rewrite-boss-employee');

            $.ajax({
                url: url,
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded'
                },
                type: 'POST',
                data: {
                    newBoss: newBossHash,
                    employee: employeeHash,
                },
                success: (result) => {
                    if(result.success){
                        $(item).remove();
                        alert('good!');
                        $(director).trigger('click');
                    } else { //error
                        placeItemBack($(item));
                        showAjaxValidateError(result);
                    }
                }
            });
        }
    }
    
    function placeItemBack (item) {
        item.css({
            'position':'relative',
            'left':0,
            'top':0
        });
        alert('Сотрудника можно переместить только на его будущего босса !!!');
    }

    function showUploadedImage () {
        let file = this.files[0];

        if(file.type.match(/image/)) {
            $('.upload_image_container').html('');

            $('.upload_image').addClass('upload_image__active');

            let reader = new FileReader();

            reader.readAsDataURL(file);

            reader.onload = (function () {
                setTimeout(() => {
                    $('.upload_image').attr('src', reader.result);
                },100)

            })(file);

        } else {
            $('.upload_image').removeClass('upload_image__active').attr('src', '');
            $('.upload_image_container').html('THIS IS NOT IMAGE');
        }
    }

    function getHashFromUrl (url) {
        url = url.split('/');
        return url[url.length - 1];
    }





    //crud



    let depart = $('#department').val();
    let attr = $('.employees').attr('current_page');
    let page = 1;
    let maxPage = $('.employees').attr('last_page');
    $('.employees').on('scroll', function () {

        if ((this.scrollHeight - $(this).height()) === $(this).scrollTop()) {
            if(page > maxPage) return;
            let newAttr = attr + `/${depart}?page=${page}`;
            console.log(depart)
            $.ajax({
                url: newAttr,
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded'
                },
                method: "GET",
                success: (result) => {
                    console.log(result)
                }
            });
            
            $(this).attr('current_page', newAttr);
            page++;
        }
    })
});


