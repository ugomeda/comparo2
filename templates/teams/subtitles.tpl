{include file="teams/header_teams.tpl"}
      <div id="content">
{assign var="menu" value="myteams"}
        <div class="team-content teams">
{include file="teams/menuteams.tpl"}
{if $team}
          <h1>Sous-titres de &#147;{$team.name|truncate:40:"..."}&#148;</h1>
{else}
          <h1>Sous-titres de la team</h1>
{/if}
{include file="teams/show_infos.tpl"}
{if count($subtitles)}
          <table class="normal">
            <thead>
              <tr>
                <td class="major">&Eacute;pisode</td>
                <td>Créé</td>
                <td>Status</td>
              </tr>
            </thead>
            <tbody>
{foreach from=$subtitles item=sub}
              <tr>
                <td class="major">
                  <img src="../img/bullet_key.png" width="10" height="10" alt="" title="Vous êtes administrateur"/>
                  <a href="subtitle.php?sid={$sub.subid}" class="big">{$sub.saison}&times;{$sub.episode} &#147;{$sub.episodename|truncate:30:"..."}&#148;</a><br />
                  <span class="actions">
                    <a href="subtitle.php?sid={$sub.subid}" class="petit">Afficher</a> |
                    <a href="edit_sub.php?subid={$sub.subid}" class="petit">&Eacute;diter</a></span>
                </td>
                <td>{$sub.created}</td>
                <td>{if $sub.status == 1}En cours{else}Inactif{/if}</td>
              </tr>
{/foreach}
            </tbody>
          </table>
{else}
          <p class="centermsg">
            Aucun sous-titre
          </p>
{/if}

        </div>
        <span class="clear"></span>
      </div>
{include file="footer.tpl"}