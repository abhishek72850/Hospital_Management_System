$(document).ready(function(){

	$("#loginCheck").on("click",function(){

		if(his.islogin){

		}
		else{
			$.featherlight({iframe: 'loginframe.php',loading:'Please wait...', iframeMaxWidth: '100%', iframeWidth: 500,iframeHeight: 300});
		}
	});

	$("#searchForm").on("submit",function(e){
		e.preventDefault();

		var query=this.search.value;

		$('.card_cont>*').detach();

		$(this).manager({
			url:"server/hospitalmanager.php",
			data: {
				query:query,
				action:'search'
			},
			callbackfunc:function(data){
				data=JSON.parse(data);

				console.log(data);

				for(i in data['data']){

					var himg=$("<img />",{
						"src":data['data'][i]['h_pic']
					});

					var hname=$("<h4></h4>",{
						"text":data['data'][i]['h_name']
					});

					var addr=$("<address></address>",{
						"text":data['data'][i]['addr_text']
					});

					var rating=$("<span></span>",{
						"text":""
					});

					var link=$("<a></a>",{
						"href":"hinfo.php?hid="+data['data'][i]['h_id'],
						"text":"View"
					});

					var vbut=$("<button></button>",{
						"data-hid":data['data'][i]['h_id']
					});

					vbut.append(link);

					var card=$("<div></div>",{
						"class":"result_card"
					});

					var card_sub_sec_a=$("<div></div>",{
						"class":"card_sub_sec"
					});

					var card_sub_sec_b=$("<div></div>",{
						"class":"card_sub_sec"
					});

					var sec_b=$("<div></div>");

					var card_sub_sec_c=$("<div></div>",{
						"class":"card_sub_sec"
					});

					card_sub_sec_a.append(himg);

					sec_b.append(hname);
					sec_b.append(addr);
					sec_b.append(rating);

					card_sub_sec_b.append(sec_b);

					card_sub_sec_c.append(vbut);

					card.append(card_sub_sec_a);
					card.append(card_sub_sec_b);
					card.append(card_sub_sec_c);

					$('.card_cont').append(card);					
				}
			}	
		});
	});


	window.swapToSignup=function(){
		console.log("Parent called");
		var current = $.featherlight.current();
		
		current.close();
		current.close();
		
		$.featherlight({iframe: 'signupframe.php', loading:'Please wait...',iframeMaxWidth: '100%', iframeWidth: 500,iframeHeight: 300});
	}
});