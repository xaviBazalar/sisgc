var NewSession = null;
var oe = null;
var ba = null;

function ResponseSuccess(response) {
	if (response.func === "MAKECALL"){
			var objid = response.data;
			document.forms.formLogin.elements.IdCall.value = objid.interactionId;
	}
	if (response.func === "GET_INTERACTIONID"){
			var objid = response.data;
			document.forms.formLogin.elements.IdCall.value = objid.interactionId;
	}
	else {
		alert("ResponseSuccess: " + response.code + "\r\n" + response.message + "\r\n Internal Code: " + response.internalCode);
	}
		
}

function ResponseError(response) {
	alert("ResponseError: " + response.code + "\r\n" + response.message + "\r\n Internal Code: " + response.internalCode);
}

		
	function MakeCall() {				
		
		var Campaign = document.forms.formLogin.elements.Campaign.value;		
		var Number = document.forms.formLogin.elements.Number.value;
		
		ba = new Baragent(ResponseSuccess, ResponseError);
		ba.MakeCall(Campaign,Number,"");

				
		return false;
	}
		
	function GetInteractionId(){		
	
		ba = new Baragent(ResponseSuccess, ResponseError);
		ba.GetInteractionId();
		
	}
	
	function Hangup() {				
		ba = new Baragent(ResponseSuccess, ResponseError);
		var IdCall = document.forms.formLogin.elements.IdCall.value;
		ba.Hangup(IdCall);
	}
	function Wrapup() {				
		ba = new Baragent(ResponseSuccess, ResponseError);
		var IdCall = document.forms.formLogin.elements.IdCall.value;
		ba.Wrapup(IdCall);
	}
	
				
	function LoginFromToken(){				
				
		
		var identityToken = document.forms.formLogin.elements.identityToken.value;
		
		if (NewSession === null){
			NewSession = new Session(ResponseSuccess, ResponseError);				
		}
				
		NewSession.LoginFromToken("OutboundEngine", identityToken);
		
		}
		
	function ReScheduleContact(){				

		var country =document.forms.formLogin.elements.country.value;
		var area =document.forms.formLogin.elements.area.value;
		var phone =document.forms.formLogin.elements.phone.value.replace(/[\\[\\]-]/gi,'');
		
						
		if (NewSession !== null){
			if (oe === null){
				oe = new OutBoundEngine(ResponseSuccess, ResponseError);	
				oe = new OutBoundEngine(ResponseSuccess, ResponseError);				
			}			
			oe.RecognizePhone(country,area,phone,"", function(response){
				var now = new Date(); 
				var objPhone = response.data;
				var utcNow = ConvertToUtc(now, objPhone.getTimezone());
				var agent = document.forms.formLogin.elements.agent.value;		
				var processId = document.forms.formLogin.elements.processId.value;
				var batchId = document.forms.formLogin.elements.batchId.value;
				var contactId4 = document.forms.formLogin.elements.contactId4.value;				
				oe.ReScheduleContact(processId, batchId, contactId4, agent, utcNow);
				}, ResponseError);
		} 
		else {
			alert("Session not found");
		}
				
		}

	function SetDispositionCode() {				

		var processId2 =document.forms.formLogin.elements.processId2.value;
		var batchId2 =document.forms.formLogin.elements.batchId2.value;
		var contactId5 =document.forms.formLogin.elements.contactId5.value;
		var interactionId2 =document.forms.formLogin.elements.interactionId2.value;
		var contactCode =document.forms.formLogin.elements.contactCode.value;
		var businessCode =document.forms.formLogin.elements.businessCode.value;		
								
		if (NewSession !== null){
			if (oe === null){
				oe = new OutBoundEngine(ResponseSuccess, ResponseError);								
			}			
			oe.SetDispositionCode(processId2, batchId2, contactId5, interactionId2, contactCode, businessCode, ResponseSuccess, ResponseError);
		} 
		else {
			alert("Session not found");
		}
				
		}
		
		

	