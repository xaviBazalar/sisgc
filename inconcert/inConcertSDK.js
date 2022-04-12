/*jslint  evil:true */
/*global $, jQuery window

Phone
MD5
cookies
inconcert
endDateConverted
startDateConverted
*/

var LOGIN_URI = "/inconcert/apps/login";
var OE_URI = "/inconcert/apps/outboundengine/sdk";

var BARAGENT_PORT = 12200;
var BARAGENT_URI = "http://127.0.0.1:" + BARAGENT_PORT + "/inconcert/apps/baragent/sdk";


var g_loginToken = null;
var g_sessionId = null;

var g_callbackSuccess = null;
var g_callbackError = null;

var responseCallback = {code : undefined,
						internalCode : undefined,
						message : undefined,
						data : undefined,
						func : undefined};

function IsTypeOf(obj, key) {
 return((obj.constructor.toString().indexOf(key) === -1)?false:true);
}

function IsNumeric(sText) {
	return((sText.match(/^\d+$/) === null)?true:false);
}

String.prototype.trim = function(){ return this.replace(/^\s+|\s+$/g,''); };


///////////////////////////////////////////////////////////////////////////////////////////
//function ConvertToUtc
function ConvertToUtc(originalDate, originalTimezone) {
    var utc = new Date(originalDate.getTime() - (originalTimezone.getTimezone() * 3600000) - (originalTimezone.getTimezoneMinutes() * 60000));
    return utc;
}

///////////////////////////////////////////////////////////////////////////////////////////
//function ProcessingXmlResponse
function ProcessingXmlResponse(sRet, code, func, data, messageOK, messageFail, callbackSuccess, callbackError){

	var success = (callbackSuccess === undefined)?g_callbackSuccess:callbackSuccess;
	var error = (callbackError === undefined)?g_callbackError:callbackError;

	if (sRet === "1" || sRet === "true"){
		if(typeof(success) === "function") {
			responseCallback.code = code;
			responseCallback.internalCode = sRet;
			responseCallback.func = func;
			responseCallback.data = data;
			responseCallback.message = messageOK;
			success(responseCallback);
		}
	}
	else {
		if(typeof(error) === "function") {
			responseCallback.code = code;
			responseCallback.internalCode = sRet;
			responseCallback.func = func;
			responseCallback.data = data;
			responseCallback.message = messageFail;
			error(responseCallback);
		}
	}
}


///////////////////////////////////////////////////////////////////////////////////////////
//function ProcessingResponse
function ProcessingResponse(response, callbackSuccess, callbackError){

	var success = (callbackSuccess === undefined)?g_callbackSuccess:callbackSuccess;
	var error = (callbackError === undefined)?g_callbackError:callbackError;

	responseCallback.code = response.code;
	responseCallback.internalCode = response.internalCode;
	responseCallback.func = response.func;
	responseCallback.data = response.data;
	responseCallback.message = response.message;
			
	if (response.internalCode === 1){
		if(typeof(success) === "function") {
			success(responseCallback);
		}
	}
	else {
		if(typeof(error) === "function") {
			error(responseCallback);
		}
	}
}

///////////////////////////////////////////////////////////////////////////////////////////
//function ProcessingErrorResponse
function ProcessingErrorResponse (response, statusText,  func, callbackError){

	var error = (callbackError === undefined)?g_callbackError:callbackError;
	if(typeof(error) === "function") {
		responseCallback.code = (response.status === undefined)?(statusText === "timeout")?408:-1:response.status;
		responseCallback.internalCode = 0;
		responseCallback.func = func;
		responseCallback.data = undefined;
		responseCallback.message = (response.statusText === undefined)?statusText:response.statusText;
		error(responseCallback);
	}
}

///////////////////////////////////////////////////////////////////////////////////////////
//function CreateUploadControls
function CreateUploadControls(formFileUpload, btnAddFile, url) {
	var m_uploadControl = {};

	m_uploadControl.parentObject = document.getElementById(formFileUpload);
	m_uploadControl.parentObject.style.position = "relative";
	m_uploadControl.parentObject.innerHTML = "<iframe src='about:blank' id='inconcertUploadFrame' name='inconcertUploadFrame' style='display:none'></iframe>";
	m_uploadControl.containerDiv = document.createElement("div");
	m_uploadControl.parentObject.appendChild(m_uploadControl.containerDiv);
	m_uploadControl.container = document.createElement("div");
	m_uploadControl.uploadForm = document.createElement("form");
	m_uploadControl.uploadForm.method = "post";
	m_uploadControl.uploadForm.encoding = "multipart/form-data";
	m_uploadControl.uploadForm.target = "inconcertUploadFrame";
	m_uploadControl.uploadForm.action = url;	
	m_uploadControl.container.appendChild(m_uploadControl.uploadForm);
	m_uploadControl.parentObject.appendChild(m_uploadControl.container);
	
	m_uploadControl.SetAttr = function (name, value){
		m_uploadControl[name] = document.createElement("input");
		m_uploadControl[name].type = "hidden";
		m_uploadControl[name].name = name;
		m_uploadControl[name].value = value;
		m_uploadControl.uploadForm.appendChild(m_uploadControl[name]);
	};
	
	m_uploadControl.SetAttr("MAX_FILE_SIZE", "200000000");
	m_uploadControl.SetAttr("UPLOAD_IDENTIFIER", "");
	
	m_uploadControl.currentFile = document.getElementById(btnAddFile);
	var real = $("#" + btnAddFile);
	var cloned = real.clone(true);
	real.hide();
	cloned.insertAfter(real);	
	m_uploadControl.uploadForm.appendChild(m_uploadControl.currentFile);
	
	m_uploadControl.Send = function (callback) {
		m_uploadControl.uploadForm.submit();
	};
	
	return m_uploadControl;
}

///////////////////////////////////////////////////////////////////////////////////////////
//Public Object ContactData

function ContactData() {

 	var m_index = undefined;
 	var m_value = undefined;
 	var m_name = undefined;

	//Index
  this.getIndex = function(){
  	return m_index;
  };
  this.setIndex = function(val){
  	m_index = val;
  };

	//Value
  this.getValue = function(){
  	return m_value;
  };
  this.setValue = function(val){
  	m_value = val;
  };

	//Name
  this.getName = function(){
  	return m_name;
  };
  this.setName = function(val){
  	m_name = val;
  };

 	this.InitFromXML = function (xml){
		this.setIndex($(xml).find("index").text());
		this.setValue($(xml).find("value").text());
		this.setName($(xml).find("name").text());
	};
}

///////////////////////////////////////////////////////////////////////////////////////////
//Public Object Contact

function Contact() {

 	var m_name = undefined;
	var m_id = undefined;
 	var m_accountOfficer = undefined;
 	var m_accountGroup = undefined;
 	var m_category = undefined;
 	var m_campaign = undefined;
 	var m_vip = undefined;
 	var m_isClient = undefined;
	var m_lastManagementResult = undefined;

 	var m_phones = [];
 	var m_data = [];


	//name
  this.getName = function(){
  	return m_name;
  };
  this.setName = function(val){
  	m_name = val;
  };

	//id
  this.getId = function(){
  	return m_id;
  };
  this.setId = function(val){
  	m_id = val;
  };

	//accountOfficer
  this.getAccountOfficer = function(){
  	return m_accountOfficer;
  };
  this.setAccountOfficer = function(val){
  	m_accountOfficer = val;
  };

	//accountGroup
  this.getAccountGroup = function(){
  	return m_accountGroup;
  };
  this.setAccountGroup = function(val){
  	m_accountGroup = val;
  };

 	//category
  this.getCategory = function(){
  	return m_category;
  };
  this.setCategory = function(val){
  	m_category = val;
  };

 	//campaign
  this.getCampaign = function(){
  	return m_campaign;
  };
  this.setCampaign = function(val){
  	m_campaign = val;
  };

 	//vip
  this.getVip = function(){
  	return m_vip;
  };
  this.setVip = function(val){
  	m_vip = val;
  };

 	//isClient
  this.getIsClient = function(){
  	return m_isClient;
  };
  this.setIsClient = function(val){
  	m_isClient = val;
  };
  
  this.getLastManagementResult = function(){
  	return m_lastManagementResult;
  };
  this.setLastManagementResult = function(val){
  	m_lastManagementResult = val;
  };

 	//phones
  this.getPhones = function(){
  	return m_phones;
  };

 	//data
  this.getData = function(){
  	return m_data;
  };

 	//data
  this.getData = function(){
  	return m_data;
  };


 	this.InitFromXML = function (xml){
		this.setId($(xml).find("contactId").text());
		this.setName($(xml).find("name").text());
		this.setAccountOfficer($(xml).find("accountOfficer").text());
		this.setAccountGroup($(xml).find("accountGroup").text());
		this.setCategory($(xml).find("category").text());
		this.setCampaign($(xml).find("campaign").text());
		this.setVip($(xml).find("VIP").text());
		this.setIsClient($(xml).find("IsClient").text());
		this.setLastManagementResult($(xml).find("lastManagementResult").text());


		$(xml).find("phone").each(function() {
				if ($(this).find("phoneNumber").text() !== ""){
	    		var phone = new Phone();
  	    	phone.InitFromXML($(this));
    	  	m_phones[m_phones.length] = phone;
    	  }
      });


		$(xml).find("data").each(function() {
				if ($(this).find("name").text() !== "") {
	    		var contactData = new ContactData();
  	    	contactData.InitFromXML($(this));
    	  	m_data[m_data.length] = contactData;
    	  }
      });

	};

}


