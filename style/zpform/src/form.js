/**
 * The Zapatec DHTML Menu
 *
 * Copyright (c) 2004-2005 by Zapatec, Inc.
 * http://www.zapatec.com
 * 1700 MLK Way, Berkeley, California,
 * 94709, U.S.A.
 * All rights reserved.
 *
 * Forms Widget
 * $$
 *
 */
 
//true for debugging, false for production
var log = false;
if (log) {
	log = new Log(Log.DEBUG, Log.popupLogger);
}

Zapatec.Form = function(formName, userConfig) {
	// Set defaults & import user config.
	this.config = {
		statusImgPos: 'beforeField', // afterField, beforeField.
		showErrors: 'none', // tooltip, afterField, beforeField.
		showErrorsOnSubmit: true, // true/false
		/**
		 * submitErrorFunc config option [function]
		 *
		 * Callback function reference to call on error. Callback function receives
		 * following object:
		 * {
		 *   generalError: "Human readable error description",
		 *   fieldErrors: [
		 *     {
		 *       field: field element object,
		 *       errorMessage: "Human readable error description"
		 *     },
		 *     ...
		 *   ]
		 * }
		 *
		 * fieldErrors property may be undefined.
		 */
		submitErrorFunc: this.submitErrorFunc,
		/**
		 * asyncSubmitFunc config option [function]
		 *
		 * Callback function reference to call after the form is sent to
		 * the server using Zapatec.Transport.fetchJsonObj and "success" response
		 * is received from the server.
		 *
		 * Server response should be a valid JSON string in the following format:
		 * {
		 *   "success": true | false,
		 *   "generalError": "Human readable error description",
		 *   "fieldErrors": {
		 *     "fieldName1": "Human readable error description",
		 *     "fieldName2": "Human readable error description",
		 *     ...
		 *   }
		 * }
		 *
		 * generalError and fieldErrors properties are optional.
		 *
		 * Callback function doesn't receive arguments.
		 * submitErrorFunc callback function is called on error.
		 */
		asyncSubmitFunc: null
	};
	for (var cc in userConfig) {
	 this.config[cc] = userConfig[cc];
	}

	this.domForm = document.getElementById(formName);
	if (this.domForm == null) {
		alert("Couldn't find form:" + formName);
		return false;
	}

	// Init data types on first run.
	if (!Zapatec.Form.dataTypesRegistered) {
		Zapatec.Form.initDataTypes();
		Zapatec.Form.dataTypesRegistered = true;
	}

	// Record reference in global array.
	this.instance = Zapatec.Form.instances.length;
	Zapatec.Form.instances[this.instance] = this;

	if (typeof this.config.asyncSubmitFunc == 'function') {
		var self = this;
		this.domForm.onsubmit = function() {
			// check if form is already submitted and result not received
			if(self.domForm.zpFormProcessing == true){
				return false;
			}

			// Validate if needed
			if (self.config.showErrorsOnSubmit &&
			 typeof self.config.submitErrorFunc == 'function') {
				if (!self.validateOnSubmit()) {
					return false;
				}
			}
			// Get urlencoded content
			var arrContent = [];
			var objFormElements = self.domForm.elements;
			for (var iElm = 0; iElm < objFormElements.length; iElm++) {
				if (objFormElements[iElm].name) {
					arrContent.push(objFormElements[iElm].name + '=' +
					 escape(objFormElements[iElm].value));
				}
			}
			var strUrl = self.domForm.action;
			var strMethod = self.domForm.method.toUpperCase();
			var strContent = arrContent.join('&');
			if (strMethod == '' || strMethod == 'GET' || strMethod == 'HEAD') {
				strUrl += '?' + strContent;
				strContent = null;
			}

			self.domForm.zpFormProcessing = true;
			// disabling all <input type="submit"> element in the form
			var inputs = self.domForm.getElementsByTagName("input");
			for(var ii = 0; ii < inputs.length; ii++){
				if(inputs[ii].type == "submit"){
					inputs[ii].disabled = true;
				}
			}

			// Submit form
			Zapatec.Transport.fetchJsonObj({
				url: strUrl,
				method: strMethod,
				contentType: self.domForm.enctype,
				content: strContent,
				onLoad: function(objResponse) {
					self.domForm.zpFormProcessing = false;
					// enabling all <input type="submit"> element in the form
					var inputs = self.domForm.getElementsByTagName("input");
					for(var ii = 0; ii < inputs.length; ii++){
						if(inputs[ii].type == "submit"){
							inputs[ii].disabled = false;
						}
					}

					if (objResponse) {
						if (objResponse.success) {
							self.config.asyncSubmitFunc();
						} else if (self.config.showErrorsOnSubmit &&
						 typeof self.config.submitErrorFunc == 'function') {
							var arrFieldErrors = [];
							if (objResponse.fieldErrors) {
								for (var strFieldName in objResponse.fieldErrors) {
									for (var iElm = 0; iElm < objFormElements.length; iElm++) {
										if (objFormElements[iElm].name &&
										 objFormElements[iElm].name == strFieldName) {
											arrFieldErrors.push({
												field: objFormElements[iElm],
												errorMessage: objResponse.fieldErrors[strFieldName],
												validator: ''
											});
										}
									}
								}
							}
							self.config.submitErrorFunc({
								generalError: objResponse.generalError || '',
								fieldErrors: arrFieldErrors
							});
						}
					} else if (self.config.showErrorsOnSubmit &&
					 typeof self.config.submitErrorFunc == 'function') {
						self.config.submitErrorFunc({
							generalError: 'No response'
						});
					}
				},
				onError: function(objError) {
					self.domForm.zpFormProcessing = false;
					// enabling all <input type="submit"> element in the form
					var inputs = self.domForm.getElementsByTagName("input");
					for(var ii = 0; ii < inputs.length; ii++){
						if(inputs[ii].type == "submit"){
							inputs[ii].disabled = false;
						}
					}

					if (self.config.showErrorsOnSubmit &&
					 typeof self.config.submitErrorFunc == 'function') {
						var strError = '';
						if (objError.errorCode) {
							strError += objError.errorCode + ' ';
						}
						strError += objError.errorDescription;
						self.config.submitErrorFunc({
							generalError: strError
						});
					}
				}
			});

			return false;
		};
	} else
	if(this.config['showErrorsOnSubmit'] && this.config['submitErrorFunc']){
		this.domForm.onsubmit = new Function('e', 'return Zapatec.Form.instances[' + this.instance + '].validateOnSubmit();');
	}

	// Apply event handlers and status indicators to form.
	formElements = this.domForm.elements;
	for (var ii = 0; ii < formElements.length; ii++) {
		// see http://www.w3.org/TR/2001/WD-DOM-Level-2-HTML-20011025/html.html#ID-76728479
		var currentField = formElements[ii];
		if (log)
			log.debug(currentField.nodeName + ":" + currentField.type);
		// MSIE bug.
		var tipo = currentField.nodeName.toLowerCase();
		if (!(tipo == 'input' || tipo == 'textarea' || tipo == 'select')) {
			tipo = ''; }
		if (tipo == 'input' && (!(currentField.type == 'text' || currentField.type == 'password' || currentField.type == 'file'))) {
			tipo = ''; }
		if (tipo == 'textarea' && currentField.id == 'ctexto') {
			continue; tipo = ''; }
		if (tipo == 'select' && currentField.id == 'SPAW_ctexto_tb_paragraph') {
			continue; tipo = ''; }
		if (currentField.nodeName && tipo == '') continue;
		if (Zapatec.Form.ignoreField(currentField)) {
			continue;
		}
		currentField.onfocus = new Function('e', 'Zapatec.Form.instances[' +
			this.instance + '].focus(e, this);');
		currentField.onkeypress = new Function('e', 'return Zapatec.Form.instances[' +
			this.instance + '].keypress(e, this);');
		currentField.onkeyup = new Function('e', 'Zapatec.Form.instances[' +
			this.instance + '].keyup(e, this);');
		currentField.onblur = new Function('e', 'Zapatec.Form.instances[' +
			this.instance + '].blur(e, this);');
		// Next some <span> elements, as IE doens't support multi-class selectors.
		currentField.__zp_statusImg1 = Zapatec.Utils.createElement('span');
		currentField.__zp_statusImg2 =
		 currentField.__zp_statusImg1.appendChild(Zapatec.Utils.createElement('span'));
		currentField.__zp_statusImg3 =
		 currentField.__zp_statusImg2.appendChild(Zapatec.Utils.createElement('span'));
		currentField.__zp_statusImg4 =
		 currentField.__zp_statusImg3.appendChild(Zapatec.Utils.createElement('span'));
		// The innermost is the one we actually style.
		currentField.__zp_statusImg =
		 currentField.__zp_statusImg4.appendChild(Zapatec.Utils.createElement('span'));
		// Attach the outermost <span> near the input field.
		if (this.config.statusImgPos == 'afterField') {
			Zapatec.Utils.insertAfter(currentField, currentField.__zp_statusImg1);
		}
		else {
			currentField.parentNode.insertBefore(currentField.__zp_statusImg1, currentField);
		}
		// An error container.
		currentField.__zp_errorText = Zapatec.Utils.createElement('span');
		currentField.__zp_errorText.className = 'zpFormError';
		currentField.__zp_errorText.init = true; //Only used first round
		// Position it by the field if configured that way.
		if (this.config.showErrors == 'afterField') {
			Zapatec.Utils.insertAfter(currentField, currentField.__zp_errorText);
		}
		else if (this.config.showErrors == 'beforeField') {
			currentField.parentNode.insertBefore(currentField.__zp_errorText, currentField);
		}
		// on first run.
		if (formElements[ii].value != '' && formElements[ii].className != 'zpFormNotRequired') {
			this.focus({}, currentField); }
		this.blur({}, currentField);
	}
};

