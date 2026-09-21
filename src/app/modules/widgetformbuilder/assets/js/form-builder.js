/*
formBuilder - http://kevinchappell.github.io/formBuilder/
Version: 1.10.3
Author: Kevin Chappell <kevin.b.chappell@gmail.com>
*/
'use strict';

var _typeof = typeof Symbol === "function" && typeof Symbol.iterator === "symbol" ? function (obj) { return typeof obj; } : function (obj) { return obj && typeof Symbol === "function" && obj.constructor === Symbol ? "symbol" : typeof obj; };

function formBuilderHelpersFn(opts, formBuilder) {
  'use strict';

  var _helpers = {
    doCancel: false
  };

  formBuilder.events = formBuilderEventsFn();

  /**
   * Convert an attrs object into a string
   *
   * @param  {Object} attrs object of attributes for markup
   * @return {string}
   */
  _helpers.attrString = function (attrs) {
    var attributes = [];
    for (var attr in attrs) {
      if (attrs.hasOwnProperty(attr)) {
        attr = _helpers.safeAttr(attr, attrs[attr]);
        attributes.push(attr.name + attr.value);
      }
    }
    var attrString = attributes.join(' ');
    return attrString;
  };

  /**
   * Convert camelCase into lowercase-hyphen
   *
   * @param  {string} str
   * @return {string}
   */
  _helpers.hyphenCase = function (str) {
    str = str.replace(/([A-Z])/g, function ($1) {
      return '-' + $1.toLowerCase();
    });

    return str.replace(/\s/g, '-').replace(/^-+/g, '');
  };

  /**
   * Convert converts messy `cl#ssNames` into valid `class-names`
   *
   * @param  {string} str
   * @return {string}
   */
  _helpers.makeClassName = function (str) {
    str = str.replace(/[^\w\s\-]/gi, '');
    return _helpers.hyphenCase(str);
  };

  _helpers.safeAttrName = function (name) {
    var safeAttr = {
      className: 'class'
    };

    return safeAttr[name] || _helpers.hyphenCase(name);
  };

  _helpers.safeAttr = function (name, value) {
    name = _helpers.safeAttrName(name);

    var valString = window.JSON.stringify(_helpers.escapeAttr(value));

    value = value ? '=' + valString : '';
    return {
      name: name,
      value: value
    };
  };

  /**
   * Add a mobile class
   *
   * @return {string}
   */
  _helpers.mobileClass = function () {
    var mobileClass = '';
    (function (a) {
      if (/(android|bb\d+|meego).+mobile|avantgo|bada\/|blackberry|blazer|compal|elaine|fennec|hiptop|iemobile|ip(hone|od)|iris|kindle|lge |maemo|midp|mmp|mobile.+firefox|netfront|opera m(ob|in)i|palm( os)?|phone|p(ixi|re)\/|plucker|pocket|psp|series(4|6)0|symbian|treo|up\.(browser|link)|vodafone|wap|windows ce|xda|xiino/i.test(a) || /1207|6310|6590|3gso|4thp|50[1-6]i|770s|802s|a wa|abac|ac(er|oo|s\-)|ai(ko|rn)|al(av|ca|co)|amoi|an(ex|ny|yw)|aptu|ar(ch|go)|as(te|us)|attw|au(di|\-m|r |s )|avan|be(ck|ll|nq)|bi(lb|rd)|bl(ac|az)|br(e|v)w|bumb|bw\-(n|u)|c55\/|capi|ccwa|cdm\-|cell|chtm|cldc|cmd\-|co(mp|nd)|craw|da(it|ll|ng)|dbte|dc\-s|devi|dica|dmob|do(c|p)o|ds(12|\-d)|el(49|ai)|em(l2|ul)|er(ic|k0)|esl8|ez([4-7]0|os|wa|ze)|fetc|fly(\-|_)|g1 u|g560|gene|gf\-5|g\-mo|go(\.w|od)|gr(ad|un)|haie|hcit|hd\-(m|p|t)|hei\-|hi(pt|ta)|hp( i|ip)|hs\-c|ht(c(\-| |_|a|g|p|s|t)|tp)|hu(aw|tc)|i\-(20|go|ma)|i230|iac( |\-|\/)|ibro|idea|ig01|ikom|im1k|inno|ipaq|iris|ja(t|v)a|jbro|jemu|jigs|kddi|keji|kgt( |\/)|klon|kpt |kwc\-|kyo(c|k)|le(no|xi)|lg( g|\/(k|l|u)|50|54|\-[a-w])|libw|lynx|m1\-w|m3ga|m50\/|ma(te|ui|xo)|mc(01|21|ca)|m\-cr|me(rc|ri)|mi(o8|oa|ts)|mmef|mo(01|02|bi|de|do|t(\-| |o|v)|zz)|mt(50|p1|v )|mwbp|mywa|n10[0-2]|n20[2-3]|n30(0|2)|n50(0|2|5)|n7(0(0|1)|10)|ne((c|m)\-|on|tf|wf|wg|wt)|nok(6|i)|nzph|o2im|op(ti|wv)|oran|owg1|p800|pan(a|d|t)|pdxg|pg(13|\-([1-8]|c))|phil|pire|pl(ay|uc)|pn\-2|po(ck|rt|se)|prox|psio|pt\-g|qa\-a|qc(07|12|21|32|60|\-[2-7]|i\-)|qtek|r380|r600|raks|rim9|ro(ve|zo)|s55\/|sa(ge|ma|mm|ms|ny|va)|sc(01|h\-|oo|p\-)|sdk\/|se(c(\-|0|1)|47|mc|nd|ri)|sgh\-|shar|sie(\-|m)|sk\-0|sl(45|id)|sm(al|ar|b3|it|t5)|so(ft|ny)|sp(01|h\-|v\-|v )|sy(01|mb)|t2(18|50)|t6(00|10|18)|ta(gt|lk)|tcl\-|tdg\-|tel(i|m)|tim\-|t\-mo|to(pl|sh)|ts(70|m\-|m3|m5)|tx\-9|up(\.b|g1|si)|utst|v400|v750|veri|vi(rg|te)|vk(40|5[0-3]|\-v)|vm40|voda|vulc|vx(52|53|60|61|70|80|81|83|85|98)|w3c(\-| )|webc|whit|wi(g |nc|nw)|wmlb|wonu|x700|yas\-|your|zeto|zte\-/i.test(a.substr(0, 4))) {
        mobileClass = ' fb-mobile';
      }
    })(navigator.userAgent || navigator.vendor || window.opera);
    return mobileClass;
  };

  /**
   * Callback for when a drag begins
   *
   * @param  {Object} event
   * @param  {Object} ui
   */
  _helpers.startMoving = function (event, ui) {
    event = event;
    ui.item.show().addClass('moving');
    _helpers.startIndex = $('li', this).index(ui.item);
  };

  /**
   * Callback for when a drag ends
   *
   * @param  {Object} event
   * @param  {Object} ui
   */
  _helpers.stopMoving = function (event, ui) {
    event = event;
    ui.item.removeClass('moving');
    if (_helpers.doCancel) {
      $(ui.sender).sortable('cancel');
      $(this).sortable('cancel');
    }
    _helpers.save();
    _helpers.doCancel = false;
  };

  /**
   * jQuery UI sortable beforeStop callback used for both lists.
   * Logic for canceling the sort or drop.
   */
  _helpers.beforeStop = function (event, ui) {
    event = event;

    var form = document.getElementById(opts.formID),
        lastIndex = form.children.length - 1,
        cancelArray = [];
    _helpers.stopIndex = ui.placeholder.index() - 1;

    if (!opts.sortableControls && ui.item.parent().hasClass('frmb-control')) {
      cancelArray.push(true);
    }

    if (opts.prepend) {
      cancelArray.push(_helpers.stopIndex === 0);
    }

    if (opts.append) {
      cancelArray.push(_helpers.stopIndex + 1 === lastIndex);
    }

    _helpers.doCancel = cancelArray.some(function (elem) {
      return elem === true;
    });
  };

  /**
   * Make strings safe to be used as classes
   *
   * @param  {string} str string to be converted
   * @return {string}     converter string
   */
  _helpers.safename = function (str) {
	  return str; // we don't want everything changing to lowercase
    //return str.replace(/\s/g, '-').replace(/[^a-zA-Z0-9\-]/g, '').toLowerCase();
  };

  /**
   * Strips non-numbers from a number only input
   *
   * @param  {string} str string with possible number
   * @return {string}     string without numbers
   */
  _helpers.forceNumber = function (str) {
    return str.replace(/[^0-9]/g, '');
  };

  /**
   * hide and show mouse tracking tooltips, only used for disabled
   * fields in the editor.
   *
   * @todo   remove or refactor to make better use
   * @param  {Object} tt jQuery option with nexted tooltip
   * @return {void}
   */
  _helpers.initTooltip = function (tt) {
    var tooltip = tt.find('.tooltip');
    tt.mouseenter(function () {
      if (tooltip.outerWidth() > 200) {
        tooltip.addClass('max-width');
      }
      tooltip.css('left', tt.width() + 14);
      tooltip.stop(true, true).fadeIn('fast');
    }).mouseleave(function () {
      tt.find('.tooltip').stop(true, true).fadeOut('fast');
    });
    tooltip.hide();
  };

  /**
   * Attempts to get element type and subtype
   *
   * @param  {Object} $field
   * @return {Object}
   */
  _helpers.getTypes = function ($field) {
    return {
      type: $field.attr('type'),
      subtype: $('.fld-subtype', $field).val()
    };
  };

	_helpers.str_replace = function(str_a, strOld_a, strNew_a)
	{
		var strResult = str_a;
		if (strResult === undefined)
		{
			strResult = '';
		}
		while (strResult.indexOf(strOld_a) >= 0)
		{
			strResult = strResult.replace(strOld_a, strNew_a);
		}

		return strResult;
	};

	_helpers.escapeAttr = function(strValue_a) {
		var strResult = strValue_a;

		if (typeof strValue_a === 'string')
		{
			strResult = _helpers.str_replace(strResult, '"', "&apos;");	// this is intentional as builder does not support "
			//strResult = _helpers.str_replace(strResult, "<", '&lt;');
			//strResult = _helpers.str_replace(strResult, ">", '&gt;');
			//strResult = _helpers.str_replace(strResult, "'", '&apos;');
			//strResult = _helpers.str_replace(strResult, "&", '&amp;');
		}

		return strResult;
	};

	_helpers.massageXML = function(strValue_a) {
		var strResult = strValue_a;
//alert(strResult);
		if (typeof(strValue_a) == "string")
		{
			strResult = _helpers.str_replace(strResult, '__AMP__', '&amp;');
			strResult = _helpers.str_replace(strResult, '__APOS__', "&apos;");
			strResult = _helpers.str_replace(strResult, '__QUOT__', '&apos;');	// this is intentional as builder does not support "
			strResult = _helpers.str_replace(strResult, '__LT__', '&lt;');
			strResult = _helpers.str_replace(strResult, '__GT__', '&gt;');
		}
//alert(strResult);
		return strResult;
	};

  // Remove null or undefined values
  _helpers.trimAttrs = function (attrs) {
    var xmlRemove = [null, undefined, '', false];
    for (var i in attrs) {
      if (_helpers.inArray(attrs[i], xmlRemove)) {
        delete attrs[i];
      }
    }
    return attrs;
  };

  /**
   * XML save
   *
   * @param  {Object} form sortableFields node
   */
  _helpers.xmlSave = function (form) {
    var formDataNew = $(form).toXML(_helpers);
    if (window.JSON.stringify(formDataNew) === window.JSON.stringify(formBuilder.formData)) {
      return false;
    }
    formBuilder.formData = formDataNew;
  };

  _helpers.jsonSave = function () {
    opts.notify.warning('json data not available yet');
  };

  /**
   * Saves and returns formData
   * @return {XML|JSON}
   */
  _helpers.save = function () {
    var element = _helpers.getElement(),
        form = document.getElementById(opts.formID),
        formData;

    var doSave = {
      xml: _helpers.xmlSave,
      json: _helpers.jsonSave
    };

    // save action for current `dataType`
    formData = doSave[opts.dataType](form);

    if (element) {
      element.value = formBuilder.formData;
      if (window.jQuery) {
        $(element).trigger('change');
      } else {
        element.onchange();
      }
    }

    //trigger formSaved event
    document.dispatchEvent(formBuilder.events.formSaved);
    return formData;
  };

  /**
   * Attempts to find an element,
   * useful if formBuilder was called without Query
   * @return {Object}
   */
  _helpers.getElement = function () {
    var element = false;
    if (formBuilder.element) {
      element = formBuilder.element;

      if (!element.id) {
        _helpers.makeId(element);
      }

      if (!element.onchange) {
        element.onchange = function () {
          opts.notify.success(opts.messages.formUpdated);
        };
      }
    }

    return element;
  };

  /**
   * increments the field ids with support for multiple editors
   * @param  {String} id field ID
   * @return {String}    incremented field ID
   */
  _helpers.incrementId = function (id) {
    var split = id.lastIndexOf('-'),
        newFieldNumber = parseInt(id.substring(split + 1), 10) + 1,
        baseString = id.substring(0, split);

    return baseString + '-' + newFieldNumber;
  };

  _helpers.makeId = function () {
    var element = arguments.length <= 0 || arguments[0] === undefined ? false : arguments[0];

    var epoch = new Date().getTime();

    return element.tagName + '-' + epoch;
  };

  /**
   * Collect field attribute values and call fieldPreview to generate preview
   * @param  {Object} field jQuery wrapped dom object @todo, remove jQuery dependency
   */
  _helpers.updatePreview = function (field) {
    var fieldData = field.data('fieldData') || {};
    var fieldClass = field.attr('class');
    if (fieldClass.indexOf('ui-sortable-handle') !== -1) {
      return;
    }

    var fieldType = $(field).attr('type'),
        $prevHolder = $('.prev-holder', field),
        previewData = {
      label: $('.fld-label', field).val(),
      type: fieldType
    },
        preview;

    var subtype = $('.fld-subtype', field).val();
    if (subtype) {
      previewData.subtype = subtype;
    }

    var maxlength = $('.fld-maxlength', field).val();
    if (maxlength) {
      previewData.maxlength = maxlength;
    }

    var mitsukibovalue = $('.fld-mitsukibovalue', field).val();
    if (mitsukibovalue) {
      previewData.mitsukibovalue = mitsukibovalue;
    }

    var length = $('.fld-length', field).val();
    if (length) {
      previewData.length = length;
    }

    var lines = $('.fld-lines', field).val();
    if (lines) {
      previewData.lines = lines;
    }


    var value = $('.fld-value', field).val();
    if (value) {
      previewData.value = value;
    }

    var infovalue = $('.fld-infovalue', field).val();
    if (infovalue) {
      previewData.infovalue = infovalue;
    }

    var quantity = $('.fld-quantity', field).val();
    if (quantity) {
      previewData.quantity = quantity;
    }

    var sectionref = $('.fld-sectionref', field).val();
    if (sectionref) {
      previewData.sectionref = sectionref;
    }

    var colour = $('.fld-colour', field).val();
    if (colour) {
      previewData.colour = colour;
    }

    var tempname = $('.fld-tempname', field).val();
    if (tempname) {
      previewData.tempname = tempname;
    }

    var fieldstyle = $('.fld-fieldstyle', field).val();
    if (fieldstyle) {
      previewData.fieldstyle = fieldstyle;
    }

    //var sectiontype = $('.fld-sectiontype', field).val();
    //if (sectiontype) {
      //previewData.sectiontype = sectiontype;
    //}

    previewData.className = $('.fld-className', field).val() || fieldData.className || '';

    var fieldsource = $('.fld-fieldsource', field).val();
    if (fieldsource) {
      previewData.fieldsource = fieldsource;
    }

    var placeholder = $('.fld-placeholder', field).val();
    if (placeholder) {
      previewData.placeholder = placeholder;
    }

    var style = $('.btn-style', field).val();
    if (style) {
      previewData.style = style;
    }

    if (fieldType === 'checkbox' ) {
      previewData.toggle = $('.checkbox-toggle', field).is(':checked');
    }

    var name = $('.fld-name', field).val();
    if (name) {
      previewData.name = name;
    }

    if (fieldType.match(/(checkbox-group|radio-group)/)) {
      previewData.enableOther = $('[name="enable-other"]', field).is(':checked');
    }

    if (fieldType.match(/(d_chart||d_list||d_multilist||select|checkbox-group|radio-group)/)) {
      previewData.values = [];
      previewData.multiple = $('[name="multiple"]', field).is(':checked');

      $('.sortable-options li', field).each(function () {
        var option = {};
        option.selected = $('.option-selected', this).is(':checked');
        option.value = $('.option-value', this).val();
        option.label = $('.option-label', this).val();
        previewData.values.push(option);
      });
    }

    if (fieldType.match(/(d_relatedlinks)/)) {
      previewData.values = [];

      $('.sortable-links li', field).each(function () {
        var option = {};
        option.label = $('.option-label', this).val();
        option.permissions = $('.option-permissions', this).val();
        option.command = $('.option-command', this).val();
		option.parameters = $('.option-parameters', this).val();
        previewData.values.push(option);
      });
    }

    previewData.className = _helpers.classNames(field, previewData);
    $('.fld-className', field).val(previewData.className);

    field.data('fieldData', previewData);
    preview = _helpers.fieldPreview(previewData);

    $prevHolder.html(preview);

    $('input[toggle]', $prevHolder).kcToggle();
  };

  /**
   * Generate preview markup
   *
   * @todo   make this smarter and use tags
   * @param  {Object} attrs
   * @return {String}       preview markup for field
   */
  _helpers.fieldPreview = function (attrs) {
    var i,
        preview = '',
        epoch = new Date().getTime();
    attrs = jQuery.extend({}, attrs);
    attrs.type = attrs.subtype || attrs.type;
    var toggle = attrs.toggle ? 'toggle' : '';
    var attrsString = _helpers.attrString(attrs);
    epoch = new Date().getTime();
	var options;
	var selected;
	var multiple;

    switch (attrs.type) {
	  case 'd_html':
	  case 'd_texthtml':
    case 'd_codeeditor':
      case 'd_multilinetext':
      case 'textarea':
      case 'rich-text':
        preview = '<textarea style="cursor: default;" ' + attrsString + '></textarea>';
        break;
      case 'formheadersection':

        preview = '<button ' + attrsString + ' style="cursor: pointer; cursor: hand;" sectionguid="'+ attrs.name +'">Click here to edit Form Header Fields</button> <span class="ge-sectionnotes-field"></span>';
        break;
      case 'internaluseformheadersection':

        preview = '<button ' + attrsString + ' style="cursor: pointer; cursor: hand;" sectionguid="'+ attrs.name +'">Click here to edit Internal Use Form Header Fields</button> <span class="ge-sectionnotes-field"></span>';
        break;
      case 'dataheadersection':

        preview = '<button ' + attrsString + ' style="cursor: pointer; cursor: hand;" sectionguid="'+ attrs.name +'">Click here to edit Data Header Fields</button> <span class="ge-sectionnotes-field"></span>';
        break;
      case 'datasection':

        preview = '<button ' + attrsString + ' style="cursor: pointer; cursor: hand;" sectionguid="'+ attrs.name +'">Click here to edit Form Fields</button> <span class="ge-sectionnotes-field"></span>';
        break;
      case 'internaluseonlysection':

        preview = '<button ' + attrsString + ' style="cursor: pointer; cursor: hand;" sectionguid="'+ attrs.name +'">Click here to edit Internal Use Only Fields</button> <span class="ge-sectionnotes-field"></span>';
        break;
      case 'button':
      case 'submit':
        preview = '<button ' + attrsString + ' style="cursor: default;">' + attrs.label + '</button>';
        break;
      case 'select':
        options = '';
            multiple = attrs.multiple ? 'multiple' : '';
        attrs.values.reverse();
        if (attrs.placeholder) {
          options += '<option disabled selected>' + attrs.placeholder + '</option>';
        }
        for (i = attrs.values.length - 1; i >= 0; i--) {
          selected = attrs.values[i].selected && !attrs.placeholder ? 'selected' : '';
          options += '<option value="' + attrs.values[i].value + '" ' + selected + '>' + attrs.values[i].label + '</option>';
        }
        preview = '<' + attrs.type + ' class="' + attrs.className + '" ' + multiple + '>' + options + '</' + attrs.type + '>';
        break;
      case 'd_list':
        options = '';
            multiple = attrs.multiple ? 'multiple' : '';
        attrs.values.reverse();
        if (attrs.placeholder) {
          options += '<option disabled selected>' + attrs.placeholder + '</option>';
        }
        for (i = attrs.values.length - 1; i >= 0; i--) {
          selected = attrs.values[i].selected && !attrs.placeholder ? 'selected' : '';
          options += '<option value="' + attrs.values[i].value + '" ' + selected + '>' + attrs.values[i].label + '</option>';
        }
        preview = '<select style="cursor: default;" class="' + attrs.className + '" ' + multiple + '>' + options + '</select>';
        break;
       case 'd_chart':
            options = '';
                multiple = attrs.multiple ? 'multiple' : '';
            attrs.values.reverse();
            if (attrs.placeholder) {
              options += '<option disabled selected>' + attrs.placeholder + '</option>';
            }
            for (i = attrs.values.length - 1; i >= 0; i--) {
              selected = attrs.values[i].selected && !attrs.placeholder ? 'selected' : '';
              options += '<option value="' + attrs.values[i].value + '" ' + selected + '>' + attrs.values[i].label + '</option>';
            }
            preview = '<select style="cursor: default;" class="' + attrs.className + '" ' + multiple + '>' + options + '</select>';
            break;
      case 'd_multilist':
        options = '';
            multiple =  'multiple';
        attrs.values.reverse();
        if (attrs.placeholder) {
          options += '<option disabled selected>' + attrs.placeholder + '</option>';
        }
        for (i = attrs.values.length - 1; i >= 0; i--) {
          selected = attrs.values[i].selected && !attrs.placeholder ? 'selected' : '';
          options += '<option value="' + attrs.values[i].value + '" ' + selected + '>' + attrs.values[i].label + '</option>';
        }
        preview = '<select style="cursor: default;" class="' + attrs.className + '" ' + multiple + '>' + options + '</select>';
        break;
      case 'd_relatedlinks':
        //var links = '';
        //attrs.values.reverse();
        //for (i = attrs.values.length - 1; i >= 0; i--) {
          //links += attrs.values[i].label +' ';
        //}
        //preview = '<input type="text" class="' + attrs.className + '" name="' + attrs.name + '" value="' + links + '" >';
        options = '';
        attrs.values.reverse();
        if (attrs.placeholder) {
          options += '<option disabled selected>' + attrs.placeholder + '</option>';
        }
        for (i = attrs.values.length - 1; i >= 0; i--) {
          options += '<option command="' + attrs.values[i].command + '" permissions="' + attrs.values[i].permissions + '" parameters="' + attrs.values[i].parameters + '">' + attrs.values[i].label + '</option>';
        }
        preview = '<select class="form-control ' + attrs.className + '">' + options + '</select>';
        break;

      case 'checkbox-group':
      case 'radio-group':
        var type = attrs.type.replace('-group', ''),
            optionName = type + '-' + epoch;
        attrs.values.reverse();
        for (i = attrs.values.length - 1; i >= 0; i--) {
          var checked = attrs.values[i].selected ? 'checked' : '';
          var optionId = type + '-' + epoch + '-' + i;
          preview += '<div><input style="cursor: default;" type="' + type + '" class="' + attrs.className + '" name="' + optionName + '" id="' + optionId + '" value="' + attrs.values[i].value + '" ' + checked + '/><label for="' + optionId + '">' + attrs.values[i].label + '</label></div>';
        }

        if (attrs.enableOther) {
          var otherID = optionName + '-other',
              optionAttrs = {
            id: otherID,
            name: optionName,
            className: attrs.className + ' other-option',
            type: type,
            onclick: 'otherOptionCallback(\'' + otherID + '\')'
          },
              otherInput = _helpers.markup('input', null, optionAttrs),
              optionAttrsString = _helpers.attrString(optionAttrs);

          window.otherOptionCallback = function (otherID) {
            var option = document.getElementById(otherID),
                otherLabel = option.nextElementSibling,
                otherInput = otherLabel.nextElementSibling;
            if (option.checked) {
              otherInput.style.display = 'inline-block';
              otherLabel.style.display = 'none';
            } else {
              otherInput.style.display = 'none';
              otherLabel.style.display = 'inline-block';
            }
          };

          preview += '<div>' + otherInput.outerHTML + '<label for="' + otherID + '">' + opts.messages.other + '</label> <input style="cursor: default;" type="text" id="' + otherID + '-value" style="display:none;" /></div>';
        }

        break;

	  case 'd_version':	// readonly field
        preview = '<input style="cursor: default;" readonly="readonly" ' + attrsString + '>';
        break;
	  case 'd_text':
		if ((opts.entitycode === 'dataform') && (opts.internalmode === 'formsection') && (attrs.name === 'ENTITY'))
		{
			preview = '<input style="cursor: default;" readonly="readonly" ' + attrsString + '>';
		}
		else
		{
			preview = '<input style="cursor: default;" ' + attrsString + '>';
		}
        break;
	  case 'mitsukibo':
	  case 'd_abnlookup':
	  case 'd_barcode':
	  case 'd_metadata':
	  case 'd_number':
	  case 'd_time':
      case 'text':
      case 'password':
      case 'email':
      case 'date':
      case 'file':
        preview = '<input style="cursor: default;" ' + attrsString + '>';
        break;
      case 'd_date':
        preview = '<input style="cursor: default;" type="date" class="' + attrs.className + '" name="' + attrs.name + '">';
        break;
      case 'd_gps':
        preview = '<input style="cursor: default;" type="text" class="' + attrs.className + '" name="' + attrs.name + '">';
        break;
      case 'd_audio':
      case 'd_document':
      case 'd_image':
      case 'd_video':
        preview = '<input style="cursor: default;" type="file" class="' + attrs.className + '" name="' + attrs.name + '">';
        break;
      case 'd_password':
        preview = '<input style="cursor: default;" type="password" class="' + attrs.className + '" name="' + attrs.name + '">';
        break;
      case 'color':
        preview = '<input style="cursor: default;" type="' + attrs.type + '" class="' + attrs.className + '"> ' + opts.messages.selectColor;
        break;
      case 'hidden':
      case 'checkbox':
        preview = '<input style="cursor: default;" type="' + attrs.type + '" ' + toggle + ' >';
        break;
	  case 'd_button':
      case 'd_description':
      case 'd_heading':
      case 'd_spacer':
      case 'd_url':
        preview = '<' + attrs.type + ' ' + attrsString + '></' + attrs.type + '>';
        break;
      case 'd_yesno':

        var strChecked = attrs.value === 'Y' ? ' checked ' : '';

        //preview = '<input type="checkbox" ' + toggle + '  >';
        preview = '<input style="cursor: default;" class="ge-yesno" type="checkbox" ' + toggle + ' value="' + attrs.value + '"' + strChecked + '>';
        break;
      case 'autocomplete':
        preview = '<input style="cursor: default;" class="ui-autocomplete-input ' + attrs.className + '" autocomplete="on">';
        break;
      default:
        attrsString = _helpers.attrString(attrs);
        preview = '<' + attrs.type + ' ' + attrsString + '>' + attrs.label + '</' + attrs.type + '>';
		break;
    }

    return preview;
  };

  // update preview to label
  _helpers.updateMultipleSelect = function () {
	$(document.getElementById(opts.formID)).unbind('change', 'input[name="multiple"]');
    $(document.getElementById(opts.formID)).on('change', 'input[name="multiple"]', function () {
      var options = $(this).parents('.field-options:eq(0)').find('.sortable-options input.option-selected');
      if (this.checked) {
        options.each(function () {
          $(this).prop('type', 'checkbox');
        });
      } else {
        options.each(function () {
          $(this).removeAttr('checked').prop('type', 'radio');
        });
      }
    });
  };

  _helpers.debounce = function (func) {
    var wait = arguments.length <= 1 || arguments[1] === undefined ? 250 : arguments[1];
    var immediate = arguments.length <= 2 || arguments[2] === undefined ? false : arguments[2];

    var timeout;
    return function () {
      var context = this,
          args = arguments;
      var later = function later() {
        timeout = null;
        if (!immediate) {
          func.apply(context, args);
        }
      };
      var callNow = immediate && !timeout;
      clearTimeout(timeout);
      timeout = setTimeout(later, wait);
      if (callNow) {
        func.apply(context, args);
      }
    };
  };

  _helpers.htmlEncode = function (value) {
    return $('<div/>').text(value).html();
  };

  _helpers.htmlDecode = function (value) {
    return $('<div/>').html(value).text();
  };

  _helpers.validateForm = function () {
    var $form = $(document.getElementById(opts.formID));

    var errors = [];
    // check for empty field labels
    $('input[name="label"], input[type="text"].option', $form).each(function () {
      if ($(this).val() === '') {
        var field = $(this).parents('li.form-field'),
            fieldAttr = $(this);
        errors.push({
          field: field,
          error: opts.messages.labelEmpty,
          attribute: fieldAttr
        });
      }
    });

    // @todo add error = { noVal: opts.messages.labelEmpty }
    if (errors.length) {
      alert('Error: ' + errors[0].error);
      $('html, body').animate({
        scrollTop: errors[0].field.offset().top
      }, 1000, function () {
        var targetID = $('.toggle-form', errors[0].field).attr('id');
        $('.toggle-form', errors[0].field).addClass('open').parent().next('.prev-holder').slideUp(250);
        $('#' + targetID + '-fld').slideDown(250, function () {
          errors[0].attribute.addClass('error');
        });
      });
    }
  };

  /**
   * Display a custom tooltip for disabled fields.
   *
   * @param  {Object} field
   */
  _helpers.disabledTT = {
    className: 'frmb-tt',
    add: function add(field) {
      var title = opts.messages.fieldNonEditable;

      if (title) {
        var tt = _helpers.markup('p', title, { className: _helpers.disabledTT.className });
        field.append(tt);
      }
    },
    remove: function remove(field) {
      $('.frmb-tt', field).remove();
    }
  };

  _helpers.classNames = function (field, previewData) {
    var noFormControl = ['checkbox', 'checkbox-group', 'd_yesno', 'radio-group', 'd_relatedlinks'],
        blockElements = ['header', 'paragraph', 'button', 'formheadersection', 'internaluseformheadersection', 'dataheadersection', 'datasection', 'internaluseonlysection', 'd_button', 'd_description', 'd_heading', 'd_url', 'd_version', 'd_spacer'],
        i = void 0;

    for (i = blockElements.length - 1; i >= 0; i--) {
      blockElements = blockElements.concat(opts.messages.subtypes[blockElements[i]]);
    }

    noFormControl = noFormControl.concat(blockElements);

    var type = previewData.type;
    var style = previewData.style;
    var className = field[0].querySelector('.fld-className').value;
    var classes = [].concat(className.split(' ')).reverse();
    var types = {
      button: 'btn',
      submit: 'btn',
	  formheadersection: 'btn',
	  internaluseformheadersection: 'btn',
	  dataheadersection: 'btn',
      datasection: 'btn',
      internaluseonlysection: 'btn'
    };

    var primaryType = types[type];

    if (primaryType) {
      if (style) {
        for (i = classes.length - 1; i >= 0; i--) {
          var re = new RegExp('(?:^|\s)' + primaryType + '-(.*?)(?:\s|$)+', 'g');
          var match = classes[i].match(re);
          if (match) {
            classes.splice(i, 1);
          }
        }
        classes.push(primaryType + '-' + style);
      }
      classes.push(primaryType);
    } else if (!_helpers.inArray(type, noFormControl)) {
      classes.push('form-control');
    }

    // reverse the array to put custom classes at end, remove any duplicates, convert to string, remove whitespace
    return $.trim(_helpers.unique(classes.reverse()).join(' '));
  };

  _helpers.markup = function (tag) {
    var content = arguments.length <= 1 || arguments[1] === undefined ? '' : arguments[1];
    var attrs = arguments.length <= 2 || arguments[2] === undefined ? {} : arguments[2];

    var contentType = void 0,
        field = document.createElement(tag),
        getContentType = function getContentType(content) {
      return Array.isArray(content) ? 'array' : typeof content === 'undefined' ? 'undefined' : _typeof(content);
    },
        appendContent = {
      string: function string(content) {
        field.innerHTML = content;
      },
      object: function object(content) {
        return field.appendChild(content);
      },
      array: function array(content) {
        for (var i = 0; i < content.length; i++) {
          contentType = getContentType(content[i]);
          appendContent[contentType](content[i]);
        }
      }
    };

    for (var attr in attrs) {
      if (attrs.hasOwnProperty(attr)) {
        if (attrs[attr]) {
          var name = _helpers.safeAttrName(attr);
          field.setAttribute(name, attrs[attr]);
        }
      }
    }

    contentType = getContentType(content);

    if (content) {
      appendContent[contentType].call(this, content);
    }

    return field;
  };

  /**
   * Closes and open dialog
   *
   * @param  {Object} overlay Existing overlay if there is one
   * @param  {Object} dialog  Existing dialog
   * @return {Event}          Triggers modalClosed event
   */
  _helpers.closeConfirm = function (overlay, dialog) {
    overlay = overlay || document.getElementsByClassName('form-builder-overlay')[0];
    dialog = dialog || document.getElementsByClassName('form-builder-dialog')[0];
    overlay.classList.remove('visible');
    dialog.remove();
    overlay.remove();
    document.dispatchEvent(formBuilder.events.modalClosed);
  };

  /**
   * Returns the layout data based on controlPosition option
   * @param  {String} controlPosition 'left' or 'right'
   * @return {Object}
   */
  _helpers.editorLayout = function (controlPosition) {
    var layoutMap = {
      left: {
        stage: 'pull-right',
        controls: 'pull-left'
      },
      right: {
        stage: 'pull-left',
        controls: 'pull-right'
      }
    };

    return layoutMap[controlPosition] ? layoutMap[controlPosition] : '';
  };

  /**
   * Adds overlay to the page. Used for modals.
   * @return {Object}
   */
  _helpers.showOverlay = function () {
    var overlay = _helpers.markup('div', null, {
      className: 'form-builder-overlay'
    });
    document.body.appendChild(overlay);
    overlay.classList.add('visible');

    overlay.onclick = function () {
      _helpers.closeConfirm(overlay);
    };

    return overlay;
  };

  /**
   * Custom confirmation dialog
   *
   * @param  {Object}  message   Content to be displayed in the dialog
   * @param  {Func}  yesAction callback to fire if they confirm
   * @param  {Boolean} coords    location to put the dialog
   * @param  {String}  className Custom class to be added to the dialog
   * @return {Object}            Reference to the modal
   */
  _helpers.confirm = function (message, yesAction) {
    var coords = arguments.length <= 2 || arguments[2] === undefined ? false : arguments[2];
    var className = arguments.length <= 3 || arguments[3] === undefined ? '' : arguments[3];

    var overlay = _helpers.showOverlay();
    var yes = _helpers.markup('button', opts.messages.yes, { className: 'yes btn btn-success btn-sm' }),
        no = _helpers.markup('button', opts.messages.no, { className: 'no btn btn-danger btn-sm' });

    no.onclick = function () {
      _helpers.closeConfirm(overlay);
    };

    yes.onclick = function () {
      yesAction();
      _helpers.closeConfirm(overlay);
    };

    var btnWrap = _helpers.markup('div', [no, yes], { className: 'button-wrap' });

    className = 'form-builder-dialog ' + className;

    var miniModal = _helpers.markup('div', [message, btnWrap], { className: className });
    if (!coords) {
      coords = {
        pageX: Math.max(document.documentElement.clientWidth, window.innerWidth || 0) / 2,
        pageY: Math.max(document.documentElement.clientHeight, window.innerHeight || 0) / 2
      };
      miniModal.style.position = 'fixed';
    } else {
      miniModal.classList.add('positioned');
    }

    miniModal.style.left = coords.pageX + 'px';
    miniModal.style.top = coords.pageY + 'px';

    document.body.appendChild(miniModal);

    yes.focus();
    return miniModal;
  };

  /**
   * Popup dialog the does not require confirmation.
   * @param  {String|DOM|Array}  content
   * @param  {Boolean} coords    false if no coords are provided. Without coordinates
   *                             the popup will appear center screen.
   * @param  {String}  className classname to be added to the dialog
   * @return {Object}            dom
   */
  _helpers.dialog = function (content) {
    var coords = arguments.length <= 1 || arguments[1] === undefined ? false : arguments[1];
    var className = arguments.length <= 2 || arguments[2] === undefined ? '' : arguments[2];

    _helpers.showOverlay();

    className = 'form-builder-dialog ' + className;

    var miniModal = _helpers.markup('div', content, { className: className });
    if (!coords) {
      coords = {
        pageX: Math.max(document.documentElement.clientWidth, window.innerWidth || 0) / 2,
        pageY: Math.max(document.documentElement.clientHeight, window.innerHeight || 0) / 2
      };
      miniModal.style.position = 'fixed';
    } else {
      miniModal.classList.add('positioned');
    }

    miniModal.style.left = coords.pageX + 'px';
    miniModal.style.top = coords.pageY + 'px';

    document.body.appendChild(miniModal);

    if (className.indexOf('data-dialog') !== -1) {
      document.dispatchEvent(formBuilder.events.viewData);
    }
    return miniModal;
  };

  /**
   * Removes all fields from the form
   */
  _helpers.removeAllfields = function () {
    var form = document.getElementById(opts.formID);
    var fields = form.querySelectorAll('li.form-field');
    var $fields = $(fields);
    var markEmptyArray = [];

    if (opts.prepend) {
      markEmptyArray.push(true);
    }

    if (opts.append) {
      markEmptyArray.push(true);
    }

    if (!markEmptyArray.some(function (elem) {
      return elem === true;
    })) {
      form.parentElement.classList.add('empty');
    }

    form.classList.add('removing');

    var outerHeight = 0;
    $fields.each(function () {
      outerHeight += $(this).outerHeight() + 3;
    });

    fields[0].style.marginTop = -outerHeight + 'px';

    setTimeout(function () {
      $fields.remove();
      document.getElementById(opts.formID).classList.remove('removing');
      _helpers.save();
    }, 500);
  };

  /**
   * If user re-orders the elements their order should be saved.
   *
   * @param {Object} $cbUL our list of elements
   */
  _helpers.setFieldOrder = function ($cbUL) {
    if (!opts.sortableControls) {
      return false;
    }
    var fieldOrder = {};
    $cbUL.children().each(function (index, element) {
      fieldOrder[index] = $(element).data('attrs').type;
    });
    if (window.sessionStorage) {
      window.sessionStorage.setItem('fieldOrder', window.JSON.stringify(fieldOrder));
    }
  };

  /**
   * Reorder the controls if the user has previously ordered them.
   *
   * @param  {Array} frmbFields
   * @return {Array}
   */
  _helpers.orderFields = function (frmbFields) {
    var fieldOrder = false;

    if (window.sessionStorage) {
      if (opts.sortableControls) {
        fieldOrder = window.sessionStorage.getItem('fieldOrder');
      } else {
        window.sessionStorage.removeItem('fieldOrder');
      }
    }

    if (!fieldOrder) {
      fieldOrder = _helpers.unique(opts.controlOrder);
    } else {
      fieldOrder = window.JSON.parse(fieldOrder);
      fieldOrder = Object.keys(fieldOrder).map(function (i) {
        return fieldOrder[i];
      });
    }

    var newOrderFields = [];

    for (var i = fieldOrder.length - 1; i >= 0; i--) {
      var field = frmbFields.filter(function (field) {
        return field.attrs.type === fieldOrder[i];
      })[0];
      newOrderFields.push(field);
    }

    return newOrderFields.filter(Boolean);
  };

  // forEach that can be used on nodeList
  _helpers.forEach = function (array, callback, scope) {
    for (var i = 0; i < array.length; i++) {
      callback.call(scope, i, array[i]); // passes back stuff we need
    }
  };

  // cleaner syntax for testing indexOf element
  _helpers.inArray = function (needle, haystack) {
    return haystack.indexOf(needle) !== -1;
  };

  /**
   * Remove duplicates from an array of elements
   * @param  {array} arrArg array with possible duplicates
   * @return {array}        array with only unique values
   */
  _helpers.unique = function (array) {
    return array.filter(function (elem, pos, arr) {
      return arr.indexOf(elem) === pos;
    });
  };

  return _helpers;
}
'use strict';

