
/*
 * Gritter for jQuery
 * http://www.boedesign.com/
 *
 * Copyright (c) 2012 Jordan Boesch
 * Dual licensed under the MIT and GPL licenses.
 *
 * Date: February 24, 2012
 * Version: 1.7.4
 */

(function($){

    /**
     * Set it up as an object under the jQuery namespace
     */
    $.gritter = {};

    /**
     * Set up global options that the user can over-ride
     */
    $.gritter.options = {
        position: '',
        class_name: '', // could be set to 'gritter-light' to use white notifications
        fade_in_speed: 'medium', // how fast notifications fade in
        fade_out_speed: 1000, // how fast the notices fade out
        time: 6000 // hang on the screen for...
    }

    /**
     * Add a gritter notification to the screen
     * @see Gritter#add();
     */
    $.gritter.add = function(params){

        try {
            return Gritter.add(params || {});
        } catch(e) {

            var err = 'Gritter Error: ' + e;
            (typeof(console) != 'undefined' && console.error) ?
                console.error(err, params) :
                alert(err);

        }

    }

    /**
     * Remove a gritter notification from the screen
     * @see Gritter#removeSpecific();
     */
    $.gritter.remove = function(id, params){
        Gritter.removeSpecific(id, params || {});
    }

    /**
     * Remove all notifications
     * @see Gritter#stop();
     */
    $.gritter.removeAll = function(params){
        Gritter.stop(params || {});
    }

    /**
     * Big fat Gritter object
     * @constructor (not really since its object literal)
     */
    var Gritter = {

        // Public - options to over-ride with $.gritter.options in "add"
        position: '',
        fade_in_speed: '',
        fade_out_speed: '',
        time: '',

        // Private - no touchy the private parts
        _custom_timer: 0,
        _item_count: 0,
        _is_setup: 0,
        _tpl_close: '<a class="gritter-close" href="#" tabindex="1">Close Notification</a>',
        _tpl_title: '<span class="gritter-title">[[title]]</span>',
        _tpl_item: '<div id="gritter-item-[[number]]" class="gritter-item-wrapper [[item_class]]" style="display:none" role="alert"><div class="gritter-top"></div><div class="gritter-item">[[close]][[image]]<div class="[[class_name]]">[[title]]<p>[[text]]</p></div><div style="clear:both"></div></div><div class="gritter-bottom"></div></div>',
        _tpl_wrap: '<div id="gritter-notice-wrapper"></div>',

        /**
         * Add a gritter notification to the screen
         * @param {Object} params The object that contains all the options for drawing the notification
         * @return {Integer} The specific numeric id to that gritter notification
         */
        add: function(params){
            // Handle straight text
            if(typeof(params) == 'string'){
                params = {text:params};
            }

            // We might have some issues if we don't have a title or text!
            if(params.text === null){
                throw 'You must supply "text" parameter.';
            }

            // Check the options and set them once
            if(!this._is_setup){
                this._runSetup();
            }

            // Basics
            var title = params.title,
                text = params.text,
                image = params.image || '',
                sticky = params.sticky || false,
                item_class = params.class_name || $.gritter.options.class_name,
                position = $.gritter.options.position,
                time_alive = params.time || '';

            this._verifyWrapper();

            this._item_count++;
            var number = this._item_count,
                tmp = this._tpl_item;

            // Assign callbacks
            $(['before_open', 'after_open', 'before_close', 'after_close']).each(function(i, val){
                Gritter['_' + val + '_' + number] = ($.isFunction(params[val])) ? params[val] : function(){}
            });

            // Reset
            this._custom_timer = 0;

            // A custom fade time set
            if(time_alive){
                this._custom_timer = time_alive;
            }

            var image_str = (image != '') ? '<img src="' + image + '" class="gritter-image" />' : '',
                class_name = (image != '') ? 'gritter-with-image' : 'gritter-without-image';

            // String replacements on the template
            if(title){
                title = this._str_replace('[[title]]',title,this._tpl_title);
            }else{
                title = '';
            }

            tmp = this._str_replace(
                ['[[title]]', '[[text]]', '[[close]]', '[[image]]', '[[number]]', '[[class_name]]', '[[item_class]]'],
                [title, text, this._tpl_close, image_str, this._item_count, class_name, item_class], tmp
            );

            // If it's false, don't show another gritter message
            if(this['_before_open_' + number]() === false){
                return false;
            }

            $('#gritter-notice-wrapper').addClass(position).append(tmp);

            var item = $('#gritter-item-' + this._item_count);

            item.fadeIn(this.fade_in_speed, function(){
                Gritter['_after_open_' + number]($(this));
            });

            if(!sticky){
                this._setFadeTimer(item, number);
            }

            // Bind the hover/unhover states
            $(item).bind('mouseenter mouseleave', function(event){
                if(event.type == 'mouseenter'){
                    if(!sticky){
                        Gritter._restoreItemIfFading($(this), number);
                    }
                }
                else {
                    if(!sticky){
                        Gritter._setFadeTimer($(this), number);
                    }
                }
                Gritter._hoverState($(this), event.type);
            });

            // Clicking (X) makes the perdy thing close
            $(item).find('.gritter-close').click(function(){
                Gritter.removeSpecific(number, {}, null, true);
                return false;
            });

            return number;

        },

        /**
         * If we don't have any more gritter notifications, get rid of the wrapper using this check
         * @private
         * @param {Integer} unique_id The ID of the element that was just deleted, use it for a callback
         * @param {Object} e The jQuery element that we're going to perform the remove() action on
         * @param {Boolean} manual_close Did we close the gritter dialog with the (X) button
         */
        _countRemoveWrapper: function(unique_id, e, manual_close){

            // Remove it then run the callback function
            e.remove();
            this['_after_close_' + unique_id](e, manual_close);

            // Check if the wrapper is empty, if it is.. remove the wrapper
            if($('.gritter-item-wrapper').length == 0){
                $('#gritter-notice-wrapper').remove();
            }

        },

        /**
         * Fade out an element after it's been on the screen for x amount of time
         * @private
         * @param {Object} e The jQuery element to get rid of
         * @param {Integer} unique_id The id of the element to remove
         * @param {Object} params An optional list of params to set fade speeds etc.
         * @param {Boolean} unbind_events Unbind the mouseenter/mouseleave events if they click (X)
         */
        _fade: function(e, unique_id, params, unbind_events){

            var params = params || {},
                fade = (typeof(params.fade) != 'undefined') ? params.fade : true,
                fade_out_speed = params.speed || this.fade_out_speed,
                manual_close = unbind_events;

            this['_before_close_' + unique_id](e, manual_close);

            // If this is true, then we are coming from clicking the (X)
            if(unbind_events){
                e.unbind('mouseenter mouseleave');
            }

            // Fade it out or remove it
            if(fade){

                e.animate({
                    opacity: 0
                }, fade_out_speed, function(){
                    e.animate({ height: 0 }, 300, function(){
                        Gritter._countRemoveWrapper(unique_id, e, manual_close);
                    })
                })

            }
            else {

                this._countRemoveWrapper(unique_id, e);

            }

        },

        /**
         * Perform actions based on the type of bind (mouseenter, mouseleave)
         * @private
         * @param {Object} e The jQuery element
         * @param {String} type The type of action we're performing: mouseenter or mouseleave
         */
        _hoverState: function(e, type){

            // Change the border styles and add the (X) close button when you hover
            if(type == 'mouseenter'){

                e.addClass('hover');

                // Show close button
                e.find('.gritter-close').show();

            }
            // Remove the border styles and hide (X) close button when you mouse out
            else {

                e.removeClass('hover');

                // Hide close button
                e.find('.gritter-close').hide();

            }

        },

        /**
         * Remove a specific notification based on an ID
         * @param {Integer} unique_id The ID used to delete a specific notification
         * @param {Object} params A set of options passed in to determine how to get rid of it
         * @param {Object} e The jQuery element that we're "fading" then removing
         * @param {Boolean} unbind_events If we clicked on the (X) we set this to true to unbind mouseenter/mouseleave
         */
        removeSpecific: function(unique_id, params, e, unbind_events){

            if(!e){
                var e = $('#gritter-item-' + unique_id);
            }

            // We set the fourth param to let the _fade function know to
            // unbind the "mouseleave" event.  Once you click (X) there's no going back!
            this._fade(e, unique_id, params || {}, unbind_events);

        },

        /**
         * If the item is fading out and we hover over it, restore it!
         * @private
         * @param {Object} e The HTML element to remove
         * @param {Integer} unique_id The ID of the element
         */
        _restoreItemIfFading: function(e, unique_id){

            clearTimeout(this['_int_id_' + unique_id]);
            e.stop().css({ opacity: '', height: '' });

        },

        /**
         * Setup the global options - only once
         * @private
         */
        _runSetup: function(){

            for(opt in $.gritter.options){
                this[opt] = $.gritter.options[opt];
            }
            this._is_setup = 1;

        },

        /**
         * Set the notification to fade out after a certain amount of time
         * @private
         * @param {Object} item The HTML element we're dealing with
         * @param {Integer} unique_id The ID of the element
         */
        _setFadeTimer: function(e, unique_id){

            var timer_str = (this._custom_timer) ? this._custom_timer : this.time;
            this['_int_id_' + unique_id] = setTimeout(function(){
                Gritter._fade(e, unique_id);
            }, timer_str);

        },

        /**
         * Bring everything to a halt
         * @param {Object} params A list of callback functions to pass when all notifications are removed
         */
        stop: function(params){

            // callbacks (if passed)
            var before_close = ($.isFunction(params.before_close)) ? params.before_close : function(){};
            var after_close = ($.isFunction(params.after_close)) ? params.after_close : function(){};

            var wrap = $('#gritter-notice-wrapper');
            before_close(wrap);
            wrap.fadeOut(function(){
                $(this).remove();
                after_close();
            });

        },

        /**
         * An extremely handy PHP function ported to JS, works well for templating
         * @private
         * @param {String/Array} search A list of things to search for
         * @param {String/Array} replace A list of things to replace the searches with
         * @return {String} sa The output
         */
        _str_replace: function(search, replace, subject, count){

            var i = 0, j = 0, temp = '', repl = '', sl = 0, fl = 0,
                f = [].concat(search),
                r = [].concat(replace),
                s = subject,
                ra = r instanceof Array, sa = s instanceof Array;
            s = [].concat(s);

            if(count){
                this.window[count] = 0;
            }

            for(i = 0, sl = s.length; i < sl; i++){

                if(s[i] === ''){
                    continue;
                }

                for (j = 0, fl = f.length; j < fl; j++){

                    temp = s[i] + '';
                    repl = ra ? (r[j] !== undefined ? r[j] : '') : r[0];
                    s[i] = (temp).split(f[j]).join(repl);

                    if(count && s[i] !== temp){
                        this.window[count] += (temp.length-s[i].length) / f[j].length;
                    }

                }
            }

            return sa ? s : s[0];

        },

        /**
         * A check to make sure we have something to wrap our notices with
         * @private
         */
        _verifyWrapper: function(){

            if($('#gritter-notice-wrapper').length == 0){
                $('body').append(this._tpl_wrap);
            }

        }

    }

})(jQuery);

