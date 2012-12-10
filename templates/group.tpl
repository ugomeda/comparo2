{include file="header.tpl"}
      <div id="header">
        <div id="logo">
          <a class="logo" href="index.php">Comparo<span>2</span></a>
        </div>
        <div id="navigation">
        <ul>
          <li class="left linked"><a href="#add" id="menu-add">Ajouter un Comparo</a></li>
        </ul>
        </div>
      </div>
      <div id="content">
        <div id="add" class="popup">
          <form action="actiongroup.php" method="post">
            <h2>Ajouter un Comparo à ce groupe</h2>
            <p>
              <label for="comparoId">Adresse du Comparo : </label>
              <input type="text" id="comparoId" name="comparoId" />
              <input type="hidden" name="groupId" value="{$group.id}" />
              <input type="submit" id="addComparo" name="addComparo" value="Ajouter" />
            </p>
          </form>
        </div>
            
        <h1>Groupe &#147;{$group.name}&#148;</h1>
        <ul id="comparos">
{foreach from=$comparos item=comparo}
          <li>
            <a href="view-{$comparo.id}.html" class="files">{$comparo.originalName} &rarr; {$comparo.editedName}</a><br />
            <span class="progress-comparo" style="background-position: {$comparo.stats_total*1.3-130}px 0">{$comparo.stats_total} %</span>
            <span class="{if $comparo.hasVO}hasVO{else}noVO{/if}" title="{if $comparo.hasVO}Ce comparo a une version originale{else}Ce comparo n'a pas de version originale{/if}" >VO</span>
            <span class="{if $comparo.hasSC}hasSC{else}noSC{/if}" title="{if $comparo.hasVO}Ce comparo a un fichier .scenechange{else}Ce comparo n'a pas de fichier .scenechange{/if}" >SC</span>
            <a href="#" onclick="view_new('{$comparo.id}', {if $date}{$date}{else}false{/if})" class="unread">{$comparo.discuss} {if $comparo.discuss > 1}nouvelles discussions{else}nouvelle discussion{/if} et {$comparo.comment} {if $comparo.comment > 1}nouveaux commentaires{else}nouveau commentaire{/if}</a>
            <span class="clear"></span>
          </li>
{/foreach}
        </ul>
      </div>
{include file="footer.tpl"}