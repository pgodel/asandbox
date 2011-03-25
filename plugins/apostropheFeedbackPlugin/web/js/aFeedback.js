function aFeedbackConstructor() 
{	
	this.feedbackForm = function(options)
	{
		var feedbackURL = options['url'];
		var ajax = options['ajax'];
		var feedbackLink = $('#a-feedback-link');
		
		feedbackLink.unbind('click.feedbackLink').bind('click.feedbackLink',function(e){
			e.preventDefault();			
			$('#a-feedback-footer .a-feedback-submitted').html(''); 
			$('#a-feedback-footer #a-feedback-link').hide(); 
			aBusy('#a-feedback-footer .a-feedback-form-container');
			$.get(feedbackURL, { }, function (data) { 
				$('#a-feedback-footer .a-feedback-form-container').html(data); 
				$('#a-feedback-footer .a-feedback-form-container').show();
				aReady('#a-feedback-footer .a-feedback-form-container');
				$('#a-feedback-form-cancel-button').unbind('click.cancelFeedback').bind('click.cancelFeedback',function(e){
					e.preventDefault();
					$('#a-feedback-footer .a-feedback-form-container').hide(); 
					$('#a-feedback-footer #a-feedback-link').show();
				});
			});
		});
	};			
}

window.aFeedback = new aFeedbackConstructor();