/*
 Masked Input plugin for jQuery
 Copyright (c) 2007-2013 Josh Bush (digitalbush.com)
 Licensed under the MIT license (http://digitalbush.com/projects/masked-input-plugin/#license)
 Version: 1.3.1
 */
(function($) {
    function getPasteEvent() {
        var el = document.createElement('input'),
            name = 'onpaste';
        el.setAttribute(name, '');
        return (typeof el[name] === 'function')?'paste':'input';
    }

    var pasteEventName = getPasteEvent() + ".mask",
        ua = navigator.userAgent,
        iPhone = /iphone/i.test(ua),
        android=/android/i.test(ua),
        caretTimeoutId;

    $.mask = {
        //Predefined character definitions
        definitions: {
            '9': "[0-9]",
            'a': "[A-Za-z]",
            '*': "[A-Za-z0-9]"
        },
        dataName: "rawMaskFn",
        placeholder: '_',
    };

    $.fn.extend({
        //Helper Function for Caret positioning
        caret: function(begin, end) {
            var range;

            if (this.length === 0 || this.is(":hidden")) {
                return;
            }

            if (typeof begin == 'number') {
                end = (typeof end === 'number') ? end : begin;
                return this.each(function() {
                    if (this.setSelectionRange) {
                        this.setSelectionRange(begin, end);
                    } else if (this.createTextRange) {
                        range = this.createTextRange();
                        range.collapse(true);
                        range.moveEnd('character', end);
                        range.moveStart('character', begin);
                        range.select();
                    }
                });
            } else {
                if (this[0].setSelectionRange) {
                    begin = this[0].selectionStart;
                    end = this[0].selectionEnd;
                } else if (document.selection && document.selection.createRange) {
                    range = document.selection.createRange();
                    begin = 0 - range.duplicate().moveStart('character', -100000);
                    end = begin + range.text.length;
                }
                return { begin: begin, end: end };
            }
        },
        unmask: function() {
            return this.trigger("unmask");
        },
        mask: function(mask, settings) {
            var input,
                defs,
                tests,
                partialPosition,
                firstNonMaskPos,
                len;

            if (!mask && this.length > 0) {
                input = $(this[0]);
                return input.data($.mask.dataName)();
            }
            settings = $.extend({
                placeholder: $.mask.placeholder, // Load default placeholder
                completed: null
            }, settings);


            defs = $.mask.definitions;
            tests = [];
            partialPosition = len = mask.length;
            firstNonMaskPos = null;

            $.each(mask.split(""), function(i, c) {
                if (c == '?') {
                    len--;
                    partialPosition = i;
                } else if (defs[c]) {
                    tests.push(new RegExp(defs[c]));
                    if (firstNonMaskPos === null) {
                        firstNonMaskPos = tests.length - 1;
                    }
                } else {
                    tests.push(null);
                }
            });

            return this.trigger("unmask").each(function() {
                var input = $(this),
                    buffer = $.map(
                        mask.split(""),
                        function(c, i) {
                            if (c != '?') {
                                return defs[c] ? settings.placeholder : c;
                            }
                        }),
                    focusText = input.val();

                function seekNext(pos) {
                    while (++pos < len && !tests[pos]);
                    return pos;
                }

                function seekPrev(pos) {
                    while (--pos >= 0 && !tests[pos]);
                    return pos;
                }

                function shiftL(begin,end) {
                    var i,
                        j;

                    if (begin<0) {
                        return;
                    }

                    for (i = begin, j = seekNext(end); i < len; i++) {
                        if (tests[i]) {
                            if (j < len && tests[i].test(buffer[j])) {
                                buffer[i] = buffer[j];
                                buffer[j] = settings.placeholder;
                            } else {
                                break;
                            }

                            j = seekNext(j);
                        }
                    }
                    writeBuffer();
                    input.caret(Math.max(firstNonMaskPos, begin));
                }

                function shiftR(pos) {
                    var i,
                        c,
                        j,
                        t;

                    for (i = pos, c = settings.placeholder; i < len; i++) {
                        if (tests[i]) {
                            j = seekNext(i);
                            t = buffer[i];
                            buffer[i] = c;
                            if (j < len && tests[j].test(t)) {
                                c = t;
                            } else {
                                break;
                            }
                        }
                    }
                }

                function keydownEvent(e) {
                    var k = e.which,
                        pos,
                        begin,
                        end;

                    //backspace, delete, and escape get special treatment
                    if (k === 8 || k === 46 || (iPhone && k === 127)) {
                        pos = input.caret();
                        begin = pos.begin;
                        end = pos.end;

                        if (end - begin === 0) {
                            begin=k!==46?seekPrev(begin):(end=seekNext(begin-1));
                            end=k===46?seekNext(end):end;
                        }
                        clearBuffer(begin, end);
                        shiftL(begin, end - 1);

                        e.preventDefault();
                    } else if (k == 27) {//escape
                        input.val(focusText);
                        input.caret(0, checkVal());
                        e.preventDefault();
                    }
                }

                function keypressEvent(e) {
                    var k = e.which,
                        pos = input.caret(),
                        p,
                        c,
                        next;

                    if (e.ctrlKey || e.altKey || e.metaKey || k < 32) {//Ignore
                        return;
                    } else if (k) {
                        if (pos.end - pos.begin !== 0){
                            clearBuffer(pos.begin, pos.end);
                            shiftL(pos.begin, pos.end-1);
                        }

                        p = seekNext(pos.begin - 1);
                        if (p < len) {
                            c = String.fromCharCode(k);
                            if (tests[p].test(c)) {
                                shiftR(p);

                                buffer[p] = c;
                                writeBuffer();
                                next = seekNext(p);

                                if(android){
                                    setTimeout($.proxy($.fn.caret,input,next),0);
                                }else{
                                    input.caret(next);
                                }

                                if (settings.completed && next >= len) {
                                    settings.completed.call(input);
                                }
                            }
                        }
                        e.preventDefault();
                    }
                }

                function clearBuffer(start, end) {
                    var i;
                    for (i = start; i < end && i < len; i++) {
                        if (tests[i]) {
                            buffer[i] = settings.placeholder;
                        }
                    }
                }

                function writeBuffer() { input.val(buffer.join('')); }

                function checkVal(allow) {
                    //try to place characters where they belong
                    var test = input.val(),
                        lastMatch = -1,
                        i,
                        c;

                    for (i = 0, pos = 0; i < len; i++) {
                        if (tests[i]) {
                            buffer[i] = settings.placeholder;
                            while (pos++ < test.length) {
                                c = test.charAt(pos - 1);
                                if (tests[i].test(c)) {
                                    buffer[i] = c;
                                    lastMatch = i;
                                    break;
                                }
                            }
                            if (pos > test.length) {
                                break;
                            }
                        } else if (buffer[i] === test.charAt(pos) && i !== partialPosition) {
                            pos++;
                            lastMatch = i;
                        }
                    }
                    if (allow) {
                        writeBuffer();
                    } else if (lastMatch + 1 < partialPosition) {
                        input.val("");
                        clearBuffer(0, len);
                    } else {
                        writeBuffer();
                        input.val(input.val().substring(0, lastMatch + 1));
                    }
                    return (partialPosition ? i : firstNonMaskPos);
                }

                input.data($.mask.dataName,function(){
                    return $.map(buffer, function(c, i) {
                        return tests[i]&&c!=settings.placeholder ? c : null;
                    }).join('');
                });

                if (!input.attr("readonly"))
                    input
                        .one("unmask", function() {
                            input
                                .unbind(".mask")
                                .removeData($.mask.dataName);
                        })
                        .bind("focus.mask", function() {
                            clearTimeout(caretTimeoutId);
                            var pos,
                                moveCaret;

                            focusText = input.val();
                            pos = checkVal();

                            caretTimeoutId = setTimeout(function(){
                                writeBuffer();
                                if (pos == mask.length) {
                                    input.caret(0, pos);
                                } else {
                                    input.caret(pos);
                                }
                            }, 10);
                        })
                        .bind("blur.mask", function() {
                            checkVal();
                            if (input.val() != focusText)
                                input.change();
                        })
                        .bind("keydown.mask", keydownEvent)
                        .bind("keypress.mask", keypressEvent)
                        .bind(pasteEventName, function() {
                            setTimeout(function() {
                                var pos=checkVal(true);
                                input.caret(pos);
                                if (settings.completed && pos == input.val().length)
                                    settings.completed.call(input);
                            }, 0);
                        });
                checkVal(); //Perform initial check for existing values
            });
        }
    });


})(jQuery);

