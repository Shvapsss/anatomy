/**
 * @author dev@dump.com.ua Андрій
 */
        
$(function () {
    var formForNew = '<textarea></textarea>'
            + '<a class="btn btn-primary create">Добавить</a>',
            formForEdit = function () {
                var li = $(this).parent();
                var act = '<a class="btn btn-default activate">' + ($(li).hasClass('inactive') ? 'Включить' : 'Отключить') + '</a>';
                var clo = $(this).closest('#tests');
                if(clo[0] !== undefined){
                    act += '<a class="btn btn-success pro">' + ($(li).find('.fa-rub') !== undefined ? '<i class="fa fa-rub active"></i>' : '<i class="fa fa-rub"></i>') + '</a>';
                }
                return '<textarea>' + $(li).find('.title').text() + '</textarea>'
                        + '<div class="btn-group" data-toggle="buttons">'
                        + '<a class="btn btn-primary edit">Сохранить</a>'
                        + act + '<a class="btn btn-warning delete">Удалить</a>'
                        + '</div>';
            },
            closeTooltip = '<i class="fa fa-times"></i>',
            editorPopoverOptions = {'content': formForEdit, 'html': true, 'placement': 'right', 'title': 'Редактировать' + closeTooltip},
            editorPopoverAnswerOptions = {'content': formForEdit, 'html': true, 'placement': 'left', 'title': 'Редактировать' + closeTooltip},
            activeTestId = 0,
            activeQuestionId = 0,
            imgDivDefault = '<div class="sh"><img src="files/_empty_image.png" /></div>',
            pdfDivDefault = '<div class="sh"><img src="files/_empty_pdf.png" /></div>';

    $('.fa-plus').popover({'content': formForNew, 'html': true, 'placement': 'bottom', 'title': 'Новый ' + closeTooltip});

    $('#tests .fa-pencil').popover(editorPopoverOptions);
    
    $('#resultsTextModal').on('click','.btn.btn-primary',function(){
        $.post('test/resultsText', $(this).closest('form').serialize()).done(function(data){
            $('#resultsTextModal .modal-body').html(data);
        });
    });


    /* tests */
    // создаём новый тест
    $('#tests').on('click', '.create', function () {
        $.post('test/create', {'title': $(this).prev().val()}).done(function (data) {
            var re = noError(data);
            if (re) {
                $('#tests ul').prepend(re.html);
                $('body').find('#tests [data-id="' + re.status + '"] .fa-pencil').popover(editorPopoverOptions);
                $('#tests .fa-plus').popover('toggle');
            }
        });

        // убираем popover
    }).on('click', '.activate', function () {
        tActivate(this, 'test');

        // удаляем тест
    }).on('click', '.delete', function () {
        var li = $(this).closest('li');
        if (confirm('Это безвозвратно удалит все вопросы с ответами в этом тесте. Продолжить?')) {
            var id = $(li).data('id');
            $.post('test/delete?id=' + id).done(function (data) {
                var re = noError(data);
                if (re) {
                    $(li).remove();
                    if (activeTestId === id) {
                        $('#questions ul').html('');
                    }
                }
            });
        }

        // редактируем название
    }).on('click', '.edit', function () {
        tEdit(this, 'test');

        // подгрузка списка вопросов
    }).on('click', '.title', function () {
        $('#tests li').removeClass('active');
        $('#text1').redactor('core.destroy').remove();
        $('#text2').redactor('core.destroy').remove();
        $('button.save_redactor').remove();
        var li = $(this).closest('li');
        var id = $(li).data('id');
        if (activeTestId === id) {
            $(li).removeClass('active');
            $('#questions ul').text('');
            $('#answers ul').text('');
            $('.dropzone').css('display', 'none');
            activeTestId = 0;
        } else {
            $('#tests li').removeClass('active');
            $(li).toggleClass('active');
            $('#answers ul').text('');
            $('.dropzone').css('display', 'none');
            activeTestId = $(li).data('id');
            $.post('question/index?id=' + activeTestId).done(function (data) {
                var re = noError(data);
                if (re) {
                    $('#questions ul').html(re.html);
                    $('body').find('#questions .fa-pencil').popover(editorPopoverOptions);
                }
            });
        }
    }).on('click','.pro', function(){
        var li = $(this).closest('li');
        var id = $(li).data('id');
        $.post('test/pro?id=' + id).done(function (data) {
            var re = noError(data);
            if (re) {
                if(re.html){
                    $('<i class="fa fa-rub"></i>').insertAfter($(li).find('.fa-arrows-alt'));
                } else {
                    $(li).find('.fa-rub').remove();
                }
                $(li).find('.fa-pencil').popover('toggle');
            }
        });
    });
    
    var el = document.getElementById('tests_list');
    Sortable.create(el,{
        handle: ".fa-arrows-alt",
        animation: "150",
        onEnd: function (evt) {
            var ar = [];
            $('#tests_list li').each(function( index ) {
                ar.push($(this).data('id'));
            });
            $.post('test/sort',{sorted:ar});
        },
    });



    /* questions */
    $('#questions').on('click', '.edit', function () {
        tEdit(this, 'question');
    }).on('click', '.activate', function () {
        tActivate(this, 'question');
    }).on('click', '.create', function () {
        $.post('question/create', {'title': $(this).prev().val(), 'test_id': activeTestId}).done(function (data) {
            var re = noError(data);
            if (re) {
                $('#questions ul').prepend(re.html);
                $('body').find('#questions [data-id="' + re.status + '"] .fa-pencil').popover(editorPopoverOptions);
                $('#questions .fa-plus').popover('toggle');
            }
        });
    }).on('click', '.fa-plus', function () {
        if (activeTestId === 0) {
            showMessage('Сначала выберите тест, к которому следует добавить вопрос.');
            $('#questions .fa-plus').popover('toggle')
            return false;
        }
    }).on('click', '.delete', function () {
        var li = $(this).closest('li');
        if (confirm('Это безвозвратно удалит вопрос со всеми ответами. Продолжить?')) {
            $.post('question/delete?id=' + $(li).data('id')).done(function (data) {
                var re = noError(data);
                if (re) {
                    $(li).remove();
                }
            });
        }
    }).on('click', '.edit', function () {
        tEdit(this, 'test');

        // подгрузка списка ответов
    }).on('click', '.title', function () {
        $('#text1').redactor('core.destroy').remove();
        $('#text2').redactor('core.destroy').remove();
        $('button.save_redactor').remove();
        $('#question li').removeClass('active');
        var li = $(this).closest('li');
        var id = $(li).data('id');
        if (activeQuestionId === id) {
            $(li).removeClass('active');
            $('#answers ul').text('');
            $('.dropzone').remove();
            activeQuestionId = 0;
        } else {
            $('#questions li').removeClass('active');
            $(li).toggleClass('active');
            activeQuestionId = $(li).data('id');
            $.post('answer/index?id=' + activeQuestionId).done(function (data) {
                var re = noError(data);
                if (re) {
                    $('#answers ul').html(re.html);
                    isFileEmpty(re.data);
                    $('body').find('#answers .fa-pencil').popover(editorPopoverAnswerOptions);
                    var textarea1 = $('<textarea style="height:200px" id="text1" name="Question[text1]">' + re.data.text1 + '</textarea>');
                    $(textarea1).insertAfter($('#answers .list_head'));
                    $(textarea1).redactor({
                        lang: 'ru',
                        plugins: ['table']
                    });
                    var textarea2 = $('<textarea style="height:200px" id="text2" name="Question[text2]">' + re.data.text2 + '</textarea>');
                    $(textarea2).insertBefore($('#answers .main_list'));
                    $(textarea2).redactor({
                        lang: 'ru',
                        plugins: ['table']
                    });
                    $('<button class="btn btn-default btn-xs save_redactor">Сохранить изменения в текстовых редакторах</button>').insertBefore($('#answers .main_list'));
                }
            });
        }

        fd.jQuery();
    });
    
    var elq = document.getElementById('questions_list');
    Sortable.create(elq,{
        handle: ".fa-arrows-alt",
        animation: "150",
        onEnd: function (evt) {
            var ar = [];
            $('#questions_list li').each(function( index ) {
                ar.push($(this).data('id'));
            });
            $.post('question/sort',{sorted:ar});
        },
    });

    $('body').on('click', '.popover .fa-times', function () {
        $(this).closest('.popover').prev().popover('toggle');
    }).on('click','.save_redactor', function(){
        $.ajax({
            url: 'question/redactor',
            type: 'post',
            data: 'id=' + activeQuestionId + '&text1=' + $('#text1').redactor('code.get') + '&text2=' + $('#text2').redactor('code.get')
        });
        return false;
    });



    /* answers */
    $('#answers').on('click', '.edit', function () {
        tEdit(this, 'answer');
    }).on('click', '.activate', function () {
        tActivate(this, 'answer');
    }).on('click', '.create', function () {
        $.post('answer/create', {'title': $(this).prev().val(), 'question_id': activeQuestionId}).done(function (data) {
            var re = noError(data);
            if (re) {
                $('#answers ul.main_list').prepend(re.html);
                $('body').find('#answers [data-id="' + re.status + '"] .fa-pencil').popover(editorPopoverAnswerOptions);
                $('#answers .fa-plus').popover('toggle');
            }
        });
    }).on('click', '.fa-plus', function () {
        if (activeQuestionId === 0) {
            showMessage('Сначала выберите вопрос, к которому хотите добавить ответы.');
            $('#answers .fa-plus').popover('toggle')
            return false;
        }
    }).on('click', '.delete', function () {
        var li = $(this).closest('li');
        if (confirm('Это безвозвратно удалит ответ. Продолжить?')) {
            $.post('answer/delete?id=' + $(li).data('id')).done(function (data) {
                var re = noError(data);
                if (re) {
                    $(li).remove();
                }
            });
        }
    }).on('change', '[type="radio"]', function () {
        $(this).closest('.main_list').find('.fa-dot-circle-o').toggleClass('fa-dot-circle-o fa-circle-o');
        $(this).next().toggleClass('fa-circle-o fa-dot-circle-o');
        $.post('answer/right?id=' + $(this).val() + '&question_id=' + activeQuestionId);
    });
    


    /* functions */
    function isFileEmpty(data){

        $('.dropzone').remove();
        
        $('<div class="dropzone" data-type="question"></div>').insertAfter('#answers .list_head');
        $('<div class="dropzone" data-type="explanation"></div>').insertAfter('#answers .main_list');
        
        if(data.question){
            html = '<div class="sh"><i class="fa fa-times"></i><img src="' + data.question + '" /></div>';
        } else {
            html = imgDivDefault;
        }
        $('[data-type="question"]').html(html)
            .on('fdsend', function (e, files) {
                if(files[0].type === "image/jpeg" || files[0].type === "image/png"){
                    files.invoke('sendTo', 'question/upload?type=question&id=' + activeQuestionId);
                } else {
                    showMessage('Загружайте JPG или PNG');
                }
            })
            .on('filedone', function (e, file, xhr) {
                var re = noError(xhr.response);
                if (re) {
                    $(this).find('.sh').html('<i class="fa fa-times"></i><img src="' + re.html + '" />');
                }
            })
            .on('click','.fa-times', function(){
                if(confirm('Удалить прикреплённый файл?')){
                    $.post('question/unlink',{'src':'question','id':activeQuestionId});
                    $(this).closest('.dropzone').find('.sh').replaceWith(imgDivDefault);
                }
            });
        $('.dropzone[data-type="question"]').filedrop();

        if(data.explanation){
            html = '<div class="sh"><i class="fa fa-times"></i><a target="_blank" href="' + data.explanation + '">' + data.filename + '</a><img src="files/_pdf.png" /></div>';
        } else {
            html = pdfDivDefault;
        }
        $('[data-type="explanation"]').html(html)
            .on('fdsend', function (e, files) {
                console.log(files[0].type); // application/pdf
                if(files[0].type === 'application/pdf'){
                    files.invoke('sendTo', 'question/upload?type=explanation&id='+activeQuestionId+'&filename='+files[0].name);
                } else {
                    showMessage('Только PDF');
                }
            })
            .on('filedone', function (e, file, xhr) {
                var re = noError(xhr.response);
                if (re) {
                    $(this).find('.sh').html('<i class="fa fa-times"></i><a target="_blank" href="' + re.data.explanation + '">' + re.data.filename + '</a><img src="files/_pdf.png" />');
                }
            })
            .on('click','.fa-times', function(){
                if(confirm('Удалить прикреплённый файл?')){
                    $.post('question/unlink',{'src':'explanation','id':activeQuestionId});
                    $(this).closest('.dropzone').find('.sh').replaceWith(pdfDivDefault);
                }
            });
        $('.dropzone[data-type="explanation"]').filedrop();
        
        $('.dropzone').show();
    }


    function tEdit(e, m) {
        var li = $(e).closest('li');
        var title = $(li).find('textarea').val();
        $.post(m + '/edit?id=' + $(li).data('id'), {'title': title}).done(function (data) {
            if (noError(data)) {
                $(li).find('.title').text(title);
                $(li).find('.fa-pencil').popover('toggle');
            }
            ;
        });
    }

    function tActivate(e, m) {
        var li = $(e).closest('li');
        $.post(m + '/activate?id=' + $(li).data('id')).done(function (data) {
            if (noError(data)) {
                $(li).toggleClass('inactive');
                $(li).find('.fa-pencil').popover('toggle');
            }
        });
    }

    function noError(data) {
        var re = $.parseJSON(data);
        if (re.status === 'error') {
            showMessage(re.html);
            return false;
        }
        return re;
    }

    function showMessage(html) {
        if ($('#message:visible')) {
            $('#message').hide();
        }
        $('#message').html(html).show('fast').delay(5000).hide('slow');
    }
    
});