//Globals
//Array of Data Types
Zapatec.Form.dataTypes = new Array();
// List of all instantiated Zapatec.Form objects.
Zapatec.Form.instances = [];

Zapatec.Form.ignoreFieldType = ['fieldset', 'submit', 'reset'];

/*
 * Should we ignore this type of field?
 * @param field [HTMLElement] the DOM element of the field
 */

Zapatec.Form.ignoreField = function(field) {
	var type = field.type.toLowerCase();
	var ignoreList = Zapatec.Form.ignoreFieldType;
	for (var ii = 0; ii < ignoreList.length; ii++) {
		if (type == ignoreList[ii]) {
			if (log)
				log.debug('Ignore Field:' + field.name + ":" + type);     
			return true; //ignore
		}
	}
	return false; //not in the list; don't ignore
}


// Setup function that auto-activates all forms.
Zapatec.Form.setupAll = function(params) {
	var _zp_forms = document.getElementsByTagName('form');
	if (_zp_forms && _zp_forms.length) {
		for (var ff = _zp_forms.length; ff--; ) {
			if ((/zpForm/).test(_zp_forms[ff].className)) {
				new Zapatec.Form(_zp_forms[ff].id, params);
			}
		}
	}
};

//Initialize the validators
Zapatec.Form.initDataTypes = function() {
	var dataTypes = Zapatec.Form.dataTypes; //shorthand

	Zapatec.Form.addDataType('zpFormRequired', 'A Required Field',
	/^.+$/,
	" ",
	"This field is required",
	null);

	Zapatec.Form.addDataType('zpFormUrl', 'A URL -- web address',
	/^[a-zA-Z]+\:\/\/(([a-zA-Z][\_\-a-zA-Z0-9]+(\.[a-zA-Z0-9][\-a-zA-Z0-9]+)*)|(([0-9]{1,3}\.){3}([0-9]{1,3})))(\:\d+){0,1}(\/.*)?$/,
	"Invalid URL",
	"Valid URL needs to be in the form http://www.yahoo.com:80/index.html or just http://www.yahoo.com",
	null);

	Zapatec.Form.addDataType('zpFormEmail', 'An Email Address',
	 /^[a-zA-Z0-9][\+\_\.\-\w]{1,127}@[a-zA-Z0-9][\-a-zA-Z0-9]+\.[a-zA-Z0-9][\-a-zA-Z0-9\.]+$/,
	" ",
	"Valid email address need to be in the form of nobody@example.com",
	null);

	Zapatec.Form.addDataType('zpFormUSPhone', 'A USA Phone Number',
	/^((\([1-9][0-9]{2}\) *)|([1-9][0-9]{2}[\-. ]?))[0-9]{3}[\-. ][0-9]{4} *(ex[t]? *[0-9]+)?$/,
	"Invalid US Phone Number",
	"Valid US Phone number needs to be in the form of 'xxx xxx-xxxx' For instance 312 123-1234. An extention can be added as ext xxxx. For instance 312 123-1234 ext 1234",
	null);

	Zapatec.Form.addDataType('zpFormUSZip', 'A USA Zip Number',
	/(^\d{5}$)|(^\d{5}-\d{4}$)/,
	"Invalid US Zip Code",
	"Valid US Zip number needs to be either in the form of '99999', for instance 94132 or '99999-9999' for instance 94132-3213",
	null);

	Zapatec.Form.addDataType('zpFormDate', 'A Valid Date',
 null,
	" ",
	"Please enter a valid date",
	function(date) {
		if (!isNaN(Date.parse(date))) {
			var tmp = date;
			var dia = tmp.substr(0, tmp.indexOf('/'));
			if (dia < 1 || dia > 31) {
				return false;
			}
			tmp = tmp.substr(tmp.indexOf('/') + 1);
			var mes = tmp.substr(0, tmp.indexOf('/')) - 1;
			if (mes < 0 || mes > 11) {
				return false;
			}
			var anio = tmp.substr(tmp.indexOf('/') + 1);
			if (anio < 1900 || anio > 2050) {
				return false;
			}
			var fecha = new Date(anio, mes, dia);
			if (dia != fecha.getDate() || mes != fecha.getMonth() || anio != fecha.getFullYear()) {
				return false;
			}
			return true;
		}
		else {
			return false;
		}
		//return (!isNaN(Date.parse(date)));
	});

	Zapatec.Form.addDataType('zpFormInt', 'An Integer',
 null,
	" ",
	"Please enter an integer",
	function(number) {
		var parsed = parseInt(number);
		return (parsed == number);
	});
	
	Zapatec.Form.addDataType('zpFormFloat', 'A Floating Point Number',
 null,
	" ",
	"Please enter a Floating Point Number",
	function(number) {
		var parsed = parseFloat(number);
		return (parsed == number);
	});

};