///////////////////////////////////////////////////////////////////////////////////////////
//Public Object Timezone

function Timezone() {

 	var m_id = undefined;
 	var m_description = undefined;
 	var m_timezone = undefined;
 	var m_timezoneMinutes = undefined;
 	var m_dst = undefined;

	//id
  this.getId = function(){
  	return m_id;
  };
  this.setId = function(val){
  	m_id = val;
  };

	//description
  this.getDescription = function(){
  	return m_description;
  };
  this.setDescription = function(val){
  	m_description = val;
  };

	//timezone
  this.getTimezone = function(){
  	return m_timezone;
  };
  this.setTimezone = function(val){
  	m_timezone = val;
  };

	//timezoneMinutes
  this.getTimezoneMinutes = function(){
  	return m_timezoneMinutes;
  };
  this.setTimezoneMinutes = function(val){
  	m_timezoneMinutes = val;
  };

	//dst
  this.getDst = function(){
  	return m_dst;
  };
  this.setDst = function(val){
  	m_dst = val;
  };

 	this.InitFromXML = function (xml){
		this.setId($(xml).find("id").text());
		this.setDescription($(xml).find("description").text());
		this.setTimezone($(xml).find("timezone").text());
		this.setTimezoneMinutes($(xml).find("timezoneMinutes").text());
		this.setDst($(xml).find("daylightSavingTime").text());
	};

}


///////////////////////////////////////////////////////////////////////////////////////////
//Public Object Phone

function Phone() {

 	var m_timezone = undefined;
 	var m_countryId = undefined;
 	var m_areaId = undefined;
 	var m_status = undefined;
 	var m_type = undefined;
 	var m_number = undefined;
	var m_extension = undefined;
	var m_importName = undefined;

	//timezone
  this.getTimezone = function(){
  	return m_timezone;
  };
  this.setTimezone = function(val){
  	m_timezone = val;
  };

	//countryId
  this.getCountryId = function(){
  	return m_countryId;
  };
  this.setCountryId = function(val){
  	m_countryId = val;
  };

	//areaId
  this.getAreaId = function(){
  	return m_areaId;
  };
  this.setAreaId = function(val){
  	m_areaId = val;
  };

	//status
  this.getStatus = function(){
  	return m_status;
  };
  this.setStatus = function(val){
  	m_status = val;
  };

	//type
  this.getType = function(){
  	return m_type;
  };
  this.setType = function(val){
  	m_type = val;
  };

	//number
  this.getNumber = function(){
  	return m_number;
  };
  this.setNumber = function(val){
  	m_number = val;
  };

	//extension
  this.getExtension = function(){
  	return m_extension;
  };
  this.setExtension = function(val){
  	m_extension = val;
  };

 	//importName
  this.getImportName = function(){
  	return m_importName;
  };
  this.setImportName = function(val){
  	m_importName = val;
  };

 	this.InitFromXML = function (xml){
		this.setCountryId($(xml).find("countryId").text());
		this.setAreaId($(xml).find("areaId").text());
		this.setStatus($(xml).find("status").text());
		this.setNumber($(xml).find("phoneNumber").text());
		this.setExtension($(xml).find("phoneExtension").text());
		this.setType($(xml).find("phoneType").text());
		this.setImportName($(xml).find("importName").text());
		m_timezone = new Timezone();
		m_timezone.InitFromXML($(xml).find("timezone"));
	};

}


///////////////////////////////////////////////////////////////////////////////////////////
//Public Object Predicate

function Predicate() {

 	var m_predicate = '';
 	var m_limit = 0;

	//predicate
  this.getPredicate = function(){  	  	
  	if (m_predicate.indexOf("<search_criteria>") > 0) {
  		return m_predicate;
  	} else {
  		return "<search_criteria><limit>" + m_limit + "</limit><predicate>" + m_predicate + "</predicate></search_criteria>";
	}
  };

  this.setRowLimit = function(val){
  	m_limit = val;
  };
    
  this.setPredicate = function(val){
  	m_predicate = val;
  };

 	this.AddParenthesis = function (opened){
 		m_predicate = opened?m_predicate+'<parenthesis>':m_predicate+'</parenthesis>';
	};
		
 	this.AddOperand = function (name, op, type, datatype, value){
 		m_predicate = m_predicate + '<operand name="' + name + '" op="' + op + '" type="' + type + '" datatype="' + datatype + '">' + value + '</operand>'; 
	};	

 	this.AddOperator = function (value){
 		m_predicate = m_predicate + '<operator>' + value + '</operator>'; 
	};	
}

///////////////////////////////////////////////////////////////////////////////////////////
//Public Object Session

function Session(callbackSuccess, callbackError) {


	function DoLogin(username, password, callcenterId, applicationName, sessionIdFromToken){
		var textToEncode = username.toLowerCase() + password;
		var hash = MD5( MD5(textToEncode) + g_loginToken);

		var params = "hash=" + hash + "&username=" + encodeURIComponent(username) + "&sessionid=" + sessionIdFromToken +
			"&callcenter=" + callcenterId + "&app=" + encodeURIComponent(applicationName);

		$.ajax({
			url: LOGIN_URI + "/login",
			type: 'POST',
			data: params,
			timeout: 60000,
			dataType: "json",
			success: function(response) {
								responseCallback.code = 200;
								if(!response.status) {
									ProcessingXmlResponse("0", 200, "LOGIN", undefined, "", response.failCause);
								}
								else {
									g_sessionId = response.authToken;
									cookies.create("SID", g_sessionId);
									inconcert.storage.Put("SID",g_sessionId);
									ProcessingXmlResponse("1", 200, "LOGIN", undefined, response.message, "");
      	  			}
      	  	},
			error: function(response, statusText) {
							cookies.erase("SID");
							ProcessingErrorResponse(response, statusText, "LOGIN");
      	  	}
		});
	}

	function RequestToken(username, password, callcenterId, applicationName){
		$.ajax({
			url: LOGIN_URI + "/getToken",
			type: 'GET',
			dataType: "json",
			timeout: 60000,
			success: function(response) {
        				g_loginToken = response.loginToken;
        				var sessionIdFromToken = response.sessionId;
        				DoLogin(username, password, callcenterId, applicationName, sessionIdFromToken);
        			},
			error: function(response, statusText) {
							ProcessingErrorResponse(response, statusText, "LOGIN");
      	  	}
		});
	}

	this.Login = function (username, password, callcenterId, applicationName){
		var sessionId = inconcert.storage.Get("SID");
		if (sessionId === undefined){
			RequestToken(username, password, callcenterId, applicationName);
		}
		else {
			g_sessionId = sessionId;
			if (cookies.read("SID") === null)	{
				cookies.create("SID", g_sessionId);
			}
			ProcessingXmlResponse("1", 200, "LOGIN", undefined, "Login succeeded");
		}
	};

	this.LoginFromToken = function (applicationName, userAuthToken){
		var sessionId = inconcert.storage.Get("SID");
		if (sessionId === undefined){
			var params = "app=" + encodeURIComponent(applicationName) + "&identity_token=" + userAuthToken; // + "&application_token=" + userAuthTokenApplication;

			$.ajax({
				url: LOGIN_URI + "/login_with_identity_token",
				type: 'POST',
				data: params,
				timeout: 60000,
				dataType: "json",
				success: function(response) {
									if(!response.status) {
										ProcessingXmlResponse("0", 200, "LOGINFROMTOKEN", undefined, "", response.failCause);
									}
									else {
										g_sessionId = response.authToken;
										cookies.create("SID", g_sessionId);
										inconcert.storage.Put("SID",g_sessionId);
										ProcessingXmlResponse("1", 200, "LOGINFROMTOKEN", undefined, response.message, "");
	      	  			}
	      	  	},
				error: function(response, statusText) {
								cookies.erase("SID");
								ProcessingErrorResponse(response, statusText, "LOGINFROMTOKEN");
	      	  	}
			});

		}
		else {
			g_sessionId = sessionId;
			if (cookies.read("SID") === null)	{
				cookies.create("SID", g_sessionId);
			}
			ProcessingXmlResponse("1", 200, "LOGINFROMTOKEN", undefined, "Login succeeded");
		}
	};


	this.Logout = function (){
	$.ajax({
		url: LOGIN_URI + "/logout",
		type: 'POST',
		timeout: 60000,
		//dataType: "json",
		success: function(response) {
			      	  inconcert.storage.Erase("SID");
      	  			cookies.erase("SID");
      	  			ProcessingXmlResponse("1", 200, "LOGOUT", undefined, "Logout succeeded");
      	  	},
			error: function(response, statusText) {
							ProcessingErrorResponse(response, statusText, "LOGOUT");
      	  	}
		});
	};

	g_callbackSuccess = callbackSuccess;
	g_callbackError = callbackError;

	inconcert.storage.Setup();
}



///////////////////////////////////////////////////////////////////////////////////////////
//Public Object OutBoundEngine

