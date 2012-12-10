{include file="teams/header_teams.tpl"}
      <div id="content">
      
{assign var="menu" value="forum"}
        <div class="team-content dashboard">
{include file="teams/menusubtitle.tpl"}
          <h1>Forum</h1>
{include file="teams/show_infos.tpl"}
          <ul id="posts">
{foreach from=$msgs item=post}
<li id="p-{$post.postid}">
  <span class="user">
    <img src="../img/unknown_user.jpg" width="100" height="100" alt="" />
    {$post.username}
  </span>
  <span class="content">
    <span class="date">{$post.date}</span>
    {$post.html}
  </span>
  <span class="clear"></span>
</li>            
{/foreach}
          </ul>

{if ! count($msgs)}
          <p class="centermsg" id="nomsg">
            Aucun message pour le moment
          </p>
{/if}
          <h3>Ajouter un message</h3>
          <script type="text/javascript" >
             {literal}$(document).ready(function() {
                $("#newmsg").markItUp(mySettings);
             });{/literal}
          </script>
          <form action="forum.php" method="post">
            <p>
              <textarea id="newmsg" rows="10" cols="10" name="message"></textarea>
              <input type="submit" value="Envoyer" name="send" class="tablebutton" />
              <input type="hidden" name="sid" value="{$sub.subid}" />
            </p>
          </form>
          </div>
        </div>
        <span class="clear"></span>
      </div>
{include file="footer.tpl"}