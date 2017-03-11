/**
 *  
 */
(function($) {
	$(document).ready(function() {

		var iconStyles = '';		
		window.initIconStyles = function(iSt) {
			iconStyles = iSt;
			onChange();

			$('#sortable').sortable({
				placeholder: 'ui-state-highlight',
				axis: 'y',
				cursor: 'move',
				create: sortEvent,
				update: sortEvent
			});

			$('#iconShape, #iconColor, #iconSize, #hoverIcon, #iconMargin').change(onChange);
		}
		
		var sortEvent = function(event, ui) {
			$('#sortOrder').val($(this).sortable('toArray').toString());
		};

		var onChange = function() {
			var im = parseInt($('#iconMargin').val());
			if (typeof im !== 'number' || (im % 1) !== 0) {
				$('#iconMarginError').show();
			}
			else {
				$('#iconMarginError').hide();
				var hovAtts = $('#hoverIcon').val() != 'none' ? ('background: ' + $('#hoverIcon').val()) : '';
				$('style#iconStyles').html(
					iconStyles
						.replace(/%iconMargin%/g,	im)
						.replace(/%iconSize%/g, 	$('#iconSize').val())
						.replace(/%hoverAttrs%/g,	hovAtts)
						.replace(/%borderRadius%/g,	$('#iconShape').val() == 'round' ? $('#iconSize').val() / 2: 0)
				);
	
				var iColor = $('#iconColor').val();
				$('#icon-preview-container .social-icon').each(function() {
					var name = $(this).parent().attr('id').substr(1);
					if (iColor == 'logo') {
						iClass = 'social-icon-' + name;
					}
					else if (iColor == 'inverse') {
						iClass = 'social-icon-' + name + '-inverse';
					}
					else {
						iClass = 'social-icon-' + iColor;
					}
					$(this).attr('class', 'social-icon ' + iClass);
				});
			}
		};
	});
} (window.jQuery));
