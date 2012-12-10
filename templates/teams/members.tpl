{include file="teams/header_teams.tpl"}
      <div id="content">
{assign var="menu" value="myteams"}
        <div class="team-content teams">
{include file="teams/menuteams.tpl"}
{if $team}
          <h1>Membres de &#147;{$team.name|truncate:40:"..."}&#148;</h1>
{else}
          <h1>Membres de la team</h1>          
{/if}
{include file="teams/show_infos.tpl"}
{if $isadmin}          <form action="members.php" method="post">
            <p>
              <label for="invit">Inviter un membre : </label> <input type="text" name="invit" id="invit" />
              <input type="submit" name="action-invit" value="Inviter" />
              <input type="hidden" name="tid" value="{$team.teamid}" />
            </p>
          </form>{/if}
          <form action="members.php" method="post">
          <table class="normal">
            <thead>
              <tr>
                {if $isadmin}<td class="check"><input type="checkbox" id="checkall" name="checkall" value="" /></td>{/if}
                <td class="major">Utilisateur</td>
                <td>Rôle</td>
              </tr>
            </thead>
            <tbody>
{foreach from=$members item=member}
              <tr>
                {if $isadmin}<td class="check">{if $member.uid}<input type="checkbox" name="member[]" value="{$member.uid}" />{/if}</td>{/if}
                <td class="major">
                  <span class="big">{$member.name|truncate:60:"..."}</span><br />
                </td>
                <td>{if $member.role == 2}Administrateur{elseif $member.role == 1}Sous-titreur{else}Invitation envoyée{/if}</td>
              </tr>
{/foreach}
            </tbody>
          </table>
          {if $isadmin}<p>
            <input type="hidden" name="tid" value="{$team.teamid}" />
            <input type="submit" value="Retirer de la team" name="delete" class="tablebutton" />
          </p>{/if}
          </form>
        </div>
        <span class="clear"></span>
      </div>
{include file="footer.tpl"}