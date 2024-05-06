jQuery( function( $ ){	

	jQuery.ajaxSetup({
		headers: {
			'X-CSRF-TOKEN': jQuery('meta[name="csrf-token"]').attr('content')
		}
	});

	
 	/*Action : ajax
 	* used to: submit forms
 	* Instance of: Jquery vailidate libaray
	* @JSON 
 	**/
	$("#form").validate({
		errorPlacement: function (error, element) {
			 return;
		},
		highlight: function(element) {
        	$(element).addClass('is-invalid');
        	$(element).parent().addClass("error");
	    },
	    unhighlight: function(element) {
	    	$(element).parent().removeClass("error");
	        $(element).removeClass('is-invalid').addClass('is-valid');
	    },
		submitHandler: function(form){
			
			var formData = new FormData($("#form")[0]);
			$.ajax({
			  	beforeSend:function(){
			  		$("#form").find('button').attr('disabled',true);
					$("#form").find('button>i').show(); 
			  	},
			  	url: $("#form").attr('action'),
			  	data: formData,
			  	type: 'POST',
			  	processData: false,
    			contentType: false,
			  	success:function(response){
				  	if(response.success){
				        toastr.success(response.message,'Success');
				        console.log(response.reload);
				        if (response.reload != undefined) {
				        	location.reload();
				        }else if (response.redirect_url != undefined) {
							setTimeout(function(){
								 location.href = response.redirect_url;
							},1000);
						}
				  	}else{
					  
				  	}
				  	$(".modal").modal('hide');
			  	},
			  	complete:function(){
			  		$("#form").find('button').attr('disabled',false);
					$("#form").find('button>i').hide(); 
			  	},
              	error:function(status,error){
					var errors = JSON.parse(status.responseText);
					var msg_error = '';
					if(status.status == 401){
	                    $("#form").find('button').attr('disabled',false);
	                    $("#form").find('button>i').hide();  
						$.each(errors.error, function(i,v){	
							msg_error += v[0]+'!</br>';
						});
						toastr.error( msg_error,'Opps!'); 
					}else{
						toastr.error(errors.message,'Opps!');
					} 				
              	}		  
			});	
			return false;
		}
	});
	
    $("body").on("submit","#formAddClient",function(e){
        e.preventDefault();
        
        var formData = new FormData($(this)[0]);
       
			$.ajax({
			  	beforeSend:function(){
			  		$(this).find('button').attr('disabled',true);
					$(this).find('button>i').show(); 
			  	},
			  	url: $(this).attr('action'),
			  	data: formData,
			  	type: 'POST',
			  	processData: false,
    			contentType: false,
			  	success:function(response){
				  	if(response.success){
				        toastr.success(response.message,'Success');
				        
				  	}else{
					  
				  	}
				  	$(".modal").modal('hide');
			  	},
			  	complete:function(){
			  		$(this).find('button').attr('disabled',false);
					$(this).find('button>i').hide(); 
			  	},
              	error:function(status,error){
					var errors = JSON.parse(status.responseText);
					var msg_error = '';
					if(status.status == 401){
	                    $(this).find('button').attr('disabled',false);
	                    $(this).find('button>i').hide();  
						$.each(errors.error, function(i,v){	
							msg_error += v[0]+'!</br>';
						});
						toastr.error( msg_error,'Opps!'); 
					}else{
						toastr.error(errors.message,'Opps!');
					} 				
              	}		  
			});	
        
    })

	$("body").on("click",".addVehicle",function(){
		$("#form")[0].reset();
		$("#vid").val('');
		$("#vehicle_number").val('');
		$("#milage").val(r.milage);
		$("#fuel_consumption").val('');
		$("#driving_behaviour").val('');
	});
	

	$("body").on("click",".editVehicle",function(){
		$("#form")[0].reset();
		var r = $(this).data('vehicle');
		$("#vid").val(r.id);
		$("#vehicle_number").val(r.vehicle_number);
		$("#milage").val(r.milage);
		$("#fuel_consumption").val(r.fuel_consumption);
		$("#driving_behaviour").val(r.driving_behaviour);
	});

	$("body").on("click",".update_user",function(){
		$("#form")[0].reset();
		var r = $(this).data('data');
		$("#hid_id").val(r.id);
		$("#crm_url").val(r.meta_data.crm_url);
		$("#crm_status").val(r.status);
		$("#crm_username").val(r.meta_data.crm_username);
		$("#crm_password").val(r.meta_data.crm_password);
	});
	//send mail to admin
	$("body").on("click",".sendMailToAdmin",function(){
		var id = $(this).data('id');
		$.ajax({
		    url: ajaxurl+'/admin/sendMailToAdmin',
		    type:'POST',
		    data:{ id:id},
		    beforeSend:function(){
		  		$(".sendMailToAdmin").find('i#spin_loader2').show(); 
		  	},
		    success:function(response){
		      	if (response.success) {
			      	if(response.success){
				        toastr.success(response.message,'Success');
				         
				  	}
		      	}
		  	},
		  	complete:function(){
		  		$(".sendMailToAdmin").find('i#spin_loader2').hide(); 
		  	},
          	error:function(status,error){
				var errors = JSON.parse(status.responseText);
				var msg_error = '';
				if(status.status == 401){
                    $(".sendMailToAdmin").find('i#spin_loader2').hide(); 
					$.each(errors.error, function(i,v){	
						msg_error += v[0]+'!</br>';
					});
					toastr.error( msg_error,'Opps!'); 
				}else{
					toastr.error(errors.message,'Opps!');
				} 				
          	}        
		}); 
		return false;
	});
    	function getAddForm(id){
    	    $.ajax({
    	    url: ajaxurl+'/client/ajax/add',
    	    type:'GET',
    	    data:{id:id},
    	    success:function(response){
    	      		$('#modalOpen').html(response.html);
    	  	},
    	  	complete:function(){
    	  		$("#clientModal").modal('show'); 
    	  		  
    	  	},
    	}); 
    	}
	
        $('body').on('click','.updateClientModalBtn',function(){
         var id = $(this).attr('data-id');
            getAddForm(id);
        }); 
	
	
        $('body').on('click','.addClientModalBtn',function(){
            var id = $(this).attr('data-id')
         var data = {
                    'id': id,
                    'customer': $("#order_customer").val(),
                    'customerextra': $("#order_customerextra").val(),
                    'address1': $("#order_address1").val(),
                    'address2': $("#order_address2").val(),
                    'city': $("#order_city").val(),
                    'fax': $("#order_fax").val(),
                    'plz': $("#order_plz").val(),
                    'region': $("#order_region").val(),
                    'email': $("#order_email").val(),
                    'telefon': $("#order_telefon").val(),
                    'country': $("#order_country").val()
                     }
            $.ajax({
			  	beforeSend:function(){
			  		$(this).find('button').attr('disabled',true);
					$(this).find('button>i').show(); 
			  	},
			  	url: ajaxurl+'/client/store',
			  	data: data,
			  	type: 'POST',
			  	success:function(response){
				  	if(response.success){
				        toastr.success(response.message,'Success');
				        
				  	}else{
					  
				  	}
				  	$(".modal").modal('hide');
			  	},
			  	complete:function(){
			  		$(this).find('button').attr('disabled',false);
					$(this).find('button>i').hide(); 
			  	},
              	error:function(status,error){
					var errors = JSON.parse(status.responseText);
					var msg_error = '';
					if(status.status == 401){
	                    $(this).find('button').attr('disabled',false);
	                    $(this).find('button>i').hide();  
						$.each(errors.error, function(i,v){	
							msg_error += v[0]+'!</br>';
						});
						toastr.error( msg_error,'Opps!'); 
					}else{
						toastr.error(errors.message,'Opps!');
					} 				
              	}		  
			});	
        
        })
	$('.livesearchcustomer').on('select2:selecting', function(e) {
		console.log('Selecting: ' , e.params.args.data);
		const data = e.params.args.data.data;
		$("#order_customer").val(data.customer);
		$("#order_customerextra").val(data.customerextra);
		$("#order_address1").val(data.address1);
		$("#order_address2").val(data.address2);
		$("#order_city").val(data.city);
		$("#order_fax").val(data.fax);
		$("#order_plz").val(data.plz);
		$("#order_region").val(data.region);
		$("#order_email").val(data.email);
		$("#order_telefon").val(data.telefon);
		$("#order_country").val(data.country);
 		$(".addClientModalBtn").text('Update Client');
		$(".addClientModalBtn").attr('data-id',data.id)
		$(".resetClient").show()

	  });
	  
	  $('.resetClient').on('click', function(e) {
	
		$("#order_customer").val('');
		$("#order_customerextra").val('');
		$("#order_address1").val('');
		$("#order_address2").val('');
		$("#order_city").val('');
		$("#order_fax").val('');
		$("#order_plz").val('');
		$("#order_region").val('');
		$("#order_email").val('');
		$("#order_telefon").val('');
		$("#order_country").val('');
 		$(".addClientModalBtn").text('Add New Client');
		$(".addClientModalBtn").attr('data-id',0)
		$(".resetClient").hide();
		$('.livesearchcustomer').val(null).trigger('change');

	  });
	  
	  $(".viewClient").on('click', function(){
		const id = $(this).data('id');
		$.ajax({
    	    url: ajaxurl+'/client/ajax/view/'+id,
    	    type:'GET',
    	    data:{id:id},
    	    success:function(response){
    	      		$('#client_view_result').html(response.html);
    	  	},
    	  	complete:function(){
    	  		$("#clientViewModal").modal('show');   
    	  	},
    	}); 
	});

	$(".addClientmodalbtn").on('click', function(){
		$("#form")[0].reset();
	});
});
