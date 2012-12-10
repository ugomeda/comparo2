{include file="header.tpl"}
      <div id="header">
        <div id="logo">
          <a class="logo" href="index.php">Comparo<span>2</span></a>
          <a href="index.php" id="new-comparo">Comparer d'autres sous-titres &raquo;</a>
        </div>
        <div id="navigation">
          <ul>
            <li class="left linked" id="menu-details"><span><a href="#details">Détails sur ce Comparo</a></span></li>
            <li class="right linked" id="menu-options"><span><a href="#options">+ d'options</a></span></li>
            <li class="right" id="quick-filter">
              {if $keep_ok}<img src="img/tick-n.png" id="quick-ok" alt="Ligne inchangées" title="Lignes inchangées" width="16" height="16" />{/if}
              <img src="img/pencil.png" id="quick-edit" alt="Edition texte" title="Edition texte" width="16" height="16" />
              <img src="img/time.png" id="quick-timing" alt="Changement timing" title="Changement timing" width="16" height="16" />
              <img src="img/add.png" id="quick-ins" alt="Ajout" title="Ajout" width="16" height="16" />
              <img src="img/cancel.png" id="quick-del" alt="Suppression" title="Suppression" width="16" height="16" />
              <img src="img/clock_edit.png" id="quick-unk" alt="Modification texte et timing" title="Modification texte et timing" width="16" height="16" />
            </li>
          </ul>
        </div>
      </div>
      <div id="content">
        <div id="details" class="popup">
          <div class="stats">
            <h2>Statistiques des modifications</h2>
            <p>
              <span class="progress" style="background-position: {$stats.timing*3-300}px 0"><label>Synchronisation :</label> {$stats.timing} %</span>
              <span class="progress" style="background-position: {$stats.text*3-300}px 0"><label>Texte :</label> {$stats.text} %</span>
              <span class="progress-total" style="background-position: {$stats.total*3-300}px 0"><label>Total :</label> {$stats.total} %</span>
            </p>
          </div>
          <div class="files">
            <h2>Fichiers comparés</h2>
{if $user->isLogged()}
            <p>
              <a href="file/{$comparo.idnonrelu}/{$comparo.nom_st1}" class="file">{$comparo.nom_st1}</a> (Non relu)<br />
              <a href="file/{$comparo.idrelu}/{$comparo.nom_st2}" class="file">{$comparo.nom_st2}</a> (Relu){if $comparo.idvo != ''}<br />
              <a href="file/{$comparo.idvo}/{$comparo.nom_vo}" class="file">{$comparo.nom_vo}</a> (Version originale){/if}{if $comparo.idsc != ''}<br />
              <a href="file/{$comparo.idsc}/{$comparo.nom_sc}" class="file">{$comparo.nom_sc}</a> (Fichier scenechange){/if}
            </p>
{else}
            <p>
              <span class="file">{$comparo.nom_st1}</span> (Non relu)<br />
              <span class="file">{$comparo.nom_st2}</span> (Relu){if $comparo.idvo != ''}<br />
              <span class="file">{$comparo.nom_vo}</span> (Version originale){/if}{if $comparo.idsc != ''}<br />
              <span class="file">{$comparo.nom_sc}</span> (Fichier scenechange){/if}
            </p>
{/if}
          </div>
          <span class="clear"></span>
        </div>
        <div id="options" class="popup">
          <div class="simple-filters">
            <h2>Filtres simples</h2>
            <p>
  {if $keep_ok}            <label for="show-ok">
                <input type="checkbox" id="show-ok" name="show-ok" />
                <img src="img/tick.png" alt="" /> Lignes non modifiées
              </label><br />{/if}
              <label for="show-edit">
                <input type="checkbox" id="show-edit" name="show-edit" checked="checked" />
                <img src="img/pencil.png" alt="" /> Modification du texte uniquement
              </label><br />
              <label for="show-timing">
                <input type="checkbox" id="show-timing" name="show-timing" checked="checked" />
                <img src="img/time.png" alt="" /> Modification du timing uniquement
              </label><br />
              <label for="show-ins">
                <input type="checkbox" id="show-ins" name="show-ins" checked="checked" />
                <img src="img/add.png" alt="" /> Lignes ajoutées
              </label><br />
              <label for="show-del">
                <input type="checkbox" id="show-del" name="show-del" checked="checked" />
                <img src="img/cancel.png" alt="" /> Lignes supprimées
              </label><br />
              <label for="show-unk">
                <input type="checkbox" id="show-unk" name="show-unk" checked="checked" />
                <img src="img/clock_edit.png" alt="" /> Texte et timing modifiés
              </label>
            </p>
            <h2>Filtres avancés</h2>
            <p>
              <label for="keep-commented">
                <input type="checkbox" id="keep-commented" name="keep-commented" checked="checked" />
                Toujours afficher les lignes commentées
              </label><br />
              <label for="keep-discussed">
                <input type="checkbox" id="keep-discussed" name="keep-discussed" checked="checked" />
                Toujours afficher les lignes discutées
              </label><br />
              <label for="keep-modified">
                <input type="checkbox" id="keep-modified" name="keep-modified" checked="checked" />
                Toujours afficher les lignes modifiées
              </label> 
            </p>
          </div>
          <div class="other-options">
            <h2>Autres options d'affichage</h2>          
            <p>
              Afficher sur : 
              <label for="mode-diff1"><input type="radio" id="mode-diff1" name="mode-diff" value="1" /> une ligne</label>,
              <label for="mode-diff2"><input type="radio" id="mode-diff2" name="mode-diff" value="2" checked="checked" /> deux lignes</label><br />
              <label for="show-graph"><input type="checkbox" id="show-graph" value="1" checked="checked" /> Afficher le graphique</label><br />
              <label for="show-id"><input type="checkbox" id="show-id" value="1" /> Afficher les ID des lignes</label><br />
              <label for="show-cp"><input type="checkbox" id="show-cp" value="1" /> Afficher le nombre de caractères</label><br />
              <label for="show-rs"><input type="checkbox" id="show-rs" value="1" checked="checked" /> Afficher les changements de Reading Speed</label><br />{if $comparo.idvo}
              <label for="show-vo"><input type="checkbox" id="show-vo" value="1" checked="checked" /> Afficher la VO</label><br />{/if}
              <label for="no-diff"><input type="checkbox" id="no-diff" value="1" /> Ne pas afficher les différences des textes</label>
            </p>
            <h2>Options de discussions</h2>
            <p id="option-discuss">
              <label for="pseudo">Pseudo affiché : <input type="text" id="pseudo" name="pseudo" class="inputtext" /></label>
            </p>
          </div>
          <span class="clear"></span>
        </div>
        <span class="clear"></span>
        <div id="graph"></div>
        <form action="#" method="post">
          <p id="selection" style="display:none"></p>  
{if !$keep_tags}        <p id="ignoretags"><img src="img/error.png" width="16" height="16" alt="" /> Les tags des sous-titres ont été ignorés.</p>{/if}
<ul id="liste-differences">
{foreach from=$lines item=line key=id}
<li class="line-{$line.mode}">
<span class="timings">
<span class="show">{$line.timing}&nbsp;</span>
{if $line.rs1_display || $line.rs2_display}
<span class="rs">
<span class="rs_{$line.rs1_display.class}">{$line.rs1_display.text}</span>
<span class="fleche">&rarr;</span>
<span class="rs_{$line.rs2_display.class}">{$line.rs2_display.text}</span>
</span>
{/if}
</span>
{if $line.vo}<span class="vo">{$line.vo|convert}</span>{/if}
<span class="text">
{if $line.cpl1}<span class="cpl_nonrelu{if $line.cpl1_max > 36} {if $line.cpl1_max > 40}cpl_red{else}cpl_orange{/if}{/if}">{$line.cpl1}</span>{/if}
{if $line.cpl2}<span class="cpl_relu{if $line.cpl2_max > 36} {if $line.cpl2_max > 40}cpl_red{else}cpl_orange{/if}{/if}">{$line.cpl2}</span>{/if}
{if $line.ids_nonrelu != ""}<span class="id_nonrelu">{$line.ids_nonrelu}</span>{/if}
{if $line.ids_relu != ""}<span class="id_relu">{$line.ids_relu}</span>{/if}
{$line.text}
</span>
</li>
{/foreach}      
</ul>
        </form>
        <div style="display:none" id="thickbox"><span class="copytext"></span></div>
      </div>
{include file="footer.tpl"}