/*!
  FileDrop Revamped - HTML5 & legacy file upload
  in public domain  | http://filedropjs.org
  by Proger_XP      | http://proger.me

  Supports IE 6+, FF 3.6+, Chrome 7+, Safari 5+, Opera 11+.
  Fork & report problems at https://github.com/ProgerXP/FileDrop
*/
;(function(e,t){typeof define=="function"&&define.amd?define(["exports"],function(n){t(e,n)}):typeof exports!="undefined"?t(e,exports):t(e,e.fd=e.fd||{})})(this,function(t,n){n.randomID=function(e){return(e||"fd")+"_"+(Math.random()*1e4).toFixed()},n.uniqueID=function(e){do var t=n.randomID(e);while(n.byID(t));return t},n.byID=function(e){return n.isTag(e)?e:document.getElementById(e)},n.isTag=function(e,t){return typeof e=="object"&&e&&e.nodeType==1&&(!t||e.tagName.toUpperCase()==t.toUpperCase())},n.newXHR=function(){try{return new XMLHttpRequest}catch(e){var t=["MSXML2.XMLHTTP.6.0","MSXML2.XMLHTTP.5.0","MSXML2.XMLHTTP.4.0","MSXML2.XMLHTTP.3.0","MSXML2.XMLHTTP","Microsoft.XMLHTTP"];for(var n=0;n<t.length;n++)try{return new ActiveXObject(t[n])}catch(e){}}throw"Cannot create XMLHttpRequest."},n.isArray=function(e){return Object.prototype.toString.call(e)==="[object Array]"},n.toArray=function(e,t){return e===null||typeof e=="undefined"?[]:(!n.isArray(e)&&(typeof e!="object"||!("callee"in e))&&(e=[e]),Array.prototype.slice.call(e,t||0))},n.addEvent=function(e,t,n){return e&&t&&n&&(e.attachEvent?(e["e"+t+n]=n,e[t+n]=function(){e["e"+t+n](window.event)},e.attachEvent("on"+t,e[t+n])):e.addEventListener(t,n,!1)),e},n.stopEvent=function(e){return e.cancelBubble=!0,e.returnValue=!1,e.stopPropagation&&e.stopPropagation(),e.preventDefault&&e.preventDefault(),e},n.setClass=function(e,t,r){return(e=n.byID(e))&&t!=null&&(typeof r!="undefined"&&!r?e.className=e.className.replace(n.classRegExp(t)," "):n.hasClass(e,t)||(e.className+=" "+t)),e},n.hasClass=function(e,t){return n.classRegExp(t).test((n.byID(e)||{}).className)},n.classRegExp=function(e){return e==""||typeof e=="object"?/$o_O/:new RegExp("(^|\\s+)"+e+"(\\s+|$)","gi")},n.extend=function(e,t,n){e=e||{},t=t||{};for(var r in t)if(n||typeof e[r]=="undefined")e[r]=t[r];return e},n.callAll=function(e,t,r){var i;t=n.toArray(t),typeof e=="function"&&(e=[e]);if(n.isArray(e)){for(var s=0;s<e.length;s++)if(typeof e[s]=="function"){i=e[s].apply(r||this,t);if(i!=null)break}}else if(e)throw"FileDrop event list must be either an Array, Function, undefined or null but "+typeof e+" was given.";return i},n.callAllOfObject=function(e,t,r){if(n.logging&&n.hasConsole){var i=e.events[t]?e.events[t].length||0:0;console.info("FileDrop "+t+" event ("+i+") args:"),console.dir([r])}var s=[n.onObjectCall].concat(e.events.any),o=n.callAll(s,[t].concat(n.toArray(r)),e);return o!=null?o:n.callAll(e.events[t],r,e)},n.appendEventsToObject=function(e,t){if(n.addEventsToObject(this,!1,arguments))return this;switch(arguments.length){case 0:return n.extend({},this.events);case 1:if(e===null)return this.events={},this;if(n.isArray(e)){var r={};for(var i=0;i<e.length;i++)r[e[i]]=n.toArray(this.events[e[i]]);return r}if(typeof e=="function")return n.funcNS(e);if(typeof e=="string")return n.toArray(this.events[e]);case 2:e=n.toArray(e);if(t===null){for(var i=0;i<e.length;i++){var s=n.splitNS(e[i]);if(!s[0])for(var o in this.events)arguments.callee.call(this,[o+":"+s[1]],null);else if(!s[1])this.events[s[0]]=[];else if(this.events[s[0]])for(var u=this.events[s[0]].length-1;u>=0;u--)n.funcNS(this.events[s[0]][u])==s[1]&&this.events[s[0]].splice(u,1)}return this}}throw"Bad parameters for FileDrop event()."},n.previewToObject=function(e,t){if(n.addEventsToObject(this,!0,arguments))return this;throw"Bad parameters for FileDrop preview()."},n.addEventsToObject=function(e,t,r){var i=r[0],s=r[1];switch(r.length){case 1:if(i&&typeof i=="object"&&!n.isArray(i)){for(var o in i)arguments.callee(e,t,[o,i[o]]);return!0};case 2:if(typeof s=="function"||n.isArray(s)){i=n.toArray(i),s=n.toArray(s);var u=t?"unshift":"push";for(var a=0;a<i.length;a++){var f=n.splitNS(i[a]);for(var l=0;l<s.length;l++)n.funcNS(s[l],f[1]);e.events[f[0]]=e.events[f[0]]||[],e.events[f[0]][u].apply(e.events[f[0]],s)}return!0}}},n.funcNS=function(e,t){return typeof e!="function"?e:arguments.length==1?(e[n.nsProp]||"").toString():(e[n.nsProp]=(t||"").toString(),e)},n.splitNS=function(e){return(e||"").match(/^([^:]*):?(.*)$/).slice(1)},n.extend(n,{logging:!0,hasConsole:"console"in window&&console.log&&console.dir,onObjectCall:null,all:[],isIE6:!1,isIE9:!1,isChrome:(navigator.vendor||"").indexOf("Google")!=-1,nsProp:"_fdns"}),n.DropHandle=function(e,t){var r=this;r.el=e=n.byID(e);if(!e)throw"Cannot locate DOM node given to new FileDrop class.";r.opt={zoneClass:"fd-zone",inputClass:"fd-file",iframe:{url:"",callbackParam:"fd-callback",fileParam:"fd-file"},input:null,recreateInput:!0,fullDocDragDetect:!1,multiple:!1,dropEffect:"copy"},n.all.push(r),r.filedrop=null;var i=r.opt.iframe;n.extend(r.opt,t,!0),n.extend(r.opt.iframe,i),n.isChrome&&(r.opt.fullDocDragDetect=!0),r.events={any:[],dragEnter:[],dragLeave:[],dragOver:[],dragEnd:[],dragExit:[],upload:[],uploadElsewhere:[],inputSetup:[],iframeSetup:[],iframeDone:[]},r.on=r.events,r.zone=r.el,r.hook=function(e){r.opt.input!=0&&(r.opt.input=r.opt.input||r.prepareInput(e),r.opt.input&&n.callAllOfObject(r,"inputSetup",r.opt.input)),r.hookDragOn(e),r.hookDropOn(e)},r.hookDragOn=function(e){r.opt.fullDocDragDetect?(r.delegate(document.body,"dragEnter"),n.addEvent(document,"dragleave",function(e){if(e.clientX==0&&e.clientY==0||n.isTag(e.relatedTarget,"html"))n.stopEvent(e),n.callAllOfObject(r,"dragLeave",e)})):(r.delegate(e,"dragEnter"),r.delegate(e,"dragLeave")),r.delegate(e,"dragOver"),r.delegate(e,"dragEnd"),r.delegate(e,"dragExit")},r.hookDropOn=function(e){n.isIE9||r.delegate(e,"drop","upload")},r.delegate=function(e,t,i){n.addEvent(e,t.toLowerCase(),function(e){n.stopEvent(e),n.callAllOfObject(r,i||t,e)})},r.prepareInput=function(e){var t=r.findInputRecursive(e)||r.createInputAt(e);if(t){var i=t.parentNode;while(i&&!n.isTag(i,"form"))i=i.parentNode;if(!i)throw"FileDrop file input has no parent form element.";var s=i?i.getAttribute("target"):"";if(s&&n.isTag(n.byID(s),"iframe"))return{file:t,form:i}}return!1},r.findInputRecursive=function(e){for(var t=0
;t<e.childNodes.length;t++){var i=e.childNodes[t];if(n.isTag(i,"input")&&i.getAttribute("type")=="file"&&n.hasClass(i,r.opt.inputClass))return i;if(i=arguments.callee(i))return i}},r.createInputAt=function(e){do var t=n.randomID();while(n.byID(t));var i=document.createElement("div");i.innerHTML='<iframe src="javascript:false" name="'+t+'"></iframe>'+'<form method="post" enctype="multipart/form-data">'+'<input type="hidden" name="'+r.opt.iframe.callbackParam+'" />'+'<input type="file" name="'+r.opt.iframe.fileParam+'" />'+"</form>",i.firstChild.setAttribute("id",t),i.firstChild.style.display="none",i.lastChild.setAttribute("target",t);var s=e.firstChild;while(s&&(!n.isTag(s)||n.isTag(s,"legend")))s=s.nextSibling;return s?e.insertBefore(i,s):e.appendChild(i),i.lastChild.lastChild},r.abortIFrame=function(){if(r.opt.input.form){var e=n.byID(r.opt.input.form.getAttribute("target"));e&&e.setAttribute("src","javascript:false")}},r.sendViaIFrame=function(e){e=e||r.opt.iframe.url;var t=(r.opt.input||{}).form;if(e&&t){do var i=n.randomID();while(i in window);window[i]=function(t){typeof t!="object"&&(t={response:t,responseXML:"",responseText:(t||"").toString(),readyState:4,status:200,statusText:"OK",getAllResponseHeaders:function(){return""},getResponseHeader:function(){return""},setRequestHeader:function(){return this},statusCode:function(){return this},abort:function(){return this}}),n.extend(t,{iframe:!0,url:e}),n.callAllOfObject(r,"iframeDone",t)};var s=t.firstChild;while(s&&(!n.isTag(s,"input")||s.name!=r.opt.iframe.callbackParam))s=s.nextSibling;return s?s.value=i:e=e.replace(/[?&]+$/,"")+(e.indexOf("?")==-1?"?":"&")+r.opt.iframe.callbackParam+"="+i,t.setAttribute("action",e),n.callAllOfObject(r,"iframeSetup",t),t.submit(),setTimeout(r.resetForm,300),!0}},r.resetForm=function(){var e=r.opt.input&&r.opt.input.file;if(e){e.value="";if(r.opt.recreateInput){var t=r.opt.input.file=e.cloneNode(!0);e.parentNode.replaceChild(t,e),n.callAllOfObject(r,"inputSetup",[r.opt.input,e])}}},r.multiple=function(e){return r.opt.input&&typeof e!="undefined"&&(e?r.opt.input.file.setAttribute("multiple","multiple"):r.opt.input.file.removeAttribute("multiple")),r.opt.input&&!!r.opt.input.file.getAttribute("multiple")},r.event=function(e,t){return n.appendEventsToObject.apply(r,arguments)},r.preview=function(e,t){return n.previewToObject.apply(r,arguments)},r.onInputSetup=function(t,i){i?(t.file.clearAttributes&&t.file.clearAttributes(),t.file.mergeAttributes&&t.file.mergeAttributes(i)):r.multiple(r.opt.multiple),n.setClass(t.file,r.opt.inputClass),r.delegate(t.file,"change","upload");var s=t.file.parentNode;s&&s.style.display.match(/^(static)?$/)&&(s.style.position="relative");if(n.isTag(e,"fieldset")){var o=document.createElement("div");o.style.position="relative",o.style.overflow="hidden",e.parentNode.insertBefore(o,e),o.appendChild(e)}},r.onDragOver=function(e){n.stopEvent(e),e.dataTransfer&&(e.dataTransfer.dropEffect=r.opt.dropEffect)},r.onUpload=function(){for(var e=0;e<n.all.length;e++)n.all[e]!==r&&n.all[e].events&&n.callAllOfObject(n.all[e],"uploadElsewhere",r)},r.event({inputSetup:r.onInputSetup,dragOver:r.onDragOver,upload:r.onUpload}),n.setClass(e,r.opt.zoneClass),r.hook(e)},n.FileDrop=function(e,t){function i(t){return function(){n.setClass(e,r.opt.dragOverClass,t)}}var r=this;e=n.byID(e),r.handle=new n.DropHandle(e,t),r.handle.filedrop=r,n.extend(r.handle.opt,{dragOverClass:"over"}),n.extend(r.handle.opt.iframe,{force:!1}),n.extend(r.handle.events,{send:[],fileSetup:[]}),r.onUpload=function(e){var t=!r.opt.iframe.force&&r.eventFiles(e,!0);t?t.length>0&&n.callAllOfObject(r,"send",[t]):!r.handle.sendViaIFrame()&&n.hasConsole&&console.warn("FileDrop fallback upload triggered but iframe options were not configured - doing nothing.")},r.eventFiles=function(e,t){var i=new n.FileList(e);if(e.dataTransfer&&(e.dataTransfer.length||e.dataTransfer.files))var s=e.dataTransfer;else var s=e.target&&e.target.files||e.srcElement&&e.srcElement.files;if(s){var o=s.items||[];s.files&&(s=s.files);var u={};for(var a=0;a<s.length;a++){var f=new n.File(s[a]);if(!u[f.name]||f.name=="image.jpg")u[f.name]=!0,f.setNativeEntry(o[a]),n.callAllOfObject(r,"fileSetup",f),(f.size>0||f.nativeEntry)&&i.push(f)}}else t&&(i=!1);return i},n.extend(r,r.handle),r.event({upload:r.onUpload,send:r.resetForm,dragEnter:i(!0),dragLeave:i(!1),uploadElsewhere:i(!1)}),r.preview({upload:i(!1)})},n.FileList=function(e){var t=this;t.dropEffect=e&&e.dropEffect||"",t.length=0,e=null,t.push=function(e){return t[t.length++]=e,t},t.pop=function(){if(t.length>0){var e=t.last();return delete t[--t.length],e}},t.first=function(){return t[0]},t.last=function(){return t[t.length-1]},t.remove=function(e){for(;e<t.length-1;e++)t[e]=t[e+1];return se.f.pop(),t},t.clear=function(){for(var e=0;e<t.length;e++)delete t[e];return t.length=0,t},t.reverse=function(){for(var e=0;e<Math.floor(t.length/2);e++)t[e]=t[t.length-e-1];return t},t.concat=function(e){var r=new n.FileList;for(var i=0;i<t.length;i++)r[i]=t[i];for(var i=0;e&&i<e.length;i++)r[t.length+i+1]=e[i];return r.length=t.length+(e||[]).length,t},t.sort=function(e,n){for(var r=0;r<t.length;r++)for(var i=0;i<t.length;i++)if(e.call(n||this,t[r],t[i],r,i)<0){var s=t[r];t[r]=t[i],t[i]=s}return t},t.sortBy=function(e,n){var r=[];for(var i=0;i<t.length;i++)r.push([i,e.call(n||this,t[i],i)]);r.sort(function(e,t){return e[1]>t[1]?1:e[1]<t[1]?-1:0});for(var i=0;i<r.length;i++)t[i]=r[i][0];return t},t.find=function(e,n){for(var r=0;r<t.length;r++){var i=e.call(n||this,t[r],r);if(i!=null)return t[r]}},t.each=function(e,n){return t.find(function(){e.apply(this,arguments)},n),t},t.invoke=function(e,t){var r=n.toArray(arguments,1);return this.each(function(t){t[e].apply(t,r)})},t.abort=function(){return this.invoke("abort")},t.findCompare=function(e,n){var r,i=null,s;return t.each(function(t){if(i==null||i<(s=e.call(n,r)))r=t},n),r},t.filter=function(e,r){var i=new n.FileList;return t.each(function(t){e.apply(this,arguments)&&i
.push(t)},r),i},t.largest=function(){return t.findCompare(function(e){return e.size})},t.smallest=function(){return t.findCompare(function(e){return-e.size})},t.oldest=function(){return t.findCompare(function(e){return-e.modDate.getTime()})},t.newest=function(){return t.findCompare(function(e){return e.modDate})},t.ofType=function(e){return e+=e.indexOf("/")==-1?"/":"$",e=new RegExp("^"+e,"i"),t.filter(function(t){return e.test(t.type)})},t.images=function(){return t.ofType("image")},t.named=function(e){return typeof e=="string"?t.find(function(t){return t.name==e}):t.filter(function(t){return e.test(t.name)})}},n.FileList.prototype.length=0,n.FileList.prototype.splice=Array.prototype.splice,n.File=function(t){var r=this;r.nativeFile=t,r.nativeEntry=null,r.name=t.fileName||t.name||"",r.size=t.fileSize||t.size||0,r.type=r.mime=t.fileType||t.type||"",r.modDate=t.lastModifiedDate||new Date,r.xhr=null,r.opt={extraHeaders:!0,xRequestedWith:!0,method:"POST"},r.events={any:[],xhrSetup:[],xhrSend:[],progress:[],done:[],error:[]},r.events.sendXHR=r.events.xhrSend,r.abort=function(){return r.xhr&&r.xhr.abort&&r.xhr.abort(),r},r.sendTo=function(e,t){t=n.extend(t,r.opt),t.url=e;if(!r.size)n.hasConsole&&console.warn("Trying to send an empty FileDrop.File.");else if(window.FileReader){var i=new FileReader;i.onload=function(e){r.sendDataReadyTo(t,e)},i.onerror=function(e){n.callAllOfObject(r,"error",[e])},i.readAsArrayBuffer(r.nativeFile)}else r.sendDataReadyTo(t);return r},r.sendDataReadyTo=function(e,t){r.abort(),r.xhr=n.newXHR(),r.hookXHR(r.xhr),r.xhr.open(e.method,e.url,!0),r.xhr.overrideMimeType&&r.xhr.overrideMimeType("application/octet-stream"),r.xhr.setRequestHeader("Content-Type","application/octet-stream");if(e.extraHeaders){r.xhr.setRequestHeader("X-File-Name",encodeURIComponent(r.name)),r.xhr.setRequestHeader("X-File-Size",r.size),r.xhr.setRequestHeader("X-File-Type",r.type),r.xhr.setRequestHeader("X-File-Date",r.modDate.toGMTString());var i=e.xRequestedWith;if(i===!0){var s=window.FileReader?"FileAPI":"Webkit";i="FileDrop-XHR-"+s}i&&r.xhr.setRequestHeader("X-Requested-With",i)}n.callAllOfObject(r,"xhrSetup",[r.xhr,e]);var o=t&&t.target&&t.target.result?t.target.result:r.nativeFile;return n.callAllOfObject(r,"xhrSend",[r.xhr,o,e]),r.xhr},r.hookXHR=function(e){var t=e.upload||e;e.onreadystatechange=function(t){if(e.readyState==4){try{var i=e.status==200?"done":"error"}catch(t){var i="error"}var s=i=="error"?[t,e]:[e,t];n.callAllOfObject(r,i,s)}},t.onprogress=function(t){var i=t.lengthComputable?t.loaded:null;n.callAllOfObject(r,"progress",[i,t.total||null,e,t])}},r.readData=function(e,t,n){return r.read({onDone:e,onError:t,func:n})},r.readDataURL=function(e,t){return r.readData(e,t||!1,"uri")},r.readDataURI=r.readDataURL,r.read=function(t){function i(e,n){typeof n=="object"||(n.message=n),n.fdError=e,t.onError!==!1&&(t.onError||t.onDone).apply(this,arguments)}n.extend(t,{onDone:new Function,onError:null,blob:r.nativeFile,func:"",start:0,end:null,mime:""});if(!window.FileReader)return i("support",e);if(t.start>0||t.end!=null&&t.end)t.blob.slice?(t.end==null&&(t.end=t.blob.size||t.blob.fileSize),t.blob=t.blob.slice(t.start,t.end,t.mime)):n.hasConsole&&console.warn("File Blob/slice() are unsupported - operating on entire File.");var s=new FileReader;s.onerror=function(e){i("read",e)},s.onload=function(e){e.target&&e.target.result?(t.func=="readAsBinaryString"&&(e.target.result=String.fromCharCode.apply(null,new Uint8Array(e.target.result))),t.onDone(e.target.result)):s.onerror(e)};var o=t.func;if(n.isArray(o)){var u=o[0];return o[0]=t.blob,s[u].apply(s,o)}if(!o||o=="bin")o="readAsBinaryString";else if(o=="url"||o=="uri"||o=="src")o="readAsDataURL";else if(o=="array")o="readAsArrayBuffer";else if(o=="text")o="readAsText";else if(o.substr(0,4)!="read")return s.readAsText(t.blob,o);return o=="readAsBinaryString"&&(o="readAsArrayBuffer"),s[o](t.blob)},r.listEntries=function(e,t){if(r.nativeEntry&&r.nativeEntry.isDirectory){t=t||new Function;var i=r.nativeEntry.createReader(),s=new n.FileList,o=0;function u(t){o-=t,o==0&&e&&(e(s),e=null)}return i.readEntries(function(e){for(var r=0;r<e.length;r++){var a=e[r];a.file?(o++,a.file(function(e){var t=new n.File(e);t.setNativeEntry(a),s.push(t),u(1)},function(){s.push(n.File.fromEntry(a)),u(1),t.apply(this,arguments)})):s.push(n.File.fromEntry(a))}r?i.readEntries(arguments.callee,t):u(0)},t),!0}},r.setNativeEntry=function(e){r.nativeEntry=e&&e.webkitGetAsEntry&&e.webkitGetAsEntry()},r.event=function(e,t){return n.appendEventsToObject.apply(r,arguments)},r.preview=function(e,t){return n.previewToObject.apply(r,arguments)},r.onXhrSend=function(e,t){e.send(t)},r.event({xhrSend:r.onXhrSend})},n.File.fromEntry=function(e){var t=new n.File(e);return t.setNativeEntry(e),t.nativeFile=null,t},n.jQuery=function(e){e=e||jQuery||window.jQuery;if(!e)throw"No window.jQuery object to integrate FileDrop into.";e.fn.filedrop=function(t){function r(e,t){return function(r){var s=(t||[]).concat(n.toArray(arguments,1));return i.triggerHandler((e+r).toLowerCase(),s)}}var i=this,s=this.data("filedrop");if(typeof t=="string")if(!s)e.error("$.filedrop('comment') needs an initialized FilrDrop on this element.");else{if(typeof s[t]!="undefined"){var o=s[t];return typeof o=="function"?o.apply(s,n.toArray(arguments,1)):o}e.error("There's no method or property FileDrop."+t+".")}else if(!t||typeof t=="object")if(!s){var u=new FileDrop(this[0],t);u.$el=e(this),this.first().data("filedrop",u),u.event("any",r("fd")),u.on.fileSetup.push(function(e){e.event("any",r("file",[e]))})}else{if(!t)return s;n.extend(s.opt,t,!0)}else e.error("Invalid $.filedrop() parameter - expected nothing (creates new zone), a string (property to access) or an object (custom zone options).");return i}},t.FileDrop=n.FileDrop});