function OutBoundEngine(callbackSuccess, callbackError) {

	///////////////////////////////////////////////////////////////////////////////////////////
	//Public Method AddContact
	this.AddContact = function (contactId, name, accountOfficer, accountGroup, category, campaign, importation, isVip, isClient, lastManagementResult, callbackSuccess, callbackError){

	isVip = (isVip === 0 || isVip === 1)?isVip:0;
	isClient = (isClient === 0 || isClient === 1)?isClient:0;

	var xml = '<contact>' +
						'<contactId>' + contactId + '</contactId>' +
						'<name>' + name + '</name>' +
						'<accountOfficer>' + accountOfficer + '</accountOfficer>' +
						'<accountGroup>' + accountGroup + '</accountGroup>' +
						'<category>' + category + '</category>' +
						'<campaign>' + campaign + '</campaign>' +
						'<VIP>' + isVip + '</VIP>' +
						'<IsClient>' + isClient + '</IsClient>' +
						'<lastManagementResult>' + lastManagementResult + '</lastManagementResult>' +
						'<lastUpdate>' + importation + '</lastUpdate>' +
						'</contact>';
	$.ajax({
		url: OE_URI + "/add_contact/",
		type: 'POST',
		contentType: "text/xml",
		data: xml,
		timeout: 60000,
		dataType: "xml",
		success: function(response) {
							ProcessingXmlResponse($(response).find('response').text(), 200, "ADD_CONTACT", undefined, "AddContact succeeded", "AddContact not succeeded", callbackSuccess, callbackError);
      	  	},
			error: function(response, statusText) {
							ProcessingErrorResponse(response, statusText, "ADD_CONTACT", callbackError);
      	  	}
		});

	};

	///////////////////////////////////////////////////////////////////////////////////////////
	//Public Method AddContactValues
	this.AddContactValues = function (contactId, contactDataList, callbackSuccess, callbackError){

		if (!IsTypeOf(contactDataList, "Array")){
			ProcessingXmlResponse("0", 200, "ADD_CONTACT_VALUES", undefined, "", "ContactDataList is not array");
			return;
		}
		else if (contactDataList.length === 0){
			ProcessingXmlResponse("0", 200, "ADD_CONTACT_VALUES", undefined, "", "ContactDataList is empty");
			return;
		}

		var xmlListData = "";
	 	for(var i=0; i<contactDataList.length; i++) {
	 		if (IsTypeOf(contactDataList[i], "ContactData")) {
	 			xmlListData = xmlListData + '<item>' +
	 										'<index>' + contactDataList[i].getIndex() + '</index>' +
	 										'<value>' + contactDataList[i].getValue() + '</value>' +
	 										'<name>' + contactDataList[i].getName() + '</name>' +
	 										'</item>';
	 		}
	 	}

		var xml = '<contact>' +
							'<contactId>' + contactId + '</contactId>' +
							'<data>' + xmlListData + '</data>' +
							'</contact>';

	$.ajax({
		url: OE_URI + "/add_contact_values/",
		type: 'POST',
		contentType: "text/xml",
		data: xml,
		timeout: 60000,
		dataType: "xml",
		success: function(response) {
							ProcessingXmlResponse($(response).find('response').text(), 200, "ADD_CONTACT_VALUES", undefined, "AddContactValues succeeded", "AddContactValues not succeeded", callbackSuccess, callbackError);
      	  	},
			error: function(response, statusText) {
							ProcessingErrorResponse(response, statusText, "ADD_CONTACT_VALUES", callbackError);
      	  	}
		});

	};

	///////////////////////////////////////////////////////////////////////////////////////////
	//Public Method AddPhone
	this.AddPhone = function (contactId, phoneType, countryId, areaId, phoneNumber, ZIP, phoneExtension, callbackSuccess, callbackError){

	var xml = '<phone>' +
						'<contactId>' + contactId + '</contactId>' +
						'<phoneType>' + phoneType + '</phoneType>' +
						'<countryId>' + countryId + '</countryId>' +
						'<areaId>' + areaId + '</areaId>' +
						'<phoneNumber>' + phoneNumber + '</phoneNumber>' +
						'<ZIP>' + ZIP + '</ZIP>' +
						'<phoneExtension>' + phoneExtension + '</phoneExtension>' +
						'</phone>';
	$.ajax({
		url: OE_URI + "/add_phone/",
		type: 'POST',
		contentType: "text/xml",
		data: xml,
		timeout: 60000,
		dataType: "xml",
		success: function(response) {
							ProcessingXmlResponse($(response).find('response').text(), 200, "ADD_PHONE", undefined, "AddPhone succeeded", "AddPhone not succeeded", callbackSuccess, callbackError);
      	  	},
			error: function(response, statusText) {
							ProcessingErrorResponse(response, statusText, "ADD_PHONE", callbackError);
      	  	}
		});

	};

	///////////////////////////////////////////////////////////////////////////////////////////
	//Public Method AssignAgent
	this.AssignAgent = function (processId, batchId, contactId, agentId, callbackSuccess, callbackError){

	var xml = '<agent>' +
						'<processId>' + processId + '</processId>' +
						'<batchId>' + batchId + '</batchId>' +
						'<contactId>' + contactId + '</contactId>' +
						'<agentId>' + agentId + '</agentId>' +
						'</agent>';

	$.ajax({
		url: OE_URI + "/assign_agent/",
		type: 'POST',
		contentType: "text/xml",
		data: xml,
		timeout: 60000,
		dataType: "xml",
		success: function(response) {
							ProcessingXmlResponse($(response).find('response').text(), 200, "ASSIGN_AGENT", undefined, "AssignAgent succeeded", "AssignAgent not succeeded", callbackSuccess, callbackError);
      	  	},
			error: function(response, statusText) {
							ProcessingErrorResponse(response, statusText, "ASSIGN_AGENT", callbackError);
      	  	}
		});

	};


	///////////////////////////////////////////////////////////////////////////////////////////
	//Public Method AddContactToBatch
	this.AddContactToBatch = function (processId, batchId, contactId, agentId, outofbatchallowed, IgnoreRules, contactDate, callbackSuccess, callbackError){

	outofbatchallowed = (outofbatchallowed === 0 || outofbatchallowed === 1)?outofbatchallowed:0;
	IgnoreRules = (IgnoreRules === 0 || IgnoreRules === 1)?IgnoreRules:0;

  agentId = (agentId === undefined || agentId === null)?"":agentId;

  var xmlDate = "";
  if (typeof(contactDate) === "date"){
  	var month  = contactDate.getMonth() + 1;
  	xmlDate = '<year>' + contactDate.getFullYear() + '</year>' +
  						'<month>' + month + '</month>' +
  						'<day>' + contactDate.getDate() + '</day>' +
  						'<hour>' + contactDate.getHours() + '</hour>' +
  						'<minute>' + contactDate.getMinutes() + '</minute>';
  }

	var xml = '<contact>' +
						'<processId>' + processId + '</processId>' +
						'<batchId>' + batchId + '</batchId>' +
						'<contactId>' + contactId + '</contactId>' +
						'<agentId>' + agentId + '</agentId>' +
						'<outofbatchallowed>' + outofbatchallowed + '</outofbatchallowed>' +
						'<IgnoreRules>' + IgnoreRules + '</IgnoreRules>' +
						xmlDate +
						'</contact>';

	$.ajax({
		url: OE_URI + "/add_contact_to_batch/",
		type: 'POST',
		contentType: "text/xml",
		data: xml,
		timeout: 60000,
		dataType: "xml",
		success: function(response) {
							ProcessingXmlResponse($(response).find('response').text(), 200, "ADD_CONTACT_TO_BATCH", undefined, "AddContactToBatch succeeded", "AddContactToBatch not succeeded", callbackSuccess, callbackError);
      	  	},
			error: function(response, statusText) {
							ProcessingErrorResponse(response, statusText, "ADD_CONTACT_TO_BATCH", callbackError);
      	  	}
		});

	};


	///////////////////////////////////////////////////////////////////////////////////////////
	//Public Method CancelContact
	this.CancelContact = function (processId, batchId, contactId, callbackSuccess, callbackError){

	var xml = '<contact>' +
						'<processId>' + processId + '</processId>' +
						'<batchId>' + batchId + '</batchId>' +
						'<contactId>' + contactId + '</contactId>' +
						'</contact>';

	$.ajax({
		url: OE_URI + "/cancel_contact/",
		type: 'POST',
		contentType: "text/xml",
		data: xml,
		timeout: 60000,
		dataType: "xml",
		success: function(response) {
							ProcessingXmlResponse($(response).find('response').text(), 200, "CANCEL_CONTACT", undefined, "CancelContact succeeded", "CancelContact not succeeded", callbackSuccess, callbackError);
      	  	},
			error: function(response, statusText) {
							ProcessingErrorResponse(response, statusText, "CANCEL_CONTACT", callbackError);
      	  	}
		});

	};

	///////////////////////////////////////////////////////////////////////////////////////////
	//Public Method CancelPhone
	this.CancelPhone = function (contactId, countryId, areaId, phoneNumber, phoneExtension, callbackSuccess, callbackError){

	var xml = '<phone>' +
						'<contactId>' + contactId + '</contactId>' +
						'<countryId>' + countryId + '</countryId>' +
						'<areaId>' + areaId + '</areaId>' +
						'<phoneNumber>' + phoneNumber + '</phoneNumber>' +
						'<phoneExtension>' + phoneExtension + '</phoneExtension>' +
						'</phone>';

	$.ajax({
		url: OE_URI + "/cancel_phone/",
		type: 'POST',
		contentType: "text/xml",
		data: xml,
		timeout: 60000,
		dataType: "xml",
		success: function(response) {
							ProcessingXmlResponse($(response).find('response').text(), 200, "CANCEL_PHONE", undefined, "CancelPhone succeeded", "CancelPhone not succeeded", callbackSuccess, callbackError);
      	  	},
			error: function(response, statusText) {
							ProcessingErrorResponse(response, statusText, "CANCEL_PHONE", callbackError);
      	  	}
		});

	};

	///////////////////////////////////////////////////////////////////////////////////////////
	//Public Method ChangePriority
	this.ChangePriority = function (processId, batchId, contactId, newPriority, callbackSuccess, callbackError){

	var xml = '<contact>' +
						'<processId>' + processId + '</processId>' +
						'<batchId>' + batchId + '</batchId>' +
						'<contactId>' + contactId + '</contactId>' +
						'<newPriority>' + newPriority + '</newPriority>' +
						'</contact>';

	$.ajax({
		url: OE_URI + "/change_contact_priority/",
		type: 'POST',
		contentType: "text/xml",
		data: xml,
		timeout: 60000,
		dataType: "xml",
		success: function(response) {
							ProcessingXmlResponse($(response).find('response').text(), 200, "CHANGE_PRIORITY", undefined, "ChangePriority succeeded", "ChangePriority not succeeded", callbackSuccess, callbackError);
      	  	},
			error: function(response, statusText) {
							ProcessingErrorResponse(response, statusText, "CHANGE_PRIORITY", callbackError);
      	  	}
		});

	};


	///////////////////////////////////////////////////////////////////////////////////////////
	//Public Method DisablePhone
	this.DisablePhone = function (contactId, countryId, areaId, phoneNumber, phoneExtension, callbackSuccess, callbackError){

	var xml = '<phone>' +
						'<contactId>' + contactId + '</contactId>' +
						'<countryId>' + countryId + '</countryId>' +
						'<areaId>' + areaId + '</areaId>' +
						'<phoneNumber>' + phoneNumber + '</phoneNumber>' +
						'<phoneExtension>' + phoneExtension + '</phoneExtension>' +
						'</phone>';

	$.ajax({
		url: OE_URI + "/disable_phone/",
		type: 'POST',
		contentType: "text/xml",
		data: xml,
		timeout: 60000,
		dataType: "xml",
		success: function(response) {
							ProcessingXmlResponse($(response).find('response').text(), 200, "DISABLE_PHONE", undefined, "DisablePhone succeeded", "DisablePhone not succeeded", callbackSuccess, callbackError);
      	  	},
			error: function(response, statusText) {
							ProcessingErrorResponse(response, statusText, "DISABLE_PHONE", callbackError);
      	  	}
		});

	};


	///////////////////////////////////////////////////////////////////////////////////////////
	//Public Method EnablePhone
	this.EnablePhone = function (contactId, countryId, areaId, phoneNumber, phoneExtension, callbackSuccess, callbackError){

	var xml = '<phone>' +
						'<contactId>' + contactId + '</contactId>' +
						'<countryId>' + countryId + '</countryId>' +
						'<areaId>' + areaId + '</areaId>' +
						'<phoneNumber>' + phoneNumber + '</phoneNumber>' +
						'<phoneExtension>' + phoneExtension + '</phoneExtension>' +
						'</phone>';

	$.ajax({
		url: OE_URI + "/enable_phone/",
		type: 'POST',
		contentType: "text/xml",
		data: xml,
		timeout: 60000,
		dataType: "xml",
		success: function(response) {
							ProcessingXmlResponse($(response).find('response').text(), 200, "ENABLE_PHONE", undefined, "EnablePhone succeeded", "EnablePhone not succeeded", callbackSuccess, callbackError);
      	  	},
			error: function(response, statusText) {
							ProcessingErrorResponse(response, statusText, "ENABLE_PHONE", callbackError);
      	  	}
		});

	};


	///////////////////////////////////////////////////////////////////////////////////////////
	//Public Method FinishContact
	this.FinishContact = function (processId, batchId, contactId, callbackSuccess, callbackError){

	var xml = '<contact>' +
						'<processId>' + processId + '</processId>' +
						'<batchId>' + batchId + '</batchId>' +
						'<contactId>' + contactId + '</contactId>' +
						'</contact>';

	$.ajax({
		url: OE_URI + "/finish_contact/",
		type: 'POST',
		contentType: "text/xml",
		data: xml,
		timeout: 60000,
		dataType: "xml",
		success: function(response) {
							ProcessingXmlResponse($(response).find('response').text(), 200, "FINISH_CONTACT", undefined, "FinishContact succeeded", "FinishContact not succeeded", callbackSuccess, callbackError);
      	  	},
			error: function(response, statusText) {
							ProcessingErrorResponse(response, statusText, "FINISH_CONTACT", callbackError);
      	  	}
		});

	};


	///////////////////////////////////////////////////////////////////////////////////////////
	//Public Method ReScheduleContact
	this.ReScheduleContact = function (processId, batchId, contactId, agent, rescheduleDate, outOfBatchAllowed, IgnoreRules, callbackSuccess, callbackError){

 	outOfBatchAllowed = (outOfBatchAllowed === 1 || outOfBatchAllowed === 0)?outOfBatchAllowed:0;
	IgnoreRules = (IgnoreRules === 0 || IgnoreRules === 1)?IgnoreRules:0;
 	var month = rescheduleDate.getMonth() + 1;
	var xml = '<contact>' +
						'<processId>' + processId + '</processId>' +
						'<batchId>' + batchId + '</batchId>' +
						'<contactId>' + contactId + '</contactId>' +
						'<agent>' + agent + '</agent>' +
						'<year>' + rescheduleDate.getFullYear() + '</year>' +
  					'<month>' + month + '</month>' +
  					'<day>' + rescheduleDate.getDate() + '</day>' +
  					'<hour>' + rescheduleDate.getHours() + '</hour>' +
  					'<minute>' + rescheduleDate.getMinutes() + '</minute>' +
  					'<outOfBatchAllowed>' + outOfBatchAllowed + '</outOfBatchAllowed>' +
					'<IgnoreRules>' + IgnoreRules + '</IgnoreRules>' +
						'</contact>';

	$.ajax({
		url: OE_URI + "/reschedule_contact/",
		type: 'POST',
		contentType: "text/xml",
		data: xml,
		timeout: 60000,
		dataType: "xml",
		success: function(response) {
								ProcessingXmlResponse($(response).find('response').text(), 200, "RESCHEDULE_CONTACT", undefined, "ReScheduleContact succeeded", "ReScheduleContact not succeeded", callbackSuccess, callbackError);
      	  	},
			error: function(response, statusText) {
								ProcessingErrorResponse(response, statusText, "RESCHEDULE_CONTACT", callbackError);
      	  	}
		});
	};

	///////////////////////////////////////////////////////////////////////////////////////////
	//Public Method ReScheduleContact
	this.ReScheduleContactToPhone = function (processId, batchId, contactId, agent, rescheduleDate, outOfBatchAllowed, IgnoreRules, phoneCountry, phoneArea, phoneNumber, phoneExtension, phoneType, ZIP, callbackSuccess, callbackError){

	if (String(phoneExtension).trim().length >0){
			phoneNumber = phoneNumber + "," + String(phoneExtension).trim();
		}

	outOfBatchAllowed = (outOfBatchAllowed === 1 || outOfBatchAllowed === 0)?outOfBatchAllowed:1;
	IgnoreRules = (IgnoreRules === 0 || IgnoreRules === 1)?IgnoreRules:0;

 	var month = rescheduleDate.getMonth() + 1;
	var xml = '<contact>' +
					'<processId>' + processId + '</processId>' +
					'<batchId>' + batchId + '</batchId>' +
					'<contactId>' + contactId + '</contactId>' +
					'<agent>' + agent + '</agent>' +
					'<year>' + rescheduleDate.getFullYear() + '</year>' +
  					'<month>' + month + '</month>' +
  					'<day>' + rescheduleDate.getDate() + '</day>' +
  					'<hour>' + rescheduleDate.getHours() + '</hour>' +
  					'<minute>' + rescheduleDate.getMinutes() + '</minute>' +
					'<outOfBatchAllowed>' + outOfBatchAllowed + '</outOfBatchAllowed>' +
					'<IgnoreRules>' + IgnoreRules + '</IgnoreRules>' +
  					'<phoneCountry>' + phoneCountry + '</phoneCountry>' +
					'<phoneArea>' + phoneArea + '</phoneArea>' +
					'<phoneNumber>' + phoneNumber + '</phoneNumber>' +
					'<phoneType>' + phoneType + '</phoneType>' +
					'<ZIP>' + ZIP + '</ZIP>' + 
				'</contact>';

	$.ajax({
		url: OE_URI + "/reschedule_contact_to_phone/",
		type: 'POST',
		contentType: "text/xml",
		data: xml,
		timeout: 60000,
		dataType: "xml",
		success: function(response) {
								ProcessingXmlResponse($(response).find('response').text(), 200, "RESCHEDULE_CONTACT_TOPHONE", undefined, "ReScheduleContactToPhone succeeded", "ReScheduleContactToPhone not succeeded", callbackSuccess, callbackError);
      	  	},
			error: function(response, statusText) {
								ProcessingErrorResponse(response, statusText, "RESCHEDULE_CONTACT_TOPHONE", callbackError);
      	  	}
		});
	};

	///////////////////////////////////////////////////////////////////////////////////////////
	//Public Method ReScheduleContactCampaign
	this.ReScheduleContactCampaign = function (campaignId, contactId, agent, rescheduleDate, outOfBatchAllowed, IgnoreRules, phoneCountry, phoneArea, phoneType, phoneNumber, phoneExtension, ZIP, callbackSuccess, callbackError){
	
		if (String(phoneExtension).trim().length >0){
			phoneNumber = phoneNumber + "," + String(phoneExtension).trim();
		}
 	
	outOfBatchAllowed = (outOfBatchAllowed === 1 || outOfBatchAllowed === 0)?outOfBatchAllowed:0;
	IgnoreRules = (IgnoreRules === 0 || IgnoreRules === 1)?IgnoreRules:0;
 	var month = rescheduleDate.getMonth() + 1;
	var xml = '<contact>' +
					  '<campaignId>' + campaignId + '</campaignId>' +
						'<contactId>' + contactId + '</contactId>' +
						'<agent>' + agent + '</agent>' +
						'<year>' + rescheduleDate.getFullYear() + '</year>' +
  					'<month>' + month + '</month>' +
  					'<day>' + rescheduleDate.getDate() + '</day>' +
  					'<hour>' + rescheduleDate.getHours() + '</hour>' +
  					'<minute>' + rescheduleDate.getMinutes() + '</minute>' +
  					'<outOfBatchAllowed>' + outOfBatchAllowed + '</outOfBatchAllowed>' +	
					'<IgnoreRules>' + IgnoreRules + '</IgnoreRules>' +				
					(phoneCountry ? '<phoneCountry>' + phoneCountry + '</phoneCountry>' : "") +
					(phoneArea ? '<phoneArea>' + phoneArea + '</phoneArea>' : "") +
					(phoneType ? '<phoneType>' + phoneType + '</phoneType>' : "") +
					(phoneNumber ? '<phoneNumber>' + phoneNumber + '</phoneNumber>' : "") +
					(phoneExtension ? '<phoneExtension>' + phoneExtension + '</phoneExtension>' : "88") +
					(ZIP ? '<ZIP>' + ZIP + '</ZIP>' : "") +
			  '</contact>';

	$.ajax({
		url: OE_URI + "/reschedule_contact_campaign/",
		type: 'POST',
		contentType: "text/xml",
		data: xml,
		timeout: 60000,
		dataType: "xml",
		success: function(response) {
								ProcessingXmlResponse($(response).find('response').text(), 200, "RESCHEDULE_CONTACT_CAMPAIGN", undefined, "ReScheduleContactCampaign succeeded", "ReScheduleContactCampaign not succeeded", callbackSuccess, callbackError);
      	  	},
			error: function(response, statusText) {
								ProcessingErrorResponse(response, statusText, "RESCHEDULE_CONTACT_CAMPAIGN", callbackError);
      	  	}
		});

	};


	///////////////////////////////////////////////////////////////////////////////////////////
	//Public Method RecycleContact
	this.RecycleContact = function (processId, batchId, contactId, agent, callbackSuccess, callbackError){

	var xml = '<contact>' +
					  '<processId>' + processId + '</processId>' +
						'<batchId>' + batchId + '</batchId>' +
						'<contactId>' + contactId + '</contactId>' +
						'<agent>' + agent + '</agent>' +
						'</contact>';

	$.ajax({
		url: OE_URI + "/recycle_contact/",
		type: 'POST',
		contentType: "text/xml",
		data: xml,
		timeout: 60000,
		dataType: "xml",
		success: function(response) {
								ProcessingXmlResponse($(response).find('response').text(), 200, "RECYCLE_CONTACT", undefined, "RecycleContact succeeded", "RecycleContact not succeeded", callbackSuccess, callbackError);
      	  	},
			error: function(response, statusText) {
								ProcessingErrorResponse(response, statusText, "RECYCLE_CONTACT", callbackError);
      	  	}
		});

	};


	///////////////////////////////////////////////////////////////////////////////////////////
	//Public Method ExtendBatch
	this.ExtendBatch = function (processId, batchId, endDate, callbackSuccess, callbackError){

  var xmlDate = "";
  if (endDate && endDate.getTime){
	
  	var month = endDate.getMonth() + 1;
  	xmlDate = '<year>' + endDate.getFullYear() + '</year>' +
  						'<month>' + month + '</month>' +
  						'<day>' + endDate.getDate() + '</day>' +
  						'<hour>' + endDate.getHours() + '</hour>' +
  						'<minute>' + endDate.getMinutes() + '</minute>';
  }

	var xml = '<process>' +
						'<processId>' + processId + '</processId>' +
						'<batchId>' + batchId + '</batchId>' +
						xmlDate +
						'</process>';

	$.ajax({
		url: OE_URI + "/extendbatch/",
		type: 'POST',
		contentType: "text/xml",
		data: xml,
		timeout: 60000,
		dataType: "xml",
		success: function(response) {
								ProcessingXmlResponse($(response).find('response').text(), 200, "EXTEND_BATCH", undefined, "ExtendBatch succeeded", "ExtendBatch not succeeded", callbackSuccess, callbackError);
      	  	},
			error: function(response, statusText) {
								ProcessingErrorResponse(response, statusText, "EXTEND_BATCH", callbackError);
      	  	}
		});

	};


 	///////////////////////////////////////////////////////////////////////////////////////////
	//Public Method RecognizePhone
	this.RecognizePhone = function (country, area, number, extension, callbackSuccess, callbackError){

		if (String(extension).trim().length >0){
			number = number + "," + String(extension).trim();
		}
		var xml = '<phone>' +
							'<country>' + country + '</country>' +
							'<area>' + area + '</area>' +
							'<number>' + number + '</number>' +
							'</phone>';
	$.ajax({
		url: OE_URI + "/recognize_phone/",
		type: 'POST',
		contentType: "text/xml",
		data: xml,
		timeout: 60000,
		dataType: "xml",
		success: function(response) {
								var objPhone = undefined;
								var sRet = $(response).find('response').text();
								if (sRet === "1"){
									objPhone = new Phone();
									objPhone.InitFromXML($(response).find('phone'));
								}
								ProcessingXmlResponse(sRet, 200, "RECOGNIZE_PHONE", objPhone, "RecognizePhone succeeded", "RecognizePhone not succeeded", callbackSuccess, callbackError);
      	  	},
			error: function(response, statusText) {
							ProcessingErrorResponse(response, statusText, "RECOGNIZE_PHONE", callbackError);
      	  	}
		});

	};



	///////////////////////////////////////////////////////////////////////////////////////////
	//Public Method RetrieveContact
	this.RetrieveContact = function (contactId, callbackSuccess, callbackError){

	$.ajax({
		url: OE_URI + "/get_contact/" + contactId,
		type: 'GET',
		timeout: 60000,
		dataType: "xml",
		success: function(response) {
								var objContact = undefined;
								var sRet = $(response).find('response').text();
								if (sRet === "1"){
									objContact = new Contact();
									objContact.InitFromXML(response);
      	  			}
								ProcessingXmlResponse(sRet, 200, "GET_CONTACT", objContact, "RetrieveContact succeeded", "RetrieveContact not succeeded", callbackSuccess, callbackError);
      	  	},
			error: function(response, statusText) {
								ProcessingErrorResponse(response, statusText, "GET_CONTACT", callbackError);
      	  	}
		});

	};


	///////////////////////////////////////////////////////////////////////////////////////////
	//Public Method SetDispositionCode
	this.SetDispositionCode = function (processId, batchId, contactId, interactionId, contactCode, businessCode, callbackSuccess, callbackError){

		var xml = '<disposition>' +
							'<processId>' + processId + '</processId>' +
							'<batchId>' + batchId + '</batchId>' +
							'<contactId>' + contactId + '</contactId>' +
							'<interactionId>' + interactionId + '</interactionId>' +
							'<contactCode>' + contactCode + '</contactCode>' +
							'<businessCode>' + businessCode + '</businessCode>' +
							'</disposition>';

	$.ajax({
		url: OE_URI + "/set_disposition_code/",
		type: 'POST',
		contentType: "text/xml",
		data: xml,
		timeout: 60000,
		dataType: "xml",
		success: function(response) {
								var objContact = undefined;
								var sRet = $(response).find('response').text();
								if (sRet === "1"){
									objContact = new Contact();
									objContact.InitFromXML(response);
      	  			}
								ProcessingXmlResponse(sRet, 200, "SET_DISPOSITION_CODE", objContact, "SetDispositionCode succeeded", "SetDispositionCode not succeeded", callbackSuccess, callbackError);
      	  	},
			error: function(response, statusText) {
								ProcessingErrorResponse(response, statusText, "SET_DISPOSITION_CODE", callbackError);
      	  	}
		});
	};


	///////////////////////////////////////////////////////////////////////////////////////////
	//Public Method StartContactImport
	this.StartContactImport = function (divForm, inputFile, importId, dbProvider, formatId, duplicateCheck, duplicateSolver, vipImport, clientImport) {
		var uploadControl = CreateUploadControls (divForm, inputFile, OE_URI + "/start_contact_import/");		
		uploadControl.SetAttr ("ImportId", importId);
		uploadControl.SetAttr ("DbProvider", dbProvider);
		uploadControl.SetAttr ("FormatId", formatId);
		uploadControl.SetAttr ("DuplicateCheck", duplicateCheck);
		uploadControl.SetAttr ("DuplicateSolver", duplicateSolver);
		uploadControl.SetAttr ("VipImport", (vipImport ? "1" : "0"));
		uploadControl.SetAttr ("ClientImport", (clientImport ? "1" : "0"));
		
		uploadControl.Send();
	};

	///////////////////////////////////////////////////////////////////////////////////////////
	//Public Method DeleteContactImport
	this.DeleteContactImport = function (importationId, callbackSuccess, callbackError){

		var xml = '<importation>' +
							'<id>' + importationId + '</id>' +
							'</importation>';

		$.ajax({
			url: OE_URI + "/delete_contact_import/",
			type: 'POST',
			contentType: "text/xml",
			data: xml,
			timeout: 60000,
			dataType: "xml",
			success: function(response) {
								ProcessingXmlResponse($(response).find('response').text(), 200, "DELETE_CONTACT_IMPORT", undefined, "DeleteContactImport succeeded", "DeleteContactImport not succeeded", callbackSuccess, callbackError);
      	  	},
			error: function(response, statusText) {
								ProcessingErrorResponse(response, statusText, "DELETE_CONTACT_IMPORT", callbackError);
      	  	}
		});

	};

	///////////////////////////////////////////////////////////////////////////////////////////
	//Public Method GetImportStatus
	this.GetImportStatus = function (importId, callbackSuccess, callbackError){

		$.ajax({
			url: OE_URI + "/get_import_status/?ImportId=" + importId,
			type: 'GET',
			timeout: 60000,
			dataType: "xml",
			success: function(response) {
								ProcessingXmlResponse($(response).find('response').text(), 200, "GET_IMPORT_STATUS", undefined, "GetImportStatus succeeded", "GetImportStatus not succeeded", callbackSuccess, callbackError);
      	  	},
			error: function(response, statusText) {
								ProcessingErrorResponse(response, statusText, "GET_IMPORT_STATUS", callbackError);
      	  	}
		});

	};

	///////////////////////////////////////////////////////////////////////////////////////////
	//Public Method CreateBatchFromFilter
	this.CreateBatchFromFilter = function (filterId, processId, batchId, startDate, endDate, excludeDuplicates, orderField, priorityBase, inverseOrder, callbackSuccess, callbackError){
		excludeDuplicates= (excludeDuplicates === 1 || excludeDuplicates === 0)?excludeDuplicates:1;
		inverseOrder= (inverseOrder === 1 || inverseOrder === 0)?inverseOrder:0;
		priorityBase = (IsNumeric(priorityBase))?priorityBase:1;

		var xmlOrder = "";
		if (String(orderField).trim().length > 0){
			xmlOrder = '<orderField>' + orderField +  '<orderField>' +
								 '<priorityBase>' + priorityBase +  '<priorityBase>' +
								 '<inverseOrder>' + inverseOrder +  '<inverseOrder>';
		}
		
		endDateConverted = endDate.getFullYear() + "-" + endDate.getMonth() + "-" + endDate.getDate() + " " + endDate.getHours() + ":" + endDate.getMinutes();
		startDateConverted = startDate.getFullYear() + "-" + startDate.getMonth() + "-" + startDate.getDate() + " " + startDate.getHours() + ":" + startDate.getMinutes();
		
		var xml = '<batch>' +
							'<id>' + batchId + '</id>' +
							'<processId>' + processId + '</processId>' +
							'<filterId>' + filterId + '</filterId>' +
							'<startDate>' + startDateConverted + '</startDate>' +
							'<endDate>' + startDateConverted + '</endDate>' +
							'<excludeDuplicates>' + excludeDuplicates + '</excludeDuplicates>' +
							xmlOrder +
							'</batch>';

		$.ajax({
			url: OE_URI + "/create_batch_from_filter/",
			type: 'POST',
			contentType: "text/xml",
			data: xml,
			timeout: 60000,
			dataType: "xml",
			success: function(response) {
								ProcessingXmlResponse($(response).find('response').text(), 200, "CREATE_BATCH_FROM_FILTER", undefined, "CreateBatchFromFilter succeeded", "CreateBatchFromFilter not succeeded", callbackSuccess, callbackError);
      	  	},
			error: function(response, statusText) {
								ProcessingErrorResponse(response, statusText, "CREATE_BATCH_FROM_FILTER", callbackError);
      	  	}
		});

	};

	///////////////////////////////////////////////////////////////////////////////////////////
	//Public Method CreateFilter
	this.CreateFilter = function (filterId, filterValues, callbackSuccess, callbackError){
		var xml = "<filter><filterId>" + filterId + "</filterId><elements>";

		for (var i = 0; i < filterValues.length; i++) {
			xml += "<entry>";
			xml += "<name>" + filterValues[i].Name + "</name>";
			xml += "<operator>" + filterValues[i].Operator + "</operator>";
			xml += "<value>" + filterValues[i].Value + "</value>";
			xml += "<custom>" + (filterValues[i].IsCustom ? "1" : "0") + "</custom>";
			xml += "</entry>";
		}

		xml += "</elements></filter>";

		$.ajax({
			url: OE_URI + "/save_filter/",
			type: 'POST',
			contentType: "text/xml",
			data: xml,
			timeout: 60000,
			dataType: "xml",
			success: function(response) {
								ProcessingXmlResponse($(response).find('response').text(), 200, "CREATE_FILTER", undefined, "CreateFilter succeeded", "CreateFilter not succeeded", callbackSuccess, callbackError);
      	  	},
			error: function(response, statusText) {
								ProcessingErrorResponse(response, statusText, "CREATE_FILTER", callbackError);
      	  	}
		});

	};

	///////////////////////////////////////////////////////////////////////////////////////////
	//Public Method CreateBatchFromImport
	this.CreateBatchFromImport = function (importId, processId, batchId, startDate, endDate, excludeDuplicates, callbackSuccess, callbackError){
		excludeDuplicates= (excludeDuplicates === 1 || excludeDuplicates === 0)?excludeDuplicates:1;

		var xml= '<batch>' +
						 '<id>' + batchId + '</id>' +
						 '<processId>' + processId + '</processId>' +
						 '<importationId>' + importId + '</importationId>' +
						 '<startDate>' + startDate + '</startDate>' +
						 '<endDate>' + endDate + '</endDate>' +
						 '<excludeDuplicates>' + excludeDuplicates + '</excludeDuplicates>' +
						 '</batch>';

		$.ajax({
			url: OE_URI + "/create_batch_from_import/",
			type: 'POST',
			contentType: "text/xml",
			data: xml,
			timeout: 60000,
			dataType: "xml",
			success: function(response) {
								ProcessingXmlResponse($(response).find('response').text(), 200, "CREATE_BATCH_FROM_IMPORT", undefined, "CreateBatchFromImport succeeded", "CreateBatchFromImport not succeeded", callbackSuccess, callbackError);
      	  	},
			error: function(response, statusText) {
								ProcessingErrorResponse(response, statusText, "CREATE_BATCH_FROM_IMPORT", callbackError);
      	  	}
		});

	};
	
	///////////////////////////////////////////////////////////////////////////////////////////
	// Public method GetCampaignDispositions
	this.GetCampaignDispositions = function (campaignId){
		
		$.ajax({
			url: OE_URI + "/get_campaign_dispositions/"+campaignId,
			type: 'GET',
			contentType: "text/xml",
			timeout: 60000,
			dataType: "xml",
			success: function(response) {
				ProcessingXmlResponse($(response).find('response').text(), 200, "GET_CAMPAIGN_DISPOSITIONS", $(response).find('dispositions'), "GetCampaignDispositions succeeded", "GetCampaignDispositions not succeeded", callbackSuccess, callbackError);
      	  	},
			error: function(response, statusText) {
				ProcessingErrorResponse(response, statusText, "GET_CAMPAIGN_DISPOSITIONS", callbackError);
      	  	}
		});
	
	};

	///////////////////////////////////////////////////////////////////////////////////////////
	//Public Method SearchContacts
	this.SearchContacts = function (predicate, callbackSuccess, callbackError){
		
		var xml = IsTypeOf(predicate, "Predicate")?predicate.getPredicate():predicate;

		$.ajax({
			url: OE_URI + "/search_contacts/",
			type: 'POST',
			contentType: "text/xml",
			data: xml,
			timeout: 60000,
			dataType: "xml",
			success: function(response) {
								var contacts = [];								
								$(response).find("contact").each(function() {										
	    						var contact = new Contact();
  	    					contact.InitFromXML($(this));
    	  					contacts[contacts.length] = contact;    	
      					});

								ProcessingXmlResponse($(response).find('response').text(), 200, "SEARCH_CONTACTS", contacts, "SearchContacts succeeded", "SearchContacts not succeeded", callbackSuccess, callbackError);
      	  	},
			error: function(response, statusText) {
								ProcessingErrorResponse(response, statusText, "SEARCH_CONTACTS", callbackError);
      	  	}
		});

	};
	
	if(typeof(callbackSuccess) === "function") {
		g_callbackSuccess = callbackSuccess;
  }

	if(typeof(callbackError) === "function") {
		g_callbackError = callbackError;
  }

}


