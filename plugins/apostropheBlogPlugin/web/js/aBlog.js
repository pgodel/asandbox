function aBlogEnableTitle()
{
	apostrophe.formUpdates({ 'selector': '#a-blog-item-title-interface', 'update': 'a-blog-title-and-slug' }); 

	// Form has class, input element has id
	var titleInterface = $('#a-blog-item-title-interface');
	var tControls = titleInterface.find('ul.a-controls');
	var tInput = titleInterface.find('.a-title');
	var originalTitle = tInput.val();
	tInput.keyup(function(event) {
		if (tInput.val().trim() != originalTitle.trim())
		{
			titleInterface.addClass('has-changes');
			tControls.fadeIn();
		}
		return false;
	});
	titleInterface.find('.a-cancel').click(function() {
		tInput.val(originalTitle);
		tControls.hide();
		return false;
	});
}

function aBlogEnableSlug()
{
	apostrophe.formUpdates({ 'selector': '#a-blog-item-permalink-interface', 'update': 'a-blog-title-and-slug' }); 
	
	// Form has class, input element has id
	var slugInterface = $('#a-blog-item-permalink-interface');
	var tControls = slugInterface.find('ul.a-controls');
	var tInput = slugInterface.find('.a-slug');
	var originalSlug = tInput.val();
	tInput.keyup(function(event) {
		if (tInput.val().trim() != originalSlug.trim())
		{
			slugInterface.addClass('has-changes');
			tControls.fadeIn();
		}
		return false;
	});
	slugInterface.find('.a-cancel').click(function() {
		tInput.val(originalSlug);
		tControls.hide();
		return false;
	});
}

function aBlogUpdateComments(enabled, feedback)
{
	if (enabled)
	{
		$('.section.comments .allow_comments_toggle').addClass('enabled').removeClass('disabled');
	}
	else
	{
		$('.section.comments .allow_comments_toggle').addClass('disabled').removeClass('enabled');		
	}
}

function aBlogEnableNewForm()
{
	var newForm = $('.a-blog-admin-new-form');
	newForm.submit(function() {
		var form = $(this);
		apostrophe.updating('.a-blog-admin-new-ajax');
		$.post(form.attr('action'), $(this).serialize(), function(data) {
			$(document).append(data);	
		});
		return false;
	});
}

function aBlogEnableForm(options)
{
	var changed = false;
	var savedState = null;
	var form = $('#a-admin-form');
	apostrophe.formUpdates({ selector: '#a-admin-form', update: 'a-admin-form' });
	// Due to the way our markup is structured this is a better place for the little
	// 'updating' message
	$('.a-subnav-wrapper').addClass('a-ajax-attach-updating');
	var status = form.find('[name="a_blog_item[publication]"]');
	var init = true;
	
	// A convenience within this closure to keep us from getting lazy and
	// using selectors that aren't specific to the form
	function find(sel)
	{
		return form.find(sel);
	}
	
	status.change(function() {
		var c = form.find('.a-published-at-container');
		var s = status.val();
		if (s === 'schedule') 
		{
			c.show();
		}
		else
		{
			c.hide();
		}
		if (!init)
		{
			find('.a-save-blog-main .label').text(options['update-labels'][s]);
		}
		init = false;
	});
	status.change();

	find('.template.section select').change(function() {
		alert(options['template-change-warning']);
		// Let the form submit as a full refresh
		$(form).unbind('submit.aFormUpdates');
	});
	
	find('.post-editors-toggle').click(function() {
		find('.post-editors-options').show();
		find('.post-editors-toggle').hide();
		return false;
	});
	
	var p = { 'choose-one': options['editors-choose-label'] };
	aMultipleSelect('#editors-section', p);
	p = { 'choose-one': options['categories-choose-label'] };
	if (options['categories-add'])
	{
		p['add'] = options['categories-add-label'];
	}
	aMultipleSelect(form.find('#categories-section'), p);
	
	function toggleAllDay(checkbox) {
		$(checkbox).toggleClass('all_day_enabled');
		find('.start_time').toggleClass('time_disabled').toggle();
		find('.end_time').toggleClass('time_disabled').toggle();
	}
	
	find('.all_day input[type=checkbox]').bind('click', function() {
		toggleAllDay($(this));
	});
	
	if (find('.all_day input[type=checkbox]:checked').length)
	{
		toggleAllDay($(this));
	}
}

function aBlogGetPostStatus()
{
	var postStatus = $('#a_blog_item_status');
	return postStatus.val();
}

// Starting to assemble the aBlogConstructor -- eventually all of the JS functions above can be migrated into this space

function aBlogConstructor() 
{	
	this.sidebarEnhancements = function(options)
	{
		var debug = options['debug'];
		
		debug ? apostrophe.log('aBlog.sidebarEnhancements -- debug') : '';
		
		$('.a-tag-sidebar-title.all-tags').click(function(){
			$('.a-tag-sidebar-list.all-tags').slideToggle();
			$(this).toggleClass('open');
		});

		$('.a-tag-sidebar-title.all-tags').hover(function(){
			$(this).toggleClass('over');
		},
		function(){
			$(this).toggleClass('over');		
		});	
	}
	
	this.slotEditView = function(options)
	{
		var formName = options['formName'];
		var autocompleteUrl = options['autocompleteUrl'];
		var className = options['class'];
		var selfLabelSelector = options['selfLabelSelector'];
		
		var debug = (options['debug']) ? options['debug'] : false;
		
		(debug) ? apostrophe.log('aBlog.slotEditView -- formName: ' + formName) : '';
		(debug) ? apostrophe.log('aBlog.slotEditView -- autocompleteUrl: ' + autocompleteUrl) : '';
		(debug) ? apostrophe.log('aBlog.slotEditView -- class: ' + className) : '';
				
    aMultipleSelect('#a-' + formName + ' .' + className, { 'autocomplete': autocompleteUrl });
    aMultipleSelect('#a-' + formName + ' .categories', { 'choose-one': 'Add Categories' });
		
		var slotEditForm = $('#a-'+formName)
		var editStates = slotEditForm.find('.a-form-row.by-type input[type="radio"]');
		var editState = slotEditForm.find('.a-form-row.by-type input[type="radio"]:checked').val();
		slotEditForm.addClass('a-options dropshadow editState-' + editState );
			editStates.live('click', function(){
			 	editState = slotEditForm.find('.a-form-row.by-type input[type="radio"]:checked').val();
				slotEditForm.removeClass('editState-title').removeClass('editState-tags').addClass('editState-'+editState);
		});
	}
	
}

window.aBlog = new aBlogConstructor();