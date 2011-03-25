// apostropheReady is called at domReady
// it hooks into the a_js javascript framework
// it can be used for progressive enhancements at runtime
// such as Cufon text replacement

function apostropheReady()
{

	// top navigation enhancements 
	$(".a-nav-main .a-nav-item").hover(function(e){
		e.preventDefault();
		$(this).stop().animate({ 
	    backgroundColor: "#ccc"
	  }, 125 );
	},function(e){
		e.preventDefault();
		$(this).stop().animate({ 
	    backgroundColor: "#efefef"
	  }, 250 );		
	});

}

