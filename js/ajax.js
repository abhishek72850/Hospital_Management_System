(function($){
	
	$.fn.manager=function(opts){

		var defaults={
			task:null,
			url:"",
			data:null,
			callback:false,
			callbackfunc:function(){}
		};

		var config=$.extend({},defaults,opts);

		var request=$.ajax({
			url:config.url,
			method:"POST",
			dataType:"text",
			data:config.data
		});
		

		request.done(function(data){
			//console.log(data);
			config.callbackfunc(data);
			
			//data=JSON.parse(data);
			/*if(data.success){
				
			}
			else{
					
			}*/
		});
		
		request.fail(function(jqXhr,data,error){
			//console.log(data);
		});
	};

})(jQuery);