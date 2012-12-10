{include file="teams/header_teams.tpl"}
      <div id="content">
{assign var="menu" value="myteams"}
        <div class="team-content teams">
{include file="teams/menuteams.tpl"}
          <h1>Gérer les teams</h1>
{include file="teams/show_infos.tpl"}
{if count($teams)}
          <table class="normal" id="myteams">
            <thead>
              <tr>
                <td class="major">Nom</td>
                <td>Sous-titres</td>
                <td>Dernier sous-titre</td>
              </tr>
            </thead>
            <tbody>
{foreach from=$teams item=team}
              <tr>
                <td class="major">
                  {if $team.admin}<img src="../img/bullet_key.png" width="10" height="10" alt="" title="Vous êtes administrateur"/>
                  {/if}<a href="subtitles.php?tid={$team.id}" class="big">{$team.name|truncate:40:"..."}</a><br />
                  <span class="actions">
                    {if $team.admin}<a href="edit_team.php?tid={$team.id}" class="petit">&Eacute;diter</a> |
                    {/if}<a href="subtitles.php?tid={$team.id}" class="petit">Sous-titres</a> |
                    <a href="members.php?tid={$team.id}" class="petit">Membres</a>{if $team.admin} |
                    <a href="edit_sub.php?tid={$team.id}" class="petit">Nouveau sous-titre</a></span>{/if}
                </td>
                <td><a href="subtitles.php?tid={$team.id}" class="flat">{if $team.count_all > 0}{$team.count_all} dont {$team.count_actif} en cours{else}Aucun{/if}</a></td>
                <td>{if $team.subid}<a href="#" class="flat"><strong>{$team.saison}&times;{$team.episode}</strong> &#147;{$team.episodename|truncate:20:"..."}&#148;</a><br />le {$team.created}{else}Aucun{/if}</td>
              </tr>
{/foreach}
            </tbody>
          </table>
{else}
          <p class="centermsg">
            Vous ne participer au aucune team
          </p>
{/if}
        </div>
        <span class="clear"></span>
      </div>
{include file="footer.tpl"}