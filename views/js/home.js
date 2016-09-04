$(function(){
	var combinaisons = {
			init: function(){
				this.cacheDom();
				this.moveElements();
				this.bindEvents();
			},
			cacheDom: function(){
				this.$wdpsadmin = $('.wdpsadmin');
			},
			moveElements : function(){
				$.each(this.$wdpsadmin,$.proxy(function(i,e){
					var $element = $(e);
					var image_first = $element.closest('li').find('img:first');
					var image_position = image_first.offset();
					var image_height = image_first.outerHeight();
					$element.closest('li').append($element.detach());
					//$element.outerWidth($('.product-container').width());
					var position = (image_height/100)*0;
					$element.css('top',position+'px');
					//console.log($('.product-container').width());
				},this));
			},
			bindEvents: function(){
				$('.product-container').bind('mouseover',function(){
					//console.log($('.product-container').width());
					$('.wdpsadmin').hide();
					$(this).closest('li').find('.wdpsadmin').outerWidth($('.product-container').width()).show();
				}).bind('mouseout',function(e){
					if(e.relatedTarget.nodeName == 'LI'){
						$('.wdpsadmin').hide();
					}
				});
			}
	}
	combinaisons.init();
});