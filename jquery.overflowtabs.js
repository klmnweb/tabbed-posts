/*
	*jQuery OverflowTabs.js 
	*https://github.com/paul-blundell/OverflowTabs
	*Released under the MIT license
	*Credit to Paul Blundell
*/

(function ( $ ) {
$.fn.realDimensions = function(container) {
	var node = this.css('visibility', 'hidden').appendTo(container ? container : 'body');
	var result = {
        "width"  : node.outerWidth(true), 
        "height" : node.outerHeight(true) 
    };
	node.remove();
	return result;
}
$.widget("ui.tabs", $.ui.tabs, {
	options: {
		overflowTabs: false,
		extraContainerPadding: 0
	},

	_OVERFLOW_TABS_HTML     : '<ul class="ui-tabs-overflow" style="display: none"></ul>',
	_OVERFLOW_SELECTOR_HTML : '<div class="ui-tabs-overflow-selector">&#xFE19;<span class="total">0</span></div>',

	_create: function() {
		this._super('_create');

		if (!this.options.overflowTabs) {
			return;
		}

		this.options.extraContainerPadding += $(this._OVERFLOW_SELECTOR_HTML).realDimensions(this.element).width;

		// update the tabs
		this.updateOverflowTabs();

		// Add a slight delay after resize, to fix Maximise issue.
		var that = this;

		// Resize events are not issued for tab component, see https://github.com/cowboy/jquery-resize/issues/7
		$(window).resize(function() {
			that.updateOverflowTabs();
		});

		// On overflow click show hidden tabs:
		$(this.element).on('click', '.ui-tabs-overflow-selector', function() {
			$('.ui-tabs-overflow', that.element).toggle();
		});
	},

	refresh: function() {
		this._super('refresh');

		if (this.options.overflowTabs) {
			this.updateOverflowTabs();
		}
	},

	updateOverflowTabs: function() {
		var someTabRemoved = false;
		var lastTabWidth = 0;
		var tabsWidth = 0;

		$('.ui-tabs-nav > li', this.element).each(function(){
			tabsWidth += $(this).outerWidth(true);
		});

		var containerWidth = $(this.element).width() - this.options.extraContainerPadding;

		// Loop until we can hide some tab:
		while (tabsWidth > containerWidth && (lastTabWidth = this._hideLastTab())) {
			someTabRemoved = true;
			tabsWidth -= lastTabWidth;
		}

		// If we have removed some tab, there is no need to try to get it back:
		if (!someTabRemoved) {
			// Get the first tab in the overflow list
			var nextTab = this._nextHiddenTab();

			// Loop until we cannot fit any more tabs
			while (nextTab && (tabsWidth += nextTab.clone().realDimensions(this.element).width) < containerWidth) {
				// Remove given tab from .ui-tabs-overflow element and append back to tab list:
				nextTab.appendTo($('.ui-tabs-nav', this.element));

				nextTab = this._nextHiddenTab();
			}
		}

		var numberOfHiddenTabs = $('.ui-tabs-overflow li', this.element).length;

		if (numberOfHiddenTabs) {
			$('.ui-tabs-overflow-selector .total', this.element).html(numberOfHiddenTabs);
		} else {
			// If overflow list is now empty then cleanup:
			$('.ui-tabs-overflow', this.element).remove();
			$('.ui-tabs-overflow-selector', this.element).remove();
		}
	},

	/**
	 * Remove last tab from the list and append to .ui-tabs-overflow element.
	 */
	_hideLastTab: function() {
		if (!$('.ui-tabs-overflow', this.element).length) {
			$('.ui-tabs-nav', this.element).after(this._OVERFLOW_TABS_HTML + this._OVERFLOW_SELECTOR_HTML);
			this.maxHiddedTabWidth = 0; // when tab is hidden, it's width is saved
		}

		var lastTab = $('.ui-tabs-nav li', this.element).last();

		if (lastTab.length === 0) {
			return 0;
		}

		var tabWidth = lastTab.outerWidth(true);

		if (tabWidth > this.maxHiddedTabWidth) {
			this.maxHiddedTabWidth = tabWidth;
			// Add extra space in case the ":hover" styles makes tab wider (e.g. bold text, border, padding, etc):
			$('.ui-tabs-overflow', this.element).width(Math.round(this.maxHiddedTabWidth * 1.1));
		}

		lastTab.prependTo($('.ui-tabs-overflow', this.element));

		return tabWidth;
	},

	/**
	 * Return first tab which is hidden in .ui-tabs-overflow element.
	 */
	_nextHiddenTab: function() {
		var firstHiddenTab = $('.ui-tabs-overflow li', this.element).first();

		if (firstHiddenTab.length === 0) {
			return null;
		}

		return firstHiddenTab;
	},
});

$(function() {
    var tabControl, closeAllTabsButton;
    
    tabControl = $(".tab-area").tabs({
        overflowTabs: true,
        extraContainerPadding: 30,
		activate: function(event, ui) { // activate event handler
			if (tabControl.find('li .close-tab-button').length > 1) {
				closeAllTabsButton.show();
			} else {
				closeAllTabsButton.hide();
			}
		}
	}).on('click', 'li .close-tab-button', function() {
		 var panelId = $(this).closest('li').remove().attr('aria-controls');
		 $('#' + panelId).remove();
		 tabControl.tabs('refresh');
	});

	closeAllTabsButton = tabControl.find('.close-all-tabs-button').click(function() {
		tabControl.find('li .close-tab-button').each(function() {
			$(this).click();
		})

		$(this).hide();
	});
});
}( jQuery ));