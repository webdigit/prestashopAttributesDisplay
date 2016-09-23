$(function(){
	var combinaisons = {
			init: function(){
				//console.log('combinaisons init');
				this.boxSelector = wdAttrDisplSelectors;
				this.boxSelectorType = wdAttrDisplType;
				this.boxSelectorPosition = wdAttrDisplPosition;
				this.boxSelectorArray = this.boxSelector.split(',');
				this.position = 100;
				this.cacheDom();
				this.displayElements();
				this.bindEvents();
			},
			cacheDom: function(){
				//console.log('combinaisons cacheDom');
				this.$wbattrdisplay = $('.wdattrdisplay');
				this.$boxSelector = $(this.boxSelector);
				//console.log($(this.boxSelector));
				this.$boxSelectorType = $(this.boxSelectorType);
				//console.log($(this.boxSelectorType));
				this.$boxSelectorPosition = $(this.boxSelectorPosition);
				//console.log($(this.boxSelectorPosition));
			},
			displayElements : function(){
				//console.log('combinaisons displayElements');

				$.each(this.boxSelectorArray,$.proxy(function(index,boxSelector){
					//console.log($(boxSelector).find('.wdpsadmin').length);
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
				this.$boxSelector.bind('mouseenter',$.proxy(function(e){
					
					$element = $(e.currentTarget);
					
					var elementPositionTop = $element.position().top;
					//console.log($element.position().top);
					//console.log('combinaisons bindEvents mouseover');
					//console.log($('.product-container').width());
					$('.wdattrdisplay').hide();
					var box_position = $element.offset();
					//console.log(box_position);
					var box_height = $element.outerHeight();
					//console.log(box_height);
					
					// GESTION DES POSITIONS DU CONTAINER
					
					switch (this.boxSelectorPosition){
					case 'top':
						var position_element = elementPositionTop-((box_height/1000)*this.position)+20;
						$element.closest(this.boxSelector).find('.wdattrdisplay').outerWidth(this.$boxSelector.width()).attr('style', function(i,s) { return s + 'top: '+position_element+'px !important;' }).show();
						console.log( 'POSITION TOP' );
						break;
					case 'bottom':
						var position_element = elementPositionTop+((box_height/100)*this.position)-10;
						$element.closest(this.boxSelector).find('.wdattrdisplay').outerWidth(this.$boxSelector.width()).attr('style', function(i,s) { return s + 'top: '+position_element+'px !important;' }).show();
						console.log( 'POSITION BOTTOM' );
						break;
					case 'top_right':
						var position_element = elementPositionTop-((box_height/1000)*this.position)+20;
						console.log( 'POSITION TOP RIGHT' );
						break;
					case 'top_left':
						var position_element = elementPositionTop-((box_height/1000)*this.position)+20;
						$element.closest(this.boxSelector).find('.wdattrdisplay').outerWidth(this.$boxSelector.width()/2).attr('style', function(i,s) { return s + 'top: '+position_element+'px !important;' }).show();
						console.log('POSITION TOP LEFT');
						break;
					case 'bottom_right':
						console.log('POSITION BOTTOM RIGHT')
					case 'bottom_left':
						console.log('POSITION BOTTOM LEFT')
						var position_element = elementPositionTop+((box_height/100)*this.position)-10;
						$element.closest(this.boxSelector).find('.wdattrdisplay').outerWidth(this.$boxSelector.width()/2).attr('style', function(i,s) { return s + 'top: '+position_element+'px !important;' }).show();
						break;
						default:break;
					}
					//BOTTOM
					//var position_element = elementPositionTop+((box_height/100)*this.position)-10;
					
					//$element.closest(this.boxSelector).find('.wdattrdisplay').outerWidth(this.$boxSelector.width()).attr('style', function(i,s) { return s + 'top: '+position_element+'px !important;' }).show();
					
					//$element.closest(this.boxSelector).find('.wdattrdisplay').show();
				},this)).bind('mouseleave',function(e){
					if(e.relatedTarget.nodeName == 'li'){
						$('.wdattrdisplay').hide();
					}
				});	
			}		 	
	}
	
	//combinaisons.init();

	// GESTION DU TYPE D'AFFICHAGE DU CONTAINER
	
	if ( wdAttrDisplType == 'hover' ){
		window.setInterval(function(){combinaisons.init()}, 500);
	}
	else {
		$('.wdattrdisplay').removeAttr('style');
	}
	
});