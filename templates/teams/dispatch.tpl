{include file="teams/header_teams.tpl"}
      <div id="content">
{assign var="menu" value="dispatch"}
        <div class="team-content dashboard">
{include file="teams/menusubtitle.tpl"}
          <h1>Dispatch</h1>
{include file="teams/show_infos.tpl"}

{foreach from=$sub.steps key=index item=step}
{if $step || count($jobs.$index)}
          <h3><a href="dispatch_edit.php?sid={$sub.subid}&step={$index}"><img src="../img/pencil.png" width="16" height="16" alt="Editer" /></a> {$step}</h3>
{if count($jobs.$index)}
  <ul class="dispatch">
{foreach from=$jobs.$index item=job}
{if $job.mode == 0}
    <li>{$job.username} : {$job.from}% à {$job.to}%</li>
{else}
    <li>{$job.username} : {if $job.from >= $sub.timing}fin{else}{$job.from}:00{/if} à {if $job.to >= $sub.timing}la fin{else}{$job.to}:00{/if}</li>
{/if}
{/foreach}  
  </ul>
{else}
<p class="smallcentermsg">
  Aucune partie attribuée

</p>
{/if}
          
          
{/if}
{/foreach}


        </div>
        <span class="clear"></span>
      </div>
{include file="footer.tpl"}