function showError(message){
    $('#gritter-notice-wrapper').remove();
    $.gritter.add({
        title: 'Ошибка',
        text: message
    });
    return false;
}

function showMessage(message){
    $.gritter.add({
        title: 'Успех',
        text: message,
        class_name: 'success-gritter'
    });
    return false;
}

$(function() {
    $(".phone-mask").mask("+7(999)999-99-99");
});

function addRequest() {
    $('.ajax-loader').fadeIn(100);
    $.ajax({
        url:'/ajax/request',
        type: 'POST',
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        data: {
            user_name: $('#user_name_request').val(),
            email: $('#email_request').val(),
            comment: $('#comment').val(),
            company_name: $('#company_name').val(),
            phone: $('#phone_request').val()
        },
        success: function (data) {
            $('.ajax-loader').fadeOut(100);
            if(data.status == 0){
                showError(data.error);
                return;
            }
            showMessage(data.message);
            $('#user_name_request').val('');
            $('#phone_request').val('');
        }
    });
}

function addRequestCorporate() {
    if(!$('#filled-in-box').is(':checked')){
        showError('Чтобы оставить заявку Вам следует подтвердить согласие на сбор данных');
        return;
    }

    $('.ajax-loader').fadeIn(100);
    $.ajax({
        url:'/ajax/request/corporate',
        type: 'POST',
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        data: {
            user_name: $('#user_name').val(),
            email: $('#email').val(),
            company_name: $('#company_name').val(),
            phone: $('#phone').val(),
            menu_id: $('#menu_id').val()
        },
        success: function (data) {
            $('.ajax-loader').fadeOut(100);
            if(data.status == 0){
                showError(data.error);
                return;
            }
            showMessage(data.message);
            $('#user_name').val('');
            $('#phone').val('');
            $('#company_name').val('');
            $('#email').val('');
        }
    });
}

