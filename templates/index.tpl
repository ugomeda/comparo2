{include file="header.tpl"}
      <div id="header">
        <div id="logo">
          <a class="logo" href="index.php">Comparo<span>2</span></a>
        </div>
        <div id="navigation">
          <ul>
            <li class="left linked selected" id="menu-newcomparo"><span><a href="#newcomparo">Créer un Comparo</a></span></li>
            <li class="left linked" id="menu-newgroup"><span><a href="#newgroup">Créer un groupe</a></span></li>
          </ul>
        </div>
      </div>
      <div id="content">
        <div id="panels">
          <form id="sendfiles" action="comparer.php" method="post" enctype="multipart/form-data">
            <div id="newcomparo">
              <h1>Comparer des sous-titres</h1>
              <p>
                <label for="subtitleOriginal" class="label">Sous-titre non relu</label>
                <input type="file" class="file input" name="subtitleOriginal" id="subtitleOriginal" />
                <br />
                <label for="subtitleEdited" class="label">Sous-titre relu</label>
                <input type="file" class="file input" name="subtitleEdited" id="subtitleEdited" />
              </p>
              <span id="switch-options">Options</span>
              <p id="options">
                <label for="subtitleVO" class="label">Version originale</label>
                <input type="file" class="file input" name="subtitleVO" id="subtitleVO" />
                <br />
                <label for="scenechange" class="label">Fichier .scenechange</label>
                <input type="file" class="file input" name="scenechange" id="scenechange" />
                <br />
                <label for="charset" class="label">Encodage des caractères</label>
                <select name="charset" id="charset" class="input">
                  <option value="0">Windows-1252</option>
                  <option value="1">ISO-8859-15</option>
                  <option value="2">UTF-8</option>
                </select>
                <br />
                <label for="keepOk">
                  <input type="checkbox" name="keepOk" value="1" id="keepOk" />
                  Afficher les lignes non modifiées
                </label>
                <br />
                <label for="noTags">
                  <input type="checkbox" name="noTags" value="1" id="noTags" />
                  Ignorer les tags
                </label>
                <br />
                <label for="highTolerance">
                  <input type="checkbox" name="highTolerance" value="1" id="highTolerance" />
                  Haute tolérance sur les timings
                </label>
              </p>
              <p>
                <input type="submit" value="Comparer !" class="sendbutton" />
              </p>
            </div>
          </form>
          <form action="actiongroup.php" method="post">
            <div id="newgroup">
              <h1>Créer un groupe</h1>
              <p>
                <label for="groupName" class="label">Nom du groupe</label>
                <input type="text" name="groupName" id="groupName" class="input" />
                <br /><br />
              </p>
              <p>
                <input type="submit" value="Créer !" class="sendbutton" name="createGroup" />
              </p>
            </div>
          </form>
        </div>
      </div>
{include file="footer.tpl"}