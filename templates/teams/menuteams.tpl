        <div id="menus">
          <ul class="menu teams">
            <li class="title">Teams</li>
            <li class="item{if $menu == "now"} iactive{/if}"><a href="index.php">Sous-titres en cours</a></li>
            <li class="item{if $menu == "myteams"} iactive{/if}"><a href="myteams.php">Gérer les teams</a></li>
            <li class="item{if $menu == "invits"} iactive{/if}"><a href="invits.php">Invitations ({$user.invits})</a></li>
            <li class="item{if $menu == "createteam"} iactive{/if}"><a href="edit_team.php">Créer une team</a></li>
            <li class="item{if $menu == "createsub"} iactive{/if}"><a href="edit_sub.php">Créer un sous-titre</a></li>
          </ul>
          <ul class="menu account">
            <li class="title">Mon compte</li>
            <li class="item">Mot de passe</li>
            <li class="item">Adresse mail</li>
          </ul>
        </div>