function sendSMS() {
    $('.ajax-loader').fadeIn(100);
    $.ajax({
        url:'/ajax/sms',
        type: 'POST',
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        success: function (data) {
            $('#sms_label').fadeIn(0);
            $('#send_sms_repeat').html('');
            $('.ajax-loader').fadeOut(100);
            $('#timer_inp').html('30');
            timerSms();
            if(data.status == 0){
                showError(data.error);
                return;
            }
        }
    });
}

function sendSMSReset() {
    $('.ajax-loader').fadeIn(100);
    $.ajax({
        url:'/ajax/sms',
        type: 'POST',
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        data:{
            phone: $('#phone').val()
        },
        success: function (data) {
            $('#sms_label').fadeIn(0);
            $('#send_sms_repeat').html('');
            $('.ajax-loader').fadeOut(100);
            $('#timer_inp').html('30');
            timerSms();
            if(data.status == 0){
                showError(data.error);
                return;
            }
        }
    });
}


function focusRequest(){
    $('html,body').animate({
        scrollTop: $("#company_name").offset().top - 90
    }, 'slow');
    $('#company_name').focus();
}

g_vacancy_id = 0;

function showVacancyModal(vacancy_id){
    g_vacancy_id = vacancy_id;
    $('#modal-vacancy').modal('show');
}