Zapatec.Form.addDataType = function(zpName, name, regex, error, help, func) {
	var dataTypes = Zapatec.Form.dataTypes; //shorthand
	Zapatec.Form.dataTypes[zpName] = {
		zpName: zpName,
		name: name,
		regex: regex,
		error: error,
		help: help,
		func: func
 };
};


Zapatec.Form.prototype.validateOnSubmit = function(){
	var errors = [];
	var tmpErrors = [];
	var formElements = this.domForm.elements;

	for (var ii = 0; ii < formElements.length; ii++){
		if (!(formElements[ii].name == 'SPAW_ctexto_tb_paragraph' || formElements[ii].name == 'ctexto')) {
			var invalid = this.validate(formElements[ii], true);

			if(!invalid)
				continue;
	
			 for(var jj = 0; jj < invalid.length; jj++)
				tmpErrors.push(invalid[jj]);
		}
	}
	
	for (var ii = 0; ii < tmpErrors.length; ii++){
		var error = tmpErrors[ii];

		if(error.validator == "zpFormRequired"){
			var moreThenOne = false;
	
			for (var jj = 0; jj < tmpErrors.length; jj++){
				if(ii != jj && tmpErrors[ii].field == tmpErrors[jj].field){
					moreThenOne = true;
					break;
				}
			}

			if(moreThenOne)
				continue;
		}

		if(
			error.field.className.match("zpFormRequired") && !error.field.value ||
			error.validator != "zpFormRequired" && error.field.value
		){
			errors[errors.length] = error;
		}
	}

	if(errors.length > 0 && typeof(this.config.submitErrorFunc) == 'function'){
		errors[0].field.focus();
		this.config.submitErrorFunc({
			generalError: errors.length==1 ? 'Hay 1 error.' : 'Hay ' + errors.length + ' errores.',
			fieldErrors: errors
		});

		return false;
	}

	return true;
}