function formBuilderEventsFn() {
  'use strict';

  var events = {};

  events.loaded = new Event('loaded');
  events.viewData = new Event('viewData');
  events.userDeclined = new Event('userDeclined');
  events.modalClosed = new Event('modalClosed');
  events.formSaved = new Event('formSaved');

  return events;
}
'use strict';

(function ($) {
  'use strict';

  var Toggle = function Toggle(element, options) {

    var defaults = {
      theme: 'fresh',
      labels: {
        off: 'Off',
        on: 'On'
      }
    };

    var opts = $.extend(defaults, options),
        $kcToggle = $('<div class="kc-toggle"/>').insertAfter(element).append(element);

    $kcToggle.toggleClass('on', element.is(':checked'));

    var kctOn = '<div class="kct-on">' + opts.labels.on + '</div>',
        kctOff = '<div class="kct-off">' + opts.labels.off + '</div>',
        kctHandle = '<div class="kct-handle"></div>',
        kctInner = '<div class="kct-inner">' + kctOn + kctHandle + kctOff + '</div>';

    $kcToggle.append(kctInner);

    $kcToggle.click(function () {
      element.attr('checked', !element.attr('checked'));
      $(this).toggleClass('on');
    });
  };

  $.fn.kcToggle = function (options) {
    var toggle = this;
    return toggle.each(function () {
      var element = $(this);
      if (element.data('kcToggle')) {
        return;
      }
      var kcToggle = new Toggle(element, options);
      element.data('kcToggle', kcToggle);
    });
  };
})(jQuery);
'use strict';

