{include file="teams/header_teams.tpl"}
      <div id="content">
{if $subtitle.subid == false}{assign var="menu" value="createsub"}{else}{assign var="menu" value="myteams"}{/if}
        <div class="team-content teams">
{include file="teams/menuteams.tpl"}
{if $subtitle.subid == false}
          <h1>Ajouter un sous-titre</h1>
{else}
          <h1>&Eacute;diter un sous-titre</h1>
{/if}
{include file="teams/show_infos.tpl"}
          <form action="edit_sub.php" method="post">
          <h3>Informations sur l'épisode</h3>
          <div class="simpleForm">
            <label for="episodename">Nom de l'épisode :</label>
            <input type="text" name="episodename" id="episodename" value="{$subtitle.episodename}" />
          </div>
          <div class="smallForm">
            <label for="season">Saison :</label>
            <input type="text" name="season" id="season" value="{$subtitle.season}" />
          </div>
          <div class="smallForm">
            <label for="episode">Numéro d'épisode :</label>
            <input type="text" name="episode" id="episode" value="{$subtitle.episode}" />
          </div>
          <div class="smallForm">
            <label for="timing" class="facultative">Timing approximatif :</label>
            <input type="text" name="timing" id="timing" value="{if $subtitle.timing > 0}{$subtitle.timing}{/if}" /> minutes
          </div>
          {if $subtitle.subid != false}<div class="alert_discret">Vérifiez le dispatch après avoir modifié le timing</div>{/if}
          <h3>Information sur le sous-titre</h3>
          <div class="selectForm">
            <label for="teamid">Team :</label>
            {html_options name=teamid id=teamid options=$teams selected=$subtitle.teamid}
          </div>
          <div class="selectForm">
            <label for="status">Status :</label>
            <input type="radio" name="status" value="1"{if $subtitle.status == "1"} checked="checked"{/if} /> Actif&nbsp;&nbsp;&nbsp;&nbsp;
            <input type="radio" name="status" value="0"{if $subtitle.status != "1"} checked="checked"{/if} /> Inactif
          </div>
          <p><br /></p>
{if $subtitle.subid == false}
          <input type="submit" id="sendbutton" value="Ajouter ce sous-titre" name="createSub" />
{else}
          <input type="submit" id="sendbutton" value="&Eacute;diter ce sous-titre" name="editSub" />
          <input type="hidden" value="{$subtitle.subid}" name="subid" />
{/if}
          </form>
        </div>
        <span class="clear"></span>
      </div>
{include file="footer.tpl"}