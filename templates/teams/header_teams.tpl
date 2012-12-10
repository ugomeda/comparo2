{assign var='path' value='../'}
{include file="header.tpl"}
      <div id="header">
        <div id="logo">
          <a class="logo" href="index.php">Comparo Teams<span>2 <span>(beta)</span></span></a>
        </div>
        <div id="navigation">
          <ul>
            <li class="left rub"><a href="index.php"><img src="../img/home_24.png" width="24" height="24" alt="Accueil" /></a></li>
{if $sub}            <li class="left rub"><a href="subtitles.php?tid={$sub.teamid}">{$sub.name}</a></li>
            <li class="left rub"><a href="subtitle.php?sid={$sub.subid}">{$sub.saison}&times;{$sub.episode} &#147;{$sub.episodename}&#148;</a></li>{/if}
          </ul>
        </div>
      </div>