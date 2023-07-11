$(function() {
	$('.multiSelect').each(function(e) {
		var self = $(this);
		var field = self.find('.multiSelect_field');
		var fieldOption = field.find('option');
		var placeholder = field.attr('data-placeholder');

		field.hide().after(`< div class="multiSelect_dropdown" > < /div> < span class="multiSelect_placeholder" > ${placeholder}< /span> < ul class="multiSelect_list" > < /ul> < span class="multiSelect_arrow" > < /span>`);

		fieldOption.each(function(e) {
			$('.multiSelect_list').append(`<li class="multiSelect_option" data-value="` + $(this).val() + `"><a class="multiSelect_text">` + $(this).text() + `</a></li>`);
		});

		var dropdown = self.find('.multiSelect_dropdown');
		var list = self.find('.multiSelect_list');
		var option = self.find('.multiSelect_option');

		dropdown.attr('data-multiple', 'true');
		list.css('top', dropdown.height() + 5);

		option.on('click', function(e) {
			var self = $(this);
			e.stopPropagation();
			self.addClass('-selected');
			field.find('option:contains(' + self.children().text() + ')').prop('selected', true);
			dropdown.append(function(e) {
				return $('<span class="multiSelect_choice">' + self.children().text() + '<span class="multiSelect_deselect -iconX" >&times</span></span>').click(function(e) {
					var self = $(this);
					e.stopPropagation();
					self.remove();
					list.find('.multiSelect_option:contains(' + self.text() + ')').removeClass('-selected');
					list.css('top', dropdown.height() + 5).find('.multiSelect_noselections').remove();
					field.find('option:contains(' + self.text() + ')').prop('selected', false);
					if (dropdown.children(':visible').length === 0) {
						dropdown.removeClass('-hasValue');
					}
				});
			}).addClass('-hasValue');
			list.css('top', dropdown.height() + 5);
			if (!option.not('.-selected').length) {
				list.append('<h5 class="multiSelect_noselections">No Selections</h5>');
			}
		});

		dropdown.on('click', function(e) {
			e.stopPropagation();
			e.preventDefault();
			dropdown.toggleClass('-open');
			list.toggleClass('-open').scrollTop(0).css('top', dropdown.height() + 5);
		});

		$(document).on('click touch', function(e) {
			if (dropdown.hasClass('-open')) {
				dropdown.toggleClass('-open');
				list.removeClass('-open');
			}
		});
	});
});