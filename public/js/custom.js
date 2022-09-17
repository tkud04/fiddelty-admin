   
$(document).ready(function(){
	$('#add-discount-input').hide();
	$('#result-box').hide();
	$('#finish-box').hide();
	
	
	$('#tz-div-side2').hide();
	$('#tz-loading').hide();
	$('#tz-timezone-error').hide();
	
	$('#as-other').hide();
	
	$('#at-same').hide();
	$('#at-other').hide();
	
	hideUnselects();
	hideSelectErrors();
	if($("#add-discount-type").val() == "single"){} 
	else {$('#sku-form-row').hide(); }
	
        $("select.customer-type").change((e) =>{
			e.preventDefault();
			let ct = $(this).val();
			console.log("ct: ",ct);
		});
		
		$('#spp-show').click((e) => {
	   e.preventDefault();
	   let spps = $('#spp-s').val();
	   
	   if(spps == "hide"){
		   $('#as-password').attr('type',"password");
		   $('#spp-show').html("Show");
		   $('#spp-s').val("show");
	   }
	   else{
		   $('#as-password').attr('type',"text");
		   $('#spp-show').html("Hide");
		   $('#spp-s').val("hide");
	   }
   });
		
		$("#server").change((e) =>{
			e.preventDefault();
			let server = $("#server").val();
			console.log("server: ",server);
			
			if(server == "other"){
				$('#as-other').fadeIn();     
            }
            else{
				$('#as-other').hide();     
            }
			
		});
		 $("#add-sender-submit").click(function(e){            
		       e.preventDefault();
			   let valid = true;
			   let name = $('#as-name').val(), username = $('#as-username').val(),
			   pass = $('#as-password').val(), s = $('#server').val(),
			   ss = $('#as-server').val(), sp = $('#as-sp').val(), sec = $('#as-sec').val();
			   
			   if(name == "" || username == "" || pass == "" || s == "none"){
				   valid = false;
			   }
			   else{
				   if(s == "other"){
					   if(ss == "" || sp == "" || sec == "nonee") valid = false;
				   }
			   }
			   
			   if(valid){
				 $('#add-sender-form').submit();
			    //updateDeliveryFees({d1: d1, d2: d2});  
			   }
			   else{
				   alert("Please fill all required fields");
			   }
             
		  });
		
		
		$("input.form-control.images").change((e) =>{
			e.preventDefault();
			let cc = $(this)[0].files;
			console.log(cc);
		});
		
         $("#tz-change").click(function(e){            
		       e.preventDefault();
              $('#tz-div-side1').hide();
              $('#tz-div-side2').fadeIn();
		  });
		  
		  $("#tz-back").click(function(e){            
		       e.preventDefault();
              $('#tz-div-side2').hide();
              $('#tz-div-side1').fadeIn();
		  });

		  $("#tz-form").submit(function(e){            
		       e.preventDefault();
			   $('#tz-timezone-error').hide();
			   let tz = $('#tz-timezone').val();
			   
			   if(tz == "none"){
				  $('#tz-timezone-error').fadeIn();
			   }
			   else{
				   $('#tz-submit').hide();
		           $('#tz-loading').fadeIn();
			       updateTZ({tz: tz});   
			   }

		  });
		  
		  $("#at-type").change(function(e){            
		       e.preventDefault();
               let atype = $(this).val();
            
			   if(atype == "same"){			 
                $('#at-other').hide();			 
                $('#at-same').fadeIn();			 
			   }
			   else if(atype == "other"){			 
                $('#at-same').hide();			 
                $('#at-other').fadeIn();			 
			   }
			   else if(atype == "none"){			 
                $('#at-same').hide();			 
                $('#at-other').hide();			 
			   }
		  });

		  
});