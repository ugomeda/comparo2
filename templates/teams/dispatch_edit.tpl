{include file="teams/header_teams.tpl"}
      <div id="content">
{assign var="menu" value="dispatch"}
        <div class="team-content dashboard">
{include file="teams/menusubtitle.tpl"}
{if $sub.steps.$step}
          <h1>Dispatch de l'étape &#147;{$sub.steps.$step}&#148;</h1>
{else}
          <h1>Dispatch de l'étape</h1>
{/if}
{include file="teams/show_infos.tpl"}

<form action="dispatch_edit.php" method="post">
<p><br />
<strong>Dispatch par :</strong>
<label for="dispatch_lines"><input type="radio" name="mode" value="line" id="dispatch_lines" checked="checked" /> Pourcentage de lignes</label>&nbsp;&nbsp;&nbsp;
<label for="dispatch_timing"><input type="radio" name="mode" value="line" id="dispatch_timing" /> Timing</label>
</p>
<div class="commandbox">
  <p class="left">
    <input type="button" id="equivalent" name="equivalent" value="Répartir les parties automatiquement" class="button" />
  </p>
  <p class="right">
    Ajouter un utilisateur :
    <select name="user" id="user">
      <option value="">Ajouter...</option>
{foreach from=$users item=user}
      <option value="{$user.0}">{$user.1}</option>
{/foreach}
    </select>
  </p>
</div>
<div id="jobs"></div>
<div id="alert_dispatch" class="alert_discret" style="visibility:hidden"></div>
<p>
<input type="hidden" name="step" value="{$step}" />
<input type="hidden" name="sid" value="{$sub.subid}" />
<input type="hidden" name="mode" id="mode" value="-1" />
<input type="submit" id="sendbutton" value="Valider" name="set_dispatch" disabled="disabled" />
</p>
</form>

</div></div>
{include file="footer.tpl"}