$(function(){
	if ( wdAttrDisplRender == 'desactive'){
		//console.log( wdAttrDisplRender );
		$('.wdattrdisplay').css('visibility','hidden');
	}
	else {
		//console.log( wdAttrDisplRender );
		$('.wdattrdisplay').css('visibility','visible');
	}
	
	var combinaisons = {
			init: function(){
				//console.log('combinaisons init');
				this.box = wdAttrDisplRender;
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
				this.$box = $(this.box);
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
					var box_width = $element.outerWidth();
					//console.log(box_width);
					var box_height = $element.outerHeight();
					//console.log(box_height);
					
					// GESTION DES POSITIONS DU CONTAINER
					
					switch (this.boxSelectorPosition){
					case 'top':
						var position_element = elementPositionTop-((box_height/1000)*this.position)+20;
						$element.closest(this.boxSelector).find('.wdattrdisplay').outerWidth(this.$boxSelector.width()).attr('style', function(i,s) { return s + 'top: '+position_element+'px !important;' }).show();
						//console.log( 'POSITION TOP' );
						break;
					case 'bottom':
						var position_element = elementPositionTop+((box_height/100)*this.position)-10;
						$element.closest(this.boxSelector).find('.wdattrdisplay').outerWidth(this.$boxSelector.width()).attr('style', function(i,s) { return s + 'top: '+position_element+'px !important;' }).show();
						//console.log( 'POSITION BOTTOM' );
						break;
					case 'top_right':
						var position_element = elementPositionTop+((box_height/1000)*this.position)-40;
						//$element.closest(this.boxSelector).find('.wdattrdisplay').offset({left : $element.outerWidth(this.$boxSelector.width())}).show();
						$element.closest(this.boxSelector).find('.wdattrdisplay').outerWidth(this.$boxSelector.width()).attr('style', function(i,s) { return s + 'top: '+position_element+'px !important; left: '+box_width+'px;' }).show();
						//console.log( 'POSITION TOP RIGHT' );
						break;
					case 'top_left':
						var position_element = elementPositionTop-((box_height/1000)*this.position)+40;
						$element.closest(this.boxSelector).find('.wdattrdisplay').outerWidth(this.$boxSelector.width()).attr('style', function(i,s) { return s + 'top: '+position_element+'px !important; left: -'+box_width+'px;' }).show();
						//console.log('POSITION TOP LEFT');
						break;
					case 'bottom_right':
						var position_element = elementPositionTop+((box_height/100)*this.position)-10;
						$element.closest(this.boxSelector).find('.wdattrdisplay').outerWidth(this.$boxSelector.width()).attr('style', function(i,s) { return s + 'top: '+position_element+'px !important; left: '+box_width+'px;' }).show();
						//console.log('POSITION BOTTOM RIGHT');
						break;
					case 'bottom_left':
						//console.log('POSITION BOTTOM LEFT')
						var position_element = elementPositionTop+((box_height/100)*this.position)-10;
						$element.closest(this.boxSelector).find('.wdattrdisplay').outerWidth(this.$boxSelector.width()).attr('style', function(i,s) { return s + 'top: '+position_element+'px !important; left: -'+box_width+'px;' }).show();
						break;
						default:break;
					}
					//BOTTOM
					//var position_element = elementPositionTop+((box_height/100)*this.position)-10;
					
					//$element.closest(this.boxSelector).find('.wdattrdisplay').outerWidth(this.$boxSelector.width()).attr('style', function(i,s) { return s + 'top: '+position_element+'px !important;' }).show();
					
					//$element.closest(this.boxSelector).find('.wdattrdisplay').show();
				},this)).bind('mouseleave',function(e){
					if(e.relatedTarget.nodeName == 'LI'){
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
	
	// Affichage des déclinaisons sur les pages
	if (wdAttrDisplPageIndex == 1) {
		console.log('index');
		if (wdAttrDisplPageProduct == 1){
			console.log('product');
		}
		if (wdAttrDisplPageCategory == 1){
			console.log('category');
		}
	}
	
	console.log(wdAttrDisplPageIndex);
	console.log(wdAttrDisplPageProduct);
	console.log(wdAttrDisplPageCategory);
	
});