///////////////////////////////////////////////////////////////////////////////////////////
//Public Object Reports

function Reports (callbackSuccess, callbackError) {
	var m_callbackSuccess = callbackSuccess;
	var m_callbackError = callbackError;
	
	this.DownloadReport = function (viewId, reportParameters) {	
		var xml = "<report><parameters>";

		for (var i = 0; i < reportParameters.length; i++) {
			xml += "<parameter>";
			xml += "<name>" + reportParameters[i].Name + "</name>";
			xml += "<value>" + reportParameters[i].Value + "</value>";
			xml += "</parameter>";
		}

		xml += "</parameters></report>";
		
		window.open("/inconcert/apps/reports/downloadreport/" + viewId + "/" + encodeURIComponent(xml), 'Download');
	};
}

///////////////////////////////////////////////////////////////////////////////////////////
//Public Object Baragent

function Baragent(callbackSuccess, callbackError) {

	///////////////////////////////////////////////////////////////////////////////////////////
	//Public Method GetInteractionId
	this.GetInteractionId = function (interactionId, callbackSuccess, callbackError){
		
		var querystring = (interactionId === undefined)?'':'?interactionid=' + interactionId;
		
		$.jsonp({
			url: BARAGENT_URI + "/get_interactionid" + querystring,						
			timeout: 10000,
			callbackParameter: "callback",
			success: function(response) {
								ProcessingResponse(response, callbackSuccess, callbackError);
      		  	},
			error: function(response, statusText) {
								ProcessingErrorResponse(response, statusText, "GET_INTERACTIONID", callbackError);
    	  	  	}
		});

	};
		
	///////////////////////////////////////////////////////////////////////////////////////////
	//Public Method Wrapup
	this.Wrapup = function (interactionId, callbackSuccess, callbackError){

		var Id = (interactionId === undefined)?'':interactionId;
		var querystring = '?interactionid=' + Id;
			
		$.jsonp({
			url: BARAGENT_URI + "/wrapup" + querystring,
			timeout: 10000,
			callbackParameter: "callback",
			success: function(response) {
								ProcessingResponse(response, callbackSuccess, callbackError);
      		  	},
			error: function(response, statusText) {
							ProcessingErrorResponse(response, statusText, "WRAPUP", callbackError);
      	  	}
		});

	};		

	///////////////////////////////////////////////////////////////////////////////////////////
	//Public Method Answer
	this.Answer = function (interactionId, callbackSuccess, callbackError){

		var Id = (interactionId === undefined)?'':interactionId;
		var querystring = '?interactionid=' + Id;
			
		$.jsonp({
			url: BARAGENT_URI + "/answer" + querystring,
			timeout: 10000,
			callbackParameter: "callback",
			success: function(response) {
								ProcessingResponse(response, callbackSuccess, callbackError);
      		  	},
			error: function(response, statusText) {
							ProcessingErrorResponse(response, statusText, "ANSWER", callbackError);
      	  	}
		});

	};		
			
	///////////////////////////////////////////////////////////////////////////////////////////
	//Public Method Hangup
	this.Hangup = function (interactionId, callbackSuccess, callbackError){

		var Id = (interactionId === undefined)?'':interactionId;
		var querystring = '?interactionid=' + Id;
			
		$.jsonp({
			url: BARAGENT_URI + "/hangup" + querystring,			
			timeout: 10000,
			callbackParameter: "callback",
			success: function(response) {
								ProcessingResponse(response, callbackSuccess, callbackError);
      		  	},
			error: function(response, statusText) {
							ProcessingErrorResponse(response, statusText, "HANGUP", callbackError);
      	  	}
		});

	};					
			
	///////////////////////////////////////////////////////////////////////////////////////////
	//Public Method MakeCall
	this.MakeCall = function (campaign, phone, iml, callbackSuccess, callbackError){

		var querystring = '?campaign=' + campaign +
											'&phone=' + phone +
											'&iml=' + iml;
      		  				
		$.jsonp({
			url: BARAGENT_URI + "/makecall" + querystring,			
			timeout: 10000,
			callbackParameter: "callback",
			success: function(response) {
								ProcessingResponse(response, callbackSuccess, callbackError);
      		  	},
			error: function(response, statusText) {
							ProcessingErrorResponse(response, statusText, "MAKECALL", callbackError);
      	  	}
		});

	};	


	///////////////////////////////////////////////////////////////////////////////////////////
	//Public Method ManualReschedule
	this.ManualReschedule = function (phone, date, hour, iml, callbackSuccess, callbackError){

		var querystring = '?phone=' + phone +
											'&date=' + date +
											'&hour=' + hour +
											'&iml=' + iml;
			
		$.jsonp({
			url: BARAGENT_URI + "/manualreschedule" + querystring,
			timeout: 10000,
			callbackParameter: "callback",
			success: function(response) {
								ProcessingResponse(response, callbackSuccess, callbackError);
      		  	},
			error: function(response, statusText) {
							ProcessingErrorResponse(response, statusText, "MANUALRESCHEDULE", callbackError);
      	  	}
		});

	};	


	///////////////////////////////////////////////////////////////////////////////////////////
	//Public Method Take
	this.Take = function (callbackSuccess, callbackError){
			
		$.jsonp({
			url: BARAGENT_URI + "/take",			
			timeout: 10000,
			callbackParameter: "callback",
			success: function(response) {
								ProcessingResponse(response, callbackSuccess, callbackError);
      		  	},
			error: function(response, statusText) {
							ProcessingErrorResponse(response, statusText, "TAKE", callbackError);
      	  	}
		});

	};	
			
			
	///////////////////////////////////////////////////////////////////////////////////////////
	//Public Method TakeFirst
	this.TakeFirst = function (callbackSuccess, callbackError){
			
		$.jsonp({
			url: BARAGENT_URI + "/takefirst",			
			timeout: 10000,
			callbackParameter: "callback",
			success: function(response) {
								ProcessingResponse(response, callbackSuccess, callbackError);
      		  	},
			error: function(response, statusText) {
							ProcessingErrorResponse(response, statusText, "TAKEFIRST", callbackError);
      	  	}
     
		});

	};	
	
	///////////////////////////////////////////////////////////////////////////////////////////
	//Public Method Hold
	this.Hold = function (callbackSuccess, callbackError){
			
		$.jsonp({
			url: BARAGENT_URI + "/hold",
			timeout: 10000,
			callbackParameter: "callback",
			success: function(response) {
								ProcessingResponse(response, callbackSuccess, callbackError);
      		  	},
			error: function(response, statusText) {
							ProcessingErrorResponse(response, statusText, "HOLD", callbackError);
      	  	}
		});

	};		

	///////////////////////////////////////////////////////////////////////////////////////////
	//Public Method GetInteractionState
	this.GetInteractionState = function (interactionId, callbackSuccess, callbackError){

		var Id = (interactionId === undefined)?'':interactionId;
		var querystring = '?interactionid=' + Id;
					
		$.jsonp({
			url: BARAGENT_URI + "/get_interactionstate" + querystring,
			timeout: 10000,
			callbackParameter: "callback",
			success: function(response) {
								ProcessingResponse(response, callbackSuccess, callbackError);
      		  	},
			error: function(response, statusText) {
							ProcessingErrorResponse(response, statusText, "GET_INTERACTIONSTATE", callbackError);
      	  	}
		});

	};	

	///////////////////////////////////////////////////////////////////////////////////////////
	//Public Method Transfer
	this.Transfer = function (interactionid, phone, value, autocommit, callbackSuccess, callbackError){

		var Id = (interactionid === undefined)?'':interactionid;		
		autocommit = (autocommit === "1" || autocommit === "0")?autocommit:"0";
		
		var querystring = '?interactionid=' + Id +
											'&phone=' + phone +
											'&value=' + value +
											'&autocommit=' + autocommit;
					
		$.jsonp({
			url: BARAGENT_URI + "/transfer" + querystring,
			timeout: 10000,
			callbackParameter: "callback",
			success: function(response) {
								ProcessingResponse(response, callbackSuccess, callbackError);
      		  	},
			error: function(response, statusText) {
							ProcessingErrorResponse(response, statusText, "TRANSFER", callbackError);
      	  	}
		});

	};	
	
 	///////////////////////////////////////////////////////////////////////////////////////////
	//Public Method TransferCommit
	this.TransferCommit = function (interactionId, callbackSuccess, callbackError){

		var Id = (interactionId === undefined)?'':interactionId;		
		var querystring = '?interactionid=' + Id;
										
														
		$.jsonp({
			url: BARAGENT_URI + "/transfercommit" + querystring,
			timeout: 10000,
			callbackParameter: "callback",
			success: function(response) {
								ProcessingResponse(response, callbackSuccess, callbackError);
      		  	},
			error: function(response, statusText) {
							ProcessingErrorResponse(response, statusText, "TRANSFERCOMMIT", callbackError);
      	  	}
		});

	};		
	
 	///////////////////////////////////////////////////////////////////////////////////////////
	//Public Method TransferCancel
	this.TransferCancel = function (interactionId, callbackSuccess, callbackError){

		var Id = (interactionId === undefined)?'':interactionId;		
		var querystring = '?interactionid=' + Id;
										
														
		$.jsonp({
			url: BARAGENT_URI + "/transfercancel" + querystring,
			timeout: 10000,
			callbackParameter: "callback",
			success: function(response) {
								ProcessingResponse(response, callbackSuccess, callbackError);
      		  	},
			error: function(response, statusText) {
							ProcessingErrorResponse(response, statusText, "TRANSFERCANCEL", callbackError);
      	  	}
		});

	};		
	
 	///////////////////////////////////////////////////////////////////////////////////////////
	//Public Method Forward
	this.Forward = function (interactionId, campaign, iml, callbackSuccess, callbackError){

		var Id = (interactionId === undefined)?'':interactionId;				
		var querystring = '?interactionid=' + Id +
											'&campaign=' + campaign +
											'&iml=' + iml;
																								
		$.jsonp({
			url: BARAGENT_URI + "/forward" + querystring,
			timeout: 10000,
			callbackParameter: "callback",
			success: function(response) {
								ProcessingResponse(response, callbackSuccess, callbackError);
      		  	},
			error: function(response, statusText) {
							ProcessingErrorResponse(response, statusText, "FORWARD", callbackError);
      	  	}
		});

	};			
				
				
 	///////////////////////////////////////////////////////////////////////////////////////////
	//Public Method Redial
	this.Redial = function (interactionId, phone, callbackSuccess, callbackError){

		var id = (interactionId === undefined)?'':interactionId;				
		var querystring = '?interactionid=' + id +
											'&phone=' + phone;
																											
		$.jsonp({
			url: BARAGENT_URI + "/redial" + querystring,
			timeout: 60000,
			callbackParameter: "callback",
			success: function(response) {
								ProcessingResponse(response, callbackSuccess, callbackError);
      		  	},
			error: function(response, statusText) {
							ProcessingErrorResponse(response, statusText, "REDIAL", callbackError);
      	  	}
		});

	};							
				
	///////////////////////////////////////////////////////////////////////////////////////////
	//Public Method TransferPopup
	this.TransferPopup = function (callbackSuccess, callbackError){
			
		$.jsonp({
			url: BARAGENT_URI + "/transfer_popup",
			timeout: 10000,
			callbackParameter: "callback",
			success: function(response) {
								ProcessingResponse(response, callbackSuccess, callbackError);
      		  	},
			error: function(response, statusText) {
							ProcessingErrorResponse(response, statusText, "TRANSFER_POPUP", callbackError);
      	  	}
		});

	};					
		
		
	///////////////////////////////////////////////////////////////////////////////////////////
	//Public Method ConferencePopup
	this.ConferencePopup = function (callbackSuccess, callbackError){
			
		$.jsonp({
			url: BARAGENT_URI + "/conference_popup",
			timeout: 10000,
			callbackParameter: "callback",
			success: function(response) {
								ProcessingResponse(response, callbackSuccess, callbackError);
      		  	},
			error: function(response, statusText) {
							ProcessingErrorResponse(response, statusText, "CONFERENCE_POPUP", callbackError);
      	  	}
		});

	};
				
	///////////////////////////////////////////////////////////////////////////////////////////
	//Public Method GetPhoneState
	this.GetPhoneState = function (interactionId, callbackSuccess, callbackError){

		var Id = (interactionId === undefined)?'':interactionId;				
		var querystring = '?interactionid=' + Id;
																								
		$.jsonp({
			url: BARAGENT_URI + "/get_phonestate" + querystring,
			timeout: 10000,
			callbackParameter: "callback",
			success: function(response) {
								ProcessingResponse(response, callbackSuccess, callbackError);
      		  	},
			error: function(response, statusText) {
							ProcessingErrorResponse(response, statusText, "GET_PHONESTATE", callbackError);
      	  	}
		});

	};											
	
	///////////////////////////////////////////////////////////////////////////////////////////
	//Public Method AcceptPreview
	this.AcceptPreview = function (interactionId, callbackSuccess, callbackError){

		var Id = (interactionId === undefined)?'':interactionId;				
		var querystring = '?interactionid=' + Id;
																								
		$.jsonp({
			url: BARAGENT_URI + "/acceptpreview" + querystring,			
			timeout: 10000,
			callbackParameter: "callback",
			success: function(response) {
								ProcessingResponse(response, callbackSuccess, callbackError);
      		  	},
			error: function(response, statusText) {
							ProcessingErrorResponse(response, statusText, "ACCEPTPREVIEW", callbackError);
      	  	}
		});

	};						

	///////////////////////////////////////////////////////////////////////////////////////////
	//Public Method CancelPreview
	this.CancelPreview = function (interactionId, callbackSuccess, callbackError){

		var Id = (interactionId === undefined)?'':interactionId;				
		var querystring = '?interactionid=' + Id;
																								
		$.jsonp({
			url: BARAGENT_URI + "/cancelpreview" + querystring,			
			timeout: 10000,
			callbackParameter: "callback",
			success: function(response) {
								ProcessingResponse(response, callbackSuccess, callbackError);
      		  	},
			error: function(response, statusText) {
							ProcessingErrorResponse(response, statusText, "CANCELPREVIEW", callbackError);
      	  	}
		});

	};						
	
	///////////////////////////////////////////////////////////////////////////////////////////
	//Public Method GetState
	this.GetState = function (callbackSuccess, callbackError){
																								
		$.jsonp({
			url: BARAGENT_URI + "/get_state",
			timeout: 10000,
			callbackParameter: "callback",
			success: function(response) {
								ProcessingResponse(response, callbackSuccess, callbackError);
      		  	},
			error: function(response, statusText) {
							ProcessingErrorResponse(response, statusText, "GET_STATE", callbackError);
      	  	}
		});

	};	
	
	///////////////////////////////////////////////////////////////////////////////////////////
	//Public Method ChangeState
	this.ChangeState = function (state, callbackSuccess, callbackError){
		
		var querystring = '?state=' + state;
																								
		$.jsonp({
			url: BARAGENT_URI + "/changestate" + querystring,
			timeout: 10000,
			callbackParameter: "callback",
			success: function(response) {
								ProcessingResponse(response, callbackSuccess, callbackError);
      		  	},
			error: function(response, statusText) {
							ProcessingErrorResponse(response, statusText, "CHANGESTATE", callbackError);
      	  	}
		});

	};		

	///////////////////////////////////////////////////////////////////////////////////////////
	//Public Method SetbuttonEnabled
	this.SetbuttonEnabled = function (idButton, state,  callbackSuccess, callbackError){
		
		var Id = (idButton === undefined)?'':idButton;		
		state = (state === "1" || state === "0")?state:"0";
		
		var querystring = '?buttonid=' + Id +
							'&state=' + state;
																								
		$.jsonp({
			url: BARAGENT_URI + "/setbuttonenabled" + querystring,
			timeout: 10000,
			callbackParameter: "callback",
			success: function(response) {
								ProcessingResponse(response, callbackSuccess, callbackError);
      		  	},
			error: function(response, statusText) {
							ProcessingErrorResponse(response, statusText, "SETBUTTONENABLED", callbackError);
      	  	}
		});

	};
	
	///////////////////////////////////////////////////////////////////////////////////////////
	//Public Method SetbuttonVisible
	this.SetbuttonVisible = function (idButton, state,  callbackSuccess, callbackError){
		
		var Id = (idButton === undefined)?'':idButton;		
		state = (state === "1" || state === "0")?state:"0";
		
		var querystring = '?buttonid=' + Id +
							'&state=' + state;
																								
		$.jsonp({
			url: BARAGENT_URI + "/setbuttonvisible" + querystring,
			timeout: 10000,
			callbackParameter: "callback",
			success: function(response) {
								ProcessingResponse(response, callbackSuccess, callbackError);
      		  	},
			error: function(response, statusText) {
							ProcessingErrorResponse(response, statusText, "SETBUTTONVISIBLE", callbackError);
      	  	}
		});

	};	
				
	///////////////////////////////////////////////////////////////////////////////////////////
	//Public Method recording
	this.Recording = function (interactionid, action,  callbackSuccess, callbackError){
		
		var Id = (interactionid === undefined)?'':interactionid;		
		action = (action === "0" || action === "1" || action === "2")?action:"0";
		
		var querystring = '?interactionid=' + Id + '&action=' + action;
																								
		$.jsonp({
			url: BARAGENT_URI + "/recording" + querystring,
			timeout: 10000,
			callbackParameter: "callback",
			success: function(response) {
								ProcessingResponse(response, callbackSuccess, callbackError);
      		  	},
			error: function(response, statusText) {
							ProcessingErrorResponse(response, statusText, "RECORDING", callbackError);
      	  	}
		});

	};	
	
					
	///////////////////////////////////////////////////////////////////////////////////////////
	//Public Method PlayAudio
	this.PlayAudio = function (streamserver, repositoryserver, interactionid, section,  part, callbackSuccess, callbackError){
		
		var Id = (interactionid === undefined)?'':interactionid;		
		
		var querystring = '?streamserver=' + streamserver + 
											'&repositoryserver=' + repositoryserver + 
											'&interactionid=' + Id + 
											'&section=' + section +
											'&part=' + part;
																								
		$.jsonp({
			url: BARAGENT_URI + "/playaudio" + querystring,
			timeout: 10000,
			callbackParameter: "callback",
			success: function(response) {
								ProcessingResponse(response, callbackSuccess, callbackError);
      		  	},
			error: function(response, statusText) {
							ProcessingErrorResponse(response, statusText, "PLAYAUDIO", callbackError);
      	  	}
		});

	};	
					           
	if(typeof(callbackSuccess) === "function") {
		g_callbackSuccess = callbackSuccess;
  }

	if(typeof(callbackError) === "function") {
		g_callbackError = callbackError;
  }
}