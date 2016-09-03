{$combinaisons.group_name_string} : 
{assign var="id_attribute_group" value=''}
{foreach from=$combinaisons.group_name_combinaisons key=k item=group_name_combinaison name=combinaisons}
	<a hre="#" itemprop="url">
	{foreach from=$group_name_combinaison item=comb}
		{if $id_attribute_group == $k}/{/if}
		{$comb}
		{assign var="id_attribute_group" value=$k}
	{/foreach}
	</a>{if $smarty.foreach.combinaisons.last}{else}-{/if}
{/foreach}


{*foreach from=$combinaisons.group_name_combinaisons key=k item=group_name_combinaison}
	{$group_name_combinaison}
{/foreach*}



{*foreach from=$combinaisons key=k item=comb}
	
	{$comb|@var_dump}
{/foreach*}