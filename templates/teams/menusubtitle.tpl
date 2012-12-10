        <div id="menus">
          <ul class="menu dashboard">
            <li class="title">Tableau de bord</li>
            <li class="item{if $menu == "dashboard"} iactive{/if}"><a href="subtitle.php?sid={$sub.subid}">Tableau de bord</a></li>
          </ul>
          <ul class="menu orga">
            <li class="title">Sous-titrage</li>
            <li class="item{if $menu == "dispatch"} iactive{/if}"><a href="dispatch.php?sid={$sub.subid}">Dispatch</a></li>
            <li class="item{if $menu == "files"} iactive{/if}"><a href="files.php?sid={$sub.subid}">Fichiers</a></li>
            <li class="item{if $menu == "forum"} iactive{/if}"><a href="forum.php?sid={$sub.subid}">Forum</a></li>
          </ul>
        </div>