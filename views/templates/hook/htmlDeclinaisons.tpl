{foreach from=$combinaisons.group_name key=k item=group_name}
	{$group_name}/
{/foreach}



{foreach from=$combinaisons key=k item=comb}
	
	{$comb|@var_dump}
{/foreach}