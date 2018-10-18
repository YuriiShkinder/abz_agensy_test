"use strict";

$(document).ready(() => {
    let
        employees = $('.employees'),
        modal = $('.modal'),
        href = location.href,
        departmentHref = href,
        searchFlag = false,
        changeDepartmentFlag = false,
        page = 2,
        lastPage = 50,
        oldLastPage,
        timer;


    $(document).on('keydown', (e) => {
        //redirect on start page on update
        if((e.keyCode === 166 || e.which === 116) || (e.ctrlKey && (e.which === 82 || e.keyCode === 82))) {
            location.href = departmentHref;
        }
    });
    
    employees.on('scroll', loadNewEmployeesItems);

    $('.addUser').on('click', function (e) {
        showAddUserForm.call(this, e);
    });

    $('.modal__close').on('click', () => {
        modal
            .removeClass('modal_active')
            .find('form').remove();
    });

    modal.on('input', 'input[type=search]', function () {
        if ($(this).val().length > 2) {
            findBosses.call(this);
        } else {
            $('.search__boss').removeClass('search__boss_active');
        }
    });

    modal.on('click', '.search__boss p', function () {
        chooseNewBoss.call(this)
    });
    //upload file
    modal.on('change', "input[type='file']", showUploadedImage);

    modal.on('submit', 'form', function (e) {
        e.preventDefault();
        requestAddUserForm.call(this);
    });

    $('#sort').on('change', sortEmployees);

    $('#department').on('change', changeDepartment);

    $('input[type=search]').on('input', search);

    employees.on('click', '.employees__item', function (e) {
        if (e.target.closest('.remove__item')) {
            removeEmployee(e);
        } else {
            refactorEmployeeItem.call(this);
        }
    });

    function loadNewEmployeesItems() {
        if ((this.scrollHeight - parseInt($(this).height())) <= $(this).scrollTop() + 10) {
            setNewLastPage();

            href = location.href.match(/\?/) ? location.href + `&page=${page}` : location.href + `?page=${page}`;


            $.get(href, (result) => {
                employees.append(result);
            });

            page++;
        }
    }

    function chooseNewBoss() {
        $('#bossHash').val($(this).attr('data-hash'));
        modal.find('input[type=search]').val($(this).html());
        $('.search__boss').removeClass('search__boss_active');
    }

    function showAddUserForm(e) {
        e.preventDefault();

        modal.addClass('modal_active');

        let url = changeDepartmentInUrl.call(this, $(this).attr('href'));

        $.get(url, (result) => {
            $('.modal').append(result);
        })
    }

    function findBosses() {
        let url = $(this).attr('data-url') + `?value=${$(this).val()}`;

        $.get(url, function (result) {
            let sb = $('.search__boss');
            sb.find('p').remove();
            sb.append(result);

        });
        $('.search__boss').addClass('search__boss_active');
    }

    function changeDepartmentInUrl(url) {
        let newUrl = url.split('/');
        newUrl[newUrl.length - 1] = '';
        return newUrl.join('/') + getHashFromUrl(departmentHref);
    }

    function requestAddUserForm() {

        let url = $(this).attr('action');

        let data = new FormData(this);

        $.ajax({
            url: url,
            type: "POST",
            data: data,
            cache: false,
            contentType:false,
            processData: false,
            success: (result) => {
                if (result.success) {
                    alert('good!');
                    $('.modal').removeClass('modal_active').find('form').remove();
                    this.reset();
                } else { //error
                    showAjaxValidateError(result);
                    }
                }
        })
    }

    function showUploadedImage() {
        let file = this.files[0];

        if (file.type.match(/image/)) {
            let image = $('.upload_image');
            $('.upload_image_container').html('');

            image.addClass('upload_image__active');

            let reader = new FileReader();

            reader.readAsDataURL(file);

            reader.onload = (function () {
                setTimeout(() => {
                    if($('.modal form').hasClass('employeeEdit')) {
                        let oldSrc = `<input type="hidden" name="old" value="${image.attr('src')}">`;
                        $('.file__upload').append(oldSrc);
                    }

                    image.attr('src', reader.result);
                }, 100)

            })(file);

        } else {
            $('.upload_image').removeClass('upload_image__active').attr('src', '');
            $('.upload_image_container').html('THIS IS NOT IMAGE');
        }
    }

    function sortEmployees() {
        clearPagination();

        let order = $('.order');
        let orderBy = order.find('input:checked').val();
        let field = this.value;

        history.pushState('', '', departmentHref);
        //form new location.href
        let newStr = order.attr('data-url').split('/');
        newStr[newStr.length - 1] = getHashFromUrl(location.href).replace(/\?+/, '');
        newStr = newStr.join('/');

        href = location.href;

        history.pushState('', '', newStr + `?field=${field}&orderBy=${orderBy}`);

        employees.scrollTop(0);

        loadDepartmentAjax(location.href);

        page = 2;
    }

    function changeDepartment() {
        clearPagination();
        page = 2;
        history.pushState('', '', this.value);
        href = departmentHref = location.href;
        changeDepartmentFlag = true;
        employees.scrollTop(0);
        loadDepartmentAjax(href);
    }

    function search() {
        clearTimeout(timer);
        timer = setTimeout(() => {
            if ($(this).val().length > 2 && $('.search__form').find('select').val()) {
                clearPagination();
                page = 2;
                $('.employees__onload').addClass('employees__onload_active');
                searchFlag = true;

                let url = $(this).closest('form').attr('action');
                let field = $('select[name=field]').val();
                let value = $(this).val();

                history.pushState('', '', url + `?field=${field}&value=${value}`);

                $.get(location.href, (result) => searchAjaxSuccess(result));

            } else if (searchFlag) showOldEmployeesItems();//if we use search before
        }, 300);
    }

    function clearPagination() {
        $('.paginationPages').remove();
    }

    function loadDepartmentAjax(href) {
        $.get(href, (result) => {
            $('.employees__item').remove();
            employees.append(result);
            setNewLastPage();
        });
    }

    function showOldEmployeesItems() {
        $('.employees__item:visible').remove();
        setTimeout(() => {
            $('.employees__item').css({'display': 'flex'});
        }, 100);
        searchFlag = false;
        lastPage = oldLastPage;
        page = 2;
    }

    function searchAjaxSuccess(result) {
        $('.employees__item').css({'display': 'none'});
        employees.append(result)
            .find('.employees__onload')
            .removeClass('employees__onload_active');
        oldLastPage = lastPage;
        setNewLastPage();
    }

    function setNewLastPage() {
        let lp = $('#lastPage');
        lastPage = lp.val();
        lp.remove();
    }


    function removeEmployee(e) {
        let url = $(e.target).closest('.remove__item').attr('data-url');
        $.ajax({
            url: url,
            type: "DELETE",
            success: (result) => {
                let {success} = result;
                if(success) {
                    alert('good!');
                    // location.reload()
                } else {
                    alert('Это босс отдела, не трожь, он сожрет тебя');
                }
            }
        })
    }

    function refactorEmployeeItem() {
        modal.addClass('modal_active');

        let url = $(this).attr('data-url');

        $.get(url, function (result) {
            modal.append(result).addClass('modal_active');
        })
    }


});