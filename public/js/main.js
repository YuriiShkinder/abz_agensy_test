"use strict";

$(document).ready(() => {
    let
        body = $('body'),
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


    $('.login').on('click', (e) => {
        e.preventDefault();
        loginWindow.slideToggle();
    });

    body.on('submit', '.login_window', function (e) {
        checkAuthentication.call(this, e)
    });


    body.on('click', (e) => {
        //close login block
        if(!$(e.target).closest('.login').length && !$(e.target).closest('.login_window').length) loginWindow.slideUp();
    });

    body.on('click', '.departments__item', openDepartment);

    body.on('click', '.close_department',  closeDepartmentItem);


    body.on('click','.subordinate__item', function () {
        //do not show subordinate when user drop item or this employee have no subordinates
        if((dragItem[0] === this && draggable) || ($(this).find('.show_subordinate').length === 0)) return;

        hierarchy = $(this).parent('.subordinate').attr('data-hierarchy');

        if(hierarchy === "6") return;

        showSubordinate.call(this);

        makeSubordinateActive.call(this);
    });

    //drag'n'drop
    body.on('mousedown', '.subordinate__item', function (e) {
        makeSubordinateDraggable.call(this, e);
    });

    body.on('mouseup', function (e) {
        dropDraggableItem(e);
    });

    $(document).on('mousemove', (e) => {
       if(draggable)  moveItem(e);
    });


    function moveItem (e) {
        dragItem.css({
            'position':'absolute',
            'left':`${e.clientX - shiftX}px`,
            'top':`${e.clientY - shiftY}px`,
            'z-index':'10'
        })
    }

    function checkAuthentication (e) {
        e.preventDefault();
        let log = $(this).find('input[name=login]').val();
        let pass = $(this).find('input[name=password]').val();
        let url = $(this).attr('action');

        $.post(url, {login: log, password: pass}, (answer) => {
            if(answer.success) {
                location.reload();
                return;
            }
            showAjaxValidateError(answer);
        })
    }

    function openDepartment () {
        if(isOpened) {
            return;
        }
        let url = $(this).attr('data-url');

        $.get(url, (html) => {
            $(this).append(html);
            setTimeout(() => {
                $(this).find('.departments__content').addClass('visible');
            },200)
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

        $.get(url, (html) => {
            if($(html).html() === 'No records') return false;
            $(this).closest('.departments__content').append(html);
            $('.departments__content').find('.subordinate:last-child').attr('data-hierarchy', +hierarchy + 1);

        })
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

    function dropDraggableItem (e) {
        clearTimeout(timer);
        if(draggable) {
            setTimeout(() => {
                draggable = false;
            },10);
            // hide to get item under
            dragItem.css({'display':'none'});

            let director = $(document.elementFromPoint(e.clientX, e.clientY)).closest('.subordinate__item');

            dragItem.css({'display':'block'});
            //return item to start place if that dropped not on subordinate
            if(director.length === 0) {
                setTimeout(()=> {
                    placeItemBack($(dragItem));
                },10);
            } else changeDirector(director, dragItem);
        }
    }

    function changeDirector (newDirector, subordinate) {
        let newBossHash = getHashFromUrl($(newDirector).attr('data-url'));
        let employeeHash = getHashFromUrl($(subordinate).attr('data-url'));
        let ajaxUrl = $('.departments').attr('data-rewrite-boss-employee');

        $.post(ajaxUrl, {newBoss: newBossHash, employee: employeeHash}, (result) => {
            if(result.success){
                $(dragItem).remove();
                alert('good!');
                $(newDirector).trigger('click');
            } else { //error
                placeItemBack($(item));
                showAjaxValidateError(result);
            }
        })
    }

    function placeItemBack (item) {
        item.css({
            'position':'relative',
            'left':0,
            'top':0
        });
        alert('Сотрудника можно переместить только на его будущего босса !!!');
    }
});

function getHashFromUrl (url) {
    url = url.split('/');
    return url[url.length - 1];
}

function showAjaxValidateError (result) {
    let errors = result.errors;
    console.log(result.errors);
    if(errors) {
        let errorMessage = '';
        for (let err in errors) {
            errorMessage += `${err} : ${errors[err]} \n`;
        }
        alert(errorMessage);
    } else {
        alert(result.error);
    }
}