Zapatec.Form.prototype.submitErrorFunc = function(objErrors){
	var message = objErrors.generalError + '\n';
	if (objErrors.fieldErrors) {
		for (var ii = 0; ii < objErrors.fieldErrors.length; ii++) {
			message += (ii + 1) + ': Campo ' + objErrors.fieldErrors[ii].field.name +
			 ' ' + objErrors.fieldErrors[ii].errorMessage + "\n";
		}
	}
	//alert(message);	
}

Zapatec.Form.prototype.focus = function(evt, elm) {
	elm.__zp_editing = true;
	elm.__zp_errorText.init = false;
	this.validate(elm);
};

Zapatec.Form.prototype.keypress = function(evt, elm) {
	// Allow only some character keypresses.
	// I use the JavaScript regexp "\character" markers, such as:
	// \d = digits 0-9.
	// \n = newlines.
	// \s = whitespace & newlines.
	// \w = "word" characters (a-z, 0-9, _)
	// Any may be uppercased to match "not this set".
	// Backspace, delete, and arrows are always allowed.

	//the key that was pressed
	var charCode = Zapatec.is_ie ? window.event.keyCode : Zapatec.is_opera ? evt.which : evt.charCode;

	if ((/zpFormAllowed-(\S+)/).test(elm.className)) {
		var allowed = new RegExp('[\\' + (RegExp.$1).split('').join('\\') + ']');

		if (evt) { //Firefox
			if (charCode && !(allowed.test(String.fromCharCode(charCode)))) {
				if(evt.preventDefault) 
					evt.preventDefault();

				evt.returnValue = false;
				return false;
			}
		} else  {
			if (window.event) { //IE
				charCode = String.fromCharCode(charCode);
				
				if (charCode && !(allowed.test(charCode))) {
					return false;
				}
			}
		}
	}
};

