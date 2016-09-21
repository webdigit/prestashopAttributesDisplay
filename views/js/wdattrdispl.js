$(function(){
	var combinaisons = {
			init: function(){
				//console.log('combinaisons init');
				this.boxSelector = wdAttrDisplSelectors;
				this.boxSelectorType = wdAttrDisplType;
				this.boxSelectorArray = this.boxSelector.split(',');
				this.position = 100;
				this.cacheDom();
				this.displayElements();
				this.bindEvents();
			},
			cacheDom: function(){
				//console.log('combinaisons cacheDom');
				//this.$wdpsadmin = $('.wdpsadmin');
				this.$wbattrdisplay = $('.wdattrdisplay');
				this.$boxSelector = $(this.boxSelector);
				this.$boxSelectorType = $(this.boxSelectorType);
			},
			displayElements : function(){
				//console.log('combinaisons displayElements');
				$.each(this.boxSelectorArray,$.proxy(function(index,boxSelector){
					//console.log($(boxSelector).find('.wdpsadmin').length);
					//$.each($(boxSelector).find('.wdpsadmin'),$.proxy(function(i,e){
					$.each($(boxSelector).find('.wdattrdisplay'),$.proxy(function(i,e){
						var $element = $(e);
						if($element.data('positioned') == undefined){
							var $element = $(e);
							var box_position = $(boxSelector).offset();
							var box_height = $(boxSelector).outerHeight();
							$element.closest(boxSelector).append($element.detach());
							//$element.outerWidth($('.product-container').width());
							var position_element = (box_height/100)*this.position;
							//console.log(position_element);
							//$element.css('top',position_element+'px !important');
							$element.attr('style', function(i,s) { return s + 'top: '+position_element+'px !important;' });
							//console.log($('.product-container').width());
							$element.data('positioned','1');
						}
					},this));
				},this));
			},
			bindEvents: function(){
				//console.log('combinaisons bindEvents');
				this.$boxSelector.bind('mouseover',$.proxy(function(e){
					$element = $(e.currentTarget);
					//console.log($element);
					var elementPositionTop = $element.position().top;
					//console.log($element.position().top);
					//console.log('combinaisons bindEvents mouseover');
					//console.log($('.product-container').width());
					//$('.wdpsadmin').hide();
					$('.wdattrdisplay').hide();
					var box_position = $element.offset();
					//console.log(box_position);
					var box_height = $element.outerHeight();
					//console.log(box_height);
					var position_element = elementPositionTop+((box_height/100)*this.position)-10;
					//$element.closest(this.boxSelector).find('.wdpsadmin').outerWidth(this.$boxSelector.width()).attr('style', function(i,s) { return s + 'top: '+position_element+'px !important;' }).show();
					$element.closest(this.boxSelector).find('.wdattrdisplay').outerWidth(this.$boxSelector.width()).attr('style', function(i,s) { return s + 'top: '+position_element+'px !important;' }).show();	
				},this)).bind('mouseout',function(e){
					if(e.relatedTarget.nodeName == 'LI'){
						//$('.wdpsadmin').hide();
						$('.wdattrdisplay').hide();
					}
				});
			}
	}
	//combinaisons.init();
	
	// Comportement pour Hover ou Static
	if ( wdAttrDisplType == 'hover' ){
		window.setInterval(function(){combinaisons.init()}, 500);
	}
	else {
		//$('.wdpsadmin').removeAttr('style');
		$('.wdattrdisplay').removeAttr('style');
	}
	
});