(function ($) {
  var FormBuilder = function FormBuilder(objOS_a, strFormID_a, options, element) {
	var os = objOS_a;
	var m_objThis = this;
	var m_strFormID = strFormID_a;
    var formBuilder = this;

    var defaults = {
      controlPosition: 'right',
      controlOrder: ['autocomplete', 'button', 'checkbox', 'checkbox-group', 'd_abnlookup', 'd_audio', 'd_barcode', 'd_button', 'd_chart', 'd_date', 'd_description', 'd_document', 'd_heading', 'd_html', 'd_texthtml', 'd_codeeditor', 'd_image',  'd_metadata', 'd_multilinetext', 'd_number', 'd_list', 'd_multilist','d_password', 'd_relatedlinks','d_spacer', 'd_text',  'd_time', 'd_url', 'd_version', 'd_video', 'd_yesno', 'd_gps', 'date', 'file', 'header', 'hidden', 'mitsukibo', 'paragraph', 'radio-group', 'formheadersection', 'internaluseformheadersection', 'dataheadersection', 'datasection', 'internaluseonlysection', 'select', 'text', 'textarea'],
      dataType: 'xml',
      /**
       * Field types to be disabled
       * ['text','select','textarea','radio-group','hidden','file','date','checkbox-group','checkbox','button','autocomplete']
       */
      disableFields: ['autocomplete', 'hidden'],
      // Uneditable fields or other content you would like to appear before and after regular fields:
      append: false,
      prepend: false,
      // array of objects with fields values
      // ex:
      // defaultFields: [{
      //   label: 'First Name',
      //   name: 'first-name',
      //   required: 'true',
      //   description: 'Your first name',
      //   type: 'text'
      // }, {
      //   label: 'Phone',
      //   name: 'phone',
      //   description: 'How can we reach you?',
      //   type: 'text'
      // }],
      defaultFields: [],
      fieldRemoveWarn: false,
      roles: {
        1: 'Administrator'
      },
      messages: {
        d_abnlookup : 'ABN Lookup',
        d_audio : 'Audio',
        d_barcode: 'Barcode',
		d_button : 'Button',
        d_chart : 'Chart',
        d_date: 'Date',
        d_document: 'Document',
        d_gps : 'GPS',
        d_heading: 'Heading',
		d_html: 'HTML',
		d_texthtml: 'Text HTML',
        d_codeeditor: 'Code Editor',
        d_image : 'Image',
        d_description: 'Instructional Text',
        d_list : 'List',
        d_multilist : 'Multi List',
        d_metadata: 'Meta Data',
        d_multilinetext: 'Multiline Text',
        d_number: 'Number',
        d_password: 'Password',
        d_relatedlinks: 'Related Links',
        d_spacer: 'Spacer',
        d_text: 'Text',
        d_time: 'Time',
        d_url: 'URL',
		d_version: 'Version',
		d_video: 'Video',
        d_yesno: 'Yes / No',

        formheadersection: 'Form Header',
        internaluseformheadersection: 'Internal Use Form Header',
        dataheadersection: 'Data Header',
        datasection: 'Form Fields',
        internaluseonlysection: 'Internal Use Only',
        sectionref: 'Ref',
		colour: 'Colour',
		tempname: 'Temp Name',
		fieldsource: 'Source',
		fieldstyle: 'Style',
        sectiontype: 'Type',

        label: 'Label',	// renamed sometimes
        name: 'Name',	// renamed sometimes

        addLink: 'Add Link',
        addOption: 'Add Option',
        allFieldsRemoved: 'All fields were removed.',
        allowSelect: 'Allow Select',
        autocomplete: 'Autocomplete',
        button: 'Button',
        cannotBeEmpty: 'This field cannot be empty',
        checkboxGroup: 'Checkbox Group',
        checkbox: 'Checkbox',
        checkboxes: 'Checkboxes',
        className: 'System Use',
        clearAllMessage: 'Are you sure you want to clear all fields?',
        clearAll: 'Clear',
        close: 'Close',
        content: 'Content',
        copy: 'Copy To Clipboard',
        dateField: 'Date',
        description: 'Help Text',
        descriptionField: 'Description',
        devMode: 'Developer Mode',
        editNames: 'Edit Names',
        editorTitle: 'Form Elements',
        editXML: 'Edit XML',
        enableOther: 'Enable &quot;Other&quot;',
        enableOtherMsg: 'Permit users to enter an unlisted option',
        infovalue : 'Tool Tip',
        fieldDeleteWarning: false,
        fieldVars: 'Field Variables',
        fieldNonEditable: 'This field cannot be edited.',
        fieldRemoveWarning: 'Are you sure you want to remove this field?',
        fileUpload: 'File Upload',
        formUpdated: 'Form Updated',
        getStarted: 'Drag a field from the right to this area',
        header: 'Header',
        hide: 'Click here to edit properties',
        hidden: 'Hidden Input',
        labelEmpty: 'Field Label cannot be empty',
        length:'Length',
        limitRole: 'Limit access to one or more of the following roles:',
        lines: 'Total Lines',
        mandatory: 'Mandatory',
        maxlength: 'Max Length',
		mitsukibovalue: 'Mitsukibo Value',
        minOptionMessage: 'This field requires a minimum of 2 options',
		mitsukibo: 'Mitsukibo Field',
        no: 'No',
        off: 'Off',
        on: 'On',
        option: 'Option',
        optional: 'optional',
        optionLabelPlaceholder: 'Label',
        optionValuePlaceholder: 'Value',
        optionEmpty: 'Option value required',
        other: 'Other',
        paragraph: 'Paragraph',
        placeholder: 'Placeholder',
        placeholders: {
          value: '',
          label: 'Label',
          text: '',
          textarea: '',
          email: 'Enter you email',
          placeholder: '',
          className: 'space separated classes',
          password: 'Enter your password',
          command: 'Command',
		  title: 'Title',
          permissions: 'Permissions',
          entity: 'Source',
		  parameters: 'Parameters'
        },
        preview: 'Preview',
        quantity: 'Quantity',
        radioGroup: 'Radio Group',
        radio: 'Radio',
        relatedLinks: 'Links',
        removeMessage: 'Click here to remove the section',
        remove: '&#215;',
        readonly: 'Read Only',
        required: 'Required',
        richText: 'Rich Text Editor',
        roles: '',
        save: 'Save',
        searchable: 'Searchable',
        selectOptions: 'Options',
        select: 'Select',
        selectColor: 'Select Color',
        selectionsMessage: 'Allow Multiple Selections',
        size: 'Size',
        sizes: {
          xs: 'Extra Small',
          sm: 'Small',
          m: 'Default',
          lg: 'Large'
        },
        sortable: 'Sortable',
        style: 'Style',
        styles: {
          btn: {
            'default': 'Default',
            danger: 'Danger',
            info: 'Info',
            primary: 'Primary',
            success: 'Success',
            warning: 'Warning'
          }
        },
        subtype: 'Type',
        subtypes: {
          text: ['text', 'password', 'email', 'color'],
          button: ['button', 'formheadersection', 'internaluseformheadersection', 'dataheadersection', 'datasection', 'internaluseonlysection', 'submit'],
          header: ['h1', 'h2', 'h3'],
          paragraph: ['p', 'address', 'blockquote', 'canvas', 'output']
        },
        text: 'Text Field',
        textArea: 'Text Area',
        toggle: 'Toggle',
        warning: 'Warning!',
        value: 'Value',
        viewXML: '&lt;/&gt;',
        yes: 'Yes'
      },
      notify: {
        error: function error(message) {
          return logDebug("form-builder:" + message);
        },
        success: function success(message) {
          return logDebug("form-builder:" + message);
        },
        warning: function warning(message) {
          return logDebug("form-builder:" + message);
        }
      },
      sortableControls: false,
      prefix: 'form-builder-'
    };

    // @todo function to set parent types for subtypes
    defaults.messages.subtypes.password = defaults.messages.subtypes.text;
    defaults.messages.subtypes.email = defaults.messages.subtypes.text;
    defaults.messages.subtypes.color = defaults.messages.subtypes.text;
    defaults.messages.subtypes.submit = defaults.messages.subtypes.button;

    var opts = $.extend(true, defaults, options),
        elem = $(element),
        frmbID = 'frmb-' + $('ul[id^=frmb-]').length++;

    opts.formID = frmbID;

    formBuilder.element = element;

    var $sortableFields = $('<ul/>').attr('id', frmbID).addClass('frmb');
    var _helpers = formBuilderHelpersFn(opts, formBuilder);

    formBuilder.layout = _helpers.editorLayout(opts.controlPosition);

    var lastID = frmbID + '-fld-1',
        boxID = frmbID + '-control-box';

    // create array of field objects to cycle through
    var frmbFields = [{
      label: opts.messages.textArea,
      attrs: {
        type: 'textarea',
        className: 'text-area',
        name: 'textarea',
		tip: 'todo 1'
      }
    }, {
      label: opts.messages.mitsukibo,
      attrs: {
        type: 'mitsukibo',
        className: 'mitsukibo-input',
        name: 'mitsukibo-input',
		tip: 'This is a test field - not for production use'
      }
    }, {
      label: opts.messages.d_abnlookup,
      attrs: {
        type: 'd_abnlookup',
        className: 'd_abnlookup-input',
        name: 'd_abnlookup-input',
		tip: 'todo 2'
      }
    }, {
      label: opts.messages.d_audio,
      attrs: {
        type: 'd_audio',
        className: 'd_audio',
        name: 'd_audio',
		tip: 'todo 3'
      }
    }, {
      label: opts.messages.d_barcode,
      attrs: {
        type: 'd_barcode',
        className: 'd_barcode-input',
		tip: 'todo 4'
      }
    },
    {
        label: opts.messages.d_chart,
        attrs: {
            type: 'd_chart',
            className: 'd_chart',
            name: 'd_chart',
            tip: 'Chart'
        }
    }, {
      label: opts.messages.d_button,
      attrs: {
        type: 'd_button',
        className: 'd_button',
        name: 'd_button-input',
		tip: 'todo 18'
      }
    }, {
      label: opts.messages.d_description,
      attrs: {
        type: 'd_description',
        className: 'd_description',
        name: 'd_description-input',
		tip: 'todo 5'
      }
    }, {
      label: opts.messages.d_document,
      attrs: {
        type: 'd_document',
        className: 'd_document',
        name: 'd_document',
		tip: 'todo 6'
      }
    }, {
      label: opts.messages.d_gps,
      attrs: {
        type: 'd_gps',
        className: 'd_gps-input',
        name: 'd_gps-input',
		tip: 'todo 7'
      }
    }, {
      label: opts.messages.d_heading,
      attrs: {
        type: 'd_heading',
        className: 'd_heading',
        name: 'd_heading-input',
		tip: 'todo 8'
      }
    }, {
      label: opts.messages.d_html,
      attrs: {
        type: 'd_html',
        className: 'd_html-input',
        name: 'd_html-input',
		tip: 'todo 9'
      }
    }, {
      label: opts.messages.d_texthtml,
      attrs: {
        type: 'd_texthtml',
        className: 'd_texthtml-input',
        name: 'd_texthtml-input',
		tip: 'todo 10'
      }
    }, {
      label: opts.messages.d_codeeditor,
      attrs: {
        type: 'd_codeeditor',
        className: 'd_codeeditor-input',
        name: 'd_codeeditor-input',
    tip: 'todo 10'
      }
    }, {
      label: opts.messages.d_image,
      attrs: {
        type: 'd_image',
        className: 'd_image',
        name: 'd_image',
		tip: 'todo 11'
      }
    }, {
      label: opts.messages.d_metadata,
      attrs: {
        type: 'd_metadata',
        className: 'd_metadata-input',
        name: 'd_metadata-input',
		tip: 'todo 12'
      }
    }, {
      label: opts.messages.d_multilinetext,
      attrs: {
        type: 'd_multilinetext',
        className: 'd_multilinetext-input',
        name: 'd_multilinetext-input',
		tip: 'todo 13'
      }
    }, {
      label: opts.messages.d_number,
      attrs: {
        type: 'd_number',
        className: 'd_number-input',
        name: 'd_number-input',
		tip: 'todo 14'
      }
    }, {
      label: opts.messages.d_text,
      attrs: {
        type: 'd_text',
        className: 'd_text-input',
        name: 'd_text-input',
		tip: 'todo 15'
      }
    }, {
      label: opts.messages.d_date,
      attrs: {
        type: 'd_date',
        className: 'd_date',
        name: 'date-input',
		tip: 'todo 16'
      }
    }, {
      label: opts.messages.d_password,
      attrs: {
        type: 'd_password',
        className: 'd_password',
        name: 'password-input',
		tip: 'todo 17'
      }
    }, {
      label: opts.messages.d_url,
      attrs: {
        type: 'd_url',
        className: 'd_url',
        name: 'd_url-input',
		tip: 'todo 18'
      }
    }, {
      label: opts.messages.d_version,
      attrs: {
        type: 'd_version',
        className: 'd_version',
        name: 'd_version-input',
		tip: 'todo 19'
      }
    }, {
      label: opts.messages.d_video,
      attrs: {
        type: 'd_video',
        className: 'd_video',
        name: 'd_video',
		tip: 'todo 20'
      }
    }, {
      label: opts.messages.d_spacer,
      attrs: {
        type: 'd_spacer',
        className: 'd_spacer',
        name: 'd_spacer-input',
		tip: 'todo 21'
      }
    }, {
      label: opts.messages.d_time,
      attrs: {
        type: 'd_time',
        className: 'd_time-input',
        name: 'd_time-input',
		tip: 'todo 22'
      }
    },{
      label: opts.messages.text,
      attrs: {
        type: 'text',
        className: 'text-input',
        name: 'text-input',
		tip: 'todo 23'
      }
    }, {
      label: opts.messages.select,
      attrs: {
        type: 'select',
        className: 'select',
        name: 'select',
		tip: 'todo 24'
      }
    }, {
      label: opts.messages.d_list,
      attrs: {
        type: 'd_list',
        className: 'd_list',
        name: 'd_list',
		tip: 'todo 25'
      }
    }, {
      label: opts.messages.d_multilist,
      attrs: {
        type: 'd_multilist',
        className: 'd_multilist',
        name: 'd_multilist',
		tip: 'todo 26'
      }
    }, {
      label: opts.messages.d_relatedlinks,
      attrs: {
        type: 'd_relatedlinks',
        className: 'd_relatedlinks',
        name: 'd_relatedlinks',
		tip: 'todo 27'
      }
    }, {
      label: opts.messages.radioGroup,
      attrs: {
        type: 'radio-group',
        className: 'radio-group',
        name: 'radio-group',
		tip: 'todo 28'
      }
    }, {
      label: opts.messages.paragraph,
      attrs: {
        type: 'paragraph',
        className: 'paragraph',
		tip: 'todo 29'
      }
    }, {
      label: opts.messages.hidden,
      attrs: {
        type: 'hidden',
        className: 'hidden-input',
        name: 'hidden-input',
		tip: 'todo 30'
      }
    }, {
      label: opts.messages.header,
      attrs: {
        type: 'header',
        className: 'header',
		tip: 'todo 31'
      }
    }, {
      label: opts.messages.fileUpload,
      attrs: {
        type: 'file',
        className: 'file-input',
        name: 'file-input',
		tip: 'todo 32'
      }
    }, {
      label: opts.messages.dateField,
      attrs: {
        type: 'date',
        className: 'calendar',
        name: 'date-input',
		tip: 'todo 33'
      }
    }, {
      label: opts.messages.checkboxGroup,
      attrs: {
        type: 'checkbox-group',
        className: 'checkbox-group',
        name: 'checkbox-group',
		tip: 'todo 34'
      }
    }, {
      label: opts.messages.checkbox,
      attrs: {
        type: 'checkbox',
        className: 'checkbox',
        name: 'checkbox',
		tip: 'todo 35'
      }
    }, {
      label: opts.messages.d_yesno,
      attrs: {
        type: 'd_yesno',
        className: 'd_yesno',
        name: 'd_yesno',
		tip: 'todo 36'
      }
    }, {
      label: opts.messages.button,
      attrs: {
        type: 'button',
        className: 'button-input',
        name: 'button',
		tip: 'todo 37'
      }
    }, {
      label: opts.messages.formheadersection,
      attrs: {
		  sectiontype: 'FORMHEADER',
        type: 'formheadersection',
        className: 'dragablesection',
        name: 'button',
		tip: 'todo 38'
      }
    }, {
      label: opts.messages.internaluseformheadersection,
      attrs: {
		  sectiontype: 'INTERNALUSEFORMHEADER',
        type: 'internaluseformheadersection',
        className: 'dragablesection',
        name: 'button',
		tip: 'todo 39'
      }
    }, {
      label: opts.messages.dataheadersection,
      attrs: {
		  sectiontype: 'DATAHEADER',
        type: 'dataheadersection',
        className: 'dragablesection',
        name: 'button',
		tip: 'todo 40'
      }
    }, {
      label: opts.messages.datasection,
      attrs: {
		  sectiontype: 'DATA',
        type: 'datasection',
        className: 'dragablesection',
        name: 'button',
		tip: 'Form Fields sections should contain the fields that you require your data entry staff to enter data into.  There can be multiple Form Fields sections on the one form to allow a more natural staged data entry experience.'
      }
    }, {
      label: opts.messages.internaluseonlysection,
      attrs: {
		  sectiontype: 'INTERNALUSEONLY',
        type: 'internaluseonlysection',
        className: 'dragablesection',
        name: 'button',
		tip: 'Internal Use Only sections should contain the fields that you require for internal office use to be captured by office staff to supliment the data capture by the data entry staff.'
      }
    }, {
      label: opts.messages.autocomplete,
      attrs: {
        type: 'autocomplete',
        className: 'autocomplete',
        name: 'autocomplete',
		tip: 'todo 43'
      }
    }];

    frmbFields = _helpers.orderFields(frmbFields);
	//frmbFields.sort(function(a, b) { return a.label > b.label; });	// JC, didn't seem to sort the fields


    if (opts.disableFields) {
      // remove disabledFields
      frmbFields = frmbFields.filter(function (field) {
        return !_helpers.inArray(field.attrs.type, opts.disableFields);
      });
    }

    // Create draggable fields for formBuilder

    var cbUl = _helpers.markup('ul', null, { id: boxID, className: 'frmb-control' });

    if (opts.sortableControls) {
      cbUl.classList.add('sort-enabled');
    }

    var $cbUL = $(cbUl);

    // Loop through
    for (var i = frmbFields.length - 1; i >= 0; i--) {

	  var strElementID = getGUID('e');
	  var strTip = frmbFields[i].attrs.tip;

      var $field = $('<li/>', {
        'class': 'icon-' + frmbFields[i].attrs.className,
        'type': frmbFields[i].type,
        'name': frmbFields[i].className,
        'label': frmbFields[i].label
      });

      $field.data('newFieldData', frmbFields[i]);

      var typeLabel = _helpers.markup('span', frmbFields[i].label, { 'id':strElementID, 'tip':strTip, 'style':'display:block; width:100%;' });
      $field.html(typeLabel).appendTo($cbUL);

		// qtip doesn't support items added after the script is loaded, so use the live with mouseover to trigger the qtip
		$('#' + strElementID).live("mouseover", function()
		{
			strElementID = $(this).attr('id');
			strTip = $(this).attr('tip');

			os.showTip(m_strFormID, '#' + strElementID, 'top right', 'bottom left', 2, strTip, false, true);
		});
    }

    var viewDataText = opts.dataType === 'xml' ? opts.messages.viewXML : opts.messages.viewJSON;

    // Build our headers and action links
    var viewData = _helpers.markup('button', viewDataText, {
      id: frmbID + '-view-data',
      type: 'button',
      className: 'view-data btn btn-default'
    }),
        clearAll = _helpers.markup('button', opts.messages.clearAll, {
      id: frmbID + '-clear-all',
      type: 'button',
      className: 'clear-all btn btn-default'
    }),
        saveAll = _helpers.markup('button', opts.messages.save, {
      className: 'btn btn-primary ' + opts.prefix + 'save',
      id: frmbID + '-save',
      type: 'button'
    }),
        formActions = _helpers.markup('div', [clearAll, viewData, saveAll], {
      className: 'form-actions btn-group'
    }).outerHTML;

    // Sortable fields
    $sortableFields.sortable({
      cursor: 'move',
      opacity: 0.9,
      revert: 150,
      beforeStop: _helpers.beforeStop,
      start: _helpers.startMoving,
      stop: _helpers.stopMoving,
      cancel: 'input, select, d_chart, d_list, d_multilist, d_relatedlinks, .disabled, .form-group, .btn',
      placeholder: 'frmb-placeholder'
    });

    // ControlBox with different fields
    $cbUL.sortable({
      helper: 'clone',
      opacity: 0.9,
      connectWith: $sortableFields,
      cursor: 'move',
      placeholder: 'ui-state-highlight',
      start: _helpers.startMoving,
      stop: _helpers.stopMoving,
      revert: 150,
      beforeStop: _helpers.beforeStop,
      update: function update(event, ui) {
        if (_helpers.doCancel) {
          return false;
        }
        event = event;
        if (ui.item.parent()[0] === $sortableFields[0]) {
          prepFieldVars(ui.item, true);
          _helpers.doCancel = true;
        } else {
          _helpers.setFieldOrder($cbUL);
          _helpers.doCancel = !opts.sortableControls;
        }
      }
    });

    var $stageWrap = $('<div/>', {
      id: frmbID + '-stage-wrap',
      'class': 'stage-wrap ' + formBuilder.layout.stage
    });

	var $heading1 = '';
	var $heading2 = '';
	if (opts.internalmode === 'formsection')
	{
		$heading1 = '<b>Current Fields</b>';
		$heading2 = '<b>Available Field Types</b>';
	}
	else if (opts.internalmode === 'form')
	{
		$heading1 = '<b>Current Sections</b>';
		$heading2 = '<b>Available Section Types</b>';
	}

	$stageWrap.prepend($heading1);

    var $formWrap = $('<div/>', {
      id: frmbID + '-form-wrap',
      'class': 'form-wrap form-builder' + _helpers.mobileClass()
    });

    elem.before($stageWrap).appendTo($stageWrap);

    var cbWrap = $('<div/>', {
      id: frmbID + '-cb-wrap',
      'class': 'cb-wrap ' + formBuilder.layout.controls
    }).append($cbUL[0], formActions);

    $stageWrap.append($sortableFields, cbWrap);
    $stageWrap.before($formWrap);
    $formWrap.append($stageWrap, cbWrap);

	cbWrap.prepend($heading2);

    var saveAndUpdate = _helpers.debounce(function (evt) {
      if (evt) {
        if (evt.type === 'keyup' && this.name === 'className') {
          return false;
        }
      }

      var $field = $(this).parents('.form-field:eq(0)');
      _helpers.updatePreview($field);
      _helpers.save();
    });

    // Save field on change
	$sortableFields.unbind('change blur keyup', '.form-elements input, .form-elements select, .form-elements textarea');
    $sortableFields.on('change blur keyup', '.form-elements input, .form-elements select, .form-elements textarea', saveAndUpdate);

    // Add append and prepend options if necessary
    var nonEditableFields = function nonEditableFields() {
      var cancelArray = [];

      if (opts.prepend && !$('.disabled.prepend', $sortableFields).length) {
        var prependedField = _helpers.markup('li', opts.prepend, { className: 'disabled prepend' });
        cancelArray.push(true);
        $sortableFields.prepend(prependedField);
      }

      if (opts.append && !$('.disabled.append', $sortableFields).length) {
        var appendedField = _helpers.markup('li', opts.append, { className: 'disabled append' });
        cancelArray.push(true);
        $sortableFields.append(appendedField);
      }

      if (cancelArray.some(function (elem) {
        return elem === true;
      })) {
        $stageWrap.removeClass('empty');
      }
    };

    var prepFieldVars = function prepFieldVars($field) {
      var isNew = arguments.length <= 1 || arguments[1] === undefined ? false : arguments[1];

      var field = {};

      if ($field instanceof jQuery) {
        var fieldData = $field.data('newFieldData');

        if (fieldData) {
          field = fieldData.attrs;
          field.label = fieldData.label;
        } else {
          var attrs = $field[0].attributes;

          if (!isNew) {
            field.values = $field.children().map(function (index, elem) {

            var objOptions = {};

            objOptions.label = $(elem).text();
            if($(elem).attr('value'))
            {
                objOptions.value = $(elem).attr('value');
            }
            if($(elem).attr('permissions'))
            {
                objOptions.permissions = $(elem).attr('permissions');
            }
            if($(elem).attr('command'))
            {
                objOptions.command = $(elem).attr('command');
            }
            if($(elem).attr('parameters'))
            {
                objOptions.parameters = $(elem).attr('parameters');
            }

            objOptions.selected = Boolean($(elem).attr('selected'));

            return objOptions;
            });
          }


          for (var i = attrs.length - 1; i >= 0; i--) {
            field[attrs[i].name] = attrs[i].value;
          }
        }
      } else {
        field = $field;
      }

	  if (field.className === 'dragablesection')
	  {
		  field.className = 'btn btn-default fb-section-button';
	  }
	  //this section validate and set default value on each of the field.
      field.label = _helpers.htmlEncode(field.label);
      field.name = isNew ? nameAttr(field) : field.name;
      field.role = field.role;
      field.className = field.className || field.class;
      field.required = field.required === 'Y' || field.required === true;
      field.readonly = field.readonly === 'Y' || field.readonly === true;
      field.searchable = field.searchable === 'Y' || field.searchable === true;
      field.sortable = field.sortable === 'Y' || field.sortable === true;
      field.maxlength = field.maxlength;
      field.length = field.length;
      field.value = field.value;
      field.infovalue = field.infovalue;
      field.quantity = field.quantity;
      field.sectionref = field.sectionref;
	  field.colour = field.colour;
	  field.tempname = isNew ? nameAttr(field) : field.tempname;
	  field.fieldstyle = field.fieldstyle;
	  field.fieldsource = field.fieldsource;
      //field.sectiontype= field.sectiontype;
      field.toggle = field.toggle;
      field.description = field.description !== undefined ? _helpers.htmlEncode(field.description) : '';

      var match = /(?:^|\s)btn-(.*?)(?:\s|$)/g.exec(field.className);
      if (match) {
        field.style = match[1];
      }

      appendNewField(field);
      $stageWrap.removeClass('empty');
    };


	var m_arrBuilderJSONProblems = [];

	function clearBuilderJSONProblems()
	{
		m_arrBuilderJSONProblems = [];
	}

	function fixBuilderJSON(os_a)
	{
		os_a.after(500, function()
		{
			var strMessage = "";

			processArray(m_arrBuilderJSONProblems, function(objProblem_a)
			{
				// fix type 1: some classes were incorrectly put into the fieldstyle instead of className, so now transfer them if that is the case
				if (objProblem_a.fixtypeid === 1)
				{
					var strFieldID = objProblem_a.fixelementid;
					var strFieldNameID = 'name-' + strFieldID;
					var strFieldStyleID = 'fieldstyle-' + strFieldID;
					var strClassNameID = 'className-' + strFieldID;
					var strFixData = objProblem_a.fixdata;
					var strFieldName = $('#' + strFieldNameID).val();
					var strFieldStyleValue = $('#' + strFieldStyleID).val();

					if (strFieldStyleValue == strFixData)
					{
						$('#' + strClassNameID).val('form-control d_list ' + strFieldStyleValue);
						$('#' + strFieldStyleID).val('');
						_helpers.updatePreview($('#' + strFieldID));	// commit the changed field

						if (strMessage.length > 0)
						{
							strMessage += "<br>";
						}
						strMessage += strFieldName + ":<br>";
						strMessage += "class '" + htmlEncode(strFixData) + "' incorrectly put into Style class list.<br>";
						strMessage += "Now moved to System class list.<br>";
					}
				}
			});

			if (strMessage.length > 0)
			{
				_helpers.save();	// make the form dirty
				os_a.dialogAlertScroll(strMessage, doNothing);
			}
		});
	}

	function logBuilderJSONProblem(intFixTypeID_a, strFixElementID_a, strFixData_a)
	{
		var blnFound = false;

		// see if already logged
		processArray(m_arrBuilderJSONProblems, function(objProblem_a)
		{
			if ((objProblem_a.fixtypeid == intFixTypeID_a) && (objProblem_a.strFixElementID_a == strFixElementID_a))
			{
				blnFound = true;
				return true;
			}
		});

		if (blnFound === false)
		{
			m_arrBuilderJSONProblems.push({ "fixtypeid": intFixTypeID_a, "fixelementid": strFixElementID_a, "fixdata": strFixData_a });
		}
	}

    // Parse saved XML template data
    var getXML = function getXML() {
      var xml = '';

	  clearBuilderJSONProblems();	// clear builder fixes

      if (formBuilder.formData) {
        xml = formBuilder.formData;
      } else if (elem.val() !== '') {
		xml = $.trim(formBuilder.element.value);
	    xml = _helpers.massageXML(xml);
		xml = $.parseXML(xml);
      } else {
        xml = false;
      }

      var fields = $(xml).find('field');
      if (fields.length > 0) {
        formBuilder.formData = xml;
        fields.each(function () {
          prepFieldVars($(this));
        });
      } else if (!xml) {
        // Load default fields if none are set
        if (opts.defaultFields && opts.defaultFields.length) {
          opts.defaultFields.reverse();
          for (var i = opts.defaultFields.length - 1; i >= 0; i--) {
            prepFieldVars(opts.defaultFields[i]);
          }
          $stageWrap.removeClass('empty');
          _helpers.save();
        } else if (!opts.prepend && !opts.append) {
          $stageWrap.addClass('empty').attr('data-content', opts.messages.getStarted);
        }
      }

      $('li.form-field:not(.disabled)', $sortableFields).each(function () {
        _helpers.updatePreview($(this));
      });

      nonEditableFields();

	  fixBuilderJSON(os);	// we now have a list of fixes, fix them
    };

    var loadData = function loadData() {

      var doLoadData = {
        xml: getXML,
        json: function json() {
          logDebug('form-builder:coming soon');
        }
      };

      doLoadData[opts.dataType]();
    };

    // callback to track disabled tooltips
	$sortableFields.unbind('mousemove', 'li.disabled');
    $sortableFields.on('mousemove', 'li.disabled', function (e) {
      $('.frmb-tt', this).css({
        left: e.offsetX - 16,
        top: e.offsetY - 34
      });
    });

    // callback to call disabled tooltips
	$sortableFields.unbind('mouseenter', 'li.disabled');
    $sortableFields.on('mouseenter', 'li.disabled', function () {
      _helpers.disabledTT.add($(this));
    });

    // callback to call disabled tooltips
	$sortableFields.unbind('mouseleave', 'li.disabled');
    $sortableFields.on('mouseleave', 'li.disabled', function () {
      _helpers.disabledTT.remove($(this));
    });

    var nameAttr = function nameAttr(field) {
      //var epoch = new Date().getTime();
      //return field.type + '-' + epoch;
	  return getGUID('a');
    };

    // multi-line textarea
    var appendTextarea = function appendTextarea(values) {
      appendFieldLi(opts.messages.textArea, advFields(values), values);
    };

    var appendd_html = function appendd_html(values) {
      appendFieldLi(opts.messages.d_html, advFields(values), values);
    };

    var appendd_texthtml = function appendd_texthtml(values) {
      appendFieldLi(opts.messages.d_texthtml, advFields(values), values);
    };

    var appendd_codeeditor = function appendd_codeeditor(values) {
      appendFieldLi(opts.messages.d_codeeditor, advFields(values), values);
    };


    var appendd_multilinetext = function appendd_multilinetext(values) {
      appendFieldLi(opts.messages.d_multilinetext, advFields(values), values);
    };


    var appendInput = function appendInput(values) {
      var type = values.type || 'text' || 'mitsukibo'  || 'd_abnlookup' ||'d_barcode' || 'd_gps' || 'd_metadata' || 'd_number' || 'd_text' || 'd_time';
      appendFieldLi(opts.messages[type], advFields(values), values);
    };

    var appendLink = function appendLink(values) {
      if (!values.values || !values.values.length) {
        values.values = [{
          selected: true
        }];

			// default link when dragging a new datatype (since we must have at least 1)
            values.values = values.values.map(function (elem, index) {
              elem.label = '';//opts.messages.link + ' ' + (index + 1);
              elem.permissions = '';
              elem.command = ''; //_helpers.hyphenCase(elem.label);
			  elem.parameters = '';
              return elem;
            });
      }

      var field = '';

      field += advFields(values);
      field += '<div class="form-group field-options">';
      field += '<label class="false-label">' + opts.messages.relatedLinks + '</label>';
      field += '<div class="sortable-links-wrap">';

        field += '<ol class="sortable-links">';
          for (i = 0; i < values.values.length; i++) {
            field += selectFieldLinks(values.name, values.values[i]);
          }

      field += '</ol>';
        var addLink = _helpers.markup('a', opts.messages.addLink, { className: 'add add-link' });
      field += _helpers.markup('div', addLink, { className: 'link-actions', style: 'text-align:right;cursor:move;' }).outerHTML;
      field += '</div>';
      field += '</div>';
      appendFieldLi(opts.messages.d_relatedlinks, field, values);

      $('.sortable-links').sortable(); // making the dynamically added link fields sortable.
    };

    /**
     * Add data for field with options [select, checkbox-group, radio-group]
     *
     * @todo   refactor this nasty crap, its actually painful to look at
     * @param  {object} values
     */
    var appendSelectList = function appendSelectList(values) {
      if (!values.values || !values.values.length) {
        values.values = [{
          selected: true
        }, {
          selected: false
        }];

            values.values = values.values.map(function (elem, index) {
              elem.label = '';//opts.messages.option + ' ' + (index + 1);
              elem.value = '';//_helpers.hyphenCase(elem.label);
              return elem;
            });
      }

      var field = '';

      field += advFields(values);
      field += '<div class="form-group field-options">';
      field += '<label class="false-label">' + opts.messages.selectOptions + '</label>';
      field += '<div class="sortable-options-wrap">';
      if (values.type === 'select') {
        field += '<div class="allow-multi">';
        field += '<input type="checkbox" id="multiple_' + lastID + '" name="multiple"' + (values.multiple ? 'checked="checked"' : '') + '>';
        field += '<label class="multiple" for="multiple_' + lastID + '">' + opts.messages.selectionsMessage + '</label>';
        field += '</div>';
      }
      if (values.type === 'd_list') {
        var strSingleDropdownChecked = '';
        var strSingleRadioChecked = '';
        var strEntityPickerChecked = '';
        var strPredictiveTextChecked = '';
		var strMultiCheckboxChecked = '';
		var strMultiDropdownChecked = '';

		// note: fieldstyle is checked because of a previous bug that was incorrectly placing the class into the fieldstyle
		var strFieldStyle = '';
		var strClassName = '';

		try
		{
			strFieldStyle = values.fieldstyle;
			if (strFieldStyle === undefined) { strFieldStyle = ''; }
			strClassName = values.className;
			if (strClassName === undefined) { strClassName = ''; }

			//alert(strFieldStyle + ":" + strClassName);
			if ((strFieldStyle.indexOf('d_singleradio') >= 0) || (strClassName.indexOf('d_singleradio') >= 0))
			{
				strSingleRadioChecked = 'checked="checked"';
				logBuilderJSONProblem(1, lastID, 'd_singleradio');
			}
			else if ((strFieldStyle.indexOf('d_entitypicker') >= 0) || (strClassName.indexOf('d_entitypicker') >= 0))
			{
				strEntityPickerChecked = 'checked="checked"';
				logBuilderJSONProblem(1, lastID, 'd_entitypicker');
      }
			else if ((strFieldStyle.indexOf('d_predictivetext') >= 0) || (strClassName.indexOf('d_predictivetext') >= 0))
        {
          strPredictiveTextChecked = 'checked="checked"';
          logBuilderJSONProblem(1, lastID, 'd_predictivetext');
        }
			else //if (strFieldStyle === 'd_singledropdown')	// note this is last because we want this to be the fallback for old forms that did not specify a fieldstyle
			{
				strSingleDropdownChecked = 'checked="checked"';
				logBuilderJSONProblem(1, lastID, 'd_singledropdown');
			}

			field += '<div class="allow-multi" style="margin-bottom:12px;">';
			field += '<span class="multiple" style="margin-left:12px;" for="multiple_' + lastID + '"><input class="listtype" type="radio" id="multiple_' + lastID + '"  value="d_singledropdown" name="singletypelist_' + lastID + '"' + strSingleDropdownChecked + '> Single-Dropdown</span>';
			field += '<span class="multiple" style="margin-left:12px;" for="multiple_' + lastID + '"><input class="listtype" type="radio" id="multiple_' + lastID + '"  value="d_singleradio" name="singletypelist_' + lastID + '"' + strSingleRadioChecked + '> Single-Radio</span>';
			field += '<span class="multiple" style="margin-left:12px;" for="multiple_' + lastID + '"><input class="listtype" type="radio" id="multiple_' + lastID + '"  value="d_entitypicker" name="singletypelist_' + lastID + '"' + strEntityPickerChecked + '> Entity-Picker</span>';
			field += '<span class="multiple" style="margin-left:12px;" for="multiple_' + lastID + '"><input class="listtype" type="radio" id="multiple_' + lastID + '"  value="d_predictivetext" name="singletypelist_' + lastID + '"' + strPredictiveTextChecked + '> Predictive-Text</span>';
			field += '</div>';
		}
		catch (err)
		{
			alert(err);
		}

      }
      if (values.type === 'd_multilist') {
		  try
		  {
			strFieldStyle = values.fieldstyle;
			if (strFieldStyle === undefined) { strFieldStyle = ''; }
			strClassName = values.className;
			if (strClassName === undefined) { strClassName = ''; }

			// note: fieldstyle is checked because of a previous bug that was incorrectly placing the class into the fieldstyle
			if ((strFieldStyle.indexOf('d_multicheckbox') >= 0) || (strClassName.indexOf('d_multicheckbox') >= 0))
			{
				strMultiCheckboxChecked = 'checked="checked"';
				logBuilderJSONProblem(1, lastID, 'd_multicheckbox');
			}
			else //if (strFieldStyle === 'd_multidropdown')		// note this is last because we want this to be the fallback for old forms that did not specify a fieldstyle
			{
				strMultiDropdownChecked = 'checked="checked"';
				logBuilderJSONProblem(1, lastID, 'd_multidropdown');
			}
			field += '<div class="allow-multi" style="margin-bottom:12px;">';
			field += '<span class="multiple" style="margin-left:12px;" for="multiple_' + lastID + '"><input class="listtype" type="radio" id="multiple_' + lastID + '" value="d_multidropdown" name="multitypelist_' + lastID + '"' + strMultiDropdownChecked + '> Multi-Dropdown</span>';
			field += '<span class="multiple" style="margin-left:12px;" for="multiple_' + lastID + '"><input class="listtype" type="radio" id="multiple_' + lastID + '"  value="d_multicheckbox" name="multitypelist_' + lastID + '"' + strMultiCheckboxChecked + '> Multi-Checkbox</span>';
			field += '</div>';
		}
		catch (err)
		{
			alert(err);
		}
      }

        field += '<ol class="sortable-options">';
          for (i = 0; i < values.values.length; i++) {
            field += selectFieldOptions(values.name, values.values[i], values.values[i].selected, values.multiple);
          }

      field += '</ol>';
        var addOption = _helpers.markup('a', opts.messages.addOption, { className: 'add add-opt' });
      field += _helpers.markup('div', addOption, { className: 'option-actions' }).outerHTML;
      field += '</div>';
      field += '</div>';
      appendFieldLi(opts.messages.select, field, values);

      $('.sortable-options').sortable(); // making the dynamically added option fields sortable.
    };

    var appendNewField = function appendNewField(values) {

      // TODO: refactor to move functions into this object
      var appendFieldType = {
        'd_list': appendSelectList,
        'd_multilist': appendSelectList,
        'd_relatedlinks': appendLink,
        'select': appendSelectList,
        'rich-text': appendTextarea,
        'textarea': appendTextarea,
        'd_html': appendd_html,
        'd_texthtml': appendd_texthtml,
        'd_texthtml': appendd_codeeditor,
        'd_multilinetext': appendd_multilinetext,
        'radio-group': appendSelectList,
        'checkbox-group': appendSelectList
      };

      values = values || '';

      if (appendFieldType[values.type]) {
        appendFieldType[values.type](values);
      } else {
        appendInput(values);
      }
    };

    /**
     * Build the editable properties for the field
     * @param  {object} values configuration object for advanced fields
     * @return {String}        markup for advanced fields
     */
    var advFields = function advFields(values) {
      var advFields = [],
          key,
          checked = '',
          roles = values.role !== undefined ? values.role.split(',') : [];

      // var fieldLabelLabel = _helpers.markup('label', opts.messages.label);
      // var fieldLabelInput = _helpers.markup('input', null, {
      //   type: 'text',
      //   name: 'label',
      //   value: values.label,
      //   className: 'fld-label form-control'
      // });
      // var fieldLabel = _helpers.markup('div', [fieldLabelLabel, fieldLabelInput], {
      //   className: 'form-group label-wrap'
      // });

	  // section type
      advFields.push(textAttribute('sectiontype', values));	// correct #2 for sectionheaders

	  // section code
      advFields.push(textAttribute('name', values));	// correct #1 for sectionheaders

	  // section title
      advFields.push(textAttribute('label', values));	// correct #3 for sectionheaders

	  // section ref
      advFields.push(textAttribute('sectionref', values));	// correct #4 for sectionheaders

	  // section colour
      advFields.push(textAttribute('colour', values));	// correct #5 for sectionheaders

	  // temp name
      advFields.push(textAttribute('tempname', values));	// correct #5 for sectionheaders

	  // p_name
      advFields.push(fieldDescription(values));

	  // p_label

	  // p_value
      if (values.type === 'checkbox-group' || values.type === 'radio-group') {
        advFields.push('<div class="form-group other-wrap"><label>' + opts.messages.enableOther + '</label>');
        advFields.push('<input type="checkbox" name="enable-other" value="" ' + (values.other !== undefined ? 'checked' : '') + ' id="enable-other-' + lastID + '"/> <label for="enable-other-' + lastID + '" class="other-label">' + opts.messages.enableOtherMsg + '</label></div>');
      }

      if (values.type === 'checkbox' || values.type === 'd_yesno') {

        if(values.value === undefined || values.value === ''){
            values.value = 'N';
        }
        /*
        else if(values.value !== 'N' && values.value !== 'Y') {
            values.value = 'N';
        }
        */
      }

      advFields.push(textAttribute('maxlength', values));
	  //advFields.push(textAttribute('mitsukibovalue', values));
	  advFields.push(textAttribute('value', values));
	  advFields.push(textAttribute('length', values));
	  advFields.push(textAttribute('lines', values));
	  advFields.push(textAttribute('infovalue', values));
	  advFields.push(textAttribute('quantity', values));

	  // p_required

	  // p_readonly

	  // p_info

	  // p_searchable

	  // p_sortable

	  // disabled style
      // advFields.push(btnStyles(values.style, values.type));

	  // field style
      advFields.push(textAttribute('fieldstyle', values));	// correct #5 for fields

	  // class
      advFields.push(textAttribute('className', values)); 	// correct #5 for sectionheaders

	  // source
      advFields.push(textAttribute('fieldsource', values));	// correct #5 for fields

      // advFields.push(fieldLabel.outerHTML);

      values.size = values.size || 'm';
      values.style = values.style || 'default';

      advFields.push(subTypeField(values));

      // disabled Placeholder
      // advFields.push(textAttribute('placeholder', values));

      //disabled roles
      // advFields.push('<div class="form-group access-wrap"><label>' + opts.messages.roles + '</label>');
      //advFields.push('<input type="checkbox" name="enable_roles" value="" ' + (values.role !== undefined ? 'checked' : '') + ' id="enable_roles-' + lastID + '"/> <label for="enable_roles-' + lastID + '" class="roles-label">' + opts.messages.limitRole + '</label>');
      // advFields.push('<div class="available-roles" ' + (values.role !== undefined ? 'style="display:block"' : '') + '>');

      // for (key in opts.roles) {
        // if (opts.roles.hasOwnProperty(key)) {
          // checked = _helpers.inArray(key, roles) ? 'checked' : '';
          // advFields.push('<input type="checkbox" name="roles[]" value="' + key + '" id="fld-' + lastID + '-roles-' + key + '" ' + checked + ' class="roles-field" /><label for="fld-' + lastID + '-roles-' + key + '">' + opts.roles[key] + '</label><br/>');
        // }
      // }
      // advFields.push('</div></div>');


      return advFields.join('');
    };

    /**
     * Description meta for field
     *
     * @param  {Object} values field values
     * @return {String}        markup for attribute, @todo change to actual Node
     */
    var fieldDescription = function fieldDescription(values) {
      var noDescFields = ['header', 'paragraph', 'button', 'formheadersection', 'internaluseformheadersection', 'dataheadersection', 'datasection', 'internaluseonlysection', 'd_abnlookup','d_audio', 'd_barcode', 'd_button', 'd_chart', 'd_date','d_description', 'd_document', 'd_gps', 'd_heading', 'd_html', 'd_texthtml', 'd_codeeditor', 'd_image','d_list', 'd_metadata', 'd_multilinetext', 'd_multilist', 'd_number', 'd_password', 'd_relatedlinks','d_text' , 'd_spacer', 'd_time', 'd_url', 'd_version', 'd_video', 'd_yesno'],
          noMakeAttr = [],
          descriptionField = '';

      noDescFields = noDescFields.concat(opts.messages.subtypes.header, opts.messages.subtypes.paragraph);

      if (noDescFields.indexOf(values.type) === -1) {
        noMakeAttr.push(true);
      }

      if (noMakeAttr.some(function (elem) {
        return elem === true;
      })) {
        var fieldDescLabel = _helpers.markup('label', opts.messages.description, { 'for': 'description-' + lastID }),
            fieldDescInput = _helpers.markup('input', null, {
          type: 'text',
          className: 'fld-description form-control',
          name: 'description',
          id: 'description-' + lastID,
          value: values.description
        }),
            fieldDesc = _helpers.markup('div', [fieldDescLabel, fieldDescInput], {
          'class': 'form-group description-wrap'
        });
        descriptionField = fieldDesc.outerHTML;
      }

      return descriptionField;
    };

    /**
     * Changes a fields type
     *
     * @param  {Object} values
     * @return {String}      markup for type <select> input
     */
    var subTypeField = function subTypeField(values) {
      var subTypes = opts.messages.subtypes,
          type = values.type,
          subtype = values.subtype || '',
          subTypeField = '',
          selected = void 0;

      if (subTypes[type]) {
        var subTypeLabel = '<label>' + opts.messages.subtype + '</label>';
        subTypeField += '<select name="subtype" class="fld-subtype form-control" id="subtype-' + lastID + '">';
        subTypes[type].forEach(function (element) {
          selected = subtype === element ? 'selected' : '';
          subTypeField += '<option value="' + element + '" ' + selected + '>' + element + '</option>';
        });
        subTypeField += '</select>';
        subTypeField = '<div class="form-group subtype-wrap">' + subTypeLabel + ' ' + subTypeField + '</div>';
      }

      return subTypeField;
    };

    var btnStyles = function btnStyles(style, type) {
      var tags = {
        button: 'btn',
        section: 'btn'
      },
          styles = opts.messages.styles[tags[type]],
          styleField = '';

      if (styles) {
        var styleLabel = '<label>' + opts.messages.style + '</label>';
        styleField += '<input value="' + style + '" name="style" type="hidden" class="btn-style">';
        styleField += '<div class="btn-group" role="group">';

        Object.keys(opts.messages.styles[tags[type]]).forEach(function (element) {
          var active = style === element ? 'active' : '';
          styleField += '<button value="' + element + '" type="' + type + '" class="' + active + ' btn-xs ' + tags[type] + ' ' + tags[type] + '-' + element + '">' + opts.messages.styles[tags[type]][element] + '</button>';
        });

        styleField += '</div>';

        styleField = '<div class="form-group style-wrap">' + styleLabel + ' ' + styleField + '</div>';
      }

      return styleField;
    };

    /**
     * Generate some text inputs for field attributes, **will be replaced**
     * @param  {String} attribute
     * @param  {Object} values
     * @return {String}
     */
    var textAttribute = function textAttribute(attribute, values) {
      var placeholderFields = ['text', 'textarea', 'select'];
      var noName = ['header'];

      var textArea = ['paragraph'];

      var noMaxlength = ['checkbox', 'select', 'checkbox-group', 'd_abnlookup', 'd_audio', 'd_barcode', 'd_button', 'd_chart', 'd_date', 'd_description', 'd_document', 'd_gps', 'd_heading', 'd_html', 'd_texthtml', 'd_codeeditor', 'd_image', 'd_list', 'd_metadata', 'd_multilinetext', 'd_multilist', 'd_number','d_password', 'd_relatedlinks','d_text', 'd_spacer', 'd_time', 'd_url', 'd_version', 'd_video', 'd_yesno', 'date', 'autocomplete', 'radio-group', 'hidden', 'button', 'header', 'formheadersection', 'internaluseformheadersection', 'dataheadersection', 'datasection', 'internaluseonlysection'];
	  var noMitsukiboValue = ['autocomplete', 'button', 'checkbox', 'checkbox-group', 'd_abnlookup', 'd_audio', 'd_barcode', 'd_button', 'd_chart', 'd_date', 'd_description', 'd_document',  'd_gps', 'd_heading', 'd_html', 'd_texthtml', 'd_codeeditor', 'd_image', 'd_list', 'd_metadata', 'd_multilinetext', 'd_multilist', 'd_number', 'd_password', 'd_relatedlinks', 'd_spacer', 'd_text', 'd_version', 'd_url','d_video', 'd_yesno', 'd_time', 'date', 'file', 'header', 'hidden', 'mitsukibo', 'paragraph', 'radio-group', 'formheadersection', 'internaluseformheadersection', 'dataheadersection', 'datasection', 'internaluseonlysection', 'select', 'text', 'textarea'];	// and add more here which are not wanted
	  var noValue = ['autocomplete', 'button', 'checkbox', 'checkbox-group', 'd_heading', 'd_spacer', 'd_description', 'file', 'header', 'hidden', 'paragraph', 'radio-group', 'formheadersection', 'internaluseformheadersection', 'dataheadersection', 'datasection', 'internaluseonlysection', 'select'];    // and add more here which are not wanted
	  var noLength = ['autocomplete', 'button', 'checkbox', 'checkbox-group', 'd_audio', 'd_button', 'd_date', 'd_description','d_document', 'd_heading', 'd_image', 'd_list', 'd_multilist', 'd_relatedlinks', 'd_spacer', 'd_url', 'd_version', 'd_video', 'd_yesno', 'file', 'header', 'hidden', 'paragraph', 'radio-group', 'formheadersection', 'internaluseformheadersection', 'dataheadersection', 'datasection', 'internaluseonlysection', 'select'];    // and add more here which are not wanted
      var noLines = ['autocomplete', 'button', 'checkbox', 'checkbox-group', 'd_abnlookup', 'd_audio', 'd_barcode', 'd_button', 'd_date', 'd_description', 'd_document', 'd_gps', 'd_heading', 'd_image', 'd_list', 'd_metadata', 'd_multilist', 'd_number', 'd_password', 'd_relatedlinks', 'd_spacer', 'd_text', 'd_version', 'd_video', 'd_url', 'd_yesno', 'd_time', 'date', 'file', 'header', 'hidden', 'mitsukibo', 'paragraph', 'radio-group', 'formheadersection', 'internaluseformheadersection', 'dataheadersection', 'datasection', 'internaluseonlysection', 'select', 'text', 'textarea'];
      var noInfoValue = ['autocomplete', 'button', 'checkbox', 'checkbox-group', 'file', 'header', 'hidden', 'paragraph', 'radio-group', 'formheadersection', 'internaluseformheadersection', 'dataheadersection', 'datasection', 'internaluseonlysection', 'select'];    // and add more here which are not wanted
      var noSectionRef = ['autocomplete', 'button', 'checkbox', 'checkbox-group', 'd_abnlookup','d_audio', 'd_barcode', 'd_button', 'd_chart', 'd_date', 'd_description', 'd_document',  'd_gps',  'd_heading', 'd_html', 'd_texthtml', 'd_codeeditor', 'd_image', 'd_list', 'd_metadata', 'd_multilinetext', 'd_multilist','d_number', 'd_password', 'd_relatedlinks', 'd_spacer', 'd_text', 'd_version', 'd_video', 'd_url', 'd_yesno', 'd_time', 'date', 'file', 'header', 'hidden', 'mitsukibo', 'paragraph', 'radio-group', 'select', 'text', 'textarea'];
	  var noColour = [];
      var noTempName = ['autocomplete', 'button', 'checkbox', 'checkbox-group', 'd_abnlookup','d_audio', 'd_barcode', 'd_button', 'd_date', 'd_description', 'd_document',  'd_gps',  'd_heading', 'd_html', 'd_texthtml', 'd_codeeditor', 'd_image', 'd_list', 'd_metadata', 'd_multilinetext', 'd_multilist','d_number', 'd_password', 'd_relatedlinks', 'd_spacer', 'd_text', 'd_version', 'd_video', 'd_url', 'd_yesno', 'd_time', 'date', 'file', 'header', 'hidden', 'mitsukibo', 'paragraph', 'radio-group', 'select', 'text', 'textarea'];
      var noFieldStyle = ['formheadersection', 'internaluseformheadersection', 'dataheadersection', 'datasection', 'internaluseonlysection'];
	  var noFieldSource = ['autocomplete', 'button', 'checkbox', 'checkbox-group','d_abnlookup', 'd_audio', 'd_barcode', 'd_button', 'd_date', 'd_description', 'd_document',  'd_gps', 'd_heading', 'd_html', 'd_texthtml', 'd_codeeditor', 'd_image', 'd_metadata', 'd_multilinetext', 'd_number', 'd_password', 'd_relatedlinks','d_spacer', 'd_text', 'd_version', 'd_url','d_video', 'd_yesno', 'd_time', 'date', 'file', 'header', 'hidden', 'mitsukibo', 'paragraph', 'radio-group', 'formheadersection', 'internaluseformheadersection', 'dataheadersection', 'datasection', 'internaluseonlysection', 'select', 'text', 'textarea'];	// and add more here which are not wanted
      var noSectionType = ['autocomplete', 'button', 'checkbox', 'checkbox-group','d_abnlookup', 'd_audio', 'd_barcode', 'd_button', 'd_chart', 'd_date', 'd_description', 'd_document', 'd_gps',  'd_heading', 'd_html', 'd_texthtml', 'd_codeeditor', 'd_image',  'd_list', 'd_metadata', 'd_multilinetext', 'd_multilist', 'd_number', 'd_password', 'd_relatedlinks','d_spacer', 'd_text', 'd_version', 'd_video', 'd_url', 'd_yesno', 'd_time', 'date', 'file', 'header', 'hidden', 'mitsukibo', 'paragraph', 'radio-group', 'select', 'text', 'textarea'];
      var noQuantity = ['formheadersection', 'internaluseformheadersection', 'dataheadersection', 'internaluseonlysection'];

      var attrVal = attribute === 'label' ? values.label : values[attribute] || '';
      var attrLabel = opts.messages[attribute];
      if (attribute === 'label' && _helpers.inArray(values.type, textArea)) {
        attrLabel = opts.messages.content;
      }
      noName = noName.concat(opts.messages.subtypes.header, textArea);
      noMaxlength = noMaxlength.concat(textArea);
	  noMitsukiboValue = noMitsukiboValue.concat(textArea);
	  noValue = noValue.concat(textArea);
	  noLength = noLength.concat(textArea);
	  noLines = noLines.concat(textArea);
	  noInfoValue = noInfoValue.concat(textArea);
	  noQuantity = noQuantity.concat(textArea);
	  noSectionRef = noSectionRef.concat(textArea);
	  //noColour = noColour.concat(textArea);
	  noTempName = noTempName.concat(textArea);
	  noSectionType = noSectionType.concat(textArea);

      var placeholders = opts.messages.placeholders,
          placeholder = placeholders[attribute] || '',
          attributefield = '',
          noMakeAttr = [];

      // Field has placeholder attribute
      if (attribute === 'placeholder' && !_helpers.inArray(values.type, placeholderFields)) {
        noMakeAttr.push(true);
      }

      // Field has name attribute
      if (attribute === 'name' && _helpers.inArray(values.type, noName)) {
        noMakeAttr.push(true);
      }

      // Field has maxlength attribute
      if (attribute === 'maxlength' && _helpers.inArray(values.type, noMaxlength)) {
        noMakeAttr.push(true);
      }

      // Field has mitsukibovalue attribute
      if (attribute === 'mitsukibovalue' && _helpers.inArray(values.type, noMitsukiboValue)) {
        noMakeAttr.push(true);
      }

      if (attribute === 'value' && _helpers.inArray(values.type, noValue)) {
        noMakeAttr.push(true);
      }

      if (attribute === 'length' && _helpers.inArray(values.type, noLength)) {
        noMakeAttr.push(true);
      }

      if (attribute === 'lines' && _helpers.inArray(values.type, noLines)) {
        noMakeAttr.push(true);
      }

      if (attribute === 'infovalue' && _helpers.inArray(values.type, noInfoValue)) {
        noMakeAttr.push(true);
      }

      if (attribute === 'quantity' && _helpers.inArray(values.type, noQuantity)) {
        noMakeAttr.push(true);
      }

      if (attribute === 'sectionref' && _helpers.inArray(values.type, noSectionRef)) {
        noMakeAttr.push(true);
      }

      if (attribute === 'colour' && _helpers.inArray(values.type, noColour)) {
        noMakeAttr.push(true);
      }

      if (attribute === 'tempname' && _helpers.inArray(values.type, noTempName)) {
        noMakeAttr.push(true);
      }

      if (attribute === 'fieldstyle' && _helpers.inArray(values.type, noFieldStyle)) {
        noMakeAttr.push(true);
      }

      if (attribute === 'sectiontype' && _helpers.inArray(values.type, noSectionType)) {
        noMakeAttr.push(true);
      }

      if (attribute === 'fieldsource' && _helpers.inArray(values.type, noFieldSource)) {
        noMakeAttr.push(true);
      }

      if (!noMakeAttr.some(function (elem) {
        return elem === true;
      })) {
        var attributeLabel = '<label>' + attrLabel + '</label>';

		//if ((attribute === 'name') && (opts.entitycode === 'dataform'))
		//{
		  //attributefield += '<input readonly="readonly" style="background-color:#eeeeee;" type="text" value="' + attrVal + '" name="' + attribute + '" placeholder="' + placeholder + '" class="fld-' + attribute + ' form-control" id="' + attribute + '-' + lastID + '">';
		//}
		if (attribute === 'className')
		{
		  attributefield += '<input readonly="readonly" style="background-color:#eeeeee;" type="text" value="' + attrVal + '" name="' + attribute + '" placeholder="' + placeholder + '" class="fld-' + attribute + ' form-control" id="' + attribute + '-' + lastID + '">';
		}
		else if (attribute === 'sectiontype')
		{
		  attributefield += '<input xreadonly="readonly" style="background-color:#eeeeee;" type="text" value="' + attrVal + '" name="' + attribute + '" placeholder="' + placeholder + '" class="fld-' + attribute + ' form-control" id="' + attribute + '-' + lastID + '">';
		}
		else
		{
			if (attribute === 'label' && _helpers.inArray(values.type, textArea)) {
			  attributefield += '<textarea name="' + attribute + '" placeholder="' + placeholder + '" class="fld-' + attribute + ' form-control" id="' + attribute + '-' + lastID + '">' + attrVal + '</textarea>';
			} else {
			  attributefield += '<input type="text" value="' + attrVal + '" name="' + attribute + '" placeholder="' + placeholder + '" class="fld-' + attribute + ' form-control" id="' + attribute + '-' + lastID + '">';
			}
		}

		if (attribute === 'tempname')
		{
			attributefield = '<div class="hidden form-group ' + attribute + '-wrap">' + attributeLabel + ' ' + attributefield + '</div>';
		}
		else
		{
			attributefield = '<div class="form-group ' + attribute + '-wrap">' + attributeLabel + ' ' + attributefield + '</div>';
		}
      }

      return attributefield;
    };

    var requiredField = function requiredField(values) {
      var noRequire = ['header', 'paragraph', 'button', 'formheadersection', 'internaluseformheadersection', 'dataheadersection', 'datasection', 'internaluseonlysection', 'd_button', 'd_relatedlinks', 'd_url', 'd_yesno'],
          noMake = [],
          requireField = '';

      if (_helpers.inArray(values.type, noRequire)) {
        noMake.push(true);
      }

      if (!noMake.some(function (elem) {
        return elem === true;
      })) {

        requireField += '<div class="form-group">';
        requireField += '<label>&nbsp;</label>';
        var _requiredField = _helpers.markup('input', null, {
          className: 'required',
          type: 'checkbox',
          name: 'required-' + lastID,
          id: 'required-' + lastID,
          value: 1
        });

        _requiredField.defaultChecked = values.required;

        requireField += _requiredField.outerHTML;
        requireField += _helpers.markup('label', opts.messages.required, {
          className: 'required-label',
          'for': 'required-' + lastID
        }).outerHTML;
        requireField += '</div>';
      }
      return requireField;
    };

    var readonlyField = function readonlyField(values) {
      var noReadonly = ['header', 'paragraph', 'button', 'formheadersection', 'internaluseformheadersection', 'dataheadersection', 'datasection', 'internaluseonlysection', 'd_button', 'd_heading', 'd_relatedlinks', 'd_spacer', 'd_version'],
          noMake = [],
          readonlyFields = '';

      if (_helpers.inArray(values.type, noReadonly)) {
        noMake.push(true);
      }

      if (!noMake.some(function (elem) {
        return elem === true;
      })) {

        readonlyFields += '<div class="form-group">';
        readonlyFields += '<label>&nbsp;</label>';
        var _readonlyField = _helpers.markup('input', null, {
          className: 'readonly',
          type: 'checkbox',
          name: 'readonly-' + lastID,
          id: 'readonly-' + lastID,
          value: 1
        });

        _readonlyField.defaultChecked = values.readonly;

        readonlyFields += _readonlyField.outerHTML;
        readonlyFields += _helpers.markup('label', opts.messages.readonly, {
          className: 'required-label',
          'for': 'readonly-' + lastID
        }).outerHTML;
        readonlyFields += '</div>';
      }
      return readonlyFields;
    };

    var searchableField = function searchableField(values) {
      //var noSearchable = ['header', 'paragraph', 'button', 'formheadersection', 'internaluseformheadersection', 'dataheadersection', 'datasection', 'internaluseonlysection', 'd_abnlookup', 'd_audio', 'd_barcode', 'd_date', 'd_description', 'd_document', 'd_gps',  'd_heading', 'd_html', 'd_texthtml', 'd_image', 'd_list', 'd_metadata','d_multilinetext', 'd_multilist', 'd_number', 'd_password','d_relatedlinks','d_spacer', 'd_text', 'd_url', 'd_version', 'd_video', 'd_yesno', 'd_time', 'autocomplete',  'checkbox', 'checkbox-group', 'file', 'header', 'hidden', 'paragraph', 'radio-group', 'select', 'text', 'textarea'],
      var noSearchable = ['header', 'paragraph', 'button', 'formheadersection', 'internaluseformheadersection', 'dataheadersection', 'datasection', 'internaluseonlysection', 'd_abnlookup', 'd_audio', 'd_button', 'd_document', 'd_image', 'd_metadata', 'd_password', 'd_relatedlinks', 'd_spacer', 'd_version', 'd_video', 'autocomplete',  'checkbox', 'checkbox-group', 'file', 'header', 'hidden', 'paragraph', 'radio-group', 'select', 'text', 'textarea'],
          noMake = [],
          searchableFields = '';

      if (_helpers.inArray(values.type, noSearchable)) {
        noMake.push(true);
      }

      if (!noMake.some(function (elem) {
        return elem === true;
      })) {

        searchableFields += '<div class="form-group">';
        searchableFields += '<label>&nbsp;</label>';
        var _searchableField = _helpers.markup('input', null, {
          className: 'searchable',
          type: 'checkbox',
          name: 'searchable-' + lastID,
          id: 'searchable-' + lastID,
          value: 1
        });

        _searchableField.defaultChecked = values.searchable;

        searchableFields += _searchableField.outerHTML;
        searchableFields += _helpers.markup('label', opts.messages.searchable, {
          className: 'searchable-label',
          'for': 'searchable-' + lastID
        }).outerHTML;
        searchableFields += '</div>';
      }
      return searchableFields;
    };

    var sortableField = function sortableField(values) {
      var noSortable = ['header', 'paragraph', 'button', 'formheadersection', 'internaluseformheadersection', 'dataheadersection', 'datasection', 'internaluseonlysection','d_abnlookup', 'd_audio', 'd_barcode', 'd_button', 'd_chart', 'd_date', 'd_description', 'd_document', 'd_gps', 'd_heading', 'd_html', 'd_texthtml', 'd_codeeditor', 'd_image', 'd_list', 'd_metadata', 'd_multilinetext', 'd_multilist', 'd_number', 'd_password','d_relatedlinks', 'd_text', 'd_spacer', 'd_time', 'd_url', 'd_version', 'd_video', 'd_yesno', 'autocomplete',  'checkbox', 'checkbox-group', 'file', 'header', 'hidden', 'paragraph', 'radio-group', 'select', 'text', 'textarea'],
          noMake = [],
          sortableFields = '';

      if (_helpers.inArray(values.type, noSortable)) {
        noMake.push(true);
      }

      if (!noMake.some(function (elem) {
        return elem === true;
      })) {

        sortableFields += '<div class="form-group">';
        sortableFields += '<label>&nbsp;</label>';
        var _sortableField = _helpers.markup('input', null, {
          className: 'sortable',
          type: 'checkbox',
          name: 'sortable-' + lastID,
          id: 'sortable-' + lastID,
          value: 1
        });

        _sortableField.defaultChecked = values.sortable;

        sortableFields += _sortableField.outerHTML;
        sortableFields += _helpers.markup('label', opts.messages.sortable, {
          className: 'sortable-label',
          'for': 'sortable-' + lastID
        }).outerHTML;
        sortableFields += '</div>';
      }
      return sortableFields;
    };

    // Append the new field to the editor
    var appendFieldLi = function appendFieldLi(title, field, values) {
      var labelVal = $(field).find('input[name="label"]').val(),
          label = labelVal ? labelVal : title;

          if( values.type === 'd_spacer' )
          {
            label = labelVal ? labelVal : '';
          }

      //var delFunctionRemoved = (values.sectiontype === 'FORMHEADER' || values.sectiontype === 'DATAHEADER' ) ? '-disabled' : '';

	  var liContents = '';
	  var delBtn = '';
	  var toggleBtn = '';

	  if ((opts.entitycode === 'dataform') && (opts.internalmode === 'formsection') && (opts.sectiontype === 'formheadersection'))
	  {
		  // do nothing
	  }
	  else if ((opts.entitycode === 'dataform') && (opts.internalmode === 'formsection') && (opts.sectiontype === 'dataheadersection') && (values.type === 'd_version'))
	  {
		  // do nothing
	  }
	  else if ((opts.entitycode === 'dataform') && (opts.internalmode === 'form') && ((values.type === 'formheadersection') || (values.type === 'internaluseformheadersection') || (values.type === 'dataheadersection')))
	  {
		  // do nothing
	  }
	  else
	  {
		  // render delete and toggle buttons
		  delBtn = _helpers.markup('a', opts.messages.remove, {
			id: 'del_' + lastID,
			className: 'del-button btn delete-confirm', // + delFunctionRemoved,
			title: opts.messages.removeMessage
		  });

		  toggleBtn = _helpers.markup('a', null, {
			id: lastID + '-edit',
			className: 'toggle-form btn icon-pencil',
			title: opts.messages.hide
		  });
	  }

	  var required = values.required,
		  toggle = values.toggle || undefined,
		  tooltip = values.description !== '' ? '<span class="tooltip-element" tooltip="' + values.description + '">?</span>' : '';

      liContents = _helpers.markup('div', [toggleBtn, delBtn], { className: 'field-actions' }).outerHTML;

      //if ((values.type !== 'formheadersection') && (values.type !== 'dataheadersection') && (values.type !== 'datasection') && (values.type !== 'internaluseonlysection'))
      //if (values.type === 'd_version')
      //{
        //liContents += '<label class="field-label">' + label + '</label>' + tooltip;
      //}
	  //else if (opts.internalmode === 'formsection')
	  //{
        //liContents += '<label class="field-label">' + label + '</label>' + tooltip;
	  //}
      if (values.type !== 'd_spacer')
      {
        liContents += '<label class="field-label">' + label + '</label>' + tooltip + '<span class="required-asterisk" ' + (required ? 'style="display:inline"' : '') + '> *</span>';
      }
      else
      {
         liContents += '<label class="field-label">&nbsp;</label>' + tooltip + '<span class="required-asterisk" ' + (required ? 'style="display:inline"' : '') + '> *</span>';
      }

	  // *** this is the fields other than the checkboxes

      liContents += _helpers.markup('div', '', { className: 'prev-holder' }).outerHTML;
      liContents += '<div id="' + lastID + '-holder" class="frm-holder">';
      liContents += '<div class="form-elements">';

      if (values.type === 'checkbox') {
        liContents += '<div class="form-group">';
        liContents += '<label>&nbsp;</label>';
        liContents += '<input class="checkbox-toggle" type="checkbox" value="1" name="toggle-' + lastID + '" id="toggle-' + lastID + '"' + (toggle === 'Y' ? ' checked' : '') + ' /><label class="toggle-label" for="toggle-' + lastID + '">' + opts.messages.toggle + '</label>';
        liContents += '</div>';
      }
      liContents += field;
      liContents += _helpers.markup('a', opts.messages.close, { className: 'close-field' }).outerHTML;

      if (values.type === 'd_spacer')
      {
        liContents = liContents.replace('class="form-group label-wrap"', 'class="form-group label-wrap" style="display: none;');
      }

	  // add the fields in this order: p_name, p_label, p_value, p_required, p_readonly, p_info, p_searchable, p_sortable

	  // p_name

	  // p_label

	  // p_value

	  // *** here are the checkboxes

	  // p_required
      liContents += requiredField(values);

	  // p_readonly
      liContents += readonlyField(values);

	  // p_info

	  // p_searchable
      liContents += searchableField(values);

	  // p_sortable
      liContents += sortableField(values);

      liContents += '</div>';
      liContents += '</div>';

		var strSectionGUID = "";
		if ((values.type === 'formheadersection') || (values.type === 'internaluseformheadersection') || (values.type === 'dataheadersection') || (values.type === 'datasection') || (values.type === 'internaluseonlysection'))
		{
			strSectionGUID = values.name;
		}

      var li = _helpers.markup('li', liContents, {
        'class': values.type + '-field form-field',
		'style': 'cursor:move;',
        'type': values.type,
		'sectionguid': strSectionGUID,
        id: lastID
      }),
          $li = $(li);

      $li.data('fieldData', { attrs: values });

      if (typeof _helpers.stopIndex !== 'undefined') {
        $('> li', $sortableFields).eq(_helpers.stopIndex).after($li);
      } else {
        $sortableFields.append($li);
      }

      _helpers.updatePreview($li);

      $(document.getElementById('frm-' + lastID + '-item')).hide().slideDown(250);

      lastID = _helpers.incrementId(lastID);
    };

    // Select field html, since there may be multiple
    var selectFieldOptions = function selectFieldOptions(name, values, selected, multipleSelect) {
      var optionInputType = {
        selected: multipleSelect ? 'checkbox' : 'radio'
      };

      var defaultOptionData = {
        selected: selected,
        label: '',
        value: ''
      };

      var optionData = Object.assign(defaultOptionData, values),
          optionInputs = [];

      for (var prop in optionData) {
        if (optionData.hasOwnProperty(prop)) {
          var placeholderVal = opts.messages.placeholders[prop];
          if( prop === 'value')
          {
            placeholderVal = 'Code';
          }
          var attrs = {
            type: optionInputType[prop] || 'text',
            'class': 'option-' + prop,
            placeholder: placeholderVal,
            value: optionData[prop],
            name: name
          };
          var option = _helpers.markup('input', null, attrs);
          if (prop === 'selected') {
            option.checked = optionData.selected;
          }
          optionInputs.push(option);
        }
      }

      var removeAttrs = {
        className: 'remove btn',
        title: opts.messages.removeMessage
      };
      optionInputs.push(_helpers.markup('a', opts.messages.remove, removeAttrs));

      var field = _helpers.markup('li', optionInputs);

      return field.outerHTML;
    };

    var selectFieldLinks = function selectFieldLinks(name, values) {
      var linkInputType = {
          selected: '',
          value: ''
      };

      var defaultLinkData = {
        label: '',
        permissions: '',
        command: '',
		parameters: ''
      };

      var linkData = Object.assign(defaultLinkData, values),
          linkInputs = [];

      for (var prop in linkData) {
        if (linkData.hasOwnProperty(prop)) {

        if(typeof linkInputType[prop] === 'undefined')
        {
             var attrs = {
                type: linkInputType[prop] || 'text',
                'class': 'option-' + prop,
                placeholder: opts.messages.placeholders[prop],
                value: linkData[prop],
                name: name,
                style:'width: calc(25% - 17px)'
              };

              var option = _helpers.markup('input', null, attrs);
              linkInputs.push(option);
            }
        }
      }

      var removeAttrs = {
        className: 'removelink btn',
        title: opts.messages.removeMessage
      };
      linkInputs.push(_helpers.markup('a', opts.messages.remove, removeAttrs));

      var field = _helpers.markup('li', linkInputs);

      return field.outerHTML;
    };

    // ---------------------- UTILITIES ---------------------- //

    // delete options
	$sortableFields.unbind('click touchstart', '.remove');
    $sortableFields.on('click touchstart', '.remove', function (e) {
      var $field = $(this).parents('.form-field:eq(0)');
      e.preventDefault();
      var optionsCount = $(this).parents('.sortable-options:eq(0)').children('li').length;
      if (optionsCount <= 2) {
        opts.notify.error('Error: ' + opts.messages.minOptionMessage);
      } else {
        $(this).parent('li').slideUp('250', function () {
          $(this).remove();
          _helpers.updatePreview($field);
          _helpers.save();
        });
      }
    });

    // delete links
	$sortableFields.unbind('click touchstart', '.removelink');
    $sortableFields.on('click touchstart', '.removelink', function (e) {
      var $field = $(this).parents('.form-field:eq(0)');
      e.preventDefault();
      var optionsCount = $(this).parents('.sortable-links:eq(0)').children('li').length;
      if (optionsCount <= 1) {
        opts.notify.error('Error: ' + opts.messages.minOptionMessage);
      } else {
        $(this).parent('li').slideUp('250', function () {
          $(this).remove();
          _helpers.updatePreview($field);
          _helpers.save();
        });
      }
    });

    // touch focus
	$sortableFields.unbind('touchstart', 'input');
    $sortableFields.on('touchstart', 'input', function (e) {
      if (e.handled !== true) {
        if ($(this).attr('type') === 'checkbox') {
          $(this).trigger('click');
        } else {
          $(this).focus();
          var fieldVal = $(this).val();
          $(this).val(fieldVal);
        }
      } else {
        return false;
      }
    });

    // toggle fields
	$sortableFields.unbind('click touchstart', '.toggle-form, .close-field');
    $sortableFields.on('click touchstart', '.toggle-form, .close-field', function (e) {
      e.stopPropagation();
      e.preventDefault();
      if (e.handled !== true) {
        var targetID = $(this).parents('.form-field:eq(0)').attr('id');
        _helpers.toggleEdit(targetID);
        e.handled = true;
      } else {
        return false;
      }
    });

    /**
     * Toggles the edit mode for the given field
     * @param  {String} fieldId
     */
    _helpers.toggleEdit = function (fieldId) {
      var field = document.getElementById(fieldId),
          toggleBtn = $('.toggle-form', field),
          editMode = $('.frm-holder', field);
      field.classList.toggle('editing');
      toggleBtn.toggleClass('open');
      $('.prev-holder', field).slideToggle(250);
      editMode.slideToggle(250);
    };

    // update preview to label
	$sortableFields.unbind('keyup change', '[name="label"]');
    $sortableFields.on('keyup change', '[name="label"]', function () {
      $('.field-label', $(this).closest('li')).text($(this).val());
    });

    // remove error styling when users tries to correct mistake
    $sortableFields.delegate('input.error', 'keyup', function () {
      $(this).removeClass('error');
    });

    // update preview for description
	$sortableFields.unbind('keyup', 'input[name="description"]');
    $sortableFields.on('keyup', 'input[name="description"]', function () {
      var $field = $(this).parents('.form-field:eq(0)');
      var closestToolTip = $('.tooltip-element', $field);
      var ttVal = $(this).val();
      if (ttVal !== '') {
        if (!closestToolTip.length) {
          var tt = '<span class="tooltip-element" tooltip="' + ttVal + '">?</span>';
          $('.field-label', $field).after(tt);
        } else {
          closestToolTip.attr('tooltip', ttVal).css('display', 'inline-block');
        }
      } else {
        if (closestToolTip.length) {
          closestToolTip.css('display', 'none');
        }
      }
    });

    _helpers.updateMultipleSelect();

    // format name attribute
    $sortableFields.delegate('input[name="name"]', 'blur', function () {
      $(this).val(_helpers.safename($(this).val()));
      if ($(this).val() === '') {
        $(this).addClass('field_error').attr('placeholder', opts.messages.cannotBeEmpty);
      } else {
        $(this).removeClass('field_error');
      }
    });

    $sortableFields.delegate('input.fld-maxlength', 'blur', function () {
      $(this).val(_helpers.forceNumber($(this).val()));
    });

    $sortableFields.delegate('input.fld-mitsukibovalue', 'blur', function () {
      //$(this).val(_helpers.forceNumber($(this).val()));	// use this if you want a numeric
	  $(this).val($(this).val());	// use this if you want non-numeric
    });

    $sortableFields.delegate('input.fld-value', 'blur', function () {
      $(this).val($(this).val());   // use this if you want non-numeric
    });

    //$sortableFields.delegate('input.fld-code', 'blur', function () {
      //$(this).val($(this).val());   // use this if you want non-numeric
    //});

    $sortableFields.delegate('input.fld-length', 'blur', function () {
      $(this).val(_helpers.forceNumber($(this).val()));   // use this if you want a numeric
    });

    $sortableFields.delegate('input.fld-lines', 'blur', function () {
      $(this).val(_helpers.forceNumber($(this).val()));   // use this if you want a numeric
    });


    $sortableFields.delegate('input.fld-infovalue', 'blur', function () {
      $(this).val($(this).val());   // use this if you want non-numeric
    });

    $sortableFields.delegate('input.fld-quantity', 'blur', function () {
      $(this).val($(this).val());   // use this if you want non-numeric
    });

    //$sortableFields.delegate('input.fld-permissions', 'blur', function () {
      //$(this).val($(this).val());   // use this if you want non-numeric
    //});

    $sortableFields.delegate('input.fld-sectionref', 'blur', function () {
      $(this).val($(this).val());   // use this if you want non-numeric
    });

    $sortableFields.delegate('input.fld-colour', 'blur', function () {
      $(this).val($(this).val());   // use this if you want non-numeric
    });

    $sortableFields.delegate('input.fld-tempname', 'blur', function () {
      $(this).val($(this).val());   // use this if you want non-numeric
    });

    $sortableFields.delegate('input.fld-fieldstyle', 'blur', function () {
      $(this).val($(this).val());   // use this if you want non-numeric
    });

    $sortableFields.delegate('input.fld-sectiontype', 'blur', function () {
      $(this).val($(this).val());   // use this if you want non-numeric
    });

    $sortableFields.delegate('input.fld-fieldsource', 'blur', function () {
      $(this).val($(this).val());   // use this if you want non-numeric
    });

    $sortableFields.delegate('input.listtype', 'click', function () {
		var strID = $(this).attr('id');
		var strValue = $(this).val();
		var strClassID = str_replace(strID, 'multiple_', 'className-');
		$('#' + strClassID).val('form-control d_list ' + strValue);
    });

    // Delete field
	$sortableFields.unbind('click touchstart', '.delete-confirm');
    $sortableFields.on('click touchstart', '.delete-confirm', function (e) {
      e.preventDefault();

      var buttonPosition = this.getBoundingClientRect(),
          bodyRect = document.body.getBoundingClientRect(),
          coords = {
        pageX: buttonPosition.left + buttonPosition.width / 2,
        pageY: buttonPosition.top - bodyRect.top - 12
      };

      var deleteID = $(this).parents('.form-field:eq(0)').attr('id'),
          $field = $(document.getElementById(deleteID));

      var removeField = function removeField() {
        $field.slideUp(250, function () {
          $field.removeClass('deleting');
          $field.remove();
          _helpers.save();
          if (!$sortableFields[0].childNodes.length) {
            $stageWrap.addClass('empty').attr('data-content', opts.messages.getStarted);
          }
        });
      };

      document.addEventListener('modalClosed', function () {
        $field.removeClass('deleting');
      }, false);

      // Check if user is sure they want to remove the field
      if (opts.fieldRemoveWarn) {
        var warnH3 = _helpers.markup('h3', opts.messages.warning),
            warnMessage = _helpers.markup('p', opts.messages.fieldRemoveWarning);
        _helpers.confirm([warnH3, warnMessage], removeField, coords);
        $field.addClass('deleting');
      } else {
        removeField($field);
      }
    });

    // Update button style selection
	$sortableFields.unbind('click', '.style-wrap button');
    $sortableFields.on('click', '.style-wrap button', function () {
      var styleVal = $(this).val(),
          $parent = $(this).parent(),
          $btnStyle = $parent.prev('.btn-style');
      $btnStyle.val(styleVal);
      $(this).siblings('.btn').removeClass('active');
      $(this).addClass('active');
      saveAndUpdate.call($parent);
    });

    // Attach a callback to toggle required asterisk
	$sortableFields.unbind('click', 'input.required');
    $sortableFields.on('click', 'input.required', function () {
      var requiredAsterisk = $(this).parents('li.form-field').find('.required-asterisk');
      requiredAsterisk.toggle();
    });


    // Attach a callback to toggle roles visibility
	$sortableFields.unbind('click', 'input[name="enable_roles"]');
    $sortableFields.on('click', 'input[name="enable_roles"]', function () {
      var roles = $(this).siblings('div.available-roles'),
          enableRolesCB = $(this);
      roles.slideToggle(250, function () {
        if (!enableRolesCB.is(':checked')) {
          $('input[type="checkbox"]', roles).removeAttr('checked');
        }
      });
    });

    // Attach a callback to add new options
	$sortableFields.unbind('click', '.add-opt');
    $sortableFields.on('click', '.add-opt', function (e) {
      e.preventDefault();
      var $optionWrap = $(this).parents('.field-options:eq(0)'),
          $multiple = $('[name="multiple"]', $optionWrap),
          $firstOption = $('.option-selected:eq(0)', $optionWrap),
          isMultiple = false;

      if ($multiple.length) {
        isMultiple = $multiple.prop('checked');
      } else {
        isMultiple = $firstOption.attr('type') === 'checkbox';
      }

      var name = $firstOption.attr('name');

      $('.sortable-options', $optionWrap).append(selectFieldOptions(name, false, false, isMultiple));
      _helpers.updateMultipleSelect();
    });

	// default link when adding a new link
	$sortableFields.unbind('click', '.add-link');
    $sortableFields.on('click', '.add-link', function (e) {
      e.preventDefault();
      var $linkWrap = $(this).parents('.field-options:eq(0)'),
          $multiple = $('[name="multiple"]', $linkWrap),
          $firstLink = $('.option-selected:eq(0)', $linkWrap);

      var name = $firstLink.attr('name');

      $('.sortable-links', $linkWrap).append(selectFieldLinks(name, false));
      //_helpers.updateMultipleSelect();
    });

	$sortableFields.unbind('mouseover mouseout', '.remove, .del-button');
    $sortableFields.on('mouseover mouseout', '.remove, .del-button', function () {
      $(this).parents('li:eq(0)').toggleClass('delete');
    });

    // View XML
    var xmlButton = $(document.getElementById(frmbID + '-view-data'));
    xmlButton.click(function (e) {
      e.preventDefault();
      var xml = _helpers.htmlEncode(elem.val()),
          code = _helpers.markup('code', xml, { className: 'xml' }),
          pre = _helpers.markup('pre', code);
      _helpers.dialog(pre, null, 'data-dialog');
    });

    // Clear all fields in form editor
    var clearButton = $(document.getElementById(frmbID + '-clear-all'));
    clearButton.click(function () {
      var fields = $('li.form-field');
      var buttonPosition = this.getBoundingClientRect(),
          bodyRect = document.body.getBoundingClientRect(),
          coords = {
        pageX: buttonPosition.left + buttonPosition.width / 2,
        pageY: buttonPosition.top - bodyRect.top - 12
      };

      if (fields.length) {
        _helpers.confirm(opts.messages.clearAllMessage, function () {
          _helpers.removeAllfields();
          opts.notify.success(opts.messages.allFieldsRemoved);
          _helpers.save();
        }, coords);
      } else {
        _helpers.dialog('There are no fields to clear', { pageX: coords.pageX, pageY: coords.pageY });
      }
    });

    // Save Idea Template
    $(document.getElementById(frmbID + '-save')).click(function (e) {
      e.preventDefault();
      _helpers.save();
      _helpers.validateForm(e);
    });

    elem.parent().find('p[id*="ideaTemplate"]').remove();
    elem.wrap('<div class="template-textarea-wrap"/>');

    loadData();

    $sortableFields.css('min-height', $cbUL.height());

    document.dispatchEvent(formBuilder.events.loaded);

    return formBuilder;
  };

  $.fn.formBuilder = function (os_a, strFormID_a, options) {
	var os = os_a;
	var m_objThis = this;
	var m_strFormID = strFormID_a;

    return this.each(function () {
      var element = this,
          formBuilder;
      if ($(element).data('formBuilder')) {
        var existingFormBuilder = $(element).parents('.form-builder:eq(0)');
        existingFormBuilder.before(element);
        existingFormBuilder.remove();
        formBuilder = new FormBuilder(os, m_strFormID, options, element);
        $(element).data('formBuilder', formBuilder);
      } else {
        formBuilder = new FormBuilder(os, m_strFormID, options, element);
        $(element).data('formBuilder', formBuilder);
      }
    });
  };
})(jQuery);
'use strict';