function uploadDocument(){
    $('#upload_document').submit();
}

var g_file_name = '';
var g_file_size = '';
var g_file_url = '';
var g_document_type = '';

$("#upload_document").submit(function(event) {
    $('.ajax-loader').fadeIn();
    event.preventDefault();
    var formData = new FormData($(this)[0]);

    $.ajax({
        url:'/image/upload/file',
        type: 'POST',
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        data: formData,
        async: false,
        cache: false,
        contentType: false,
        processData: false,
        success: function (data) {
            if(data.success == 0){
                showError(data.error);
                $('.ajax-loader').css('display','none');
                return;
            }
            g_file_name = data.file_name;
            g_file_size = data.file_size;
            g_file_url = data.file_url;

            getDocumentVacancyList();
        }
    });
});

function uploadInsuranceDocument(document_type_id){
    $('#upload_document_' + document_type_id).submit();
}


function getDocumentVacancyList(){
    $.ajax({
        url:'/ajax/vacancy/document',
        type: 'POST',
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        data: {
            file_name: g_file_name,
            file_size: g_file_size,
            file_url: g_file_url
        },
        success: function (data) {
            $('#file_list').append(data);
            $('.ajax-loader').css('display','none');
        }
    });
}

function getInsuranceDocumentList(){
    $.ajax({
        url:'/ajax/insurance/document',
        type: 'POST',
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        data: {
            file_name: g_file_name,
            file_size: g_file_size,
            document_type: g_document_type,
            file_url: g_file_url
        },
        success: function (data) {
            $('#file_list_' + g_document_type).append(data);
            $('.ajax-loader').css('display','none');
        }
    });
}


