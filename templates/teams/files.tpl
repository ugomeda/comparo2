{include file="teams/header_teams.tpl"}
      <div id="content">
{assign var="menu" value="files"}
        <div class="team-content dashboard">
{include file="teams/menusubtitle.tpl"}
          <h1>Fichiers</h1>
{include file="teams/show_infos.tpl"}

          <form action="files.php" enctype="multipart/form-data" method="post">
            <h3>Ajouter un fichier</h3>
            <div class="selectForm">
              <label for="file">Fichier :</label>
              <input type="file" id="file" name="file" />
            </div>
            <div class="selectForm">
              <label for="section">Section :</label>
              {html_options id=section name=section options=$sub.steps}
            </div>
          </form>
        </div>
        <span class="clear"></span>
      </div>
{include file="footer.tpl"}