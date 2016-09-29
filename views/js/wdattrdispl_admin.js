$(function(){
	
	$('#wd_attr_displ_type').bind('change',function(e){
		var value = $(this).val();
		switch (value){
		case 'static':
			$('#wd_attr_displ_position').closest('.form-group').css('visibility','hidden');
			//$('#wd_attr_displ_position').closest('.form-group').hide();
			break;
		case 'hover' :
			$('#wd_attr_displ_position').closest('.form-group').css('visibility','visible');
			//$('#wd_attr_displ_position').closest('.form-group').show();
			break;
			default:break;
		}
	});
	
	$('#wd_attr_displ_format').bind('change', function(e){
		var value = $(this).val();
		switch (value){
		case 'attribut' :
			$('#wd_attr_displ_outstock').closest('.form-group').css('visibility', 'hidden');
			break;
		case 'combinaison' :
			$('#wd_attr_displ_outstock').closest('.form-group').css('visibility', 'visible');
			break;
			default:break;
		}
	});
	
	/*$('#wd_attr_displ_page').bind('change', function(e){
		var value = $(this).val();
		switch (value){
		case 'active' :
			$('.checkbox').closest('.form-group').css('visibility','visible');
			break;
		case 'desactive' :
			$('.checkbox').closest('.form-group').css('visibility','hidden');
			break;
			default:break;
		}
	});*/

})
