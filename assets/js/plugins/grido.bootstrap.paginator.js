/**
 * Grido paginator plugin.
 *
 * @author Petr Bugyík
 * @param {jQuery} $
 * @param {Window} window
 */
/*jshint esversion: 6, laxbreak: true, expr: true */
;
(function ($, window) {
	"use strict";

	window.Grido.Grid.prototype.onInit.push(function (Grido) {
		if (Grido.$element.hasClass('bootstrap') === false) { // template `bootstrap.latte` is required
			return;
		}

		var tmp;
		var selector = '.paginator input';

		Grido.$element
			.on('keyup', selector, function (e) {
				const code = e.keyCode || e.which;
				const $el = $(this);
				if (code === 13) {
					Grido.ajax.doRequest($el.data('grido-link').replace(0, $el.val()));
					return false;
				}

				const val = parseInt(this.value);
				const min = parseInt($el.attr('min'));
				const max = parseInt($el.attr('max'));
				if (isNaN(this.value) || val < min || max < val) {
					$el.val(this.value.length > 1
						? this.value.substr(0, this.value.length - 1)
						: $el.data('grido-current'));

					return false;
				}
			})
			.on('focus', selector, function () {
				const $el = $(this);
				$el
					.val($el.data('grido-current'))
					.select();
			})
			.on('blur', selector, function () {
				const $el = $(this);
				$el.val('');
				$el.attr('placeholder', tmp);
			});
	});

})(jQuery, window);
