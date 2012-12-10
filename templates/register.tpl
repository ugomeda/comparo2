{include file="header.tpl"}
      <div id="header">
        <div id="logo">
          <a class="logo" href="index.php">Comparo<span>2 <span>(beta)</span></span></a>
          <a href="index.php" id="new-comparo">Comparer des sous-titres &raquo;</a>
        </div>
        <div id="navigation-vide">
        </div>
      </div>
      <div id="content">
        <div id="panels">
        <form action="register.php" method="post">
            <div id="newcomparo">
              <h1>S'enregistrer</h1>
              {if $error}
              <p class="error">
                {$error}
              </p>
              {/if}
              {if $ok}
              <p class="ok">
                {$ok}
              </p>
              {/if}
              <p>
                <label for="username" class="label">Nom d'utilisateur</label>
                <input type="text" class="input" name="username" id="username" value="{$username}" />
                <br />
                <label for="password_1" class="label">Mot de passe</label>
                <input type="password" class="input" name="password_1" id="password_1" />
                <br />
                <label for="password_2" class="label">Mot de passe (confirmer)</label>
                <input type="password" class="input" name="password_2" id="password_2" />
                <br /><br />
              </p>
              <p>
                <input type="submit" value="S'enregistrer" name="register" class="sendbutton" />
              </p>
              </p>
            </div>
        </form>
        </div>
      
        <div style="height: 20px;"></div>
      </div>
{include file="footer.tpl"}