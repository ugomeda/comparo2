{include file="teams/header_teams.tpl"}
      <div id="content">
{if $team.teamid == false}{assign var="menu" value="createteam"}{else}{assign var="menu" value="myteams"}{/if}
        <div class="team-content teams">
{include file="teams/menuteams.tpl"}
{if $team.teamid == false}
          <h1>Créer une team</h1>
{else}
          <h1>&Eacute;diter une team</h1>
{/if}
{include file="teams/show_infos.tpl"}
          <form action="edit_team.php" method="post">
          <h3>Informations générales</h3>
          <div class="simpleForm">
            <label for="teamName">Nom de la team :</label>
            <input type="text" name="teamName" id="teamName" value="{$team.teamName}" />
          </div>
          
          <h3>&Eacute;tapes du sous-titrage</h3>
          <p>
            Laissez les champs vides pour les étapes inutiles
          </p>
          <div class="stepForm">
            <label for="stepName1">&Eacute;tape 1 :</label>
            <input type="text" name="stepName[]" id="stepName1" value="{$team.stepName.0}" class="name" />
            <input type="text" name="stepColor[]" id="stepColor1" value="{$team.stepColor.0}" class="color" />
          </div>
          <div class="stepForm">
            <label for="stepName2">&Eacute;tape 2 :</label>
            <input type="text" name="stepName[]" id="stepName2" value="{$team.stepName.1}" class="name" />
            <input type="text" name="stepColor[]" id="stepColor2" value="{$team.stepColor.1}" class="color" />
          </div>
          <div class="stepForm">
            <label for="stepName3">&Eacute;tape 3 :</label>
            <input type="text" name="stepName[]" id="stepName3" value="{$team.stepName.2}" class="name" />
            <input type="text" name="stepColor[]" id="stepColor3" value="{$team.stepColor.2}" class="color" />
          </div>
          <div class="stepForm">
            <label for="stepName4">&Eacute;tape 4 :</label>
            <input type="text" name="stepName[]" id="stepName4" value="{$team.stepName.3}" class="name" />
            <input type="text" name="stepColor[]" id="stepColor4" value="{$team.stepColor.3}" class="color" />
          </div>
          <div class="stepForm">
            <label for="stepName5">&Eacute;tape 5 :</label>
            <input type="text" name="stepName[]" id="stepName5" value="{$team.stepName.4}" class="name" />
            <input type="text" name="stepColor[]" id="stepColor5" value="{$team.stepColor.4}" class="color" />
          </div>
          <script type="text/javascript">
{literal}
          <!--
            $(document).ready(function() {
              $('.color').each(function() {
                var element = $(this);
                element.ColorPicker({
                  color: element.val(),
                  onShow: function (colpkr) {
                    $(colpkr).fadeIn(500);
                    return false;
                  },
                  onHide: function (colpkr) {
                    $(colpkr).fadeOut(500);
                    return false;
                  },
                  onChange: function (hsb, hex, rgb) {
                    element.css('backgroundColor', '#' + hex).css('color', '#' + hex).val('#' + hex);
                  }
                });
                $(this).css('backgroundColor', $(this).val()).css('color', $(this).val()).css('width', '20px').css('cursor', 'pointer').css('textIndent', '-999px');
              });              
            });
            -->
{/literal}
          </script>
          <p><br /></p>
{if $team.teamid == false}
          <input type="submit" id="sendbutton" value="Créer cette team" name="createTeam" />
{else}
          <input type="submit" id="sendbutton" value="&Eacute;diter cette team" name="editTeam" />
          <input type="hidden" value="{$team.teamid}" name="teamid" />
{/if}
          </form>
        </div>
        <span class="clear"></span>
      </div>
{include file="footer.tpl"}