{include file="teams/header_teams.tpl"}
      <div id="content">
{assign var="menu" value="now"}
        <div class="team-content teams">
{include file="teams/menuteams.tpl"}
          <h1>Sous-titres en cours</h1>
{include file="teams/show_infos.tpl"}
{if count($subtitles)}
          <table class="normal">
            <thead>
              <tr>
                <td class="major">&Eacute;pisode</td>
                <td>Team</td>
                <td>Créé</td>
              </tr>
            </thead>
            <tbody>
{foreach from=$subtitles item=sub}
              <tr>
                <td class="major">
                  {if $sub.admin}<img src="../img/bullet_key.png" width="10" height="10" alt="" title="Vous êtes administrateur"/>
                  {/if}<a href="subtitle.php?sid={$sub.subid}" class="big">{$sub.saison}&times;{$sub.episode} &#147;{$sub.episodename|truncate:30:"..."}&#148;</a><br />
                </td>
                <td><a href="subtitles.php?tid={$sub.teamid}" class="flat">{$sub.name|truncate:30:'...'}</a></td>
                <td>{$sub.created}</td>
              </tr>
{/foreach}
            </tbody>
          </table>
{else}
          <p class="centermsg">
            Vous n'avez aucun sous-titre en cours
          </p>
{/if}
        </div>
        <span class="clear"></span>
      </div>
{include file="footer.tpl"}