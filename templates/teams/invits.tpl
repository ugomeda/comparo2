{include file="teams/header_teams.tpl"}
      <div id="content">
{assign var="menu" value="invits"}
        <div class="team-content teams">
{include file="teams/menuteams.tpl"}
          <h1>Invitations</h1>
          <form action="invits.php" method="post">
          <p>
            Pour accepter ou refuser une invitation, cochez la ligne et cliquez sur le bouton approprié en dessous de la liste des invitations.
          </p>
{include file="teams/show_infos.tpl"}
{if count($invits)}
          <table class="normal">
            <thead>
              <tr>
                <td class="check"><input type="checkbox" id="checkall" name="checkall" value="" /></td>
                <td class="major">Team</td>
                <td>Administrateur</td>
              </tr>
            </thead>
            <tbody>
{foreach from=$invits item=invit}
              <tr>
                <td class="check"><input type="checkbox" name="invit[]" value="{$invit.teamid}" /></td>
                <td class="major">
                  <span class="big">{$invit.teamname|truncate:60:"..."}</span><br />
                </td>
                <td>{$invit.admin|truncate:30:"..."}</td>
              </tr>
{/foreach}
            </tbody>
          </table>
          <p>
            <input type="submit" value="Accepter" name="accept" class="tablebutton" /> <input type="submit" value="Refuser" name="refuse" class="tablebutton" />
          </p>
{else}
          <p class="centermsg">
            Vous n'avez aucune invitation
          </p>
{/if}
          </form>
        </div>
        <span class="clear"></span>
      </div>
{include file="footer.tpl"}