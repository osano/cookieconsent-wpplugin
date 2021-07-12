jQuery(function() {

    var form = document.getElementById('cc-options-form');
    form.onchange = function () { update(); };

    loadThemeSelector(selected);

    function update () {
        setCode(getOptions(getInputs(form)));
        updateForm(form);
    }

    function setCode (config) {
        optionsField = jQuery('#icc_popup_options');
        if(typeof config != 'object') {
            optionsField.val('{'+config+'}');
        }
        else {
            var code = JSON.stringify(config);
            optionsField.val(code);
        }
    }

    function getOptions(input) {
        if (input.custom) return input.custom;
        var content = {};
        var options = {};

        var t = input.text;
        //if (t.header)  content.header = t.header;
        if (t.message) content.message = escapeHtml(t.message);
        if (t.dismiss) content.dismiss = escapeHtml(t.dismiss);
        if (t.allow)   content.allow = escapeHtml(t.allow);
        if (t.deny)    content.deny = escapeHtml(t.deny);
        if (t.link)    content.link = escapeHtml(t.link);

        options.palette = getThemes()[input.theme];

        if (input.layout == 'wire') {
            options.palette.button.border = options.palette.button.background;
            options.palette.button.background = 'transparent';
            options.palette.button.text =  options.palette.button.border;

        }

        //remove link if user didnt fill in field
        if(input.policy == 'policylink') {
            input.href ? content.href = input.href : options.showLink = false;
        } else if (input.policy == 'policypage') {
            content.href = 'policypage';
        }

        // only add if not default
        if(input.layout != 'block' && input.layout != 'wire') options.theme = input.layout;
        if(input.position != 'bottom') {
            if(input.position == 'top-push') {
                options.position = 'top';
                options.static = true;
            }
            else options.position = input.position;
        }
        if(input.compliance != 'info') options.type = input.compliance;

        //if has any content, add content
        for(var key in content) {
            if (content.hasOwnProperty(key)) {
                options.content = content;
                break;
            }
        }

        return options;
    }

    function getInputs (elem) {
        return {
            text: {
                allow: elem.querySelector('[name="allow-text"]').value,
                link: elem.querySelector('[name="link-text"]').value,
                message: elem.querySelector('[name="message-text"]').value,
                deny: elem.querySelector('[name="deny-text"]').value,
                dismiss: elem.querySelector('[name="dismiss-text"]').value,
            },
            href: elem.querySelector('[name="link-href"]').value,
            policy: elem.querySelectorAll('[name="policy"]:checked').item(0).value,
            position: elem.querySelectorAll('[name="choose-position"]:checked').item(0).value,
            layout: elem.querySelectorAll('[name="choose-layout"]:checked').item(0).value,
            theme: elem.querySelectorAll('[name="theme-selector"]:checked').item(0).value,
            compliance: elem.querySelectorAll('[name="choose-cookie-compliance"]:checked').item(0).value,
            custom: elem.querySelector('[name="custom-attributes"]').value
        }
    }

    function escapeHtml(html) {
        // let the spec decide how to escape the html
        var text = document.createTextNode(html);
        var div = document.createElement('div');
        div.appendChild(text);
        return div.innerHTML;
    }

    function updateForm(form) {
        document.getElementById('text-policylink-container').style.display = (form.querySelectorAll('[name="policy"]:checked').item(0).value == 'policylink' && form.querySelector('[name="link-href"]').value == '') ? 'none' : 'inline';
        document.getElementById('text-accept-container').style.display = form.querySelectorAll('[name="choose-cookie-compliance"]:checked').item(0).value != 'opt-in' ? 'none' : 'inline';
        document.getElementById('text-deny-container').style.display = form.querySelectorAll('[name="choose-cookie-compliance"]:checked').item(0).value != 'opt-out' ? 'none' : 'inline';
    }

    function loadThemeSelector(selected) {
        var themes = getThemes();
        var container = jQuery('#choose-colours');
        var isChecked = false;
        for (var key in themes) {
            var html = '';
            var checked = '';
            if (selected && selected == key) isChecked = false;
            if (!isChecked) {
                checked = 'checked';
                isChecked = true;
            }
            var sel = getThemes()[key];
            html += '<input type="radio" name="theme-selector" id="' + key + '-colour" class="input-hidden" value="'+ key +'" ' + checked + ' />';
            html += '<label for="'+ key +'-colour"><div class="theme-preview-container" style="background:'+ sel.popup.background + ';">';
            html += '<div class="theme-preview-button" style="background:'+ sel.button.background + ';"></div></div></label>';
            container.append(html);
        }
    }

    function getThemes() {
        return {
            theme1: {"popup": {"background": '#000'},"button": {"background": '#f1d600'}},
            theme2: {"popup": {"background": "#eaf7f7", "text":"#5c7291"},"button":{"background": "#56cbdb", "text":"#ffffff"}},
            theme3: {"popup": {"background": '#252e39'},"button": {"background": '#14a7d0'}},
            theme4: {"popup": {"background": '#000', "text": '#0f0'},"button": {"background": '#0f0'}},
            theme5: {"popup": {"background": '#3937a3'},"button": {"background": '#e62576'}},
            theme6: {"popup": {"background":"#64386b","text":"#ffcdfd"},"button": {"background":"#f8a8ff","text":"#3f0045"}},
            theme7: {"popup":{"background":"#237afc"},"button":{"background":"#fff","text":"#237afc"}},
            theme8: {"popup":{"background":"#aa0000","text":"#ffdddd"},"button":{"background":"#ff0000"}},
            theme9: {"popup":{"background":"#383b75"},"button":{"background":"#f1d600"}},
            theme10: {"popup":{"background":"#1d8a8a"},"button":{"background":"#62ffaa"}},
            theme11: {"popup":{"background":"#edeff5","text":"#838391"},"button":{"background":"#4b81e8"}},
            theme12: {"popup":{"background":"#343c66","text":"#cfcfe8"},"button":{"background":"#f71559"}},
            theme13: {"popup":{"background":"#216942","text":"#b2d192"},"button":{"background":"#afed71"}},
            theme14: {"popup":{"background":"#3c404d","text":"#d6d6d6"},"button":{"background":"#8bed4f"}},
            theme15: {"popup":{"background":"#eb6c44","text":"#ffffff"},"button":{"background":"#f5d948"}},
            theme16: {"popup":{"background":"#efefef","text":"#404040"},"button":{"background":"#8ec760","text":"#ffffff"}}
        };
    }

    update();

});