function deleteDocument(ob){
    $(ob).closest('.doc-upload__file').remove();
}

function addVacancyRequest() {
    $('.ajax-loader').fadeIn(100);

    var file_name = [];
    $('.file_name_input').each(function(){
        file_name.push($(this).html());
    });

    var file_size = [];
    $('.file_size_input').each(function(){
        file_size.push($(this).html());
    });

    var file_url = [];
    $('.file_url_input').each(function(){
        file_url.push($(this).val());
    });

    $.ajax({
        url:'/ajax/request/vacancy',
        type: 'POST',
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        data: {
            user_name: $('#user_name_vacancy').val(),
            email: $('#email_vacancy').val(),
            phone: $('#phone_vacancy').val(),
            vacancy_id: g_vacancy_id,
            file_sizes: file_size,
            file_names: file_name,
            file_urls: file_url
        },
        success: function (data) {
            $('.ajax-loader').fadeOut(100);
            if(data.status == 0){
                showError(data.error);
                return;
            }
            $('#modal-vacancy').modal('hide');
            $('#modal-success').modal('show');
        }
    });
}

$('.jsTimepicker1').timepicker({
    timeFormat: 'HH:mm',
    interval: 60,
    startTime: '07:00',
    dynamic: false,
    dropdown: true,
    scrollbar: true
});