// toXML is a jQuery plugin that turns our form editor into XML
// @todo this is a total mess that has to be refactored
(function ($) {
  'use strict';

  $.fn.toXML = function (_helpers) {

    var serialStr = '';

    var fieldOptions = function fieldOptions($field) {
      var options = [];
      $('.sortable-options li', $field).each(function () {
        var $option = $(this),
            attrs = {
          value: $('.option-value', $option).val(),
          selected: $('.option-selected', $option).is(':checked')
        },
            option = _helpers.markup('option', $('.option-label', $option).val(), attrs).outerHTML;
        options.push('\n\t\t\t' + option);
      });
      return options.join('') + '\n\t\t';
    };

    var fieldLinks = function fieldLinks($field) {
      var options = [];
      $('.sortable-links li', $field).each(function () {
        var $option = $(this),
            attrs = {
          permissions: $('.option-permissions', $option).val(),
          command: $('.option-command', $option).val(),
		  parameters: $('.option-parameters', $option).val()
        },
            option = _helpers.markup('option', $('.option-label', $option).val(), attrs).outerHTML;
        options.push('\n\t\t\t' + option);
      });

      return options.join('') + '\n\t\t';
    };

    // Begin the core plugin
    this.each(function () {
      var sortableFields = this;
      if (sortableFields.childNodes.length >= 1) {
        serialStr += '<form-template>\n\t<fields>';
        // build new xml
        _helpers.forEach(sortableFields.childNodes, function (index, field) {
          index = index;
          var $field = $(field);

          var fieldData = $field.data('fieldData');

          if (!$field.hasClass('disabled')) {
            var roleVals = $('.roles-field:checked', field).map(function () {
              return this.value;
            }).get();
            var enableOther = $('[name="enable-other"]:checked', field).length;
            var listType = $('input.listtype:checked', $field).val();
            var isListType = $('input.listtype', $field).is(':checked');

            //var styleValue = isListType ? listType: $('input.fld-fieldstyle', $field).val();

            var types = _helpers.getTypes($field);

            var sourceValue = $('input.fld-fieldsource', $field).val();

            var xmlAttrs = {
              className: fieldData.className,
              description: $('input.fld-description', $field).val(),
              label: $('.fld-label', $field).val(),
              length: $('input.fld-length', $field).val(),
              lines: $('input.fld-lines', $field).val(),
              infovalue: $('input.fld-infovalue', $field).val(),
              quantity: $('input.fld-quantity', $field).val(),
			  //permissions: $('input.fld-permissions', $field).val(),
              maxlength: $('input.fld-maxlength', $field).val(),
			  mitsukibovalue: $('input.fld-mitsukibovalue', $field).val(),
			  value: $('input.fld-value', $field).val(),
			  //code: $('input.fld-code', $field).val(),
              multiple: $('input[name="multiple"]', $field).is(':checked'),
              name: $('input.fld-name', $field).val(),
              placeholder: $('input.fld-placeholder', $field).val(),
              required: $('input.required', $field).is(':checked'),
              readonly: $('input.readonly', $field).is(':checked'),
              searchable: $('input.searchable', $field).is(':checked'),
              sectionref: $('input.fld-sectionref', $field).val(),
			  colour: $('input.fld-colour', $field).val(),
			  tempname: $('input.fld-tempname', $field).val(),
			  fieldstyle: $('input.fld-fieldstyle', $field).val(),
			  //fieldstyle: styleValue,
			  fieldsource: sourceValue,
              sectiontype: $('input.fld-sectiontype', $field).val(),
              sortable: $('input.sortable', $field).is(':checked'),
              toggle: $('.checkbox-toggle', $field).is(':checked'),
              type: types.type,
              subtype: types.subtype
            };
            if (roleVals.length) {
              xmlAttrs.role = roleVals.join(',');
            }
            if (enableOther) {
              xmlAttrs.enableOther = 'true';
            }
            xmlAttrs = _helpers.trimAttrs(xmlAttrs);
            var multipleField = xmlAttrs.type.match(/(d_list|d_multilist|select|checkbox-group|radio-group)/);
            var multipleFieldLink = xmlAttrs.type.match(/(d_relatedlinks)/);

            var fieldContent = '',
                xmlField;
            if (multipleField) {
              fieldContent = fieldOptions($field);
            }

            if (multipleFieldLink) {
              fieldContent = fieldLinks($field);
            }

            xmlField = _helpers.markup('field', fieldContent, xmlAttrs);
            serialStr += '\n\t\t' + xmlField.outerHTML;
          }
        });
        serialStr += '\n\t</fields>\n</form-template>';
      } // if "$(this).children().length >= 1"
    });
    return serialStr;
  };
})(jQuery);
'use strict';

// Polyfill for Object.assign

if (typeof Object.assign !== 'function') {
  (function () {
    Object.assign = function (target) {
      if (target === undefined || target === null) {
        throw new TypeError('Cannot convert undefined or null to object');
      }

      var output = Object(target);
      for (var index = 1; index < arguments.length; index++) {
        var source = arguments[index];
        if (source !== undefined && source !== null) {
          for (var nextKey in source) {
            if (source.hasOwnProperty(nextKey)) {
              output[nextKey] = source[nextKey];
            }
          }
        }
      }
      return output;
    };
  })();
}

// Element.remove() polyfill
if (!('remove' in Element.prototype)) {
  Element.prototype.remove = function () {
    if (this.parentNode) {
      this.parentNode.removeChild(this);
    }
  };
}

// Event polyfill
if (typeof Event !== 'function') {
  (function () {
    window.Event = function (evt) {
      var event = document.createEvent('Event');
      event.initEvent(evt, true, true);
      return event;
    };
  })();
}