Zapatec.Form.prototype.keyup = function(evt, elm) {
	this.validate(elm);
};

Zapatec.Form.prototype.blur = function(evt, elm) {
	elm.__zp_editing = false;
	if (elm.__zp_errorText.init) {
		this.validate(elm, false);
	} else {
		this.validate(elm, true);
	}
};

Zapatec.Form.prototype.validate = function(elm, setError) {
	if (!elm.className)
		return null;

	var dataTypes = Zapatec.Form.dataTypes;
	var elmDataTypes = elm.className.split(/\s+/);
	var valid = true;
	var message = '';
	var errors = [];
	
	for (var ii = 0; ii < elmDataTypes.length; ii++) {
		var dt = elmDataTypes[ii];

		if((/zpFormMask="([^"]+)"/).test(elm.className)){
			var mask = RegExp.$1;
			var pattern = "^";
			var chars = mask.split('');
			
			for(var i = 0; i < chars.length; i++){
				switch(chars[i]){
					case "0":
						pattern += "[0-9]";
						break;
					case "9":
						pattern += "[0-9 ]?";
						break;
					case "#":
						pattern += "[0-9 -+]";
						break;
					case "L":
						pattern += "[a-zA-Z]";
						break;
					case "?":
						pattern += "[a-zA-Z]?";
						break;
					case "A":
						pattern += "[0-9a-zA-Z]";
						break;
					case "a":
						pattern += "[0-9a-zA-Z]?";
						break;
					case "&":
						pattern += ".";
						break;
					case "C":
						pattern += ".?";
						break;
					case "\\":
						i++;
						if(i >= chars.length)
							break;
						// fall through
					default:
						if(chars[i].match(/[\w\d]/))
							pattern += chars[i];
						else
							pattern += "\\" + chars[i];
				}
			}
			
			pattern += "$";
//			document.write(pattern)
			if(!elm.value.match(new RegExp(pattern))){
				valid = false;
				message = " ";
				//message = "Does not conform to mask " + mask.replace(/&/g, '&amp;').replace(/</g, '&lt;').replace(/>/g, '&gt;');
				errors.push({field: elm, errorMessage: "Does not conform to mask " + mask, validator: "zpFormMask"});
				// help = "Please enter a string conforming to the mask " + mask;
			}
		} else if (dataTypes[dt]) {
			if (dataTypes[dt].regex) {
				// Regex validation.
				valid &= dataTypes[dt].regex.test(elm.value);
			} else if (dataTypes[dt].func) {
				// Javascript function validation.
				valid &= dataTypes[dt].func(elm.value);
			}

			if (!valid){
			 	message = dataTypes[dt].error;
				errors.push({field: elm, errorMessage: dataTypes[dt].error, validator: dt});
			}
		}
	}
	this.setImageStatus(elm, message, setError);

	return errors;
};

Zapatec.Form.prototype.setImageStatus = function(elm, status, setError) {
	// Sets the CLASS of the status indicator next to a form field,
	// and its title tooltip popup.
	// False = valid, otherwise text to display.
	var img = elm.__zp_statusImg;
	var required = (/zpFormRequired/).test(elm.className);
	
	elm.__zp_statusImg1.className = (required ? 'zpIsRequired' : 'zpNotRequired');
	elm.__zp_statusImg.className = 'zpStatusImg';

	// show validation only if setError is true(if this is not onload validation)
    if(setError){
		elm.__zp_statusImg2.className = (elm.__zp_editing ? ' zpIsEditing' : ' zpNotEditing');
		elm.__zp_statusImg3.className = (!elm.value ? ' zpIsEmpty' : ' zpNotEmpty');
		elm.__zp_statusImg4.className = (!status ? ' zpIsValid' : ' zpNotValid');
	    
		// Error status text handling.
		if ((/(beforeField|afterField)/).test(this.config.showErrors)) {
			// Only diplay an error is it is required or the user typed something
			if (required || (elm.value)) {
				elm.__zp_errorText.innerHTML = status;
			}
		}
		else {
			// Create and/or show a tooltip on the img.
			if (Zapatec.Tooltip) {
				if (!img.__zp_tooltip) {
					var tt = Zapatec.Utils.createElement('div', document.body);
					img.__zp_tooltip = new Zapatec.Tooltip(img, tt);
				}
				img.__zp_tooltip.tooltip.innerHTML = status ? status : '';
			}
			else {
				// Zapatec.Tooltip not installed? Go for default browser tooltip.
				img.title = status ? status : '';
			}
		}
    }
};