function addInsuranceRequest() {
    $('.ajax-loader').fadeIn(100);

    var car_policy_list = [];
    $('.car_policy_list').each(function(){
        car_policy_list.push($(this).val());
    });

    var file_name = [];
    $('.file_name_input').each(function(){
        file_name.push($(this).html());
    });

    var file_size = [];
    $('.file_size_input').each(function(){
        file_size.push($(this).html());
    });

    var file_url = [];
    $('.file_url_input').each(function(){
        file_url.push($(this).val());
    });

    var document_type = [];
    $('.document_type_input').each(function(){
        document_type.push($(this).val());
    });

    $.ajax({
        url:'/ajax/request/insurance',
        type: 'POST',
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        data: {
            last_name: $('#last_name').val(),
            first_name: $('#first_name').val(),
            policy_number: $('#policy_number').val(),
            policy_date: $('#policy_date').val(),
            policy_time: $('#policy_time').val(),
            phone: $('#phone').val(),
            city_id: $('#city_id').val(),
            causer_name: $('#causer_name').val(),
            situation_desc: $('#situation_desc').val(),
            is_need_evacuator:  $('.is_need_evacuator:checked').val(),
            car_policy_list: car_policy_list,
            file_sizes: file_size,
            file_names: file_name,
            document_types: document_type,
            file_urls: file_url
        },
        success: function (data) {
            $('.ajax-loader').fadeOut(100);
            if(data.status == 0){
                showError(data.error);
                return;
            }

            $('.doc-upload__files').html('');
            $('#last_name').val('');
            $('#first_name').val('');
            $('#policy_number').val('');
            $('#policy_date').val('');
            $('#policy_time').val('');
            $('#causer_name').val('');
            $('#situation_desc').val('');

            $('#modal-success').modal('show');
        }
    });
}


function showBuyModal(){
    $('#modal-buy-policy').modal('show');
}

function showCheckValidateOGPOModal(){
    $('#modal-check-ogpo-policy').modal('show');
}

function redirectToCalculator(){
    window.location.href = $('#policy_kind').val();
}

function setFocusRequest(){
    $('html,body').animate({
        scrollTop: $("#request_form").offset().top
    }, 'slow');
    $('#user_name_request').focus();

}

function checkValidatePolicy(){
    $('.ajax-loader').fadeIn();
    $.ajax({
        url:'/ajax/check-validate-policy',
        type: 'POST',
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        data: {
            iin: $('#g_iin').val()
        },
        success: function (data) {
            $('.ajax-loader').fadeOut();
            if(data.status == 0){
                showError(data.error);
                return;
            }
            else {
                $('#validate_message').html(data.message);
                $('#modal-check-ogpo-policy').modal('hide');
                $('#modal-validate-success').modal('show');
            }
        }
    });
}

